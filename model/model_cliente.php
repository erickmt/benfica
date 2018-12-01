<?php

/**
 * Model_Cliente
 *
 * Interações com a tabela Cliente
 */
class Model_Cliente {

  private $conexao;

  function Model_Cliente($conexao)
  {
     $this->conexao = $conexao;
  }

  function trocaPerfil($id_selecionado){

    if (!isset($_SESSION)) {
      session_start();
    }  

    $id_cl = $id_selecionado;
    $pf_cl = $_SESSION['usuario']['cliente']['idPerfilCliente'];

    $sql_query = "SELECT dias_validade, id_perfil FROM perfil_cliente where id_perfil = 2;";

    $resultado = $this->conexao->query($sql_query);
    $linha     = mysqli_fetch_array($resultado);

    $diasValidade = $linha['dias_validade'];

    //Gravando no banco de dados !
    if($pf_cl == 'Atacadista' || $pf_cl  == 2){
    $sql = "UPDATE cliente set dta_validade = null, id_perfil = 1 where id_cliente = '$id_cl'";
    };
    if($pf_cl == 'Varejista' || $pf_cl  == 1){
    $sql = "UPDATE cliente set dta_validade = (current_date + INTERVAL ".$diasValidade." day), id_perfil = 2 where id_cliente = '$id_cl'";
    };

    $resultado = $this->conexao->query($sql);

    // Mudança para o perfil 1
    if($pf_cl == 'Atacadista' || $pf_cl  == 2){
      $_SESSION['usuario']['cliente']['idPerfilCliente'] == 1;
    };
    // Mudança para o perfil 2
    if($pf_cl == 'Varejista' || $pf_cl  == 1){
      $_SESSION['usuario']['cliente']['idPerfilCliente'] == 2;
    };
    
    return 'sucesso';
}

  function alterarSituacao($idCliente)
  { 

    $sql_query = "SELECT situacao FROM cliente where id_cliente = ".$idCliente.";";

    $resultado = $this->conexao->query($sql_query);
    $linha     = mysqli_fetch_array($resultado);

    $situacao = $linha['situacao'];
    echo $sql_query;

    $novaSituacao = 0;
    if($situacao == 0)
      $novaSituacao = 1;

    //Gravando no banco de dados !

    $sql = "UPDATE cliente set situacao = ".$novaSituacao." where id_cliente =".$idCliente.";";
    echo $sql;

    $resultado = $this->conexao->query($sql);

    return 'sucesso';
  }
    /**
     * buscarDadosBasicosCliente
     * @author Victor
     */
  function buscarDadosBasicosCliente($idCliente)
  {

          // Buscar os vendedores responsáveis
          $Vendedores = new Model_Vendedor($this->conexao);          

          //Monta e executa a query
          $sql       = " SELECT 
                           cli.nome, cli.telefone_01, per.descricao, cpf, cep, bairro, logradouro, cidade, numero, estado
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
          return array('retorno' => 'sucesso', 'dados' => array( 'nome' => $linha['nome'], 'telefone' => $linha['telefone_01'], 'perfil' => $linha['descricao'], 'cpf' => $linha['cpf'], 'cep' => $linha['cep'], 'bairro' => $linha['bairro'], 'logradouro' => $linha['logradouro'], 'numero' => $linha['numero'], 'cidade' => $linha['cidade'], 'estado' => $linha['estado']));
  }


    /**
     * buscarClientePorId
     * @author Victor
     */
	function buscarClientePorId($idCliente)
	{

          // Buscar os vendedores responsáveis
          $Vendedores = new Model_Vendedor($this->conexao);          

          //Monta e executa a query
          $sql       = " SELECT 
                           cli.id_cliente,cli.nome,cli.telefone_01,cli.valor_credito,cli.id_perfil,per.descricao, cli.logradouro, cli.numero, cli.bairro, cli.cidade, cli.estado, cli.cep, cli.cpf, cli.email
                         FROM
                           cliente cli,
                           perfil_cliente per
                         WHERE 
                           cli.id_cliente    = ".$idCliente."
                           AND cli.id_perfil = per.id_perfil
                           AND cli.situacao = 0;";

          //Executa a query
          $resultado = $this->conexao->query($sql);

          //Retorna o dado quando não há erro
          if(!$resultado)
               return array('indicador_erro' => 1, 'dados' => null);

          //Leitura das informações do cliente 
          $linha   = mysqli_fetch_array($resultado);
          
          //Tratamento dos valores retornados
          if(!$linha['valor_credito'])
               $linha['valor_credito'] = 0.0;

          //Verifica se o telefone está preenchido na base de dados
          if(empty($linha['telefone_01']) == true)
              $linha['telefone_01'] = "";

          //Tratamento das strings retornada no banco
          $linha['nome']      = strtolower($linha['nome']);
          $linha['nome']      = ucwords($linha['nome']);
          $linha['descricao'] = strtolower($linha['descricao']);
          $linha['descricao'] = ucwords($linha['descricao']);          

          $cliente = array('id_cliente' => $linha['id_cliente'], 'nome' => $linha['nome'], 'telefone' => $linha['telefone_01'], 'valor_credito' => $linha['valor_credito'], 'id_perfil' => $linha['id_perfil'], 'perfil' => $linha['descricao'], 'cep' => $linha['cep'], 'logradouro' => $linha['logradouro'], 'numero' => $linha['numero'], 'bairro' => $linha['bairro'], 'cidade' => $linha['cidade'], 'estado' => $linha['estado'], 'cpf' => $linha['cpf'], 'email' => $linha['email']);

          //Busca os vendedores do cliente
          $listaVendedores = $Vendedores->buscarVendedoresCliente($idCliente);
          if($listaVendedores['indicador_erro'] == 1)
               return array('indicador_erro' => 1, 'dados' => null);

          $cliente['responsaveis'] = $listaVendedores['dados'];
          return array('indicador_erro' => 0, 'dados' => $cliente);
	}


    /**
     * buscaIdClientePorNome
     * @author Victor
     */
     function buscaIdClientePorNome($nomeCompleto, $cpf)
     {

          //Monta e executa a query
          $sql       = " SELECT 
                           cli.id_cliente
                         FROM
                           cliente cli
                         WHERE 
                         cli.situacao = 0 ";
         

          //Se o nome tiver sido enviado
          if($nomeCompleto != false)
          {
              //$nomeCompleto =  utf8_decode($nomeCompleto);
              $sql = $sql." and cli.nome like '%".$nomeCompleto."%' ";
          }else{
            //Se o nome tiver sido enviado
            if($cpf != false)
            {
                $sql = $sql." and cli.cpf = '".$cpf."'";
            }
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
                    $dados     = array('id_cliente' => $linha['id_cliente']);
                    $retorno[] = $dados;
               }
               return array('indicador_erro' => 0, 'dados' => $retorno);
          }
     }



    /**
     * alterarCreditoCliente
     * @author Victor
     */
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
     */
    function indicadorClienteAtacadistaValido($idCliente)
    {


          //Monta e executa a query
          $sql1       = " SELECT 
                           id_perfil
                         FROM
                           cliente
                         WHERE 
                           id_cliente    = ".$idCliente;

          //Executa a query
          $resultado1 = $this->conexao->query($sql1);

          //Retorna o dado quando não há erro
          if(!$resultado1)
               return false;

          $linha1   = mysqli_fetch_array($resultado1);

          if($linha1['id_perfil'] == 1)
            return false;

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
     */
     function atualizarPerfilCliente($idCliente, $novoIdPerfil, $observacao = false, $novaDataValidade = false)
     {

            if (!isset($_SESSION)) {
              session_start();
            } 

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
                    (".$_SESSION['usuario']['id_usuario'].", 'A','CLIENTE', 'ID_PERFIL' ,".
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
                    (".$_SESSION['usuario']['id_usuario'].", 'A','CLIENTE', 'DTA_VALIDADE' ,".
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
     */
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
     */
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


	function atualizaDadosCliente($cpf,  $cep, $rua, $numero, $bairro, $cidade, $estado, $idCliente, $email)
	{
		$sql = "update cliente set ";
		$i = 0;
		if($cpf != false){
			$sql = $sql." cpf = '".$cpf."'";
			$i = 1;
		}
		if($cep != false){
			if($i == 1)
				$sql = $sql.", ";
			$sql = $sql." cep = '".$cep."'";
			$i = 1;
		}		
		if($rua != false)
		{
			if($i == 1)
				$sql = $sql.", ";
			$sql = $sql." logradouro = '".$rua."'";
			$i = 1;
		}		
		if($numero != false)
		{
			if($i == 1)
				$sql = $sql.", ";
			$sql = $sql." numero = '".$numero."'";
			$i = 1;
		}			
		if( $bairro != false)
		{
			if($i == 1)
				$sql = $sql.", ";
			$sql = $sql." bairro = '".$bairro."'";
			$i = 1;
		}		
		if($cidade != false)
		{
			if($i == 1)
				$sql = $sql.", ";
			$sql = $sql." cidade = '".$cidade."'";
			$i = 1;
		}
		if($estado != false)
		{
			if($i == 1)
				$sql = $sql.", ";
			$sql = $sql." estado = '".$estado."'";
			$i = 1;
		}
    if($email != false)
    {
      if($i == 1)
        $sql = $sql.", ";
      $sql = $sql." email = '".$email."'";
      $i = 1;
    }
		
		if($i == 1)
			$sql = $sql." where id_cliente = ".$idCliente.";";

		$resultado     = $this->conexao->query($sql);           

          if(!$resultado)
               return false;
          else 
               return true;
	}
    /**
     * voltarUltimoPerfilCliente
     * @author Victor
     */
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

          //Se não retornar nenhuma linha
          if (mysqli_num_rows($resultado) == 0)
              return true;

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

              //Se não encontrar a data de validade anterior
              if(!$resultado2)
                   return $this->atualizarPerfilCliente($idCliente, $linha['conteudo_anterior'], 'EXCLUSAO DE VENDA', false);

              $linha2   = mysqli_fetch_array($resultado2);
              return $this->atualizarPerfilCliente($idCliente, $linha['conteudo_anterior'], 'EXCLUSAO DE VENDA', $linha2['conteudo_anterior']);
          }
          else 
              return $this->atualizarPerfilCliente($idCliente, $linha['conteudo_anterior'], 'EXCLUSAO DE VENDA', false);


  }
  
  function cadastroCliente($nome, $cpf, $orgao_expeditor, $data_nascimento, $mae, $vendedor, $telefone, $endereco, $bairro, $cep, $cidade, $estado, $pais, $observacao)
  {


	  $sql = "INSERT INTO cliente(nome, cpf, orgao_expeditor, dta_nascimento, nome_mae, id_vendedor, telefone_01, logradouro, bairro, cep, cidade, estado, pais, observacao,  id_perfil) VALUES ('".$nome."','".$cpf."','".$orgao_expeditor."','".$data_nascimento."','".$mae."',".$vendedor.",".$telefone.",'".$endereco."','".$bairro."','".$cep."','".$cidade."','".$estado."','".$pais."','".$observacao."', 1);";
	  
	  $resultado = $this->conexao->query($sql);

    //Se retornar algum erro
    if(!$resultado)
         return array('indicador_erro' => 1, 'dados' => null);          
  
		return array('retorno' => 'sucesso', 'dados' => null);
  }


}

?>