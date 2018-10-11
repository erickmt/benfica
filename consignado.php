<!DOCTYPE html>

    <?php
        //Autenticação
        require_once "library/conexao.php";
        require_once "controller/seguranca.php";
        require_once "model/model_produto.php";
        require_once "controller/controller_relatorio.php";


        $seguranca   = new Seguranca($conexao);
        $seguranca->paginaExclusivaAdministrador();
        
        $relatorio       = new Relatorio($conexao);
        $listarTipoProduto = $relatorio->listarTipoProduto();
        

        require_once "model/model_venda.php";
        require_once "controller/controller_venda.php";
        
        $venda       = new Venda($conexao);
        $listarLojas = $venda->listarLojas(0);
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
    <link href="css/select2.min.css" rel="stylesheet" />
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
        require_once "pages/view_consignado.php";
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
    <script src="js/select2.min.js"></script>
    <link rel="stylesheet" href="css/dialogo.css">
    <script type="text/javascript" charset="utf-8" src="js/dialogo.js"></script> 
    
    <script>
        $('.js-example-basic-multiple').select2();
    </script>
    <script src="js/jquery.btechco.excelexport.js"></script>
    <script src="js/jquery.base64.js"></script>
        
    <script>
        $(document).ready(function () {
            $("#gerarExcel").click(function () {
                var nomeLoja = $("#lojaBusca>option:selected").html();
                $("#tabelaVendasConsignadas").btechco_excelexport({
                    containerid: "tabelaVendasConsignadas"
                , datatype: $datatype.Table
                , filename: nomeLoja + " - Relatório de Peças Venda Consignada"
                });
            });
        });
    // tell the embed parent frame the height of the content
    if (window.parent && window.parent.parent){
      window.parent.parent.postMessage(["resultsFrame", {
        height: document.body.getBoundingClientRect().height,
        slug: "4rx3896m"
      }], "*")
    }
  </script>
</body>

</html>