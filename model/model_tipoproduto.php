<?php

/**
 * Model_TipoProduto
 *
 * Interações com a tabela TipoProduto 
 */
class Model_TipoProduto {


     private $conexao;

     function Model_TipoProduto($conexao)
     {
          $this->conexao = $conexao;
     }


    /**
     * buscaIdClientePorNome
     * @author Victor
     */
     function listarTiposProduto()
     {

          //Monta e executa a query
          $sql       = " SELECT 
                           id_numero_produto, descricao
                         FROM
                           tipo_produto;";

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
                    $dados     = array('id_tipo_produto' => $linha['id_numero_produto'], 'nome_tipo_produto' => ($linha['descricao']));
                    $retorno[] = $dados;
               }
               return array('indicador_erro' => 0, 'dados' => $retorno);
          }
     }

}

?>