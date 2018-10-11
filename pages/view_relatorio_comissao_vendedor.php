


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
                    <h1 class="page-header">Relatório de Comissões Por Vendedor</h1>
                </div>
            </div>
            
            <div class="row">
         
                <!-- Barra de progresso da venda -->
                <div class="col-lg-12" >



                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Informe os critérios de referência para geração do relatório:
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" >

                                <form role="form" id="gerarRelatorioComissao" method="POST">

                                    <!-- /.row -->
                                    <div class="row">

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Vendedor:</label>
                                                <select class="form-control" id="listagemVendedores" name="idVendedor">
                                                    <option value="0"> TODOS </option>
                                                    <?php foreach ($listaVendedores as $key => $value): ?>
                                                        <option value="<?php echo $value['id_vendedor']; ?>"> <?php echo $value['descricao']; ?> </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>                                            

                                        </div>

                                        <div class="col-lg-1">

                                            <div class="form-group">
                                                <label>Mês:</label>
                                                <input type="text" class="form-control" name="mesReferencia" id="mesReferencia" minlength="1" maxlength="2" onkeypress="return SomenteNumero(event);" required >
                                            </div>

                                        </div>

                                        <div class="col-lg-1">

                                            <div class="form-group">
                                                <label>Ano:</label>
                                                <input type="text" class="form-control" name="anoReferencia" id="anoReferencia" minlength="4" maxlength="4" onkeypress="return SomenteNumero(event);" required >
                                            </div>

                                        </div>
                                        
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>Loja:</label>
                                                <select class="form-control" name="lojaBusca" id="lojaBusca">
                                                    <?php foreach ($listarLojas as $key => $value): ?>
                                                        <option value="<?php echo $value['id_loja']; ?>"> <?php echo $value['descricao']; ?> </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>                                            
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>Forma Pagamento:</label>
                                                <select class="form-control" name="idPagamento" id="idPagamento">
                                                    <option value="0"> Todos </option>
                                                    <?php foreach ($listaFormaPagamento['dados'] as $key => $value): ?>
                                                        <option value="<?php echo $value['idFormaPagamento']; ?>"> <?php echo $value['nomeFormaPagamento']; ?> </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>                                            
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>Tipo:</label>
                                                <select class="form-control" name="tipoRelatorio" id="tipoRelatorio">
                                                    <option value="0"> Resumido </option>
                                                    <option value="1"> Detalhado </option>
                                                </select>
                                            </div>
                                        </div> 

                                        <div class="col-lg-12">

                                            <center>
                                            <button type="submit" id="botaoGerarRelatorioComissao" class="btn btn-default">
                                                <i class="fa fa-search"></i>
                                                Gerar Relatório
                                            </button></center>


                                        </div>

                                        <div class="col-lg-12">
                                            <br>

                                            <div class="alert alert-danger" role="alert" id="ErroGerarRelatorioComissao" style="display: none;">
                                                <strong>Erro: </strong> Não foi localizada nenhuma venda para a pesquisa realizada.
                                            </div>                                    

                                        </div>
                                    </div>
                                    </form> </div></div>


                    <div class="alert alert-danger" role="alert" id="ErroGeracaoRelatorioComissao" style="display: none;">
                    </div>

                    <div class="panel panel-default" id="sessaoRelatorioComissao" style="display: none;" >

                    <div class="iconeFixo">
                        <a href="javascript:void(0)" onclick="printDivRelatorioComissao();" ><img src="library/impressora-2.png" alt="Imprimir Recibo" height="50" width="50"></a>
                    </div>
                    <div id="sessaoImpressaoRelatorio">

                        <div class="panel-heading">
                            <h4 style="text-align: center" id="tituloRel"><b>Relatório de Vendas Realizadas (Ativas)</b></h4>
                            <p style="text-align:center" id="identificacaoCriterioRelatorioComissao" >EDUARDO (04/2018)</p>
                            <center>
                                <button class="btn btn-success" id="gerarExcel" >
                                    <i class="fa fa-file-excel-o"></i>
                                    Gerar Excel
                                </button> 
                            </center>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">

 

                            <div class="table-responsive" id="relatorioResumido">
                                <table class="table table-striped table-bordered table-hover" id="tabelaComissaoResumida">
                                    <thead>

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
                            
                        </div>
                    </div>
                        <!-- /.panel-body -->
                    </div>