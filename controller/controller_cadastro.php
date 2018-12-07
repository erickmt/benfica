<?php

/**
 * Venda
 *
 * Operações relacionadas a página de Venda
 * 
 */
class Cadastro {

	private $conexao;

	function Cadastro($conexao)
	{
		$this->conexao = $conexao;
	}
	

	function alterarSenha($idUsuario, $senha){

		if(!isset($idUsuario) || empty($idUsuario) || !isset($senha) || empty($senha))
			$retorno 	= 'Dados informados inválidos';

		$Model_Usuario = new Model_Usuario($this->conexao);
		$retorno = $Model_Usuario->alterarSenhaUsuario($idUsuario, $senha);

		if($retorno['indicador_erro'] == 1)
			$retorno 	= 'Erro ao atualizar senha do usuário';
		
		$retorno 	= 'Senha atualizada com sucesso';
		return $retorno;
	}

	function cadastroCliente($nome, $identidade, $cpf, $orgao_expeditor, $data_nascimento, $mae, $vendedor, $telefone, $endereco, $bairro, $cep, $cidade, $estado, $pais, $observacao)
	{

	 if(!isset($identidade)); 
	 	$identidade = NULL;
     if(!isset($cpf));
	 	$cpf = NULL;
	 if(!isset($orgao_expeditor));
	 	$orgao_expeditor = NULL;
     if(!isset($data_nascimento)); 
	 	$data_nascimento = NULL;
     if(!isset($mae));
	 	$mae = NULL;
     if(!isset($telefone)); 
	 	$telefone = NULL;
     if(!isset($endereco)); 
	 	$endereco = NULL;
     if(!isset($bairro)); 
	 	$bairro = NULL;
     if(!isset($cep)); 
	 	$cep = NULL;
     if(!isset($cidade));
	 	$cidade = NULL;
     if(!isset($estado)); 
	 	$estado = NULL;
     if(!isset($pais)); 
	 	$pais = NULL;
     if(!isset($observacao))
     	$observacao = NULL;
      $vendedor = 1;


		$cliente = new Model_Cliente($this->conexao);
		
		$retorno = $cliente->cadastroCliente($nome, $identidade, $cpf, $orgao_expeditor, $data_nascimento, $mae, $vendedor, $telefone, $endereco, $bairro, $cep, $cidade, $estado, $pais, $observacao);
		
		return $retorno;
	}

	function alterarSituacao($idCliente)
	{
		$cliente = new Model_Cliente($this->conexao);
		
		$retorno = $cliente->alterarSituacao($idCliente);
		
		return $retorno;
	}

	function atualizaDadosCliente($cpf = false, $cep = false, $rua = false, $numero = false, $bairro = false, $cidade = false, $estado = false, $email = false)
	{
		if (!isset($_SESSION)) {
		  session_start();
		}
		
		$idCliente = $_SESSION['usuario']['cliente']['idCliente'];
		
		if(isset($cpf))
		{
			$cpf = str_replace('.', '', $cpf);
			$cpf = str_replace('-', '', $cpf);
			$cpf = str_replace('/', '', $cpf);
		}
		if(isset($cep))
		{
			$cep = str_replace('.', '', $cep);
			$cep = str_replace('-', '', $cep);
		}

		$cliente = new Model_Cliente($this->conexao);
		$retorno = $cliente->atualizaDadosCliente($cpf,  $cep, $rua, $numero, $bairro, $cidade, $estado, $idCliente, $email);
		
		return $retorno;
	}

}