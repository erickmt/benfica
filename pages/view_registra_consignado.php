


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
                    <h1 class="page-header">Registrar Retirada Produto</h1>
                </div>
            </div>
            
            <div class="row">
         
                <!-- Barra de progresso da venda -->
                <div class="col-lg-12" id="painelVendaPrincipal">



                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Siga os passos definidos abaixo para registrar a retira dos produtos:
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

                                    
                                    <br><i><p style="text-align: center;" id="msgInicial">Informe os dados do funcionário.</p></i><br>
                                    

                                    <form role="form" id="buscaFuncionario" method="post">

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
                                                <label>Identidade (RG):</label>
                                                <input class="form-control" name="numeroIdentidadePesquisa" id="numeroIdentidadePesquisa" maxlength="32"  >
                                            </div>

                                        </div>
                                        <div class="col-lg-12">

                                            <center>
                                            <button type="submit" class="btn btn-default">
                                                <i class="fa fa-search"></i>
                                                Pesquisar Funcionario
                                            </button></center>

                                            <div id="load01" style="display: none;"><br>
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

                                        <div class="col-lg-12">
                                            <div class="well well-sm" id="identificacaoCliente">
                                                <center><br><p>Cliente <b>Victor Theles da Silva Costa</b> de perfil <b>Atacadista</b>.</p></center>
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

                                                        <div class="col-lg-9">
                                                            <div class="form-group">
                                                                <label>Tipo Produto:</label>
                                                                 <select class="form-control" name="tipoProduto" id="tipoProduto">
                                                                    <option value='' ></option>
                                                                    <option value='M' >Blusa</option>
                                                                    <option value='F' >Calca</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <label>Modelo:</label>
                                                                <select class="form-control" name="modeloProduto" id="modeloProduto">
                                                                    <option value='0' ></option>
                                                                    <option value='M' >M</option>
                                                                    <option value='F' >F</option>
                                                                </select>
                                                            </div>
                                                        </div>                                                        

                                                        <div class="col-lg-12">
                                                            <div class="form-group " >
                                                                <label>Nome Produto:</label>
                                                                 <select class="form-control" name="nomeProduto" id="nomeProduto">
                                                                    <option value='' ></option>
                                                                    <option value='M' >Blusa</option>
                                                                    <option value='F' >Calca</option>
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
                                                        <div class="col-lg-12">
                                                            <div class="alert alert-danger" id="erroInclusaoItemVenda" style="display: none; margin: 0%;"></div>                                                            
                                                            <center><button type="submit" class="btn btn-primary btn-block" id="botaoIncluirItemVenda">Incluir Item</button></center><br>
                                                            <div class="alert alert-success" id="sucessoInclusaoItemVenda" style="display: none; margin: 0%;"></div>                                                            
                                                            <center><div id="processoInclusaoVenda" style="display: none;"><img src="library/loader-2.gif" alt="Incluindo..." height="53" width="60"></div></center>
                                                        </div>
                                                    </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.col-lg-4 -->
                                        <div class="col-lg-6">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">
                                                    <center>Desconto Cliente</center>
                                                </div>                                                                        
                                                <div class="panel-body">
                                                    <form>
                                                            <div class="row">
                                                                <center><p>Este desconto será abatido no valor da venda corrente.</p></center>
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
                                                                    <center><div class="alert alert-success" id="sucessoAlteracaoCreditoCliente" style="display: none;" ></div></center>                                                            
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
                                                            <label class="radio-inline">
                                                                <input type="radio" name="vendaExterna" id="optionsRadiosInline3" value="N" checked>Não
                                                            </label>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="vendaExterna" id="optionsRadiosInline4" value="S">Sim
                                                            </label>
                                                    </div>

                                                    <br>

                                                   <div class="form-group">
                                                            <label>Valor Deslocamento: </label>
                                                            <div class="form-group input-group">
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
                                                <table class="table table-hover" id="tabelaProdutos">
                                                    <thead>
                                                        <tr>
                                                            <th width="6%">#</th>
                                                            <th width="31%">Produto</th>
                                                            <th width="13%">Quantidade</th>
                                                            <th width="13%">Peso Total (g)</th>
                                                            <th width="13%">Valor Unitário</th>
                                                            <th width="13%">Valor Total</th>
                                                            <th width="10%"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>                                                        
                                                        <tr>
                                                            <td width="6%"></td>
                                                            <td width="31%"><b>TOTAL:</b></td>
                                                            <td width="13%"></td>
                                                            <td width="13%"></td>
                                                            <td width="13%"></td>
                                                            <td width="13%"></td>
                                                            <td width="10%"></td>
                                                        </tr>                                                        
                                                    </tbody>
                                                </table>
                                            </div>

                                            <center>
                                                <button type="button" class="btn btn-default" onclick="atualizaItensVenda()"><i class="fa fa-refresh"></i>  Atualizar</button>
                                                <button type="button" class="btn btn-default"><i class="fa fa-plus"></i>  Cadastrar Novo Produto</button>
                                            </center>

                                            <!-- /.table-responsive -->
                                        </div>
                                        </div>


                                    <div class="panel panel-primary">

                                        <div class="panel-heading">
                                            <center>Responsável Venda</center>
                                        </div>               

                                        <div class="panel-body">
                                            <div class="form-group">
                                                <select class="form-control" id="selecaoVendedor">
                                                    <!-- Options montados de acordo com o retorno do controller - js -->
                                                </select>
                                            </div>
                                        </div>

                                    </div>

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
                                                    <br><p>Cliente <b>Victor Theles da Silva Costa</b> de perfil <b>Atacadista</b>.</p>
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

<!--
                                                        <div class="col-lg-12">
                                                            <label>Quantidade Parcelas:</label>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="quantidadeParcelas" min="1" max="50" id="quantidadeParcelas" value="1" onkeypress="return SomenteNumero(event);">
                                                            </div>
                                                        </div>                                                        
-->
                                                        <div class="col-lg-12">
                                                            <center><button type="submit" class="btn btn-primary btn-block">Incluir</button></center><br>
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
                                                                    <th>Valor total (c/ taxas)</th>
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
                                                                    <td>Valor Total de Desconto do Cliente:</td>
                                                                    <td>R$ 3,00</td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Valor Total em Taxas das Formas de Pagamento:</td>
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
                                            <button type="button" class="btn btn-success btn-block" onclick="trasferenciaPasso03paraPasso04();" >Prosseguir Venda</button><br>
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
                                    <center><span class="label label-danger" id="avisoVendaNaoConcluida" >Venda não concluída!</span></center><br>
                                    <div id="recibo">

                                            <center>
                                            <table border="1" id="tabelaRecibo" width="90%">

                                            <tfixo1>
                                              <tr bgcolor="#DCDCDC">
                                                <th width="100%" colspan="4"><p style="margin: 12px; text-align:center;">BENFICA LOJA - ATACADO/VAREJO - (31) 2564-7158</p></th>
                                              </tr>
                                              <tr bgcolor="#F5F5F5">
                                                <td colspan="4"><p style="font-size: 11px; text-align:center; margin: 8px;">SEGUNDA A SEXTA DE 09:00HS ÀS 18:00HS<br>SÁBADO DE 09:00HS ÀS 17:00HS</p></td>
                                              </tr>
                                              <tr>
                                                <td class="tg-yw4l" colspan="1" style="line-height: 1.5;" id="identificacaoClienteRecibo" >&nbsp;&nbsp;<b>Cliente:</b> André Luiz da Silva (Atacadista)<br>&nbsp;&nbsp;<b>RG:</b> 3234544323<br>&nbsp;&nbsp;<b>Telefone:</b> 98784532<br></td>
                                                <td class="tg-yw4l" colspan="3" style="line-height: 1.5;" id="identificacaoVendaRecibo">&nbsp;&nbsp;<b>Código da venda:</b> 33234<br>&nbsp;&nbsp;<b>Data:</b> 11/11/2017<br>&nbsp;&nbsp;<b>Vendedor:</b> Luiz Silva<br></td>
                                              </tr>
                                              <tr bgcolor="#F5F5F5">
                                                <td class="tg-yw4l" width="50%"><p style="margin: 5px; text-align:center; font-weight: bold;">Descrição Produto</p></td>
                                                <td class="tg-yw4l" width="16%"><center><b>Quantidade</b></center></td>
                                                <td class="tg-yw4l" width="18%"><center><b>Valor Unitário</b></center></td>
                                                <td class="tg-yw4l" width="18%"><center><b>Valor Total</b></center></td>
                                              </tr>
                                          </tfixo1>

                                              <div id="tbodyRecibo"><!--
                                                  <tr>
                                                    <td><p style="font-size: 11px; margin: 4px;">BERMUDA COLORIDA TOP</p></td>
                                                    <td><p style="font-size: 11px; margin: 4px; text-align:center;">2</p></td>
                                                    <td><p style="font-size: 11px; margin: 4px; text-align:left;">R$ 15,00</p></td>
                                                    <td><p style="font-size: 11px; margin: 4px; text-align:left;">R$ 30,00</p></td>
                                                  </tr>  -->
                                              </div>                      
                                              <tr bgcolor="#DCDCDC" id="resumoPagamentoRecibo">
                                                <td colspan="4"><p style="margin: 5px; text-align:center; font-weight: bold;">Resumo Pagamento</p></td>
                                              </tr>

                                              <tr>
                                                <td colspan="3"><p style="font-size: 11px; margin: 4px;">TOTAL EM PRODUTOS</p></td>
                                                <td id="totalProdutosRecibo"><p style="font-size: 11px; margin: 4px; text-align:left;">R$ 30,00</p></td>
                                              </tr>    

                                              <tr>
                                                <td colspan="3"><p style="font-size: 11px; margin: 4px;">FRETE/CORREIOS/MOTOBOY/OUTROS</p></td>
                                                <td id="totalDeslocamentoRecibo"><p style="font-size: 11px; margin: 4px; text-align:left;">R$ 40,00</p></td>
                                              </tr>                                              
<!--
                                              <tr>
                                                <td colspan="3"><p style="font-size: 11px; margin: 4px;">TAXAS</p></td>
                                                <td class="tg-yw4l" style="text-align:left" id="totalTaxasRecibo"><p style="font-size: 11px; margin: 4px; text-align:left;">R$ 30,00</p></td>
                                              </tr>                    
-->
                                              <tr>
                                                <td colspan="3"><p style="font-size: 11px; margin: 4px;">DESCONTO</p></td>
                                                <td id="totalCreditoRecibo"><p style="font-size: 11px; margin: 4px; text-align:left;">R$ 30,00</p></td>
                                              </tr>                                                                               

                                              <tr bgcolor="#DCDCDC">
                                                <td colspan="4"><p style="margin: 5px; text-align:center; font-weight: bold;">Formas de Pagamento</p></td>
                                              </tr>

                                        

                                              <tr bgcolor="#DCDCDC" id="observacoesRecibo">
                                                <td colspan="4"><p style="margin: 5px; text-align:center; font-weight: bold;">Observações</p></td>
                                              </tr>                                              

                                              <tr>
                                                <td class="tg-yw4l" colspan="4" style="font-size: 10.5px; line-height: 15px">
<br>
&nbsp;1) Prazo máximo para troca: 30 (trinta) dias corridos a partir da data da compra - sem renovação;<br>
&nbsp;2) A mercadoria só será trocada desde que esteja nas mesmas condições em que foi recebido/comprado (na embalagem original; com etiqueta, quando for o caso; bem dobrada e sem qualquer indício de uso).<br>
&nbsp;3) NÃO trocamos peças na cor BRANCA/OFF, peças em Promoção, cuecas, peças intimas, meias, carteiras, cintos, oculos, calçados<br>
&nbsp;4) O produto só poderá ser substituído por outro do mesmo MODELO (Exemplo: Bermudas Jeans por Bermudas Jeans | Calças jeans por calça jeans), salvo se não houver mais em estoque a BENFICA LOJA poderá oferecer outro produto semelhante.<br>
&nbsp;5) CALÇADOS: Trocas somente por defeito de fábrica. <br>
&nbsp;6) RELOGIOS: 3 meses de garantia da máquina e da bateria - A garantia não cobre pulseira, vidro, pinos, arranhões, etc.
&nbsp;7) NÃO trocamos produtos que estejam com a etiqueta (tag) rasgadas, danificadas, riscadas ou rasuradas.
<center><br>NÃO TROCAMOS PEÇAS SEM ESTA NOTA.
<br>SÁBADO: TROCA DE NO MÁXIMO 5 PEÇAS.</center><br>

                                                </td>
                                              </tr>
                                              <tr bgcolor="#F5F5F5">
                                                <td class="tg-yw4l" colspan="4" style="font-size: 11px;">

<center><b>TATIANE (31) 99288-7558<br>
HUDSON (31) 98832-9894<br>
EDUARDO (31) 97500-1249<br>
INSTA: @BENFICA.LOJA</b></center>

                                                </td>
                                              </tr>
                                              <tfixo3>                                                  
                                            </table></center>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    
                </div>







            </div>

        </div>
    </div>