


<?php

/**
 * Seguranca
 *
 * Operações relacionadas a segurança / autenticação do sistema
 * 
 */
class Seguranca {

		private $conexao;

		function Seguranca($conexao)
		{
		    $this->conexao = $conexao;

			if (!isset($_SESSION)) {
				session_start();
			}		    
		}

		function protegePagina() {

		  	if (!isset($_SESSION['usuario']['id_usuario']) AND !isset($_SESSION['usuario']['perfil']) AND !isset($_SESSION['usuario']['login']) AND !isset($_SESSION['usuario']['senha'])) {

		    	// Não há usuário logado, manda pra página de login
		    	$this->expulsaVisitante();

		  	}
		  	else {

		  		//Se os dados da base não baterem com os dados da base, redireciona para a página de login
		  		$model_usuario = new Model_Usuario($this->conexao);
				if($model_usuario->confirmaUsuario($_SESSION['usuario']['id_usuario'], $_SESSION['usuario']['perfil'], $_SESSION['usuario']['login'], $_SESSION['usuario']['senha']) != true)
					$this->expulsaVisitante();
		  	}
		}


		function paginaExclusivaAdministrador() {

		  	if (!isset($_SESSION['usuario']['id_usuario']) AND !isset($_SESSION['usuario']['perfil']) AND !isset($_SESSION['usuario']['login']) AND !isset($_SESSION['usuario']['senha'])) {

		    	// Não há usuário logado, manda pra página de login
		    	$this->expulsaVisitante();

		  	}
		  	else {

		  		if ($_SESSION['usuario']['perfil'] != 'A')
		  			$this->expulsaVisitante();
		  	}
		}		

		function expulsaVisitante() {

			// Destrói a sessão e redireciona para a página de login
			session_destroy();
			header("Location: login.php");
		  
		}		

}

?>

