<div id="wrapper">

    <link rel="stylesheet" type="text/css" href="css/datatables.min.css">
	<link rel="stylesheet" type="text/css" href="css/generator-base.css">
	<link rel="stylesheet" type="text/css" href="css/editor.dataTables.min.css">
	<link rel="stylesheet" href="css/dialogo.css">

	<script type="text/javascript" charset="utf-8" src="js/datatables.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="js/dataTables.editor.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="js/table.produtos.js"></script>
	<script type="text/javascript" charset="utf-8" src="js/dialogo.js"></script> 
	
	
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Sessão de Produtos</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">					
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="produtos" width="100%">
							<thead>
								<tr>
									<th>Loja</th>
									<th>Descrição</th>
									<th>Tipo</th>
									<th>Preço Varejo</th>
									<th>Preço Atacado</th>
									<th> 
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

	<div class="modal fade" id="produtoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
			<h4 class="modal-title" id="myModalLabel">Cadastrar produto</h4>
		  </div>
		  
		  <form class="form-horizontal" action="/action_page.php">
		  <div class="modal-body">
		  
			  <div class="form-group">
				<label class="control-label col-sm-5" for="descricao">Descrição:</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="descricao" placeholder="" required>
				</div>
			  </div>

			  <div class="form-group">
				<label class="control-label col-sm-5" for="loja">Loja:</label>
				<div class="col-sm-7">			  
				  <select name="loja" id="loja" required>
					<option value=""></option>
					<option value="masc">Masculino</option>
					<option value="fem">Feminino</option>
				  </select>
				</div>
			  </div> 			  

			  <div class="form-group">
				<label class="control-label col-sm-5" for="tipo">Tipo:</label>
				<div class="col-sm-7">			  
				  <select name="tipo" id="tipo" required>
					<option value=""></option>
					<option value="etc1">Etc 1</option>
					<option value="etc2">Etc 2</option>
				  </select>
				</div>
			  </div> 			  

			  <div class="form-group">
				<label class="control-label col-sm-5" for="modelo">Modelo:</label>
				<div class="col-sm-7">			  
				  <select name="modelo" id="modelo">
					<option value=""></option>
					<option value="etc1">Etc 1</option>
					<option value="etc2">Etc 2</option>
				  </select>
				</div>
			  </div> 			  
			  
			  <div class="form-group">
				<label class="control-label col-sm-5" for="preco_varejo">Preço de varejo:</label>
				<div class="col-sm-7">
				  <input type="number" class="form-control" id="preco_varejo" placeholder="0.00" required>
				</div>
			  </div>			  

			  <div class="form-group">
				<label class="control-label col-sm-5" for="preco_atacado">Preço de atacado:</label>
				<div class="col-sm-7">
				  <input type="number" class="form-control" id="preco_atacado" placeholder="0.00" required>
				</div>
			  </div>			

			  <div class="form-group">
				<label class="control-label col-sm-5" for="preco_custo">Preço de custo:</label>
				<div class="col-sm-7">
				  <input type="number" class="form-control" id="preco_custo" placeholder="0.00">
				</div>
			  </div>					  
			  
			  <div class="form-group">
				<label class="control-label col-sm-5" for="parametro_falta">Parâmetro alerta de falta:</label>
				<div class="col-sm-7">
				  <input type="number" class="form-control" id="parametro_falta" placeholder="" required>
				</div>
			  </div>					  
			  
			  <div class="form-group">
				<label class="control-label col-sm-5" for="parametro_excesso">Parâmetro alerta de excesso:</label>
				<div class="col-sm-7">
				  <input type="number" class="form-control" id="parametro_excesso" placeholder="" required>
				</div>
			  </div>							  
			  
			  <div class="form-group">
				<label class="control-label col-sm-5" for="peso">Peso (em gramas):</label>
				<div class="col-sm-7">
				  <input type="number" class="form-control" id="peso" placeholder="">
				</div>
			  </div>
			  
			  <div class="form-group">
				<label class="control-label col-sm-5" for="quantidade">Quantidade em estoque:</label>
				<div class="col-sm-7">
				  <input type="number" class="form-control" id="quantidade" placeholder="" required>
				</div>
			  </div>			  
			  
		  </div>
		  
		  <div class="modal-footer">
			<button id="btAtualizarValor" type="submit" class="btn btn-primary" data-dismiss="modal">Cadastrar</button>
		  </div>
		  
		  </form>
		  
		</div>
	  </div>
	</div>

