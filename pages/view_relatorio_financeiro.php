


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
                    <h1 class="page-header">Relatório Financeiro</h1>
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



                                   <form role="form" id="gerarRelatorioFinanceiro" method="POST">

                                    <!-- /.row -->
                                    <div class="row">


                                        <div class="col-lg-3">
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>Data Inicial:</label>
                                                <br><input type="date" name="dataInicial" class="form-control" required >
                                            </div>                                            
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>Data Final:</label>
                                                <br><input type="date" name="dataFinal" class="form-control" required >
                                            </div>                                            
                                        </div>

                                        <div class="col-md-2">
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

                                        <div class="col-lg-3">
                                        </div>

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

                                            <div class="alert alert-danger" role="alert" id="ErroGerarRelatorioFinanceiro" style="display: none;">
                                                <strong>Erro: </strong> Não foi localizada nenhuma venda para a pesquisa realizada.
                                            </div>                                    

                                        </div>
                                    </div>
                                    </form> 

                                    </div></div>

                    <div class="panel panel-default" id="sessaoRelatorioFinanceiro" style="display: none;" >

                    <div class="iconeFixo">
                        <a href="javascript:void(0)" onclick="printDivRelatorioComissao();" ><img src="library/impressora-2.png" alt="Imprimir Recibo" height="50" width="50"></a>
                    </div>
                    <div id="sessaoImpressaoRelatorio">
                        
                        <div class="panel-heading">
                            <h4 style="text-align: center" id="tituloRel"><b>Relatório Financeiro (vendas ativas)</b> 
                            </h4>
                            <center>
                                <button class="btn btn-success" id="gerarExcel" >
                                    <i class="fa fa-file-excel-o"></i>
                                    Gerar Excel
                                </button> 
                            </center>
                            <br />
                            <p style="text-align:center" id="identificacaoCriterioRelatorioFinanceiro" >Período de Referência: 23/12/2017 até 23/01/2017</p>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">

 

            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="tabelaValorRecebidoFormaPagamento">
                                    <thead>
                                        <tr>
                                            <th colspan = "2" ><h4><b><center>Valores Totais Recebidos</center></b></h4></th>
                                        </tr>                                        
                                        <tr>
                                            <th  width="70%" style="text-align:center">Forma de Pagamento</th>
                                            <th  width="30%" style="text-align:center">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>16565444</td>
                                            <td>09/12/2019</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p><i>(*) As taxas por forma de pagamento estão incluídas nos valores apresentados acima.</i></p>
                            </div>

                            <hr>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="tabelaValorRecebido">
                                    <thead>
                                        <tr>
                                            <th colspan = "2" ><h4><b><center>Valores Recebidos Tratados</center></b></h4></th>
                                        </tr>                                        
                                    </thead>                                    
                                    <tbody>
                                        <tr>
                                            <td width="70%">Valor total em desconto:</td>
                                            <td id="valorTotalTaxasRelatorioFinanceiro">R$ 50,00</td>
                                        </tr>

                                        <tr>
                                            <td width="70%" >Valor total em comissão:</td>
                                            <td id="valorTotalComissaoRelatorioFinanceiro">R$ 50,00</td>
                                        </tr>

                                        <tr>
                                            <td width="70%" >Valor total em frete, etc (outros):</td>
                                            <td id="valorTotalFreteRelatorioFinanceiro">R$ 50,00</td>
                                        </tr>

                                        <tr>
                                            <td width="70%" >Valor total de custo dos produtos:</td>
                                            <td id="valorTotalCustoRelatorioFinanceiro">R$ 50,00</td>
                                        </tr>                                                                     

                                        <tr>
                                            <td width="70%" >Valor total líquido das vendas:</td>
                                            <td id="valorTotalLiquidoRelatorioFinanceiro">R$ 50,00</td>
                                        </tr>                                                                                                                                          
                                    </tbody>
                                </table>
                            </div>                            

                </div>                
                <div class="col-lg-2"></div>                                
            </div>




                        </div>
                    </div>
                        <!-- /.panel-body -->
                    </div>