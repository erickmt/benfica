


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
                    <h1 class="page-header">Relatório de Peças</h1>
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



                                   <form role="form" id="gerarRelatorioPecas" method="POST">

                                    <!-- /.row -->
                                    <div class="row">
									
										<div class="col-lg-8">
                                            <div class="form-group">
                                                <label>Tipo Produto:</label>
                                                 <select class="form-control" style="width: 100%" name="tipoProduto" id="tipoProduto">
                                                    <option value='' ></option>
                                                    <option value='3' >Blusa</option>
                                                    <option value='8' >Calca</option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-lg-4">
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>Data Inicial:</label>
                                                <br><input type="date" name="dataInicial" required >
                                            </div>                                            
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>Data Final:</label>
                                                <br><input type="date" name="dataFinal" required >
                                            </div>                                            
                                        </div>

                                        <div class="col-lg-4">
                                        </div>

                                        <div class="col-lg-12">

                                            <center>
                                            <button type="submit" id="botaoGerarRelatorioFinanceiroVenda" class="btn btn-default">
                                                <i class="fa fa-search"></i>
                                                Gerar Relatório
                                            </button></center>


                                        </div>

                                        <div class="col-lg-12">
                                            <br>

                                            <div class="alert alert-danger" role="alert" id="ErroGerarRelatorioFinanceiro" style="display: none;">
                                                <strong>Erro: </strong> Não foi localizada nenhuma venda para a pesquisa realizada.
                                            </div>                                    

                                        </div>
                                    </div>
                                    </form> </div></div>


                    <div class="panel panel-default" id="sessaoRelatorioFinanceiro" style="display: none;" >

                    <div class="iconeFixo">
                        <a href="javascript:void(0)" onclick="printDivRelatorioComissao();" ><img src="library/impressora-2.png" alt="Imprimir Recibo" height="50" width="50"></a>
                    </div>
                    <div id="sessaoImpressaoRelatorio">

                        <div class="panel-heading">
                            <h4 style="text-align: center"><b>Relatório de peças</b></h4>
                            <p style="text-align:center" id="identificacaoCriterioRelatorioFinanceiro" >Período de Referência: 23/12/2017 até 23/01/2017</p>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
 

            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8"> 
                            <!--  Essa div é o relatório de cima e a adriane é linda <3  --> 
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="tabelaValorRecebidoFormaPagamento">
                                    <thead>
                                        <tr>
                                            <th colspan = "2" ><h4><b><center>Valores de Peças Vendidas</center></b></h4></th>
                                        </tr>                                        
                                        <tr>
                                            <th  width="70%" style="text-align:center">Peças</th>
                                            <th  width="30%" style="text-align:center">Quantidade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>16565444</td>
                                            <td>09/12/2019</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <hr>                          

                </div>                
                <div class="col-lg-2"></div>                                
            </div>




                        </div>
                    </div>
                        <!-- /.panel-body -->
                    </div>