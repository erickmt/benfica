
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">

                        <!-- MENU PRINCIPAL -->
                        <li>
                            <a href="index.php"><i class="fa fa-home fa-fw"></i> Início</a>
                        </li>

                        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
                          <a id="menu_venda" class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponents" data-parent="#exampleAccordion">
                            <i class="fa fa-dashboard fa-fw"></i> <span class="nav-link-text">Vendas</span> <span class="fa arrow"></span>
                          </a>
                          <ul class="nav nav-second-level collapse" id="collapseComponents">
                            <li>
                                <a id="menu_venda" href="#">Realizar Venda</a>
                            </li>
                            <li>
                                <a id="menu_venda" href="#">Cancelar Venda</a>
                            </li>
                          </ul>
                        </li>

                        <li>
                            <a id="menu_venda" href="#"><i class="fa fa-file-text-o fa-fw"></i> Notas</a>
                        </li>
                        <li>
                            <a id="menu_venda" href="#"><i class="fa fa-group fa-fw"></i> Clientes</a>
                        </li>
                        <li>
                            <a id="menu_venda" href="#"><i class="fa fa-tag fa-fw"></i> Produtos</a>
                        </li>
                        <li>
                            <a id="menu_venda" href="#"><i class="fa fa-list-alt fa-fw"></i> Estoque</a>
                        </li>
                        <li>
                            <a id="menu_venda" href="#"><i class="fa fa-list fa-fw"></i> Consulta</a>
                        </li>
                        <li>
                            <a id="menu_venda" href="#"><i class="fa fa-money fa-fw"></i> Caixa</a>
                        </li>
                        
                        <?php if ($_SESSION['usuario']['perfil'] == 'A'): ?>
                            <li>                
                                <a id="menu_venda" href="#"><i class="fa fa-pie-chart fa-fw"></i> Vendas Consignadas </a>
                            </li>                        
                        <?php endif; ?>
                        <!--
                        <li>                
                            <a href="vendas_consignadas.php"><i class="fa fa-pie-chart fa-fw"></i> Vendas Consignadas </a>
                        </li>-->
                        <?php if ($_SESSION['usuario']['perfil'] == 'A'): ?>
                         <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Example Pages">
                          <a id="menu_venda" class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExamplePages" data-parent="#exampleAccordion">
                            <i class="fa fa-files-o fa-fw"></i> Relatórios<span class="fa arrow"></span></a>
                          <ul class="nav nav-second-level collapse" id="collapseExamplePages">
                            <li>
                               <a id="menu_venda" href="#">Comissões Por Vendedor</a>
                            </li>
                            <li>
                               <a id="menu_venda" href="#">Financeiro</a>
                            </li>
                            <li>
                               <a id="menu_venda" href="#">Faturamentos</a>
                            </li>
                            <li>
                               <a id="menu_venda" href="#">Extrato</a>
                            </li>
                            <li>
                               <a id="menu_venda" href="#">Análise Peças</a>
                            </li>                           
                          </ul>
                        </li>
                        <?php endif; ?>

                        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Menu Levels">
                          <a id="menu_venda" class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMulti" data-parent="#exampleAccordion">
                            <i class="fa fa-wrench fa-fw"></i> Gerenciamento<span class="fa arrow"></span>
                          </a>
                          <ul class="nav nav-second-level collapse" id="collapseMulti">             
                            <li>
                                <a id="menu_venda" href="#"> Formas de Pagamento</a>
                            </li>
                            <li>
                                <a id="menu_venda" href="#">NCM</a>
                            </li>
                            <?php if ($_SESSION['usuario']['perfil'] == 'A'): ?>
                                <li>
                                    <a id="menu_venda" href="#">Usuários</a>
                                </li>
                                <li>
                                    <a id="menu_venda" href="#">Vendedores</a>
                                </li>

                                <li>
                                    <a id="menu_venda" href="#">Perfis de Clientes</a>
                                </li>
                                <li>
                                    <a id="menu_venda" href="#">Tipos de Produtos</a>
                                </li>
                                <li>
                                    <a id="menu_venda" href="#">Lojas</a>
                                </li>
                            <?php endif; ?>         
                          </ul>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>