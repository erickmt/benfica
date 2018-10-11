<?php

/**
 * Model_Vendedor
 *
 * Interações com a tabela Vendedor 
 */
class Model_Vendedor {

  private $conexao;

  function Model_Vendedor($conexao)
  {
    $this->conexao = $conexao;
  }

    /**
     * buscarVendedoresCliente
     * @author Victor
     */
	function buscarVendedoresCliente($idCliente)
	{

		    //O primeiro item de exibição é o vendedor cadastrado para o cliente
            $sql       = "SELECT 
							cli.id_vendedor, ven.nome
						FROM
							cliente cli,
							vendedor ven
						WHERE 
							cli.id_cliente      = ".$idCliente."
							AND cli.id_vendedor = ven.id_vendedor";

            //Executa a query
            $resultado = $this->conexao->query($sql);


          	//Se não retornar nenhuma linha
          	if (mysqli_num_rows($resultado) == 0 && !$resultado)
		        return array('indicador_erro' => 1, 'dados' => null);

		    if (mysqli_num_rows($resultado) <> 0)
		    {
	            $linha    = mysqli_fetch_array($resultado);
	            $vendedor = array('id' => $linha['id_vendedor'], 'nome' => $linha['nome']);
	            $dados[]  = $vendedor;

	            //Busca os demais vendedores
	            $sql       = "SELECT 
								ven.id_vendedor, ven.nome
							FROM
								vendedor ven
							WHERE 
								ven.id_vendedor <> ".$vendedor['id']."
								AND ven.situacao = 0
							ORDER BY ven.nome";            
		    }
		    else 
		    {

	            //Busca os demais vendedores
	            $sql       = "SELECT 
								ven.id_vendedor, ven.nome
							FROM
								vendedor ven
							WHERE 
								ven.situacao = 0
							ORDER BY ven.nome";            		    	

		    }

            //Executa a query
            $resultado = $this->conexao->query($sql);

            //Retorna a listagem quando não há erro
            if(!$resultado)
               return array('indicador_erro' => 1, 'dados' => null);

            while($linha    = mysqli_fetch_array($resultado))
            {
            	$vendedor = array('id' => $linha['id_vendedor'], 'nome' => $linha['nome']);
            	$dados[]  = $vendedor;						
            }

			return array('indicador_erro' => 0, 'dados' => $dados);
    }	

    function buscarPorcentagemComissao($idVendedor)
    {

          $sql       = " SELECT 
                       porcentagem_comissao
                     FROM
                       vendedor
                      WHERE 
                      	id_vendedor = ".$idVendedor;
  
          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Se retornar algum erro
          if(!$resultado)
               return false;

          //Se não retornar nenhuma linha
          if (mysqli_num_rows($resultado) == 0)
               return false;

          $linha   = mysqli_fetch_array($resultado);
          if($linha['porcentagem_comissao'] >= 1.0)
             $linha['porcentagem_comissao'] = $linha['porcentagem_comissao']/100;

				  return $linha['porcentagem_comissao'];
    }


    function buscarVendedores()
    {

          $listaVendedores = array();

          $sql       = " SELECT 
                       id_vendedor, nome, telefone_01
                     FROM
                       vendedor
                      WHERE 
                        situacao = 0
                      ORDER BY nome;";
  
          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Se retornar algum erro
          if(!$resultado)
               return $listaVendedores;

          //Se não retornar nenhuma linha
          if (mysqli_num_rows($resultado) == 0)
               return $listaVendedores;



          while ($linha = mysqli_fetch_array($resultado))
          {
              $linha['nome'] = strtoupper($linha['nome']);
              //$linha['nome'] = ucwords($linha['nome']);

              $item = array('id_vendedor' => $linha['id_vendedor'], 'descricao' => $linha['nome']); 
              $listaVendedores[] = $item;
          }

          // Retorna a lista de vendedores
          return $listaVendedores;
    }    

}

?>