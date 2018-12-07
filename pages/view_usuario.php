<div id="wrapper">
    <link rel="stylesheet" type="text/css" href="css/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="css/generator-base.css">
    <link rel="stylesheet" type="text/css" href="css/editor.dataTables.min.css">
    <link rel="stylesheet" href="css/dialogo.css">

    <script type="text/javascript" charset="utf-8" src="js/datatables.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="js/dataTables.editor.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="js/table.usuario.js"></script>    
    <script type="text/javascript" charset="utf-8" src="js/dialogo.js"></script> 

    
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Gestão de Usuários</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
						<div class="panel-body">                            
							<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="usuario" width="100%">
								<thead>
									<tr>
									    <th width = "7%">ID</th>
										<th width="10%">Loja</th>
										<th>Login</th>
										<th>Perfil</th>
                                        <th width="15%">
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
                                        <th width="5%"><i class="fa fa-key" aria-hidden="true"></i></th>
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
<!-- /#wrapper -->
</div>


