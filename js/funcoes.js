//Quando a página for lida
$(document).ready(function(){

    var temporizador = false;
    $('#cpfClienteSelecionado').keypress(function(){
    
        // O input que estamos utilizando
        var input = $(this);
        
        // Limpa o timeout antigo
        if ( temporizador ) {
            clearTimeout( temporizador );
        }
        
        // Cria um timeout novo de 500ms
        temporizador = setTimeout(function(){
            // Remove as classes de válido e inválido
            input.removeClass('valido');
            input.removeClass('invalido');
            $('#erroCpfCnpj').hide();

        
            // O CPF ou CNPJ
            var cpf_cnpj = input.val();
            
            // Valida
            var valida = valida_cpf_cnpj( cpf_cnpj );
            
            // Testa a validação
            if ( valida ) {
                input.addClass('valido');
            } else {
                input.addClass('invalido');
                $('#erroCpfCnpj').fadeIn();
            }
        }, 500);
    
    });

    $('#verifica-caixa').on('click', function(){
      var nomeMetodo        = "confereCaixa";
      var nomeController    = "Venda";
      var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;
      
      $.ajax({
              dataType: "json",
              type: "POST",
              //url: "controller/controller_caixa.php",
              //data: "caixa=" + total + ',ok',
              url: "transferencia/transferencia.php",
              data: dados,
              success: function( retorno ){
                if(retorno.resultado == 'aberto')
                  BotaoCaixa(0);
                else BotaoCaixa(1);
            }
          });
      });      

      //Quando o formulário que busca o cliente for submetido
      $('#buscaFuncionario').submit(function(){

        $('#ErroLocalizaCliente').hide();

        //Valida entrada do nome
        if(($('#nomeCompletoPesquisa').val().replace( /\s/g, '' ) == '')&&($('#numeroIdentidadePesquisa').val().replace( /\s/g, '' ) == ''))
        {
            $('#nomeCompletoPesquisa').val('');
            $('#ErroLocalizaCliente').html('<strong>Erro: </strong>Necessário informar o nome completo ou o número da identidade do funcionario.');
            $('#ErroLocalizaCliente').fadeIn();

            return false;
        }

        var nomeMetodo    = "localizarFuncionario";
        var nomeController  = "Consignado";
    
        //Pega os dados do formulário
        var dados = $('#buscaFuncionario').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        //Coloca todos os campos do formulário de coleta de postagens em branco - sem preenchimento
        $('#nomeCompletoPesquisa').val("");
        $('#numeroIdentidadePesquisa').val("");

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          beforeSend: function() {
            $('#load01').show();
          },          
         complete: function(){
            $('#load01').hide();
          },
          success: function( retorno ){

            //Se o resultado for ok, verifica os demais itens
            if(retorno.resultado == "sucesso")
            {
              trasferenciaSelecaoProdutosConsignado(retorno.dados);
            }
            else if (retorno.resultado == "alerta")
            {
            $('#AvisoLocalizaCliente').html(retorno.html);
              $('#AvisoLocalizaCliente').fadeIn();
            }
            else if (retorno.resultado == "erro")
            {
            $('#ErroLocalizaCliente').html(retorno.html);
              $('#ErroLocalizaCliente').fadeIn();
            }            

          }
        });
        return false;
      });

      //Quando o formulário que busca o cliente for submetido
      $('#gerarRelatorioFinanceiro').submit(function(){

        // pendente
        $('#ErroGerarRelatorioFinanceiro').hide();
        $('#sessaoRelatorioFinanceiro').hide();

        var nomeMetodo     = "gerarRelatorioFinanceiro";
        var nomeController = "Relatorio";

        var html = '';
        var nomeLoja = $("#lojaBusca>option:selected").html();
        //Pega os dados do formulário
        var dados = $('#gerarRelatorioFinanceiro').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){
            if(retorno.resultado == "sucesso")
            {
              $('#tabelaValorRecebidoFormaPagamento tbody').empty();

              for (var i = 0; i < retorno.dados.formapagamento.length; i++) {
                  var newRow = $("<tr>");
                  var cols = "";
                  cols += '<td>'+retorno.dados.formapagamento[i].descricao+'</td>';
                  cols += '<td>R$ '+retorno.dados.formapagamento[i].valor+'</td>';
                  newRow.append(cols);
                  $("#tabelaValorRecebidoFormaPagamento").append(newRow); 
              } 
              //Cabeçalho do relatório
              $("#identificacaoCriterioRelatorioFinanceiro").html("Período de Referência: "+retorno.dados.data_inicial + " a "+retorno.dados.data_final);

              //Valores fixos do relatório
              $('#tituloRel').html("<b>Relatório Financeiro (vendas ativas) - Loja "+nomeLoja+"</b>");
              $('#valorTotalTaxasRelatorioFinanceiro').html("R$ "+retorno.dados.valor_taxas);
              $('#valorTotalComissaoRelatorioFinanceiro').html("R$ "+retorno.dados.valor_comissao);
              $('#valorTotalFreteRelatorioFinanceiro').html("R$ "+retorno.dados.valor_outros);
              $('#valorTotalLiquidoRelatorioFinanceiro').html("R$ "+retorno.dados.valor_liquido);
              $('#valorTotalCustoRelatorioFinanceiro').html("R$ "+retorno.dados.valor_custo);
              $('#sessaoRelatorioFinanceiro').fadeIn();               

            }
            else
            {
              $('#ErroGerarRelatorioFinanceiro').html("<strong>Erro: </strong>"+retorno.descricao);
              $('#ErroGerarRelatorioFinanceiro').fadeIn();               
              $('#sessaoRelatorioFinanceiro').hide();               
            }

          }
        });
        return false;
      });

      //Quando o formulário que busca o cliente for submetido
      $('#gerarRelatorioPedidos').submit(function(){

        // pendente
        $('#ErroGerarRelatorioPedidos').hide();
        $('#sessaoRelatorioPedidos').hide();

        var nomeMetodo     = "listarPedidos";
        var nomeController = "Venda";

        var html = '';
        var nomeLoja = $("#lojaBusca>option:selected").html();
        //Pega os dados do formulário
        var dados = $('#gerarRelatorioPedidos').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){


            if(retorno.resultado == "sucesso")
            {
              $('#tabelaPedidos tbody').empty();

              for (var i = 0; i < retorno.dados.length; i++) {
                  var newRow = $("<tr>");
                  var cols = "";
                  cols += '<td><center>'+retorno.dados[i].id_venda+'</center></td>';
                  cols += '<td>'+retorno.dados[i].nome_cliente+'</td>';
                  cols += '<td><center>R$ '+retorno.dados[i].total_venda+'</center></td>';
                  cols += '<td><center>'+retorno.dados[i].data_pedido+'</center></td>';
                  cols += '<td><center>'+retorno.dados[i].id_tiny+'</center></td>';
                  cols += '<td><center>'+retorno.dados[i].emitida+'</center></td>';
                  cols += "<td><center><button onclick='criarPedido("+retorno.dados[i].id_venda+");' class='btn btn-primary btn-xs'><i class='fa fa-file-o'></i></button></center></td>";
                  cols += "<td><center><button data-toggle='modal' data-target='#modalRecibo' onclick='imprimirCupom("+retorno.dados[i].id_venda+");'  class='btn btn-default btn-xs'><i class='fa fa-file-o'></i></button></center></td>";
                  cols += "<td><center><button onclick='emitirNota("+retorno.dados[i].id_venda+");' class='btn btn-default btn-xs'><i class='fa fa-file-text'></i></button></center></td>";
                 
                  
                  newRow.append(cols);
                  $("#tabelaPedidos").append(newRow); 
              } 

              //Valores fixos do relatório
              $('#tituloRel').html("<b>Relatório Pedidos (vendas ativas) - Loja "+nomeLoja+"</b>");
              $('#sessaoRelatorioPedidos').fadeIn();               

            }
            else
            {
              $('#ErroGerarRelatorioPedidos').html(retorno.descricao);
              $('#ErroGerarRelatorioPedidos').fadeIn();               
              $('#sessaoRelatorioPedidos').hide();               
            }

          }
        });
        return false;
      });

	  $('#formCadastroCliente').submit(function(){

        var nomeMetodo     = "cadastroCliente";
        var nomeController = "Cadastro";

        //Pega os dados do formulário
        var dados = $('#formCadastroCliente').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;
        
        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){
      		  
      			if(retorno.resultado == "sucesso")
            {
      				alert('parabens');
            }
            else
            {
              alert(retorno.descricao);             
            }
          }
        });

        return false;
      });
	  
	  
      $('#gerarRelatorioFaturamentoAno').submit(function(){

        // pendente
        $('#ErroGerarRelatorioFinanceiro').hide();
        $('#sessaoRelatorioTrimestral').hide();
        $('#sessaoRelatorioMensal').hide();

        var nomeMetodo     = "gerarRelatorioFaturamentoAno";
        var nomeController = "Relatorio";
        
        var nomeLoja = $("#lojaBusca2>option:selected").html();
        
        //Pega os dados do formulário
        var dados = $('#gerarRelatorioFaturamentoAno').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){


            if(retorno.resultado == "sucesso")
            {
            //$('#tabelaTrimestre tbody').empty();
              for (var i = 0; i < retorno.dados.faturamentoAno.length; i++) {
                  var trimestre = retorno.dados.faturamentoAno[i].trimestre;
                  var newRow = $("<tr>");
                  var cols = "";
                  
                  cols += '<td><b>'+nomeLoja+'</b></td>';
                  cols += '<td>'+trimestre+'</td>';
                  cols += '<td>R$ '+retorno.dados.faturamentoAno[i].valor_taxas+'</td>';
                  cols += '<td>R$ '+retorno.dados.faturamentoAno[i].valor_comissao+'</td>';
                  cols += '<td>R$ '+retorno.dados.faturamentoAno[i].valor_outros+'</td>';
                  cols += '<td>R$ '+retorno.dados.faturamentoAno[i].valor_liquido+'</td>';
                  cols += '<td>R$ '+retorno.dados.custo[i].valor_custo+'</td>';
                  cols += '<td>R$ '+retorno.dados.faturamentoAno[i].valor_pago+'</td>';
          
                  newRow.append(cols);
                  $("#tabelaTrimestre").append(newRow);    
              }  
              
              //Cabeçalho do relatório
              $('#sessaoRelatorioTrimestral').fadeIn();
            }
            else
            {
              $('#ErroGerarRelatorioFinanceiro').html("<strong>Erro: </strong>"+retorno.descricao);
              $('#ErroGerarRelatorioFinanceiro').fadeIn();               
              $('#sessaoRelatorioTrimestral').hide();               
            }

          }
        });
        return false;
      });


	//Quando o formulário que busca o cliente for submetido
      $('#gerarRelatorioFaturamento').submit(function(){
        
        $('#tabelaTrimestre tbody').empty();
        
        // pendente
        $('#ErroGerarRelatorioFinanceiro').hide();
        $('#sessaoRelatorioMensal').hide();
        $('#sessaoRelatorioTrimestral').hide();

        var nomeMetodo     = "gerarRelatorioFaturamento";
        var nomeController = "Relatorio";
        
        var nomeLoja = $("#lojaBusca>option:selected").html();
        
        //Pega os dados do formulário
        var dados = $('#gerarRelatorioFaturamento').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){


            if(retorno.resultado == "sucesso")
            {
    			  $('#tabelaValorMensal tbody').empty();
              for (var i = 0; i < retorno.dados.formapagamento.length; i++) {
                  var mes = retorno.dados.formapagamento[i].mes;
                  var newRow = $("<tr>");
                  var cols = "";
				          if(mes == 08)
                      mes = 'Agosto';
                  else if(mes == 09)
                      mes = 'Setembro';
                  else if(mes == 08)
                      mes = 'Outubro';
                  else if(mes == 08)
                      mes = 'Novembro';
                  else if(mes == 08)
                      mes = 'Dezembro';
                  cols += '<td>'+mes+'</td>';
                  cols += '<td>R$ '+retorno.dados.formapagamento[i].valor_taxas+'</td>';
                  cols += '<td>R$ '+retorno.dados.formapagamento[i].valor_comissao+'</td>';
				          cols += '<td>R$ '+retorno.dados.formapagamento[i].valor_outros+'</td>';
                  cols += '<td>R$ '+retorno.dados.formapagamento[i].valor_liquido+'</td>';
                  cols += '<td>R$ '+retorno.dados.custo[i].valor_custo+'</td>';
                  cols += '<td>R$ '+retorno.dados.formapagamento[i].valor_pago+'</td>';
				  
                  newRow.append(cols);
                  $("#tabelaValorMensal").append(newRow);    
              }  

              $('#tituloRelMes').html("<b>Relatório de Faturamentos (vendas ativas) - Loja "+nomeLoja+"</b>");
              //Cabeçalho do relatório
              $("#identificacaoCriterioRelatorioFinanceiro").html("Período de Referência: "+retorno.dados.data_inicial + " a "+retorno.dados.data_final);
              $('#sessaoRelatorioMensal').fadeIn();
            }
            else
            {
              $('#ErroGerarRelatorioFinanceiro').html("<strong>Erro: </strong>"+retorno.descricao);
              $('#ErroGerarRelatorioFinanceiro').fadeIn();               
              $('#sessaoRelatorioMensal').hide();               
            }

          }
        });
        return false;
      });

      //Quando o formulário que busca o cliente for submetido
      $('#gerarRelatorioPecas').submit(function(){

        // pendente
        $('#ErroGerarRelatorioFinanceiro').hide();
        $('#sessaoRelatorioFinanceiro').hide();

        var nomeMetodo     = "gerarRelatorioPecas";
        var nomeController = "Relatorio";
        var dia = parseInt(0);
        var mes = parseInt(0);

        var nomeLoja = $("#lojaBusca>option:selected").html();
        var tipoRelatorio = $("#tipoRelatorio").val();
        
        //Pega os dados do formulário
        var dados = $('#gerarRelatorioPecas').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&produtoSelecionado=' + $('#nomeProduto').val();

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){
            if(retorno.resultado == "sucesso")
            {
              var data = retorno.dados.formapagamento[0].data;
              var datames = data;

              $('#tabelaValorRecebidoFormaPagamento tbody').empty();
              for (var i = 0; i < retorno.dados.formapagamento.length; i++) {

                if(retorno.dados.formapagamento[i].data != data && tipoRelatorio == 1){
                    var newRowd = $("<tr>");
                    var col = "";
                    col += '<td><strong>'+data+'</strong></td>';
                    col += '<td><strong>TOTAL</strong></td>';
                    col += '<td><strong>'+dia+'</strong></td>'; 
                    col += '<td></td>';                     
                    newRowd.append(col);
                    $("#tabelaValorRecebidoFormaPagamento").append(newRowd);
                    data = retorno.dados.formapagamento[i].data;
                    dia = 0;

                };

                var valida_data = validar_data(datames, data, tipoRelatorio == 1);
                  if(valida_data == 1){
                    var nome_mes = nomeMes(datames.substring(6,7));
                    var newRowm = $("<tr>");
                    var col = "";
                    col += '<td><strong>'+nome_mes+'</strong></td>';
                    col += '<td><strong>TOTAL</strong></td>';
                    col += '<td><strong>'+mes+'</strong></td>'; 
                    col += '<td></td>';                     
                    newRowm.append(col);
                    $("#tabelaValorRecebidoFormaPagamento").append(newRowm);
                    mes = 0;
                    datames = data;
                  };

                dia += parseInt(retorno.dados.formapagamento[i].valor);
                mes += parseInt(retorno.dados.formapagamento[i].valor);
                var newRow = $("<tr>");
                var cols = "";
                //alterar as colunas se tiver mais uma variável
                cols += '<td>'+retorno.dados.formapagamento[i].descricao+'</td>';
                cols += '<td>'+retorno.dados.formapagamento[i].desc_tipo+'</td>';
                cols += '<td>'+retorno.dados.formapagamento[i].valor+'</td>';
                cols += '<td>'+retorno.dados.formapagamento[i].estoque+'</td>';
                newRow.append(cols);
                $("#tabelaValorRecebidoFormaPagamento").append(newRow);
              }  

              //Daqui pra baixo é o segundo relatório fixo, não mude, depois a gente remove
              //Cabeçalho do relatório
              $("#identificacaoCriterioRelatorioFinanceiro").html("Período de Referência: "+retorno.dados.data_inicial + " a "+retorno.dados.data_final);

              //Valores fixos do relatório
              $('#tituloRelMes').html("<b>Relatório de peças - Loja "+nomeLoja+"</b>");
              $('#sessaoRelatorioFinanceiro').fadeIn();               

            }
            else
            {
              $('#ErroGerarRelatorioFinanceiro').html("<strong>Erro: </strong>"+retorno.descricao);
              $('#ErroGerarRelatorioFinanceiro').fadeIn();               
              $('#sessaoRelatorioFinanceiro').hide();               
            }

          }
        });
        return false;
      });


    $('#gerarRelatorioConsignado').submit(function(){

        // pendente
        $('#ErroGerarRelatorio').hide();
        $('#sessaoRelatorio').hide();

        var nomeMetodo     = "gerarRelatorioConsignado";
        var nomeController = "Relatorio";

        var nomeLoja = $("#lojaBusca>option:selected").html();
        
        //Pega os dados do formulário
        var dados = $('#gerarRelatorioConsignado').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&produtoSelecionado=' + $('#nomeProduto').val();

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){
            if(retorno.resultado == "sucesso")
            {
              $('#tabelaVendasConsignadas tbody').empty();
              for (var i = 0; i < retorno.dados.produtos.length; i++) {
                    var newRowd = $("<tr>");
                    var col = "";
                    col += '<td>'+retorno.dados.produtos[i].loja+'</td>';
                    col += '<td>'+retorno.dados.produtos[i].dta_venda+'</td>'; 
                    col += '<td>'+retorno.dados.produtos[i].id_venda+'</td>'; 
                    col += '<td>'+retorno.dados.produtos[i].descricao+'</td>'; 
                    col += '<td>'+retorno.dados.produtos[i].quantidade+'</td>'; 
                    col += '<td>'+retorno.dados.produtos[i].devolvido+'</td>'; 
                    col += '<td>R$ '+retorno.dados.produtos[i].valor+'</td>'; 
                    col += '<td>R$ '+retorno.dados.produtos[i].total+'</td>'; 
                    col += '<td>R$ '+retorno.dados.produtos[i].restante+'</td>'; 
                    col += '<td>'+retorno.dados.produtos[i].nome+'</td>'; 
                    col += "<td><center><button onclick='devolverConsignado("+retorno.dados.produtos[i].id_produto+',"'+retorno.dados.produtos[i].descricao+'",'+retorno.dados.produtos[i].devolvido+");'><i class='fa fa-reply'></i></button></center></td>";
                    newRowd.append(col);
                    $("#tabelaVendasConsignadas").append(newRowd);
              }  
              //Cabeçalho do relatório
              $("#identificacaoCriterioRelatorio").html("Período de Referência: "+retorno.dados.data_inicial + " a "+retorno.dados.data_final);
              $('#tituloRelMes').html("<b>Relatório de peças - Loja "+nomeLoja+"</b>");
              $('#sessaoRelatorio').fadeIn();               

            }
            else
            {
              $('#ErroGerarRelatorio').html("<strong>Erro: </strong>"+retorno.descricao);
              $('#ErroGerarRelatorio').fadeIn();               
              $('#sessaoRelatorio').hide();               
            }

          }
        });
        return false;
      });

      //Quando o formulário que busca o cliente for submetido
      $('#gerarRelatorioPecasCliente').submit(function(){

        // pendente
        $('#ErroGerarRelatorioPecaCliente').hide();
        // $('#sessaoRelatorioPecaCliente').hide(); 
        var nomeMetodo     = "gerarRelatorioPecasCliente";
        var nomeController = "Relatorio";

        var nomeLoja = $("#lojaBuscaCliente>option:selected").html();
        var idLoja = $("#lojaBuscaCliente").val();

        
        //Pega os dados do formulário
        var dados = $('#gerarRelatorioPecasCliente').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){
            if(retorno.resultado == "sucesso")
            {
              $('#tabelaPecaCliente tbody').empty();
              for (var i = 0; i < retorno.dados.length; i++) {
                
                var newRow = $("<tr>");
                var cols = "";
                
                cols += '<td width="10%" ><center>'+retorno.dados[i].perfil+'</center></td>';
                cols += '<td width="10%" ><center>'+retorno.dados[i].id_cliente+'</center></td>';
                cols += '<td width="75%" >'+retorno.dados[i].nome+'</td>';
                cols += '<td width="10%" >'+retorno.dados[i].quantidade+'</td>';                  
                cols += "<td><button data-toggle='modal' data-target='#modalPecasCliente' onclick='visualizarProdutos("+retorno.dados[i].id_cliente+","+idLoja+");' class='btn btn-default btn-xs'><i class='fa fa-eye'></i></button></td>";
                newRow.append(cols);

                $("#tabelaPecaCliente").append(newRow);
              }  
              // //Daqui pra baixo é o segundo relatório fixo, não mude, depois a gente remove
              // //Cabeçalho do relatório
              $("#identificacaoCriterioRelatorioPecaCliente").html(retorno.data_inicial + " - "+retorno.data_final);

              // //Valores fixos do relatório
              // $('#tituloRelMesCliente').html("<b>Relatório de peças - Loja "+nomeLoja+"</b>");
               $('#sessaoRelatorioPecaCliente').fadeIn();               

            }
            else
            {
              $('#ErroGerarRelatorioPecaCliente').html("<strong>Erro: </strong>"+retorno.descricao);
              $('#ErroGerarRelatorioPecaCliente').fadeIn();               
              $('#sessaoRelatorioPecaCliente').hide();               
            }

          }
        });
        return false;
      });


      function validar_data (d1, d2) {
        var d2 = d2 + '';
        var d1 = d1 + '';

        d2 = d2.substring(0,7);
        d1 = d1.substring(0,7);
        if(d1 != d2)
          return 1;
        return 0;
      }

      function nomeMes($numero_mes){
        if($numero_mes == 1)
          return 'Janeiro';
        if($numero_mes == 2)
          return 'Fevereiro';
        if($numero_mes == 3)
          return 'Março';
        if($numero_mes == 4)
          return 'Abril';
        if($numero_mes == 5)
          return 'Maio';
        if($numero_mes == 6)
          return 'Junho';
        if($numero_mes == 7)
          return 'Julho';
        if($numero_mes == 8)
          return 'Agosto';
        if($numero_mes == 9)
          return 'Setembro';
        if($numero_mes == 10)
          return 'Outubro';
        if($numero_mes == 11)
          return 'Novembro';
        return 'Dezembro';
      }
      //Quando o formulário que busca o cliente for submetido
      $('#gerarRelatorioCaixa').submit(function(){

        // pendente
        $('#ErroGerarRelatorioFinanceiro').hide();
        $('#sessaoRelatorioFinanceiro').hide();

        var nomeMetodo     = "gerarRelatorioCaixa";
        var nomeController = "Relatorio";
        
        //Pega os dados do formulário
        var dados = $('#gerarRelatorioCaixa').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){


            if(retorno.resultado == "sucesso")
            {
              $('#tabelaMovimentacaoCaixa tbody').empty();
              for (var i = 0; i < retorno.dados.formapagamento.length; i++) {
                  
                  if(retorno.dados.formapagamento[i].valor < 0)
                    var newRow = $("<tr class='danger'>");
                  else if(retorno.dados.formapagamento[i].descricao == "Abertura De Caixa")
                    var newRow = $("<tr class='success'>");
                  else 
                    var newRow = $("<tr>");
                  var cols = "";
                  //alterar as colunas se tiver mais uma variável
                  if(retorno.dados.formapagamento[i].descricao == 'TOTAL' || retorno.dados.formapagamento[i].data == 'TOTAL'){
                    cols += '<td><strong>'+retorno.dados.formapagamento[i].data+'</strong></td>';
                    cols += '<td><strong>'+retorno.dados.formapagamento[i].descricao+'</strong></td>';
                    cols += '<td><strong>'+retorno.dados.formapagamento[i].valor+'</strong></td>';
                  }
                  else {
                    cols += '<td>'+retorno.dados.formapagamento[i].data+'</td>';
                    cols += '<td>'+retorno.dados.formapagamento[i].descricao+'</td>';
                    cols += '<td>'+retorno.dados.formapagamento[i].valor+'</td>';
                  }
                  newRow.append(cols);
                  $("#tabelaMovimentacaoCaixa").append(newRow);    
              }  

              //Daqui pra baixo é o segundo relatório fixo, não mude, depois a gente remove
              //Cabeçalho do relatório
              $("#identificacaoCriterioRelatorioFinanceiro").html("Período de Referência: "+retorno.dados.data_inicial + " a "+retorno.dados.data_final);

              //Valores fixos do relatório
              var nomeLoja = $("#lojaBusca>option:selected").html();
              if(nomeLoja == null){
                nomeLoja = "";
              }else{
                nomeLoja =  (" - Loja " + nomeLoja);
              }
              $('#tituloRel').html("<center><b>Valores de Movimentação de Caixa" +nomeLoja+ "</b></center>");

              $('#sessaoRelatorioFinanceiro').fadeIn();               

            }
            else
            {
              $('#ErroGerarRelatorioFinanceiro').html("<strong>Erro: </strong>"+retorno.descricao);
              $('#ErroGerarRelatorioFinanceiro').fadeIn();               
              $('#sessaoRelatorioFinanceiro').hide();               
            }

          }
        });
        return false;
      });


      //Quando o formulário que busca o cliente for submetido
      $('#gerarRelatorioComissao').submit(function(){

        $('#ErroGeracaoRelatorioComissao').hide();
        $('#sessaoRelatorioComissao').hide();
        $('#tabelaComissaoResumida tbody').empty();
        
        var nomeLoja = $("#lojaBusca>option:selected").html();
        $('#tituloRel').html("<b>Relatório Comissão (vendas ativas) - Loja "+nomeLoja+"</b>");

        var relatorioSelecionado = $('#tipoRelatorio').val();
        if(relatorioSelecionado  == 0)
        {
          relatorioResumido();
        }
        else  
        {
          var qttVendedor = $("#listagemVendedores").val()
          if(qttVendedor == 0){
            alert("Não é possível gerar relatório detalhado de todos vendedores");
            return false;
          }else{
            relatorioDetalhado();            
            relatorioResumido();
          }
        }
        return false;
      });


      function relatorioDetalhado(){
        
        var nomeMetodo     = "gerarRelatorioComissao";
        var nomeController = "Relatorio";
        var nomeVendedor   = $('#listagemVendedores option:selected').text();
        
        //Pega os dados do formulário
        var dados = $('#gerarRelatorioComissao').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&nomeVendedor=' + nomeVendedor;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){
            if(retorno.resultado == "sucesso")
            {
              var linha = $("<tr>");
              var coluna = '<th colspan = "8" id="tituloRelDetalhado"><h4><b><center>Comissão Resumida de vendedores</center></b></h4></th>';
              coluna +='</tr>';
              linha.append(coluna);
              $("#tabelaComissaoResumida").append(linha);

              linha = $("<tr>");
              coluna  ='    <th  width="10%" style="text-align:center">Loja</th>';
              coluna +='    <th  width="10%" style="text-align:center">Venda</th>';
              coluna +='    <th  width="10%" style="text-align:center">Data</th>';
              coluna +='    <th  width="20%" style="text-align:center">Cliente</th>';
              coluna +='    <th  width="15%" style="text-align:center">Valor Total Venda</th>';
              coluna +='    <th  width="15%" style="text-align:center">Valor Total Comissão</th>';
              coluna +='    <th  width="10%" style="text-align:center">Tipo</th>';
              coluna +='    <th  width="10%" style="text-align:center">Pagamentos</th>';
              coluna +='</tr>';
              linha.append(coluna);
              $("#tabelaComissaoResumida").append(linha);

              for (var i = 0; i < retorno.dados.vendas.length; i++) {
                  
                  var newRow = $("<tr>");
                  var cols = "";
                  
                  cols += '<td width="10%" ><center>'+retorno.dados.vendas[i].lj_venda+'</center></td>';
                  cols += '<td width="10%" ><center>'+retorno.dados.vendas[i].id_venda+'</center></td>';
                  cols += '<td width="10%" >'+retorno.dados.vendas[i].dta_venda+'</td>';
                  cols += '<td width="20%" ><p style="font-size: 12px;"  >'+retorno.dados.vendas[i].nome+'<br>CPF / CNPJ: '+ retorno.dados.vendas[i].cpf  +'</p></td>';
                  cols += '<td width="15%" style="text-align:center;" >R$ '+retorno.dados.vendas[i].valor_total_pago+'</td>';
                  cols += '<td width="15%" style="text-align:center;" >R$ '+retorno.dados.vendas[i].valor_total_comissao+'</td>';                  
                  cols += '<td width="10%" style="text-align:center;" > '+retorno.dados.vendas[i].per_desc+'</td>';                  
                  
                  var listaPamagentos = "<ul>";

                  for (j = 0; j < retorno.dados.vendas[i].pagamentos.length; j++) {

                     listaPamagentos += "<li><p style='font-size: 11px;'>"+retorno.dados.vendas[i].pagamentos[j].descricao+"</p></li>";
                  }

                  listaPamagentos += "</ul>";
                  cols += '<td width="10%">'+listaPamagentos+'</td>';
                  newRow.append(cols);

                  $("#tabelaComissaoResumida").append(newRow);    
              }  


              //Cabeçalho do relatório
              $("#identificacaoCriterioRelatorioComissao").html(retorno.dados.vendedor + "( "+retorno.dados.periodo+" )");    
              
              //Rodapé do relatório
              var newRow = $("<tr>");
              var cols = "";

              cols += '<td></td>';
              cols += '<td></td>';
              cols += '<td></td>';
              cols += '<td></td>';
              cols += '<td style="text-align:center"><b>R$ '+retorno.dados.totalVenda+'</b></td>';
              cols += '<td style="text-align:center"><b>R$ '+retorno.dados.totalComissao+'</b></td>';                  
              cols += '<td></td>';
              cols += '<td></td>';

              newRow.append(cols);
              $("#tabelaComissaoResumida").append(newRow);
              
             

              $('#sessaoRelatorioComissao').fadeIn();   
              var nomeVendedor = $("#listagemVendedores>option:selected").html();
              var nomeLoja = $("#lojaBusca>option:selected").html();
              $('#tituloRelDetalhado').html("<h4><b><center>Comissão Detalhada de "+nomeVendedor+" - "+nomeLoja+ "</center></b></h4>");
            }
            else
            {
                $('#ErroGeracaoRelatorioComissao').html("<strong>Erro: </strong>"+retorno.descricao);
                $('#ErroGeracaoRelatorioComissao').fadeIn();              
                $('#sessaoRelatorioComissao').hide();   
            }

          }
        });
      }


      function relatorioResumido(){

        var nomeMetodo     = "gerarRelatorioResumidoComissao";
        var nomeController = "Relatorio";
        var qttVendedor = $("#listagemVendedores").val()
        //Pega os dados do formulário
        var dados = $('#gerarRelatorioComissao').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){
            if(retorno.resultado == "sucesso")
            {
              var linha = $("<tr>");
              var coluna = '<th colspan = "8" id="tituloRelResumido"><h4><b><center>Comissão Resumida de vendedores</center></b></h4></th>';
              coluna +='</tr>';
              linha.append(coluna);
              $("#tabelaComissaoResumida").append(linha);

              linha = $("<tr>");
              coluna  ='    <th  width="8%" style="text-align:center">Loja</th>';
              coluna +='    <th  width="12%%" style="text-align:center">Vendedor</th>';
              coluna +='    <th  width="15%%" style="text-align:center">Total Vendido</th>';
              coluna +='    <th  width="12%%" style="text-align:center">Comissão Total</th>';
              coluna +='    <th  width="12%%" style="text-align:center">Total Venda Varejo</th>';
              coluna +='    <th  width="12%%" style="text-align:center">Comissão Varejo</th>';
              coluna +='    <th  width="12%%" style="text-align:center">Total Venda Atacado</th>';
              coluna +='    <th  width="15%%" style="text-align:center">Comissão Atacado</th>';
              coluna +='</tr>';
              linha.append(coluna);
              $("#tabelaComissaoResumida").append(linha);                         
              
              for (var i = 0; i < retorno.dados.vendas.length; i++) {
                  var newRow = $("<tr>");
                  var cols = "";
                  
                  if(nomeLoja == 0 && qttVendedor == 0)
                    cols += '<td width="10%" ><center>Todas</center></td>';
                  else
                    cols += '<td width="8%" ><center>'+retorno.dados.vendas[i].lj_venda+'</center></td>';
                  cols += '<td width="12%" ><center>'+retorno.dados.vendas[i].nome+'</center></td>';
                  cols += '<td width="15%" style="text-align:center;" >R$'+retorno.dados.vendas[i].total_venda+'</center></td>';
                  cols += '<td width="12%" style="text-align:center;" >R$'+retorno.dados.vendas[i].comissao_total+'</td>';
                  cols += '<td width="12%  style="text-align:center;" ><center>R$'+retorno.dados.vendas[i].total_venda_varejo+'</center></td>';
                  cols += '<td width="12%" style="text-align:center;" >R$'+retorno.dados.vendas[i].comissao_varejo+'</td>';
                  cols += '<td width="12%" style="text-align:center;" >R$'+retorno.dados.vendas[i].total_venda_atacado+'</td>';                  
                  cols += '<td width="15%" style="text-align:center;" >R$'+retorno.dados.vendas[i].comissao_atacado+'</td>';                  
                  newRow.append(cols);

                  $("#tabelaComissaoResumida").append(newRow);    
              }  


              //Cabeçalho do relatório
              $("#identificacaoCriterioRelatorioComissao").html("( "+retorno.dados.periodo+" )");    
              
              //Rodapé do relatório
              var newRow = $("<tr>");
              var cols = "";

              cols += '<td></td>';
              cols += '<td></td>';
              cols += '<td style="text-align:center"><b>R$ '+retorno.dados.totalVenda+'</b></td>';
              cols += '<td style="text-align:center"><b>R$ '+retorno.dados.totalComissao+'</b></td>'; 
              cols += '<td></td>';
              cols += '<td></td>';
              cols += '<td></td>';
              cols += '<td></td>';

              newRow.append(cols);
              $("#tabelaComissaoResumida").append(newRow);                 
              $('#sessaoRelatorioComissao').fadeIn();
              
              var nomeLoja = $("#lojaBusca>option:selected").html();
              $('#tituloRelResumido').html("<h4><b><center>Comissão Resumida de Vendedores - "+nomeLoja+ "</center></b></h4>");             
            }
            else
            {
                $('#ErroGeracaoRelatorioComissao').html("<strong>Erro: </strong>"+retorno.descricao);
                $('#ErroGeracaoRelatorioComissao').fadeIn();              
                $('#sessaoRelatorioComissao').hide();   
            }

          }
        });
      }

      $('#autenticacaoAlteracaoVenda').on('hide.bs.modal', function () {
        
        alteraConsignado = false;
		    alteraVendedor = false;
		    $("#login").val("");
        $("#senha").val("");
        $('#erroAutorizacaoAlteracaoVenda').html("");
        $('#erroAutorizacaoAlteracaoVenda').hide();

          //Volta com os dados somente se o usuário superior não for autenticado
          if( $("#controle").val() == 0)
          {
              $('#valorUnitarioProduto').val($('#valorUnitarioProduto').attr('valorOriginal'));
          }

          // Seta o campo de controle
          $("#controle").val(0);
          $("#botaoIncluirItemVenda").prop("disabled", false);
      });


      //Quando o formulário que busca o cliente for submetido
      $('#login').submit(function(){

        $('#erroLogin').hide();

        var nomeMetodo    = "criar";
        var nomeController  = "Sessao";
    
        //Pega os dados do formulário
        var dados = $('#login').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){


            if(retorno.resultado == "sucesso")
            {
                //Redireciona para a página principal do sistema
                url = "index.php";
                $( location ).attr("href", url);
                $('#erroLogin').hide();              
            }
            else
            {
                $('#erroLogin').html(retorno.descricao);
                $('#erroLogin').fadeIn();              
            }

          }
        });
        return false;
      });


      //Quando o formulário de pesquisar venda - na pagina de cancelamento de venda é chamado
      $('#pesquisarVenda').submit(function(){

        $('#ErroLocalizaVenda').hide();       
        $('#sessaoResultadoVendasLocalizadas').hide();
        $('#codigoVendaPesquisaVenda').hide();
        $('#nomeCompletoPesquisaVenda').hide();
        $('#numeroIdentidadePesquisaVenda').hide();        

        var codigoVendaPesquisa      = false;
        var nomeCompletoPesquisa     = false;
        var numeroIdentidadePesquisa = false;

        if ($('#codigoVendaPesquisa').val().replace( /\s/g, '' ) == '')        
        {
          codigoVendaPesquisa      = false;
          $('#codigoVendaPesquisa').val(-1);
        }
        else 
          codigoVendaPesquisa      = $('#codigoVendaPesquisa').val();

        if ($('#nomeCompletoPesquisa').val().replace( /\s/g, '' ) == '')        
        {
          nomeCompletoPesquisa     = false;
          $('#nomeCompletoPesquisa').val(-1);
        }
        else
          nomeCompletoPesquisa     = $('#nomeCompletoPesquisa').val();

        if ($('#numeroIdentidadePesquisa').val().replace( /\s/g, '' ) == '')        
        {
          numeroIdentidadePesquisa = false;  
          $('#numeroIdentidadePesquisa').val(-1);
        }
        else
          numeroIdentidadePesquisa = $('#numeroIdentidadePesquisa').val();


        var nomeMetodo      = "pesquisarVenda";
        var nomeController  = "Venda";

        //Pega os dados do formulário
        var dados = $('#pesquisarVenda').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          /*beforeSend: function() {
            $('#load01').show();
          },          
         complete: function(){
            $('#load01').hide();
          },*/
          success: function( retorno ){

            //Se o resultado for ok, verifica os demais itens
            if(retorno.resultado == 'sucesso')
            {
              $('#tabelaVendasLocalizadas tbody').empty();

              for (i = 0; i < retorno.dados.length; i++) {
                  

                  var newRow = $("<tr>");
                  var cols = "";

                  cols += '<td width="6%">'+retorno.dados[i].id_venda+'</td>';
                  cols += '<td width="9%">'+retorno.dados[i].dta_venda+'</td>';
                  cols += '<td width="40%"><p style="font-size: 11px;">'+retorno.dados[i].nome+'<br>CPF / CNPJ: '+ retorno.dados[i].cpf  +'</p></td>';

                  var listaProdutos = "<ul>";

                  for (j = 0; j < retorno.dados[i].produtos.length; j++) {

                      listaProdutos += "<li><p style='font-size: 11px;'>"+retorno.dados[i].produtos[j].nome_produto+"<br>"+retorno.dados[i].produtos[j].quantidade+" unidade(s)</p></li>";
                  }

                  listaProdutos += "</ul>";
                  cols += '<td width="40%">'+listaProdutos+'</td>';
                  //cols += "<td><a  href='javascript:void(0)' onclick='modalConfirmacaoExclusaoVenda("+retorno.dados[i].id_venda+");' class='btn btn-danger'>Cancelar</button></td>";
                  cols += "<td><a  href='javascript:void(0)' onclick='autenticacaoCancelamentoVenda("+retorno.dados[i].id_venda+");' class='btn btn-danger'>Cancelar</button></td>";


                  newRow.append(cols);
                  $("#tabelaVendasLocalizadas").append(newRow);    
              }  

                if(codigoVendaPesquisa != false)
                {
                    $('#codigoVendaPesquisaVenda').html("&emsp; &emsp; &emsp; &emsp;Código da venda: "+codigoVendaPesquisa);                  
                    $('#codigoVendaPesquisaVenda').show();
                }

                if(nomeCompletoPesquisa != false)
                {
                    $('#nomeCompletoPesquisaVenda').html("&emsp; &emsp; &emsp; &emsp;Nome completo: "+nomeCompletoPesquisa);                  
                    $('#nomeCompletoPesquisaVenda').show();
                }                

                if(numeroIdentidadePesquisa != false)
                {
                    $('#numeroIdentidadePesquisaVenda').html("&emsp; &emsp; &emsp; &emsp;Número identidade: "+numeroIdentidadePesquisa);                  
                    $('#numeroIdentidadePesquisaVenda').show();
                }                                

                $('#ErroLocalizaVenda').hide();
                $('#sessaoResultadoVendasLocalizadas').fadeIn();

                // Move a tela para os resultados
                $('html, body').animate({scrollTop:$('#sessaoResultadoVendasLocalizadas').position().top}, 'slow');                
            }
            else
            {
                $('#sessaoResultadoVendasLocalizadas').hide();
                $('#ErroLocalizaVenda').html("<strong>Erro: </strong>"+retorno.descricao);
                $('#ErroLocalizaVenda').fadeIn();
            }

          }
        });

        $('#codigoVendaPesquisa').val('');
        $('#nomeCompletoPesquisa').val('');
        $('#numeroIdentidadePesquisa').val('');        

        return false;
      });


      $( "#MenuOpcaoPasso02" ).click(function() {
          $('#sucessoInclusaoItemVenda').hide();
          $('#erroInclusaoItemVenda').hide();
          $("#botaoPerfil").prop("disabled",false);
          $('#sucessoAlteracaoCreditoCliente').hide();
          $('#erroAlteracaoCreditoCliente').hide();
      });


      $( "#optionsRadiosInline1" ).click(function() {

        var nomeMetodo    = "ajusteValorProdutosConsignado";
        var nomeController  = "Venda";

        //Pega os dados do formulário
        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&indicadorConsignado=N';

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){

            //Se o resultado for ok, verifica os demais itens
            if(retorno.resultado == 'sucesso')
            {
                atualizaItensVenda();
            }
            else
            {
                atualizaItensVenda();
            }

          }
        });


      });

      $( "#optionsRadiosInline2" ).click(function() {

        var nomeMetodo    = "ajusteValorProdutosConsignado";
        var nomeController  = "Venda";

        //Pega os dados do formulário
        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&indicadorConsignado=S';

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){

            //Se o resultado for ok, verifica os demais itens
            if(retorno.resultado == 'sucesso')
            {
                atualizaItensVenda();
            }
            else
            {
                atualizaItensVenda();
            }

          }
        });


      });      


      $( "#MenuOpcaoPasso03" ).click(function() {
        
          if($('#MenuOpcaoPasso03').hasClass('disabled') == false){
              trasferenciaPasso02paraPasso03();
          }
          else
          {
            return false;
          }
      });


      //Quando o formulário de incluir itens na venda for submetido
      $('#inclusaoFormasPagamento').submit(function(){

        $('#erroInclusaoFormaPagamento').hide();

        // Se não houver vírgula, apresenta uma mensagem de erro
        if($('#valorFormaPagamento').val().indexOf(",") == -1)
        {
            // inclui a mensagem de erro
            $('#erroInclusaoFormaPagamento').html("<strong>Erro: </strong> Formato incorreto do valor.");
            $('#erroInclusaoFormaPagamento').fadeIn();
            return false;
        }                        

        var nomeMetodo    = "adicionarFormaPagamento";
        var nomeController  = "Venda";

        //Pega os dados do formulário
        var dados = $('#inclusaoFormasPagamento').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&nomeFormaPagamento=' + $('#idFormaPagamento option:selected').text() + '&quantidadeParcelas='+ $('#quantidadeParcelas').val();

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          /*beforeSend: function() {
            $('#load01').show();
          },          
         complete: function(){
            $('#load01').hide();
          },*/
          success: function( retorno ){

            //Se o resultado for ok, verifica os demais itens
            if(retorno.resultado == 'sucesso')
            {
                $('#valorFormaPagamento').val(retorno.novo_valor_sugerido);

                if(retorno.novo_valor_sugerido != '0,00')
                {
                    $('#indicadorConclusaoVenda').removeClass('alert-success');
                    $('#indicadorConclusaoVenda').addClass('alert-danger');                                                     
                    $('#indicadorConclusaoVenda').html("<strong>Atenção: </strong> Ainda resta definir a forma de pagamento de R$ "+retorno.novo_valor_sugerido+" desta venda.");
                }
                else 
                {
                    $('#indicadorConclusaoVenda').removeClass('alert-danger');
                    $('#indicadorConclusaoVenda').addClass('alert-success');                                                     
                    $('#indicadorConclusaoVenda').html("As formas de pagamento do valor total desta venda foram todas definidas.");
                }                  
                atualizaFormasDePagamento();     
                atualizaResumoPagamento();         

                //Volta ao topo do painel
                $('html, body').animate({scrollTop:$('#painelVendaPrincipal').position().top}, 'slow');                
            }
            else
            {
                // inclui a mensagem de erro
                $('#erroInclusaoFormaPagamento').html("<strong>Erro: </strong> "+retorno.descricao);
                $('#erroInclusaoFormaPagamento').fadeIn();
            }

          }
        });

        $('#quantidadeParcelas').val('1');
        $('#valorFormaPagamento').val('0,00');

        return false;
      });





      //Quando o formulário de incluir itens na venda for submetido
      $('#inclusaoProdutosVenda').submit(function(){


        $('#erroInclusaoItemVenda').hide();

        /* Validações iniciais do produto a ser incluído */
        if($('#nomeProduto option:selected').val() == 0 || $('#nomeProduto option:selected').val() == '')
        {
            // inclui a mensagem de erro
            $('#erroInclusaoItemVenda').html("<strong>Erro: </strong> Necessário infomar o produto.");
            $('#erroInclusaoItemVenda').fadeIn();
            $('#sucessoInclusaoItemVenda').hide();
            
            return false;
        }

        if($('#valorUnitarioProduto').val().replace( /\s/g, '' ) == '' || $('#valorUnitarioProduto').val() == '0.0')
        {
            // inclui a mensagem de erro
            $('#erroInclusaoItemVenda').html("<strong>Erro: </strong> Necessário infomar o valor unitário do produto.");
            $('#erroInclusaoItemVenda').fadeIn();
            $('#sucessoInclusaoItemVenda').hide();
            return false;
        }        

        // Se não houver vírgula, apresenta uma mensagem de erro
        if($('#valorUnitarioProduto').val().indexOf(",") == -1)
        {
            // inclui a mensagem de erro
            $('#erroInclusaoItemVenda').html("<strong>Erro: </strong> Formato incorreto do valor unitário do produto.");
            $('#erroInclusaoItemVenda').fadeIn();
            $('#sucessoInclusaoItemVenda').hide();
            return false;
        }                        


        // Não permite pequenas alterações de valores do produto
        var novo_valor     = $('#valorUnitarioProduto').val().replace(".", "");
        novo_valor         = novo_valor.replace(",", ".");
        var valor_original = $('#valorUnitarioProduto').attr('valorOriginal').replace(".", "");        
        valor_original     = valor_original.replace(",", ".");
        var valor_limite   = (parseFloat(valor_original) + parseFloat(2)).toFixed(2); 
        if(novo_valor < valor_limite && novo_valor != valor_original && valor_original != 0)
        {
            $('#sucessoInclusaoItemVenda').hide();
            $('#erroInclusaoItemVenda').html("<strong>Erro: </strong> Não é permitido alterar o preço do produto para um valor abaixo de R$ "+valor_limite+", uma vez que seu valor original é R$ "+$('#valorUnitarioProduto').attr('valorOriginal')+".");
            $('#erroInclusaoItemVenda').fadeIn();
            return false;
        }                                

        if($('#quantidadeProduto').val().replace( /\s/g, '' ) == '')
        {
            // inclui a mensagem de erro
            $('#erroInclusaoItemVenda').html("<strong>Erro: </strong> Necessário infomar a quantidade do produto.");
            $('#erroInclusaoItemVenda').fadeIn();
            return false;
        }                

        pesoTotal = 1;

        var nomeMetodo    = "adicionarItensVendaSessao";
        var nomeController  = "Venda";

        //Pega os dados do formulário
        var dados = $('#inclusaoProdutosVenda').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&nomeRealProduto=' + $('#nomeProduto option:selected').text() + '&pesoTotal='+ pesoTotal;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          beforeSend: function() {
            $('#processoInclusaoVenda').show();
          },          
         complete: function(){
            $('#processoInclusaoVenda').hide();
          },
          success: function( retorno ){

            //Se o resultado for ok, verifica os demais itens
            if(retorno.resultado == 'sucesso')
            {
                $('#sucessoInclusaoItemVenda').html($('#nomeProduto option:selected').text()+" incluído com sucesso.");
                $('#sucessoInclusaoItemVenda').fadeIn();
                $('#erroInclusaoItemVenda').hide();
                $('#quantidadeProduto').val(1);
                $('#valorUnitarioProduto').val('');

                //muda a seleção do produto para brancos
                $("#tipoProduto").val($("#tipoProduto option:first-child").val());
                $('#modeloProduto option[value="0"]').attr({ selected : "selected" });

                $("#nomeProduto").val($("#tipoProduto option:first-child").val());
                //localizarProdutoPorTipo();
                atualizaItensVenda();

            }
            else
            {
                // inclui a mensagem de erro
                $('#sucessoInclusaoItemVenda').hide();
                $('#erroInclusaoItemVenda').html("<strong>Erro: </strong>"+retorno.resultado);
                $('#erroInclusaoItemVenda').fadeIn();
            }

          }
        });
        return false;

      });


      //Quando o formulário que busca o cliente for submetido
      $('#buscaCliente').submit(function(){

        //Pendente - código abaixo inserido só para teste
        //$('#nomeCompletoPesquisa').val('oi');
        //$('#numeroIdentidadePesquisa').val('123');

        $('#cpfClienteSelecionado').val("");
        $('#cepClienteSelecionado').val("");
        $('#ruaClienteSelecionado').val("");
        $('#numeroClienteSelecionado').val("");
        $('#bairroClienteSelecionado').val("");
        $('#cidadeClienteSelecionado').val("");
        $('#ufClienteSelecionado').val("");
        $('#emailClienteSelecionado').val("");



        $('#ErroLocalizaCliente').hide();

        //Valida entrada do nome
        if(($('#nomeCompletoPesquisa').val().replace( /\s/g, '' ) == '')&&($('#numeroIdentidadePesquisa').val().replace( /\s/g, '' ) == ''))
        {
            $('#nomeCompletoPesquisa').val('');
            $('#ErroLocalizaCliente').html('<strong>Erro: </strong>Necessário informar o nome completo ou o número da identidade do cliente.');
            $('#ErroLocalizaCliente').fadeIn();

            return false;
        }

      	var nomeMetodo 		= "localizarCliente";
      	var nomeController 	= "Venda";

        //Pega os dados do formulário
        var dados = $('#buscaCliente').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        //Coloca todos os campos do formulário de coleta de postagens em branco - sem preenchimento
        $('#nomeCompletoPesquisa').val("");
        $('#numeroIdentidadePesquisa').val("");

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          beforeSend: function() {
            $('#load01').show();
            $('#lojaVenda').prop('disabled', true);
          },          
         complete: function(){
            $('#load01').hide();
          },
          success: function( retorno ){

          	//Se o resultado for ok, verifica os demais itens
            if(retorno.resultado == "sucesso")
            {
            	trasferenciaPasso01paraPasso02(retorno.dados);
            }
            else if (retorno.resultado == "alerta")
            {
        		$('#AvisoLocalizaCliente').html(retorno.html);
          		$('#AvisoLocalizaCliente').fadeIn();
            }
            else if (retorno.resultado == "erro")
            {
        		$('#ErroLocalizaCliente').html(retorno.html);
          		$('#ErroLocalizaCliente').fadeIn();
            }            

          }
        });
        return false;
      });

      // Chama sempre que o tipo do produto for alterado
      $('#valorUnitarioProduto').on('change', function(){

        $('#erroInclusaoItemVenda').hide();

        // Se não houver vírgula, apresenta uma mensagem de erro
        if($('#valorUnitarioProduto').val().indexOf(",") == -1)
        {
            // inclui a mensagem de erro
            $('#erroInclusaoItemVenda').html("<strong>Erro: </strong> Formato incorreto do valor unitário do produto.");
            $('#erroInclusaoItemVenda').fadeIn();
            return false;
        }                

        // Não permite pequenas alterações de valores do produto
        var novo_valor     = $('#valorUnitarioProduto').val().replace(".", "");
        novo_valor         = novo_valor.replace(",", ".");
        var valor_original = $('#valorUnitarioProduto').attr('valorOriginal').replace(".", "");        
        valor_original     = valor_original.replace(",", ".");
        var valor_limite   = (parseFloat(valor_original) + parseFloat(2)).toFixed(2); 

        if(novo_valor < valor_limite && novo_valor != valor_original && valor_original != 0)
        {

            $('#sucessoInclusaoItemVenda').hide();
            $('#erroInclusaoItemVenda').html("<strong>Erro: </strong> Não é permitido alterar o preço do produto para um valor abaixo de R$ "+valor_limite+", uma vez que seu valor original é R$ "+$('#valorUnitarioProduto').attr('valorOriginal')+".");
            $('#erroInclusaoItemVenda').fadeIn();
            return false;
        }          

        // Não permite que um usuário comum do tipo funcionário altere o preco de um produto sem autenticação
        if(novo_valor != valor_original)              
        {
            //Abre o modal de permissão se necessário 
            autenticacaoAlteracaoVenda();
        }

      });


      // Chama sempre que a quantidadeProduto for alterada para nada 
      $('#quantidadeProduto').on('change', function(){
        if($('#quantidadeProduto').val().replace( /\s/g, '' ) == '')
            $('#quantidadeProduto').val('1');
      });

      // Chama sempre que a quantidadeParcelas for alterada para nada 
      $('#quantidadeParcelas').on('change', function(){
        if($('#quantidadeParcelas').val().replace( /\s/g, '' ) == '' || $('#quantidadeParcelas').val().replace( /\s/g, '' ) == '0')
            $('#quantidadeParcelas').val('1');
      });      



      // Chama sempre que o tipo do produto for alterado
      $('#modeloProduto').on('change', function(){

        if($("#tipoProduto").val() == '')
          return false;
        else 
          localizarProdutoPorTipo();

        return false;
      });


      $('#lojaBuscaPeca').on('change', function(){
        var id = $('#lojaBuscaPeca').val();
        alterarLojaBusca(id);
        return false;
      });

      // Chama sempre que o tipo do produto for alterado
      $('#tipoProduto').on('change', function(){
        localizarProdutoPorTipo();
        return false;
      });
      
      // Chama sempre que o tipo do produto for alterado
      $('#tipoProdutoRelatorio').on('change', function(){
        localizarProdutoRelatorioPorTipo();
        return false;
      });

      // Chama sempre que a forma de pagamento for alterada
      $('#idFormaPagamento').on('change', function(){

        var taxa = $('#idFormaPagamento option:selected').attr('taxa');

        if (taxa != 0)
        {
          $("#indicadorConsiderarTaxasNao").prop("checked", false);
          $("#indicadorConsiderarTaxasSim").prop("checked", true);
          $('#apresentacaoTaxas').fadeIn();
        }
        else
        {
          $("#indicadorConsiderarTaxasNao").prop("checked", true);
          $("#indicadorConsiderarTaxasSim").prop("checked", false);
          $('#apresentacaoTaxas').fadeOut();
        }

        var id = $('#idFormaPagamento option:selected').attr('value');
        if(id == 3)
        {  
          $("#quantidadeParcelas").val('');
        }
        else
          $("#quantidadeParcelas").val('1');

        return false;
      });      


      $( "#optionsRadiosInline3" ).click(function() {
        $("#valorDeslocamento").prop( "disabled", true );
        $("#valorDeslocamento").val('0,0');
      });

      $( "#optionsRadiosInline4" ).click(function() {
        $("#valorDeslocamento").prop( "disabled", false );
        $("#valorDeslocamento").val('0,0');
      });

      // Chama sempre que o valor do deslocamento da venda for alterado para validar as novas informações
      $('#valorDeslocamento').on('change', function(){

          $('#erroAlteracaoValorDeslocamento').hide();

          //Muda para zero quando excluir toda a informação do campo
          if($('#valorDeslocamento').val().replace( /\s/g, '' ) == '')
          {
              $('#valorDeslocamento').val('0,0');
          }

        // Se não houver vírgula, apresenta uma mensagem de erro
        if($('#valorDeslocamento').val().indexOf(",") == -1)
        {
            // inclui a mensagem de erro
            $('#erroAlteracaoValorDeslocamento').html("<strong>Erro: </strong> Formato incorreto do valor do deslocamento.");
            $('#erroAlteracaoValorDeslocamento').fadeIn();
            //$('#valorDeslocamento').val('0,0');
            return false;
        }  
        atualizaItensVenda();                   
      });      


      // Chama sempre que o valor do crédito do cliente for alterado 
      $( "#confirmarAtualizacaoCredito" ).click(function() {

        $('#erroAlteracaoCreditoCliente').hide();
        $('#sucessoAlteracaoCreditoCliente').hide();

        //Valida entrada do nome
        if($('#valorCreditoCliente').val().replace( /\s/g, '' ) == '')
        {
            $('#valorCreditoCliente').val('0,0');
        }


        // Se não houver vírgula, apresenta uma mensagem de erro
        if($('#valorCreditoCliente').val().indexOf(",") == -1)
        {
            // inclui a mensagem de erro
            $('#erroAlteracaoCreditoCliente').html("<strong>Erro: </strong> Formato incorreto do crédito do cliente.");
            $('#erroAlteracaoCreditoCliente').fadeIn();
            return false;
        }                        

        //Busca todos os nomes dos produtos relacionados ao tipo selecionado
        var nomeMetodo      = "alterarCreditoCliente";
        var nomeController  = "Venda";
    
        //Pega os dados do formulário
        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&novoCredito=' + $("#valorCreditoCliente").val();


        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          /*beforeSend: function() {
            $('#load01').show();
          },          
         complete: function(){
            $('#load01').hide();
          },*/
          success: function( retorno ){

            //Se o resultado for ok, monta o nome dos produtos relacionados ao tipo selecionado
            if(retorno.resultado != "sucesso")
            {
                // inclui a mensagem de erro
                $('#erroAlteracaoCreditoCliente').html("<strong>Erro: </strong>"+retorno.resultado);
                $('#erroAlteracaoCreditoCliente').fadeIn();
                $('#sucessoAlteracaoCreditoCliente').hide();             
            }
            else
            {
                $('#sucessoAlteracaoCreditoCliente').html("Crédito alterado para R$ "+$('#valorCreditoCliente').val()+" com sucesso.");
                $('#sucessoAlteracaoCreditoCliente').fadeIn();
                $('#erroAlteracaoCreditoCliente').hide();
                atualizaItensVenda();
            }
          }
        });
        return false;

      });

      // Chama sempre que o nome do produto for alterado
      $('#nomeProduto').on('change', function(){

        //Busca todos os nomes dos produtos relacionados ao tipo selecionado
        var nomeMetodo    = "buscarPrecoProduto";
        var nomeController  = "Venda";
    
        //Pega os dados do formulário
        if($("#nomeProduto").val() == '')
            return false;

        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&idProduto=' + $("#nomeProduto").val() + '&perfilCliente=' + $("#perfilCliente").val();

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          /*beforeSend: function() {
            $('#load01').show();
          },          
         complete: function(){
            $('#load01').hide();
          },*/
          success: function( retorno ){

            //Se o resultado for ok, monta o preço do produto campo de preço
            if(retorno.resultado == "sucesso")
            {
                $("#valorUnitarioProduto").val(retorno.preco);
                $('#valorUnitarioProduto').attr('valorOriginal', retorno.preco);

                $("#valorVarejo").val(retorno.precoVarejo);
                $("#valorAtacado").val(retorno.precoAtacado);

                $("#pesoTotal").val(retorno.peso);
            }

          }
        });
        return false;

      });

   




});



	function prosseguirVendaClienteDuplicado()
	{        
        var nomeMetodo 		= "localizarClientePorId";
      	var nomeController 	= "Venda";
      	var idCliente 		= $('#id_cliente').val();

        //Monta os dados de entrada do metodo
        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController +'&idCliente=' + idCliente;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          beforeSend: function() {
            $('#load02').show();
          },          
          complete: function(){
            $('#load02').hide();
          },
          success: function( retorno ){

          	//Se o resultado for ok, verifica os demais itens
            if(retorno.resultado == "sucesso")
            {
            	trasferenciaPasso01paraPasso02(retorno.dados);
            }
          }
        });		        
	}

      

    function inserirValorCaixa($mov, $desc){

      if($desc == '' || $desc == ' '){
        $.confirm({
            title: 'Erro!',
            content: 'Insira uma descrição para a transação',
            type: 'red',
            typeAnimated: true,
            buttons: {
                Ok: function () {
                }
            }
        });
        return false;
      }

      var tipo = 'tipo';
      var texto = 'texto';
      var desc = $desc;
      var lojaBusca = $('#lojaBusca').val();
      if($mov == 0){
        tipo = 'entrada';
        texto = 'Inserir valor? R$';
        $('#cxEtr')[0].reset();
      }
      else{
        tipo = 'saída';
        texto = 'Remover valor? R$ -';
        $('#cxSd')[0].reset();
      }
      var nomeMetodo        = "movimentaCaixa";
      var nomeController    = "Venda";

      $.confirm({
          title: 'Caixa!',
          content: '' +
          '<form action="" class="formName">' +
          '<div class="form-group">' +
          '<label>Valor de ' + tipo +
          ' </label><br/><br />' +
          '<strong>Total: <strong> <input type="text" min="0" class="total" placeholder="0.00" onKeyUp="maskIt(this,event,' + "'" + '###.###.###,##' + "'" + ',true)" />'+
          '</div>' +
          '</form>',
          buttons: {
              formSubmit: {
                  text: 'Enviar',
                  btnClass: 'btn-blue',
                  action: function () {
                      var total = this.$content.find('.total').val();
                      var validaTotal = parseFloat(total);
                      if(validaTotal == 0 || isNaN(validaTotal)){
                          $.alert('Insira alguma valor');
                          return false;
                      }
                      
                      totalMov = total.replace(".", "");
                      totalMov = totalMov.replace(",", ".");
                      
                      if($mov == 1){
                        totalMov = parseFloat(totalMov).toFixed(2) * (-1);
                      }else{
                        totalMov = totalMov;
                      }
                      
                      var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&valor=' + totalMov + '&desc=' + desc +'&lojaBusca=' + lojaBusca;
                      $.confirm({
                        title: 'Confirmar',
                        content: texto+' '+total+
                          '<br> Motivo: '+ desc,
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
                                    if(msg.resultado == "sucesso"){
                                      $.confirm({
                                        title: 'Sucesso!',
                                        content: 'Valor inserido R$'+total,
                                        type: 'green',
                                        typeAnimated: true,
                                        autoClose: 'OK|1000',
                                        buttons: {
                                            OK: function () {
                                            }
                                        }
                                     }); 
                                    }else{
                                      $.alert(msg.descricao);
                                    }
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

    $('#abre-caixa').on('click', function(){
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
    });  


    function BotaoCaixa($situacao){
      if($situacao == 0){
        $('#abre-caixa').removeClass('btn btn-danger').addClass('btn btn-success');
        $("#abre-caixa").html('Aberto');
        $("#abre-caixa").prop("disabled",true);
      }
      if($situacao == 1){
        $('#abre-caixa').removeClass('btn btn-success').addClass('btn btn-danger');
        $("#abre-caixa").html('Fechado');
        $("#abre-caixa").prop("disabled",false);
      }
    }


      function trocar_perfil(id_selecionado){
        var nomeMetodo        = "trocaPerfil";
        var nomeController    = "Venda";
        
        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&id_selecionado=' + id_selecionado;
        
        $.confirm({
          title: 'Trocar perfil',
          content: 'Deseja alterar o perfil?',
          buttons: {
              formSubmit: {
                  text: 'Alterar',
                  btnClass: 'btn-blue',
                  action: function () {
                     $.confirm({
                  title: 'Confirmar',
                  content: 'Deseja alterar o perfil do cliente?',
                  buttons: {
                      confirmar: function () {
                        $.ajax({
                         type: "POST",
                         url: "transferencia/transferencia.php",
                         data: dados,   
                         success: function(){
                            $.alert('Perfil alterado com sucesso!');
                            atualizaItensVenda();
                      }
                    });
                      },
                      cancelar: function () {
                          $.alert('Cancelado');
                      }
                  }
              });
              }
              },
              cancelar: function () {
                  //close
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


    function trasferenciaPasso01paraPasso02(dados)
    {

		//buscar informações do controller para montar o passo 02 
		$('#identificacaoCliente').html("<center><br><p>Cliente <b>"+dados[0].nome+"</b> de perfil <b>"+dados[0].perfil+"</b>. <br/><br/> <button id='botaoPerfil' onclick='trocar_perfil("+dados[0].id_cliente+")' class='btn btn-primary btn-sm'> Trocar Perfil </button></p></center>");		
    $('#valorCreditoCliente').val(dados[0].valor_credito);
    $("#perfilCliente").val(dados[0].id_perfil);
    $('#cpfClienteSelecionado').val(dados[0].cpf);
    $('#emailClienteSelecionado').val(dados[0].email)

    if(dados[0].cpf.length < 12){
        $("#cpfClienteSelecionado").mask("999.999.999-99");
    } else if(dados[0].cpf.length >= 12){
        $("#cpfClienteSelecionado").mask("99.999.999/9999-99");
    }

    $('#cepClienteSelecionado').val(dados[0].cep).mask("99.999-999");;
    $('#ruaClienteSelecionado').val(dados[0].logradouro);
    $('#numeroClienteSelecionado').val(dados[0].numero);
    $('#bairroClienteSelecionado').val(dados[0].bairro);
    $('#cidadeClienteSelecionado').val(dados[0].cidade);
    $('#ufClienteSelecionado').val(dados[0].estado);

    $('#selecaoVendedor').empty();
		for (i = 0; i < dados[0].responsaveis.length; i++) {
		    $('#selecaoVendedor').append('<option value = '+ dados[0].responsaveis[i].id +'>' + dados[0].responsaveis[i].nome + '</option>');
        vendedorAtual = $('#selecaoVendedor').val();
		}


    //Montagem da listagem de tipos de produtos
    $('#tipoProduto').empty();
    $('#tipoProduto').append("<option value=''></option>");
    for (i = 0; i < dados[1].length; i++) {
        $('#tipoProduto').append('<option value = '+ dados[1][i].id_tipo_produto +'>' + dados[1][i].nome_tipo_produto + '</option>');
    }    

    //Montagem da listagem de modelos de produtos
    $('#modeloProduto').empty();
    $('#modeloProduto').append("<option value='0'></option>");
    $('#modeloProduto').append("<option value = 'M'>M</option>");
    $('#modeloProduto').append("<option value = 'F'>F</option>");

    //Montagem da listagem dos produtos
    $('#nomeProduto').empty();
    $('#nomeProduto').append("<option value=''></option>");
    for (i = 0; i < dados[2].length; i++) {
        $('#nomeProduto').append('<option value = '+ dados[2][i].id_produto +'>' + dados[2][i].nome_produto + '</option>');
    }        

    $('#quantidadeProduto').val(1);
    $('#valorUnitarioProduto').val('');

    	//Apresenta a estrutura do PASSO 02
		$('#MenuOpcaoPasso01').removeClass('active');
		$('#MenuOpcaoPasso02').addClass('active');

		$('#passo01').removeClass('in');
		$('#passo01').removeClass('active');
		$('#passo02').addClass('in');
		$('#passo02').addClass('active');		

    atualizaItensVenda();
    $('#erroInclusaoItemVenda').hide();
    $('#sucessoInclusaoItemVenda').hide();  
    $('#sucessoAlteracaoCreditoCliente').hide();
    $('#erroAlteracaoCreditoCliente').hide();


    //aqui - desabilita opção de venda externa passo 2 
    $("#optionsRadiosInline1").prop("checked", true);
    $("#optionsRadiosInline2").prop("checked", false);
    $("#optionsRadiosInline3").prop("checked", true);
    $("#optionsRadiosInline4").prop("checked", false);              
    $("#valorDeslocamento").val('0,0');
    $("#valorDeslocamento").prop("disabled", true);
    



    //Volta ao topo do painel
    $('html, body').animate({scrollTop:$('#painelVendaPrincipal').position().top}, 'slow');
    }



    function incluirItensListagemProdutos(dados) {
      
        $('#nomeProduto').empty();
        for (i = 0; i < dados.length; i++) {
            $('#nomeProduto').append('<option value = '+ dados[i].id_produto +'>' + dados[i].nome_produto + '</option>');

        }

        //Limpar os valores
        $("#valorUnitarioProduto").val(dados[0].preco);
        $('#valorUnitarioProduto').attr('valorOriginal', dados[0].preco);

        $("#valorVarejo").val(dados[0].precoVarejo);
        $('#valorAtacado').val(dados[0].precoAtacado);        

        $("#pesoTotal").val(dados[0].pesoTotal);
    }



    function atualizaItensVenda(){

        var nomeMetodo        = "listarItensVendaSessao";
        var nomeController    = "Venda";
        var valorDeslocamento = 0;

        if($("#valorDeslocamento").val() != '')
          valorDeslocamento = $("#valorDeslocamento").val();

        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&valorDeslocamento=' + valorDeslocamento;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          /*beforeSend: function() {
            $('#load01').show();
          },          
         complete: function(){
            $('#load01').hide();
          },*/
          success: function( retorno ){

            //Se o resultado for ok, inclui os itens da venda na tabela
            if(retorno.resultado == 'sucesso')
            {

              $('#tabelaProdutos tbody').empty();

              for (i = 0; i < retorno.listaProdutos.length; i++) {
                  
                  var newRow = $("<tr>");
                  var cols = "";

                  cols += '<td width="5%">'+retorno.listaProdutos[i].idProduto+'</td>';
                  cols += '<td width="30%">'+retorno.listaProdutos[i].nomeProduto+'</td>';
                  cols += '<td width="12%"><center>'+retorno.listaProdutos[i].quantidadeProduto+'</center></td>';
                  cols += '<td width="12%"><center>'+retorno.listaProdutos[i].pesoTotal+'</center></td>';
                  cols += '<td width="12%"> R$ '+retorno.listaProdutos[i].valor+'</td>';
                  cols += '<td width="12%"> R$ '+retorno.listaProdutos[i].valorTotal+'</td>';
                  cols += "<td width='9%'><button type='button' class='btn btn-default btn-xs' onclick='editarProdutoLista("+retorno.listaProdutos[i].idProduto+","+retorno.listaProdutos[i].quantidadeProduto+")'><i class='fa fa-pencil'></i> Editar</button></td>";
                  cols += "<td width='9%'><button type='button' class='btn btn-default btn-xs' onclick='excluirProdutoLista("+retorno.listaProdutos[i].idProduto+")'><i class='fa fa-times'></i> Excluir</button></td>";

                  newRow.append(cols);
                  $("#tabelaProdutos").append(newRow);    
              }  

              // Alteração na apresentação do perfil do cliente/ atacadista ou varejista
              var textoIdentificacaoCliente = $("#identificacaoCliente").html();
              var novoTexto = "";
              if(retorno.idPerfilCliente == 1)
                  novo = textoIdentificacaoCliente.replace("Atacadista", "Varejista");  
              else
                  novo = textoIdentificacaoCliente.replace("Varejista", "Atacadista");
              $("#identificacaoCliente").html(novo);

              //Alteração nos somatórios dos valores da venda
              newRow = $("<tr>");
              cols = "";
              cols += '<td width="5%"></td>';
              cols += '<td width="30%"><b>TOTAL:</b></td>';
              cols += '<td width="12%"><center>'+retorno.contabilizacao.quantidadeTotal+'</center></td>';
              cols += '<td width="12%"><center>'+retorno.contabilizacao.pesoTotal+'</center></td>';
              cols += '<td width="12%"></td>';
              cols += '<td width="12%"><b>R$ '+retorno.contabilizacao.precoTotal+'</b></td>';
              cols += '<td width="9%"></td>';
              cols += '<td width="9%"></td>';


              newRow.append(cols);
              $("#tabelaProdutos").append(newRow);    
            }

          }
        });
        return false;
   }

   function editarProdutoLista(idProduto, quantidadeAtual){

    var nomeMetodo        = "editarItemVendaSessao";
    var nomeController    = "Venda";

    var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&idProduto=' + idProduto;
    //alert(cell);
    $.confirm({
      title: 'Atualizar Quantidade',
      content: '' +
    '<form class="formName">' +
    '<div class="form-group">' +
    '<label>Quantidade do produto</label>' +
    '<input type="number" min="1" max="999" class="quantidade form-control" value="'+quantidadeAtual+'" required />' +
    '</div>' +
    '</form>',
      buttons: {
          formSubmit: {
              text: 'Alterar',
              btnClass: 'btn-blue',
              action: function () {
              var quantidade = this.$content.find('.quantidade').val();
                $.ajax({
                  type: "POST",
                  url: "transferencia/transferencia.php",
                  data: dados + '&novaQuantidade=' + quantidade,
                  success: function(msg){
                    atualizaItensVenda();
                    $.alert('Quantidade alterada com sucesso!');
                }
              });
            }
          },
          cancelar: function () {
              //close
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
  return false;
}

  
  function excluirProdutoLista(idProduto){

        var nomeMetodo        = "excluirItemVendaSessao";
        var nomeController    = "Venda";

        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&idProduto=' + idProduto;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          /*beforeSend: function() {
            $('#load01').show();
          },          
         complete: function(){
            $('#load01').hide();
          },*/

          success: function( retorno ){

            //Se o resultado for ok, verifica os demais itens
            if(retorno.resultado == "sucesso")
            {
              atualizaItensVenda();
            }
          }});

        
        return false;

  }



    function trasferenciaPasso02paraPasso03()
    {

      if($('#numeroClienteSelecionado').val() == null || $('#numeroClienteSelecionado').val() == 0)
        buscarCep();

      $("#botaoPerfil").prop("disabled",true);

      // Busca o mesmo conteúdo da identificação do cliente do passo 2
      $("#identificacaoClientePasso03").html($("#identificacaoCliente").html());

      $('#erroEvolucaoPasso03').hide();

      // Busca as formas de pagamento cadastradas
        var nomeMetodo    = "buscarDadosPasso03";
        var nomeController  = "Venda";

        //Pega os dados do formulário
        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController +'&idVendedor='+$('#selecaoVendedor option:selected').val()+'&indicadorConsignado='+jQuery("input[name=vendaTipoConsignado]:checked").val();

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          /*beforeSend: function() {
            $('#load01').show();
          },          
         complete: function(){
            $('#load01').hide();
          },*/

          success: function( retorno ){

            //Se o resultado for ok, verifica os demais itens
            if(retorno.resultado == "sucesso")
            {
                $('#idFormaPagamento').empty();
                for (var i = 0; i < retorno.dados[0].length; i++) {
                    $('#idFormaPagamento').append("<option taxa = '"+retorno.dados[0][i].taxaFormaPagamento+"' value = "+ retorno.dados[0][i].idFormaPagamento +">" + retorno.dados[0][i].nomeFormaPagamento + "</option>");
                }

                //Se a primeira forma de pagamento apresentada não tiver taxa, não apresenta a opção de taxa
                if (retorno.dados[0][0].taxaFormaPagamento != 0)
                {
                  $("#indicadorConsiderarTaxasNao").prop("checked", false);
                  $("#indicadorConsiderarTaxasSim").prop("checked", true);
                  $('#apresentacaoTaxas').show();
                }
                else
                {
                  $("#indicadorConsiderarTaxasNao").prop("checked", true);
                  $("#indicadorConsiderarTaxasSim").prop("checked", false);
                  $('#apresentacaoTaxas').hide();
                }

                //Monta o primeiro valor de sugestão
                $('#valorFormaPagamento').val(retorno.dados[1]);

                if( retorno.dados[1] != '0,00')
                {
                    $('#indicadorConclusaoVenda').removeClass('alert-success');
                    $('#indicadorConclusaoVenda').addClass('alert-danger');                                                                       
                    $('#indicadorConclusaoVenda').html("<strong>Atenção: </strong> Ainda resta definir a forma de pagamento de R$ "+retorno.dados[1]+" desta venda.");
                }
                else 
                {
                    $('#indicadorConclusaoVenda').removeClass('alert-danger');
                    $('#indicadorConclusaoVenda').addClass('alert-success');                                                     
                    $('#indicadorConclusaoVenda').html("Não é necessário definir formas de pagamento para esta venda.");
                }
                    

                atualizaResumoPagamento();
                atualizaFormasDePagamento();

                //Apresenta a estrutura do PASSO 02
                $('#MenuOpcaoPasso02').removeClass('active');
                $('#MenuOpcaoPasso03').addClass('active');

                $('#passo02').removeClass('in');
                $('#passo02').removeClass('active');
                $('#passo03').addClass('in');
                $('#passo03').addClass('active');         

                //habilitar o acesso ao passo 02 e 03
                $('#MenuOpcaoPasso02').removeClass('disabled');
                $('#MenuOpcaoPasso02').html("<a href='#passo02' data-toggle='tab'>Passo 02 - Inclusão Produtos</a>");
                $('#MenuOpcaoPasso03').removeClass('disabled');
                $('#MenuOpcaoPasso03').html("<a href='#passo03' data-toggle='tab'>Passo 03 - Definição Pagamento</a>");                

                // Volta ao topo do painel da venda
                $('html, body').animate({scrollTop:$('#painelVendaPrincipal').position().top}, 'slow');                
            }

            //Se ocorrer algum erro
            else 
            {

                // Apresenta a mensagem de erro e não passa para o próximo passo
                $('#erroEvolucaoPasso03').html("<strong>Erro: </strong>"+retorno.descricao);
                $('#erroEvolucaoPasso03').fadeIn();
            }
          }});
    }





    function atualizaFormasDePagamento(){

        var nomeMetodo        = "listarFormasDePagamento";
        var nomeController    = "Venda";


        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          /*beforeSend: function() {
            $('#load01').show();
          },          
         complete: function(){
            $('#load01').hide();
          },*/
          success: function( retorno ){

            //Se o resultado for ok, inclui os itens da venda na tabela
            if(retorno.resultado == 'sucesso')
            {

              $('#listaFormasDePagamento tbody').empty();

              for (i = 0; i < retorno.formasPagamento.length; i++) {
                  
                  var newRow = $("<tr>");
                  var cols = "";

                  cols += '<td>'+retorno.formasPagamento[i].nomeFormaPagamento+'</td>';
                  cols += '<td><center>R$ '+retorno.formasPagamento[i].valorVenda+'</center></td>';
                  cols += '<td><center>R$ '+retorno.formasPagamento[i].valorFormaPagamento+'</center></td>';

                  cols += "<td><button type='button' class='btn btn-default btn-xs' onclick='excluirFormaPagamento("+retorno.formasPagamento[i].idFormaPagamento+")'><i class='fa fa-times'></i> Excluir</button></td>";

                  newRow.append(cols);
                  $("#listaFormasDePagamento").append(newRow);    
              }  
            }
            else
              alert('Erro na listagem das formas de pagamento');

          }
        });
        return false;
   }    




  function excluirFormaPagamento(idFormaPagamento){

        var nomeMetodo        = "excluirFormaPagamento";
        var nomeController    = "Venda";

        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&idFormaPagamento=' + idFormaPagamento;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          /*beforeSend: function() {
            $('#load01').show();
          },          
         complete: function(){
            $('#load01').hide();
          },*/

          success: function( retorno ){

            //Se o resultado for ok, verifica os demais itens
            if(retorno.resultado == "sucesso")
            {

                $('#indicadorConclusaoVenda').removeClass('alert-success');
                $('#indicadorConclusaoVenda').addClass('alert-danger');                                                     
                $('#valorFormaPagamento').val(retorno.novo_valor_sugerido);
                $('#indicadorConclusaoVenda').html("<strong>Atenção: </strong> Ainda resta definir a forma de pagamento de R$ "+retorno.novo_valor_sugerido+" desta venda.");
                atualizaFormasDePagamento();
                atualizaResumoPagamento();
            }
          }});

        
        return false;

  }   

function somenteNumerosPonto( obj , e )
{
    var tecla = ( window.event ) ? e.keyCode : e.which;
    if ( tecla == 8 || tecla == 0 )
        return true;
    if ( tecla != 46 && tecla < 48 || tecla > 57 )
        return false;
}


function SomenteNumero(e){
 var tecla=(window.event)?event.keyCode:e.which;
 if((tecla>47 && tecla<58)) return true;
 else{
 if (tecla==8 || tecla==0) return true;
 else  return false;
 }
}


function typeOf (obj) {
  return {}.toString.call(obj).split(' ')[1].slice(0, -1).toLowerCase();
}


function maskIt(w,e,m,r,a){
// Cancela se o evento for Backspace
if (!e) var e = window.event
if (e.keyCode) code = e.keyCode;
else if (e.which) code = e.which;
// Variáveis da função
var txt  = (!r) ? w.value.replace(/[^\d]+/gi,'') : w.value.replace(/[^\d]+/gi,'').reverse();
var mask = (!r) ? m : m.reverse();
var pre  = (a ) ? a.pre : "";
var pos  = (a ) ? a.pos : "";
var ret  = "";
if(code == 9 || code == 8 || txt.length == mask.replace(/[^#]+/g,'').length) return false;
// Loop na máscara para aplicar os caracteres
for(var x=0,y=0, z=mask.length;x<z && y<txt.length;){
if(mask.charAt(x)!='#'){
ret += mask.charAt(x); x++; } 
else {
ret += txt.charAt(y); y++; x++; } }
// Retorno da função
ret = (!r) ? ret : ret.reverse()  
w.value = pre+ret+pos; }
// Novo método para o objeto 'String'
String.prototype.reverse = function(){
return this.split('').reverse().join(''); };

function number_format( number, decimals, dec_point, thousands_sep ) {
var n = number, c = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals;
var d = dec_point == undefined ? "," : dec_point;
var t = thousands_sep == undefined ? "." : thousands_sep, s = n < 0 ? "-" : "";
var i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}




    function atualizaResumoPagamento(){

        var nomeMetodo        = "listarResumoPagamento";
        var nomeController    = "Venda";

        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          /*beforeSend: function() {
            $('#load01').show();
          },          
         complete: function(){
            $('#load01').hide();
          },*/
          success: function( retorno ){

            //Se o resultado for ok, inclui os itens da venda na tabela
            if(retorno.resultado == 'sucesso')
            {

                $('#resumoPagamentoVenda tbody').empty();

                  var newRow = $("<tr>");
                  var cols = "";

                  /* Coluna Valor Total em Produtos */
                  cols += '<td>Valor Total em Produtos:</td>';
                  cols += '<td>R$ '+retorno.dados.valor_total_produtos+'</td>';
                  newRow.append(cols);
                  $("#resumoPagamentoVenda").append(newRow);  

                  /* Coluna Valor Total em Deslocamento (Frete) */
                  newRow = $("<tr>");
                  cols  = '<td>Valor Total em Deslocamento (Frete):</td>';
                  cols += '<td>R$ '+retorno.dados.valor_total_deslocamento+'</td>';
                  newRow.append(cols);
                  $("#resumoPagamentoVenda").append(newRow);  

                  /* Coluna Valor Total de Desconto do Cliente: */
                  newRow = $("<tr>");
                  cols  = '<td>Valor Total de Desconto na Venda do Cliente:</td>';
                  cols += '<td>R$ '+retorno.dados.valor_total_credito+'</td>';
                  newRow.append(cols);
                  $("#resumoPagamentoVenda").append(newRow);              

                  // Montagem da string taxas
                  newRow = $("<tr>");
                  var taxas = '';
                  for (i = 0; i < retorno.dados.taxas.length; i++) {
                      taxas += "<br>&emsp; &emsp; &emsp; &emsp; &emsp; &emsp; "+ retorno.dados.taxas[i].valor_taxa_forma_pagamento+ " ("+retorno.dados.taxas[i].nome_forma_pagamento+")";
                  }

                  /* Coluna Valor Total em Taxas: */
                  cols  = '<td>Valor Total em Desconto das Formas de Pagamento:</td>';
                  if (taxas != '')
                      cols += '<td>R$ '+retorno.dados.valor_total_taxas+' , sendo <small>'+taxas+'</small></td>';
                  else 
                      cols += '<td>R$ '+retorno.dados.valor_total_taxas+'</td>';
                  newRow.append(cols);
                  $("#resumoPagamentoVenda").append(newRow);    


                  /* Coluna Valor Total de Desconto (em taxas) por Produto: */
                  newRow = $("<tr>");
                  cols  = '<td>Valor Total de Desconto (da Forma de pagamento) por Produto:</td>';
                  cols += '<td>R$ '+retorno.dados.valor_total_acrescimo_pdt+'</td>';
                  newRow.append(cols);
                  $("#resumoPagamentoVenda").append(newRow);    


                  /* Coluna Valor Total : */
                  newRow = $("<tr>");
                  cols  = '<td><b>Valor Total:</b></td>';
                  cols += '<td><b>R$ '+retorno.dados.valor_total+'</b></td>';
                  newRow.append(cols);
                  $("#resumoPagamentoVenda").append(newRow);                                

            }

          }
        });
        return false;
   }    



    function trasferenciaPasso03paraPasso04()
    {
      atualizaDadosCliente();
      $('#erroEvolucaoPasso04').hide();

      // Busca as formas de pagamento cadastradas
        var nomeMetodo    = "gerarReciboPasso04";
        var nomeController  = "Venda";

        //Pega os dados do formulário
        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          /*beforeSend: function() {
            $('#load01').show();
          },          
         complete: function(){
            $('#load01').hide();
          },*/

          success: function( retorno ){

            //Se o resultado for ok, verifica os demais itens
            if(retorno.resultado == "sucesso")
            {
                var result = concluirVenda();
                if(!result)
                {
                  return tentarConcluirVendaNovamente();
                }
                //montaRecibo(retorno.dados);
                //$('#simboloConfirmacao').show();

                $('#avisoVendaNaoConcluida').removeClass('label-success');
                $('#avisoVendaNaoConcluida').addClass('label-danger');
                $('#avisoVendaNaoConcluida').html("Venda não concluída!");                                

                //Apresenta a estrutura do PASSO 04
                $('#MenuOpcaoPasso03').removeClass('active');
                $('#MenuOpcaoPasso04').addClass('active');

                $('#passo03').removeClass('in');
                $('#passo03').removeClass('active');
                $('#passo04').addClass('in');
                $('#passo04').addClass('active');                                   

                // Volta ao topo do painel de venda
                $('html, body').animate({scrollTop:$('#painelVendaPrincipal').position().top}, 'slow');                
            }

            //Se ocorrer algum erro
            else 
            {

                // Apresenta a mensagem de erro e não passa para o próximo passo
                $('#erroEvolucaoPasso04').html("<strong>Erro: </strong>"+retorno.descricao);
                $('#erroEvolucaoPasso04').fadeIn();
            }
          }});
    }   


    function tentarConcluirVendaNovamente(){
      $.confirm({
            title: 'Erro ao salvar venda!',
            content: 'Tentar novamente?',
            type: 'red',
            typeAnimated: true,
            buttons: {
                OK: function () {
                    trasferenciaPasso03paraPasso04();
                },
                Cancelar: function(){
                  self.close();
                }
            }
         }); 
    }
    function atualizaDadosCliente(){
    
      // Busca as formas de pagamento cadastradas
      var nomeMetodo    = "atualizaDadosCliente";
      var nomeController  = "Cadastro";

      //Pega os dados do formulário
      var dados = $('#atualizaDadosCliente').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

      $.ajax({
        dataType: "json",
        type: "POST",
        url: "transferencia/transferencia.php",
        data: dados,
        success: function( retorno ){
          if(retorno.resultado == "sucesso")
          {
                
          }
        }
      });
  }

  function buscarCep(){
    var cep = $('#cepClienteSelecionado').val();
    cep = cep.replace('.','');
    cep = cep.replace('-','');
    if(cep.length != 8)
      return;
    var validacep = /^[0-9]{8}$/;
    //Valida o formato do CEP.
    if(validacep.test(cep)) {

        //Cria um elemento javascript.
        var scriptCep = document.createElement('script');

        //Sincroniza com o callback.
        scriptCep.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

        //Insere script no documento e carrega o conteúdo.
        document.body.appendChild(scriptCep);

    } //end if.
    else {
        //cep é inválido.
        alert("Formato de CEP inválido.");
    }
  }

  function meu_callback(conteudo) {
    if (!("erro" in conteudo)) {
        //Atualiza os campos com os valores.
        document.getElementById('ruaClienteSelecionado').value=(conteudo.logradouro);
        document.getElementById('bairroClienteSelecionado').value=(conteudo.bairro);
        document.getElementById('cidadeClienteSelecionado').value=(conteudo.localidade);
        document.getElementById('ufClienteSelecionado').value=(conteudo.uf);
    } //end if.
    else {
        //CEP não Encontrado.
        alert("CEP não encontrado.");
    }
}


function montaRecibo(dados)
{

  var identificacaoClienteRecibo = "&nbsp;&nbsp;<b>Cliente:</b> "+dados.nome+" ("+dados.perfil+")<br>&nbsp;&nbsp;<b>CPF:</b> "+dados.cpf+"<br>&nbsp;&nbsp;<b>Telefone:</b> "+dados.telefone+"<br>";
  $('#identificacaoClienteRecibo').html(identificacaoClienteRecibo);

  var vendedor = $('#selecaoVendedor option:selected').text();
  if(vendedor == "")
    vendedor = dados.vendedor;

  var identificacaoVendaRecibo = "&nbsp;&nbsp;<b>Código da venda:</b> "+dados.id_venda+"<br>&nbsp;&nbsp;<b>Data:</b> "+dados.dia+"<br>&nbsp;&nbsp;<b>Vendedor:</b> "+vendedor+"<br>";
  $('#identificacaoVendaRecibo').html(identificacaoVendaRecibo);
      
  //Remover as linhas antigas
  $('.itensVendaRecibo').remove();

  for (i = 0; i < dados.itens_venda.length; i++) {

      var linhasProdutos = "";

      var unitario = dados.itens_venda[i].valorUnitario;
      var totalItem = dados.itens_venda[i].valorTotal;
      
      if(dados.descontoItem > 0 && dados.formas_pagamento.length == 1){
        var fp = dados.formas_pagamento[0].nome_forma.toUpperCase().substring(0,8);
        if(fp == "DINHEIRO"){
          var valor_unitario = dados.itens_venda[i].valorUnitario.replace(',','.');
          valor_unitario = valor_unitario * 0.9;
          var valor_total = dados.itens_venda[i].valorTotal.replace(',','.');
          valor_total = valor_total * 0.9;
          valor_unitario = valor_unitario.toFixed(2).replace('.',',');
          valor_total = valor_total.toFixed(2).replace('.',',');

          unitario = unitario + " (R$ " + valor_unitario + ")";
          totalItem = totalItem + " (R$ " + valor_total + ")";
        }
      }


      linhasProdutos  = "<tr style='font-size: 10px' class='itensVendaRecibo'>";
      linhasProdutos += "<td><p style='font-size: 11px; margin: 4px;'>"+dados.itens_venda[i].nomeProduto+"</p></td>";
      linhasProdutos += "<td><p style='font-size: 11px; margin: 4px; text-align:center;'>"+dados.itens_venda[i].quantidade+"</p></td>";
      linhasProdutos += "<td><p style='font-size: 11px; margin: 4px; text-align:left;'>R$ "+ unitario +"</p></td>";
      linhasProdutos += "<td><p style='font-size: 11px; margin: 4px; text-align:left;'>R$ "+totalItem+"</p></td>";
      linhasProdutos += "</tr>";

      $(linhasProdutos).insertBefore("#resumoPagamentoRecibo");
  }

  var totalVenda = dados.valorTotalProdutos.replace(',','.');
  var desconto    = dados.valorCredito.replace(',','.');
  totalVenda = totalVenda - desconto;
  totalVenda = totalVenda.toFixed(2).replace('.',',');

  $("#totalProdutosRecibo").html('<p style="font-size: 11px; margin: 4px; text-align:left;">R$ '+dados.valorTotalProdutos+'</p>');    
  $("#totalVenda").html('<p style="font-size: 11px; margin: 4px; text-align:left;">R$ '+totalVenda+'</p>');
  $("#totalCreditoRecibo").html('<p style="font-size: 11px; margin: 4px; text-align:left;">R$ '+dados.valorCredito+'</p>');
  $("#totalDeslocamentoRecibo").html('<p style="font-size: 11px; margin: 4px; text-align:left;">R$ '+dados.valor_total_outros+'</p>');    
  $("#totalTaxasRecibo").html('<p style="font-size: 11px; margin: 4px; text-align:left;">R$ '+dados.valor_total_taxas+'</p>');
  var loja = "BENFICA";
  var rodape = '<td class="tg-yw4l" colspan="4" style="font-size: 11px;"><center><b>TATIANE (31) 99288-7558<br>HUDSON (31) 98832-9894<br>EDUARDO (31) 97500-1249<br>INSTA: @BENFICA.LOJA</b></center></td>'                                                                           

  if(dados.id_loja == 2){
      loja = "SUITS SIX";
      rodape = '<td class="tg-yw4l" colspan="4" style="font-size: 11px;"><center><b>TATIANE (31) 99288-7558<br>HUDSON (31) 98832-9894<br>EDUARDO (31) 97500-1249<br>INSTA: @SUITS6.STORE</b></center></td>'                                                                           
    }
  $("#tituloRecibo").html('<p style="margin: 12px; text-align:center;">'+loja+' - ATACADO/VAREJO - (31) 2564-7158</p>');    
  $("#rodape").html(rodape);    
  

  //Remover as linhas antigas
  $('.itensFormasRecibo').remove();

  for (i = 0; i < dados.formas_pagamento.length; i++) {

      var linhasFormas = "<tr class='itensFormasRecibo'>";
      //linhasFormas += "<td colspan='3'><p style='font-size: 11px; margin: 4px;'>"+dados.formas_pagamento[i].nome_forma+'<br>&nbsp;&nbsp;&nbsp;&nbsp;- Acréscimo de R$ '+dados.formas_pagamento[i].valor_acrescimo+' (taxa).</p></td>';
      linhasFormas += "<td colspan='2'><p style='font-size: 11px; margin: 4px;'>"+dados.formas_pagamento[i].nome_forma.toUpperCase()+'</p></td>';
      linhasFormas += "<td colspan='1'><p style='font-size: 11px; margin: 4px;'>"+dados.formas_pagamento[i].quantidade_parcelas+' x</p></td>';
      linhasFormas += "<td colspan='1'><p style='font-size: 11px; margin: 4px; text-align:left;'>R$ "+dados.formas_pagamento[i].valor+'</p></td>';
      linhasFormas += "</tr>";
    

      $(linhasFormas).insertBefore("#observacoesRecibo");  
  } 

}

    function printDiv()  
    {
        var conteudo = document.getElementById('recibo').innerHTML;  
        var win = window.open();  
        win.document.write(conteudo);  
        win.print();  
        win.close();//Fecha após a impressão.  
    } 


    function printDivRelatorioComissao()  
    {
        var conteudo = document.getElementById('sessaoImpressaoRelatorio').innerHTML;  
        var win = window.open();  
        win.document.write(conteudo);  
        win.print();  
        win.close();//Fecha após a impressão.  
    }     


    function alterarLojaBusca(id)
    {

        //Busca todos os nomes dos produtos relacionados ao tipo selecionado
        var nomeMetodo    = "alterarLojaBusca";
        var nomeController  = "Sessao";
        
        //Pega os dados do formulário
        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&lojaSelecionada='+ id;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){
            if(retorno == sucesso)
              alert();
          }
        });

    }

    function localizarProdutoPorTipo()
    {

        //Busca todos os nomes dos produtos relacionados ao tipo selecionado
        var nomeMetodo    = "localizarProdutoPorTipo";
        var nomeController  = "Venda";
    
        //Pega os dados do formulário
        if($("#tipoProduto").val() != '')
          var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&idTipoProduto=' + $("#tipoProduto").val() + '&perfilCliente=' + $("#perfilCliente").val() + '&modeloProduto=' + $('#modeloProduto option:selected').val();
        else 
          var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&idTipoProduto=0&perfilCliente=' + $("#perfilCliente").val() + '&modeloProduto=' + $('#modeloProduto option:selected').val();


        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){

            //Se o resultado for ok, monta o nome dos produtos relacionados ao tipo selecionado
            if(retorno.resultado == "sucesso")
            {
               incluirItensListagemProdutos(retorno.dados);               
            }
            else 
            {
                // Quando ocorre algum erro, nenhum produto é exibido

                $('#nomeProduto').empty();
                $("#valorUnitarioProduto").val(0);
                $('#valorUnitarioProduto').attr('valorOriginal', 0);
                $("#valorVarejo").val(0);
                $('#valorAtacado').val(0);        
                $("#pesoTotal").val(0);              
            }

          }
        });

    }

    function localizarProdutoRelatorioPorTipo()
    {

        //Busca todos os nomes dos produtos relacionados ao tipo selecionado
        var nomeMetodo    = "localizarProdutoRelatorioPorTipo";
        var nomeController  = "Venda";
    
        //Pega os dados do formulário
        if($("#tipoProdutoRelatorio").val() != '')
          var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&idTipoProduto=' + $("#tipoProdutoRelatorio").val() + '&perfilCliente=' + $("#perfilCliente").val() + '&modeloProduto=' + $('#modeloProduto option:selected').val();
        else 
          var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&idTipoProduto=0&perfilCliente=' + $("#perfilCliente").val() + '&modeloProduto=' + $('#modeloProduto option:selected').val();

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){

            //Se o resultado for ok, monta o nome dos produtos relacionados ao tipo selecionado
            if(retorno.resultado == "sucesso")
            {
               incluirItensListagemProdutos(retorno.dados);               
            }
            else 
            {
                // Quando ocorre algum erro, nenhum produto é exibido
                $('#nomeProduto').empty();            
            }

          }
        });

    }


    function iniciarNovaVenda(opcao)
    {

      //opcao == 0: desfazer... não deve ser voltado para o passo 01
      if (opcao == 0)
        return false;

      //opcao == 1: confirmação de início da nova venda

        //Busca todos os nomes dos produtos relacionados ao tipo selecionado
        var nomeMetodo      = "iniciarNovaVenda";
        var nomeController  = "Venda";
        var dados           = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){

            //Se o resultado for ok, monta o nome dos produtos relacionados ao tipo selecionado
            if(retorno.resultado)
            {

              $("#AvisoLocalizaCliente").hide();
              $("#ErroLocalizaCliente").hide();
              $("#msgInicial").html("Informe os dados básicos do cliente para iniciar uma nova venda. Este deverá ser cadastrado caso não seja localizado.");

              //Monta a tela do passo 01 
              $('#MenuOpcaoPasso02').removeClass('active');
              $('#MenuOpcaoPasso03').removeClass('active');
              $('#MenuOpcaoPasso04').removeClass('active');

              $('#MenuOpcaoPasso02').addClass('disabled');
              $('#MenuOpcaoPasso03').addClass('disabled');
              $('#MenuOpcaoPasso04').addClass('disabled');

              $('#MenuOpcaoPasso02').html("<a data-toggle=''>Passo 02 - Inclusão Produtos</a>");
              $('#MenuOpcaoPasso03').html("<a data-toggle=''>Passo 03 - Definição Pagamento</a>");
              $('#MenuOpcaoPasso04').html("<a data-toggle=''>Passo 04 - Finalização Venda</a>");                

              $('#passo02').removeClass('in');
              $('#passo03').removeClass('in');
              $('#passo04').removeClass('in');

              $('#passo02').removeClass('active');
              $('#passo03').removeClass('active');
              $('#passo04').removeClass('active');

              $('#MenuOpcaoPasso01').addClass('active');
              $('#passo01').addClass('in');
              $('#passo01').addClass('active');   
              $('#lojaVenda').prop('disabled', false);

              //Volta ao topo do painel
              $('html, body').animate({scrollTop:$('#painelVendaPrincipal').position().top}, 'slow');

              //Fecha o modal
              $('#confirmacaoInicioNovaVenda').delay(1000).fadeOut(450);

              setTimeout(function(){
                $('#confirmacaoInicioNovaVenda').modal("hide");
              }, 1500);   

            }

          }
        });

        return false;

    }    




    function concluirVenda()
    {


      // Busca as formas de pagamento cadastradas
        var nomeMetodo    = "concluirVenda";
        var nomeController  = "Venda";
        var concluiu = true;
        //Pega os dados do formulário
        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        $.confirm({
          title: 'Concluir venda',
          content: function () {
              var self = this;
              return  $.ajax({
                dataType: "json",
                type: "POST",
                url: "transferencia/transferencia.php",
                data: dados,
                /*beforeSend: function() {
                  $('#load01').show();
                },          
               complete: function(){
                  $('#load01').hide();
                },*/
      
                success: function( retorno ){
      
                  //Se o resultado for ok, verifica os demais itens
                  if(retorno.resultado == "sucesso")
                  {
                      
                      montaRecibo(retorno.dados);
                      //habilitar o acesso ao passo 02 e 03
                      $('#MenuOpcaoPasso02').addClass('disabled');
                      $('#MenuOpcaoPasso02').html("<a data-toggle=''>Passo 02 - Inclusão Produtos</a>");
                      $('#MenuOpcaoPasso03').addClass('disabled');
                      $('#MenuOpcaoPasso03').html("<a data-toggle=''>Passo 03 - Definição Pagamento</a>");                
                      
                      $('#avisoVendaNaoConcluida').removeClass('label-danger');
                      $('#avisoVendaNaoConcluida').addClass('label-success');
                      $('#avisoVendaNaoConcluida').html("Venda concluída!");
      
                      $('#simboloConfirmacao').hide();
                      // Volta ao topo do painel de venda
                      $('html, body').animate({scrollTop:$('#painelVendaPrincipal').position().top}, 'slow');    
                      criarPedido(retorno.dados.id_venda);            
                  }else{
                    concluiu = false;
                  }
                }
              }).done(function (response) {
                  self.close();
              }).fail(function(){
                  concluiu = false;
                  self.close();
              });
          }
        });
      return concluiu;
    }   


    function imprimirCupom(idVenda)
    {


      // Busca as formas de pagamento cadastradas
        var nomeMetodo    = "imprimirCupom";
        var nomeController  = "Venda";

        //Pega os dados do formulário
        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&idVenda=' + idVenda;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){

            //Se o resultado for ok, verifica os demais itens
            if(retorno.resultado == "sucesso")
            {

                montaRecibo(retorno.dados);       
            }
          }});
    }   

    function excluirVenda(desfazer, idVenda)
    {

      $('#ErroExclusaoVenda').hide(); 

      if(desfazer == true)
      {
        $('#confirmacaoExclusaoVenda').modal('hide');
        setTimeout(function () {
            $('#myModal1').modal('show')
        }, 2000);
        return false;
      }


      // Busca as formas de pagamento cadastradas
        var nomeMetodo    = "excluirVenda";
        var nomeController  = "Venda";

        //Pega os dados do formulário
        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&idVenda=' + idVenda;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          /*beforeSend: function() {
            $('#load01').show();
          },          
         complete: function(){
            $('#load01').hide();
          },*/

          success: function( retorno ){

            //Se o resultado for ok, verifica os demais itens
            if(retorno.resultado == "sucesso")
            {
                $('#confirmacaoExclusaoVenda').modal('hide');
                setTimeout(function () {
                    $('#myModal1').modal('show')
                }, 2000);

                $('#ErroExclusaoVenda').removeClass('alert-danger');
                $('#ErroExclusaoVenda').addClass('alert-success');
                $('#ErroExclusaoVenda').html('<center>'+retorno.descricao+'</center>');
                $('#ErroExclusaoVenda').fadeIn();                
                $('#sessaoResultadoVendasLocalizadas').fadeOut();    
            }
            else
            {

                $('#confirmacaoExclusaoVenda').modal('hide');
                setTimeout(function () {
                    $('#myModal1').modal('show')
                }, 2000);

                $('#ErroExclusaoVenda').removeClass('alert-success');
                $('#ErroExclusaoVenda').addClass('alert-danger');
                $('#ErroExclusaoVenda').html('<strong>Erro: </strong> '+retorno.descricao);
                $('#ErroExclusaoVenda').fadeIn();
            }
          }});
    }       


    function modalConfirmacaoExclusaoVenda(idVenda)
    {

      $('#textoModalExclusao').html('Deseja realmente excluir a venda de código '+idVenda+' ?');


      $('#confirmaExclusao').attr("onclick", "excluirVenda(false, "+idVenda+");");
      $('#naoConfirmaExclusao').attr("onclick", "excluirVenda(true, false);");


      $('#confirmacaoExclusaoVenda').modal('show');
      setTimeout(function () {
          $('#myModal1').modal('hide')
      }, 2000);

    }



    function autenticacaoCancelamentoVenda(idVenda)
    {

      // Primeiro , verifica se o usuário tem acesso a operação 
        var nomeMetodo      = "verificaPermissaoExclusaoVenda";
        var nomeController  = "Sessao";

        //Pega os dados do formulário
        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,



          success: function( retorno ){

            //Se o resultado for ok, verifica os demais itens
            if(retorno.resultado == "sucesso")
            {

                //Abre o modal somente se não tiver permissão
                if(retorno.permissao == 'N')
                {
                    $('#idVenda').val(idVenda);

                    //Abre o modal de autenticação
                    $('#autenticacaoExclusaoVenda').modal('show');
                    setTimeout(function () {
                        $('#myModal1').modal('hide')
                    }, 2000);
                }

                //Se tiver permissão abre só o modal de confirmação de exclusão
                else
                {
                    modalConfirmacaoExclusaoVenda(idVenda);
                }
            }
            else
            {
                alert("Ocorreu um erro ao buscar as permissões do usuário");
            }
          }});
    }

    function visualizarProdutos(idCliente, loja)
    {
        var dataRelatorio = document.getElementById("identificacaoCriterioRelatorioPecaCliente").textContent;
        var dtIn = (dataRelatorio.split(' - ')[0]);
        var dtFn = (dataRelatorio.split(' - ')[1]);
        dtIn = dtIn.split('/')[2]+'-'+dtIn.split('/')[1]+'-'+dtIn.split('/')[0];
        dtFn = dtFn.split('/')[2]+'-'+dtFn.split('/')[1]+'-'+dtFn.split('/')[0];
        

        var nomeMetodo     = "RelatorioPecasCliente";
        var nomeController = "Relatorio";
        //Pega os dados do formulário
        var dados = '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController +'&idCliente=' +idCliente+'&dtInicial="'+dtIn+'"&dtFinal="'+dtFn+'"&lojaBuscaCliente='+loja;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){
            if(retorno.resultado == "sucesso")
            {
              $('#tablePecasCliente tbody').empty();
              for (var i = 0; i < retorno.dados.length; i++) {
                  
                var newRow = $("<tr>");
                var cols = "";
                
                cols += '<td><center>'+retorno.dados[i].descricao+'</center></td>';
                cols += '<td>'+retorno.dados[i].quantidade+'</td></tr>';                  
                
                newRow.append(cols);

                $("#tablePecasCliente").append(newRow);
              }  

              $("#identificacaoCriterioRelatorioPecaCliente").html(retorno.data_inicial + " - "+retorno.data_final);
              $('#sessaoRelatorioPecaCliente').fadeIn();               

            }
            else
            {
              $('#ErroGerarRelatorioFinanceiro').html("<strong>Erro: </strong>"+retorno.descricao);
              $('#ErroGerarRelatorioFinanceiro').fadeIn();               
              $('#sessaoRelatorioFinanceiro').hide();               
            }

          }
        });
    }           

    function criarPedido(id){

        var nomeMetodo     = "novoPedido";
        var nomeController = "Venda";
        //Pega os dados do formulário
        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&id=' + parseInt(id);

        $.confirm({
              title: 'Criar Nota?',
              content: 'Venda ' + id,
              buttons: {
                  Confirmar: function () {
                    $.confirm({
                      title: 'Nota fiscal',
                      content: function () {
                          var self = this;
                          return $.ajax({
                            dataType: "json",
                            type: "POST",
                            url: "transferencia/transferencia.php",
                            data: dados,
                            success: function( retorno ){
                              if(retorno.resultado == "Sucesso")
                              {
                                $.confirm({
                                    title: 'Sucesso',
                                    content: retorno.descricao,
                                    type: 'green',
                                    typeAnimated: true,
                                    buttons: {
                                        OK: {
                                            text: 'OK',
                                            btnClass: 'btn-green',
                                            action: function(){
                                            }
                                        }
                                    }
                                }); 
                              }
                              else if(retorno.resultado == "Nota"){
                                window.open(retorno.descricao);
                              }
                              else
                              {
                                $.confirm({
                                    title: 'Erro',
                                    content: retorno.descricao,
                                    type: 'red',
                                    typeAnimated: true,
                                    buttons: {
                                        OK: {
                                            text: 'OK',
                                            btnClass: 'btn-red',
                                            action: function(){
                                            }
                                        }
                                    }
                                });  
                              }
      
                            }
                          }).done(function (response) {
                              self.close();
                          }).fail(function(){
                              self.close();
                          });
                      }
                    });
                  },
                  Não: function () {
                    $.alert('Nota não emitida!');
                  }
              }
          });
        return false;
      }


 function emitirNota(id){

        var nomeMetodo     = "emitirNota";
        var nomeController = "Venda";
        //Pega os dados do formulário
        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&id=' + parseInt(id);

        $.confirm({
              title: 'Emitir Nota',
              content: 'Venda ' + id,
              buttons: {
                  Confirmar: function () {
                    $.confirm({
                      title: 'Nota fiscal',
                      content: function () {
                          var self = this;
                          return $.ajax({
                            dataType: "json",
                            type: "POST",
                            url: "transferencia/transferencia.php",
                            data: dados,
                            success: function( retorno ){
                              if(retorno.resultado == "Sucesso")
                              {
                                $.confirm({
                                    title: 'Sucesso',
                                    content: retorno.descricao,
                                    type: 'green',
                                    typeAnimated: true,
                                    buttons: {
                                        OK: {
                                            text: 'OK',
                                            btnClass: 'btn-green',
                                            action: function(){
                                            }
                                        }
                                    }
                                }); 
                              }
                              else if(retorno.resultado == "Nota"){
                                window.open(retorno.descricao);
                              }
                              else
                              {
                                $.confirm({
                                    title: 'Erro',
                                    content: retorno.descricao,
                                    type: 'red',
                                    typeAnimated: true,
                                    buttons: {
                                        OK: {
                                            text: 'OK',
                                            btnClass: 'btn-red',
                                            action: function(){
                                            }
                                        }
                                    }
                                });  
                              }
      
                            }
                          }).done(function (response) {
                              self.close();
                          }).fail(function(){
                              self.close();
                          });
                      }
                    });
                  },
                  Não: function () {
                    $.alert('Nota não emitida!');
                  }
              }
          });
        return false;
      }

    function devolverConsignado(id_produto, produto, quantidade_dev){

      var nomeMetodo     = "devolveConsignado";
      var nomeController = "Consignado";
      
      //Pega os dados do formulário
      var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&produtoSelecionado=' + id_produto;

        $.confirm({
          title: ''+produto+'</br>',
          content: '' +
          '<form id="form_comissao" name="form_comissao" >' +
          '<div class="form-group">' +
          '<label>Já devolveu '+quantidade_dev+'</br>Informe quantos devolver:</label>' +
          '<input type="text" placeholder="" class="valor form-control" id="pct" name="valor" required />' +
          '</div>' +
          '</form>',
          buttons: {
              formSubmit: {
                  text: 'Alterar',
                  btnClass: 'btn-blue',
                  action: function () {
                      var valor = this.$content.find('.valor').val();
                      if (!valor){
                          $.alert('Insira um valor válido');
                          return false;
                      }
                      if (isNaN( valor )){
                        $.alert('Insira um valor válido');
                        return false;
                      }
                      if ( valor >= 100){
                        $.alert('Insira um valor válido');
                        return false;
                      }
              
                    $.confirm({
                      title: 'Confirmar',
                      content: 'Deseja devolver mais ' + valor + ' peças?',
                      buttons: {
                          confirmar: function () {
                            $.ajax({
                            type: "POST",
                            url: "transferencia/transferencia.php",
                            data: dados + "&quantidade=" + valor,
                            success: function(msg){
                            $.alert(valor +' voltaram para o estoque!');
                            atualizaTabelaConsignado();
                          }
                    });
                      },
                      cancelar: function () {
                          $.alert('Cancelado');
                      }
                  }
              });
              }
              },
              cancelar: function () {
                  //close
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

    function atualizaTabelaConsignado(){
        var nomeMetodo     = "gerarRelatorioConsignado";
        var nomeController = "Relatorio";

        var nomeLoja = $("#lojaBusca>option:selected").html();
        
        //Pega os dados do formulário
        var dados = $('#gerarRelatorioConsignado').serialize() + '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&produtoSelecionado=' + $('#nomeProduto').val();

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          success: function( retorno ){
            if(retorno.resultado == "sucesso")
            {
              $('#tabelaVendasConsignadas tbody').empty();
              for (var i = 0; i < retorno.dados.produtos.length; i++) {
                    var newRowd = $("<tr>");
                    var col = "";
                    col += '<td>'+retorno.dados.produtos[i].loja+'</td>';
                    col += '<td>'+retorno.dados.produtos[i].dta_venda+'</td>'; 
                    col += '<td>'+retorno.dados.produtos[i].id_venda+'</td>'; 
                    col += '<td>'+retorno.dados.produtos[i].descricao+'</td>'; 
                    col += '<td>'+retorno.dados.produtos[i].quantidade+'</td>'; 
                    col += '<td>'+retorno.dados.produtos[i].devolvido+'</td>'; 
                    col += '<td>R$ '+retorno.dados.produtos[i].valor+'</td>'; 
                    col += '<td>R$ '+retorno.dados.produtos[i].total+'</td>'; 
                    col += '<td>R$ '+retorno.dados.produtos[i].restante+'</td>'; 
                    col += '<td>'+retorno.dados.produtos[i].nome+'</td>'; 
                    col += "<td><center><button onclick='devolverConsignado("+retorno.dados.produtos[i].id_produto+',"'+retorno.dados.produtos[i].descricao+'",'+retorno.dados.produtos[i].devolvido+");'><i class='fa fa-reply'></i></button></center></td>";
                    newRowd.append(col);
                    $("#tabelaVendasConsignadas").append(newRowd);
              }  
              //Cabeçalho do relatório
              $("#identificacaoCriterioRelatorio").html("Período de Referência: "+retorno.dados.data_inicial + " a "+retorno.dados.data_final);
              $('#tituloRelMes').html("<b>Relatório de peças - Loja "+nomeLoja+"</b>");
              $('#sessaoRelatorio').fadeIn();               

            }
            else
            {
              $('#ErroGerarRelatorio').html("<strong>Erro: </strong>"+retorno.descricao);
              $('#ErroGerarRelatorio').fadeIn();               
              $('#sessaoRelatorio').hide();               
            }

          }
        });
        return false;
    }
	


  function fecharModalAutenticacaoExclusaoVenda()
  {
    alteraConsignado = false;
  	alteraVendedor = false;

    $("#login").val("");
    $("#senha").val("");
    $('#erroAutorizacaoExclusaoVenda').html("");
    $('#erroAutorizacaoExclusaoVenda').hide();

    $('#autenticacaoExclusaoVenda').modal('hide');
    setTimeout(function () {
        $('#myModal1').modal('show')
    }, 2000);

  }    


  function autenticarUsuarioAouS(tipoChamada)
  {

        var nomeMetodo      = "autenticarAdministradorSubGerente";
        var nomeController  = "Sessao";
    
        //Pega os dados do formulário
        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&login=' + $("#login").val() + '&senha=' + $("#senha").val();

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados, 
          success: function( retorno ){

            if(retorno.resultado == "erro")
            {
                if(tipoChamada == 1)
                {
                  $('#erroAutorizacaoExclusaoVenda').html("Usuário não encontrado na base de dados ou usuário também sem permissão para exclusão da venda.");
                  $('#erroAutorizacaoExclusaoVenda').show();          
                }
                else if(tipoChamada == 2)
                {
                  $('#erroAutorizacaoAlteracaoVenda').html("Usuário não encontrado na base de dados ou usuário também sem permissão para exclusão da venda.");
                  $('#erroAutorizacaoAlteracaoVenda').show();          
                }                
            }
            else if (retorno.resultado == "sucesso")
            {
	          if(alteraConsignado){
              if(retorno.permissao == 'S')
                {
  	          	  alteraConsignado = false;
  	          	  $("#optionsRadiosInline2").prop('checked', true);
                	$("#optionsRadiosInline1").prop('checked', false);
  				        
                  $("#login").val("");
                  $("#senha").val("");
                  $('#erroAutorizacaoAlteracaoVenda').html("");
                  $('#erroAutorizacaoAlteracaoVenda').hide();


                  $('#autenticacaoAlteracaoVenda').modal('hide');
                  setTimeout(function () {
                      $('#myModal1').modal('show')
                  }, 2000);
                }
              else{
                  $('#erroAutorizacaoAlteracaoVenda').html("Usuário não encontrado na base de dados ou usuário também sem permissão para exclusão da venda.");
                  $('#erroAutorizacaoAlteracaoVenda').show();  
               }
	          } 
            else if(alteraVendedor)
            {
              if(retorno.permissao == 'S')
                {
                  alteraVendedor = false;
                  $("#selecaoVendedor").val(vendedorFuturo);
                  $("#login").val("");
                  $("#senha").val("");
                  $('#erroAutorizacaoAlteracaoVenda').html("");
                  $('#erroAutorizacaoAlteracaoVenda').hide();


                  $('#autenticacaoAlteracaoVenda').modal('hide');
                  setTimeout(function () {
                      $('#myModal1').modal('show')
                  }, 2000);
                }
               else{
                  $('#erroAutorizacaoExclusaoVenda').html("Usuário não encontrado na base de dados ou usuário também sem permissão para exclusão da venda.");
                  $('#erroAutorizacaoExclusaoVenda').show();  
               }
            } 
            else if(tipoChamada == 1)
            {
                if(retorno.permissao == 'S')
                {

                  fecharModalAutenticacaoExclusaoVenda();
                  modalConfirmacaoExclusaoVenda($('#idVenda').val());          

                }
                else
                {

                  $('#erroAutorizacaoExclusaoVenda').html("Usuário não encontrado na base de dados ou usuário também sem permissão para exclusão da venda.");
                  $('#erroAutorizacaoExclusaoVenda').show();                          

                }
            }
            else if(tipoChamada == 2)
            {
                  if(retorno.permissao == 'N')
                  {
                    $('#erroAutorizacaoAlteracaoVenda').html("Usuário não encontrado na base de dados ou usuário também sem permissão para exclusão da venda.");
                    $('#erroAutorizacaoAlteracaoVenda').show();                          
                  }
                  else 
                  {

                      $("#login").val("");
                      $("#senha").val("");
                      $('#erroAutorizacaoAlteracaoVenda').html("");
                      $('#erroAutorizacaoAlteracaoVenda').hide();
                      $("#controle").val(1);

                      $('#autenticacaoAlteracaoVenda').modal('hide');
                      setTimeout(function () {
                          $('#myModal1').modal('show')
                      }, 2000);
                  }
              }              

            }
          }
        });

  }


    function autenticacaoAlteracaoVenda()
    {
      // Primeiro , verifica se o usuário tem acesso a operação 
        var nomeMetodo      = "verificaPermissaoExclusaoVenda";
        var nomeController  = "Sessao";

        //Pega os dados do formulário
        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,


          success: function( retorno ){

            //Se o resultado for ok, verifica os demais itens
            if(retorno.resultado == "sucesso")
            {

                //Abre o modal somente se não tiver permissão
                if(retorno.permissao == 'N')
                {

                    // bloqueia o botão de incluir item na venda
                    $("#botaoIncluirItemVenda").prop("disabled", true);

                    //Abre o modal de autenticação
                    $('#autenticacaoAlteracaoVenda').modal('show');
                    setTimeout(function () {
                        $('#myModal1').modal('hide')
                    }, 2000);
                }
                else
                {
                  $("#botaoIncluirItemVenda").prop("disabled", false);
                }
            }
            else
            {
                alert("Ocorreu um erro ao buscar as permissões do usuário");
            }
          }});
    }   

    var alteraConsignado = false;
    function autenticacaoVendaConsignado()
    {
    	$("#optionsRadiosInline1").prop("checked", true);
        $("#optionsRadiosInline2").prop('checked', false);
    // Primeiro , verifica se o usuário tem acesso a operação 
      var nomeMetodo      = "verificaPermissaoVenderConsignado";
      var nomeController  = "Sessao";

      //Pega os dados do formulário
      var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

      $.ajax({
        dataType: "json",
        type: "POST",
        url: "transferencia/transferencia.php",
        data: dados,


        success: function( retorno ){

          //Se o resultado for ok, verifica os demais itens
          if(retorno.resultado == "sucesso")
          {

              //Abre o modal somente se não tiver permissão
              if(retorno.permissao == 'N')
              {
              	 alteraConsignado = true;
                  //Abre o modal de autenticação
                  $('#autenticacaoAlteracaoVenda').modal('show');
                  setTimeout(function () {
                      $('#myModal1').modal('hide')
                  }, 2000);
              }
              else{
                  $("#optionsRadiosInline2").prop('checked', true);
              	  $("#optionsRadiosInline1").prop('checked', false);
              }
          }
          else
          {
              alert("Ocorreu um erro ao buscar as permissões do usuário");
          }
        }
      });
    }

    
    var alteraVendedor = false;
    var vendedorFuturo;
    function autenticacaoAlterarVendedor()
    {
      vendedorFuturo = $('#selecaoVendedor').val();

      $('#selecaoVendedor').val(vendedorAtual);
      // Primeiro , verifica se o usuário tem acesso a operação 
      var nomeMetodo      = "verificaPermissaoVenderConsignado";
      var nomeController  = "Sessao";

      //Pega os dados do formulário
      var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

      $.ajax({
        dataType: "json",
        type: "POST",
        url: "transferencia/transferencia.php",
        data: dados,


        success: function( retorno ){

          //Se o resultado for ok, verifica os demais itens
          if(retorno.resultado == "sucesso")
          {
              //Abre o modal somente se não tiver permissão
              if(retorno.permissao == 'N')
              {
                 alteraVendedor = true;
                  //Abre o modal de autenticação
                  $('#autenticacaoAlteracaoVenda').modal('show');
                  setTimeout(function () {
                      $('#myModal1').modal('hide')
                  }, 2000);
              }else{
                $('#selecaoVendedor').val(vendedorFuturo);
              }
          }
          else
          {
              alert("Ocorreu um erro ao buscar as permissões do usuário");
          }
        }
    });
  }

  function prosseguirRegistroFuncionarioDuplicado()
  {

        var nomeMetodo     = "localizarFuncionarioPorId";
        var nomeController = "Consignado";
        var idFuncionario  = $('#id_funcionario').val();

        //Monta os dados de entrada do metodo
        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController +'&idFuncionario=' + idFuncionario;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          beforeSend: function() {
            $('#load02').show();
          },          
         complete: function(){
            $('#load02').hide();
          },
          success: function( retorno ){

            //Se o resultado for ok, verifica os demais itens
            if(retorno.resultado == "sucesso")
            {
              trasferenciaSelecaoProdutosConsignado(retorno.dados);
            }
          }
        });           

  }    




    function trasferenciaSelecaoProdutosConsignado(dados)
    {

    //buscar informações do controller para montar o passo 02 
    $('#identificacaoCliente').html("<center><br><p>Funcionário <b>"+dados[0].nome+"</b>.</p></center>");    

    //Montagem da listagem de tipos de produtos
    $('#tipoProduto').empty();
    $('#tipoProduto').append("<option value=''></option>");
    for (i = 0; i < dados[1].length; i++) {
        $('#tipoProduto').append('<option value = '+ dados[1][i].id_tipo_produto +'>' + dados[1][i].nome_tipo_produto + '</option>');
    }    

    //Montagem da listagem de modelos de produtos
    $('#modeloProduto').empty();
    $('#modeloProduto').append("<option value='0'></option>");
    $('#modeloProduto').append("<option value = 'M'>M</option>");
    $('#modeloProduto').append("<option value = 'F'>F</option>");

    //Montagem da listagem dos produtos
    $('#nomeProduto').empty();
    $('#nomeProduto').append("<option value=''></option>");
    for (i = 0; i < dados[2].length; i++) {
        $('#nomeProduto').append('<option value = '+ dados[2][i].id_produto +'>' + dados[2][i].nome_produto + '</option>');
    }        

    $('#quantidadeProduto').val(1);
    $('#valorUnitarioProduto').val('');

      //Apresenta a estrutura do PASSO 02
    $('#MenuOpcaoPasso01').removeClass('active');
    $('#MenuOpcaoPasso02').addClass('active');

    $('#passo01').removeClass('in');
    $('#passo01').removeClass('active');
    $('#passo02').addClass('in');
    $('#passo02').addClass('active');   

    //pendente
    //atualizaItensVenda();
    atualizaItensConsignado();
    $('#erroInclusaoItemVenda').hide();
    $('#sucessoInclusaoItemVenda').hide();  

    //Volta ao topo do painel
    $('html, body').animate({scrollTop:$('#painelVendaPrincipal').position().top}, 'slow');
    }  




    function atualizaItensConsignado(){

        var nomeMetodo        = "listarItensConsignadoSessao";
        var nomeController    = "Consignado";

        var dados = 'nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController;

        $.ajax({
          dataType: "json",
          type: "POST",
          url: "transferencia/transferencia.php",
          data: dados,
          /*beforeSend: function() {
            $('#load01').show();
          },          
         complete: function(){
            $('#load01').hide();
          },*/
          success: function( retorno ){

            //Se o resultado for ok, inclui os itens da venda na tabela
            if(retorno.resultado == 'sucesso')
            {

              $('#tabelaProdutos tbody').empty();

              for (i = 0; i < retorno.listaProdutos.length; i++) {
                  
                  var newRow = $("<tr>");
                  var cols = "";

                  cols += '<td width="6%">'+retorno.listaProdutos[i].idProduto+'</td>';
                  cols += '<td width="31%">'+retorno.listaProdutos[i].nomeProduto+'</td>';
                  cols += '<td width="13%"><center>'+retorno.listaProdutos[i].quantidadeProduto+'</center></td>';
                  cols += '<td width="13%"><center>'+retorno.listaProdutos[i].pesoTotal+'</center></td>';
                  cols += '<td width="13%"> R$ '+retorno.listaProdutos[i].valor+'</td>';
                  cols += '<td width="13%"> R$ '+retorno.listaProdutos[i].valorTotal+'</td>';
                  cols += "<td width='10%'><button type='button' class='btn btn-default btn-xs' onclick='excluirProdutoLista("+retorno.listaProdutos[i].idProduto+")'><i class='fa fa-times'></i> Excluir</button></td>";

                  newRow.append(cols);
                  $("#tabelaProdutos").append(newRow);    
              }  

              // Alteração na apresentação do perfil do cliente/ atacadista ou varejista
              var textoIdentificacaoCliente = $("#identificacaoCliente").html();
              var novoTexto = "";
              if(retorno.idPerfilCliente == 1)
                  novo = textoIdentificacaoCliente.replace("Atacadista", "Varejista");  
              else
                  novo = textoIdentificacaoCliente.replace("Varejista", "Atacadista");
              $("#identificacaoCliente").html(novo);

              //Alteração nos somatórios dos valores da venda
              newRow = $("<tr>");
              cols = "";
              cols += '<td width="6%"></td>';
              cols += '<td width="31%"><b>TOTAL:</b></td>';
              cols += '<td width="13%"><center>'+retorno.contabilizacao.quantidadeTotal+'</center></td>';
              cols += '<td width="13%"><center>'+retorno.contabilizacao.pesoTotal+'</center></td>';
              cols += '<td width="13%"></td>';
              cols += '<td width="13%"><b>R$ '+retorno.contabilizacao.precoTotal+'</b></td>';
              cols += '<td width="10%"></td>';

              newRow.append(cols);
              $("#tabelaProdutos").append(newRow);    
            }

          }
        });
        return false;
   }
