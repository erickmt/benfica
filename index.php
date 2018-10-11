
<!DOCTYPE html>

<?php

        require_once "library/conexao.php";
        require_once "model/model_usuario.php";
        require_once "controller/seguranca.php";

        $seguranca = new Seguranca($conexao);
        $seguranca->protegePagina();

        //session_start();
?>

<html lang="pt">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Benfica - Gerenciamento e Vendas</title>

    <!-- Inclusão CSS -->
    <link rel="shortcut icon" href="library/benfica.png" />
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="vendor/morrisjs/morris.css" rel="stylesheet">
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<body>

	<?php if ($_SESSION['usuario']['perfil'] == 'C'): ?>
        <!-- Chamada da view principal -->
        <?php header("Location: consulta.php"); ?>

    <?php endif; ?>  

    <?php if ($_SESSION['usuario']['perfil'] != 'C'): ?>
    <!-- Chamada da view principal -->
        <?php require_once "pages/view_index.php"; ?>
   
    <?php endif; ?>

    <!-- Inclusão JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/metisMenu/metisMenu.min.js"></script>
    <script src="vendor/raphael/raphael.min.js"></script>
    <script src="vendor/morrisjs/morris.min.js"></script>
    <script src="data/morris-data.js"></script>
    <script src="dist/js/sb-admin-2.js"></script>

</body>

</html>

