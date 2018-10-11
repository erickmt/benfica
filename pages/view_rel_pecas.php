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
               <div class="panel-body" id="painel_principal_venda">
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs">
                     <li><a href="#passo01" data-toggle="tab">Por venda / cliente</a> </li>
                     <li><a href="#passo02" data-toggle="tab">Por peça</a> </li>
                  </ul>
                  <!-- Tab panes -->
                  <div class="tab-content">
                     <!-- PASSO 01 VENDA / IDENTIFICAÇÃO DE CLIENTE -->
                     <div class="tab-pane fade" id="passo01">
                        <div class="panel-body">
                           <form role="form" id="gerarRelatorioPecasCliente" method="POST">
                              <!-- /.row -->
                              <div class="row">
                                 <div class="col-lg-6">
                                    <div class="form-group " >
                                       <label>Nome Cliente:</label>
                                       <input id="nomeCliente" name="nomeCliente" style="width: 100%" class="form-control">
                                    </div>
                                 </div>
                                 <div class="col-lg-2">
                                    <div class="form-group">
                                       <label>Loja:</label>
                                       <select class="form-control" name="lojaBuscaCliente" id="lojaBuscaCliente">
                                          <?php foreach ($listarLojas as $key => $value): ?>
                                          <option value="<?php echo $value['id_loja']; ?>"> <?php echo $value['descricao']; ?> </option>
                                          <?php endforeach; ?>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-lg-2">
                                    <div class="form-group">
                                       <label>Data Inicial:</label>
                                       <br><input type="date" name="dataInicialCliente" class="form-control" required >
                                    </div>
                                 </div>
                                 <div class="col-lg-2">
                                    <div class="form-group">
                                       <label>Data Final:</label>
                                       <br><input type="date" name="dataFinalCliente" class="form-control" required >
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <div class="col-lg-5"></div>
                                    <div class="col-lg-2">
                                       <button type="submit" id="botaoGerarRelatorioPecaCliente" class="btn btn-default btn-block">
                                       <i class="fa fa-search"></i>
                                       Gerar Relatório
                                       </button>
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <br>
                                    <div class="alert alert-danger" role="alert" id="ErroGerarRelatorioPecaCliente" style="display: none;">
                                       <strong>Erro: </strong> Não foi localizada nenhuma peça ou venda para a pesquisa realizada.
                                    </div>
                                 </div>
                              </div>
                           </form>
                        </div>
                        <div class="panel panel-default" id="sessaoRelatorioPecaCliente" style="display: none;" >
                           <div >
                              <h4 style="text-align: center" id="tituloRelMesCliente"><b>Relatório de peças</b></h4>
                              <p style="text-align:center" id="identificacaoCriterioRelatorioPecaCliente" >Período de Referência: 23/12/2017 até 23/01/2017</p>
                              <center>
                                 <button class="btn btn-success" id="gerarExcelCliente" >
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
                                    <!--  Essa div é o relatório de cima e a adriane é linda <3  --> 
                                    <div class="table-responsive">
                                       <table class="table table-striped table-bordered table-hover" id="tabelaPecaCliente">
                                          <thead>
                                             <tr>
                                                <th colspan = "5" >
                                                   <h4>
                                                      <b>
                                                         <center>Peças vendidas por cliente</center>
                                                      </b>
                                                   </h4>
                                                </th>
                                             </tr>
                                             <tr>
                                                <th  width="10%" style="text-align:center">Perfil</th>
                                                <th  width="10%" style="text-align:center">Id</th>
                                                <th  width="75%" style="text-align:center">Nome</th>
                                                <th  width="10%" style="text-align:center">Quantidade</th>
                                                <th  width="5%" style="text-align:center">Visualizar</th>
                                             </tr>
                                          </thead>
                                          <tbody>
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

                     <div class="tab-pane fade " id="passo02">
                        <div class="panel-body">
                           <form role="form" id="gerarRelatorioPecas" method="POST">
                              <!-- /.row -->
                              <div class="row">
                                 <div class="col-lg-2">
                                    <div class="form-group">
                                       <label>Loja:</label>
                                       <select class="form-control" name="lojaBuscaPeca" id="lojaBuscaPeca">
                                          <?php foreach ($listarLojas as $key => $value): ?>
                                            <option value="<?php echo $value['id_loja']; ?>"> <?php echo $value['descricao']; ?> </option>
                                          <?php endforeach; ?>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-lg-3">
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
                                 <div class="col-lg-3">
                                    <div class="form-group " >
                                       <label>Nome Produto:</label>
                                       <select id="nomeProduto" style="width: 100%"  name="nomeProduto" class="form-control js-example-basic-multiple" multiple="multiple">
                                          <option value='' ></option>
                                       </select>
                                    </div>
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
                                 <div class="col-lg-12">
                                    <div class="col-lg-4"></div>
                                    <div class="col-lg-2">
                                       <div class="form-group">
                                          <select class="form-control" name="tipoRelatorio" id="tipoRelatorio">
                                             <option value="0"> Resumido </option>
                                             <option value="1"> Detalhado </option>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-lg-2">
                                       <button type="submit" id="botaoGerarRelatorioFinanceiroVenda" class="btn btn-default btn-block">
                                       <i class="fa fa-search"></i>
                                       Gerar Relatório
                                       </button>
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <br>
                                    <div class="alert alert-danger" role="alert" id="ErroGerarRelatorioFinanceiro" style="display: none;">
                                       <strong>Erro: </strong> Não foi localizada nenhuma peça para a pesquisa realizada.
                                    </div>
                                 </div>
                              </div>
                           </form>
                        </div>
                        <div class="panel panel-default" id="sessaoRelatorioFinanceiro" style="display: none;" >
                           
                           <div class="iconeFixo">
                              <a href="javascript:void(0)" onclick="printDivRelatorioComissao();" ><img src="library/impressora-2.png" alt="Imprimir Recibo" height="50" width="50"></a>
                           </div>
                           <div id="sessaoImpressaoRelatorio">
                              
                              <div class="panel-heading">
                                 <h4 style="text-align: center" id="tituloRelMes"><b>Relatório de peças</b></h4>
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
                                    <div class="col-lg-2"></div>
                                    <div class="col-lg-8">
                                       <!--  Essa div é o relatório de cima e a adriane é linda <3  --> 
                                       <div class="table-responsive">
                                          <table class="table table-striped table-bordered table-hover" id="tabelaValorRecebidoFormaPagamento">
                                             <thead>
                                                <tr>
                                                   <th colspan = "4" >
                                                      <h4>
                                                         <b>
                                                            <center>Valores de Peças Vendidas</center>
                                                         </b>
                                                      </h4>
                                                   </th>
                                                </tr>
                                                <tr>
                                                   <th  width="35%" style="text-align:center">Peça</th>
                                                   <th  width="35%" style="text-align:center">Tipo</th>
                                                   <th  width="15%" style="text-align:center">Quantidade Vendida</th>
                                                   <th  width="15%" style="text-align:center">Quantidade em Estoque</th>
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
                        </div>
                        <!-- /.panel-body -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

	<!-- Modal -->
	<div class="modal fade" id="modalPecasCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
			<h4 class="modal-title" id="myModalLabel">Produtos adquiridos</h4>
		  </div>
		  
		  <div class="modal-body">
		  
			<table class="table table-striped table-bordered table-hover" id="tablePecasCliente">
            <thead>  
              <tr>
                <th><center>Produto</center></th>
                <th><center>Quantidade</center></th>
              </tr>
            <tbody>
               <tr>
                <td></td>
                <td></td>
              </tr>
           </tbody>
			</table>  
			  
		  </div>
		  		  
		</div>
	  </div>
	</div>