<!DOCTYPE html>

    <?php
        /*/Autenticação */
        require_once "library/conexao.php";
        require_once "model/model_usuario.php";
        require_once "model/model_venda.php";
        require_once "model/model_log.php";

        require_once "controller/seguranca.php";
        require_once "controller/controller_venda.php";

        $seguranca = new Seguranca($conexao);
        $seguranca->protegePagina();

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
    
    <link href="css/select2.min.css" rel="stylesheet" />
    <link href="css/estilo.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/dialogo.css">

	<script type="text/javascript" charset="utf-8" src="css/datatables.min.css"></script>
	<script type="text/javascript" charset="utf-8" src="js/dataTables.editor.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="js/editor.bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="js/dialogo.js"></script> 
    <script type="text/javascript" charset="utf-8" src="js/valida_cpf_cnpj.js"></script>
		
	<nav class="navbar navbar-default" role="navigation" style="margin-bottom: 0">

	<?php include "pages/auxiliar/cabecalho.php"; ?>

	<!-- Chamada do menu principal da página -->
	<?php include "pages/auxiliar/menu_venda.php"; ?>	   
	
	</nav>	<!-- Chamada do cabeçalho da página -->
        <style>
        .valido {
            border: 1px solid green;
        }
        .invalido {
            border: 1px solid red;
        }
        </style>
        

</head>

<body >
	

	<?php

        // Chamada da view principal
	    include "pages/view_vendas.php";

    ?>
    <link rel="stylesheet" href="css/dialogo.css">
    <script type="text/javascript" charset="utf-8" src="js/dialogo.js"></script> 
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
    <script src="js/jquery.mask.min.js"></script>
	
	<script src="js/select2.min.js"></script>
    <script>

    $("#cpfClienteSelecionado").keydown(function(){
        try {
            $("#cpfClienteSelecionado").unmask();
        } catch (e) {}
        
        var tamanho = $("#cpfClienteSelecionado").val().length;
        console.log(tamanho);
        if(tamanho < 11){
            $("#cpfClienteSelecionado").mask("999.999.999-99");
        } else if(tamanho >= 11){
            $("#cpfClienteSelecionado").mask("99.999.999/9999-99");
        }
        
        // ajustando foco
        var elem = this;
        setTimeout(function(){
            // mudo a posição do seletor
            elem.selectionStart = elem.selectionEnd = 10000;
        }, 0);
        // reaplico o valor para mudar o foco
        var currentValue = $(this).val();
        $(this).val('');
        $(this).val(currentValue);
    });


    $("#cepClienteSelecionado").keydown(function(){
        try {
            $("#cepClienteSelecionado").unmask();
        } catch (e) {}
        
        var tamanho = $("#cepClienteSelecionado").val().length;
        
        $("#cepClienteSelecionado").mask("99.999-999");

        // ajustando foco
        var elem = this;
        setTimeout(function(){
            // mudo a posição do seletor
            elem.selectionStart = elem.selectionEnd = 10000;
        }, 0);
        // reaplico o valor para mudar o foco
        var currentValue = $(this).val();
        $(this).val('');
        $(this).val(currentValue);
    });

    function habilitar(){
        if(document.getElementById('habilita').checked){
            document.getElementById('valorDeslocamento').disabled = false;
        } else {
            document.getElementById('valorDeslocamento').disabled = true;
        }
    }
    
    $('#selecionarLoja').on('click', function(){
          $.confirm({
            title: 'Selecione a loja!',
            content: '' +
            '<form id="form_comissao" name="form_comissao" >' +
            '<div class="form-group">' +
              '<select class="form-control" style="width: 100%" name="tipoProduto" id="tipoProduto">'+
              '<option value="" ></option>'+
              '<option value="M" >Blusa</option>'+
              '<option value="F" >Calca</option>'+
              '</select>' +
            '</div>' +
            '</form>',
            typeAnimated: true,
            buttons: {
                Ok: function () {
                }
            }
        });
    });

    $(document).ready(function (){

         $("#nomeProduto").select2({
           allowClear:true,
           placeholder: ''
         });

        $('#selecionarLoja').on('click', function(){
            $.confirm({
                title: 'Selecione a loja!',
                content: '' +
                '<form id="form_comissao" name="form_comissao" >' +
                '<div class="form-group">' +
                '<select class="form-control" style="width: 100%" name="tipoProduto" id="tipoProduto">'+
                '<option value="" ></option>'+
                '<option value="M" >Blusa</option>'+
                '<option value="F" >Calca</option>'+
                '</select>' +
                '</div>' +
                '</form>',
                typeAnimated: true,
                buttons: {
                    Ok: function () {
                    }
                }
            });
        });

        $(document).keypress(function(e) {
            if(e.which == 13){ $('#botaoIncluirItemVenda').click();
                $('#nomeProduto').val(''); // Select the option with a value of '1'
                $('#nomeProduto').trigger('change'); // Notify any JS components that the value changed // clear out values selected
                $('#nomeProduto').select2('open');
            }
        });

    });

    // tell the embed parent frame the height of the content
    if (window.parent && window.parent.parent){
      window.parent.parent.postMessage(["resultsFrame", {
        height: document.body.getBoundingClientRect().height,
        slug: "4rx3896m"
      }], "*")
    }
	
    // tell the embed parent frame the height of the content
    if (window.parent && window.parent.parent){
      window.parent.parent.postMessage(["resultsFrame", {
        height: document.body.getBoundingClientRect().height,
        slug: "4rx3896m"
      }], "*")
    }
  
    </script>
<link rel="stylesheet" href="css/dialogo.css">
<script type="text/javascript" charset="utf-8" src="js/dialogo.js"></script> 
</body>

</html>