

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
                    <h1 class="page-header">Gerenciamento e Vendas  <a>  <?php  
                            if($_SESSION['usuario']['id_loja'] != 0){
                                echo $_SESSION['usuario']['lojaDescricao'];
                            }
                        ?></a></h1>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-group fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div><h3>Clientes</h3></div>
                                </div>
                            </div>
                        </div>
                        <a href="clientes.php">
                            <div class="panel-footer">
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tag fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div><h3>Produtos</h3></div>
                                </div>
                            </div>
                        </div>
                        <a href="produtos.php">
                            <div class="panel-footer">
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
				<div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shopping-cart fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div><h3> Vendas</h3></div>
                                </div>
                            </div>
                        </div>
                        <a href="realizar-venda.php">
                            <div class="panel-footer">
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <?php if ($_SESSION['usuario']['perfil'] == 'A'): ?>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-files-o fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div><h3>Relatórios</h3></div>
                                </div>
                            </div>
                        </div>
                        <a href="relatorio-financeiro.php">
                            <div class="panel-footer">
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endif; ?>       

            </div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

