
<!DOCTYPE html>

    <?php
        //Autenticação
        require_once "library/conexao.php";
        require_once "model/model_usuario.php";
        require_once "model/model_vendedor.php";
        require_once "controller/seguranca.php";
        require_once "controller/controller_relatorio.php";

        $seguranca   = new Seguranca($conexao);
        $seguranca->paginaExclusivaAdministrador();

        require_once "model/model_venda.php";
        require_once "controller/controller_venda.php";
        
        $venda       = new Venda($conexao);
        $listarLojas = $venda->listarLojas(0);
        //session_start();
    ?> 

<html lang="pt">

<head>



    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="jquery.btechco.excelexport.js"></script>
<script src="jquery.base64.js"></script>

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
    <link href="dist/css/estilo.css" rel="stylesheet">
    <link href="vendor/morrisjs/morris.css" rel="stylesheet">
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

   

</head>

<body>
    


    <?php
        // Chamada da view principal
        require_once "pages/view_relatorio_faturamento.php";
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
    <script src="js/jquery.btechco.excelexport.js"></script>
    <script src="js/jquery.base64.js"></script>
    
    <script>
        $(document).ready(function () {
            
            $("#limpar").click(function () {
                $('#tabelaTrimestre tbody').empty();
            });
            
            $("#gerarExcelMensal").click(function () {
                var nomeLoja = $("#lojaBusca>option:selected").html();
                $("#tabelaValorMensal").btechco_excelexport({
                    containerid: "tabelaValorMensal"
                , datatype: $datatype.Table
                , filename: nomeLoja + " - Relatório mensal"
                });
            });

            $("#gerarExcelAnual").click(function () {
                var nomeLoja = $("#lojaBusca2>option:selected").html();
                $("#tabelaTrimestre").btechco_excelexport({
                    containerid: "tabelaTrimestre"
                , datatype: $datatype.Table
                , filename: nomeLoja + " - Relatório anual"
                });
            });
        });
    </script>
</body>

</html>