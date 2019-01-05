<!-- Autenticacao alteracao preço do produto na venda / alteração vendedor do cliente para a venda -->
<div class="modal fade"  role="dialog" id="autenticacaoAlteracaoVenda" >
   <div class="modal-dialog modal-md" >
      <div class="modal-content">
         <form class="form-signin" id="autorizacaoExclusaoVenda" method="post">
            <div class="modal-body">
               <h4><b>Usuário não autorizado a realizar a operação</b></h4>
               <hr>
               <p>Um usuário autorizado deve autorizar o procedimento:</p>
               <div class="row">
                  <div class="col-lg-6">
                     <p><b>Login:</b></p>
                     <input type="text" id="login" name="login" class="form-control" maxlength="32" placeholder="Digite seu login" required="" autofocus="">
                  </div>
                  <div class="col-lg-6">
                     <p><b>Senha:</b></p>
                     <input type="password" id="senha" name="senha" class="form-control" maxlength="32" placeholder="Digite sua senha" required="">
                  </div>
                  <input type="hidden" id="controle" name="controle" value="0">                
                  <div class="col-lg-12">
                     <br>
                     <div class="alert alert-danger" id="erroAutorizacaoAlteracaoVenda" style="display: none; margin: 0%;" ></div>
                  </div>
               </div>
         </form>
         </div>
         <div class="modal-footer">
            <a  href='javascript:void(0)' onclick='autenticarUsuarioAouS(2);' class="btn btn-success" >Autenticar</a>
            <a  href='javascript:void(0)' onclick='fecharModalAutenticacaoAlteracaoVenda();' data-dismiss="modal" class="btn btn-default" >Sair</a>
         </div>
      </div>
   </div>
</div>
<!-- Confirmação de início de venda -->
<div class="modal fade"  role="dialog" id="confirmacaoInicioNovaVenda" >
   <div class="modal-dialog modal-md" >
      <div class="modal-content">
         <div class="modal-body">
            <h4><b>Confirmação de procedimento:</b></h4>
            <hr>
            <p>Deseja realmente iniciar uma nova venda?</p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="novaVenda" onclick="iniciarNovaVenda(1)">Sim, iniciar uma nova venda</button>
            <button type="button" data-dismiss="modal" class="btn btn-default" onclick="iniciarNovaVenda(0)">Não, continuar com a venda corrente</button>
         </div>
      </div>
   </div>
</div>
<!-- Painel principal da venda  -->
<div id="wrapper">
   <script type="text/javascript" charset="utf-8" src="js/dialogo.js"></script>   
   <div id="page-wrapper">
      <div class="row">
         <div class="col-lg-12">
            <h1 class="page-header">Realizar Venda
               <a>  <?php  
                  if($_SESSION['usuario']['id_loja'] != 0){
                      echo " - ".$_SESSION['usuario']['lojaDescricao'];
                  }
                  ?></a>
               <!--<button id='verifica-caixa' class="btn btn-primary" style="float:right; margin: 0 5px" onclick='BotaoCaixa()'><i class="fa fa-refresh"></i></button>
               <button type="button" id='abre-caixa' class="btn btn-secondary" style="float:right;" disabled>Caixa</button>
               -->
            </h1>
         </div>
      </div>
      <div class="row">
         <!-- Barra de progresso da venda -->
         <div class="col-lg-12" id="painelVendaPrincipal">
            <div class="panel panel-default">
               <div class="panel-heading">
                  Siga os passos definidos abaixo para completar a venda:
               </div>
               <!-- /.panel-heading -->
               <div class="panel-body" id="painel_principal_venda">
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs">
                     <!--<li class="active" id="MenuOpcaoPasso01" data-toggle="modal" data-target="#confirmacaoInicioNovaVenda" ><a href="#passo01" data-toggle="tab">Passo 01 - Identificação Cliente</a>-->
                     <li class="active" id="MenuOpcaoPasso01" data-toggle="modal" data-target="#confirmacaoInicioNovaVenda" ><a href="#passo01" data-toggle="">Passo 01 - Identificação Cliente</a>
                     </li>
                     <!--<li id="MenuOpcaoPasso02"><a href="#passo02" data-toggle="tab">Passo 02 - Inclusão Produtos</a>-->
                     <!-- Quando não pode acessar, deve ficar assim:  -->
                     <li id="MenuOpcaoPasso02" class="disabled"><a  data-toggle="">Passo 02 - Inclusão Produtos</a>
                     </li>
                     <!--<li id="MenuOpcaoPasso03"><a href="#passo03" data-toggle="tab">Passo 03 - Definição Pagamento</a>-->
                     <li id="MenuOpcaoPasso03" class="disabled"><a data-toggle="">Passo 03 - Definição Pagamento</a>
                     </li>
                     <!--<li id="MenuOpcaoPasso04"><a href="#passo04" data-toggle="tab">Passo 04 - Finalização Venda</a>-->
                     <li id="MenuOpcaoPasso04" class="disabled"><a data-toggle="">Passo 04 - Finalização Venda</a>
                     </li>
                  </ul>
                  <!-- Tab panes -->
                  <div class="tab-content">
                     <!-- PASSO 01 VENDA / IDENTIFICAÇÃO DE CLIENTE -->
                     <div class="tab-pane fade in active" id="passo01">
                        <br>
                        <i>
                           <p style="text-align: center;" id="msgInicial">Informe os dados básicos do cliente para iniciar uma nova venda. Este deverá ser cadastrado caso não seja localizado.</p>
                        </i>
                        <br>
                        <form role="form" id="buscaCliente" method="post">
                           <!-- /.row -->
                           <div class="row">
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label>Nome Completo:</label>
                                    <input class="form-control" name="nomeCompletoPesquisa" id="nomeCompletoPesquisa" maxlength="255" >
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <label>CPF / CPNJ:</label>
                                    <input class="form-control" name="numeroIdentidadePesquisa" id="numeroIdentidadePesquisa" maxlength="32"  >
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <center>
                                    <div class="form-group">
                                       <button type="submit" class="btn btn-default" id="pesquisaCliente">
                                       <i class="fa fa-search"></i>
                                       Pesquisar Cliente
                                       </button>
                                       <!--<button type="button" class="btn btn-default" id="cadastro_cliente_rapido" data-toggle="modal" data-target="#clienteModal"> <i class="fa fa-plus"></i> Cadastrar Novo Cliente</button>-->
                                    </div>
                                 </center>
                              </div>
                              <div class="col-lg-12">
                                 <center>
                                    <div class="col-lg-5"></div>
                                    <div class="col-lg-2">
                                       <?php if ($_SESSION['usuario']['id_loja'] == 0): ?>
                                       <select class="form-control" name="lojaVenda" id="lojaVenda">
                                          <?php foreach ($listarLojas as $key => $value): ?>
                                          <option value="<?php echo $value['id_loja']; ?>"> <?php echo $value['descricao']; ?> </option>
                                          <?php endforeach; ?>
                                       </select>
                                       <!-- <button type="button" class="btn btn-primary" id="selecionarLoja" > Selecionar Loja</button>                                             -->
                                       <?php endif; ?>
                                    </div>
                                    <div class="col-lg-2"></div>
                                 </center>
                                 <div id="load01" style="display: none;">
                                    <br>
                                    <center><img src="library/loader.gif" alt="Carregando..." height="60" width="60"></center>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <br>
                                 <div class="alert alert-warning" role="alert" style="display: none;" id="AvisoLocalizaCliente">
                                    <!-- codigo montado no controller , js -->
                                 </div>
                                 <div class="alert alert-danger" role="alert" style="display: none;" id="ErroLocalizaCliente">
                                    <!-- Código montado no controller --> 
                                 </div>
                              </div>
                        </form>
                        </div>
                     </div>
                     <!-- PASSO 02 VENDA / INCLUSÃO DE ITENS DA VENDA -->
                     <div class="tab-pane fade " id="passo02">
                        <br>
                        <!-- /.row -->
                        <div class="row">
                           <div hidden>
                           </div>
                           <div class="col-lg-12">
                              <div class="well well-sm" id="identificacaoCliente">
                                 <center>
                                    <br>
                                    <p>Cliente <b>Victor Theles da Silva Costa</b> de perfil <b>Atacadista</b>.</p>
                                 </center>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="panel panel-primary">
                                 <div class="panel-heading">
                                    <center>Inclusão Itens de Venda</center>
                                 </div>
                                 <div class="panel-body">
                                    <div class="row">
                                       <form role="form" id="inclusaoProdutosVenda" method="post">
                                       <div class="col-lg-12">
                                             <div class="form-group">
                                                <label>Código de barras:</label>
                                                <input class="form-control" style="width: 100%" name="codigoBarra" id="codigoBarra" disabled>
                                             </div>
                                          </div>
                                          <div class="col-lg-12">
                                             <div class="form-group " >
                                                <label>Nome Produto:</label>
                                                <select id="nomeProduto" style="width: 100%" name="nomeProduto" class="form-control">
                                                   <option value='' ></option>
                                                </select>
                                             </div>
                                          </div>
                                          <div class="col-lg-6">
                                             <div class="form-group">
                                                <label>Quantidade:</label>
                                                <input class="form-control" type="number"  min="1" max="1000" name="quantidadeProduto" id="quantidadeProduto" value="1" onkeypress="return SomenteNumero(event);" >
                                             </div>
                                          </div>
                                          <div class="col-lg-6">
                                             <div class="form-group">
                                                <label>Valor Unitário:</label>
                                                <input class="form-control" name="valorUnitarioProduto" id='valorUnitarioProduto' valorOriginal='0,0' value='0,0' onKeyUp="maskIt(this,event,'###.###.###,##',true)" >  
                                             </div>
                                          </div>
                                          <input type="hidden" name="valorVarejo" id="valorVarejo">
                                          <input type="hidden" name="valorAtacado" id="valorAtacado">
                                          <input type="hidden" name="pesoTotal" id="pesoTotal" value="">
                                          <div class="col-lg-1"></div>
                                          <div class="col-lg-8">
                                             <center><button type="submit" class="btn btn-primary btn-block" id="botaoIncluirItemVenda">Incluir Item</button></center>
                                          </div>
                                          <!--<div class="col-lg-5">
                                             <center><button type="button" class="btn btn-default btn-block" id="cadastro_rapido" data-toggle="modal" data-target="#produtoModal"> <i class="fa fa-plus"></i>  Cadastrar Novo Produto</button></center>
                                          </div>-->
                                          <div class="col-lg-1"></div>
                                          <div class="col-lg-2">
                                             <center><button type="button" class="btn btn-default" onclick="localizarProdutoPorTipo()"><i class="fa fa-refresh"></i></button>
                                             </center>
                                          </div>
                                          <div class="col-lg-12">
                                             <br>
                                             <div class="alert alert-danger" id="erroInclusaoItemVenda" style="display: none; margin: 0%;"></div>
                                             <div class="alert alert-success" id="sucessoInclusaoItemVenda" style="display: none; margin: 0%;"></div>
                                             <center>
                                                <div id="processoInclusaoVenda" style="display: none;"><img src="library/loader-2.gif" alt="Incluindo..." height="53" width="60"></div>
                                             </center>
                                          </div>
                                       </form>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="panel panel-primary">
                                 <div class="panel-heading">
                                    <center>Desconto Cliente</center>
                                 </div>
                                 <div class="panel-body">
                                    <form>
                                       <div class="row">
                                          <center>
                                             <p>Este desconto será abatido no valor da venda corrente.</p>
                                          </center>
                                          <div class="col-lg-6">
                                             <div class="form-group input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" class="form-control" id="valorCreditoCliente" onKeyUp="maskIt(this,event,'###.###.###,##',true)">
                                             </div>
                                          </div>
                                          <div class="col-lg-6">
                                             <button type="button" class="btn btn-default btn-block" id="confirmarAtualizacaoCredito">Atualizar Desconto</button>
                                          </div>
                                          <div class="col-lg-12">
                                             <div class="alert alert-danger" id="erroAlteracaoCreditoCliente" style="display: none;"></div>
                                             <center>
                                                <div class="alert alert-success" id="sucessoAlteracaoCreditoCliente" style="display: none;" ></div>
                                             </center>
                                          </div>
                                          <input type="hidden" name="perfilCliente" id="perfilCliente">
                                       </div>
                                    </form>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="panel panel-primary">
                                 <div class="panel-heading">
                                    <center>Especialidades Venda</center>
                                 </div>
                                 <div class="panel-body">
                                    <div class="row">
                                       <div class="col-lg-6">
                                          <div class="form-group">
                                             <label>Venda Tipo Consignado: </label><br>
                                             <label class="radio-inline">
                                             <input type="radio" name="vendaTipoConsignado" id="optionsRadiosInline1" value="N" checked>Não
                                             </label>
                                             <label class="radio-inline">
                                             <input type="radio" name="vendaTipoConsignado" id="optionsRadiosInline2" value="S">Sim
                                             </label>
                                          </div>
                                       </div>
                                       <div class="col-lg-6">
                                          <div class="form-group">
                                             <label>Venda Externa: </label><br>
                                             <div class="form-group input-group">
                                                <span class="input-group-addon">
                                                <input type="checkbox" name="habilita" id="habilita" onchange="habilitar()" />
                                                </span>
                                                <span class="input-group-addon">$</span>    
                                                <input type="text" class="form-control" id="valorDeslocamento" value="0,0" disabled onKeyUp="maskIt(this,event,'###.###.###,##',true)">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="col-lg-12">
                                          <div class="alert alert-danger" id="erroAlteracaoValorDeslocamento" style="display: none;"></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-12">
                              <div class="panel panel-primary">
                                 <div class="panel-heading">
                                    <center>
                                       Prévia Valor Final
                                       <p><small>Os valores abaixo podem sofrer ajustes de acordo com as formas de pagamento a serem definidas no próximo passo.</small></p>
                                    </center>
                                 </div>
                                 <div class="panel-body">
                                    <div class="table-responsive">
                                       <table class="table table-hover" id="tabelaProdutos" class="produtoLista">
                                          <thead>
                                             <tr>
                                                <th width="5%">#</th>
                                                <th width="30%">Produto</th>
                                                <th width="12%">Quantidade</th>
                                                <th width="12%">Peso Total (g)</th>
                                                <th width="12%">Valor Unitário</th>
                                                <th width="12%">Valor Total</th>
                                                <th width="9%%">Editar</th>
                                                <th width="9%%">Excluir</th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                             <tr>
                                                <td width="5%"></td>
                                                <td width="30%"><b>TOTAL:</b></td>
                                                <td width="12%"></td>
                                                <td width="12%"></td>
                                                <td width="12%"></td>
                                                <td width="12%"></td>
                                                <td width="9%"></td>
                                                <td width="9%"></td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                    <center>
                                       <button type="button" class="btn btn-default" onclick="atualizaItensVenda()"><i class="fa fa-refresh"></i>  Atualizar</button>
                                    </center>
                                    <!-- /.table-responsive -->
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="panel panel-primary">
                                 <div class="panel-heading">
                                    <center>Responsável Venda</center>
                                 </div>
                                 <div class="panel-body">
                                    <div class="form-group">
                                       <label>Vendedor:</label>
                                       <select class="form-control" id="selecaoVendedor">
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           
                           <div class="col-lg-6">
                              <div class="panel panel-primary">
                                 <div class="panel-heading" id="cabecalhoOrcamento">
                                    <center>Orçamento</center>
                                 </div>
                                 <div class="panel-body">
                                    <div class="row">
                                       <div class="col-lg-6">
                                          <div class="form-group">
                                             <label>Salvar:</label>
                                             <button class="btn btn-primary btn-block" onclick="salvarOrcamento()"> Salvar </button>
                                          </div>
                                       </div>
                                       <div class="col-lg-6">
                                          <div class="form-group">
                                             <label>Carregar:</label>
                                             <div class="input-group">
                                                <input type="text" class="form-control" name="orcamentoId" id="orcamentoId" placeholder="Orçamento" required>
                                                <span class="input-group-btn">
                                                   <button class="btn btn-default" data-toggle='modal' data-target='#orcamentoModal' type="button"><i class="fa fa-search"></i></button>
                                                </span>
                                                <span class="input-group-btn">
                                                   <button class="btn btn-default" onclick="selecionarOrcamento()" type="button"><i class="fa fa-mouse-pointer"></i></button>
                                                </span>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="col-lg-12">
                                          <div class="alert alert-danger" id="erroOrcamento" style="display: none;"></div>
                                          <div class="alert alert-success" id="sucessoOrcamento" style="display: none;"></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="col-lg-12">
                              <button type="button" class="btn btn-success btn-block" onclick="trasferenciaPasso02paraPasso03()" >Prosseguir Venda</button><br>
                              <div class="alert alert-danger" id="erroEvolucaoPasso03" style="display: none;"></div>
                           </div>
                        </div>
                     </div>
                     <!-- PASSO 03 / DEFINIÇÃO DE MEIOS DE PAGAMENTO --> 
                     <div class="tab-pane fade" id="passo03">
                        <br>
                        <!-- /.row -->
                        <div class="row">
                           <div class="col-lg-12">
                              <div class="well well-sm" id="identificacaoClientePasso03">
                                 <center>
                                    <br>
                                    <p>Cliente <b>Victor Theles da Silva Costa</b> de perfil <b>Atacadista</b>.</p>
                                 </center>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="panel panel-primary">
                                 <div class="panel-heading">
                                    <center>Inclusão Formas de Pagamento</center>
                                 </div>
                                 <div class="panel-body">
                                    <div class="row">
                                       <form role="form" id="inclusaoFormasPagamento">
                                          <div class="col-lg-12">
                                             <label>Valor:</label>
                                             <div class="form-group ">
                                                <input type="text" class="form-control" name="valorFormaPagamento" id="valorFormaPagamento" required onKeyUp="maskIt(this,event,'###.###.###,##',true)" />
                                             </div>
                                          </div>
                                          <div class="col-lg-12">
                                             <div class="form-group">
                                                <label>Forma Pagamento:</label>
                                                <select class="form-control" name="idFormaPagamento" id="idFormaPagamento">
                                                   <option>Dinheiro</option>
                                                   <option>Cartão</option>
                                                </select>
                                             </div>
                                          </div>
                                          <div class="col-lg-12" id="apresentacaoTaxas">
                                             <div class="form-group">
                                                <label>Considerar taxas: </label><br>
                                                <label class="radio-inline">
                                                <input type="radio" name="indicadorConsiderarTaxas" id="indicadorConsiderarTaxasSim" value="S" checked>Sim
                                                </label>
                                                <label class="radio-inline">
                                                <input type="radio" name="indicadorConsiderarTaxas" id="indicadorConsiderarTaxasNao" value="N">Não
                                                </label>
                                             </div>
                                          </div>
                                          
                                             <div class="col-lg-12">
                                                 <label>Quantidade Parcelas:</label>
                                                 <div class="form-group">
                                                     <input type="text" class="form-control" name="quantidadeParcelas" value="1" min="1" max="50" id="quantidadeParcelas" required onkeypress="return SomenteNumero(event);">
                                                 </div>
                                             </div>                                                        
                                             
                                          <div class="col-lg-12">
                                             <center><button type="submit" class="btn btn-primary btn-block">Incluir</button></center>
                                             <br>
                                             <div class="alert alert-danger" id="erroInclusaoFormaPagamento" style="display: none;"></div>
                                          </div>
                                       </form>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-8">
                              <div class="panel panel-primary">
                                 <div class="panel-heading">
                                    <center>Formas de Pagamento Incluídas</center>
                                 </div>
                                 <div class="panel-body">
                                    <div class="table-responsive">
                                       <table class="table table-hover" id="listaFormasDePagamento">
                                          <thead>
                                             <tr>
                                                <th>Forma De Pagamento</th>
                                                <th>Valor em produtos</th>
                                                <th>Valor total (c/ desconto)</th>
                                                <th>Parcelas</th>
                                                <th></th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                             <tr>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                    <div class="alert alert-danger" id="indicadorConclusaoVenda"></div>
                                 </div>
                              </div>
                           </div>
 
                           <div class="col-lg-12">
                              <div class="panel panel-primary">
                                 <div class="panel-heading">
                                    <center>Resumo Pagamento Venda</center>
                                 </div>
                                 <div class="panel-body">
                                    <div class="table-responsive">
                                       <table class="table table-hover" id="resumoPagamentoVenda">
                                          <tbody>
                                             <tr>
                                                <td>Valor Total em Produtos:</td>
                                                <td>R$ 23,00</td>
                                             </tr>
                                             <tr>
                                                <td>Valor Total em Deslocamento (Frete):</td>
                                                <td>R$ 00,00</td>
                                             </tr>
                                             <tr>
                                                <td>Valor de Desconto da Venda:</td>
                                                <td>R$ 3,00</td>
                                             </tr>
                                             <tr>
                                                <td>Valor Total em Desconto das Formas de Pagamento:</td>
                                                <td>R$ 3,00 , sendo:
                                                   <small>
                                                   <br>&emsp; &emsp; &emsp; &emsp; &emsp; &emsp; 1,50 (Cartão)
                                                   <br>&emsp; &emsp; &emsp; &emsp; &emsp; &emsp; 1,50 (Dinheiro)
                                                   </small>
                                                </td>
                                             </tr>
                                             <tr>
                                                <td><b>Valor Total</b></td>
                                                <td><b>R$ 3,00</b></td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                 </div>
                              </div>
                              <hr>
                              <div class="panel panel-primary">
                                 <div class="panel-heading">
                                    <center>Atualizar cadastro cliente</center>
                                 </div>
                                 <div class="panel-body">
                                    <form role="form" id="atualizaDadosCliente" method="post">
                                      <div class="row">
                                          <div class="col-md-2">
                                             <label>CEP:</label>
                                             <div class="input-group">
                                                
                                               <input onfocusout="buscarCep()" type="text" class="form-control" name="cepClienteSelecionado" id="cepClienteSelecionado"/>
                                               <span class="input-group-btn">
                                                 <button class="btn btn-default" onclick="buscarCep()" type="button"><i class="fa fa-refresh"></i></button>
                                               </span>
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group ">
                                                <label>Rua:</label>
                                                <input type="text" class="form-control" name="ruaClienteSelecionado" id="ruaClienteSelecionado"/>
                                             </div>
                                          </div>
                                          <div class="col-md-1">
                                             <div class="form-group ">
                                                <label>Número:</label>
                                                <input type="text" class="form-control" name="numeroClienteSelecionado" id="numeroClienteSelecionado"/>
                                             </div>
                                          </div>
                                          <div class="col-md-2">
                                             <div class="form-group ">
                                                <label>Bairro:</label>
                                                <input type="text" class="form-control" name="bairroClienteSelecionado" id="bairroClienteSelecionado" />
                                             </div>
                                          </div>
                                          <div class="col-md-2">
                                             <div class="form-group ">
                                                <label>Cidade:</label>
                                                <input type="text" class="form-control" name="cidadeClienteSelecionado" id="cidadeClienteSelecionado" />
                                             </div>
                                          </div>
                                          <div class="col-md-1">
                                             <div class="form-group ">
                                                <label>Estado:</label>
                                                <input type="text" class="form-control" name="ufClienteSelecionado" id="ufClienteSelecionado" maxlength="2" />
                                             </div>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-md-3">
                                             <div class="form-group ">
                                                <label>CPF / CNPJ:</label>
                                                <input type="text" class="form-control" name="cpfClienteSelecionado" id="cpfClienteSelecionado" maxlength="18" />
                                             </div>
                                          </div>
                                          <!--<div class="col-md-3">
                                             <div class="form-group ">
                                                <label>IE:</label>
                                                <input type="text" class="form-control" name="ieClienteSelecionado" id="ieClienteSelecionado" maxlength="18" />
                                             </div>
                                          </div>-->
                                          <div class="col-md-9">
                                             <div class="form-group ">
                                                <label>Email:</label>
                                                <input type="text" class="form-control" name="emailClienteSelecionado" id="emailClienteSelecionado" />
                                             </div>
                                          </div>
                                       </div>
                                    </form>
                                    <div class="alert alert-danger" id="erroCpfCnpj" style="display: none;">
                                       <strong>Erro: CPF / CNPJ Inválido</strong>
                                    </div>
                                 </div>
                              </div>
                              <button type="button" class="btn btn-success btn-block" onclick="trasferenciaPasso03paraPasso04();" >Finalizar Venda</button><br>
                              <div class="alert alert-danger" id="erroEvolucaoPasso04" style="display: none;"></div>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane fade" id="passo04">
                        <br>
                        <div class="iconeFixo">
                           <a href="javascript:void(0)" onclick="concluirVenda();" id="simboloConfirmacao"><img src="library/confirmacao.png" alt="Concluir Venda" height="50" width="50"></a>
                           <BR><BR>
                           <a href="javascript:void(0)" onclick="printDiv();" ><img src="library/impressora-2.png" alt="Imprimir Recibo" height="50" width="50"></a>
                        </div>
                        <center><span class="label label-danger" id="avisoVendaNaoConcluida" >Venda não concluída!</span></center>
                        <br>
                        <div id="recibo">
                           <center>
                              <table border="1" id="tabelaRecibo" width="90%">
                                 <tfixo1>
                                    <tr bgcolor="#DCDCDC">
                                       <th width="100%" colspan="4">
                                          <p style="margin: 12px; text-align:center;" id="tituloRecibo"></p>
                                       </th>
                                    </tr>
                                    <tr bgcolor="#F5F5F5">
                                       <td colspan="4">
                                          <p style="font-size: 11px; text-align:center; margin: 8px;">SEGUNDA A SEXTA DE 09:00HS ÀS 18:00HS<br>SÁBADO DE 09:00HS ÀS 17:00HS</p>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td class="tg-yw4l" colspan="1" style="line-height: 1.5;" id="identificacaoClienteRecibo" >&nbsp;&nbsp;<b>Cliente:</b> André Luiz da Silva (Atacadista)<br>&nbsp;&nbsp;<b>RG:</b> 3234544323<br>&nbsp;&nbsp;<b>Telefone:</b> 98784532<br></td>
                                       <td class="tg-yw4l" colspan="3" style="line-height: 1.5;" id="identificacaoVendaRecibo">&nbsp;&nbsp;<b>Código da venda:</b> 33234<br>&nbsp;&nbsp;<b>Data:</b> 11/11/2017<br>&nbsp;&nbsp;<b>Vendedor:</b> Luiz Silva<br></td>
                                    </tr>
                                    <tr bgcolor="#F5F5F5">
                                       <td class="tg-yw4l" width="50%">
                                          <p style="margin: 5px; text-align:center; font-weight: bold;">Descrição Produto</p>
                                       </td>
                                       <td class="tg-yw4l" width="16%">
                                          <center><b>Quantidade</b></center>
                                       </td>
                                       <td class="tg-yw4l" width="18%">
                                          <center><b>Valor Unitário</b></center>
                                       </td>
                                       <td class="tg-yw4l" width="18%">
                                          <center><b>Valor Total</b></center>
                                       </td>
                                    </tr>
                                 </tfixo1>
                                 <div id="tbodyRecibo">
                                    <!--
                                       <tr>
                                         <td><p style="font-size: 11px; margin: 4px;">BERMUDA COLORIDA TOP</p></td>
                                         <td><p style="font-size: 11px; margin: 4px; text-align:center;">2</p></td>
                                         <td><p style="font-size: 11px; margin: 4px; text-align:left;">R$ 15,00</p></td>
                                         <td><p style="font-size: 11px; margin: 4px; text-align:left;">R$ 30,00</p></td>
                                       </tr>  -->
                                 </div>
                                 <tr bgcolor="#DCDCDC" id="resumoPagamentoRecibo">
                                    <td colspan="4">
                                       <p style="margin: 5px; text-align:center; font-weight: bold;">Resumo Pagamento</p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td colspan="3">
                                       <p style="font-size: 11px; margin: 4px;">TOTAL EM PRODUTOS</p>
                                    </td>
                                    <td id="totalProdutosRecibo">
                                       <p style="font-size: 11px; margin: 4px; text-align:left;">R$ 30,00</p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td colspan="3">
                                       <p style="font-size: 11px; margin: 4px;">TOTAL DA VENDA</p>
                                    </td>
                                    <td id="totalVenda">
                                       <p style="font-size: 11px; margin: 4px; text-align:left;">R$ 30,00</p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td colspan="3">
                                       <p style="font-size: 11px; margin: 4px;">DESCONTO NA VENDA</p>
                                    </td>
                                    <td id="totalCreditoRecibo">
                                       <p style="font-size: 11px; margin: 4px; text-align:left;">R$ 30,00</p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td colspan="3">
                                       <p style="font-size: 11px; margin: 4px;">FRETE/CORREIOS/MOTOBOY/OUTROS</p>
                                    </td>
                                    <td id="totalDeslocamentoRecibo">
                                       <p style="font-size: 11px; margin: 4px; text-align:left;">R$ 40,00</p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td colspan="3">
                                        <p style="font-size: 11px; margin: 4px;">DESCONTO NO PAGAMENTO</p>
                                    </td>
                                    <td id="totalTaxasRecibo">
                                        <p style="font-size: 11px; margin: 4px; text-align:left;">R$ 30,00</p>
                                    </td>
                                 </tr>                    
                                 <tr bgcolor="#DCDCDC">
                                    <td colspan="4">
                                       <p style="margin: 5px; text-align:center; font-weight: bold;">Formas de Pagamento</p>
                                    </td>
                                 </tr>
                                 <tr bgcolor="#DCDCDC" id="observacoesRecibo">
                                    <td colspan="4">
                                       <p style="margin: 5px; text-align:center; font-weight: bold;">Observações</p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td class="tg-yw4l" id="descricao_nota" colspan="4" style="font-size: 10.5px; line-height: 15px; padding: 5px;">
                                     
                                    </td>
                                 </tr>
                                 <tr bgcolor="#DCDCDC">
                                    <td colspan="4" style="font-size: 10.5px; line-height: 15px; margin: 10">
                                        <p style="margin: 5px; text-align:center; font-weight: bold;">
                                            NÃO TROCAMOS PEÇAS SEM ESTA NOTA.
                                            <br />SÁBADO: TROCA DE NO MÁXIMO 5 PEÇAS
                                        </p>
                                    </td>
                                 </tr>
                                 <tr bgcolor="#F5F5F5">
                                    <td class="tg-yw4l" colspan="4" style="font-size: 11px;">
                                       <center>
                                       <b id="rodape">
                                       </b>
                                       </center>
                                    </td>
                                 </tr>
                                 <tfixo3>
                              </table>
                           </center>
                        </div>
                  </div>
               </div>
               <!-- /.panel-body -->
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="orcamentoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
      
         <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
         <h4 class="modal-title" id="myModalLabel">Buscar Orçamentos</h4>
         </div>



         <div class="modal-body">
            <div class="row">

               <div class="col-md-5 form-group"> 
                  <label>Cliente:</label>
                  <br><input type="text" class="form-control" id="orcamentoCliente" />
               </div>
               <div class="col-md-4 form-group">
                  <label>Data:</label>
                  <br><input type="date" class="form-control" id="orcamentoData" />         
               </div>
               <div class="col-md-3 form-group">
                  <br><input type="button" value="Pesquisar" onClick="buscarOrcamento()" class="btn btn-button btn-block btn-primary" />               
               </div>

            </div>

            <table class="table table-striped table-bordered table-hover" id="tableOrcamentos">
               <thead>  
               <tr>
                  <th><center>Número</center></th>
                  <th><center>Cliente</center></th>
                  <th><center>Data</center></th>
                  <th><center>Valor</center></th>
                  <th><center>Selecionar</center></th>
               </tr>
               <tbody>
                  <tr>
                  </tr>
               </tbody>
            </table>  
         </div>
         
         <div class="modal-footer">
            <div class="col-lg-12">
               <div class="alert alert-danger" id="erroListaOrcamento" style="display: none;" ></div>
            </div>
         </div>

      </div>
   </div>
</div>

    <div class="modal fade" id="clienteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
			<h4 class="modal-title" id="myModalLabel">Cadastrar cliente</h4>
		  </div>
		  
		  <form  role="form" method="post"class="form-horizontal" id="formCadastroCliente">
		  <div class="modal-body">

			  <div class="form-group">
				<label class="control-label col-sm-5" for="nome">Nome completo:</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="nome" placeholder="">
				</div>
			  </div>
			  
			  <div class="form-group">
				<label class="control-label col-sm-5" for="identidade">Número de identidade:</label>
				<div class="col-sm-7"> 
				  <input type="number" class="form-control" id="identidade" placeholder="Ex: 9999999999">
				</div>
			  </div>
			  
			  <div class="form-group">
				<label class="control-label col-sm-5" for="cpf">CPF:</label>
				<div class="col-sm-7">
				  <input type="number" class="form-control" id="cpf" placeholder="">
				</div>
			  </div>		

			  <div class="form-group">
				<label class="control-label col-sm-5" for="orgao_expeditor">Órgão expeditor:</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="orgao_expeditor" placeholder="">
				</div>
			  </div>				  
			  
			  <div class="form-group">
				<label class="control-label col-sm-5" for="data_nascimento">Data de nascimento:</label>
				<div class="col-sm-7">
				  <input type="date" class="form-control" id="data_nascimento" placeholder="">
				</div>
			  </div>				

			  <div class="form-group">
				<label class="control-label col-sm-5" for="mae">Nome completo da mãe:</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="mae" placeholder="">
				</div>
			  </div>  			  
			  
			  <div class="form-group">
				<label class="control-label col-sm-5" for="vendedor">Vendedor:</label>
				<div class="col-sm-7">			  
				  <select name="vendedor" id="vendedor">
					<option value="eduardo">Eduardo</option>
					<option value="hudson">Hudson</option>
					<option value="etc">Etc</option>
				  </select>
				</div>
			  </div> 			  
			  
			  <div class="form-group">
				<label class="control-label col-sm-5" for="telefone">Telefone:</label>
				<div class="col-sm-7"> 
				  <input type="number" class="form-control" id="telefone" placeholder="Ex: 31999999999">
				</div>
			  </div>	

			  <div class="form-group">
				<label class="control-label col-sm-5" for="endereco">Endereço:</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="endereco" placeholder="">
				</div>
			  </div>  		

			  <div class="form-group">
				<label class="control-label col-sm-5" for="bairro">Bairro:</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="bairro" placeholder="">
				</div>
			  </div>  		

			  <div class="form-group">
				<label class="control-label col-sm-5" for="cep">CEP:</label>
				<div class="col-sm-7">
				  <input type="number" class="form-control" id="cep" placeholder="30600100">
				</div>
			  </div>  		
			  
			  <div class="form-group">
				<label class="control-label col-sm-5" for="cidade">Cidade:</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="cidade" placeholder="">
				</div>
			  </div>  				  
			  
			  <div class="form-group">
				<label class="control-label col-sm-5" for="estado">Estado:</label>
				<div class="col-sm-7">			  
				  <select name="uf" id="uf">
					<option value="MG">MG</option>
					<option value="AC">AC</option>
					<option value="AL">AL</option>
					<option value="AM">AM</option>
					<option value="AP">AP</option>
					<option value="BA">BA</option>
					<option value="CE">CE</option>
					<option value="DF">DF</option>
					<option value="ES">ES</option>
					<option value="GO">GO</option>
					<option value="MA">MA</option>
					<option value="MS">MS</option>
					<option value="MT">MT</option>
					<option value="PA">PA</option>
					<option value="PB">PB</option>
					<option value="PE">PE</option>
					<option value="PI">PI</option>
					<option value="PR">PR</option>
					<option value="RJ">RJ</option>
					<option value="RN">RN</option>
					<option value="RS">RS</option>
					<option value="RO">RO</option>
					<option value="RR">RR</option>
					<option value="SC">SC</option>
					<option value="SE">SE</option>
					<option value="SP">SP</option>
					<option value="TO">TO</option>
				  </select>
				</div>
			  </div> 
			  
			  <!-- BR DEFAULT -->
			  <div class="form-group">
				<label class="control-label col-sm-5" for="pais">País:</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="pais" placeholder="">
				</div>
			  </div>  	
			  
			  <div class="form-group">
				<label class="control-label col-sm-5" for="desconto">Desconto:</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="desconto" placeholder="R$ 0.00">
				</div>
			  </div>  	

			  <div class="form-group">
				<label class="control-label col-sm-5" for="observacao">Observação:</label>
				<div class="col-sm-7">
				  <textarea class="form-control" id="observacao" maxlength="255" placeholder="Máximo de 255 caracteres">
				  </textarea>
				</div>
			  </div>  			  

			  <div class="form-group"> 
				<div class="col-sm-offset-2 col-sm-10">
				  <div class="checkbox">
					<label><input type="checkbox">Inativo</label>
				  </div>
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

  	