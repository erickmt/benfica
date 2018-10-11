
    <?php

        //Autenticação
        require_once "library/conexao.php";
        require_once "model/model_usuario.php";
        require_once "controller/seguranca.php";

        $seguranca = new Seguranca($conexao);
        $seguranca->expulsaVisitante();
    ?>   