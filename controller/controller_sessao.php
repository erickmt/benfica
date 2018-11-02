<?php

/**
 * Sessao
 *
 * Operações relacionadas as variáveis globais, de sessão do sistema
 * 
 */
class Sessao {

	private $conexao;

	function Sessao($conexao)
	{
		$this->conexao = $conexao;
	}

	function alterarLojaBusca($id){
		session_start();
		$_SESSION['usuario']['lojaVenda'] = $id;
		$retorno = array('resultado' => 'sucesso');
	    return $retorno;
	}

	function criar($login, $senha){

		$model_usuario = new Model_Usuario($this->conexao);


		$buscaUsuario  = $model_usuario->buscarUsuario($login, $senha);
		if($buscaUsuario['indicador_erro'] == 1)
		{
	        $resultado = 'erro';	     
	        $descricao = 'Ocorreu um erro ao buscar o usuário na base de dados.';
		    $retorno   = array('resultado' => $resultado, 'descricao' => $descricao);		
		    return $retorno;
		}


		if ( $buscaUsuario['indicador_erro'] == 0 && $buscaUsuario['perfil'] == 'X' )
		{
	        $resultado = 'erro';	     
	        $descricao = 'Não foi localizado nenhum usuário a partir dos critérios informados.';
		    $retorno   = array('resultado' => $resultado, 'descricao' => $descricao);		
		    return $retorno;		    
		}

		$lojdaDesc = $model_usuario->buscarLojaDescricao($buscaUsuario['id_loja']);
		
		//Armazena os dados do usuário na sessão
		session_start();
		$_SESSION['usuario'] = array('id_usuario' => $buscaUsuario['id_usuario'], 'perfil' => $buscaUsuario['perfil'], 'id_loja' => $buscaUsuario['id_loja'], 'lojaVenda' => $buscaUsuario['id_loja'], 'lojaDescricao' => $lojdaDesc['descricao'] , 'login' => $login, 'senha' => $senha,'listaProdutos' => array(), 'formasPagamento' => array(), 'cliente' => array('idPerfilCliente' => null, 'idCliente' => null, 'creditoCliente' => null));
		$retorno             = array('resultado' => 'sucesso');
	    return $retorno;		    		
	}

	function verificaPermissaoExclusaoVenda()
	{

		$model_usuario = new Model_Usuario($this->conexao);

		session_start();
		$perfil = $model_usuario->buscaPerfilUsuario($_SESSION['usuario']['id_usuario'], false, false);

		if($perfil == false)
		{
		    $retorno             = array('resultado' => 'erro');
		    return $retorno;		    					
		}

		//Identifica a permissão da funcionalidade
		if($perfil == 'A' || $perfil == 'S')
			$permissao = 'S';
		else
			$permissao = 'N';

	    $retorno             = array('resultado' => 'sucesso', 'permissao' => $permissao);
	    return $retorno;		    					
	}

	function verificaPermissaoVenderConsignado()
	{

		$model_usuario = new Model_Usuario($this->conexao);

		session_start();
		$perfil = $model_usuario->buscaPerfilUsuario($_SESSION['usuario']['id_usuario'], false, false);

		if($perfil == false)
		{
		    $retorno             = array('resultado' => 'erro');
		    return $retorno;		    					
		}

		//Identifica a permissão da funcionalidade
		if($perfil == 'A')
			$permissao = 'S';
		else
			$permissao = 'N';

	    $retorno             = array('resultado' => 'sucesso', 'permissao' => $permissao);
	    return $retorno;		    					
	}

	function autenticarAdministradorSubGerente($login, $senha)
	{

		$model_usuario = new Model_Usuario($this->conexao);

		session_start();
		$perfil = $model_usuario->buscaPerfilUsuario(false, $login, $senha);
		if($perfil == false)
		{
		    $retorno             = array('resultado' => 'erro');
		    return $retorno;		    					
		}

		//Identifica a permissão da funcionalidade
		if($perfil == 'A')
			$permissao = "S";
		else
			$permissao = "N";

	    $retorno             = array('resultado' => 'sucesso', 'permissao' => $permissao);
	    return $retorno;		    					

	}
    
}

?>