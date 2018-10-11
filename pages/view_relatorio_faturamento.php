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
            <h1 class="page-header">Relatório de Faturamentos</h1>
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
               <div class="panel-body" class="col-lg-6">
                  <!-- gerarRelatorioFaturamento gerarRelatorioFinanceiro -->
                  <!-- /.row -->
                  <div class="row">
                     <form role="form" id="gerarRelatorioFaturamento" method="POST">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-5"></div>
                                <div class="col-lg-4">
                                    <h4><strong>Mensal</strong></h4>
                                </div>
                            </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label>Data Inicial:</label>
                                 <br><input type="date" name="dataInicial" required >
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label>Data Final:</label>
                                 <br><input type="date" name="dataFinal" required >
                              </div>
                           </div>
                           <div class="col-lg-4">
                                <br/>
                                <select class="form-control" name="lojaBusca" id="lojaBusca">
                                    <?php foreach ($listarLojas as $key => $value): ?>
                                        <option value="<?php echo $value['id_loja']; ?>"> <?php echo $value['descricao']; ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                           <div class="col-lg-12">
                                <div class="col-lg-4"></div>
                                <div class="col-lg-4">
                                    <button type="submit" id="botaoGerarRelatorioFinanceiro" class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                    Gerar Relatório
                                    </button>
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
                     <div class="col-md-1"></div>
                     <form method="POST" id="gerarRelatorioFaturamentoAno">
                        <div class="col-md-3">
                            <center>
                            <h4><strong>Anual</strong></h4>
                            </center>
                            <center>
                                <br />
                                <div class="input-group">                                  
                                    <input type="number" class="form-control" name="ano" id="ano" placeholder="Ano" required>
                                    <span class="input-group-btn">
                                    <input class="btn btn-primary" type="submit">Buscar</input>
                                    </span>
                                </div>
                            </center>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <center>
                                <h4><strong>Loja</strong></h4>
                                </center>
                                <br />
                                <select class="form-control" name="lojaBusca2" id="lojaBusca2">
                                    <?php foreach ($listarLojas as $key => $value): ?>
                                        <option value="<?php echo $value['id_loja']; ?>"> <?php echo $value['descricao']; ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>  
                        </div>
                    </form>
                  </div>
               </div>
            </div>
            <div class="panel panel-default" id="sessaoRelatorioMensal" style="display: none;" >
               <div id="sessaoImpressaoRelatorio">
                  <div class="panel-heading">
                     <h4 style="text-align: center" id="tituloRelMes"><b>Relatório de Faturamentos (vendas ativas)</b></h4>
                     <p style="text-align:center" id="identificacaoCriterioRelatorioFinanceiro" >Período de Referência: 23/12/2017 até 23/01/2017</p>
                     <center>
                            <button class="btn btn-success" id="gerarExcelMensal" >
                                <i class="fa fa-file-excel-o"></i>
                                Gerar Excel
                            </button>
                     </center>
                  </div>
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                     <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                           <div class="table-responsive">
                              <!--id="tabelaValorRecebido"-->
                              <table class="table table-striped table-bordered table-hover" id="tabelaValorMensal">
                                 <thead>
                                    <tr>
                                       <th colspan = "7" >
                                          <h4>
                                             <b>
                                                <center>Faturamentos Mensais</center>
                                             </b>
                                          </h4>
                                       </th>
                                    </tr>
                                    <tr>
                                       <th  width="13%" style="text-align:center">Mês</th>
                                       <th  width="13%" style="text-align:center">Valor em desconto</th>
                                       <th  width="13%" style="text-align:center">Valor em comissão</th>
                                       <th  width="13%" style="text-align:center">Valor em frete (etc)</th>
                                       <th  width="13%" style="text-align:center">Valor líquido</th>
                                       <th  width="15%" style="text-align:center">Valor custo</th>
                                       <th  width="20%" style="text-align:center">Valor Total</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                           <hr>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="panel panel-default" id="sessaoRelatorioTrimestral" style="display: none;" >
               <div id="sessaoImpressaoRelatorio">
                  <div class="panel-heading">
                     <h4 style="text-align: center"><b>Relatório de Faturamentos (vendas ativas)</b></h4>
                        <center>
                            <button class="btn btn-success" id="gerarExcelAnual" >
                                <i class="fa fa-file-excel-o"></i>
                                Gerar Excel
                            </button>
                            <button class="btn btn-default" id="limpar" >
                                <i class="fa fa-eraser"></i>
                                Limpar tabela
                            </button>                    
                        </center>
                  </div>
                  <div class="panel-body">
                     <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-10">
                           <div class="table-responsive">
                              <!--id="tabelaValorRecebido"-->
                              <table class="table table-striped table-bordered table-hover" id="tabelaTrimestre">
                                 <thead>
                                    <tr>
                                       <th colspan = "8" >
                                          <h4>
                                             <b>
                                                <center>Faturamentos Mensais</center>
                                             </b>
                                          </h4>
                                       </th>
                                    </tr>
                                    <tr>
                                       <th  width="8%" style="text-align:center">Loja</th>
                                       <th  width="12%" style="text-align:center">Período</th>
                                       <th  width="12%" style="text-align:center">Valor em taxas</th>
                                       <th  width="12%" style="text-align:center">Valor em comissão</th>
                                       <th  width="12%" style="text-align:center">Valor em frete (etc)</th>
                                       <th  width="12%" style="text-align:center">Valor líquido</th>
                                       <th  width="14%" style="text-align:center">Valor custo</th>
                                       <th  width="18%" style="text-align:center">Valor Total</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                           <hr>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>