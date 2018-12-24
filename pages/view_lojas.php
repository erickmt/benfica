<div id="wrapper">
	<link rel="stylesheet" type="text/css" href="css/datatables.min.css">
	<link rel="stylesheet" type="text/css" href="css/generator-base.css">
	<link rel="stylesheet" type="text/css" href="css/editor.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="css/estilo.css">

	<script type="text/javascript" charset="utf-8" src="js/datatables.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="js/dataTables.editor.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="js/table.lojas.js"></script>

	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Gestão de Lojas</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">							
						<table cellpadding="0" cellspacing="0" border="0" class="display table table-striped table-bordered" id="lojas" style="width:100%" width="100%">
							<thead>
								<tr>
									<th width="5%">Id</th>	
									<th>Descrição</th>	
									<th>Telefone</th>		
								</tr>
							</thead>
						</table>
						<br />
						<div class="alert alert-danger" role="alert" id="ErroGerarRelatorio" style="display: none;"> </div>

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

	<!-- Modal -->
	<!-- <div class="modal fade bd-example-modal-lg" id="modalNotaLoja" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
			<h4 class="modal-title" id="labelNotaLoja"></h4>
		  </div>
		  
		  <div class="modal-body">

      	<h5>Contato da nota</h5>
				<textarea rows="4" style="width:50%" id="contatoNota"></textarea>
				
				<h5>Instruções da nota</h5>
				<textarea rows="10" style="width:100%" id="descricaoNota"></textarea>
				
				<button class="btn btn-button btn-primary" onclick="atualizarNota()" > Confirmar </button>
		  </div>
		  		  
		</div>
	  </div>
	</div> -->