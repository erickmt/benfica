<div id="wrapper">

    <link rel="stylesheet" type="text/css" href="css/datatables.min.css">
	<link rel="stylesheet" type="text/css" href="css/generator-base.css">
	<link rel="stylesheet" type="text/css" href="css/editor.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
    <link rel="stylesheet" href="css/dialogo.css">


	<script type="text/javascript" charset="utf-8" src="js/datatables.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="js/dataTables.editor.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="js/table.cliente.js"></script>
    <script type="text/javascript" charset="utf-8" src="js/dialogo.js"></script> 


	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Sessão Clientes</h1>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="cliente" width="100%">
							<thead>
								<tr>
									<th width="8%">ID</th>
									<th width="32%">Nome</th>
									<th width="10%">Perfil</th>
									<th width="10%">Telefone</th>
									<th width="12%">CPF</th>
									<th width="10%">Última Compra</th>
									<th width="8%"> Inativar
										<!--<div class="dropdown">
										  <button type="button" class="" data-toggle="dropdown">Situação
										  <span class="caret"></span></button>
										  <ul class="dropdown-menu">
										    <li><a href="#">Ativo</a></li>
										    <li><a href="#">Desativado</a></li>
										    <li><a href="#" class="todos">Todos</a></li>
										  </ul>
										</div>-->
									</th>
									<th width="5%"> <i class="fa fa-eye" aria-hidden="true"></i></th>
								</tr>
							</thead>
						</table>
					</div>

					<!-- Menu Modal -->
					<div id="historico" class="w3-modal">
					  <div class="w3-modal-content w3-animate-zoom">
					    <div class="w3-container w3-light-grey w3-display-container">
					      <span onclick="document.getElementById('historico').style.display='none'" class="w3-button w3-display-topright w3-large"><i class="fa fa-times-circle-o fa-2x" aria-hidden="true"></i></span>
					      <center><h1>Histórico de compras do cliente</h1></center>
					    </div>
						<div class="panel-body">
							<table cellpadding="0" cellspacing="0" border="0" class="display" id="venda" width="100%">
								<thead>
									<tr>
										<th>ID - Cliente</th>
										<th>Loja</th>
										<th>RG</th>
										<th>Vendedor</th>
										<th>Data da Compra</th>
										<th>Valor Total Pago</th>
										<th>Valor Líquido</th>
									</tr>
								</thead>
							</table>
						</div>
					  </div>
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

