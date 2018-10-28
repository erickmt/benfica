
<?php

	require_once "../library/conexao.php";
	require_once "../controller/controller_venda.php";
	require_once "../controller/controller_sessao.php";
	require_once "../controller/controller_relatorio.php";
	require_once "../controller/controller_consignado.php";
	require_once "../controller/controller_cadastro.php";

	require_once "../model/model_cliente.php";
	require_once "../model/model_vendedor.php";
	require_once "../model/model_tipoproduto.php";
	require_once "../model/model_produto.php";
	require_once "../model/model_formapagamento.php";
	require_once "../model/model_perfilcliente.php";
	require_once "../model/model_venda.php";
	require_once "../model/model_log.php";
	require_once "../model/model_itens_de_venda.php";
	require_once "../model/model_formaspagamentovenda.php";
	require_once "../model/model_usuario.php";
	require_once "../model/model_funcionario.php";

	if(isset($_POST) && !empty($_POST))
	{

		//Mapeamento dos controllers e métodos
		switch ($_POST['nomeController']) {


			case 'Consignado':
				$Consignado = new Consignado($conexao);			

				switch ($_POST['nomeMetodo']) {

					case 'localizarFuncionario':
						$retorno = $Consignado->localizarFuncionario($_POST['nomeCompletoPesquisa'],$_POST['numeroIdentidadePesquisa']);
						break;								

					case 'localizarFuncionarioPorId':
						$retorno = $Consignado->localizarFuncionarioPorId($_POST['idFuncionario']);
						break;						

					case 'listarItensConsignadoSessao':
						$retorno = $Consignado->listarItensConsignadoSessao();
						break;								

					case 'devolveConsignado':
						$retorno = $Consignado->devolveConsignado($_POST['produtoSelecionado'],$_POST['quantidade']);
						break;								

					default:
						$retorno = array("resultado" => "erro");
						break;
				}
				break;


			case 'Relatorio':
				$Relatorio = new Relatorio($conexao);			

				switch ($_POST['nomeMetodo']) {

					case 'gerarRelatorioComissao':
						$retorno = $Relatorio->gerarRelatorioComissao($_POST['idVendedor'], $_POST['nomeVendedor'], $_POST['mesReferencia'], $_POST['anoReferencia'], $_POST['lojaBusca'], $_POST['idPagamento']);
						break;
					
					case 'gerarRelatorioResumidoComissao':
						$retorno = $Relatorio->gerarRelatorioResumidoComissao($_POST['idVendedor'], $_POST['mesReferencia'], $_POST['anoReferencia'], $_POST['lojaBusca'], $_POST['idPagamento']);
						break;

					case 'imprimir':
						$retorno = $Relatorio->imprimir($_POST['html']);
						break;

					case 'gerarRelatorioFinanceiro':
						$retorno = $Relatorio->gerarRelatorioFinanceiro($_POST['dataInicial'], $_POST['dataFinal'], $_POST['lojaBusca']);
						break;	

					case 'gerarRelatorioPecas':
						$retorno = $Relatorio->gerarRelatorioPecas($_POST['tipoProduto'],$_POST['dataInicial'], $_POST['dataFinal'],$_POST['produtoSelecionado'],$_POST['lojaBuscaPeca'], $_POST['tipoRelatorio']);
						break;

					case 'gerarRelatorioConsignado':
						$retorno = $Relatorio->gerarRelatorioConsignado($_POST['cliente'], $_POST['tipoProduto'],$_POST['dataInicial'], $_POST['dataFinal'],$_POST['produtoSelecionado'],$_POST['lojaBusca'],$_POST['devolvidas']);
						break;

					case 'gerarRelatorioPecas':
						$retorno = $Relatorio->gerarRelatorioPecas($_POST['tipoProduto'],$_POST['dataInicial'], $_POST['dataFinal'],$_POST['produtoSelecionado'],$_POST['lojaBuscaPeca'], $_POST['tipoRelatorio']);
						break;

					case 'gerarRelatorioPecasCliente':
						$retorno = $Relatorio->gerarRelatorioPecasCliente($_POST['nomeCliente'],$_POST['dataInicialCliente'], $_POST['dataFinalCliente'],$_POST['lojaBuscaCliente']);
						break;

					case 'RelatorioPecasCliente':
						$retorno = $Relatorio->RelatorioPecasCliente($_POST['idCliente'],$_POST['dtInicial'], $_POST['dtFinal'],$_POST['lojaBuscaCliente']);
						break;

					case 'gerarRelatorioCaixa':
						$lojaBusca = 0;
						if(isset($_POST["lojaBusca"])){
							$lojaBusca = $_POST["lojaBusca"];
						}
						$retorno = $Relatorio->gerarRelatorioCaixa($_POST['dataInicial'], $_POST['dataFinal'], $lojaBusca);
						break;									

					case 'gerarRelatorioFaturamento':
						$retorno = $Relatorio->gerarRelatorioFaturamento($_POST['dataInicial'], $_POST['dataFinal'], $_POST['lojaBusca']);
						break;

					case 'gerarRelatorioFaturamentoAno':
						$retorno = $Relatorio->gerarRelatorioFaturamentoAno($_POST['ano'], $_POST['lojaBusca2']);
						break;
						
					default:
						$retorno = array("resultado" => "erro");
						break;
				}
				break;



			case 'Sessao':
				$Sessao = new Sessao($conexao);			

				switch ($_POST['nomeMetodo']) {

					case 'criar':
						$retorno = $Sessao->criar($_POST['login'],$_POST['senha']);
						break;

					case 'verificaPermissaoExclusaoVenda':
						$retorno = $Sessao->verificaPermissaoExclusaoVenda();
						break;						

					case 'verificaPermissaoVenderConsignado':
						$retorno = $Sessao->verificaPermissaoVenderConsignado();
						break;	

					case 'autenticarAdministradorSubGerente':
						$retorno = $Sessao->autenticarAdministradorSubGerente($_POST['login'],$_POST['senha']);
						break;										
					
					case 'alterarLojaBusca':
						$retorno = $Sessao->alterarLojaBusca($_POST['lojaSelecionada']);
						break;

					default:
						$retorno = array("resultado" => "erro");
						break;
				}
				break;
				
			case 'Cadastro':
				$Cadastro = new Cadastro($conexao);			

				switch ($_POST['nomeMetodo']) {

					case 'cadastroCliente':
						$retorno = $Cadastro->cadastroCliente($_POST['nome'], $_POST['identidade'], $_POST['cpf'], $_POST['orgao_expeditor'], $_POST['data_nascimento'], $_POST['mae'], $_POST['vendedor'], $_POST['telefone'], $_POST['endereco'], $_POST['bairro'], $_POST['cep'], $_POST['cidade'], $_POST['uf'], $_POST['pais'], $_POST['observacao']);
						break;

					case 'alterarSituacao':
						$retorno = $Cadastro->alterarSituacao($_POST['idCliente']);
						break;

					case 'atualizaDadosCliente':
						$retorno = $Cadastro->atualizaDadosCliente($_POST['cpfClienteSelecionado'], $_POST['cepClienteSelecionado'], $_POST['ruaClienteSelecionado'],$_POST['numeroClienteSelecionado'],$_POST['bairroClienteSelecionado'],$_POST['cidadeClienteSelecionado'],$_POST['ufClienteSelecionado'],$_POST['emailClienteSelecionado']);
						break;
				}
				break;	

			case 'Venda':
				$Venda = new Venda($conexao);

				switch ($_POST['nomeMetodo']) {

					case 'trocaPerfil':
						$retorno = $Venda->trocaPerfil($_POST['id_selecionado']);
						break;

					case 'visualizarNota':
						$retorno = $Venda->visualizarNota($_POST['chave']);
						break;

					case 'listarPedidos':
						$retorno = $Venda->listarPedidos($_POST['nomeCliente'], $_POST['id_venda'], $_POST['dataInicial'], $_POST['dataFinal'], $_POST['lojaBusca'], $_POST['situacao'], $_POST['idPagamento'], $_POST['multiplasFormas']);
						break;

					case 'emitirNota':
						$retorno = $Venda->emitirNota($_POST['id']);
						break;
						
					case 'novoPedido':
						$retorno = $Venda->novoPedido($_POST['id']);
						break;

					case 'confereCaixa':
						$retorno = $Venda->confereCaixa();
						break;

					case 'movimentaCaixa':
						$retorno = $Venda->movimentaCaixa($_POST['valor'],$_POST['desc'], $_POST['lojaBusca']);
						break;

					case 'localizarCliente':
						$lojaSelecionada = 0;
						if(isset($_POST["lojaVenda"])){
							$lojaSelecionada = $_POST["lojaVenda"];
						}
						$retorno = $Venda->localizarCliente($_POST['nomeCompletoPesquisa'],$_POST['numeroIdentidadePesquisa'], $lojaSelecionada);
						break;

					case 'ajusteValorProdutosConsignado':
						$retorno = $Venda->ajusteValorProdutosConsignado($_POST['indicadorConsignado']);
						break;						

					case 'localizarClientePorId':
						$retorno = $Venda->localizarClientePorId($_POST['idCliente']);
						break;						

					case 'localizarProdutoPorTipo':
						$retorno = $Venda->localizarProdutoPorTipo($_POST['idTipoProduto'], $_POST['perfilCliente'], $_POST['modeloProduto']);
						break;	

					case 'localizarProdutoRelatorioPorTipo':
						$retorno = $Venda->localizarProdutoRelatorioPorTipo($_POST['idTipoProduto'], $_POST['perfilCliente'], $_POST['modeloProduto']);
						break;											

					case 'buscarPrecoProduto':
						$retorno = $Venda->buscarPrecoProduto($_POST['idProduto'], $_POST['perfilCliente']);
						break;	

					case 'alterarCreditoCliente':
						$retorno = $Venda->alterarCreditoCliente($_POST['novoCredito']);
						break;
						
					case 'buscarDadosPasso03':
						$retorno = $Venda->buscarDadosPasso03($_POST['idVendedor'], $_POST['indicadorConsignado']);
						break;		

					case 'adicionarFormaPagamento':
						$retorno = $Venda->adicionarFormaPagamento($_POST['valorFormaPagamento'], $_POST['idFormaPagamento'], $_POST['nomeFormaPagamento'], $_POST['indicadorConsiderarTaxas'], $_POST['quantidadeParcelas']);
						break;		

					case 'listarFormasDePagamento':
						$retorno = $Venda->listarFormasDePagamento();
						break;			

					case 'excluirFormaPagamento':
						$retorno = $Venda->excluirFormaPagamento($_POST['idFormaPagamento']);
						break;
													
						
					case 'adicionarItensVendaSessao':
						$retorno = $Venda->adicionarItensVendaSessao($_POST['nomeProduto'],$_POST['nomeRealProduto'],$_POST['quantidadeProduto'],$_POST['pesoTotal'],$_POST['valorUnitarioProduto'], $_POST['valorAtacado'], $_POST['valorVarejo'], $_POST['modeloProduto']);
						break;

					case 'listarItensVendaSessao':
						$retorno = $Venda->listarItensVendaSessao($_POST['valorDeslocamento']);
						break;

					case 'excluirItemVendaSessao':
						$retorno = $Venda->excluirItemVendaSessao($_POST['idProduto']);
						break;											
					
					case 'editarItemVendaSessao':
						$retorno = $Venda->editarItemVendaSessao($_POST['idProduto'], $_POST['novaQuantidade']);
						break;

					case 'listarResumoPagamento':
						$retorno = $Venda->listarResumoPagamento();
						break;					

					case 'concluirVenda':
						$retorno = $Venda->concluirVenda();
						break;

					case 'imprimirCupom':
						$retorno = $Venda->imprimirCupom($_POST['idVenda']);
						break;					

					case 'iniciarNovaVenda':
						$retorno = $Venda->iniciarNovaVenda();
						break;				

					case 'gerarReciboPasso04':
						$retorno = $Venda->gerarReciboPasso04();
						break;			

					case 'pesquisarVenda':
						$retorno = $Venda->pesquisarVenda($_POST['codigoVendaPesquisa'], $_POST['nomeCompletoPesquisa'], $_POST['numeroIdentidadePesquisa']);
						break;																		

					case 'excluirVenda':
						$retorno = $Venda->excluirVenda($_POST['idVenda']);
						break;									

					default:
						$retorno = array("resultado" => "erro");
						break;
				}
				break;	


			default:
				$retorno = array("resultado" => "erro");
				break;
		}
		echo json_encode($retorno);
	}

?>