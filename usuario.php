<!DOCTYPE html>

  <?php
        //Autenticação
        require_once "library/conexao.php";
        require_once "model/model_usuario.php";
        require_once "controller/seguranca.php";

        $seguranca   = new Seguranca($conexao);
        $seguranca->paginaExclusivaAdministrador();

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

    <!-- Inclusão CSS-->
    <link rel="shortcut icon" href="library/benfica.png" />
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> 
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="vendor/morrisjs/morris.css" rel="stylesheet">
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<link rel="stylesheet" type="text/css" href="css/generator-base.css">
	<link rel="stylesheet" type="text/css" href="css/editor.bootstrap.min.css">

	<script type="text/javascript" charset="utf-8" src="css/datatables.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="js/dataTables.editor.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="js/editor.bootstrap.min.js"></script>
			
   <nav class="navbar navbar-default" role="navigation" style="margin-bottom: 0">

    <?php include "pages/auxiliar/cabecalho.php"; ?>

    <!-- Chamada do menu principal da página -->
    <?php include "pages/auxiliar/menu.php"; ?>    
    
    </nav>  <!-- Chamada do cabeçalho da página -->



</head>

<body >
	
    
	<?php
        // Chamada da view principal
	    include "pages/view_usuario.php";
    ?>
	
	
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