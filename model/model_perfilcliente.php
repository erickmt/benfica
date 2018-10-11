<?php

/**
 * Model_PerfilCliente
 *
 * Interações com a tabela PerfilCliente 
 */
class Model_PerfilCliente {

    private $conexao;

    function Model_PerfilCliente($conexao)
    {
      $this->conexao = $conexao;
    }

    /**
     * buscarQtdaMinimaProdutos
     * @author Victor
     */
    function buscarQtdaMinimaProdutos($idPerfil)
    {

          //Monta e executa a query
          $sql       = " SELECT 
                           quantidade_minima
                         FROM
                           perfil_cliente
                         WHERE 
                           id_perfil    = ".$idPerfil;

          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Retorna o dado quando não há erro
          if(!$resultado)
               return false;
 
          $linha   = mysqli_fetch_array($resultado);
          return $linha['quantidade_minima'];
  }

}

?>