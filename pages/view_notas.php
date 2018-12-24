<!-- Painel principal da venda  -->
<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <!-- Chamada do cabeçalho da página -->
        <?php require_once "auxiliar/cabecalho.php"; ?>

        <!-- Chamada do menu principal da página -->
        <?php require_once "auxiliar/menu.php"; ?>
    </nav>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Sessão de Notas</h1>
            </div>
        </div>
        
        <div class="row">
     
            <!-- Barra de progresso da venda -->
            <div class="col-lg-12" >

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Informe os critérios de referência para geração do relatório:
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body" >
                    <form role="form" id="gerarRelatorioPedidos" method="POST">

                    <!-- /.row -->
                    <div class="row">


                        <div class="col-lg-4">
                            <div class="form-group " >
                               <label>Nome Cliente:</label>
                               <input id="nomeCliente" name="nomeCliente" style="width: 100%" class="form-control">
                            </div>
                         </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                               <label>Venda:</label>
                               <br><input type="number" name="id_venda" class="form-control" >
                            </div>
                         </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Forma Pagamento:</label>
                                <select class="form-control" name="idPagamento" id="idPagamento">
                                    <option value="0"> Todos </option>
                                    <?php foreach ($listaFormaPagamento['dados'] as $key => $value): ?>
                                        <option value="<?php echo $value['idFormaPagamento']; ?>"> <?php echo $value['nomeFormaPagamento']; ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>                                            
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Multiplas:</label>
                                <select class="form-control" name="multiplasFormas" id="multiplasFormas">
                                    <option value="t"> Todas </option>
                                    <option value="s"> Sim </option>
                                    <option value="n"> Não </option>
                                </select>
                            </div>                                            
                        </div>  

                    </div>
                    <div class="row">

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Data Inicial:</label>
                                <br><input type="date" name="dataInicial" id="dataInicial" class="form-control" >
                            </div>                                            
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Data Final:</label>
                                <br><input type="date" name="dataFinal" id="dataFinal" class="form-control" >
                            </div>                                            
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Emitida:</label>
                                <br>
                                <select class="form-control" name="situacao" id="situacao">
                                    <option value="1"> Sim </option>
                                    <option value="0"> Não </option>
                                    <option value="2" selected> Todas </option>
                                </select>
                            </div>  
                        </div>

                        <?php if ($_SESSION['usuario']['id_loja'] == '0'): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Loja:</label>
                                <br>
                                <select class="form-control" name="lojaBusca" id="lojaBusca">
                                    <?php foreach ($listarLojas as $key => $value): ?>
                                        <option value="<?php echo $value['id_loja']; ?>"> <?php echo $value['descricao']; ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>  
                        </div>
                        <?php endif; ?>

                        <div class="col-lg-12">
                            <div class="row">
                                <center>
                                    <div class="col-md-5"></div>
                                    <div class="col-md-2">
                                        <button type="submit" id="botaoGerarRelatorioFinanceiro" class="form-control btn btn-default">
                                            <i class="fa fa-search"></i>
                                            Gerar Relatório
                                        </button>
                                    </div>
                                </center>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <br>

                            <div class="alert alert-danger" role="alert" id="ErroGerarRelatorioPedidos" style="display: none;">
                                <strong>Erro: </strong> Não foi localizada nenhuma venda para a pesquisa realizada.
                            </div>                                    

                        </div>
                    </div>
                    </form> 

                    </div>
                </div>

                <div class="panel panel-default" id="sessaoRelatorioPedidos" style="display: none;" >

                    <div id="sessaoImpressaoRelatorio">
                        <div class="panel-heading">
                            <h4 style="text-align: center" id="tituloRel"><b>Relatório Pedidos </b></h4>
                            <br />
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-1"></div>
                                <div class="col-lg-10">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="tabelaPedidos">
                                        <thead>
                                            <tr>
                                                <th colspan = "10" ><h4><b><center>Pedidos Criados no Tiny</center></b></h4></th>
                                            </tr>                                        
                                            <tr>
                                                <th  width="9%" style="text-align:center">Venda</th>
                                                <th  width="35%" style="text-align:center">Cliente</th>
                                                <th  width="10%" style="text-align:center">Total</th>
                                                <th  width="12%" style="text-align:center">Data</th>
                                                <th  width="16%" style="text-align:center">Nº Nota</th>
                                                <th  width="7%" style="text-align:center">Emitida</th>
                                                <th  width="6%" style="text-align:center">Criar</th>
                                                <th  width="6%" style="text-align:center">Cupom</th>
                                                <th width="6%" style="text-align:center">Emitir</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                                <hr>                         
                                </div>                
                            </div>
                        </div>
                    </div>
                    <!-- /.panel-body -->
                </div>


            <div class="modal fade" id="modalRecibo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="tab-pane" id="passo04">
                        <br>
                        <div class="iconeFixo">
                           <BR>
                           <a href="javascript:void(0)" onclick="printDiv();" ><img src="library/impressora-2.png" alt="Imprimir Recibo" height="50" width="50"></a>
                        </div>
                        <br>
                        <div id="recibo">
                           <center>
                              <table border="1" id="tabelaRecibo" width="90%">
                                 <tfixo1>
                                    <tr bgcolor="#DCDCDC">
                                       <th width="100%" colspan="4">
                                          <p style="margin: 12px; text-align:center;" id="tituloRecibo"></p>
                                       </th>
                                    </tr>
                                    <tr bgcolor="#F5F5F5">
                                       <td colspan="4">
                                          <p style="font-size: 11px; text-align:center; margin: 8px;">SEGUNDA A SEXTA DE 09:00HS ÀS 18:00HS<br>SÁBADO DE 09:00HS ÀS 17:00HS</p>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td class="tg-yw4l" colspan="1" style="line-height: 1.5;" id="identificacaoClienteRecibo" >&nbsp;&nbsp;<b>Cliente:</b> André Luiz da Silva (Atacadista)<br>&nbsp;&nbsp;<b>RG:</b> 3234544323<br>&nbsp;&nbsp;<b>Telefone:</b> 98784532<br></td>
                                       <td class="tg-yw4l" colspan="3" style="line-height: 1.5;" id="identificacaoVendaRecibo">&nbsp;&nbsp;<b>Código da venda:</b> 33234<br>&nbsp;&nbsp;<b>Data:</b> 11/11/2017<br>&nbsp;&nbsp;<b>Vendedor:</b> Luiz Silva<br></td>
                                    </tr>
                                    <tr bgcolor="#F5F5F5">
                                       <td class="tg-yw4l" width="50%">
                                          <p style="margin: 5px; text-align:center; font-weight: bold;">Descrição Produto</p>
                                       </td>
                                       <td class="tg-yw4l" width="16%">
                                          <center><b>Quantidade</b></center>
                                       </td>
                                       <td class="tg-yw4l" width="18%">
                                          <center><b>Valor Unitário</b></center>
                                       </td>
                                       <td class="tg-yw4l" width="18%">
                                          <center><b>Valor Total</b></center>
                                       </td>
                                    </tr>
                                 </tfixo1>
                                 <div id="tbodyRecibo">
                                    <!--
                                       <tr>
                                         <td><p style="font-size: 11px; margin: 4px;">BERMUDA COLORIDA TOP</p></td>
                                         <td><p style="font-size: 11px; margin: 4px; text-align:center;">2</p></td>
                                         <td><p style="font-size: 11px; margin: 4px; text-align:left;">R$ 15,00</p></td>
                                         <td><p style="font-size: 11px; margin: 4px; text-align:left;">R$ 30,00</p></td>
                                       </tr>  -->
                                 </div>
                                 <tr bgcolor="#DCDCDC" id="resumoPagamentoRecibo">
                                    <td colspan="4">
                                       <p style="margin: 5px; text-align:center; font-weight: bold;">Resumo Pagamento</p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td colspan="3">
                                       <p style="font-size: 11px; margin: 4px;">TOTAL EM PRODUTOS</p>
                                    </td>
                                    <td id="totalProdutosRecibo">
                                       <p style="font-size: 11px; margin: 4px; text-align:left;">R$ 30,00</p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td colspan="3">
                                       <p style="font-size: 11px; margin: 4px;">TOTAL DA VENDA</p>
                                    </td>
                                    <td id="totalVenda">
                                       <p style="font-size: 11px; margin: 4px; text-align:left;">R$ 30,00</p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td colspan="3">
                                       <p style="font-size: 11px; margin: 4px;">DESCONTO NA VENDA</p>
                                    </td>
                                    <td id="totalCreditoRecibo">
                                       <p style="font-size: 11px; margin: 4px; text-align:left;">R$ 30,00</p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td colspan="3">
                                       <p style="font-size: 11px; margin: 4px;">FRETE/CORREIOS/MOTOBOY/OUTROS</p>
                                    </td>
                                    <td id="totalDeslocamentoRecibo">
                                       <p style="font-size: 11px; margin: 4px; text-align:left;">R$ 40,00</p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td colspan="3">
                                        <p style="font-size: 11px; margin: 4px;">DESCONTO NO PAGAMENTO</p>
                                    </td>
                                    <td id="totalTaxasRecibo">
                                        <p style="font-size: 11px; margin: 4px; text-align:left;">R$ 30,00</p>
                                    </td>
                                 </tr>                    
                                 <tr bgcolor="#DCDCDC">
                                    <td colspan="4">
                                       <p style="margin: 5px; text-align:center; font-weight: bold;">Formas de Pagamento</p>
                                    </td>
                                 </tr>
                                 <tr bgcolor="#DCDCDC" id="observacoesRecibo">
                                    <td colspan="4">
                                       <p style="margin: 5px; text-align:center; font-weight: bold;">Observações</p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td class="tg-yw4l" id="descricao_nota" colspan="4" style="font-size: 10.5px; line-height: 15px; padding: 5px;">
                                     
                                    </td>
                                 </tr>
                                 <tr bgcolor="#DCDCDC">
                                    <td colspan="4" style="font-size: 10.5px; line-height: 15px; margin: 10">
                                        <p style="margin: 5px; text-align:center; font-weight: bold;">
                                            NÃO TROCAMOS PEÇAS SEM ESTA NOTA.
                                            <br />SÁBADO: TROCA DE NO MÁXIMO 5 PEÇAS
                                        </p>
                                    </td>
                                 </tr>
                                 <tr bgcolor="#F5F5F5">
                                    <td class="tg-yw4l" colspan="4" style="font-size: 11px;">
                                       <center>
                                       <b id="rodape">
                                       </b>
                                       </center>
                                    </td>
                                 </tr>
                                 <tfixo3>
                              </table>
                           </center>
                        </div>
                        <br />
                     </div>
                   </div>
                  </div>
                </div>