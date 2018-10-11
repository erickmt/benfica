
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">

                        <!-- MENU PRINCIPAL -->
                        <li>
                            <a href="index.php"><i class="fa fa-home fa-fw"></i> Início</a>
                        </li>

                        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
                          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponents" data-parent="#exampleAccordion">
                            <i class="fa fa-dashboard fa-fw"></i> <span class="nav-link-text">Vendas</span> <span class="fa arrow"></span>
                          </a>
                          <ul class="nav nav-second-level collapse" id="collapseComponents">
                            <li>
                                <a href="realizar-venda.php">Realizar Venda</a>
                            </li>
                            <li>
                                <a href="cancelar-venda.php">Cancelar Venda</a>
                            </li>
                          </ul>
                        </li>

                        <li>
                            <a href="notas.php"><i class="fa fa-file-text-o fa-fw"></i> Notas</a>
                        </li>
                        <li>
                            <a href="clientes.php"><i class="fa fa-group fa-fw"></i> Clientes</a>
                        </li>
                        <li>
                            <a href="produtos.php"><i class="fa fa-tag fa-fw"></i> Produtos</a>
                        </li>
                        <li>
                            <a href="estoque.php"><i class="fa fa-list-alt fa-fw"></i> Estoque</a>
                        </li>
                        <li>
                            <a href="consulta.php"><i class="fa fa-list fa-fw"></i> Consulta</a>
                        </li>
                        <li>
                            <a href="caixa.php"><i class="fa fa-money fa-fw"></i> Caixa</a>
                        </li>
                        <?php if ($_SESSION['usuario']['perfil'] == 'A'): ?>
                            <li>                
                                <a href="consignado.php"><i class="fa fa-pie-chart fa-fw"></i> Vendas Consignadas</a>
                            </li>                        
                        <?php endif; ?>
                        <!--
                        <li>                
                            <a href="vendas_consignadas.php"><i class="fa fa-pie-chart fa-fw"></i> Vendas Consignadas </a>
                        </li>-->
                        <?php if ($_SESSION['usuario']['perfil'] == 'A'): ?>
                         <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Example Pages">
                          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExamplePages" data-parent="#exampleAccordion">
                            <i class="fa fa-files-o fa-fw"></i> Relatórios<span class="fa arrow"></span></a>
                          <ul class="nav nav-second-level collapse" id="collapseExamplePages">
                            <li>
                               <a href="relatorio-comissao-vendedor.php">Comissões Por Vendedor</a>
                            </li>
                            <li>
                               <a href="relatorio-financeiro.php">Financeiro</a>
                            </li>
							<li>
                               <a href="relatorio-faturamento.php">Faturamentos</a>
                            </li>
                            <li>
                               <a href="relatorio-extrato.php">Extrato</a>
                            </li>
                            <li>
                               <a href="relatorio-peca.php">Análise Peças</a>
                            </li>							
                          </ul>
                        </li>
                        <?php endif; ?>

                        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Menu Levels">
                          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMulti" data-parent="#exampleAccordion">
                            <i class="fa fa-wrench fa-fw"></i> Gerenciamento<span class="fa arrow"></span>
                          </a>
                          <ul class="nav nav-second-level collapse" id="collapseMulti">             
                            <li>
                                <a href="forma_pagamento.php"> Formas de Pagamento</a>
                            </li>
                            <li>
                                <a href="ncm.php">NCM</a>
                            </li>
                            <?php if ($_SESSION['usuario']['perfil'] == 'A'): ?>
                                <li>
                                    <a href="usuario.php">Usuários</a>
                                </li>
                                <li>
                                    <a href="vendedor.php">Vendedores</a>
                                </li>

                                <li>
                                    <a href="tipo_cliente.php">Perfis de Clientes</a>
                                </li>
                                <li>
                                    <a href="tipo_produtos.php">Tipos de Produtos</a>
                                </li>
                                <li>
                                    <a href="lojas.php">Lojas</a>
                                </li>
                                <!--<li>
                                    <a href="grid.html">Operações</a>
                                </li>                                -->
                            <?php endif; ?>         
                          </ul>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>