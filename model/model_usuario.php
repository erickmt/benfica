<?php

/**
 * Model_Usuario
 *
 * Interações com a tabela Usuario 
 */
class Model_Usuario {

		private $conexao;

		function Model_Usuario($conexao)
		{
		    $this->conexao = $conexao;
		}

        function imprimir($html)
        {
            $arquivo = 'msgcontatos.xls';

            // Configurações header para forçar o download
            header ("Expires: Mon, 07 Jul 2016 05:00:00 GMT");
            header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
            header ("Cache-Control: no-cache, must-revalidate");
            header ("Pragma: no-cache");
            header ("Content-type: application/x-msexcel");
            header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
            header ("Content-Description: PHP Generated Data" );
            // Envia o conteúdo do arquivo
            echo $html;

            return 'sucesso';
        }

        function buscarLojaDescricao($idLoja){

            //Monta e executa a query
            $sql       = "select descricao
                        from loja
                        where id = ".$idLoja.";";

            //Executa a query
            $resultado = $this->conexao->query($sql);

            //Se retornar algum erro
            if(!$resultado)
                return array('indicador_erro' => 1);

            //Se não retornar nenhuma linha
            if (mysqli_num_rows($resultado) == 0)
                return array('indicador_erro' => 0, 'perfil' => 'X');          

            //Leitura de uma linha
            $retorno        = array();
            $linha          = mysqli_fetch_array($resultado);

            //Retorna o ID perfil do usuário
            return array('indicador_erro' => 0, 'descricao' => $linha['descricao']);
        }


        function alterarSenhaUsuario($idUsuario, $senha){

            //Monta e executa a query
            $sql       = "update usuario set senha = '".$senha."' where id_usuario = ".$idUsuario;

            //Executa a query
            $resultado = $this->conexao->query($sql);

            //Se retornar algum erro
            if(!$resultado)
                return array('indicador_erro' => 1);

            return array('indicador_erro' => 0);
        }

		function buscarUsuario($login, $senha){

            //Remove os espaços do início e fim da string de login
            $login = trim($login);
            $login = strtolower($login);
            $senha = md5($senha);

            //Monta e executa a query
            $sql       = "select id_usuario, perfil, id_loja
                        from usuario
                        where login = '".$login."'
                        and senha = '".$senha."' and situacao = 0";

            //Executa a query
            $resultado = $this->conexao->query($sql);

            //Se retornar algum erro
            if(!$resultado)
               return array('indicador_erro' => 1);

            //Se não retornar nenhuma linha
            if (mysqli_num_rows($resultado) == 0)
               return array('indicador_erro' => 0, 'perfil' => 'X');          

            //Leitura de uma linha
            $retorno        = array();
            $linha          = mysqli_fetch_array($resultado);

            //Retorna o ID perfil do usuário
            return array('indicador_erro' => 0, 'perfil' => $linha['perfil'], 'id_usuario' => $linha['id_usuario'], 'id_loja' => $linha['id_loja']);
		}

        function confirmaUsuario($id_usuario, $perfil, $login, $senha){

            //Remove os espaços do início e fim da string de login
            $login = trim($login);
            $login = strtolower($login);
            $senha = md5($senha);

            //Monta e executa a query
            $sql      = "select count(*) as registro
                        from usuario
                        where login     = '".$login."'
                        and senha       = '".$senha."'
                        and perfil      = '".$perfil."'
                        and id_usuario  = ".$id_usuario." and situacao = 0";

            //Executa a query
            $resultado = $this->conexao->query($sql);

            //Se retornar algum erro
            if(!$resultado)
               return false;

            //Se não retornar nenhuma linha
            if (mysqli_num_rows($resultado) == 0)
               return false;

            //Leitura de uma linha
            $retorno        = array();
            $linha          = mysqli_fetch_array($resultado);

            if($linha['registro'] == 0)
                return false;
            else 
                return true;
        }        


        function buscaPerfilUsuario($id_usuario, $login, $senha){

            //Remove os espaços do início e fim da string de login
            $login = trim($login);
            $login = strtolower($login);
            $senha = md5($senha);

            //Inicialização da variável SQL
            $sql = "";

            if ($id_usuario != false)
            {
                //Monta e executa a query
                $sql       = "select perfil
                            from usuario
                            where situacao = 0 and id_usuario = ".$id_usuario;                
            }
            else 
            {
                //Monta e executa a query
                $sql       = "select perfil
                            from usuario
                            where situacao = 0 and login = '".$login."'
                            and senha = '".$senha."'";
            }

            //Executa a query
            $resultado = $this->conexao->query($sql);

            //Se retornar algum erro
            if(!$resultado)
               return false;

            //Se não retornar nenhuma linha
            if (mysqli_num_rows($resultado) == 0)
               return false;

            //Leitura de uma linha
            $retorno        = array();
            $linha          = mysqli_fetch_array($resultado);


            //Retorna o perfil do usuário
            return $linha['perfil'];
        }

}

?>