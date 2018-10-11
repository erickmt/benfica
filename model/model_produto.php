<?php

/**
 * Model_Produto
 *
 * Interações com a tabela Produto 
 */
class Model_Produto {

    private $conexao;

    function Model_Produto($conexao)
    {
        $this->conexao = $conexao;
    }

    function devolveConsignado($produto, $quantidade){
        $sql = "UPDATE itens_de_venda set quantidade_devolvida = (quantidade_devolvida + '$quantidade') where id = '$produto'";

        $resultado = $this->conexao->query($sql);

        //Se retornar algum erro
        if(!$resultado)
            return array('resultado' => 'erro', 'descricao' => "Erro ao devolver peças");

        $sql = "UPDATE produto set `quantidade_estoque` = (`quantidade_estoque` + '$quantidade') where id_produto = (select id_produto from itens_de_venda where id = '$produto');";

        $resultado = $this->conexao->query($sql);

        //Se retornar algum erro
        if(!$resultado)
            return array('resultado' => 'erro', 'descricao' => "Erro ao voltar itens para estoque");
        
        return array('resultado' => 'sucesso', 'descricao' => "Produtos devolvidos com sucesso");
    }
	
	function buscarPecasConignado($cliente, $tipoProduto, $dataInicial, $dataFinal, $produto = false, $lojaBusca, $devolvidas){
	
		$sql = "SELECT 
                    lj.descricao loja,
                    v.id_venda id_venda,
                    DATE_FORMAT(v.dta_venda, '%d/%m/%Y') dta_venda,
                    ifnull(p.descricao, 'PRODUTO DELETADO') descricao,
                    iv.id id_produto,
                    iv.quantidade quantidade,
                    iv.quantidade_devolvida devolvido,
                    round(iv.preco_venda / quantidade, 2) valor,
                    round(iv.preco_venda, 2) total,
                    round((iv.preco_venda - (iv.preco_venda / quantidade * quantidade_devolvida)), 2) restante,
                    c.nome nome 
				from 
					venda v
				inner join
					itens_de_venda iv on v.id_venda = iv.id_venda
				left join
					cliente c on v.id_cliente = c.id_cliente
				left join
					produto p on p.id_produto = iv.id_produto
                left join
	                loja lj on v.id_loja = lj.id
				where 
					indicador_consignado = 0
					and (v.dta_cancelamento_venda = '0000-00-00' or v.dta_cancelamento_venda = null) ";
					
		if($cliente != false)
			$sql = $sql." and c.nome like '".$cliente."%' ";
		
		if($tipoProduto != false)
			$sql = $sql." and p.id_tipo_produto = ".$tipoProduto." ";
		
		if($produto != false)
			$sql = $sql." and p.id_produto in (".$produto.") ";
		
		if($dataInicial != false)
			$sql = $sql." and v.dta_venda >= '".$dataInicial."' ";
		
		if($dataFinal != false)
			$sql = $sql." and v.dta_venda <= '".$dataFinal."' ";
        
        if($devolvidas == 1)
            $sql = $sql." and iv.quantidade = iv.quantidade_devolvida";
        
        if($devolvidas == 2)
            $sql = $sql." and iv.quantidade != iv.quantidade_devolvida";
        
        if($lojaBusca != 0)
            $sql = $sql." and v.id_loja = ".$lojaBusca." ";
            
		$sql = $sql." order by id_venda, iv.id ";
        
		 $resultado = $this->conexao->query($sql);

          //Se retornar algum erro
          if(!$resultado)
               return array('indicador_erro' => 1, 'dados' => "Erro ao buscar vendas");

          //Se não retornar nenhuma linha
          if (mysqli_num_rows($resultado) == 0)
               return array('indicador_erro' => 2, 'dados' => "Nenhuma venda encontrada para esta pesquisa");

		  $dados = array();
          while ($linha = mysqli_fetch_array($resultado))
          {
              $linha['descricao'] = strtoupper($linha['descricao']);
              $linha['nome'] = strtoupper($linha['nome']);
              $produto = array('loja' => $linha['loja'], 'id_venda' => $linha['id_venda'], 'dta_venda' => $linha['dta_venda'], 'quantidade' => $linha['quantidade'], 'devolvido' => $linha['devolvido'], 'valor' => $linha['valor'], 'total' => $linha['total'], 'restante' => $linha['restante'], 'nome' => $linha['nome'],'descricao' => $linha['descricao'], 'id_produto' => $linha['id_produto']); 
              $dados[] = $produto;
          }

         return array('indicador_erro' => 0, 'dados' => $dados);
		  
	}
    /**
     * listarProdutos
     * @author Victor
     */

    function buscarTipoProduto()
    {

          $listaTipoProduto = array();

          $sql       = " SELECT 
                       id_numero_produto, descricao
                     FROM
                       tipo_produto
                      ORDER BY 2;";
  
          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Se retornar algum erro
          if(!$resultado)
               return $listaTipoProduto;

          //Se não retornar nenhuma linha
          if (mysqli_num_rows($resultado) == 0)
               return $listaTipoProduto;



          while ($linha = mysqli_fetch_array($resultado))
          {
              $linha['descricao'] = strtoupper($linha['descricao']);
              //$linha['nome'] = ucwords($linha['nome']);

              $item = array('id_numero_produto' => $linha['id_numero_produto'], 'descricao' => $linha['descricao']); 
              $listaTipoProduto[] = $item;
          }

          // Retorna a lista de vendedores
          return $listaTipoProduto;
    }  

    function gerarRelatorioPecasCliente($nomeCliente, $dataInicial, $dataFinal, $lojaBusca)
    {
  
              //Monta e executa a query
          $sql = "SELECT 
                    pf.descricao perfil,
                    v.id_cliente id_cliente,
                    nome,
                    (SELECT 
                            SUM(quantidade)
                        FROM
                            benfica.itens_de_venda
                        WHERE
                            id_venda IN (SELECT 
                                    id_venda
                                FROM
                                    venda
                                WHERE
                                    id_cliente = v.id_cliente
                                    AND dta_venda BETWEEN '".$dataInicial."' and '".$dataFinal."')) AS quantidade
                FROM
                    venda v
                        LEFT JOIN
                    cliente cli ON cli.id_cliente = v.id_cliente
                        LEFT JOIN
                    perfil_cliente pf ON pf.id_perfil = cli.id_perfil
                WHERE
                    v.dta_cancelamento_venda IS NULL
                        OR v.dta_cancelamento_venda = '0000-00-00'
                        AND v.dta_venda BETWEEN '".$dataInicial."' and '".$dataFinal."'
                        AND v.indicador_consignado = 1 ";

            if($nomeCliente != '')
              $sql = $sql." and nome like '%".$nomeCliente."%' ";
            if($lojaBusca != 0)
              $sql = $sql." and v.id_loja = ".$lojaBusca." ";
            $sql = $sql." GROUP BY id_cliente ORDER BY quantidade DESC;";
    
            //Executa a query
            $resultado = $this->conexao->query($sql);
  
            //Se retornar algum erro
            if(!$resultado)
                 return array('indicador_erro' => 1, 'dados' => null);          
  
            //Se não retornar nenhuma linha
            if (mysqli_num_rows($resultado) == 0)
                 return array('indicador_erro' => 0, 'dados' => null);

            $retorno = array();
            while ($linha   = mysqli_fetch_array($resultado))
            {                            
              $dados     = array('perfil' => $linha['perfil'], 'id_cliente' => $linha['id_cliente'], 'nome' => strtoupper($linha['nome']), 'quantidade' => $linha['quantidade']);              
              $retorno[] = $dados;
            }
        
            return array('indicador_erro' => 0, 'dados' => $retorno);
    }

    function RelatorioPecasCliente($idCliente, $dataInicial, $dataFinal, $lojaBusca)
    {
  
            //Monta e executa a query
        $sql       = "SELECT 
                        p.descricao descricao, SUM(iv.quantidade) quantidade
                    FROM
                        venda v
                            INNER JOIN
                        itens_de_venda iv ON v.id_venda = iv.id_venda
                        left join
                        produto p on p.id_produto = iv.id_produto
                    WHERE
                        v.id_cliente =  '".$idCliente."'
                        and v.dta_venda BETWEEN ".$dataInicial." and ".$dataFinal." ";
            if($lojaBusca != 0)
              $sql = $sql." and v.id_loja = ".$lojaBusca." ";
            $sql = $sql." GROUP BY 1 order by 2 desc;";
            
            //Executa a query
            $resultado = $this->conexao->query($sql);
  
            //Se retornar algum erro
            if(!$resultado)
                 return array('indicador_erro' => 1, 'dados' => null);          
  
            //Se não retornar nenhuma linha
            if (mysqli_num_rows($resultado) == 0)
                 return array('indicador_erro' => 0, 'dados' => null);

            $retorno = array();
            while ($linha   = mysqli_fetch_array($resultado))
            {                            
              $dados     = array('descricao' => strtoupper($linha['descricao']), 'quantidade' => $linha['quantidade']);              
              $retorno[] = $dados;
            }
        
            return array('indicador_erro' => 0, 'dados' => $retorno);
    }
     function listarProdutos($tipoProduto = false, $modeloProduto = false)
     {

          if (!isset($_SESSION)) {
            session_start();
          }  
           $lojaLogada = $_SESSION['usuario']['id_loja'];
           if($lojaLogada == 0)
              $lojaLogada = $_SESSION['usuario']['lojaVenda'];
          //Monta e executa a query
	          $sql       = " SELECT 
	                           id_produto, descricao, preco_atacado, preco_varejo, peso
	                         FROM
	                           produto
                           WHERE 
                             situacao = 0 AND id_loja = ".$lojaLogada." ";

          if ($modeloProduto != false)
          {
              if($modeloProduto == 'M' || $modeloProduto == 'F')
                   $sql = $sql." AND modelo = '".$modeloProduto."'";
          }   

          if ($tipoProduto != false)
          {
              $sql = $sql." AND id_tipo_produto = ".$tipoProduto;
          }
          
          if ($lojaLogada == 0)
          {
			       return array('indicador_erro' => 1, 'dados' => null);   
          }
          
          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Se retornar algum erro
          if(!$resultado)
               return array('indicador_erro' => 1, 'dados' => null);          

          //Se não retornar nenhuma linha
          if (mysqli_num_rows($resultado) == 0)
               return array('indicador_erro' => 0, 'dados' => null);
          else 
          {
               $retorno = array();
               while ($linha   = mysqli_fetch_array($resultado))
               {

                    $linha['preco_atacado'] = number_format($linha['preco_atacado'], 2, ',', '.');
                    $linha['preco_varejo']  = number_format($linha['preco_varejo'], 2, ',', '.');

                    $linha['descricao'] = strtoupper($linha['descricao']);

                    $dados     = array('id_produto' => $linha['id_produto'], 'nome_produto' => $linha['descricao'], 'preco_atacado' => $linha['preco_atacado'], 'preco_varejo' => $linha['preco_varejo'], 'peso' => $linha['peso']);
                    $retorno[] = $dados;
               }
               return array('indicador_erro' => 0, 'dados' => $retorno);
          }
     }

     function listarProdutosRelatorio($tipoProduto = false, $modeloProduto = false)
     {

          if (!isset($_SESSION)) {
            session_start();
          }  

           $lojaLogada = $_SESSION['usuario']['id_loja'];

          //Monta e executa a query
            $sql       = " SELECT 
                             id_produto, descricao, preco_atacado, preco_varejo, peso
                           FROM
                             produto
                           WHERE 
                             situacao = 0 ";

          if ($modeloProduto != false)
          {
              if($modeloProduto == 'M' || $modeloProduto == 'F')
                   $sql = $sql." AND modelo = '".$modeloProduto."'";
          }   

          if ($tipoProduto != false)
          {
              $sql = $sql." AND id_tipo_produto = ".$tipoProduto;
          }
          if ($lojaLogada != 0)
          {
             $sql = $sql." AND id_loja = ".$lojaLogada;
          }
          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Se retornar algum erro
          if(!$resultado)
               return array('indicador_erro' => 1, 'dados' => null);          

          //Se não retornar nenhuma linha
          if (mysqli_num_rows($resultado) == 0)
               return array('indicador_erro' => 0, 'dados' => null);
          else 
          {
               $retorno = array();
               while ($linha   = mysqli_fetch_array($resultado))
               {

                    $linha['preco_atacado'] = number_format($linha['preco_atacado'], 2, ',', '.');
                    $linha['preco_varejo']  = number_format($linha['preco_varejo'], 2, ',', '.');

                    $linha['descricao'] = strtoupper($linha['descricao']);

                    $dados     = array('id_produto' => $linha['id_produto'], 'nome_produto' => $linha['descricao'], 'preco_atacado' => $linha['preco_atacado'], 'preco_varejo' => $linha['preco_varejo'], 'peso' => $linha['peso']);
                    $retorno[] = $dados;
               }
               return array('indicador_erro' => 0, 'dados' => $retorno);
          }
     }



    /**
     * obterPrecoProduto
     * @author Victor
     */
     function obterPrecoProduto($idProduto)
     {

          //Monta e executa a query
          $sql       = " SELECT 
                           preco_atacado, preco_varejo, peso
                         FROM
                           produto
                         where
                           id_produto = ".$idProduto.";";


          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Se retornar algum erro
          if(!$resultado)
               return array('indicador_erro' => 1, 'dados' => null);          

          //Se não retornar nenhuma linha
          if (mysqli_num_rows($resultado) == 0)
               return array('indicador_erro' => 0, 'dados' => null);
          else 
          {
          	$linha   = mysqli_fetch_array($resultado);

               $linha['preco_atacado'] = number_format($linha['preco_atacado'], 2, ',', '.');
               $linha['preco_varejo']  = number_format($linha['preco_varejo'], 2, ',', '.');               
               
               $retorno = array('preco_atacado' =>  $linha['preco_atacado'] , 'preco_varejo' =>  $linha['preco_varejo'] , 'peso' => $linha['peso']);
               return array('indicador_erro' => 0, 'dados' => $retorno);
          }
     }



   /**
     * buscaValorCustoProduto
     * @author Victor
     */
     function buscaValorCustoProduto($idProduto)
     {

          //Monta e executa a query
          $sql       = " SELECT 
                           preco_custo
                         FROM
                           produto
                         where
                           id_produto = ".$idProduto.";";


          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Se retornar algum erro
          if(!$resultado)
               return 0;

          //Se não retornar nenhuma linha
          if (mysqli_num_rows($resultado) == 0)
               return 0;
          else 
          {
               $linha = mysqli_fetch_array($resultado);
               return $linha['preco_custo'];
          }
     }

     function buscaDescricaoProduto($idProduto)
     {

          //Monta e executa a query
          $sql       = " SELECT 
                              n.descricao as nf_descricao,
                              n.ncm as ncm
                          FROM
                              ncm n
                          WHERE
                              n.id_ncm = (SELECT 
                                      nf_descricao
                                  FROM
                                      produto p
                                  WHERE
                                      id_produto = ".$idProduto.");";


          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Se retornar algum erro
          if(!$resultado)
               return array( 'nf_descricao' => "", 'ncm' => "");

          //Se não retornar nenhuma linha
          if (mysqli_num_rows($resultado) == 0)
               return array( 'nf_descricao' => "", 'ncm' => "");
          else 
          {
               $linha = mysqli_fetch_array($resultado);
               return array( 'nf_descricao' => $linha['nf_descricao'], 'ncm' => $linha['ncm']);
          }
     }          



    /**
     * verificarQuantidadeEmEstoque
     * @author Victor
     */
     function verificarQuantidadeEmEstoque($idProduto, $quantidadeProduto)
     {

          //Monta e executa a query
          $sql       = " SELECT 
                           quantidade_estoque
                         FROM
                           produto
                         where
                           id_produto = ".$idProduto.";";


          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Se retornar algum erro
          if(!$resultado)
               return false;

          //Se não retornar nenhuma linha
          if (mysqli_num_rows($resultado) == 0)
               return false;
          else 
          {
               $linha   = mysqli_fetch_array($resultado);

               if($linha['quantidade_estoque'] < $quantidadeProduto)
                    return false;
               else
                    return true;
          }
     }


    /**
     * buscarPesoProduto
     * @author Victor
     */
     function buscarPesoProduto($idProduto)
     {

          //Monta e executa a query
          $sql       = " SELECT 
                           peso
                         FROM
                           produto
                         where
                           id_produto = ".$idProduto.";";


          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Se retornar algum erro
          if(!$resultado)
               return 0;

          //Se não retornar nenhuma linha
          if (mysqli_num_rows($resultado) == 0)
               return 0;
          else 
          {
               $linha   = mysqli_fetch_array($resultado);

               if(empty($linha['peso']) == true)
                    return 0;
               else
                    return $linha['peso'];
          }
     }

    /**
     * reduzirProdutosEstoque
     * @author Victor
     */
     function reduzirProdutosEstoque($produtos)
     {

          $sql = " SELECT id_produto, quantidade_estoque FROM produto where id_produto in (";

          //Percorre a lista dos produtos enviados 
          for($i=0; $i < count($produtos); $i++)
          {
               $sql = $sql." ".$produtos[$i]['idProduto'].",";
          }

          //Remove a última vírgula
          $size = strlen($sql);
          $sql  = substr($sql,0, $size-1);

          //Inclui o parênteses, fechando o comando
          $sql  = $sql." );";

          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Se retornar algum erro
          if(!$resultado)
              return false;    

          // Seta a variável sql
          $sql1 = "";

          // Para cada linha encontrada, vai montando o update
          while ($linha   = mysqli_fetch_array($resultado))
          {

              //Busca o indice do produto no array interno com os produtos
              for($i=0; $i < count($produtos); $i++)
              {
                   if ($produtos[$i]['idProduto'] == $linha['id_produto'])
                   {
                          $novaQuantidadeProdutoEstoque = $linha['quantidade_estoque'] - $produtos[$i]['quantidadeProduto'];
                          $sql1 = "UPDATE produto SET quantidade_estoque = ".$novaQuantidadeProdutoEstoque." WHERE id_produto = ".$linha['id_produto']."; ";
                          $resultado1     = $this->conexao->query($sql1);    
                          if(!$resultado1)
                              die("aqui 3: ".$this->conexao->error);                              
                   }
              }

          }
      
          return true;
     }



    /**
     * voltarProdutosEstoque
     * @author Victor
     */
     function voltarProdutosEstoque($produtos)
     {

          $sql = " SELECT id_produto, quantidade_estoque FROM produto where id_produto in (";

          //Percorre a lista dos produtos enviados 
          for($i=0; $i < count($produtos); $i++)
          {
               $sql = $sql." ".$produtos[$i]['id_produto'].",";
          }

          //Remove a última vírgula
          $size = strlen($sql);
          $sql  = substr($sql,0, $size-1);

          //Inclui o parênteses, fechando o comando
          $sql  = $sql." );";


          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Se retornar algum erro
          if(!$resultado)
               return false;

          // Seta a variável sql
          $sql = " ";

          // Para cada linha encontrada, vai montando o update
          while ($linha   = mysqli_fetch_array($resultado))
          {

              $auxiliar = 0;

              //Busca o indice do produto no array interno com os produtos
              for($i=0; $i < count($produtos); $i++)
              {
                   if ($produtos[$i]['id_produto'] == $linha['id_produto'])
                   {
                        $auxiliar = $i;
                   }
              }

              //Define a nova quantidade do produto em estoque
              $novaQuantidadeProdutoEstoque = $linha['quantidade_estoque'] + $produtos[$auxiliar]['quantidade'];

              //Monta o update
              $sql = " UPDATE produto SET quantidade_estoque = ".$novaQuantidadeProdutoEstoque." WHERE id_produto = ".$linha['id_produto']."; ";

              //Atualiza a tabela de produtos com a nova quantidade em estoque
              $resultado2     = $this->conexao->query($sql);                         

              if(!$resultado2)
                   return false;              
          }
          
          return true;
     }


}

?>