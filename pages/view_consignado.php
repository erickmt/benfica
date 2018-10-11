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
         <div class="col-md-12">
            <h1 class="page-header">Relatório de Vendas Consignadas</h1>
         </div>
      </div>
            <div class="panel panel-default">
               <div class="panel-heading">
                  Informe os critérios de referência para geração do relatório:
               </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <form role="form" id="gerarRelatorioConsignado" method="POST">
                      <!-- /.row -->
                      <div class="row">
                          <div class="col-md-3">
                            <div class="form-group">
                                <label>Cliente:</label>
                                <input type="text" name="cliente" id="cliente" class="form-control" required >
                            </div>
                          </div>
                          <div class="col-md-2">
                            <div class="form-group">
                                <label>Tipo Produto:</label>
                                <select class="form-control" style="width: 100%" name="tipoProdutoRelatorio" id="tipoProdutoRelatorio">
                                  <option value=0>TODOS</option>
                                    <?php foreach ($listarTipoProduto as $key => $value): ?>
                                      <option value="<?php echo $value['id_numero_produto']; ?>"> <?php echo $value['descricao']; ?> </option>
                                    <?php endforeach; ?>
                               </select>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="form-group " >
                                <label>Nome Produto:</label>
                                <select id="nomeProduto" style="width: 100%"  name="nomeProduto" class="form-control js-example-basic-multiple" multiple="multiple">
                                  <option value='' ></option>
                                </select>
                            </div>
                          </div>
                          <div class="col-md-2">
                            <div class="form-group">
                                <label>Loja:</label>
                                <select class="form-control" name="lojaBusca" id="lojaBusca">
                                  <?php foreach ($listarLojas as $key => $value): ?>
                                   <option value="<?php echo $value['id_loja']; ?>"> <?php  echo $value['descricao']; ?> </option>
                                  <?php endforeach; ?>
                               </select>
                            </div>
                          </div>
                          <div class="col-md-2">
                            <div class="form-group">
                                <label>Devolvidas:</label>
                                <select class="form-control" name="devolvidas" id="devolvidas">
                                  <option value="0" > Todas </option>
                                  <option value="1"> Sim </option>
                                  <option value="2" selected> Não </option>
                               </select>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4"></div>
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
                        </div>
                        <div class="row">
                          <div class="col-md-5"></div>
                          <div class="col-md-2">
                              <button type="submit" id="botaoGerarRelatorio" class="btn btn-default btn-block">
                              <i class="fa fa-search"></i>
                              Gerar Relatório
                              </button>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <br>
                          <div class="alert alert-danger" role="alert" id="ErroGerarRelatorio" style="display: none;">
                              <strong>Erro: </strong> Não foi localizada nenhuma peça para a pesquisa realizada.
                          </div>
                        </div>
                    </form>
                </div>
                </div>
                <div class="panel panel-default" id="sessaoRelatorio" style="display: none;" >                    
                  <div id="sessaoImpressaoRelatorio">
                    
                    <div class="panel-heading">
                        <h4 style="text-align: center" id="tituloRelMes"><b>Relatório de vendas consignadas</b></h4>
                        <p style="text-align:center" id="identificacaoCriterioRelatorio" >Período de Referência: 23/12/2017 até 23/01/2017</p>
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
                          <div class="col-md-12">
                              <!--  Essa div é o relatório de cima  --> 
                              <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="tabelaVendasConsignadas">
                                    <thead>
                                      <tr>
                                          <th colspan = "11" >
                                            <h4>
                                                <b>
                                                  <center>Peças e Vendas Consignadas</center>
                                                </b>
                                            </h4>
                                          </th>
                                      </tr>
                                      <tr>
                                          <th  width="6%" style="text-align:center">Loja</th>
                                          <th  width="6%" style="text-align:center">Data</th>
                                          <th  width="5%" style="text-align:center">Venda</th>
                                          <th  width="22%" style="text-align:center">Produto</th>
                                          <th  width="5%" style="text-align:center">Vendido</th>
                                          <th  width="5%" style="text-align:center">Devolvido</th>
                                          <th  width="8%" style="text-align:center">Valor</th>
                                          <th  width="8%" style="text-align:center">Total</th>
                                          <th  width="8%" style="text-align:center">Restante</th>
                                          <th  width="22%" style="text-align:center">Cliente</th>
                                          <th  width="5%" style="text-align:center">Devolver</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                              </div>
                          </div>
                        </div>
                      <!-- /.panel-body -->
                    </div>
                </div>
              </div>
         </div>
      </div>
   </div>
</div>