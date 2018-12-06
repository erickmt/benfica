<?php

/**
 * Relatorio
 *
 * Operações relacionadas as variáveis globais, de sessão do sistema
 * 
 */
class Relatorio {

	private $conexao;

	function Relatorio($conexao)
	{
		$this->conexao = $conexao;
	}

	function listarVendedores(){
		$model_usuario = new Model_Vendedor($this->conexao);
		return $model_usuario->buscarVendedores();
	}

	function imprimir($html){
		$model_usuario = new Model_Usuario($this->conexao);
		$retorno = $model_usuario->imprimir($html);
		return $retorno;
	}

	function listarTipoProduto(){
		$model_produto = new Model_Produto($this->conexao);
		return $model_produto->buscarTipoProduto();
	}

	function gerarRelatorioComissao($idVendedor, $nomeVendedor, $mesReferencia, $anoReferencia, $lojaBusca, $idPagamento)
	{
		$model_venda   = new Model_Venda($this->conexao);

		//Validação dos dados de entrada
		if ($mesReferencia < 1 || $mesReferencia > 12)
			return array("resultado" => "erro", "descricao" => "Mês informado inválido.");

		if ($anoReferencia < 1)
			return array("resultado" => "erro", "descricao" => "Ano informado inválido.");		

		//Busca as vendas realizadas
		$vendas = $model_venda->buscarVendasComissao($idVendedor, $mesReferencia, $anoReferencia, $lojaBusca, $idPagamento);

		if ($vendas["indicador_erro"] == 1)
			return array("resultado" => "erro", "descricao" => "Ocorreu um erro inesperado ao buscar as vendas.");

		if ($vendas["indicador_erro"] == 0 and $vendas["dados"] == null)
			return array("resultado" => "erro", "descricao" => "Não foi identificada nenhuma venda no mês informado para o vendedor em questão.");		

		$vendasRetornadas = array();
		$totalComissao    = 0.0;
		$totalVenda       = 0.0;

		//Tratamento para cada venda identificada
		for($i=0; $i<count($vendas["dados"]); $i++)
		{
			$totalComissao = $totalComissao + $vendas["dados"][$i]["valor_total_comissao"];
			$totalVenda    = $totalVenda    + $vendas["dados"][$i]["valor_total_pago"];

			$vendas["dados"][$i]["valor_total_pago"]     = number_format($vendas["dados"][$i]["valor_total_pago"], 2, ',', '.');
			$vendas["dados"][$i]["valor_total_comissao"] = number_format($vendas["dados"][$i]["valor_total_comissao"], 2, ',', '.');			
			$vendas["dados"][$i]["dta_venda"]            = date('d/m/Y',  strtotime($vendas["dados"][$i]["dta_venda"]));

			$vendaAuxiliar      = array( 'lj_venda' => $vendas["dados"][$i]['lj_venda'], 'id_venda' => $vendas["dados"][$i]['id_venda'], 'dta_venda' => $vendas["dados"][$i]['dta_venda'], 'nome' => $vendas["dados"][$i]['nome'], 'numero_rg' => $vendas["dados"][$i]['numero_rg'], 'valor_total_pago' => $vendas["dados"][$i]["valor_total_pago"], 'valor_total_comissao' => $vendas["dados"][$i]["valor_total_comissao"], 'per_desc' => $vendas["dados"][$i]["per_desc"], 'pagamentos' => $vendas["dados"][$i]["pagamentos"] );
			$vendasRetornadas[] = $vendaAuxiliar;
		}

		// Formata os valores para serem apresentados
		$totalComissao = number_format($totalComissao, 2, ',', '.');
		$totalVenda    = number_format($totalVenda, 2, ',', '.');


		//Retorna as informações da venda
		return array("resultado" => "sucesso", "dados" => array('vendas' => $vendasRetornadas, 'vendedor' => $nomeVendedor, 'periodo' => $mesReferencia." / ".$anoReferencia, 'totalVenda' => $totalVenda, 'totalComissao' => $totalComissao));
	}
    
	function gerarRelatorioResumidoComissao($idVendedor, $mesReferencia, $anoReferencia, $lojaBusca, $idPagamento)
	{
		$model_venda   = new Model_Venda($this->conexao);

		//Validação dos dados de entrada
		if ($mesReferencia < 1 || $mesReferencia > 12)
			return array("resultado" => "erro", "descricao" => "Mês informado inválido.");

		if ($anoReferencia < 1)
			return array("resultado" => "erro", "descricao" => "Ano informado inválido.");		

		//Busca as vendas realizadas
		$vendas = $model_venda->buscarVendasResumidaComissao($idVendedor, $mesReferencia, $anoReferencia, $lojaBusca, $idPagamento);

		if ($vendas["indicador_erro"] == 1)
			return array("resultado" => "erro", "descricao" => "Ocorreu um erro inesperado ao buscar as vendas.");

		if ($vendas["indicador_erro"] == 0 and $vendas["dados"] == null)
			return array("resultado" => "erro", "descricao" => "Não foi identificada nenhuma venda no mês informado para o vendedor em questão.");		

		$vendasRetornadas = array();
		$totalComissao    = 0.0;
		$totalVenda       = 0.0;

		//Tratamento para cada venda identificada
		for($i=0; $i<count($vendas["dados"]); $i++)
		{
			$totalComissao = $totalComissao + $vendas["dados"][$i]["comissao_total"];
			$totalVenda    = $totalVenda    + $vendas["dados"][$i]["total_venda"];

			$vendas["dados"][$i]["comissao_total"]     = number_format($vendas["dados"][$i]["comissao_total"], 2, ',', '.');
			$vendas["dados"][$i]["total_venda"] = number_format($vendas["dados"][$i]["total_venda"], 2, ',', '.');			
			$vendas["dados"][$i]["total_venda_varejo"]     = number_format($vendas["dados"][$i]["total_venda_varejo"], 2, ',', '.');
			$vendas["dados"][$i]["comissao_varejo"] = number_format($vendas["dados"][$i]["comissao_varejo"], 2, ',', '.');			
			$vendas["dados"][$i]["total_venda_atacado"]     = number_format($vendas["dados"][$i]["total_venda_atacado"], 2, ',', '.');
			$vendas["dados"][$i]["comissao_atacado"] = number_format($vendas["dados"][$i]["comissao_atacado"], 2, ',', '.');			

			$vendaAuxiliar      = array('lj_venda' =>$vendas["dados"][$i]['lj_venda'], 'comissao_total' => $vendas["dados"][$i]['comissao_total'], 'total_venda' => $vendas["dados"][$i]['total_venda'], 'nome' => $vendas["dados"][$i]['nome'], 'total_venda_varejo' => $vendas["dados"][$i]['total_venda_varejo'], 'comissao_varejo' => $vendas["dados"][$i]["comissao_varejo"], 'total_venda_atacado' => $vendas["dados"][$i]["total_venda_atacado"], 'comissao_atacado' => $vendas["dados"][$i]["comissao_atacado"]);
			$vendasRetornadas[] = $vendaAuxiliar;
		}

		// Formata os valores para serem apresentados
		$totalComissao = number_format($totalComissao, 2, ',', '.');
		$totalVenda    = number_format($totalVenda, 2, ',', '.');


		//Retorna as informações da venda
		return array("resultado" => "sucesso", "dados" => array('vendas' => $vendasRetornadas, 'periodo' => $mesReferencia." / ".$anoReferencia, 'totalVenda' => $totalVenda, 'totalComissao' => $totalComissao));
	}


	function gerarRelatorioFaturamento( $dataInicial, $dataFinal, $lojaBusca)
    {
		$model_venda   = new Model_Venda($this->conexao);

		//Validação dos dados de entrada
		if ( $dataInicial == '0000-00-00' || $dataFinal == '0000-00-00')
			return array("resultado" => "erro", "descricao" => "Data informada inválida.");

		if ($dataFinal < $dataInicial)
			return array("resultado" => "erro", "descricao" => "A data final informada é menor do que a data inicial informada.");

		

		//Busca as vendas realizadas, separadas por forma de pagamento
		$vendasFormaPagamento = $model_venda->buscarFaturamento($dataInicial, $dataFinal, $lojaBusca); //buscarValoresGeraisVendas



		if($vendasFormaPagamento['indicador_erro'] == 1)
			return array("resultado" => "erro", "descricao" => "Não foi identificada nenhuma venda no período informado.");

		$custoFaturamento = $model_venda->buscarCusto($dataInicial, $dataFinal, $lojaBusca); //buscarValoresGeraisVendas



		if($custoFaturamento['indicador_erro'] == 1)
			return array("resultado" => "erro", "descricao" => "Não foi identificada nenhuma venda no período informado.");


		$retorno = array();
		$retorno['formapagamento'] = $vendasFormaPagamento['dados'];
		$retorno['custo'] = $custoFaturamento['dados'];
		
		$retorno['data_inicial']   = date('d/m/Y', strtotime($dataInicial.' 02:02:02'));
		$retorno['data_final']     = date('d/m/Y', strtotime($dataFinal.' 02:02:02'));
		
		$r = array("resultado" => "sucesso", "dados" => $retorno);
		return $r;
    }

	function gerarRelatorioFaturamentoAno($ano, $lojaBusca)
    {
		$model_venda   = new Model_Venda($this->conexao);
		

		//Busca as vendas realizadas, separadas por forma de pagamento
		$faturamentoAno = $model_venda->buscarFaturamentoAno($ano, $lojaBusca); //buscarValoresGeraisVendas



		if($faturamentoAno['indicador_erro'] == 1)
			return array("resultado" => "erro", "descricao" => "Não foi identificada nenhuma venda no período informado.");

		$custoFaturamento = $model_venda->buscarCustoAno($ano, $lojaBusca); //buscarValoresGeraisVendas



		if($custoFaturamento['indicador_erro'] == 1)
			return array("resultado" => "erro", "descricao" => "Não foi identificada nenhuma venda no período informado.");


		$retorno = array();
		$retorno['faturamentoAno'] = $faturamentoAno['dados'];
		$retorno['custo'] = $custoFaturamento['dados'];
		
		$r = array("resultado" => "sucesso", "dados" => $retorno);
		return $r;
    }
	
	
	
    function gerarRelatorioFinanceiro( $dataInicial, $dataFinal, $lojaBusca)
    {
		$model_venda   = new Model_Venda($this->conexao);

		//Validação dos dados de entrada
		if ( $dataInicial == '0000-00-00' || $dataFinal == '0000-00-00')
			return array("resultado" => "erro", "descricao" => "Data informada inválida.");

		if ($dataFinal < $dataInicial)
			return array("resultado" => "erro", "descricao" => "A data final informada é menor do que a data inicial informada.");

		

		//Busca as vendas realizadas, separadas por forma de pagamento
		$vendasFormaPagamento = $model_venda->buscarVendasPorFormaPagamento($dataInicial, $dataFinal, $lojaBusca);



		if($vendasFormaPagamento['indicador_erro'] == 1)
			return array("resultado" => "erro", "descricao" => "Não foi identificada nenhuma venda no período informado.");

		$retorno = array();
		$retorno['formapagamento'] = $vendasFormaPagamento['dados'];


		//Busca as vendas realizadas
		$vendasValoresGerais = $model_venda->buscarValoresGeraisVendas($dataInicial, $dataFinal, $lojaBusca);

		if($vendasValoresGerais['indicador_erro'] == 1)
			return array("resultado" => "erro", "descricao" => "Não foi identificada nenhuma venda no período informado.");
		else
		{

			$retorno['valor_comissao'] = $vendasValoresGerais['dados']['valor_comissao'];
			$retorno['valor_taxas']    = $vendasValoresGerais['dados']['valor_taxas'];
			$retorno['valor_outros']   = $vendasValoresGerais['dados']['valor_outros'];
			$retorno['valor_liquido']  = $vendasValoresGerais['dados']['valor_liquido'];
			$retorno['valor_custo']    = $vendasValoresGerais['dados']['valor_custo'];
	
			$retorno['data_inicial']   = date('d/m/Y', strtotime($dataInicial.' 02:02:02'));
			$retorno['data_final']     = date('d/m/Y', strtotime($dataFinal.' 02:02:02'));

			$r = array("resultado" => "sucesso", "dados" => $retorno);
			return $r;
		}

    }

	function gerarRelatorioPecasCliente($nomeCliente, $dataInicial, $dataFinal, $lojaBusca)
	{
		$model_produto   = new Model_Produto($this->conexao);

		//Validação dos dados de entrada
		if ($dataInicial > $dataFinal)
			return array("resultado" => "erro", "descricao" => "Data final deve ser maior que a data inciall.");

		//Busca as vendas realizadas
		$retorno = $model_produto->gerarRelatorioPecasCliente($nomeCliente, $dataInicial, $dataFinal, $lojaBusca);

		if ($retorno["indicador_erro"] == 1)
			return array("resultado" => "erro", "descricao" => "Ocorreu um erro inesperado ao buscar as vendas.");

		if ($retorno["indicador_erro"] == 0 and $retorno["dados"] == null)
			return array("resultado" => "erro", "descricao" => "Não foi identificada nenhuma venda no mês informado para o vendedor em questão.");		

		$retorno['data_inicial']   = date('d/m/Y', strtotime($dataInicial.' 02:02:02'));
		$retorno['data_final']     = date('d/m/Y', strtotime($dataFinal.' 02:02:02'));
		$retorno['resultado'] = "sucesso";
		return $retorno;
	}

	function RelatorioPecasCliente($idCliente, $dataInicial, $dataFinal, $lojaBusca)
	{
		$model_produto   = new Model_Produto($this->conexao);

		session_start();

		//Validação dos dados de entrada
		if ($dataInicial > $dataFinal)
			return array("resultado" => "erro", "descricao" => "Data final deve ser maior que a data inciall.");

		//Busca as vendas realizadas
		$retorno = $model_produto->RelatorioPecasCliente($idCliente, $dataInicial, $dataFinal, $lojaBusca);

		if ($retorno["indicador_erro"] == 1)
			return array("resultado" => "erro", "descricao" => "Ocorreu um erro inesperado ao buscar as vendas.");

		if ($retorno["indicador_erro"] == 0 and $retorno["dados"] == null)
			return array("resultado" => "erro", "descricao" => "Não foi identificada nenhuma venda no mês informado para o vendedor em questão.");		

		$retorno['resultado'] = "sucesso";
		return $retorno;
	}

    function gerarRelatorioPecas($tipoProduto, $dataInicial, $dataFinal, $produto, $lojaBusca, $tipoRelatorio)
    {
		$model_venda   = new Model_Venda($this->conexao);

		//Validação dos dados de entrada
		if ( $dataInicial == '0000-00-00' || $dataFinal == '0000-00-00')
			return array("resultado" => "erro", "descricao" => "Data informada inválida.");

		if ($dataFinal < $dataInicial)
			return array("resultado" => "erro", "descricao" => "A data final informada é menor do que a data inicial informada.");

		//Busca as vendas realizadas, separadas por forma de pagamento
		$vendasFormaPagamento = $model_venda->buscarPecas($tipoProduto, $dataInicial, $dataFinal, $produto, $lojaBusca, $tipoRelatorio);

		if($vendasFormaPagamento['indicador_erro'] == 1)
			return array("resultado" => "erro", "descricao" => "Não foi identificada nenhuma venda no período informado.");

		$retorno = array();
		$retorno['formapagamento'] = $vendasFormaPagamento['dados'];

		$retorno['data_inicial']   = date('d/m/Y', strtotime($dataInicial.' 02:02:02'));
		$retorno['data_final']     = date('d/m/Y', strtotime($dataFinal.' 02:02:02'));

		$r = array("resultado" => "sucesso", "dados" => $retorno);
		return $r;
	}
	
    function gerarRelatorioConsignado($cliente, $tipoProduto, $dataInicial, $dataFinal, $produto, $lojaBusca, $devolvidas)
    {
		$model_produto   = new Model_Produto($this->conexao);

		//Validação dos dados de entrada
		if ( $dataInicial == '0000-00-00' || $dataFinal == '0000-00-00')
			return array("resultado" => "erro", "descricao" => "Data informada inválida.");

		if ($dataFinal < $dataInicial)
			return array("resultado" => "erro", "descricao" => "A data final informada é menor do que a data inicial informada.");

		//Busca as vendas realizadas, separadas por forma de pagamento
		$produtosVenda = $model_produto->buscarPecasConignado($cliente, $tipoProduto, $dataInicial, $dataFinal, $produto, $lojaBusca, $devolvidas);

		if($produtosVenda['indicador_erro'] == 1)
			return array("resultado" => "erro", "descricao" => "Não foi identificada nenhuma venda no período informado.");

		if($produtosVenda['indicador_erro'] == 2)
			return array("resultado" => "erro", "descricao" => "Nenhuma venda encontrada para esta pesquisa.");

		$retorno = array();
		$retorno['produtos'] = $produtosVenda['dados'];

		$retorno['data_inicial']   = date('d/m/Y', strtotime($dataInicial.' 02:02:02'));
		$retorno['data_final']     = date('d/m/Y', strtotime($dataFinal.' 02:02:02'));

		return array("resultado" => "sucesso", "dados" => $retorno);
	}
	
	function gerarRelatorioHistoricoCliente($idCliente){

		$model_venda   = new Model_Venda($this->conexao);		

		//Busca as vendas realizadas, separadas por forma de pagamento
		$historico= $model_venda->buscarHistoricoCliente($idCliente); //buscarValoresGeraisVendas

		if($historico['indicador_erro'] == 1)
			return array("resultado" => "erro", "descricao" => "Erro ao buscar histórico de vendas do cliente.");
		
		if($historico['indicador_erro'] == 2)
			return array("resultado" => "erro", "descricao" => "Não foi identificada nenhuma venda para o cliente informado.");

		return array("resultado" => "sucesso", "dados" => $historico['dados']);
	
	}

    function gerarRelatorioCaixa($dataInicial, $dataFinal, $lojaBusca, $formaPagamento)
    {

		session_start();

		$lojaLogada = $_SESSION['usuario']['id_loja'];
		if($lojaLogada != 0){
			$lojaBusca = $lojaLogada;
		}

		$model_venda   = new Model_Venda($this->conexao);

		//Validação dos dados de entrada
		if ( $dataInicial == '0000-00-00' || $dataFinal == '0000-00-00')
			return array("resultado" => "erro", "descricao" => "Data informada inválida.");

		if ($dataFinal < $dataInicial)
			return array("resultado" => "erro", "descricao" => "A data final informada é menor do que a data inicial informada.");

		

		//Busca as vendas realizadas, separadas por forma de pagamento
		$vendasFormaPagamento = $model_venda->gerarRelatorioCaixa($dataInicial, $dataFinal, $lojaBusca, $formaPagamento);

		if($vendasFormaPagamento['indicador_erro'] == 1)
			return array("resultado" => "erro", "descricao" => "Erro ao realizar busca.");

		if($vendasFormaPagamento['indicador_erro'] == 2)
			return array("resultado" => "erro", "descricao" => "Não foi identificada nenhuma movimentação financeira.");

		$retorno = array();
		$retorno['formapagamento'] = $vendasFormaPagamento['dados'];

		$retorno['data_inicial']   = date('d/m/Y', strtotime($dataInicial.' 02:02:02'));
		$retorno['data_final']     = date('d/m/Y', strtotime($dataFinal.' 02:02:02'));

		$r = array("resultado" => "sucesso", "dados" => $retorno);
		return $r;


    }


}

?>