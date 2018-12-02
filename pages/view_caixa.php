<!-- Painel principal da venda  -->
<div id="wrapper">
   <div id="page-wrapper">
      <div class="row">
         <div class="col-md-12">
            <h1 class="page-header">Movimentação do caixa</h1>
         </div>
      </div>
      <div class="panel panel-default">
         <div class="panel-heading">
            Realizar entrada ou saída de valores
         </div>
         <div class="panel-body" >
            <!-- /.panel-heading -->
            <div class="row">
               <div class="form-group">
                  <div class="container">
                     <?php if ($_SESSION['usuario']['id_loja'] == 0): ?>
                        <div class="row">
                           <div class="col-sm-5 col-xs-3"></div>
                           <div class="col-sm-2 col-xs-6" >
                              <div class="form-group">
                                 <label>Loja:</label>
                                 <select class="form-control" name="lojaBusca" id="lojaBusca">
                                    <option value="0"> </option>
                                    <?php foreach ($listarLojas as $key => $value): ?>
                                    <option value="<?php echo $value['id_loja']; ?>"> <?php echo $value['descricao']; ?> </option>
                                    <?php endforeach; ?>
                                 </select>
                              </div>
                           </div>
                           <div class="col-sm-5"></div>
                        </div>
                     <?php endif; ?>
                     <br />
                     <div class="col-md-6">
                        <form name="cxEtr" id="cxEtr">
                           <div class="input-group">                                
                              <span class="input-group-btn">
                              <button class="btn btn-success" onclick="inserirValorCaixa(0,document.cxEtr.descEntrada.value)" type="button">Entrada</button>
                              </span>
                              <input type="text" name="descEntrada" class="form-control" placeholder="Descrição / Motivo" required>                                
                           </div>
                        </form>
                     </div>
                     <div class="col-md-6">
                        <form name="cxSd" id="cxSd">
                           <div class="input-group">
                              <input type="text" class="form-control" name="descSaida" placeholder="Descrição / Motivo" required>
                              <span class="input-group-btn">
                              <button class="btn btn-danger" onclick="inserirValorCaixa(1,document.cxSd.descSaida.value)" type="button">Saída</button>
                              </span>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <br />
      <?php if ($_SESSION['usuario']['perfil'] == 'A'): ?>
      <!-- Barra de progresso da venda -->
      <div class="panel panel-default">
         <div class="panel-heading">
            Relatório de movimentação
         </div>
         <!-- /.panel-heading -->
         <div class="panel-body" >
            <form role="form" id="gerarRelatorioCaixa" method="POST">
               <!-- /.row -->
               <div class="row">
                  <div class="col-md-3">
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label>Data Inicial:</label>
                        <br><input type="date" name="dataInicial" class="form-control" required >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label>Data Final:</label>
                        <br><input type="date" name="dataFinal" class="form-control" required >
                     </div>
                  </div>
                  <div class="col-lg-3">
                     <div class="form-group " >
                        <label>Forma de Pagamento:</label>
                        <select id="idPagamentoRelatorio" style="width: 100%"  name="idPagamentoRelatorio" class="form-control js-example-basic-multiple" multiple="multiple">
                           <option value='' ></option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-1">
                  </div>
                  <?php if ($_SESSION['usuario']['id_loja'] == '0'): ?>
                  <div class="col-md-12">
                     <div class="col-md-5"></div>
                     <div class="col-md-2">
                        <div class="form-group">
                           <select name="lojaBusca" id="lojaBusca" class="form-control">
                              <?php foreach ($listarLojas as $key => $value): ?>
                              <option value="<?php echo $value['id_loja']; ?>"> <?php echo $value['descricao']; ?> </option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                     </div>
                  </div>
                  <?php endif; ?>
                  <div class="col-md-12">
                     <div class="col-md-5"></div>
                     <div class="col-md-2">
                        <button type="submit" id="botaoGerarRelatorioCaixa" class="btn btn-default form-control">
                        <i class="fa fa-search"></i>
                        Gerar Relatório
                        </button>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <br>
                     <div class="alert alert-danger" role="alert" id="ErroGerarRelatorioFinanceiro" style="display: none;">
                        <strong>Erro: </strong> Não foi localizada nenhum valor para a pesquisa realizada.
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
      <div class="panel panel-default" id="sessaoRelatorioFinanceiro" style="display: none;" >
         <div class="iconeFixo">
            <a href="javascript:void(0)" onclick="printDivRelatorioComissao();" ><img src="library/impressora-2.png" alt="Imprimir Recibo" height="50" width="50"></a>
         </div>
         <div id="sessaoImpressaoRelatorio">
            <div class="panel-heading">
               <h4 style="text-align: center"><b>Relatório de Caixa (Apenas movimentação de dinheiro)</b></h4>
               <p style="text-align:center" id="identificacaoCriterioRelatorioFinanceiro" >Período de Referência: 23/12/2017 até 23/01/2017</p>
               <center>
                  <button class="btn btn-success" id="gerarExcel" >
                  <i class="fa fa-file-excel-o"></i>
                  Gerar Excel
                  </button> 
               </center>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-2"></div>
                  <div class="col-md-8">
                     <!--  Essa div é o relatório de cima --> 
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="tabelaMovimentacaoCaixa">
                           <thead>
                              <tr>
                                 <th colspan = "3" >
                                    <h4 id="tituloRel">
                                       <b>
                                          <center>Valores de Movimentação de Caixa</center>
                                       </b>
                                    </h4>
                                 </th>
                              </tr>
                              <tr>
                                 <th  width="25%" style="text-align:center">Data</th>
                                 <th  width="25%" style="text-align:center">Descrição</th>
                                 <th  width="50%" style="text-align:center">Valor</th>
                              </tr>
                           </thead>
                           <tbody>
                           </tbody>
                        </table>
                     </div>
                     <hr>
                  </div>
                  <div class="col-md-2"></div>
               </div>
            </div>
         </div>
         <!-- /.panel-body -->
      </div>
      <?php endif; ?>
   </div>
</div>