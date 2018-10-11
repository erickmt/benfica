<!DOCTYPE html>

    <?php
        /*/Autenticação */
        require_once "library/conexao.php";
        require_once "model/model_usuario.php";
        require_once "controller/seguranca.php";
        
        $seguranca = new Seguranca($conexao);
        $seguranca->protegePagina();
        
        require_once "model/model_venda.php";
        require_once "controller/controller_venda.php";
        
        $venda       = new Venda($conexao);
        $listarLojas = $venda->listarLojas(1);
    ?>  

    
<html lang="pt">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Benfica - Gerenciamento e Vendas</title>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css">

    <!-- Inclusão CSS-->
    <link rel="shortcut icon" href="library/benfica.png" />
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> 
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="dist/css/estilo.css" rel="stylesheet">
    <link href="vendor/morrisjs/morris.css" rel="stylesheet">
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<link rel="stylesheet" type="text/css" href="css/generator-base.css">
	<link rel="stylesheet" type="text/css" href="css/editor.bootstrap.min.css">
    
    <link href="css/estilo.css" rel="stylesheet" />
    
	<nav class="navbar navbar-default" role="navigation" style="margin-bottom: 0">

	<?php include "pages/auxiliar/cabecalho.php"; ?>

	<!-- Chamada do menu principal da página -->
	<?php include "pages/auxiliar/menu.php"; ?>	   
	
	</nav>	<!-- Chamada do cabeçalho da página -->


</head>

<body >
	
	<?php

        // Chamada da view principal
	    include "pages/view_caixa.php";

    ?>

    <!-- Inclusão JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/metisMenu/metisMenu.min.js"></script>
    <script src="vendor/raphael/raphael.min.js"></script>
    <script src="vendor/morrisjs/morris.min.js"></script>
    <script src="data/morris-data.js"></script>
    <script src="dist/js/sb-admin-2.js"></script>
    <script src="js/funcoes.js"></script>
    <script src="js/jquery.maskMoney.js"></script>
	
    <link rel="stylesheet" href="css/dialogo.css">
    <script type="text/javascript" charset="utf-8" src="js/dialogo.js"></script> 

    <script src="js/jquery.btechco.excelexport.js"></script>
    <script src="js/jquery.base64.js"></script>
    <script>
        $(document).ready(function () {        
            
            $("#gerarExcel").click(function () {
                var nomeLoja = $("#lojaBusca>option:selected").html();
                if (nomeLoja == null){
                    nomeLoja = ""
                    }
                $("#tabelaMovimentacaoCaixa").btechco_excelexport({
                    containerid: "tabelaMovimentacaoCaixa"
                , datatype: $datatype.Table
                , filename: "Relatório Caixa " + nomeLoja
                });
            });
        });
    </script>
</body>

</html>