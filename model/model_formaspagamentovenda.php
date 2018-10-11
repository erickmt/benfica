<?php

/**
 * Model_FormasPagamentoVenda
 *
 * Interações com a tabela FormasPagamentoVenda 
 */
class Model_FormasPagamentoVenda {


      private $conexao;

      function Model_FormasPagamentoVenda($conexao)
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
				insert into forma_pagamento_venda (
					id_venda, 
					id_forma_pagamento_venda, 
          id_forma_pagamento,
					valor_pago, 
					quantidade_parcela)
				values 
					(".$dados[$i]['id_venda'].",".
					   $dados[$i]['id_forma_pagamento_venda'].",".
             $dados[$i]['id_forma_pagamento'].",".
					   $dados[$i]['valor_pago'].",".
					   $dados[$i]['quantidade_parcelas'].");";

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