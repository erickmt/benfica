<div id="wrapper">

    <link rel="stylesheet" type="text/css" href="css/datatables.min.css">
	<link rel="stylesheet" type="text/css" href="css/generator-base.css">
	<link rel="stylesheet" type="text/css" href="css/editor.dataTables.min.css">

	<script type="text/javascript" charset="utf-8" src="js/datatables.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="js/dataTables.editor.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="js/table.estoque.js"></script>	

	
	
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Estoque</h1>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
                    <!-- /.panel-heading -->
                    <div class="panel-body" id="painel_principal_venda">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li><a href="#passo01" data-toggle="tab">Estoque completo</a> </li>

                            <li><a href="#passo02" data-toggle="tab">Produtos em falta</a> </li>
                               
                            <li> <a href="#passo03" data-toggle="tab">Produtos em excesso</a> </li>
                        </ul>

                            <!-- Tab panes -->
                        <div class="tab-content">
                                <!-- PASSO 01 VENDA / IDENTIFICAÇÃO DE CLIENTE -->

                            <div class="tab-pane fade" id="passo01">
                               	<div class="panel-body">							
									<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="estoque" width="100%">
										<thead>
											<tr>
												<th>Código</th>
												<th>Descrição do Produto</th>
												<th>Tipo</th>
												<th>Modelo</th>
												<th>Quantidade em Estoque</th>
											</tr>
										</thead>
									</table>				
								</div>
								<!-- /.panel-body -->                                     
                            </div>

                                <!-- PASSO 02 VENDA / INCLUSÃO DE ITENS DA VENDA -->
                            <div class="tab-pane fade " id="passo02">
                                <div class="panel-body">							
									<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="estoque_minimo" width="100%">
										<thead>
											<tr>
												<th>Código</th>
												<th>Descrição do Produto</th>
												<th>Tipo</th>
												<th>Modelo</th>
												<th>Mínimo desejado</th>
												<th>Quantidade em Estoque</th>
											</tr>
										</thead>
									</table>				
								</div>
								<!-- /.panel-body -->          
                            </div>

                            <!-- PASSO 03 / DEFINIÇÃO DE MEIOS DE PAGAMENTO --> 
                            <div class="tab-pane fade" id="passo03">

	                            <div class="panel-body">							
										<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="estoque_maximo" width="100%">
											<thead>
												<tr>
													<th>Código</th>
													<th>Descrição do Produto</th>
													<th>Tipo</th>
													<th>Modelo</th>
													<th>Máximo desejado</th>
													<th>Quantidade em Estoque</th>
													</tr>
											</thead>
										</table>				
									</div>
									<!-- /.panel-body -->          
	                            </div>
                                
                            </div>
						<!-- /.tab --> 
                        </div>
                    <!-- /.panel-body -->    
                    </div>
                <!-- /.panel -->                                                  
				</div>
			<!-- /.col-lg-12 -->
			</div>
		<!-- /.row -->
		</div>
	<!-- /#page-wrapper -->
	</div>
<!-- /#wrapper -->
</div>

