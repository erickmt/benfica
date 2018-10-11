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

}

?>