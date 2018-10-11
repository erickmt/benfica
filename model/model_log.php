<?php

/**
 * Model_Log
 *
 * Interações com a tabela Log 
 */
class Model_Log {

	private $conexao;

    function Model_Log($conexao)
    {
      $this->conexao = $conexao;
    }
     // Verificar os métodos de exemplo do model de clientes
    function logNota($descricao, $cliente)
    {
    	if (!isset($_SESSION)) {
            session_start();
          }  
           $usuario = $_SESSION['usuario']['id_usuario'];

    	$sql = "insert into log  (id_login_usuario, tipo_operacao, nome_tabela, nome_atributo, observacao, id_cliente) values (".$usuario.",1,'PEDIDO','EMISSAO','".$descricao."',".$cliente.")";

    	 //Executa a query
         $resultado = $this->conexao->query($sql);

    }
}

?>