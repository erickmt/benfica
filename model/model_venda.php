<?php

/**
 * Model_Venda
 *
 * Interações com a tabela Venda
 */

class Model_Venda
{
    
    private $conexao;
    
    function Model_Venda($conexao)
    {
        $this->conexao = $conexao;
    }
    
    function buscarLojas($todas)
    {
        $listaLoja = array();
        
        $sql = " SELECT 
                       id, descricao
                     FROM
                       loja ";
        
        if ($todas == 1) {
            $sql = $sql . "where id > 0 ";
        }
        
        $sql = $sql . "ORDER BY 2; ";
        
        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return $listaLoja;
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return $listaLoja;
        
        while ($linha = mysqli_fetch_array($resultado)) {
            $linha['descricao'] = strtoupper($linha['descricao']);
            //$linha['nome'] = ucwords($linha['nome']);
            
            $item        = array(
                'id_loja' => $linha['id'],
                'descricao' => $linha['descricao']
            );
            $listaLoja[] = $item;
        }
        
        // Retorna a lista de lojas
        return $listaLoja;
    }
    
    function gravarPedido($pedidoTiny, $id_cliente, $total, $resposta, $naoEmitir)
    {
        if (isset($resposta["retorno"]['registros']['registro']['numero']))
            $id_tiny = $resposta["retorno"]['registros']['registro']['numero'];
        else if(isset($resposta['retorno']['notas_fiscais'][0]['nota_fiscal']['numero']))
            $id_tiny = $resposta['retorno']['notas_fiscais'][0]['nota_fiscal']['numero'];
        else
            return array(
                'indicador_erro' => 2,
                'dados' => null
            );
        
        $id_venda     = $pedidoTiny['numero_ordem_compra'];
        $nome_cliente = $pedidoTiny['cliente']['nome'];
        
        $retorno = "Processada";
        if ($resposta['retorno']['status_processamento'] == 1)
            $retorno = "Solicitação não processada";
        if ($resposta['retorno']['status_processamento'] == 2)
            $retorno = "Solicitação processada, mas possui erros de validação";
        $situcao = $resposta['retorno']['status'];
        
        $sql = "INSERT INTO `pedido`
                    (`id_tiny`,
                    `id_venda`,
                    `id_cliente`,
                    `nome_cliente`,
                    `total_venda`,
                    `situacao`,
                    `retorno`,
                    `data_pedido`,
                    `naoEmitir`)
                    VALUES
                    (" . $id_tiny . ",
                    " . $id_venda . ",
                    " . $id_cliente . ",
                    '" . $nome_cliente . "',
                    " . $total . ",
                    '" . $situcao . "',
                    '" . $retorno . "',
                    now(),
                    " . $naoEmitir . ");";

        $resultado = $this->conexao->query($sql);
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => "Erro ao gravar pedido no banco de dados"
            );
        
        return array(
            'retorno' => 'sucesso',
            'dados' => "Pedido gravado com sucesso"
        );
    }
    
    
    function listarPedidos($nome = false, $id_venda = false, $dataInicial = false, $dataFinal = false, $lojaBusca, $situacao, $idPagamento, $multiplasFormas)
    {
        //Monta e executa a query
        $sql = "SELECT 
                      IFNULL(id_tiny, 0) id_tiny,
                      v.id_venda id_venda,
                      v.id_loja id_loja,
                      c.nome nome_cliente,
                      v.valor_total_pago total_venda,
                      LEFT(DATE_FORMAT(v.dta_venda, '%d-%m-%Y'),
                          10) data_pedido,
                      ifnull(p.situacao, 'Ok') situacao,
                      ifnull(p.retorno, 'Sem Pedido') retorno,
                      ifnull(id_nota_tiny, '') nota,
                      ifnull(link, '') link,
                      case when emitida = 1 then 'Sim' else 'Não' end as emitida
                  FROM
                      venda v
                          LEFT JOIN
                      cliente c USING (id_cliente)
                          LEFT JOIN
                      pedido p USING (id_venda)
                  where case WHEN " . $situacao . " in (0,1) then ifnull(emitida,0) = " . $situacao . " else ifnull(emitida,0) in (0,1) end AND id_venda
                  and (v.dta_cancelamento_venda IS NULL
                                  OR v.dta_cancelamento_venda = '0000-00-00') ";
        
        if ($lojaBusca > 0) {
            $sql = $sql . " and v.id_loja = " . $lojaBusca . " ";
        }
        
        if ($dataInicial != NULL && $dataFinal != null) {
            $sql = $sql . " and LEFT(v.dta_venda,10) BETWEEN '" . $dataInicial . "' and '" . $dataFinal . "' ";
        }
        
        if ($nome != NULL) {
            $sql = $sql . " and c.nome LIKE '" . $nome . "%' ";
        }
        
        if ($id_venda != NULL) {
            $sql = $sql . " and v.id_venda = '" . $id_venda . "' ";
        }
        
        if ($multiplasFormas == 's') {
            $sql = $sql . " and (select count(*) from forma_pagamento_venda where id_venda = v.id_venda) > 1 ";
        }
        
        if ($multiplasFormas == 'n') {
            $sql = $sql . " and (select count(*) from forma_pagamento_venda where id_venda = v.id_venda) = 1 ";
        }
        
        if ($idPagamento != 0) {
            $sql = $sql . " and  " . $idPagamento . " in (select id_forma_pagamento from forma_pagamento_venda fp where fp.id_venda = v.id_venda)";
        }
        
        $sql = $sql . " order by 2 desc limit 200; ";
        
        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 0,
                'dados' => null
            );
        
        $retorno = array();
        while ($linha = mysqli_fetch_array($resultado)) {
            $dados     = array(
                'id_tiny' => $linha['id_tiny'],
                'id_venda' => $linha['id_venda'],
                'id_loja' => $linha['id_loja'],
                'nome_cliente' => strtoupper($linha['nome_cliente']),
                'total_venda' => $linha['total_venda'],
                'situacao' => $linha['situacao'],
                'retorno' => $linha['retorno'],
                'emitida' => $linha['emitida'],
                'nota' => $linha['nota'],
                'data_pedido' => $linha['data_pedido'],
                'link' => $linha['link']
            );
            $retorno[] = $dados;
        }
        
        return array(
            'indicador_erro' => 2,
            'dados' => $retorno
        );
    }
    
    
    function buscarNota($id_venda)
    {
        //Monta e executa a query
        $sql = "select p.id_tiny id_tiny, v.id_loja id_loja, p.id_nota id_nota, p.id_nota_tiny id_nota_tiny, p.emitida emitida, p.link link, p.naoEmitir naoEmitir from pedido p inner join venda v on p.id_venda = v.id_venda where p.id_venda =" . $id_venda . "; ";
        
        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 0,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        $linha                   = mysqli_fetch_array($resultado);
        $retorno['id_pedido']    = $linha['id_tiny'];
        $retorno['emitida']      = $linha['emitida'];
        $retorno['id_nota']      = $linha['id_nota'];
        $retorno['id_loja']      = $linha['id_loja'];
        $retorno['link']         = $linha['link'];
        $retorno['id_nota_tiny'] = $linha['id_nota_tiny'];
        $retorno['naoEmitir']    = $linha['naoEmitir'];
        
        return array(
            'indicador_erro' => 2,
            'dados' => $retorno
        );
    }
    
    
    function gravarNota($idPedido, $numero, $idNotaFiscal)
    {
        
        //Marca a venda como excluída na tabela de venda
        $sql       = "UPDATE pedido set id_nota = " . $idNotaFiscal . ", id_nota_tiny = " . $numero . " where id_tiny = " . $idPedido . ";";
        $resultado = $this->conexao->query($sql);
        if (!$resultado)
            return false;
        
        return true;
        
    }
    
    function gravarNotaFaturada($idPedido, $idNotaFiscal, $link)
    {
        
        //Marca a venda como excluída na tabela de venda
        $sql       = "UPDATE pedido set emitida = 1, link = '" . $link . "' where id_tiny = " . $idPedido . ";";
        $resultado = $this->conexao->query($sql);
        if (!$resultado)
            return false;
        
        return true;
        
    }
    
    function movimentaCaixa($valor, $desc, $lojaLogada)
    {
        $sql_query = "INSERT INTO `caixa` (`id_loja`, `valor`, `descricao`) VALUES (" . $lojaLogada . "," . $valor . ",'" . $desc . "');";
        $resultado = $this->conexao->query($sql_query);
    }
    
    function confereCaixa($lojaLogada)
    {
        $sql_query = "select id from caixa where left(data,10) = curdate() and descricao = 'Abertura de Caixa' and id_loja = " . $lojaLogada . ";";
        $resultado = $this->conexao->query($sql_query);
        if (mysqli_num_rows($resultado) == 0)
            return 'fechado';
        return 'aberto';
        
    }
    
    /**
     * gravarVenda
     * @author Victor
     */
    function gravarVenda($dados)
    {
        
        if ($dados['indicador_externo'] == 'S')
            $dados['indicador_externo'] = 0;
        else
            $dados['indicador_externo'] = 1;
        
        if ($dados['indicador_consignado'] == 'S')
            $dados['indicador_consignado'] = 0;
        else
            $dados['indicador_consignado'] = 1;
        
        if ($dados['taxa_pelo_cliente'] == 'S')
            $dados['taxa_pelo_cliente'] = 0;
        else
            $dados['taxa_pelo_cliente'] = 1;
        
        //Monta e executa a query
        $sql = " 
                insert into venda (

          id_cliente,
          id_loja,
                    id_vendedor, 
                    id_perfil, 
                    dta_venda, 
                    valor_total_pago, 
                    valor_total_comissao, 
                    valor_total_taxas, 
                    valor_total_outros, 
                    valor_total_liquido, 
          valor_credito_cliente,
                    indicador_externo, 
                    indicador_consignado, 
                    taxa_pelo_cliente)

                values 
                    (" . $dados['id_cliente'] . "," . $dados['lojaVenda'] . "," . $dados['id_vendedor'] . "," . $dados['id_perfil'] . ",
                       current_date ," . $dados['valor_total_pago'] . "," . $dados['valor_total_comissao'] . "," . $dados['valor_total_taxas'] . "," . $dados['valor_total_outros'] . "," . $dados['valor_total_liquido'] . "," . $dados['valor_credito_cliente'] . "," . $dados['indicador_externo'] . "," . $dados['indicador_consignado'] . "," . $dados['taxa_pelo_cliente'] . ");";
        
        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Retorna o dado quando não há erro
        if (!$resultado)
            return false;
        
        //Busca o id da venda criada
        $sql = " SELECT 
                       max(id_venda) as id_venda
                     FROM
                       venda
                      WHERE 
                          id_cliente = " . $dados['id_cliente'];
        
        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return false;
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return false;
        else {
            $linha = mysqli_fetch_array($resultado);
            return $linha['id_venda'];
        }
    }
    
    
    function buscarVendas($codigoVenda = false, $nomeCompleto = false, $cpf = false, $lojaLogada)
    {
        //Monta e executa a query
        $sql = "select ven.id_venda, ven.dta_venda ,cli.nome, cli.cpf
                        from venda ven, cliente cli
                        where ven.id_cliente = cli.id_cliente
                        and ( ven.dta_cancelamento_venda is null or ven.dta_cancelamento_venda = '0000-00-00' ) ";
        
        //Se o codigoVenda tiver sido enviado
        if ($codigoVenda != false) {
            //$nomeCompleto =  utf8_decode($nomeCompleto);
            $sql = $sql . " and ven.id_venda = " . $codigoVenda . " ";
        }
        
        //Se o nome tiver sido enviado
        if ($nomeCompleto != false) {
            $sql = $sql . " and cli.nome =  '" . $nomeCompleto . "' ";
        }
        
        //Se o cpf tiver sido enviado
        if ($cpf != false) {
            $sql = $sql . " AND cli.cpf = '" . $cpf . "'";
        }
        
        if ($lojaLogada != 0) {
            //$nomeCompleto =  utf8_decode($nomeCompleto);
            $sql = $sql . " and ven.id_loja = " . $lojaLogada . " ";
        }
        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 0,
                'dados' => null
            );
        
        //Encontrou uma ou mais vendas
        $retorno = array();
        while ($linha = mysqli_fetch_array($resultado)) {
            
            $linha['dta_venda'] = date('d/m/Y', strtotime($linha['dta_venda']));
            $dados              = array(
                'id_venda' => $linha['id_venda'],
                'dta_venda' => $linha['dta_venda'],
                'nome' => strtoupper(($linha['nome'])),
                'cpf' => $linha['cpf']
            );
            
            //Busca os produtos da venda em questão
            $sql_2 = "select pro.descricao, ite.quantidade
                from itens_de_venda ite, produto pro
                where ite.id_venda = " . $linha['id_venda'] . "
                and ite.id_produto = pro.id_produto";
            
            $resultado_2 = $this->conexao->query($sql_2);
            
            //Se retornar algum erro
            if (!$resultado_2)
                return array(
                    'indicador_erro' => 1,
                    'dados' => null
                );
            
            
            $dados['produtos'] = array();
            while ($linha_2 = mysqli_fetch_array($resultado_2)) {
                $itensVenda                 = array();
                $itensVenda['nome_produto'] = ($linha_2['descricao']);
                $itensVenda['quantidade']   = $linha_2['quantidade'];
                $dados['produtos'][]        = $itensVenda;
            }
            
            $retorno[] = $dados;
        }
        
        return array(
            'indicador_erro' => 0,
            'dados' => $retorno
        );
    }
    
    function buscarVendaNota($id_venda)
    {
        //Monta e executa a query
        $sql = "SELECT venda.id_loja, id_venda, cliente.id_cliente idCliente, cliente.nome, cliente.cep, cliente.cpf, cliente.logradouro, numero, cliente.bairro, cliente.cidade, cliente.estado, perfil.descricao perfil, cliente.email email, cliente.ie ie, dta_venda dia, cliente.telefone_01 telefone, vendedor.nome vendedor FROM venda left JOIN perfil_cliente perfil USING (id_perfil) left JOIN cliente USING (id_cliente) left join vendedor on vendedor.id_vendedor = venda.id_vendedor WHERE id_venda = " . $id_venda . ";";
        
        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 0,
                'dados' => null
            );
        
        $linha   = mysqli_fetch_array($resultado);
        $retorno = array();
        
        $retorno['id_loja']    = $linha['id_loja'];
        $retorno['id_venda']   = $linha['id_venda'];
        $retorno['nome']       = $linha['nome'];
        $retorno['perfil']     = $linha['perfil'];
        $retorno['email']      = $linha['email'];
        $retorno['ie']         = $linha['ie'];
        $retorno['telefone']   = $linha['telefone'];
        $retorno['cep']        = $linha['cep'];
        $retorno['cpf']        = $linha['cpf'];
        $retorno['logradouro'] = $linha['logradouro'];
        $retorno['numero']     = $linha['numero'];
        $retorno['bairro']     = $linha['bairro'];
        $retorno['cidade']     = $linha['cidade'];
        $retorno['estado']     = $linha['estado'];
        $retorno['idCliente']  = $linha['idCliente'];
        $retorno['vendedor']   = $linha['vendedor'];
        $retorno['dia']        = date('d/m/Y', strtotime($linha['dia']));
        
        $sql = "select produto.descricao nomeProduto, id_produto, case WHEN nf_descricao = 0 || nf_descricao is null THEN produto.descricao else ncm.ncm end as nf_descricao, quantidade, round(preco_venda / quantidade, 2) valorUnitario, preco_venda valorTotal from itens_de_venda left join produto using (id_produto) left join ncm on nf_descricao = id_ncm where id_venda = " . $id_venda . " and nf_descricao > 0 group by 1 ;";
        
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 2,
                'dados' => null
            );
        
        //Encontrou uma ou mais vendas
        $dadosItens  = array();
        $itens_venda = array();
        while ($linha = mysqli_fetch_array($resultado)) {
            $dadosItens['nomeProduto']   = $linha['nomeProduto'];
            $dadosItens['id_produto']    = $linha['id_produto'];
            $dadosItens['nf_descricao']  = $linha['nf_descricao'];
            $dadosItens['quantidade']    = $linha['quantidade'];
            $dadosItens['valorUnitario'] = $linha['valorUnitario'];
            $dadosItens['valorTotal']    = $linha['valorTotal'];
            $itens_venda[]               = $dadosItens;
        }
        
        $sql = "SELECT valor_total_outros, valor_total_taxas, (SELECT SUM(preco_venda) FROM itens_de_venda iv WHERE iv.id_venda = v.id_venda) AS valorTotalProdutos, valor_credito_cliente valorCredito FROM venda v WHERE id_venda = " . $id_venda . ";";
        
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 0,
                'dados' => null
            );
        
        $linha                         = mysqli_fetch_array($resultado);
        //Encontrou uma ou mais vendas
        $retorno['valor_total_outros'] = $linha['valor_total_outros'];
        $retorno['valor_total_taxas']  = $linha['valor_total_taxas'];
        $retorno['valorTotalProdutos'] = $linha['valorTotalProdutos'];
        $retorno['valorCredito']       = $linha['valorCredito'];
        $retorno['totalVenda']         = $linha['valorTotalProdutos'] - $linha['valorCredito'];
        
        
        $sql = "select id_forma_pagamento id_forma, porcentagem_taxa taxa, descricao nome_forma, quantidade_parcela, valor_pago valor from forma_pagamento_venda inner join forma_pagamento using(id_forma_pagamento) where id_venda = " . $id_venda . ";";
        
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        
        //Encontrou uma ou mais vendas
        $dadosPagamento = array();
        while ($linha = mysqli_fetch_array($resultado)) {
            $dadosPagamento['id_forma']           = $linha['id_forma'];
            $dadosPagamento['nome_forma']         = $linha['nome_forma'];
            $dadosPagamento['valor']              = $linha['valor'];
            $dadosPagamento['quantidade_parcela'] = $linha['quantidade_parcela'];
            $dadosPagamento['taxa']               = $linha['taxa'];
            $formas_pagamento[]                   = $dadosPagamento;
        }
        
        $retorno['itens_venda'] = $itens_venda;
        if (isset($formas_pagamento))
            $retorno['formas_pagamento'] = $formas_pagamento;
        
        return array(
            "retorno" => "sucesso",
            'indicador_erro' => 0,
            'dados' => $retorno
        );
    }
    
    
    function buscarVendaImpressao($id_venda)
    {
        //Monta e executa a query
        $sql = "SELECT venda.id_loja, id_venda, cliente.id_cliente idCliente, cliente.nome, cliente.cep, cliente.cpf, cliente.logradouro, numero, cliente.bairro, cliente.cidade, cliente.estado, perfil.descricao perfil, cliente.email email, cliente.ie ie, dta_venda dia, cliente.telefone_01 telefone, vendedor.nome vendedor FROM venda left JOIN perfil_cliente perfil USING (id_perfil) left JOIN cliente USING (id_cliente) left join vendedor on vendedor.id_vendedor = venda.id_vendedor WHERE id_venda = " . $id_venda . ";";
        
        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 0,
                'dados' => null
            );
        
        $linha   = mysqli_fetch_array($resultado);
        $retorno = array();
        
        $retorno['id_loja']    = $linha['id_loja'];
        $retorno['id_venda']   = $linha['id_venda'];
        $retorno['nome']       = $linha['nome'];
        $retorno['perfil']     = $linha['perfil'];
        $retorno['email']      = $linha['email'];
        $retorno['ie']         = $linha['ie'];
        $retorno['telefone']   = $linha['telefone'];
        $retorno['cep']        = $linha['cep'];
        $retorno['cpf']        = $linha['cpf'];
        $retorno['logradouro'] = $linha['logradouro'];
        $retorno['numero']     = $linha['numero'];
        $retorno['bairro']     = $linha['bairro'];
        $retorno['cidade']     = $linha['cidade'];
        $retorno['estado']     = $linha['estado'];
        $retorno['idCliente']  = $linha['idCliente'];
        $retorno['vendedor']   = $linha['vendedor'];
        $retorno['dia']        = date('d/m/Y', strtotime($linha['dia']));
        
        $sql = "select produto.descricao nomeProduto, id_produto, case WHEN nf_descricao = 0 || nf_descricao is null THEN produto.descricao else ncm.ncm end as nf_descricao, quantidade, round(preco_venda / quantidade, 2) valorUnitario, preco_venda valorTotal from itens_de_venda left join produto using (id_produto) left join ncm on nf_descricao = id_ncm where id_venda = " . $id_venda . " group by 1 ;";
        
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 2,
                'dados' => null
            );
        
        //Encontrou uma ou mais vendas
        $dadosItens  = array();
        $itens_venda = array();
        while ($linha = mysqli_fetch_array($resultado)) {
            $dadosItens['nomeProduto']   = $linha['nomeProduto'];
            $dadosItens['id_produto']    = $linha['id_produto'];
            $dadosItens['nf_descricao']  = $linha['nf_descricao'];
            $dadosItens['quantidade']    = $linha['quantidade'];
            $dadosItens['valorUnitario'] = $linha['valorUnitario'];
            $dadosItens['valorTotal']    = $linha['valorTotal'];
            $itens_venda[]               = $dadosItens;
        }
        
        $sql = "SELECT valor_total_outros, valor_total_taxas, (SELECT SUM(preco_venda) FROM itens_de_venda iv WHERE iv.id_venda = v.id_venda) AS valorTotalProdutos, valor_credito_cliente valorCredito FROM venda v WHERE id_venda = " . $id_venda . ";";
        
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 0,
                'dados' => null
            );
        
        $linha                         = mysqli_fetch_array($resultado);
        //Encontrou uma ou mais vendas
        $retorno['valor_total_outros'] = $linha['valor_total_outros'];
        $retorno['valor_total_taxas']  = $linha['valor_total_taxas'];
        $retorno['valorTotalProdutos'] = $linha['valorTotalProdutos'];
        $retorno['valorCredito']       = $linha['valorCredito'];
        $retorno['totalVenda']         = $linha['valorTotalProdutos'] - $linha['valorCredito'];
        
        
        $sql = "select descricao nome_forma, quantidade_parcela, valor_pago valor from forma_pagamento_venda inner join forma_pagamento using(id_forma_pagamento) where id_venda = " . $id_venda . ";";
        
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        
        //Encontrou uma ou mais vendas
        $dadosPagamento = array();
        while ($linha = mysqli_fetch_array($resultado)) {
            
            $dadosPagamento['nome_forma']          = $linha['nome_forma'];
            $dadosPagamento['valor']               = $linha['valor'];
            $dadosPagamento['quantidade_parcelas'] = $linha['quantidade_parcela'];
            $formas_pagamento[]                    = $dadosPagamento;
        }
        
        $retorno['itens_venda'] = $itens_venda;
        if (isset($formas_pagamento))
            $retorno['formas_pagamento'] = $formas_pagamento;

        $sql = "SELECT descricao, descricao_nota, contato_nota, telefone_nota  FROM `loja` WHERE id = ".$retorno['id_loja'].";";
    
        $resultado = $this->conexao->query($sql);

        //Se retornar alguma linha
        if ($resultado && mysqli_num_rows($resultado) > 0)
        {   
            $linha = mysqli_fetch_array($resultado);

            $retorno['nome_loja']       = $linha['descricao'];
            $retorno['descricao_nota']  = nl2br($linha['descricao_nota']);
            $retorno['contato_nota']    = nl2br($linha['contato_nota']);
            $retorno['telefone_nota']   = $linha['telefone_nota'];
        };

        return array(
            "retorno" => "sucesso",
            'indicador_erro' => 0,
            'dados' => $retorno
        );
    }
    
    function buscarTokenTiny($idLoja){
        $sql = "select token_tiny from loja where id = ".$idLoja.";";

        $resultado = $this->conexao->query($sql);

        //Se retornar algum erro
        if (!$resultado)
        return array(
            "resultado" => "erro",
            'indicador_erro' => 1,
            'dados' => "Token da loja não encontrada"
        );

        $linha = mysqli_fetch_array($resultado);

        $retorno = $linha['token_tiny'];

        return $retorno;

    }
    

    function inserirProdutosOrcamento($produtos, $cliente, $idOrcamento){

    }


    function buscarDadosNotaLoja($idLoja){

        $sql = "select descricao, descricao_nota, contato_nota, telefone_nota FROM loja where id = ".$idLoja.";";
        
        $resultado = $this->conexao->query($sql);


        //Se retornar algum erro
        if (!$resultado)
            return false;

        $linha = mysqli_fetch_array($resultado);

        $retorno['nome_loja']       = $linha['descricao'];
        $retorno['descricao_nota']  = nl2br($linha['descricao_nota']);
        $retorno['contato_nota']    = nl2br($linha['contato_nota']);
        $retorno['telefone_nota']   = $linha['telefone_nota'];

        return array(
            "resultado" => "sucesso",
            'indicador_erro' => 0,
            'dados' => $retorno
        );
    }

    function atualizaDadosNotaLoja($idLoja, $contato, $descricao){

        $sql = "update loja set contato_nota = '".$contato."', descricao_nota = '".$descricao."' where id = ".$idLoja.";";

        $resultado = $this->conexao->query($sql);

        if(!$resultado)
            return array("resultado" => "erro", "indicador_erro" => 1, "dados" => "Erro ao atualizado dados da nota");
        else
            return array("resultado" => "sucesso", "indicador_erro" => 1, "dados" => "Dados atualizados com sucesso");
    }



    /**
     * marcarVendaExcluida
     * @author Victor
     */
    function marcarVendaExcluida($idVenda)
    {
        
        //Marca a venda como excluída na tabela de venda
        $sql       = "UPDATE venda set dta_cancelamento_venda = current_date where id_venda = " . $idVenda . ";";
        $resultado = $this->conexao->query($sql);
        
        if (!$resultado)
            return false;
        
        //Busca os produtos da venda em questão
        $sql = "select id_produto, quantidade
          from itens_de_venda
          where id_venda = " . $idVenda;
        
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return false;
        
        $retorno = array();
        while ($linha = mysqli_fetch_array($resultado)) {
            $itensVenda               = array();
            $itensVenda['id_produto'] = $linha['id_produto'];
            $itensVenda['quantidade'] = $linha['quantidade'];
            $retorno[]                = $itensVenda;
        }
        
        // Retorna os produtos
        return $retorno;
    }
    
    
    
    
    
    /**
     * buscarUltimaVendaCliente
     * @author Victor
     */
    function buscarUltimaVendaCliente($idVenda)
    {
        
        
        //Primeiro, busca o cliente da venda em questão
        $sql       = " SELECT 
                           id_cliente
                         FROM
                           venda
                         WHERE 
                           id_venda    = " . $idVenda;
        $resultado = $this->conexao->query($sql);
        if (!$resultado)
            return false;
        $linha = mysqli_fetch_array($resultado);
        
        
        //Busca a ultima venda do cliente
        
        $sql       = " SELECT 
                           dta_venda
                         FROM
                           venda
                         WHERE 
                           id_cliente    = " . $linha['id_cliente'] . "
                           and (dta_cancelamento_venda is null or dta_cancelamento_venda = '0000-00-00')
                         ORDER BY dta_venda DESC";
        $resultado = $this->conexao->query($sql);
        if (!$resultado)
            return array(
                'idCliente' => $linha['id_cliente'],
                'data' => false
            );
        
        //Se não retornar nenhuma venda antiga
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'idCliente' => $linha['id_cliente'],
                'data' => false
            );
        
        $linha2 = mysqli_fetch_array($resultado);
        return array(
            'idCliente' => $linha['id_cliente'],
            'data' => $linha2['dta_venda']
        );
    }
    
    
    
    /**
     * buscarCreditoUtilizadoNaVenda
     * @author Victor
     */
    function buscarCreditoUtilizadoNaVenda($idVenda)
    {
        
        
        //Primeiro, busca o cliente da venda em questão
        $sql       = " SELECT 
                           valor_credito_cliente
                         FROM
                           venda
                         WHERE 
                           id_venda    = " . $idVenda;
        $resultado = $this->conexao->query($sql);
        if (!$resultado)
            return false;
        $linha = mysqli_fetch_array($resultado);
        return $linha['valor_credito_cliente'];
    }
    
    /**
     * indicaVendaPosterior
     * @author Victor
     */
    function indicaVendaPosterior($idVenda)
    {
        
        
        //Primeiro, busca o cliente da venda em questão
        $sql       = " SELECT 
                           count(*) as contador
                         FROM
                           venda
                         WHERE 
                           id_venda    > " . $idVenda;
        $resultado = $this->conexao->query($sql);
        if (!$resultado)
            return false;
        
        $linha = mysqli_fetch_array($resultado);
        if ($linha['contador'] == 0)
            return false;
        else
            return true;
    }
    
    
    
    function buscarVendasComissao($idVendedor, $mes, $ano, $lojaBusca, $idPagamento)
    {
        
        //Monta e executa a query
        $sql = "select 
                          lj.descricao lj_venda, ven.id_venda, ven.dta_venda ,cli.nome, cli.numero_rg, ven.valor_total_pago,
                          (ven.valor_total_pago - valor_total_taxas - valor_total_outros) valor_total_venda, ven.valor_total_comissao,
                          case when ven.indicador_externo = 0 then concat(per.descricao, ' - Externo') else per.descricao end as per_desc
                        from
                          venda ven
                        left join cliente cli 
                          on ven.id_cliente = cli.id_cliente
                        left join
                          perfil_cliente per on ven.id_perfil = per.id_perfil
                        left join
                          loja lj on lj.id = ven.id_loja
                          where 
                          ven.dta_cancelamento_venda is null or ven.dta_cancelamento_venda = '0000-00-00'
                          and ven.id_vendedor = " . $idVendedor . "
                          and month(ven.dta_venda) = '" . $mes . "' 
                          and year(ven.dta_venda) = '" . $ano . "'
                          and indicador_consignado = 1 ";
        
        if ($lojaBusca != 0) {
            $sql = $sql . " and ven.id_loja = " . $lojaBusca . " ";
        }
        
        if ($idPagamento != 0) {
            $sql = $sql . " and  " . $idPagamento . " in (select id_forma_pagamento from forma_pagamento_venda fp where fp.id_venda = ven.id_venda)";
        }
        
        $sql = $sql . " ORDER BY ven.id_venda asc; ";

        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 0,
                'dados' => null
            );
        
        //Encontrou uma ou mais vendas
        $retorno = array();
        while ($linha = mysqli_fetch_array($resultado)) {
            if ($linha['dta_venda'] > '2018-06-25') //data de alteração nas regras de cálculo
                $linha['valor_total_venda'] = $linha['valor_total_venda'] * 0.9;
            
            $dados = array(
                'lj_venda' => $linha['lj_venda'],
                'id_venda' => $linha['id_venda'],
                'dta_venda' => $linha['dta_venda'],
                'nome' => strtoupper(($linha['nome'])),
                'numero_rg' => $linha['numero_rg'],
                'valor_total_pago' => $linha['valor_total_venda'],
                'valor_total_comissao' => $linha['valor_total_comissao'],
                'per_desc' => $linha['per_desc']
            );
            
            $sql_2 = "SELECT 
                      id_venda, descricao
                  FROM
                      forma_pagamento_venda fv
                          INNER JOIN
                      forma_pagamento fp ON fv.id_forma_pagamento = fp.id_forma_pagamento
                  WHERE
                      id_venda = " . $linha['id_venda'];
            
            $resultado_2 = $this->conexao->query($sql_2);
            
            //Se retornar algum erro
            if (!$resultado_2)
                return array(
                    'indicador_erro' => 1,
                    'dados' => null
                );
            
            $dados['pagamentos'] = array();
            while ($linha_2 = mysqli_fetch_array($resultado_2)) {
                $pagamentosVenda              = array();
                $pagamentosVenda['descricao'] = ($linha_2['descricao']);
                $dados['pagamentos'][]        = $pagamentosVenda;
            }
            
            $retorno[] = $dados;
        }
        
        return array(
            'indicador_erro' => 2,
            'dados' => $retorno
        );
    }
    
    function buscarVendasResumidaComissao($idVendedor, $mes, $ano, $lojaBusca, $idPagamento)
    {
        
        //Monta e executa a query
        $sql = "SELECT 
          lj.descricao lj_venda, nome,
          SUM(ven.valor_total_pago - valor_total_taxas - valor_total_outros) total_venda,
          SUM(valor_total_comissao) comissao_total,
          IFNULL((SELECT 
                          SUM(valor_total_pago - valor_total_taxas - valor_total_outros) total_venda
                      FROM
                          venda v
                      WHERE
                          v.dta_cancelamento_venda IS NULL
                              OR v.dta_cancelamento_venda = '0000-00-00'
                              AND MONTH(v.dta_venda) = '" . $mes . "'
                              AND YEAR(v.dta_venda) = '" . $ano . "'
                              AND v.indicador_consignado = 1
                              AND id_perfil = 1
                              AND ven.id_vendedor = v.id_vendedor";
        if ($lojaBusca != 0 || $idVendedor != 0) {
            $sql = $sql . " and id_loja = ven.id_loja ";
        }
        
        $sql = $sql . "),
                  0) AS total_venda_varejo,
          IFNULL((SELECT 
                          SUM(valor_total_comissao)
                      FROM
                          venda v
                      WHERE
                          v.dta_cancelamento_venda IS NULL
                              OR v.dta_cancelamento_venda = '0000-00-00'
                              AND MONTH(v.dta_venda) = '" . $mes . "'
                              AND YEAR(v.dta_venda) = '" . $ano . "'
                              AND v.indicador_consignado = 1
                              AND v.id_perfil = 1
                              AND ven.id_vendedor = v.id_vendedor";
        if ($lojaBusca != 0 || $idVendedor != 0) {
            $sql = $sql . " and id_loja = ven.id_loja ";
        }
        
        $sql = $sql . "),
                  0) AS comissao_varejo,
          IFNULL((SELECT 
                          SUM(valor_total_pago - valor_total_taxas - valor_total_outros) total_venda
                      FROM
                          venda v
                      WHERE
                          v.dta_cancelamento_venda IS NULL
                              OR v.dta_cancelamento_venda = '0000-00-00'
                              AND MONTH(v.dta_venda) = '" . $mes . "'
                              AND YEAR(v.dta_venda) = '" . $ano . "'
                              AND v.indicador_consignado = 1
                              AND v.id_perfil = 2
                              AND ven.id_vendedor = v.id_vendedor";
        if ($lojaBusca != 0 || $idVendedor != 0) {
            $sql = $sql . " and id_loja = ven.id_loja ";
        }
        
        $sql = $sql . "),
                  0) AS total_venda_atacado,
          IFNULL((SELECT 
                          SUM(valor_total_comissao)
                      FROM
                          venda v
                      WHERE
                          v.dta_cancelamento_venda IS NULL
                              OR v.dta_cancelamento_venda = '0000-00-00'
                              AND MONTH(v.dta_venda) = '" . $mes . "'
                              AND YEAR(v.dta_venda) = '" . $ano . "'
                              AND v.indicador_consignado = 1
                              AND v.id_perfil = 2
                              AND ven.id_vendedor = v.id_vendedor";
        if ($lojaBusca != 0 || $idVendedor != 0) {
            $sql = $sql . " and id_loja = ven.id_loja ";
        }
        
        $sql = $sql . "),
                  0) AS comissao_atacado
      FROM
          venda ven
              LEFT JOIN
          vendedor vdd ON ven.id_vendedor = vdd.id_vendedor
              LEFT JOIN
          loja lj on lj.id = ven.id_loja
      WHERE
          ven.dta_cancelamento_venda IS NULL
              OR ven.dta_cancelamento_venda = '0000-00-00'
              AND MONTH(ven.dta_venda) = '" . $mes . "'
              AND YEAR(ven.dta_venda) = '" . $ano . "'
              AND indicador_consignado = 1 ";
        
        if ($idVendedor != 0) {
            $sql = $sql . " and ven.id_vendedor = " . $idVendedor . " ";
        }
        if ($lojaBusca != 0) {
            $sql = $sql . " and ven.id_loja = " . $lojaBusca . " ";
        }
        
        if ($idPagamento != 0) {
            $sql = $sql . " and  " . $idPagamento . " in (select id_forma_pagamento from forma_pagamento_venda fp where fp.id_venda = ven.id_venda)";
        }
        
        if ($idVendedor == 0 && $lojaBusca == 0) {
            $sql = $sql . " GROUP BY 2;";
        } else {
            $sql = $sql . " GROUP BY 1, 2;";
        }
              
        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 0,
                'dados' => null
            );

        //Encontrou uma ou mais vendas
        $retorno = array();
        while ($linha = mysqli_fetch_array($resultado)) {
            $linha['total_venda']         = $linha['total_venda'] * 0.9;
            $linha['total_venda_varejo']  = $linha['total_venda_varejo'] * 0.9;
            $linha['total_venda_atacado'] = $linha['total_venda_atacado'] * 0.9;
            
            $dados = array(
                'lj_venda' => $linha['lj_venda'],
                'nome' => strtoupper(($linha['nome'])),
                'total_venda' => $linha['total_venda'],
                'comissao_total' => $linha['comissao_total'],
                'total_venda_varejo' => $linha['total_venda_varejo'],
                'comissao_varejo' => $linha['comissao_varejo'],
                'total_venda_atacado' => $linha['total_venda_atacado'],
                'comissao_atacado' => $linha['comissao_atacado']
            );
            
            $retorno[] = $dados;
        }
        
        return array(
            'indicador_erro' => 2,
            'dados' => $retorno
        );
    }
    
    function buscarVendasPorFormaPagamento($dataInicial, $dataFinal, $lojaBusca)
    {
        //Monta e executa a query
        $sql = "select
                    c.id_loja,
                    b.descricao,
                    sum(a.valor_pago) as valor
                  from
                    forma_pagamento_venda a,
                    forma_pagamento b,
                    venda c
                  where 
                    a.id_forma_pagamento = b.id_forma_pagamento
                    and a.id_venda = c.id_venda
                    and (c.dta_cancelamento_venda = '0000-00-00' or c.dta_cancelamento_venda is null)
                    and c.dta_venda between '" . $dataInicial . "' and '" . $dataFinal . "'
                    and indicador_consignado = 1 ";
        
        if ($lojaBusca > 0) {
            $sql = $sql . "and c.id_loja = " . $lojaBusca . " group by c.id_loja, b.descricao ORDER BY b.descricao;";
        } else {
            $sql = $sql . "group by b.descricao ORDER BY b.descricao;";
        }
        
        
        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Encontrou uma ou mais vendas
        $retorno = array();
        $valor   = 0;
        while ($linha = mysqli_fetch_array($resultado)) {
            
            // Tratamento dos valores retornados do banco
            $linha['descricao'] = $this->removeAcentos(($linha['descricao']));
            
            $linha['descricao'] = strtolower($linha['descricao']);
            $linha['descricao'] = ucwords($linha['descricao']);
            
            $dados     = array(
                'descricao' => $linha['descricao'],
                'valor' => number_format($linha['valor'], 2, ',', '.')
            );
            $retorno[] = $dados;
            $valor     = $valor + $linha['valor'];
        }
        
        $valor     = number_format($valor, 2, ',', '.');
        $retorno[] = array(
            'descricao' => 'TOTAL',
            'valor' => $valor
        );
        
        return array(
            'indicador_erro' => 0,
            'dados' => $retorno
        );
    }
    
    function buscarHistoricoCliente($idCliente)
    {
      //Monta e executa a query
      $sql = "SELECT 
          CONCAT(v.id_cliente, ' - ', c.nome) cliente, c.cpf cpf, v.id_venda id_venda, vd.nome vendedor, l.descricao loja, v.id_venda id_venda, DATE_FORMAT(v.dta_venda, '%d/%m/%Y') dta_venda, v.valor_total_pago valor
      FROM
          venda v
              INNER JOIN
          cliente c USING (id_cliente)
              INNER JOIN
          vendedor vd ON v.id_vendedor = vd.id_vendedor
          INNER JOIN
        loja l on l.id = v.id_loja
      WHERE
          (v.dta_cancelamento_venda = '0000-00-00'
                OR v.dta_cancelamento_venda IS NULL)
          AND id_cliente = ".$idCliente.";" ;     
      
      //Executa a query
      $resultado = $this->conexao->query($sql);
      
      //Se retornar algum erro
      if (!$resultado)
          return array(
              'indicador_erro' => 1,
              'dados' => null
          );
      
      //Se não retornar nenhuma linha
      if (mysqli_num_rows($resultado) == 0)
          return array(
              'indicador_erro' => 2,
              'dados' => null
          );
      
      //Encontrou uma ou mais vendas
      $retorno = array();

      while ($linha = mysqli_fetch_array($resultado)) {

          $dados     = array(
              'cliente' => $linha['cliente'],
              'cpf' => $linha['cpf'],
              'loja' => $linha['loja'],
              'id_venda' => $linha['id_venda'],
              'vendedor' => $linha['vendedor'],
              'dta_venda' => $linha['dta_venda'],
              'valor' => number_format($linha['valor'], 2, ',', '.')
          );
         
          $retorno[] = $dados;
      }
      
      return array('indicador_erro' => 0, 'dados' => $retorno);
    }
    
    
    function buscarPecas($tipoProduto, $dataInicial, $dataFinal, $produto, $lojaBusca, $tipoRelatorio)
    {
        $sql = "
            SELECT
            c.id_loja,
            c.dta_venda data,
            a.id_produto AS cod,
            b.descricao AS descricao,
            d.descricao AS desc_tipo,
            COUNT(a.quantidade) AS valor,
            b.quantidade_estoque AS estoque
        FROM
            venda c,
            itens_de_venda a,
            produto b,
            tipo_produto d
        WHERE
            c.dta_venda BETWEEN '" . $dataInicial . "' AND '" . $dataFinal . "'
                AND b.id_produto = a.id_produto
                AND a.id_venda = c.id_venda
                AND d.id_numero_produto = b.id_tipo_produto
                AND (c.dta_cancelamento_venda = '0000-00-00'
                OR c.dta_cancelamento_venda IS NULL)
                AND indicador_consignado = 1 ";
        
        if ($produto > 0) {
            $sql = $sql . " and a.id_produto in (" . $produto . ") ";
        } else {
            if ($tipoProduto != 0) {
                $sql = $sql . "and d.id_numero_produto = '" . $tipoProduto . "' ";
            }
        }
        
        if ($tipoRelatorio == 1) {
            if ($lojaBusca > 0) {
                $sql = $sql . " and c.id_loja = " . $lojaBusca . " GROUP BY 1, 2, a.id_produto order by 2, 6 desc;";
            } else {
                $sql = $sql . " GROUP BY 2, a.id_produto order by 2, 6 desc;";
            }
        } else {
            if ($lojaBusca > 0) {
                $sql = $sql . " and c.id_loja = " . $lojaBusca . " GROUP BY 1, a.id_produto order by 2, 6 desc;";
            } else {
                $sql = $sql . " GROUP BY a.id_produto order by 2, 6 desc;";
            }
        }
        
        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Encontrou uma ou mais vendas
        $retorno = array();
        $valor   = 0;
        $estoque = 0;
        while ($linha = mysqli_fetch_array($resultado)) {
            
            // Tratamento dos valores retornados do banco
            $linha['descricao'] = $this->removeAcentos(($linha['descricao']));
            
            $linha['descricao'] = ucwords($linha['descricao']);
            
            $dados     = array(
                'descricao' => $linha['descricao'],
                'valor' => $linha['valor'],
                'estoque' => $linha['estoque'],
                'desc_tipo' => $linha['desc_tipo'],
                'data' => $linha['data']
            );
            $retorno[] = $dados;
            $valor     = $valor + $linha['valor'];
        }
        
        $retorno[] = array(
            'descricao' => 'TOTAL',
            'desc_tipo' => '',
            'valor' => $valor,
            'estoque' => ''
        );
        
        return array(
            'indicador_erro' => 0,
            'dados' => $retorno
        );
    }
    
    function gerarRelatorioCaixa($dataInicial, $dataFinal, $lojaBusca, $formaPagamanto)
    {
        $sql = "
            SELECT LEFT(data, 10)              AS data, 
                   valor, 
                   cx.descricao descricao,
                   lj.descricao loja, 
                   Ifnull((SELECT Sum(a.valor_pago) 
                           FROM   forma_pagamento_venda a, 
                                  venda c 
                           WHERE  a.id_forma_pagamento in (" . $formaPagamanto . ")
                          and a.id_venda = c.id_venda
                          and (c.dta_cancelamento_venda = '0000-00-00' or c.dta_cancelamento_venda is null)
                          and c.dta_venda = LEFT(data, 10)
                          and indicador_consignado = 1 ";
        if ($lojaBusca != 0)
            $sql = $sql . " and c.id_loja = " . $lojaBusca . " ";
        
        $sql = $sql . "   ), 0) AS v_venda 
            FROM   caixa cx
            left join
                loja lj on lj.id = cx.id_loja
            WHERE  LEFT(data, 10) between '" . $dataInicial . "' and '" . $dataFinal . "' ";
        
        if ($lojaBusca != 0)
            $sql = $sql . " and cx.id_loja = " . $lojaBusca . " order by 1";
        else
            $sql = $sql . " order by 1";
        
        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 2,
                'dados' => null
            );
        
        //Encontrou uma ou mais vendas
        $dt_ant     = '00-00-0000';
        $retorno    = array();
        $total_data = 0;
        $valor      = 0;
        $ult_dt     = '00-00-0000';
        while ($linha = mysqli_fetch_array($resultado)) {
            if ($linha['data'] != $dt_ant) {
                if ($total_data != 0) {
                    $dados      = array(
                        'data' => '',
                        'descricao' => 'TOTAL',
                        'valor' => round($total_data, 2)
                    );
                    $retorno[]  = $dados;
                    $total_data = 0;
                }
                $dados      = array(
                    'data' => $linha['data'],
                    'descricao' => 'Vendas do dia',
                    'valor' => $linha['v_venda']
                );
                $valor      = $valor + round($linha['v_venda'], 2);
                $retorno[]  = $dados;
                $total_data = $total_data + round($linha['v_venda'], 2);
            }
            $dt_ant             = $linha['data'];
            $total_data         = $total_data + round($linha['valor'], 2);
            // Tratamento dos valores retornados do banco
            $linha['descricao'] = $this->removeAcentos(($linha['descricao']));
            
            $linha['descricao'] = strtolower($linha['descricao']);
            $linha['descricao'] = ucwords($linha['descricao']);
            
            $dados     = array(
                'data' => $linha['data'],
                'descricao' => $linha['descricao'],
                'valor' => round($linha['valor'], 2)
            );
            $retorno[] = $dados;
            $valor     = $valor + $linha['valor'];
            $ult_dt    = $linha['data'];
        }
        $dados     = array(
            'data' => '',
            'descricao' => 'TOTAL',
            'valor' => round($total_data, 2)
        );
        $retorno[] = $dados;
        $retorno[] = array(
            'data' => 'TOTAL',
            'descricao' => '',
            'desc_tipo' => '',
            'valor' => round($valor, 2)
        );
        
        return array(
            'indicador_erro' => 0,
            'dados' => $retorno
        );
    }
    
    
    
    function buscarFaturamento($dataInicial, $dataFinal, $lojaBusca)
    {
        
        //Monta e executa a query
        $sql = "
                      select 
                          id_loja,
                          mid(dta_venda,6,2) as mes,
                          sum(a.valor_total_comissao) as valor_comissao,
                          sum(a.valor_total_taxas) as valor_taxas,
                          sum(a.valor_total_outros) as valor_outros,
                          sum(a.valor_total_liquido) as valor_liquido,
                          sum(a.valor_total_pago) as valor_pago
                      from venda a 
                        where 
                          (a.dta_cancelamento_venda = '0000-00-00' or a.dta_cancelamento_venda is null)
                          and a.dta_venda between  '" . $dataInicial . "' and '" . $dataFinal . "'
                          and indicador_consignado = 1 ";
        
        if ($lojaBusca > 0) {
            $sql = $sql . " and id_loja = " . $lojaBusca . " group by id_loja, mes";
        } else {
            $sql = $sql . " group by mes";
        }
        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //$linha   = mysqli_fetch_array($resultado);
        
        //Verifica se o telefone está preenchido na base de dados
        if (empty($linha['valor_comissao']) == true)
            $linha['valor_comissao'] = "";
        else
            $linha['valor_comissao'] = number_format($linha['valor_comissao'], 2, ',', '.');
        
        if (empty($linha['valor_taxas']) == true)
            $linha['valor_taxas'] = "";
        else
            $linha['valor_taxas'] = number_format($linha['valor_taxas'], 2, ',', '.');
        
        if (empty($linha['valor_outros']) == true)
            $linha['valor_outros'] = "";
        else
            $linha['valor_outros'] = number_format($linha['valor_outros'], 2, ',', '.');
        
        if (empty($linha['valor_liquido']) == true)
            $linha['valor_liquido'] = "";
        else
            $linha['valor_liquido'] = number_format($linha['valor_liquido'], 2, ',', '.');
        
        // RETORNA OS VALORES OBTIDOS
        
        //Encontrou uma ou mais vendas
        $retorno = array();
        while ($linha = mysqli_fetch_array($resultado)) {
            
            if ($linha['mes'] == 01)
                $linha['mes'] = 'Janeiro';
            else if ($linha['mes'] == 02)
                $linha['mes'] = 'Fevereiro';
            else if ($linha['mes'] == 03)
                $linha['mes'] = 'Março';
            else if ($linha['mes'] == 04)
                $linha['mes'] = 'Abril';
            else if ($linha['mes'] == 05)
                $linha['mes'] = 'Maio';
            else if ($linha['mes'] == 06)
                $linha['mes'] = 'Junho';
            else if ($linha['mes'] == 07)
                $linha['mes'] = 'Julho';
            
            
            
            $dados     = array(
                'mes' => $linha['mes'],
                'valor_comissao' => $linha['valor_comissao'],
                'valor_taxas' => $linha['valor_taxas'],
                'valor_outros' => $linha['valor_outros'],
                'valor_liquido' => $linha['valor_liquido'],
                'valor_pago' => $linha['valor_pago']
            );
            $retorno[] = $dados;
        }
        
        return array(
            'indicador_erro' => 0,
            'dados' => $retorno
        );
    }
    
    function buscarFaturamentoAno($ano, $lojaBusca)
    {
        
        //Monta e executa a query
        $sql = "
            select
                id_loja, 
                '" . $ano . "' as trimestre,
                sum(a.valor_total_comissao) as valor_comissao,
                sum(a.valor_total_taxas) as valor_taxas,
                sum(a.valor_total_outros) as valor_outros,
                sum(a.valor_total_liquido) as valor_liquido,
                sum(a.valor_total_pago) as valor_pago
            from venda a 
              where 
                (a.dta_cancelamento_venda = '0000-00-00' or a.dta_cancelamento_venda is null)
                and LEFT(dta_venda,4) = '" . $ano . "'
                and indicador_consignado = 1";
        
        if ($lojaBusca > 0) {
            $sql = $sql . " and id_loja = " . $lojaBusca . " group by 1, 2";
        } else {
            $sql = $sql . " group by 2";
        }
        
        $sql = $sql . "
            union
            select 
                id_loja, 
                '1º Trimestre' as trimestre,
                sum(a.valor_total_comissao) as valor_comissao,
                sum(a.valor_total_taxas) as valor_taxas,
                sum(a.valor_total_outros) as valor_outros,
                sum(a.valor_total_liquido) as valor_liquido,
                sum(a.valor_total_pago) as valor_pago
            from venda a 
              where 
                (a.dta_cancelamento_venda = '0000-00-00' or a.dta_cancelamento_venda is null)
                and LEFT(dta_venda,7) BETWEEN concat('" . $ano . "', '-01') and concat('" . $ano . "', '-03')
                and indicador_consignado = 1
                ";
        
        if ($lojaBusca > 0) {
            $sql = $sql . " and id_loja = " . $lojaBusca . " group by 1, 2";
        } else {
            $sql = $sql . " group by 2";
        }
        
        $sql = $sql . "
            union
            select
                id_loja, 
                '2º Trimestre' as trimestre,
                sum(a.valor_total_comissao) as valor_comissao,
                sum(a.valor_total_taxas) as valor_taxas,
                sum(a.valor_total_outros) as valor_outros,
                sum(a.valor_total_liquido) as valor_liquido,
                sum(a.valor_total_pago) as valor_pago
            from venda a 
              where 
                (a.dta_cancelamento_venda = '0000-00-00' or a.dta_cancelamento_venda is null)
                and LEFT(dta_venda,7) BETWEEN concat('" . $ano . "', '-04') and concat('" . $ano . "', '-06')
                and indicador_consignado = 1 ";
        
        if ($lojaBusca > 0) {
            $sql = $sql . " and id_loja = " . $lojaBusca . " group by 1, 2";
        } else {
            $sql = $sql . " group by 2";
        }
        
        $sql = $sql . "
            union
            select
                id_loja, 
                '3º Trimestre' as trimestre,
                sum(a.valor_total_comissao) as valor_comissao,
                sum(a.valor_total_taxas) as valor_taxas,
                sum(a.valor_total_outros) as valor_outros,
                sum(a.valor_total_liquido) as valor_liquido,
                sum(a.valor_total_pago) as valor_pago
            from venda a 
              where 
                (a.dta_cancelamento_venda = '0000-00-00' or a.dta_cancelamento_venda is null)
                and LEFT(dta_venda,7) BETWEEN concat('" . $ano . "', '-07') and concat('" . $ano . "', '-09')
                and indicador_consignado = 1 ";
        
        if ($lojaBusca > 0) {
            $sql = $sql . " and id_loja = " . $lojaBusca . " group by 1, 2";
        } else {
            $sql = $sql . " group by 2";
        }
        
        $sql = $sql . "
            union
            select 
                id_loja, 
                '4º Trimestre' as trimestre,
                sum(a.valor_total_comissao) as valor_comissao,
                sum(a.valor_total_taxas) as valor_taxas,
                sum(a.valor_total_outros) as valor_outros,
                sum(a.valor_total_liquido) as valor_liquido,
                sum(a.valor_total_pago) as valor_pago
            from venda a 
              where 
                (a.dta_cancelamento_venda = '0000-00-00' or a.dta_cancelamento_venda is null)
                and LEFT(dta_venda,7) BETWEEN concat('" . $ano . "', '-10') and concat('" . $ano . "', '-12')
                and indicador_consignado = 1 ";
        
        if ($lojaBusca > 0) {
            $sql = $sql . " and id_loja = " . $lojaBusca . " group by 1, 2";
        } else {
            $sql = $sql . " group by 2";
        }
        
        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //$linha   = mysqli_fetch_array($resultado);
        
        //Verifica se o telefone está preenchido na base de dados
        if (empty($linha['valor_comissao']) == true)
            $linha['valor_comissao'] = "";
        else
            $linha['valor_comissao'] = number_format($linha['valor_comissao'], 2, ',', '.');
        
        if (empty($linha['valor_taxas']) == true)
            $linha['valor_taxas'] = "";
        else
            $linha['valor_taxas'] = number_format($linha['valor_taxas'], 2, ',', '.');
        
        if (empty($linha['valor_outros']) == true)
            $linha['valor_outros'] = "";
        else
            $linha['valor_outros'] = number_format($linha['valor_outros'], 2, ',', '.');
        
        if (empty($linha['valor_liquido']) == true)
            $linha['valor_liquido'] = "";
        else
            $linha['valor_liquido'] = number_format($linha['valor_liquido'], 2, ',', '.');
        
        // RETORNA OS VALORES OBTIDOS
        
        //Encontrou uma ou mais vendas
        $retorno = array();
        while ($linha = mysqli_fetch_array($resultado)) {
            
            $dados     = array(
                'trimestre' => $linha['trimestre'],
                'valor_comissao' => $linha['valor_comissao'],
                'valor_taxas' => $linha['valor_taxas'],
                'valor_outros' => $linha['valor_outros'],
                'valor_liquido' => $linha['valor_liquido'],
                'valor_pago' => $linha['valor_pago']
            );
            $retorno[] = $dados;
        }
        
        return array(
            'indicador_erro' => 0,
            'dados' => $retorno
        );
    }
    
    
    function buscarCusto($dataInicial, $dataFinal, $lojaBusca)
    {
        
        //Monta e executa a query
        $sql = "
            select
              a.id_loja, mid(dta_venda,6,2), sum(b.preco_custo) as valor_custo
            from
              venda a, itens_de_venda b
            where 
              (a.dta_cancelamento_venda = '0000-00-00' or a.dta_cancelamento_venda is null)
              and a.dta_venda between  '" . $dataInicial . "' and '" . $dataFinal . "'
              and indicador_consignado = 1
              and a.id_venda = b.id_venda";
        
        if ($lojaBusca > 0) {
            $sql = $sql . " and id_loja = " . $lojaBusca . " group by 1, 2";
        } else {
            $sql = $sql . " group by 2";
        }
        
        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //$linha   = mysqli_fetch_array($resultado);
        
        //Verifica se o telefone está preenchido na base de dados
        if (empty($linha['valor_custo']) == true)
            $linha['valor_custo'] = "";
        else
            $linha['valor_custo'] = number_format($linha['valor_custo'], 2, ',', '.');
        
        
        
        // RETORNA OS VALORES OBTIDOS
        
        //Encontrou uma ou mais vendas
        $retorno = array();
        while ($linha = mysqli_fetch_array($resultado)) {
            
            $dados     = array(
                'valor_custo' => $linha['valor_custo']
            );
            $retorno[] = $dados;
        }
        
        return array(
            'indicador_erro' => 0,
            'dados' => $retorno
        );
    }
    
    function buscarCustoAno($ano, $lojaBusca)
    {
        
        //Monta e executa a query
        $sql = "
                      select
                        a.id_loja, '" . $ano . "', sum(b.preco_custo) as valor_custo
                      from
                        venda a, itens_de_venda b
                      where 
                        (a.dta_cancelamento_venda = '0000-00-00' or a.dta_cancelamento_venda is null)
                        and left(a.dta_venda,4) = '" . $ano . "'
                        and indicador_consignado = 1
                        and a.id_venda = b.id_venda ";
        
        if ($lojaBusca > 0) {
            $sql = $sql . " and id_loja = " . $lojaBusca . " group by 1, 2 ";
        } else {
            $sql = $sql . " group by 2 ";
        }
        
        $sql = $sql . " union
                      select
                        a.id_loja, '1º Trimestre', sum(b.preco_custo) as valor_custo
                      from
                        venda a, itens_de_venda b
                      where 
                        (a.dta_cancelamento_venda = '0000-00-00' or a.dta_cancelamento_venda is null)
                        and LEFT(a.dta_venda,7) BETWEEN concat('" . $ano . "', '-01') and concat('" . $ano . "', '-03')
                        and indicador_consignado = 1
                        and a.id_venda = b.id_venda ";
        
        if ($lojaBusca > 0) {
            $sql = $sql . " and id_loja = " . $lojaBusca . " group by 1, 2 ";
        } else {
            $sql = $sql . " group by 2 ";
        }
        
        $sql = $sql . " union
                        select
                        a.id_loja, '2º Trimestre', sum(b.preco_custo) as valor_custo
                      from
                        venda a, itens_de_venda b
                      where 
                        (a.dta_cancelamento_venda = '0000-00-00' or a.dta_cancelamento_venda is null)
                        and LEFT(a.dta_venda,7) BETWEEN concat('" . $ano . "', '-04') and concat('" . $ano . "', '-06')
                        and indicador_consignado = 1
                        and a.id_venda = b.id_venda ";
        
        if ($lojaBusca > 0) {
            $sql = $sql . " and id_loja = " . $lojaBusca . " group by 1, 2 ";
        } else {
            $sql = $sql . " group by 2 ";
        }
        
        $sql = $sql . " union
                        select
                        a.id_loja, '3º Trimestre', sum(b.preco_custo) as valor_custo
                      from
                        venda a, itens_de_venda b
                      where 
                        (a.dta_cancelamento_venda = '0000-00-00' or a.dta_cancelamento_venda is null)
                        and LEFT(a.dta_venda,7) BETWEEN concat('" . $ano . "', '-07') and concat('" . $ano . "', '-09')
                        and indicador_consignado = 1
                        and a.id_venda = b.id_venda ";
        
        if ($lojaBusca > 0) {
            $sql = $sql . " and id_loja = " . $lojaBusca . " group by 1, 2 ";
        } else {
            $sql = $sql . " group by 2 ";
        }
        
        $sql = $sql . " union
                      select
                        a.id_loja, '4º Trimestre', sum(b.preco_custo) as valor_custo
                      from
                        venda a, itens_de_venda b
                      where 
                        (a.dta_cancelamento_venda = '0000-00-00' or a.dta_cancelamento_venda is null)
                        and LEFT(a.dta_venda,7) BETWEEN concat('" . $ano . "', '-10') and concat('" . $ano . "', '-12')
                        and indicador_consignado = 1
                        and a.id_venda = b.id_venda ";
        
        if ($lojaBusca > 0) {
            $sql = $sql . " and id_loja = " . $lojaBusca . " group by 1, 2";
        } else {
            $sql = $sql . " group by 2";
        }
        
        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //$linha   = mysqli_fetch_array($resultado);
        
        //Verifica se o telefone está preenchido na base de dados
        if (empty($linha['valor_custo']) == true)
            $linha['valor_custo'] = "";
        else
            $linha['valor_custo'] = number_format($linha['valor_custo'], 2, ',', '.');
        
        
        
        // RETORNA OS VALORES OBTIDOS
        
        //Encontrou uma ou mais vendas
        $retorno = array();
        while ($linha = mysqli_fetch_array($resultado)) {
            
            $dados     = array(
                'valor_custo' => $linha['valor_custo']
            );
            $retorno[] = $dados;
        }
        
        return array(
            'indicador_erro' => 0,
            'dados' => $retorno
        );
    }
    
    
    
    
    function buscarValoresGeraisVendas($dataInicial, $dataFinal, $lojaBusca)
    {
        
        //Monta e executa a query
        $sql = "
                        select
                          a.id_loja,
                          sum(a.valor_total_comissao) as valor_comissao,
                          sum(a.valor_total_taxas) as valor_taxas,
                          sum(a.valor_total_outros) as valor_outros,
                          sum(a.valor_total_liquido) as valor_liquido
                        from
                          venda a
                        where 
                          (a.dta_cancelamento_venda = '0000-00-00' or a.dta_cancelamento_venda is null)
                          and a.dta_venda between  '" . $dataInicial . "' and '" . $dataFinal . "' 
                          and indicador_consignado = 1 ";
        
        if ($lojaBusca > 0) {
            $sql = $sql . "and a.id_loja = " . $lojaBusca . " group by a.id_loja";
        }
        
        //Executa a query
        $resultado = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado) == 0)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        $linha = mysqli_fetch_array($resultado);
        
        //Verifica se o telefone está preenchido na base de dados
        if (empty($linha['valor_comissao']) == true)
            $linha['valor_comissao'] = "";
        else
            $linha['valor_comissao'] = number_format($linha['valor_comissao'], 2, ',', '.');
        
        if (empty($linha['valor_taxas']) == true)
            $linha['valor_taxas'] = "";
        else
            $linha['valor_taxas'] = number_format($linha['valor_taxas'], 2, ',', '.');
        
        if (empty($linha['valor_outros']) == true)
            $linha['valor_outros'] = "";
        else
            $linha['valor_outros'] = number_format($linha['valor_outros'], 2, ',', '.');
        
        if (empty($linha['valor_liquido']) == true)
            $linha['valor_liquido'] = "";
        else
            $linha['valor_liquido'] = number_format($linha['valor_liquido'], 2, ',', '.');
        
        // BUSCA O VALOR TOTAL DE CUSTO DOS PRODUTOS
        
        
        //Monta e executa a query
        $sql = "
                      select
                        a.id_loja,
                        sum(b.preco_custo) as valor_custo
                      from
                        venda a, itens_de_venda b
                      where 
                        (a.dta_cancelamento_venda = '0000-00-00' or a.dta_cancelamento_venda is null)
                        and a.dta_venda between  '" . $dataInicial . "' and '" . $dataFinal . "'
                        and indicador_consignado = 1
                        and a.id_venda = b.id_venda ";
        
        if ($lojaBusca > 0) {
            $sql = $sql . "and a.id_loja = " . $lojaBusca . " group by a.id_loja";
        }
        
        //Executa a query
        $resultado2 = $this->conexao->query($sql);
        
        //Se retornar algum erro
        if (!$resultado2)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        //Se não retornar nenhuma linha
        if (mysqli_num_rows($resultado2) == 0)
            return array(
                'indicador_erro' => 1,
                'dados' => null
            );
        
        $linha2 = mysqli_fetch_array($resultado2);
        
        if (empty($linha2['valor_custo']) == true)
            $linha2['valor_custo'] = "";
        else
            $linha2['valor_custo'] = number_format($linha2['valor_custo'], 2, ',', '.');
        
        // RETORNA OS VALORES OBTIDOS
        
        $retorno = array(
            'valor_comissao' => $linha['valor_comissao'],
            'valor_taxas' => $linha['valor_taxas'],
            'valor_outros' => $linha['valor_outros'],
            'valor_liquido' => $linha['valor_liquido'],
            'valor_custo' => $linha2['valor_custo']
        );
        return array(
            'indicador_erro' => 0,
            'dados' => $retorno
        );
    }
    
    function removeAcentos($string, $slug = false)
    {
        return preg_replace(array(
            "/(á|à|ã|â|ä)/",
            "/(Á|À|Ã|Â|Ä)/",
            "/(é|è|ê|ë)/",
            "/(É|È|Ê|Ë)/",
            "/(í|ì|î|ï)/",
            "/(Í|Ì|Î|Ï)/",
            "/(ó|ò|õ|ô|ö)/",
            "/(Ó|Ò|Õ|Ô|Ö)/",
            "/(ú|ù|û|ü)/",
            "/(Ú|Ù|Û|Ü)/",
            "/(ñ)/",
            "/(Ñ)/"
        ), explode(" ", "a A e E i I o O u U n N"), $string);
    }
    
}

?>