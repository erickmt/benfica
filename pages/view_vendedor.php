<div id="wrapper">

    <link rel="stylesheet" type="text/css" href="css/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="css/generator-base.css">
    <link rel="stylesheet" type="text/css" href="css/editor.dataTables.min.css">
    <link rel="stylesheet" href="css/dialogo.css">

    <script type="text/javascript" charset="utf-8" src="js/datatables.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="js/dataTables.editor.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="js/table.vendedor.js"></script>    
    <script type="text/javascript" charset="utf-8" src="js/dialogo.js"></script> 

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Gestão de Vendedores</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">                            
                        <table cellpadding="0" cellspacing="0" border="0" class="display table table-striped table-bordered" id="vendedor" width="100%">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Loja</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Comissão</th>
                                    <th>
                                        <div class="dropdown">
                                            <button class="btn btn btn-outline btn-default" type="button" data-toggle="dropdown">Situação
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
<!-- /#wrapper -->