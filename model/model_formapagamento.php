<?php

/**
 * Model_FormaPagamento
 *
 * Interações com a tabela FormaPagamento 
 */
class Model_FormaPagamento {

      private $conexao;

      function Model_FormaPagamento($conexao)
      {
         $this->conexao = $conexao;
      }

    
     function listarTodasFormasPagamento(){
          if (!isset($_SESSION)) {
               session_start();
             }
     
             $loja = $_SESSION['usuario']['id_loja'];
     
               $sql = " SELECT 
                         id_forma_pagamento, descricao, porcentagem_taxa
                        FROM
                         forma_pagamento ";

          //    if( $loja != 0 )
          //      $sql = $sql." WHERE id_loja = 0 or id_loja = ".$loja." ;";
                         
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
                         if ($linha['porcentagem_taxa'] >= 1.0)
                           $linha['porcentagem_taxa'] = $linha['porcentagem_taxa'] / 100;
     
                         $dados     = array('idFormaPagamento' => $linha['id_forma_pagamento'], 'nomeFormaPagamento' => ucfirst(($linha['descricao'])), 'taxaFormaPagamento' => $linha['porcentagem_taxa']);
                         $retorno[] = $dados;
                    }
                    return array('indicador_erro' => 0, 'dados' => $retorno);
               }
     }
     
    
      /**
     * listarFormasPagamento
     * @author Victor
     */
     function listarFormasPagamento()
     {

        if (!isset($_SESSION)) {
          session_start();
        }

        $loja = $_SESSION['usuario']['lojaVenda'];

          $sql       = " SELECT 
                       id_forma_pagamento, descricao, porcentagem_taxa
                     FROM
                       forma_pagamento
                      WHERE 
                        situacao = 0 and (id_loja = 0 or id_loja = ".$loja.");";
                        
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
                    if ($linha['porcentagem_taxa'] >= 1.0)
                      $linha['porcentagem_taxa'] = $linha['porcentagem_taxa'] / 100;

                    $dados     = array('idFormaPagamento' => $linha['id_forma_pagamento'], 'nomeFormaPagamento' => ucfirst(($linha['descricao'])), 'taxaFormaPagamento' => $linha['porcentagem_taxa']);
                    $retorno[] = $dados;
               }
               return array('indicador_erro' => 0, 'dados' => $retorno);
          }
     }


    /**
     * listarTaxaFormaPagamento
     * @author Victor
     */
     function listarTaxaFormaPagamento($id_forma_pagamento)
     {


          $sql       = " SELECT 
                       porcentagem_taxa
                     FROM
                       forma_pagamento
                     WHERE id_forma_pagamento = ".$id_forma_pagamento.";";
  
          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Se retornar algum erro
          if(!$resultado)
               return array('indicador_erro' => 1, 'dados' => null);          

          //Se não retornar nenhuma linha
          if (mysqli_num_rows($resultado) == 0)
               return array('indicador_erro' => 1, 'dados' => null);
          else 
          {

          	$linha   = mysqli_fetch_array($resultado);

            if ($linha['porcentagem_taxa'] >= 1.0)
            $linha['porcentagem_taxa'] = $linha['porcentagem_taxa'] / 100;

			     return array('indicador_erro' => 0, 'dados' => $linha['porcentagem_taxa']);
          }
     }


}

?>