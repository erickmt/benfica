

<!-- Autenticacao exclusão de venda -->
<div class="modal fade"  role="dialog" id="autenticacaoExclusaoVenda" >
  <div class="modal-dialog modal-md" >

    <div class="modal-content">

      <form class="form-signin" id="autorizacaoExclusaoVenda" method="post">
      <div class="modal-body">

          <h4><b>Usuário não autorizado a realizar a operação</b></h4>
          <hr>
          <p>Um usuário do tipo "Administrador" ou "Sub-gerente" deve autorizar o procedimento:</p>        

            <div class="row">
                <div class="col-lg-6">
                    <p><b>Login:</b></p>
                    <input type="text" id="login" name="login" class="form-control" maxlength="32" placeholder="Digite seu login" required="" autofocus="">
                </div>
                <div class="col-lg-6">
                    <p><b>Senha:</b></p>
                    <input type="password" id="senha" name="senha" class="form-control" maxlength="32" placeholder="Digite sua senha" required="">
                </div>                
                <input type="hidden" id="idVenda" name="idVenda">                
                <div class="col-lg-12">
                    <br>
                    <div class="alert alert-danger" id="erroAutorizacaoExclusaoVenda" style="display: none; margin: 0%;" ></div>
                </div>                                
            </div>
          </form>

      </div>
      <div class="modal-footer">
        <a  href='javascript:void(0)' onclick='autenticarUsuarioAouS(1);' class="btn btn-success" >Autenticar</a>
        <a  href='javascript:void(0)' onclick='fecharModalAutenticacaoExclusaoVenda();' data-dismiss="modal" class="btn btn-default" >Sair</a>
      </div>
    </div>

  </div>
</div>

<!-- Confirmação de exclusão de venda -->
<div class="modal fade"  role="dialog" id="confirmacaoExclusaoVenda" >
  <div class="modal-dialog modal-md" >

    <div class="modal-content">
      <div class="modal-body">
            <h4><b>Confirmação de procedimento:</b></h4>
            <hr>
            <p id="textoModalExclusao"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="confirmaExclusao" onclick="">Confirmar cancelamento da venda</button>
        <button type="button" data-dismiss="modal" class="btn btn-default" id="naoConfirmaExclusao"  onclick="">A venda não deverá ser cancelada</button>
      </div>
    </div>

  </div>
</div>


<!-- Painel principal da venda  -->
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
                    <h1 class="page-header">Cancelar Venda</h1>
                </div>
            </div>
            
            <div class="row">
         
                <!-- Barra de progresso da venda -->
                <div class="col-lg-12" >



                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Informe o código da venda ou a identificação do cliente:
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" >



                                   <form role="form" id="pesquisarVenda" method="POST">

                                    <!-- /.row -->
                                    <div class="row">

                                        <div class="col-lg-2">

                                            <div class="form-group">
                                                <label>Código da venda:</label>
                                                <input class="form-control" name="codigoVendaPesquisa" id="codigoVendaPesquisa" onkeypress="return SomenteNumero(event);" >
                                            </div>

                                        </div>

                                        <div class="col-lg-6">

                                            <div class="form-group">
                                                <label>Nome completo:</label>
                                                <input class="form-control" name="nomeCompletoPesquisa" id="nomeCompletoPesquisa" maxlength="255" >
                                            </div>

                                        </div>
                                        <div class="col-lg-4">

                                            <div class="form-group">
                                                <label>CPF / CNPJ:</label>
                                                <input class="form-control" name="numeroIdentidadePesquisa" id="numeroIdentidadePesquisa" maxlength="32" >
                                            </div>

                                        </div>
                                        <div class="col-lg-12">

                                            <center>
                                            <button type="submit" class="btn btn-default">
                                                <i class="fa fa-search"></i>
                                                Pesquisar Venda
                                            </button></center>


                                        </div>

                                        <div class="col-lg-12">
                                            <br>

                                            <div class="alert alert-danger" role="alert" id="ErroLocalizaVenda" style="display: none;">
                                                <strong>Erro: </strong> Não foi localizada nenhuma venda para a pesquisa realizada.
                                            </div>                                    

                                        </div>
</div>
                                    </form> </div></div>


                    <div class="alert alert-danger" role="alert" id="ErroExclusaoVenda" style="display: none;">
                    </div>

                    <div class="panel panel-default" id="sessaoResultadoVendasLocalizadas" style="display: none;">
                        <div class="panel-heading">
                            <h4 style="text-align: center"><b>Vendas Localizadas:</b></h4>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">



                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="tabelaVendasLocalizadas">
                                    <thead>
                                        <tr>
                                            <th  width="6%">Venda</th>
                                            <th  width="9%">Data</th>
                                            <th  width="40%">Cliente</th>
                                            <th  width="40%">Produtos</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>16565444</td>
                                            <td>09/12/2019</td>
                                            <td>Mark</td>
                                            <td>Otto</td>
                                            <td><button type="button" class="btn btn-danger">Excluír</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>

                            <p><b>Critérios de Pesquisa:</b></p>
                            <p id="codigoVendaPesquisaVenda">&emsp; &emsp; &emsp; &emsp;Código da venda: 321</p>
                            <p id="nomeCompletoPesquisaVenda">&emsp; &emsp; &emsp; &emsp;Nome completo: 321</p>
                            <p id="numeroIdentidadePesquisaVenda">&emsp; &emsp; &emsp; &emsp;Número identidade: 321</p>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>



                   