<div id="wrapper">

    <link rel="stylesheet" type="text/css" href="css/datatables.min.css">
	<link rel="stylesheet" type="text/css" href="css/generator-base.css">
	<link rel="stylesheet" type="text/css" href="css/editor.dataTables.min.css">
	<link rel="stylesheet" href="css/dialogo.css">

	<script type="text/javascript" charset="utf-8" src="js/datatables.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="js/dataTables.editor.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="js/table.produtos_a.js"></script>
	<script type="text/javascript" charset="utf-8" src="js/dialogo.js"></script> 
	
	
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Sessão de Produtos</h1>
			</div>
		</div>
		<div class="row">
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">					
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="produtos" width="100%">
							<thead>
								<div class="row">
								    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>Produto:</label>
                                            <br><input type="text" id="descProduto" class="form-control" required >
                                        </div>                                            
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>Tipo:</label>
                                            <br><input type="text" id="tipoProduto" class="form-control" required >
                                        </div>                                            
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>NCM:</label>
                                            <br><input type="text" id="ncmProduto" class="form-control" required >
                                        </div>                                            
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>Varejo:</label>
                                            <br><input type="text" id="varejoProduto" class="form-control" required >
                                        </div>                                            
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>Atacado:</label>
                                            <br><input type="text" id="atacadoProduto" class="form-control" required >
                                        </div>                                            
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>Estoque:</label>
                                            <br><input type="text" id="estoqueProduto" class="form-control" required >
                                        </div>                                            
                                    </div>
								</div>
								<tr>
									<th width="5%">Loja</th>
									<th width="30%">Descrição</th>
									<th width="10%">Tipo</th>
									<th width="5%">NCM</th>
									<th width="10%">Custo</th>
									<th width="10%">Varejo</th>
									<th width="10%">Atacado</th>
									<th width="10%">Estoque</th>
									<th width="12%"> 
										<div class="dropdown">
										  <button type="button" class="" data-toggle="dropdown">Situação
										  <span class="caret"></span></button>
										  <ul class="dropdown-menu">
										    <li><a href="#">Ativo</a></li>
										    <li><a href="#">Desativado</a></li>
										    <li><a href="#" class="todos">Todos</a></li>
										  </ul>
										</div>
									</th>
								</tr>
							</thead>
						</table>
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

