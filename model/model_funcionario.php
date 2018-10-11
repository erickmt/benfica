<?php

/**
 * Model_Funcionario
 *
 * Interações com a tabela Funcionario
 */
class Model_Funcionario {

  private $conexao;

  function Model_Funcionario($conexao)
  {
     $this->conexao = $conexao;
  }

    /**
     * buscarDadosBasicosCliente
     * @author Victor
     */
    /*
  function buscarDadosBasicosCliente($idCliente)
  {

          // Buscar os vendedores responsáveis
          $Vendedores = new Model_Vendedor($this->conexao);          

          //Monta e executa a query
          $sql       = " SELECT 
                           cli.nome, cli.numero_rg, cli.telefone_01, per.descricao
                         FROM
                           cliente cli,
                           perfil_cliente per
                         WHERE 
                           cli.id_perfil = per.id_perfil and 
                           cli.id_cliente    = ".$idCliente;

          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Retorna o dado quando não há erro
          if(!$resultado)
               return array('retorno' => 'erro', 'descricao' => 'Ocorreu um erro inesperado na busca dos dados básicos do cliente.');

          //Leitura das informações do cliente 
          $linha   = mysqli_fetch_array($resultado);

          //Tratamento das strings retornada no banco
          $linha['nome']      = strtolower($linha['nome']);
          $linha['nome']      = ucwords($linha['nome']);
          $linha['descricao'] = strtolower($linha['descricao']);
          $linha['descricao'] = ucwords($linha['descricao']);          

          //Retorna os dados básicos do cliente
          return array('retorno' => 'sucesso', 'dados' => array( 'nome' => $linha['nome'], 'rg' => $linha['numero_rg'], 'telefone' => $linha['telefone_01'], 'perfil' => $linha['descricao']   ));
  }

*/
    /**
     * buscarFuncionarioPorId
     * @author Victor
     */
	function buscarFuncionarioPorId($idFuncionario)
	{

          // Buscar os vendedores responsáveis
          $Vendedores = new Model_Vendedor($this->conexao);          

          //Monta e executa a query
          $sql       = " SELECT 
                           ven.id_vendedor,ven.nome,ven.numero_rg,ven.telefone_01
                         FROM
                           vendedor ven
                         WHERE 
                           ven.id_vendedor    = ".$idFuncionario;

          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Retorna o dado quando não há erro
          if(!$resultado)
               return array('indicador_erro' => 1, 'dados' => null);

          //Leitura das informações do cliente 
          $linha   = mysqli_fetch_array($resultado);

          //Verifica se o telefone está preenchido na base de dados
          if(empty($linha['telefone_01']) == true)
              $linha['telefone_01'] = "";

          //Tratamento das strings retornada no banco
          $linha['nome']      = strtolower($linha['nome']);
          $linha['nome']      = ucwords($linha['nome']);

          $funcionario = array('id_funcionario' => $linha['id_vendedor'], 'nome' => $linha['nome'], 'rg' => $linha['numero_rg'], 'telefone' => $linha['telefone_01']);

          return array('indicador_erro' => 0, 'dados' => $funcionario);
	}



    /**
     * buscaIdFuncionarioPorNome
     * @author Victor
     */
     function buscaIdFuncionarioPorNome($nomeCompleto, $numeroIdentidade)
     {

          //Monta e executa a query
          $sql       = " SELECT 
                           ven.id_vendedor
                         FROM
                           vendedor ven
                         WHERE 
                           ven.situacao = 0 ";
         

          //Se o nome tiver sido enviado
          if($nomeCompleto != false)
          {
              //$nomeCompleto =  utf8_decode($nomeCompleto);
              $sql = $sql." AND ven.nome like '%".$nomeCompleto."%' ";
          }

          //Se o nome tiver sido enviado
          if($numeroIdentidade != false)
          {
              $sql = $sql." AND ven.numero_rg = '".$numeroIdentidade."'";
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
                    $dados     = array('id_funcionario' => $linha['id_vendedor']);
                    $retorno[] = $dados;
               }
               return array('indicador_erro' => 0, 'dados' => $retorno);
          }
     }

/*


    /**
     * alterarCreditoCliente
     * @author Victor
     
     function alterarCreditoCliente($idCliente, $novoValorCredito)
     {

          $sql           = "UPDATE cliente set valor_credito = ".$novoValorCredito." , dta_atualizacao_credito = current_timestamp where id_cliente = ".$idCliente.";";
          $resultado     = $this->conexao->query($sql);           

          if(!$resultado)
               return false;
          else 
               return true;
     }



    /**
     * indicadorClienteAtacadistaValido
     * @author Victor
     
    function indicadorClienteAtacadistaValido($idCliente)
    {

          //Monta e executa a query
          $sql       = " SELECT 
                           dta_validade, current_date as dta_atual
                         FROM
                           cliente
                         WHERE 
                           id_cliente    = ".$idCliente;

          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Retorna o dado quando não há erro
          if(!$resultado)
               return false;

          //Leitura das informações do cliente 
          $linha   = mysqli_fetch_array($resultado);

          if($linha['dta_validade'] < $linha['dta_atual'])
              return false;
          else 
              return true;
  }


    /**
     * alterarCreditoCliente
     * @author Victor
     
     function atualizarPerfilCliente($idCliente, $novoIdPerfil, $observacao = false, $novaDataValidade = false)
     {

            if ($observacao == false)
              $observacao = '';

            // Busca a quantidade de dias para validade de um cliente Atacadista
              $sql5       = " SELECT 
                               dias_validade
                             FROM
                               perfil_cliente
                             WHERE 
                               id_perfil = 2";

              //Executa a query
              $resultado = $this->conexao->query($sql5);

              //Retorna o dado quando não há erro
              if(!$resultado)
                   return false;

              $linha          = mysqli_fetch_array($resultado);
              $diasValidade   = $linha['dias_validade'];



          //Busca como a informação estava antes da alteração
              //Monta e executa a query
              $sql1       = " SELECT 
                               a.id_perfil, a.dta_validade
                             FROM
                               cliente a
                             WHERE 
                               a.id_cliente    = ".$idCliente;

              //Executa a query
              $resultado = $this->conexao->query($sql1);

              //Retorna o dado quando não há erro
              if(!$resultado)
                   return false;

              //Leitura das informações do cliente 
              $linha          = mysqli_fetch_array($resultado);
              $antigoIdPerfil = $linha['id_perfil'];
              if ($antigoIdPerfil == 2)
                $antigaData   = $linha['dta_validade'];
              else 
                $antigaData   = 'NULL';


              //Grava a alteração do perfil
              if($novoIdPerfil == 2 && $novaDataValidade == false)
                  $sql           = "UPDATE cliente set id_perfil = 2, dta_validade = (current_date + INTERVAL ".$diasValidade." day) , dta_atualizacao = current_date where id_cliente = ".$idCliente.";";                
              else  if ($novoIdPerfil == 2)
                  $sql           = "UPDATE cliente set id_perfil = 2, dta_validade = '".$novaDataValidade."', dta_atualizacao = current_date where id_cliente = ".$idCliente.";";
              else 
                  $sql           = "UPDATE cliente set id_perfil = 1, dta_validade = null , dta_atualizacao = current_date where id_cliente = ".$idCliente.";";
              $resultado     = $this->conexao->query($sql);           
              if(!$resultado)
                   return false;


              //Busca como a informação ficou
              $sql2       = " SELECT 
                               id_perfil, dta_validade
                             FROM
                               cliente
                             WHERE 
                               id_cliente    = ".$idCliente;
              $resultado = $this->conexao->query($sql2);
              if(!$resultado)
                   return false;
              $linha        = mysqli_fetch_array($resultado);
              $novoIdPerfil = $linha['id_perfil'];
              if ($novoIdPerfil == 2)
                $novaData   = $linha['dta_validade'];
              else 
                $novaData   = 'NULL';                 


              //Grava os novos registros na tabela de log

                  //Monta e executa a query - Alteração do ID_PERFIL
                  $sql3       = " 
                  insert into log (
                    id_login_usuario, 
                    tipo_operacao, 
                    nome_tabela, 
                    nome_atributo, 
                    conteudo_anterior, 
                    conteudo_atual, 
                    observacao, id_cliente)
                  values 
                    (1, 'A','CLIENTE', 'ID_PERFIL' ,".
                     $antigoIdPerfil.",".
                     $novoIdPerfil.",'".$observacao."', ".$idCliente.");";

                  //Executa a query
                  $resultado = $this->conexao->query($sql3);

                  //Retorna o dado quando não há erro
                  if(!$resultado)
                       return false;

                  //Monta e executa a query - Alteração da DTA_VALIDADE
                  $sql4       = " 
                  insert into log (
                    id_login_usuario, 
                    tipo_operacao, 
                    nome_tabela, 
                    nome_atributo, 
                    conteudo_anterior, 
                    conteudo_atual, 
                    observacao)
                  values 
                    (1, 'A','CLIENTE', 'DTA_VALIDADE' ,".
                     $antigaData.",".
                     $novaData.",'".$observacao."');";

                  //Executa a query
                  $resultado = $this->conexao->query($sql4);

                  //Retorna o dado quando não há erro
                  if(!$resultado)
                       return false;

          //Operações executadas com sucesso - retorna true para dar sequência aos procedimentos
          return true;
     }  



    /**
     * atualizarDataUltimaCompra
     * @author Victor
     
     function atualizarDataUltimaCompra($idCliente, $data = false)
     {

          $sql = "";

          if ($data == false)
            $sql           = "UPDATE cliente set dta_ultima_compra = null where id_cliente = ".$idCliente.";";
          else if ($data == 'atual')
            $sql           = "UPDATE cliente set dta_ultima_compra = current_date where id_cliente = ".$idCliente.";";
          else 
            $sql           = "UPDATE cliente set dta_ultima_compra = '".$data."' where id_cliente = ".$idCliente.";";

          $resultado     = $this->conexao->query($sql);           

          if(!$resultado)
               return false;
          else 
               return true;
     }       



    /**
     * buscarCreditoCliente
     * @author Victor
     *
    function buscarCreditoCliente($idCliente)
    {
          $sql       = " SELECT 
                           valor_credito
                         FROM
                           cliente
                         WHERE 
                           id_cliente    = ".$idCliente;
          $resultado = $this->conexao->query($sql);
          if(!$resultado)
               return false;
          $linha   = mysqli_fetch_array($resultado);
          return $linha['valor_credito'];
  } 



    /**
     * voltarUltimoPerfilCliente
     * @author Victor
     *
  function voltarUltimoPerfilCliente($idCliente) 
  {


          $sql       = " SELECT 
                           conteudo_anterior
                         FROM
                           log
                         WHERE 
                          nome_tabela       = 'CLIENTE'
                          and nome_atributo = 'ID_PERFIL'
                          and id_cliente    = ".$idCliente."
                         ORDER BY id_log DESC";
                           
          $resultado = $this->conexao->query($sql);
          if(!$resultado)
               return false;
          $linha   = mysqli_fetch_array($resultado);


          if($linha['conteudo_anterior'] == 2)
          {

              $sql2       = " SELECT 
                               conteudo_anterior
                             FROM
                               log
                             WHERE 
                              nome_tabela       = 'CLIENTE'
                              and nome_atributo = 'DTA_VALIDADE'
                              and id_cliente    = ".$idCliente."
                             ORDER BY id_log DESC";
                               
              $resultado2 = $this->conexao->query($sql2);
              if(!$resultado2)
                   return false;
              $linha2   = mysqli_fetch_array($resultado2);


              return $this->atualizarPerfilCliente($idCliente, $linha['conteudo_anterior'], 'EXCLUSAO DE VENDA', $linha2['conteudo_anterior']);
          }
          else 
              return $this->atualizarPerfilCliente($idCliente, $linha['conteudo_anterior'], 'EXCLUSAO DE VENDA', false);


  }

*/
}

?>