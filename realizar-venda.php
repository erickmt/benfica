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
    

    function abreCaixa(){
      var nomeMetodo        = "movimentaCaixa";
      var nomeController    = "Venda";
      $.confirm({
          title: 'Caixa!',
          content: '' +
          '<form action="" class="formName">' +
          '<div class="form-group">' +
          '<label>Notas</label><br/><br />' +
          '<input id="notas" type="number" min="0" value=0 class="n100" /> $100'+
          '<input id="notas" type="number" min="0" style={margin:0,0,0,30px} value=0 class="n50"/> $50' +
          '<input id="notas" type="number" min="0" value=0 class="n20"/> $20<br /><br />'+
          '<input id="notas" type="number" min="0" value=0 class="n10"/> $10 &nbsp' +
          '<input id="notas" type="number" min="0" value=0 class="n5"/> $5 &nbsp'+
          '<input id="notas" type="number" min="0" value=0 class="n2"/> $2 <br /> <br />' +
          '<h5><strong> Moedas </strong></h5> <br />' +
          '<input id="notas" type="number" min="0" value=0 class="m1" /> R$1&nbsp '+
          '<input id="notas" type="number" min="0" style={margin:0,0,0,30px} value=0 class="m50"/> ¢50' +
          '<input id="notas" type="number" min="0" value=0 class="m25"/> ¢25<br /><br />'+
          '<input id="notas" type="number" min="0" value=0 class="m10"/> ¢10 &nbsp&nbsp' +
          '<input id="notas" type="number" min="0" value=0 class="m5"/> ¢5 &nbsp'+
          '</div>' +
          '</form>',
          buttons: {
              formSubmit: {
                  text: 'Enviar',
                  btnClass: 'btn-blue',
                  action: function () {
                      var n100 = this.$content.find('.n100').val();
                      var n50 = this.$content.find('.n50').val();
                      var n20 = this.$content.find('.n20').val();
                      var n10 = this.$content.find('.n10').val();
                      var n5 = this.$content.find('.n5').val();
                      var n2 = this.$content.find('.n2').val();
                      var m1 = this.$content.find('.m1').val();
                      var m50 = this.$content.find('.m50').val();
                      var m25 = this.$content.find('.m25').val();
                      var m10 = this.$content.find('.m10').val();
                      var m5 = this.$content.find('.m5').val();
                      var m2 = this.$content.find('.m2').val();

                      var total1 = (parseFloat(n100)*100) + (parseFloat(n50)*50) + (parseFloat(n20)*20);
                      var total2 = (parseFloat(n10)*10) + (parseFloat(n5)*5);
                      var nMoedas = (parseFloat(m1)*100)   + (parseFloat(m50)*50) + (parseFloat(m25)*25) + (parseFloat(m10)*10) + (parseFloat(m5)*5); 
                      var total3 = (parseFloat(n2)*2) + (parseFloat(nMoedas)/100);
                      total = total1 + total2 + total3;
                      if(total == 0){
                          $.alert('Insira alguma valor');
                          return false;
                      }
                      var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&valor=' + total + '&desc=Abertura de caixa';
                      $.confirm({
                        title: 'Confirma?',
                        content: 'Valor inserido R$'+total,
                        buttons: {
                            Confirmar: function () {
                                $.ajax({
                                  dataType: "json",
                                  type: "POST",
                                  //url: "controller/controller_caixa.php",
                                  //data: "caixa=" + total + ',ok',
                                  url: "transferencia/transferencia.php",
                                  data: dados,
                                  success: function( msg ){
                                   BotaoCaixa(0);
                                   $.confirm({
                                      title: 'Caixa aberto',
                                      content: 'Valor inserido R$'+total,
                                      type: 'green',
                                      typeAnimated: true,
                                      autoClose: 'OK|1000',
                                      buttons: {
                                          OK: function () {
                                          }
                                      }
                                  });                            
                                }
                              });
                            },
                            Cancelar: function () {
                                $.alert('Cancelado!');
                            }
                        }
                    });
                  }
              },
              Cancelar: function () {
                  //
              },
          },
          onContentReady: function () {
              // bind to events
              var jc = this;
              this.$content.find('form').on('submit', function (e) {
                  // if the user submits the form by pressing enter in the field.
                  e.preventDefault();
                  jc.$$formSubmit.trigger('click'); // reference the button and click it
              });
          }
      });
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
            if(e.which == 13){ 
                $('#botaoIncluirItemVenda').click();
                $('#codigoBarra').val(''); // Select the option with a value of '1'
                $('#nomeProduto').val(''); // Select the option with a value of '1'
                $('#nomeProduto').trigger('change'); // Notify any JS components that the value changed // clear out values selected
                //$('#nomeProduto').select2('open');
            }
        });

      $.ajax({
              dataType: "json",
              type: "POST",
              //url: "controller/controller_caixa.php",
              //data: "caixa=" + total + ',ok',
              url: "transferencia/transferencia.php",
              data: 'nomeMetodo=confereCaixa&nomeController=Venda',
              success: function( retorno ){
                if(retorno.resultado == 'fechado')
                  abreCaixa();
            }
          });
    });
    
    $("#optionsRadiosInline2").change(function() {
        autenticacaoVendaConsignado();
    });

    $("#selecaoVendedor").change(function() {
        //autenticacaoAlterarVendedor();
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