<?php

/**
 * Model_itens_de_venda
 *
 * Interações com a tabela itens_de_venda
 */
class Model_itens_de_venda {


    private $conexao;

    function Model_itens_de_venda($conexao)
    {
       $this->conexao = $conexao;
    }


    /**
     * gravar
     * @author Victor
     */
    function gravar($dados)
    {
    	//Grava todos os itens do array na tabela. Interrompe se encontrar algum erro
    	for($i=0; $i< count($dados); $i++)
    	{

          //Monta e executa a query
          $sql       = " 
				insert into itens_de_venda (
					id_venda, 
					id_item_de_venda, 
					id_produto, 
					quantidade, preco_custo, preco_venda)
				values 
					(".$dados[$i]['id_venda'].",".
					   $dados[$i]['id_item_de_venda'].",".
					   $dados[$i]['id_produto'].",".
					   $dados[$i]['quantidade'].",".
             ($dados[$i]['valorCusto'] * $dados[$i]['quantidade']).",".
             ($dados[$i]['valorUnitarioSemFormatacao'] * $dados[$i]['quantidade']).");";

  
          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Retorna o dado quando não há erro
          if(!$resultado)
               return false;
        }

        return true;       
   }
	function buscarOrcamento($cliente, $data)
   {
      $sql = "select o.id_orcamento id_orcamento,
                  c.nome cliente,
                  date_format(o.data, '%d/%m/%Y') data,
                  sum(preco) valor
               from orcamento o 
               inner join cliente c using (id_cliente) where 1 = 1 ";
      
      if(isset($cliente) && !empty($cliente)){
         $sql = $sql." and c.nome like '%".$cliente."%' ";
      }
      if(isset($data) && !empty($data)){
         $sql = $sql." and date_format(o.data, '%Y-%m-%d') like '%".$data."%' ";
      }
      
      $sql = $sql." group by 1 order by 1 desc";

      $retorno = $this->conexao->query($sql);

      if(!$retorno)
         return array("resultado" => "erro", "dados" => "Erro ao buscar lista de orçamentos");

      if(mysqli_num_rows($retorno) == 0)
         return array("resultado" => "erro", "dados" => "Nenhum resultado encontrado para a pesquisa");
      
      $orcamentos = array();
      while($linha = mysqli_fetch_array($retorno)){
         $orcamentos[] = $linha;
      }

      return array("resultado" => "sucesso", "dados" => $orcamentos);
   }

   function carregarOrcamento($orcamentoId){
      $sql = "select 
               o.id_produto id_produto, 
               p.descricao descricao, 
               o.quantidade quantidade, 
               p.peso peso,
               REPLACE(o.preco, '.', ',') preco, 
               REPLACE(p.preco_varejo, '.', ',') preco_varejo, 
               REPLACE(p.preco_atacado, '.', ',') preco_atacado
         from orcamento o 
            inner join produto p 
            using (id_produto) 
         where id_orcamento = ".$orcamentoId;

      $resultado = $this->conexao->query($sql); 

      if (!$resultado)
      return array(
          'indicador_erro' => 1,
          'dados' => "Erro ao buscar orçamento"
      );
  
      //Se não retornar nenhuma linha
      if (mysqli_num_rows($resultado) == 0)
            return array(
               'indicador_erro' => 2,
               'dados' => "Orçamento não econtrado"
         );

        $dadosItens  = array();
        $itens_venda = array();
        while ($linha = mysqli_fetch_array($resultado)) {
            $dadosItens['id_produto']   = $linha['id_produto'];
            $dadosItens['descricao']    = $linha['descricao'];
            $dadosItens['quantidade']  = $linha['quantidade'];
            $dadosItens['peso']    = $linha['peso'];
            $dadosItens['preco'] = $linha['preco'];
            $dadosItens['preco_varejo']    = $linha['preco_varejo'];
            $dadosItens['preco_atacado']    = $linha['preco_atacado'];
            $itens_venda[]               = $dadosItens;
        }

        return array(
         'indicador_erro' => 0,
         'dados' => $itens_venda
        );
   }

   function inserirProdutosOrcamento($dados, $cliente, $orcamento){

      if($orcamento == 0)
         $sql       = "select max(id_orcamento) + 1 as ultimo from orcamento;";
      else 
         $sql       = "delete from orcamento where id_orcamento = ".$orcamento.";";

      $ultimoOrcamento = $orcamento;

      //Executa a query
      $resultado = $this->conexao->query($sql); 
      
      if($orcamento == 0){
         if (mysqli_num_rows($resultado) == 0)
               return array("retorno" => "erro", "dados" => "Erro ao buscar última faturamento");
         else 
         {
            $linha = mysqli_fetch_array($resultado);
            $ultimoOrcamento = $linha['ultimo'];
         }
      }
      //Grava todos os itens do array na tabela. Interrompe se encontrar algum erro
      for($i=0; $i< count($dados); $i++)
    	{

          //Monta e executa a query
          $sql       = " 
				insert into orcamento (
               id_cliente,
					id_orcamento, 
               id_produto,
               quantidade, 
               preco,
               data)
				values 
					(".$cliente.",".
					   $ultimoOrcamento.",".
					   $dados[$i]['id_produto'].",".
					   $dados[$i]['quantidade'].",".
					   $dados[$i]['preco'].",
					   curdate());";

          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Retorna o dado quando não há erro
          if(!$resultado)
            return array("retorno" => "erro", "dados" => "Erro ao salvar produto");
        }

        return array("retorno" => "sucesso", "dados" => $ultimoOrcamento);  
   }

}

?>