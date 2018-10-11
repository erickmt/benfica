<?php

/**
 * Venda
 *
 * Operações relacionadas a página de Venda
 * 
 */
class Venda {

	private $conexao;

	function Venda($conexao)
	{
		$this->conexao = $conexao;
	}

	function movimentaCaixa($valor, $desc){
		session_start();
		$model_venda = new Model_Venda($this->conexao);
		$model_venda->movimentaCaixa($valor, $desc);
	}

	function confereCaixa(){
		session_start();
		$model_venda = new Model_Venda($this->conexao);
		$resultado  = $model_venda->confereCaixa();
		$retorno 	= array('resultado' => $resultado);
		return $retorno;
	}

	function trocaPerfil($id_selecionado){
		session_start();
		$model_venda = new Model_Venda($this->conexao);
		$resultado  = $model_venda->trocaPerfil($id_selecionado);
		$retorno 	= array('resultado' => $resultado);
		$this->redefinePerfilCliente(false);
		return $retorno;
	}

	function buscarPrecoProduto($idProduto, $perfilCliente)
	{
		session_start();
		$model_produto = new Model_Produto($this->conexao);
		$preco_produto = $model_produto->obterPrecoProduto($idProduto);

		//Tratamento de erro na busca do produto
		if($preco_produto['indicador_erro'] == 1 || $preco_produto['dados'] == null)
		{

			$html = "                 <center>
	                                        <b>[1] Ocorreu algum erro inesperado na aplicação. Reinicie a venda.</b>
	                                  </center>";

	        $resultado = 'erro';	     
	        $dados 	   = null;
		    $retorno   = array('resultado' => $resultado, 'html' => $html, 'dados' => $dados);
		    return $retorno;	
		}

		// Verifica o preço de acordo com o perfil do cliente
		if($_SESSION['usuario']['cliente']['idPerfilCliente'] == 1)
			$preco = $preco_produto['dados']['preco_varejo'];
		else 
			$preco = $preco_produto['dados']['preco_atacado'];

		$resultado  = 'sucesso';
		$retorno 	= array('resultado' => $resultado, 'preco' => $preco, 'precoVarejo' => $preco_produto['dados']['preco_varejo'], 'precoAtacado' => $preco_produto['dados']['preco_atacado'], 'peso' => $preco_produto['dados']['peso']);

		return $retorno;
	}
	
	function localizarProdutoPorTipo($idTipoProduto, $perfilCliente, $modeloProduto)
	{
		session_start();
		$model_produto = new Model_Produto($this->conexao);

		//Se o tipo não for definido, mostra todos os produtos
		if($idTipoProduto == 0)
			$produtos = $model_produto->listarProdutos(false, $modeloProduto);
		else 
			$produtos = $model_produto->listarProdutos($idTipoProduto, $modeloProduto);

		//Tratamento de erro na busca do produto
		if($produtos['indicador_erro'] == 1 || $produtos['dados'] == null)
		{

			$html = "                 <center>
	                                        <b>[2] Ocorreu algum erro inesperado na aplicação. Reinicie a venda.</b>
	                                  </center>";

	        $resultado = 'erro';	     
	        $dados 	   = 'inexiste';
		    $retorno   = array('resultado' => $resultado, 'html' => $html, 'dados' => $dados);  

		    return $retorno;	
		}

		$dados = array();
		for($i=0; $i< count($produtos['dados']); $i++)
		{
			$produtoUnidade = array('id_produto' => $produtos['dados'][$i]['id_produto'], 'nome_produto' => $produtos['dados'][$i]['nome_produto'], 'precoVarejo' => $produtos['dados'][$i]['preco_varejo'], 'precoAtacado' => $produtos['dados'][$i]['preco_atacado'], 'peso' => $produtos['dados'][$i]['peso']);

			if($_SESSION['usuario']['cliente']['idPerfilCliente'] == 1)
				$produtoUnidade['preco'] = $produtos['dados'][$i]['preco_varejo'];	
			else 
				$produtoUnidade['preco'] = $produtos['dados'][$i]['preco_atacado'];	
			
			$dados[] = $produtoUnidade;
		}

		$resultado  = 'sucesso';
		$retorno 	= array('resultado' => $resultado, 'dados' => $dados);

		return $retorno;
	}

	function localizarClientePorId($idCliente)
	{

		//Classe de persistencia da tabela cliente
		$model_cliente      = new Model_Cliente($this->conexao);
		//Classe de persistencia da tabela TipoProduto
		$model_tipo_produto = new Model_TipoProduto($this->conexao);
		//Classe de persistencia da tabela Produto
		$model_produto      = new Model_Produto($this->conexao);		


		$cliente	   = $model_cliente->buscarClientePorId($idCliente);
		$dados[] 	   = $cliente['dados'];

		//Inclui na sessão do cliente (idCliente e idPerfilCliente)
		session_start();
		$_SESSION['usuario']['cliente']['idCliente']       = $dados[0]['id_cliente'];
		$_SESSION['usuario']['cliente']['idPerfilCliente'] = $dados[0]['id_perfil'];
		$_SESSION['usuario']['cliente']['valorCredito']    = $dados[0]['valor_credito'];
		$_SESSION['usuario']['cliente']['indicadorConsignado'] = 'N';

		//Formata o valor do crédito para apresentação na tela
        $dados[0]['valor_credito'] = number_format($dados[0]['valor_credito'], 2, ',', '.');

		//Busca os tipos de produtos da persistencia
		$tiposProdutos = $model_tipo_produto->listarTiposProduto();
		if($tiposProdutos['indicador_erro'] == 1 || $tiposProdutos['dados'] == null)
		{

			$html = "                 <center>
	                                        <b>[3] Ocorreu algum erro inesperado na aplicação. Reinicie a venda.</b>
	                                  </center>";

	        $resultado = 'erro';	     
	        $dados 	   = null;
		    $retorno   = array('resultado' => $resultado, 'html' => $html, 'dados' => $dados);
		    return $retorno;	
		}
		array_push($dados, $tiposProdutos['dados']);


		//Busca os produtos da persistencia
		$produtos = $model_produto->listarProdutos(false);
		if($produtos['indicador_erro'] == 1 || $produtos['dados'] == null)
		{

			$html = "                 <center>
	                                        <b>[4] Ocorreu algum erro inesperado na aplicação. Reinicie a venda.</b>
	                                  </center>";

	        $resultado = 'erro';	     
	        $dados 	   = null;
		    $retorno   = array('resultado' => $resultado, 'html' => $html, 'dados' => $dados);
		    return $retorno;	
		}
		array_push($dados, $produtos['dados']);


		$resultado 	= "sucesso";
		$html 	 	= null;
		$retorno 	= array('resultado' => $resultado, 'html' => $html, 'dados' => $dados);


		//Destrói as variáveis de sessão para iniciar uma nova venda
		$_SESSION['usuario']['listaProdutos']   = array();
		$_SESSION['usuario']['formasPagamento'] = array();		


		return $retorno;
	}

    function localizarCliente($nomeCompleto, $numeroIdentidade)
    {

		//Classe de persistencia da tabela cliente
		$model_cliente      = new Model_Cliente($this->conexao);
		//Classe de persistencia da tabela TipoProduto
		$model_tipo_produto = new Model_TipoProduto($this->conexao);
		//Classe de persistencia da tabela Produto
		$model_produto      = new Model_Produto($this->conexao);		

    	//Verificação das variáveis enviadas ou não
    	if(empty($nomeCompleto) || trim($nomeCompleto) == '')
	     	$nomeCompleto     = false;
    	if(empty($numeroIdentidade) || trim($numeroIdentidade) == '' )
	     	$numeroIdentidade = false;

    	//Verificação das variáveis enviadas ou não
    	if( ($nomeCompleto == false) && ($numeroIdentidade == false) )
	     	return "O nome do cliente nem o número da identidade não foi informado";

	    //Busca o cliente por nome
		$id_clientes = $model_cliente->buscaIdClientePorNome($nomeCompleto, $numeroIdentidade);

		//Se ocorrer algum erro na busca do cliente
		if($id_clientes['indicador_erro'] == 1)
		{
			$html = "                 <center>
	                                        <b>[5] Ocorreu algum erro inesperado na aplicação. Reinicie a venda.</b>
	                                  </center>";

	        $resultado = 'erro';	     
	        $dados 	   = null;
		    $retorno   = array('resultado' => $resultado, 'html' => $html, 'dados' => $dados);
		    return $retorno;	        
		}

		//Tratamento de cliente não localizado
		$clientesLocalizados = array();


		if($id_clientes['dados'] <> null)
		{

			//Busca os dados básicos de cada cliente encontrado
			for($i=0; $i<count($id_clientes['dados']); $i++)
			{
				$cliente = $model_cliente->buscarClientePorId($id_clientes['dados'][$i]['id_cliente']);
				$clientesLocalizados[] = $cliente['dados'];
			}					
			//Para este caso , inclui na sessão do cliente (idCliente e idPerfilCliente)
			if(count($clientesLocalizados) == 1)
			{		
					session_start();
					$_SESSION['usuario']['cliente']['idCliente']       = $clientesLocalizados[0]['id_cliente'];
					$_SESSION['usuario']['cliente']['idPerfilCliente'] = $clientesLocalizados[0]['id_perfil'];
					$_SESSION['usuario']['cliente']['valorCredito']    = $clientesLocalizados[0]['valor_credito'];	
					$_SESSION['usuario']['cliente']['indicadorConsignado'] = 'N';
			}			

		}



		//Nova inicialização da variável DADOS
		$dados = array();

	     //monta o html de retorno
	     if (count($clientesLocalizados) == 0)
	     {

			$html = "                 <center>
                                            <b>O cliente não foi localizado no catálogo de cadastro.</b><br><br>
                                      </center>";

            $resultado 	= 'alerta';
            $dados 		= null;
		    $retorno    = array('resultado' => $resultado, 'html' => $html, 'dados' => $dados);
		    return $retorno;	                    
	     }
	     else if (count($clientesLocalizados) == 1)
	     {
	     	$resultado = 'sucesso';
	     	$html      = null;
	     	$dados 	   = $clientesLocalizados;
	     }
	     else if (count($clientesLocalizados) > 1){

			$resultado = 'alerta';
			$dados 	   = $clientesLocalizados;


			$html = "<center>
			                    <b>Foram identificados mais de um cliente com os mesmos dados informados.<br>Selecione o cliente verídico e prossiga com a venda ou cadastre o novo cliente:</b><br><br>
								<div class='form-group'>
                                    <select class='form-control' id='id_cliente'>";

            //Trata todos os clientes retornados
            for($i=0; $i<count($clientesLocalizados);$i++)
            {
            		$html = $html."<option value = ".$clientesLocalizados[$i]['id_cliente'].">".$clientesLocalizados[$i]['nome']." - RG: ".$clientesLocalizados[$i]['rg']." - Telefone: ".$clientesLocalizados[$i]['telefone']."</option>";

            }

            $html = $html."</select>
                                    </div>
                                    <button type='button' class='btn btn-success' OnClick='prosseguirVendaClienteDuplicado()'>Prosseguir Venda</button>
                              </center>";

		    $retorno = array('resultado' => $resultado, 'html' => $html, 'dados' => $dados);
		    return $retorno;

	     }

		//Formata o valor do crédito para apresentação na tela
        $clientesLocalizados[0]['valor_credito'] = number_format($clientesLocalizados[0]['valor_credito'], 2, ',', '.');	     

		//Busca os tipos de produtos da persistencia
		$tiposProdutos = $model_tipo_produto->listarTiposProduto();
		if($tiposProdutos['indicador_erro'] == 1 || $tiposProdutos['dados'] == null)
		{

			$html = "                 <center>
	                                        <b>[6] Ocorreu algum erro inesperado na aplicação. Reinicie a venda.</b>
	                                  </center>";

	        $resultado = 'erro';	     
	        $dados 	   = null;
		    $retorno   = array('resultado' => $resultado, 'html' => $html, 'dados' => $dados);
		    return $retorno;	
		}
		array_push($dados, $tiposProdutos['dados']);


		//Busca os produtos da persistencia
		$produtos = $model_produto->listarProdutos(false);
		if($produtos['indicador_erro'] == 1 || $produtos['dados'] == null)
		{

			$html = "                 <center>
	                                        <b>[7] Ocorreu algum erro inesperado na aplicação. Reinicie a venda.</b>
	                                  </center>";

	        $resultado = 'erro';	     
	        $dados 	   = null;
		    $retorno   = array('resultado' => $resultado, 'html' => $html, 'dados' => $dados);
		    return $retorno;	
		}
		array_push($dados, $produtos['dados']);

		$resultado = 'sucesso';
	    $retorno   = array('resultado' => $resultado, 'html' => $html, 'dados' => $dados);

		//Destrói as variáveis de sessão para iniciar uma nova venda
		$_SESSION['usuario']['listaProdutos']   = array();
		$_SESSION['usuario']['formasPagamento'] = array();			    

	    return $retorno;
    }

    function alterarCreditoCliente($novoValorCredito)
    {

        //Formata o valor do crédito para o formato do php e assim ser possível realizar as operações sobre este valor
        $novoValorCredito 	= str_replace('.', '', $novoValorCredito);
        $novoValorCredito   = str_replace(',', '.', $novoValorCredito);	            	

    	//Validação do dado de entrada
    	if($novoValorCredito < 0)
    	{
			$resultado = 'Não é permitido incluir um valor de crédito abaixo de zero.';
			$retorno = array('resultado' => $resultado);
	    	return $retorno;	
    	}

		session_start();
    	$model_cliente = new Model_Cliente($this->conexao);

		// Altera o crédito no banco de dados
    	$retornoAlteracao = $model_cliente->alterarCreditoCliente($_SESSION['usuario']['cliente']['idCliente'],$novoValorCredito);

		// Se for gravado com sucesso
		if ($retornoAlteracao)
		{
				$_SESSION['usuario']['cliente']['valorCredito'] = $novoValorCredito;				
				$resultado = 'sucesso';
		}
		else
		{
			$resultado = 'Ocorreu um erro ao alterar o crédito do cliente';
		}

		//Retorna para o programa chamador
		$retorno = array('resultado' => $resultado);
	    return $retorno;			
	}
	
    function buscarDadosPasso03($idVendedor, $indicadorConsignado){

		session_start();

		/* Verifica se algum produto foi incluído na venda */
		// Pendencia pendente - remover o comentário do código abaixo -- somente para teste
		if(count($_SESSION['usuario']['listaProdutos']) == 0)
			return array('resultado' => 'erro', 'descricao' => 'Necessário incluir ao menos um produto para prosseguir a venda.');

		$_SESSION['usuario']['cliente']['idVendedor'] 			= $idVendedor;
		$_SESSION['usuario']['cliente']['indicadorConsignado'] 	= $indicadorConsignado;

		//Apaga as formas de pagamento se houver
		$_SESSION['usuario']['formasPagamento'] = array();

    	/* Buscar as formas de pagamento*/
    	$model_forma_pagamento = new Model_FormaPagamento($this->conexao);

		$formas_pagamento = $model_forma_pagamento->listarFormasPagamento();
		if($formas_pagamento['indicador_erro'] == 1 || $formas_pagamento['dados'] == null)
		{
		    return array('resultado' => 'erro', 'descricao' => 'Ocorreu um erro na busca às formas de pagamento cadastradas no sistema.');
		}

		//O valor inicial sugerido é o valor total da venda
		$valorAPagar     = $_SESSION['usuario']['cliente']['precoTotal'];

		// Verifica se o valor da venda informado é menor ou igual as valores já definidos nas outras formas de pagamento
		for($i=0; $i < count($_SESSION['usuario']['formasPagamento']); $i++)
		{
			$valorAPagar = $valorAPagar - $_SESSION['usuario']['formasPagamento'][$i]['valorVenda'];
		}	

		// Tratamento especial para venda consignado
		if ($_SESSION['usuario']['cliente']['indicadorConsignado'] == 'S')
			$valorAPagar = 0;

		//Sugere um novo valor para incluir uma nova forma de pagamento - formata para ser apresentado
		$valorAPagar     = number_format($valorAPagar, 2, ",", ".");

		$resultado = 'sucesso';
	    $retorno   = array('resultado' => $resultado, 'dados' => array($formas_pagamento['dados'], $valorAPagar));

		return $retorno;
    }

    function adicionarFormaPagamento($valorVenda, $idFormaPagamento, $nomeFormaPagamento, $indicadorConsiderarTaxas, $quantidadeParcelas)
    {

        //Formata o valor da venda para o formato do php e assim ser possível realizar as operações sobre este valor
        $valorVenda    = str_replace('.', '', $valorVenda);
        $valorVenda    = str_replace(',', '.', $valorVenda);	            	

		session_start();
		$valorAPagar    = $_SESSION['usuario']['cliente']['precoTotal'];

		$model_formapagamento   = new Model_FormaPagamento($this->conexao);

		// Verifica se o valor da venda informado é menor ou igual as valores já definidos nas outras formas de pagamento
		for($i=0; $i < count($_SESSION['usuario']['formasPagamento']); $i++)
		{
			$valorAPagar = $valorAPagar - $_SESSION['usuario']['formasPagamento'][$i]['valorVenda'];
		}		

		$valorVenda  = number_format($valorVenda, 2, '.', '');	
		$valorAPagar = number_format($valorAPagar, 2, '.', '');	

		if($valorVenda > $valorAPagar)
		{
			$descricaoErro = "O valor informado (".number_format($valorVenda, 2, ",", ".").") é superior ao valor total da venda. O valor restante a ser pago é R$ ".number_format($valorAPagar, 2, ',', '.').".";
			$retorno 	   = array('resultado' => 'erro', 'descricao' => $descricaoErro);
			return $retorno;
		}

		// Se o valor total da venda já tiver suas formas de pagamento definidas , não permite a inclusão de uma nova forma de pagamento
		if($valorAPagar == 0)
		{
			$descricaoErro = "Não é possível incluir uma nova forma de pagamento, uma vez que não há valores pendentes.";
			$retorno 	   = array('resultado' => 'erro', 'descricao' => $descricaoErro);
			return $retorno;
		}		


		// Busca a taxa da forma de pagamento
		$formaPagamento = $model_formapagamento->listarTaxaFormaPagamento($idFormaPagamento);

		// Se ocorrer algum erro ao buscar a taxa da forma de pagamento
		if($formaPagamento['indicador_erro'] == 1)
		{

			$retorno 	   = array('resultado' => 'erro', 'descricao' => 'Ocorreu um erro no cálculo da taxa da forma de pagamento.');
			return $retorno;
		}


		//TRATAMENTO DE CASOS QUE JÁ ENCONTRA UMA FORMA DE PAGAMENTO
		$encontrouForma = -1;

		//Percorre todos os produtos da lista. Se já existe algum produto igual na lista, soma a sua quantidade
		for($i=0; $i < count($_SESSION['usuario']['formasPagamento']); $i++)
		{

			if($_SESSION['usuario']['formasPagamento'][$i]['idFormaPagamento'] == $idFormaPagamento)
				$encontrouForma = $i;
		}

		//Se houver encontrado uma forma de pagamento semelhante, adiciona os valores
		if($encontrouForma != -1)
		{
			$_SESSION['usuario']['formasPagamento'][$encontrouForma]['valorVenda'] 					= $_SESSION['usuario']['formasPagamento'][$encontrouForma]['valorVenda'] + $valorVenda;
			$_SESSION['usuario']['formasPagamento'][$encontrouForma]['indicadorConsiderarTaxas'] 	= $indicadorConsiderarTaxas;
			$_SESSION['usuario']['formasPagamento'][$encontrouForma]['quantidadeParcelas'] 			= $quantidadeParcelas;

			
			if($indicadorConsiderarTaxas == 'S')
				// Calcula a nova porcentagem cobrada... perceba que o valor da venda mudou
				$valorFormaPagamento = ($formaPagamento['dados'] * $_SESSION['usuario']['formasPagamento'][$encontrouForma]['valorVenda']) + $_SESSION['usuario']['formasPagamento'][$encontrouForma]['valorVenda'];
			else 
				$valorFormaPagamento = $_SESSION['usuario']['formasPagamento'][$encontrouForma]['valorVenda'];

			$_SESSION['usuario']['formasPagamento'][$encontrouForma]['valorFormaPagamento']			= $valorFormaPagamento;			
		}

		//Se não tiver nenhuma forma de pagamento semelhante, faz a inclusão
		else
		{
			if($indicadorConsiderarTaxas == 'S')
				$valorFormaPagamento = ($formaPagamento['dados'] * $valorVenda) + $valorVenda;
			else 
				$valorFormaPagamento = $valorVenda;

			$_SESSION['usuario']['formasPagamento'][] = array('idFormaPagamento' => $idFormaPagamento, 'nomeFormaPagamento' => $nomeFormaPagamento, 'valorVenda' => $valorVenda, 'indicadorConsiderarTaxas' => $indicadorConsiderarTaxas, 'quantidadeParcelas' => $quantidadeParcelas, 'valorFormaPagamento' => $valorFormaPagamento);
		}

		$valorAPagar    = $_SESSION['usuario']['cliente']['precoTotal'];

		// Verifica se o valor da venda informado é menor ou igual as valores já definidos nas outras formas de pagamento
		for($i=0; $i < count($_SESSION['usuario']['formasPagamento']); $i++)
		{
			$valorAPagar = $valorAPagar - $_SESSION['usuario']['formasPagamento'][$i]['valorVenda'];
		}		

		if($valorAPagar < 0.00 )
			$valorAPagar = 0.00;

		// Tratamento especial para venda consignado
		if ($_SESSION['usuario']['cliente']['indicadorConsignado'] == 'S')
			$valorAPagar = 0;		

		$novo_valor_sugerido = number_format($valorAPagar, 2, ',', '.');



		$resultado  = 'sucesso';
		$retorno 	= array('resultado' => $resultado, 'novo_valor_sugerido' => $novo_valor_sugerido);

		return $retorno;

    }

	function listarFormasDePagamento()
	{
		session_start();
		$formasPagamento = array();
		for($i=0; $i<count($_SESSION['usuario']['formasPagamento']); $i++)
		{
			$forma = array();

			$forma['idFormaPagamento'] 		= $_SESSION['usuario']['formasPagamento'][$i]['idFormaPagamento'];
			$forma['nomeFormaPagamento'] 	= $_SESSION['usuario']['formasPagamento'][$i]['nomeFormaPagamento'];
			$forma['valorVenda'] 			= number_format($_SESSION['usuario']['formasPagamento'][$i]['valorVenda'], 2, ',', '.');
			$forma['valorFormaPagamento'] 	= number_format($_SESSION['usuario']['formasPagamento'][$i]['valorFormaPagamento'], 2, ',', '.');

			$formasPagamento[] = $forma;
		}

		$retorno 			= array('resultado' => 'sucesso', 'formasPagamento' => $formasPagamento);
		return $retorno;			
	}

	function excluirFormaPagamento($idFormaPagamento)
	{


		session_start();	
		$aux 						= 0;
		$novaListaFormasPagamento 	= array();

		$novoValorAPagar            = 0;
		$totalPago  				= 0;

		//Percorre todos os produtos da lista para recálculo
		for($i=0; $i < count($_SESSION['usuario']['formasPagamento']); $i++)
		{
			if ($_SESSION['usuario']['formasPagamento'][$i]['idFormaPagamento'] != $idFormaPagamento)
			{
				$novaListaFormasPagamento[] = $_SESSION['usuario']['formasPagamento'][$i];
				//$totalPago 					= $totalPago + $_SESSION['usuario']['formasPagamento'][$i]['valorFormaPagamento']; 
				$totalPago 					= $totalPago + $_SESSION['usuario']['formasPagamento'][$i]['valorVenda']; 
			}
		}

		//Armazena as novas formas de pagamento da venda
		$_SESSION['usuario']['formasPagamento'] = $novaListaFormasPagamento;

		//Busca o valor que ainda precisa ser definido
		$novoValorAPagar = $_SESSION['usuario']['cliente']['precoTotal'] - $totalPago;

		// Tratamento especial para venda consignado
		if ($_SESSION['usuario']['cliente']['indicadorConsignado'] == 'S')
			$novoValorAPagar = 0;		

		$retorno = array('resultado' => 'sucesso', 'novo_valor_sugerido' => number_format($novoValorAPagar, 2, ',', '.'));
		return $retorno;		
	}

	function adicionarItensVendaSessao($idProduto, $nomeProduto, $quantidadeProduto, $pesoTotal, $valor, $valorAtacado, $valorVarejo, $modelo)
	{
		
		$model_produto       = new Model_Produto($this->conexao);

		// Retorna false se não encontrar a quantidade solicitada do produto em estoque
		// Pendente: remover o comentáiro das linhas abaixo... somente para facilitar os testes
		
		if(!$model_produto->verificarQuantidadeEmEstoque($idProduto, $quantidadeProduto))
		{
			$resultado  = 'Quantidade desejada do produto indisponível no estoque.';
			$retorno 	= array('resultado' => $resultado);
			return $retorno;
		}

		//Adequa o formato dos valores ao formato do PHP para futuras manipulações
        $valor 			= str_replace('.', '', $valor);
        $valor 			= str_replace(',', '.', $valor);		
        $valorAtacado 	= str_replace('.', '', $valorAtacado);
        $valorAtacado 	= str_replace(',', '.', $valorAtacado);		
        $valorVarejo 	= str_replace('.', '', $valorVarejo);
        $valorVarejo 	= str_replace(',', '.', $valorVarejo);		      


		//Busca o peso do produto - unidade
		$pesoTotal      = $model_produto->buscarPesoProduto($idProduto);

		session_start();
		$quantidadeTotal 	= 0;
		$encontrouProduto 	= -1;		

		//Percorre todos os produtos da lista. Se já existe algum produto igual na lista, soma a sua quantidade
		for($i=0; $i < count($_SESSION['usuario']['listaProdutos']); $i++)
		{
			if($_SESSION['usuario']['listaProdutos'][$i]['idProduto'] == $idProduto)
				$encontrouProduto = $i;
		}


		//Se já tiver encontrado um produto semelhante na lista
		if($encontrouProduto >= 0)
		{
			$_SESSION['usuario']['listaProdutos'][$encontrouProduto]['quantidadeProduto'] = $_SESSION['usuario']['listaProdutos'][$encontrouProduto]['quantidadeProduto'] + $quantidadeProduto;

			// Se o preço informado for diferente do preço já cadastrado, atualiza o preço com o novo valor			
			if ($_SESSION['usuario']['listaProdutos'][$encontrouProduto]['valor'] != $valor)
			{
				if($_SESSION['usuario']['cliente']['indicadorConsignado'] == 'S')
				{
					$_SESSION['usuario']['listaProdutos'][$encontrouProduto]['valor'] = $valor + 2.0;
				}
				else 
				{
					$_SESSION['usuario']['listaProdutos'][$encontrouProduto]['valor'] = $valor;	
				}
			}

		}
		// Se não tiver encontrado nenhum produto semelhante na lista
		else{

			if($_SESSION['usuario']['cliente']['indicadorConsignado'] == 'S')
			{
				$valor 			= $valor + 2.0;
				$valorAtacado 	= $valorAtacado + 2.0;
				$valorVarejo 	= $valorVarejo + 2.0;
			}

			$_SESSION['usuario']['listaProdutos'][] = array('idProduto' => $idProduto, 'nomeProduto' => $nomeProduto, 'quantidadeProduto' => $quantidadeProduto, 'pesoTotal' => $pesoTotal, 'valor' => $valor, 'valorAtacado' => $valorAtacado, 'valorVarejo' => $valorVarejo);
		}


		// Redefine o perfil do cliente, de acordo com os itens da venda
		$this->redefinePerfilCliente();


		$retorno 	= array('resultado' => 'sucesso');
		return $retorno;
	}

	function listarItensVendaSessao($valorDeslocamento)
	{
        session_start();
        $listaApresentacao 	= array();
        $quantidadeTotal 	= 0;
        $pesoTotal 		 	= 0.0;
        $precoTotal 	 	= 0.0;

        //Formata o valor do deslocamento para o formato do php e assim ser possível realizar as operações sobre este valor
        $valorDeslocamento 	= str_replace('.', '', $valorDeslocamento);
        $valorDeslocamento  = str_replace(',', '.', $valorDeslocamento);	        

		//Percorre todos os produtos da lista para recálculo
		for($i=0; $i < count($_SESSION['usuario']['listaProdutos']); $i++)
		{

			$item = array();
			$item['idProduto'] 			= $_SESSION['usuario']['listaProdutos'][$i]['idProduto'];
			$item['nomeProduto'] 		= $_SESSION['usuario']['listaProdutos'][$i]['nomeProduto'];
			$item['quantidadeProduto'] 	= $_SESSION['usuario']['listaProdutos'][$i]['quantidadeProduto'];
			$item['valor'] 				= $_SESSION['usuario']['listaProdutos'][$i]['valor'];
			$item['valor']              = number_format($item['valor'], 2, ",", ".");
			$item['valorTotal'] 		= $_SESSION['usuario']['listaProdutos'][$i]['valor'] * $item['quantidadeProduto'];
			$item['valorTotal']         = number_format($item['valorTotal'], 2, ",", ".");
			$item['pesoTotal'] 			= $_SESSION['usuario']['listaProdutos'][$i]['pesoTotal'] * $item['quantidadeProduto'];
			$pesoTotal                  = $pesoTotal + $item['pesoTotal'];
			$item['pesoTotal']          = number_format($item['pesoTotal'], 2, ",", ".");
			$listaApresentacao[] 		= $item;
			$quantidadeTotal 	 		= $quantidadeTotal + $item['quantidadeProduto'];
			$precoTotal			 		= ($precoTotal + ($_SESSION['usuario']['listaProdutos'][$i]['valor'] * $item['quantidadeProduto']));
		}

		$precoTotal         		    = ($precoTotal + $valorDeslocamento) - $_SESSION['usuario']['cliente']['valorCredito'];
		$pesoTotal         			    = number_format($pesoTotal, 2, ",", ".");						
		if($precoTotal<0)
			$precoTotal = 0;

		$_SESSION['usuario']['cliente']['precoTotal']    		= $precoTotal;
		$_SESSION['usuario']['cliente']['valorDeslocamento']    = $valorDeslocamento;



		$precoTotal 		= number_format($precoTotal, 2, ",", ".");			

		$contabilizacao 	= array('quantidadeTotal' => $quantidadeTotal, 'precoTotal' => $precoTotal, 'pesoTotal' => $pesoTotal);
		$retorno 			= array('resultado' => 'sucesso', 'listaProdutos' => $listaApresentacao, 'idPerfilCliente' => $_SESSION['usuario']['cliente']['idPerfilCliente'], 'contabilizacao' => $contabilizacao);


		return $retorno;			
	}

	function excluirItemVendaSessao($idProduto)
	{

		session_start();	
		$aux 				= 0;
		$novaListaProdutos 	= array();

		//Percorre todos os produtos da lista para recálculo
		for($i=0; $i < count($_SESSION['usuario']['listaProdutos']); $i++)
		{
			if ($_SESSION['usuario']['listaProdutos'][$i]['idProduto'] != $idProduto)
				$novaListaProdutos[] = $_SESSION['usuario']['listaProdutos'][$i];
		}

		$_SESSION['usuario']['listaProdutos'] = $novaListaProdutos;

		//Redefine o perfil do cliente após a exclusão
		$this->redefinePerfilCliente(false);

		//Para este caso de exclusão, remove todas as formas de pagamento, se houver
		$_SESSION['usuario']['formasPagamento'] = array();

		$retorno = array('resultado' => 'sucesso');
		return $retorno;				
	}	

	function listarResumoPagamento(){

		session_start();	
		$valorTotalTaxas    = 0;
		$valorTotalProdutos = 0;
		$qtdaTotalProdutos  = 0;
		$taxas              = array();

		//Percorre todos os produtos da lista para calculo
		for($i=0; $i < count($_SESSION['usuario']['listaProdutos']); $i++)
		{
			$valorTotalProdutos			= $valorTotalProdutos + ($_SESSION['usuario']['listaProdutos'][$i]['valor'] * $_SESSION['usuario']['listaProdutos'][$i]['quantidadeProduto']);
			$qtdaTotalProdutos			= $qtdaTotalProdutos  + $_SESSION['usuario']['listaProdutos'][$i]['quantidadeProduto'];
		}

		//Percorre todas as formas de pagamento cadastradas
		for($i=0; $i < count($_SESSION['usuario']['formasPagamento']); $i++)
		{

			$forma['valor_taxa_forma_pagamento'] = $_SESSION['usuario']['formasPagamento'][$i]['valorFormaPagamento'] - $_SESSION['usuario']['formasPagamento'][$i]['valorVenda'];
			$forma['nome_forma_pagamento']       = $_SESSION['usuario']['formasPagamento'][$i]['nomeFormaPagamento'];
			$valorTotalTaxas                     = $valorTotalTaxas + $forma['valor_taxa_forma_pagamento'];
			$forma['valor_taxa_forma_pagamento'] = number_format($forma['valor_taxa_forma_pagamento'], 2, ",", ".");
			$taxas[] 		         			 = $forma;
		}

		$dados = array();

		$dados['valor_total_produtos'] 	   = number_format($valorTotalProdutos, 2, ",", ".");
		$dados['valor_total_deslocamento'] = number_format($_SESSION['usuario']['cliente']['valorDeslocamento'], 2, ",", ".");
		$dados['valor_total_credito']      = number_format($_SESSION['usuario']['cliente']['valorCredito'], 2, ",", ".");
		$dados['valor_total_taxas']        = number_format($valorTotalTaxas, 2, ",", ".");
		$dados['taxas']        			   = $taxas;
		$dados['valor_total_acrescimo_pdt']= $valorTotalTaxas / $qtdaTotalProdutos;
		$dados['valor_total_acrescimo_pdt']= number_format($dados['valor_total_acrescimo_pdt'], 2, ",", ".");
		$dados['valor_total']			   = ($valorTotalProdutos+ $_SESSION['usuario']['cliente']['valorDeslocamento']+$valorTotalTaxas)-$_SESSION['usuario']['cliente']['valorCredito'];

		if ($dados['valor_total'] < 0)		
			$dados['valor_total'] = 0;
		$dados['valor_total']		       = number_format($dados['valor_total'], 2, ",", ".");

		$retorno = array('resultado' => 'sucesso', 'dados' => $dados);
		return $retorno;				
	}

	function concluirVenda()
	{

		session_start();

		//Não permite concluir se ainda tiver alguma pendencia
		if(!$this->indicaPossibilidadeConclusao())
		{
			$retorno = array('resultado' => 'erro', 'descricao' => 'Não é possível concluir a venda, uma vez que ainda existem pendências a serem solucionadas.');
			return $retorno;
		}

		//Verifica novamente o perfil do cliente
		$this->redefinePerfilCliente(true);

		//Monta e grava os valores da venda
		$gravacaoVenda = $this->gravarVenda(true);

		if ($gravacaoVenda['retorno'] == 'erro')
			return array('resultado' => 'erro', 'descricao' => $gravacaoVenda['descricao']);


		$gravarItensVenda = $this->gravarItensVenda($gravacaoVenda['dados']['id_venda'], true);
		if ($gravarItensVenda['retorno'] == 'erro')
			return array('resultado' => 'erro', 'descricao' => $gravarItensVenda['descricao']);		

		//Inclui os produtos da venda
		$gravacaoVenda['dados']['itens_venda'] = $gravarItensVenda['dados'];

		$gravarFormas = $this->gravarFormasDePagamento($gravacaoVenda['dados']['id_venda'], true);
		if ($gravarFormas['retorno'] == 'erro')
			return array('resultado' => 'erro', 'descricao' => $gravarFormas['descricao']);		

		//Inclui as formas de pagamento da venda
		$gravacaoVenda['dados']['formas_pagamento']    = $gravarFormas['dados'];		
		$gravacaoVenda['dados']['valorCredito']        = number_format($_SESSION['usuario']['cliente']['valorCredito'], 2, ',', '.');
		$gravacaoVenda['dados']['valor_total_outros']  = number_format($gravacaoVenda['dados']['valor_total_outros'], 2, ',', '.');
		$gravacaoVenda['dados']['valor_total_taxas']   = number_format($gravacaoVenda['dados']['valor_total_taxas'], 2, ',', '.');


		//Buscar os dados do cliente para apresentar no recibo
		$Model_cliente = new Model_Cliente($this->conexao);
		$dadosCliente  = $Model_cliente->buscarDadosBasicosCliente($_SESSION['usuario']['cliente']['idCliente']);
		if ($dadosCliente['retorno'] == 'erro')
			return array('resultado' => 'erro', 'descricao' => $dadosCliente['descricao']);	

		$gravacaoVenda['dados']['nome']        = $dadosCliente['dados']['nome'];
		$gravacaoVenda['dados']['rg']          = $dadosCliente['dados']['rg'];
		$gravacaoVenda['dados']['telefone']    = $dadosCliente['dados']['telefone'];
		$gravacaoVenda['dados']['perfil']      = $dadosCliente['dados']['perfil'];
		$gravacaoVenda['dados']['dia']         = date("d/m/Y");


		$retorno = array('resultado' => 'sucesso', 'dados' => $gravacaoVenda['dados']);
		return $retorno;						
	}

	function indicaPossibilidadeConclusao()
	{

		$total 		 = 0.0;
		$valorAPagar = 0.0;

		//Verifica se algum valor da venda ainda não teve sua forma de pagamento definida
		for($i=0; $i < count($_SESSION['usuario']['formasPagamento']); $i++)
		{
			$total = $total + $_SESSION['usuario']['formasPagamento'][$i]['valorVenda'];
		}

		$valorAPagar = (float)$_SESSION['usuario']['cliente']['precoTotal'] - (float)$total;
    	$valorAPagar = round($valorAPagar, 3);

		if (!isset($_SESSION)) {
			session_start();
		}	    	

		// Tratamento especial para venda consignado
		if ($_SESSION['usuario']['cliente']['indicadorConsignado'] == 'S')
			$valorAPagar = 0.0;		    	

		//Se não puder concluir a venda , retorna false 
		if ($valorAPagar > 0.0)
			return false;
		else 
			return true;
	}

	function redefinePerfilCliente($atualizaBase = false)
	{
		//session_start();	

		if (!isset($_SESSION)) {
			session_start();
		}	   		

		$model_cliente       = new Model_Cliente($this->conexao);
		$model_perfilcliente = new Model_PerfilCliente($this->conexao);

		$quantidadeTotal     = 0;

		//Verifica a validade do cliente atacadista e atualiza a sessão
		if($model_cliente->indicadorClienteAtacadistaValido($_SESSION['usuario']['cliente']['idCliente']))
			$_SESSION['usuario']['cliente']['idPerfilCliente'] = 2;
		else 
			$_SESSION['usuario']['cliente']['idPerfilCliente'] = 1;		

		// Busca a quantidade total de produtos da venda
		for($i=0; $i < count($_SESSION['usuario']['listaProdutos']); $i++)
		{
				$quantidadeTotal = $quantidadeTotal + $_SESSION['usuario']['listaProdutos'][$i]['quantidadeProduto'];
		}					

		//Busca a quantidade mínima de produtos para o cliente se tornar um atacadista
		$quantidadeMinima = $model_perfilcliente->buscarQtdaMinimaProdutos(2);

		if ($quantidadeTotal >= $quantidadeMinima)
		{
			$_SESSION['usuario']['cliente']['idPerfilCliente'] = 2;
			if($atualizaBase)
				$this->atualizarPerfilCliente();
		}

		//Percorre todos os produtos da lista para recálculo, de acordo com o novo perfil do cliente
		for($i=0; $i < count($_SESSION['usuario']['listaProdutos']); $i++)
		{

			if($_SESSION['usuario']['cliente']['idPerfilCliente'] == 2)
			{
				if($_SESSION['usuario']['listaProdutos'][$i]['valor'] == $_SESSION['usuario']['listaProdutos'][$i]['valorVarejo'])
					$_SESSION['usuario']['listaProdutos'][$i]['valor'] = $_SESSION['usuario']['listaProdutos'][$i]['valorAtacado'];
			}
			else 
			{
				if($_SESSION['usuario']['listaProdutos'][$i]['valor'] == $_SESSION['usuario']['listaProdutos'][$i]['valorAtacado'])
					$_SESSION['usuario']['listaProdutos'][$i]['valor'] = $_SESSION['usuario']['listaProdutos'][$i]['valorVarejo'];

			}				
		}

	}

	function atualizarPerfilCliente()
	{
		$model_cliente = new Model_Cliente($this->conexao);
		//session_start();	
		return $model_cliente->atualizarPerfilCliente($_SESSION['usuario']['cliente']['idCliente'], $_SESSION['usuario']['cliente']['idPerfilCliente']);
	}

	//Gravar o registro na tabela de venda
	function gravarVenda($indicadorConclusao = false)
	{

		$model_venda           		= new Model_Venda($this->conexao);
		$model_formapagamento  		= new Model_FormaPagamento($this->conexao);
		$model_vendedor        		= new Model_Vendedor($this->conexao);
		$model_produto         		= new Model_Produto($this->conexao);
		$model_cliente         		= new Model_Cliente($this->conexao);

		$valorTotalProdutos 		= 0;
		$valorTotalTaxas    		= 0; 
		$valorTaxaPagaPeloCliente  	= 0;
		$valorTaxaPagaPeloVendedor 	= 0;


		//Percorre todas as formas de pagamento cadastradas para obter os valores das taxas pagas
		for($i=0; $i < count($_SESSION['usuario']['formasPagamento']); $i++)
		{
			$valorTotalTaxas            = $valorTotalTaxas + ($_SESSION['usuario']['formasPagamento'][$i]['valorFormaPagamento'] - $_SESSION['usuario']['formasPagamento'][$i]['valorVenda']);

			if($_SESSION['usuario']['formasPagamento'][$i]['valorFormaPagamento'] == $_SESSION['usuario']['formasPagamento'][$i]['valorVenda'])
			{

				$formaPagamento            = $model_formapagamento->listarTaxaFormaPagamento($_SESSION['usuario']['formasPagamento'][$i]['idFormaPagamento']);

				$valorTaxaPagaPeloVendedor = $valorTaxaPagaPeloVendedor + ($formaPagamento['dados'] * $_SESSION['usuario']['formasPagamento'][$i]['valorVenda']);//+$_SESSION['usuario']['formasPagamento'][$i]['valorVenda']);
			}
			else 
			{
				$valorTaxaPagaPeloCliente  = $valorTaxaPagaPeloCliente + (($_SESSION['usuario']['formasPagamento'][$i]['valorFormaPagamento'] - $_SESSION['usuario']['formasPagamento'][$i]['valorVenda']));
			}
		}

		//Monta o indicador de venda externa
		if($_SESSION['usuario']['cliente']['valorDeslocamento'] == 0)
			$indicadorExterno = 'N';
		else 
			$indicadorExterno = 'S';

		//Monta o indicador de taxas pagas pelo cliente
		if ($valorTaxaPagaPeloCliente == 0)
			$taxa_pelo_cliente = 'N';
		else 
			$taxa_pelo_cliente = 'S';



		/* CALCULO DA COMISSÃO DO VENDEDOR SOBRE A VENDA EM QUESTÃO */

		//Buscar a comissão fixa do vendedor
		$porcentagemComissao     = $model_vendedor->buscarPorcentagemComissao($_SESSION['usuario']['cliente']['idVendedor']);

		if($indicadorExterno == 'S')
			$porcentagemComissao = 0.02;

		$comissaoVendedor        = 0;
		$valorTotalCustoProduto  = 0;

		for($i=0; $i < count($_SESSION['usuario']['listaProdutos']); $i++)
		{
			//Se o produto tiver sido vendido para um valor igual ao valor definido, aplica a porcentagem parametrizada
			if(($_SESSION['usuario']['listaProdutos'][$i]['valor'] == $_SESSION['usuario']['listaProdutos'][$i]['valorAtacado']) || ($_SESSION['usuario']['listaProdutos'][$i]['valor'] == $_SESSION['usuario']['listaProdutos'][$i]['valorVarejo']))  
			{

				// Se varejista
				if($_SESSION['usuario']['cliente']['idPerfilCliente'] == 1)
					$comissaoVendedor = $comissaoVendedor +(($_SESSION['usuario']['listaProdutos'][$i]['valorVarejo'] - ($_SESSION['usuario']['listaProdutos'][$i]['valorAtacado'] + 2.0)) * $_SESSION['usuario']['listaProdutos'][$i]['quantidadeProduto']);
			
				// Se atacadista
				else 
					$comissaoVendedor = $comissaoVendedor + (($_SESSION['usuario']['listaProdutos'][$i]['valor'] * $porcentagemComissao)*$_SESSION['usuario']['listaProdutos'][$i]['quantidadeProduto']);

			}
			else 
			{

				$comissaoVendedor = $comissaoVendedor +(($_SESSION['usuario']['listaProdutos'][$i]['valor'] - ($_SESSION['usuario']['listaProdutos'][$i]['valorAtacado'] + 2.0)) * $_SESSION['usuario']['listaProdutos'][$i]['quantidadeProduto']);

				/* REGRA REMOVIDA A PEDIDO DO EDUARDO, USUÁRIO, EM 02/03/2018 
				if($_SESSION['usuario']['cliente']['idPerfilCliente'] == 2)
					$comissaoVendedor = $comissaoVendedor + (($_SESSION['usuario']['listaProdutos'][$i]['valor'] - ($_SESSION['usuario']['listaProdutos'][$i]['valorAtacado'] + 2.0)) * $_SESSION['usuario']['listaProdutos'][$i]['quantidadeProduto']);

				else
					$comissaoVendedor = $comissaoVendedor + (($_SESSION['usuario']['listaProdutos'][$i]['valor'] - ($_SESSION['usuario']['listaProdutos'][$i]['valorVarejo'] + 2.0)) * $_SESSION['usuario']['listaProdutos'][$i]['quantidadeProduto']);
				*/

			}

			//buscar o valor de custo de todos os produtos da venda
			$valorTotalCustoProduto = $valorTotalCustoProduto + ($model_produto->buscaValorCustoProduto($_SESSION['usuario']['listaProdutos'][$i]['idProduto']) * $_SESSION['usuario']['listaProdutos'][$i]['quantidadeProduto']);

			//Busca o valor total da venda em produtos
			$valorTotalProdutos		= $valorTotalProdutos + ($_SESSION['usuario']['listaProdutos'][$i]['valor'] * $_SESSION['usuario']['listaProdutos'][$i]['quantidadeProduto']);			
		}
		//Subtrai da comissão do vendedor a taxa da forma de pagamento que não foi paga pelo cliente
		$comissaoVendedor = $comissaoVendedor - $valorTaxaPagaPeloVendedor;


		$valorTotal = ($valorTotalProdutos + $valorTotalTaxas + $_SESSION['usuario']['cliente']['valorDeslocamento']) - $_SESSION['usuario']['cliente']['valorCredito'];
		if ($valorTotal < 0)
		{
			$novoValorCredito = abs($valorTotal);
			$valorTotal       = 0;
		}
		else
		{
			$novoValorCredito = 0;
		}
		
		//Se for uma venda do tipo consignada, a comissão será sempre 0
		if($_SESSION['usuario']['cliente']['indicadorConsignado'] == 'S')
			$comissaoVendedor = 0;
			

		/* Monta os valores a serem enviados a persistência */
		$dados = array();
		$dados['id_cliente'] 			= $_SESSION['usuario']['cliente']['idCliente'];
		$dados['id_vendedor'] 			= $_SESSION['usuario']['cliente']['idVendedor'];
		$dados['id_perfil'] 			= $_SESSION['usuario']['cliente']['idPerfilCliente'];		
		$dados['valor_credito_cliente'] = $_SESSION['usuario']['cliente']['valorCredito'];
		$dados['valor_total_pago'] 		= $valorTotal;
		$dados['valor_total_comissao'] 	= $comissaoVendedor;
		$dados['valor_total_taxas'] 	= $valorTotalTaxas;
		$dados['valor_total_outros'] 	= $_SESSION['usuario']['cliente']['valorDeslocamento'];
		$dados['valor_total_liquido'] 	= $valorTotal - ($valorTotalCustoProduto + $comissaoVendedor + $valorTotalTaxas + $_SESSION['usuario']['cliente']['valorDeslocamento']);
		$dados['indicador_externo']		= $indicadorExterno;
		$dados['indicador_consignado'] 	= $_SESSION['usuario']['cliente']['indicadorConsignado'];
		$dados['taxa_pelo_cliente'] 	= $taxa_pelo_cliente;
		$dados['valorTotalProdutos']    = number_format($valorTotalProdutos, 2, ",", ".");


		// Grava os dados na base de dados somente se for a conclusão definitiva da venda
		if($indicadorConclusao == true)
		{

			//chamar o método para gravar na base de dados - ele retorna o id da venda cadastrada
			$dados['id_venda']              = $model_venda->gravarVenda($dados);

			if($dados['id_venda'] == false)
				return array('retorno' => 'erro', 'descricao' => 'Ocorreu um erro na gravação da venda.');


			//Atualizar o valor do crédito do cliente
			$resultadoAlteracaoCredito = $model_cliente->alterarCreditoCliente($_SESSION['usuario']['cliente']['idCliente'], $novoValorCredito);
			if($resultadoAlteracaoCredito == false)
				return array('retorno' => 'erro', 'descricao' => 'Ocorreu um erro na atualização do crédito do cliente.');

			$reducaoEstoque = $model_produto->reduzirProdutosEstoque($_SESSION['usuario']['listaProdutos']);
			if($reducaoEstoque == false)
				return array('retorno' => 'erro', 'descricao' => 'Ocorreu um erro na atualização dos produtos no estoque.');


			$atualizacaoData = $model_cliente->atualizarDataUltimaCompra($_SESSION['usuario']['cliente']['idCliente'], 'atual' );
			if($atualizacaoData == false)
				return array('retorno' => 'erro', 'descricao' => 'Ocorreu um erro inesperado ao atualizar a data da última compra do cliente.');



		}
		else
			$dados['id_venda'] = '______________';


		//Chega a este ponto da execução se todas os dados tiverem sido gravados com sucesso
		return array('retorno' => 'sucesso', 'dados' => $dados);
	}

	function gravarItensVenda($idVenda, $indicadorGravacao = false)
	{
		$dados 					= array();
		$model_itens_de_venda   = new Model_itens_de_venda($this->conexao);
		$model_produto          = new Model_produto($this->conexao);

		//Percorre os itens da venda na sessão
		for($i=0; $i < count($_SESSION['usuario']['listaProdutos']); $i++)
		{
			$item = array();

			$item['id_venda']			= $idVenda;
			$item['id_item_de_venda']	= $i + 1;
			$item['id_produto']		    = $_SESSION['usuario']['listaProdutos'][$i]['idProduto'];
			$item['quantidade']			= $_SESSION['usuario']['listaProdutos'][$i]['quantidadeProduto'];
			$item['nomeProduto']		= $_SESSION['usuario']['listaProdutos'][$i]['nomeProduto'];
			$item['valorUnitario']		= $_SESSION['usuario']['listaProdutos'][$i]['valor'];
			$item['valorTotal']		    = $_SESSION['usuario']['listaProdutos'][$i]['valor'] * $item['quantidade'];
			$item['valorCusto']         = $model_produto->buscaValorCustoProduto($item['id_produto']);

			//Formata os valores para apresentação ao usuário
			$item['valorUnitarioSemFormatacao']      = $item['valorUnitario'];
			$item['valorUnitario']      = number_format($item['valorUnitario'], 2, ",", ".");
			$item['valorTotal']         = number_format($item['valorTotal'], 2, ",", ".");
			

			$dados[] = $item;
		}	

		//Grava na persistencia somente se tiver que ser gravado
		if ($indicadorGravacao == true)
		{

			$gravacao = $model_itens_de_venda->gravar($dados);

			//Verifica retorno da gravação
			if($gravacao == false)
				return array('retorno' => 'erro', 'descricao' => '[8] Ocorreu um erro inesperado ao gravar os itens da venda');
		}

		// Retorna o indicador de sucesso da aplicação
		return array('retorno' => 'sucesso', 'dados' => $dados);
	}

	function gravarFormasDePagamento($idVenda, $indicadorGravacaoBase = false)
	{
		$dados 					        = array();
		$model_formas_pagamento_venda   = new Model_FormasPagamentoVenda($this->conexao);

		//Percorre os itens da venda na sessão
		for($i=0; $i < count($_SESSION['usuario']['formasPagamento']); $i++)
		{
			$item = array();

			$item['id_venda']					= $idVenda;
			$item['id_forma_pagamento_venda']	= $i + 1;
			$item['id_forma_pagamento']	        = $_SESSION['usuario']['formasPagamento'][$i]['idFormaPagamento'];
			$item['valor_pago']		  	        = $_SESSION['usuario']['formasPagamento'][$i]['valorFormaPagamento'];
			$item['quantidade_parcelas']		= $_SESSION['usuario']['formasPagamento'][$i]['quantidadeParcelas'];
			$item['valor_acrescimo']			= $_SESSION['usuario']['formasPagamento'][$i]['valorFormaPagamento'] - $_SESSION['usuario']['formasPagamento'][$i]['valorVenda'];
			$item['nome_forma']	       		    = $_SESSION['usuario']['formasPagamento'][$i]['nomeFormaPagamento'];

			//Formata o valor para ser apresentado ao usuário
			$item['valor']		  	            = number_format($item['valor_pago'], 2, ",", ".");
			$item['valor_acrescimo']		  	= number_format($item['valor_acrescimo'], 2, ",", ".");
			$dados[] = $item;
		}	

		if($indicadorGravacaoBase == true)
		{

			//Grava na persistencia
			$gravacao = $model_formas_pagamento_venda->gravar($dados);

			//Verifica retorno da gravação
			if($gravacao == false)
				return array('retorno' => 'erro', 'descricao' => '[9] Ocorreu um erro inesperado ao gravar as formas de pagamento da venda');
		}

		// RETORNA OS DADOS 
		return array('retorno' => 'sucesso', 'dados' => $dados);
	}	


	function iniciarNovaVenda()
	{

		session_start();

		//Destrói todas as variáveis de sessão para iniciar uma nova venda
		$_SESSION['usuario']['listaProdutos']   = array();
		$_SESSION['usuario']['formasPagamento'] = array();
		$_SESSION['usuario']['cliente']         = array();

		return array('resultado' => 'sucesso');
	}

	function gerarReciboPasso04()
	{

		session_start();

		//Não permite concluir se ainda tiver alguma pendencia
		if(!$this->indicaPossibilidadeConclusao())
		{
			$retorno = array('resultado' => 'erro', 'descricao' => 'Não é possível gerar o recibo da venda, uma vez que ainda existem pendências a serem solucionadas.');
			return $retorno;
		}

		//Verifica novamente o perfil do cliente
		$this->redefinePerfilCliente(false);

		//Monta e grava os valores da venda
		$gravacaoVenda = $this->gravarVenda(false);

		if ($gravacaoVenda['retorno'] == 'erro')
			return array('resultado' => 'erro', 'descricao' => $gravacaoVenda['descricao']);


		$gravarItensVenda = $this->gravarItensVenda($gravacaoVenda['dados']['id_venda'], false);
		if ($gravarItensVenda['retorno'] == 'erro')
			return array('resultado' => 'erro', 'descricao' => 'Ocorreu um erro ao buscar os itens da venda para emissão do recibo.');

		//Inclui os produtos da venda
		$gravacaoVenda['dados']['itens_venda'] = $gravarItensVenda['dados'];

		$gravarFormas = $this->gravarFormasDePagamento($gravacaoVenda['dados']['id_venda'], false);
		if ($gravarFormas['retorno'] == 'erro')
			return array('resultado' => 'erro', 'descricao' => 'Ocorreu um erro ao buscar as formas de pagamento para emissão do recibo.');		

		//Inclui as formas de pagamento da venda
		$gravacaoVenda['dados']['formas_pagamento']    = $gravarFormas['dados'];		
		$gravacaoVenda['dados']['valorCredito']        = number_format($_SESSION['usuario']['cliente']['valorCredito'], 2, ',', '.');
		$gravacaoVenda['dados']['valor_total_outros']  = number_format($gravacaoVenda['dados']['valor_total_outros'], 2, ',', '.');
		$gravacaoVenda['dados']['valor_total_taxas']   = number_format($gravacaoVenda['dados']['valor_total_taxas'], 2, ',', '.');


		//Buscar os dados do cliente para apresentar no recibo
		$Model_cliente = new Model_Cliente($this->conexao);
		$dadosCliente  = $Model_cliente->buscarDadosBasicosCliente($_SESSION['usuario']['cliente']['idCliente']);
		if ($dadosCliente['retorno'] == 'erro')
			return array('resultado' => 'erro', 'descricao' => $dadosCliente['descricao']);	

		$gravacaoVenda['dados']['nome']        = $dadosCliente['dados']['nome'];
		$gravacaoVenda['dados']['rg']          = $dadosCliente['dados']['rg'];
		$gravacaoVenda['dados']['telefone']    = $dadosCliente['dados']['telefone'];
		$gravacaoVenda['dados']['perfil']      = $dadosCliente['dados']['perfil'];
		$gravacaoVenda['dados']['dia']         = date("d/m/Y");


		$retorno = array('resultado' => 'sucesso', 'dados' => $gravacaoVenda['dados']);
		return $retorno;						
	}	

    function pesquisarVenda($codigoVenda, $nomeCompleto, $numeroIdentidade)
    {

		//Classe de persistencia da tabela Venda
		$model_venda      = new Model_Venda($this->conexao);		

    	//Verificação das variáveis enviadas ou não
    	if(empty($codigoVenda) || trim($codigoVenda) == '' || $codigoVenda == '-1')
	     	$codigoVenda = false;    	
    	if(empty($nomeCompleto) || trim($nomeCompleto) == '' || $nomeCompleto == '-1')
	     	$nomeCompleto     = false;
    	if(empty($numeroIdentidade) || trim($numeroIdentidade) == '' || $numeroIdentidade == '-1' )
	     	$numeroIdentidade = false;

    	//Verificação das variáveis enviadas ou não
    	if( ($nomeCompleto == false) && ($numeroIdentidade == false) && ($codigoVenda == false))
    	{
			$descricao = "Nenhum critério para pesquisa da venda foi informado.";
	        $resultado = 'erro';	     
		    $retorno   = array('resultado' => $resultado, 'descricao' => $descricao);
		    return $retorno;	        			
    	}

	    //Buscar as vendas na base de dados
		$vendas = $model_venda->buscarVendas($codigoVenda, $nomeCompleto, $numeroIdentidade);

		//Se ocorrer algum erro na busca do cliente
		if($vendas['indicador_erro'] == 1)
		{
			$descricao = "Ocorreu algum erro inesperadona pesquisa da venda.";
	        $resultado = 'erro';	     
		    $retorno   = array('resultado' => $resultado, 'descricao' => $descricao);
		    return $retorno;	        
		}

		//Se nenhuma venda tiver sido encontrada
		if($vendas['indicador_erro'] == 0 && $vendas['dados'] == null)
		{
			$descricao = "Não foi localizada nenhuma venda para o critério informado.";
	        $resultado = 'erro';	     
		    $retorno   = array('resultado' => $resultado, 'descricao' => $descricao);
		    return $retorno;	        
		}

		//Operações realizadas com sucesso, retorna as vendas localizadas
	    $retorno = array('resultado' => 'sucesso', 'dados' => $vendas['dados']);
	    return $retorno;	 		
	}	

	function excluirVenda($idVenda)
	{

		//Classe de persistencia da tabela Venda
		$model_venda   = new Model_Venda($this->conexao);		
		//Classe de persistencia da tabela Produto
		$model_produto = new Model_Produto($this->conexao);				
		//Classe de persistencia da tabela Cliente
		$model_cliente = new Model_Cliente($this->conexao);						
	
		//Colocar o dta_cancelamento_Venda na tabela venda
		$venda         = $model_venda->marcarVendaExcluida($idVenda);
		if($venda == false)
		{
			$descricao = "Ocorreu um erro inesperado ao gravar a exclusão da venda.";
	        $resultado = 'erro';	     
		    $retorno   = array('resultado' => $resultado, 'descricao' => $descricao);
		    return $retorno;	        
		}	

		//Volta os produtos para a quantidade correta no estoque
		$voltaProdutoEstoque = $model_produto->voltarProdutosEstoque($venda);
		if($voltaProdutoEstoque == false)
		{
			$descricao = "Ocorreu um erro inesperado ao incluir os produtos no estoque.";
	        $resultado = 'erro';	     
		    $retorno   = array('resultado' => $resultado, 'descricao' => $descricao);
		    return $retorno;	        
		}			

		//Busca a data da última venda do cliente
		$ultimaVenda = $model_venda->buscarUltimaVendaCliente($idVenda);

		//Se tiver realizado alguma outra venda, atualiza a data no cadastro
		if($ultimaVenda['data'] != false)
			$atualizacaoData = $model_cliente->atualizarDataUltimaCompra( $ultimaVenda['idCliente'], $ultimaVenda['data'] );
		else 
			$atualizacaoData = $model_cliente->atualizarDataUltimaCompra( $ultimaVenda['idCliente'], false );			

		if($atualizacaoData == false)
		{
			$descricao = "Ocorreu um erro inesperado ao atualizar a data da última compra do cliente.";
	        $resultado = 'erro';	     
		    $retorno   = array('resultado' => $resultado, 'descricao' => $descricao);
		    return $retorno;	        
		}		

		//Verifica se teve algum crédito utilizado para esta venda
		$creditoCliente = $model_venda->buscarCreditoUtilizadoNaVenda($idVenda);
		if($creditoCliente == false)
		{
			$descricao = "Ocorreu um erro inesperado ao buscar o crédito do cliente na venda.";
	        $resultado = 'erro';	     
		    $retorno   = array('resultado' => $resultado, 'descricao' => $descricao);
		    return $retorno;	        
		}

		//Se tiver algum crédito do cliente na venda, restaura o valor e soma com o crédito existente do cliente
		if ($creditoCliente != 0)
		{


				$buscaCredito = $model_cliente->buscarCreditoCliente($ultimaVenda['idCliente']);
				if($creditoCliente == false)
				{
					$descricao = "Ocorreu um erro inesperado ao buscar o crédito do cliente.";
			        $resultado = 'erro';	     
				    $retorno   = array('resultado' => $resultado, 'descricao' => $descricao);
				    return $retorno;	        
				}

				//Soma o valor do crédito do cliente
				$novoValorCredito = $creditoCliente + $buscaCredito;

				//Atualiza na base o valor do crédito
				$atualizaCredito  = $model_cliente->alterarCreditoCliente($ultimaVenda['idCliente'], $novoValorCredito);
				if($atualizaCredito == false)
				{
					$descricao = "Ocorreu um erro inesperado ao restaurar o crédito do cliente.";
			        $resultado = 'erro';	     
				    $retorno   = array('resultado' => $resultado, 'descricao' => $descricao);
				    return $retorno;	        
				}				

		}

		//Verifica se tem que voltar ou não o perfil
		//Volta o perfil somente se não houver nenhuma venda realizada para o cliente depois da venda que está sendo excluida
		$vendaPosterior = $model_venda->indicaVendaPosterior($idVenda);

		//Se tiver que voltar o perfil
		if($vendaPosterior == false)
		{

			$voltaUltimoPerfil = $model_cliente->voltarUltimoPerfilCliente($ultimaVenda['idCliente']);
			if($voltaUltimoPerfil == false)
			{
				$descricao = "Ocorreu um erro inesperado ao voltar o perfil do cliente.";
		        $resultado = 'erro';	     
			    $retorno   = array('resultado' => $resultado, 'descricao' => $descricao);
			    return $retorno;	        
			}							

		}


		//Operações realizadas com sucesso, retorna as vendas localizadas
	    $retorno = array('resultado' => 'sucesso', 'descricao' => 'Venda cancelada com sucesso.');
	    return $retorno;	 		


	}

	function ajusteValorProdutosConsignado($indicadorConsignado)
	{

		if (!isset($_SESSION)) {
			session_start();
		}			
	
		// Confere se está tentando mudar para um status igual... se for igual, nada é feito
		if ($indicadorConsignado == $_SESSION['usuario']['cliente']['indicadorConsignado'])
			return array("resultado" => "sucesso");

		if ($indicadorConsignado == 'N')
		{

			for($i=0; $i < count($_SESSION['usuario']['listaProdutos']); $i++)
			{
				$_SESSION['usuario']['listaProdutos'][$i]['valor'] 			= $_SESSION['usuario']['listaProdutos'][$i]['valor'] - 2.0;
				$_SESSION['usuario']['listaProdutos'][$i]['valorAtacado'] 	= $_SESSION['usuario']['listaProdutos'][$i]['valorAtacado'] - 2.0;
				$_SESSION['usuario']['listaProdutos'][$i]['valorVarejo'] 	= $_SESSION['usuario']['listaProdutos'][$i]['valorVarejo'] - 2.0;
			}

			$_SESSION['usuario']['cliente']['indicadorConsignado'] = 'N';

		}
		else 
		{

			for($i=0; $i < count($_SESSION['usuario']['listaProdutos']); $i++)
			{
				$_SESSION['usuario']['listaProdutos'][$i]['valor'] 			= $_SESSION['usuario']['listaProdutos'][$i]['valor'] + 2.0;
				$_SESSION['usuario']['listaProdutos'][$i]['valorAtacado'] 	= $_SESSION['usuario']['listaProdutos'][$i]['valorAtacado'] + 2.0;
				$_SESSION['usuario']['listaProdutos'][$i]['valorVarejo'] 	= $_SESSION['usuario']['listaProdutos'][$i]['valorVarejo'] + 2.0;
			}

			$_SESSION['usuario']['cliente']['indicadorConsignado'] = 'S';

		}

		return array("resultado" => "sucesso");
	}

}
?>