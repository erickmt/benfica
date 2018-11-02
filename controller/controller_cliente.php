<?php

/*
 * Editor server script for DB table cliente
 * Created by http://editor.datatables.net/generator
 */

// DataTables PHP library and database connection
include( "lib/DataTables.php" );

// Alias Editor classes so they are easy to use
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate;

if (!isset($_SESSION)) {
	session_start();
}	  

function validateCashFlowDates(&$values, &$action) {
    //Caso seja CNPJ
    $cpfcnpj = $values["cliente"]["cpf"];
    $valido = (strlen($cpfcnpj) == 11 || strlen($cpfcnpj) == 14);
    if(!$valido)
    	return "Tamanho incorreto de CPF / CNPJ";
    //Caso seja CPF
    if(strlen($cpfcnpj) == 11) {
    	$valido = validar_cpf($cpfcnpj);
    }        
    if(strlen($cpfcnpj) == 14) {
    	$valido = validar_cnpj($cpfcnpj);
    }     

    if($valido == false)
    	return 'CPF / CNPJ inválido';

    //if ( $values["cliente"]["cpf"]  <= '' &&
    //     $values["cliente"]["nome_mae"] <= ''    ) {
    //    return 'Informar CPF ou nome da mãe';
    //}
    //global validators do not return true ... for whatever reason


    return null;   
}
	
function validar_cpf($cpf)
{
	$cpf = preg_replace('/[^0-9]/', '', (string) $cpf);
	// Valida tamanho
	if (strlen($cpf) != 11)
		return false;
	// Calcula e confere primeiro dígito verificador
	for ($i = 0, $j = 10, $soma = 0; $i < 9; $i++, $j--)
		$soma += $cpf{$i} * $j;
	$resto = $soma % 11;
	if ($cpf{9} != ($resto < 2 ? 0 : 11 - $resto))
		return false;
	// Calcula e confere segundo dígito verificador
	for ($i = 0, $j = 11, $soma = 0; $i < 10; $i++, $j--)
		$soma += $cpf{$i} * $j;
	$resto = $soma % 11;
	return $cpf{10} == ($resto < 2 ? 0 : 11 - $resto);
}

function validar_cnpj($cnpj)
{
	$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
	// Valida tamanho
	if (strlen($cnpj) != 14)
		return false;
	// Valida primeiro dígito verificador
	for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
	{
		$soma += $cnpj{$i} * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}
	$resto = $soma % 11;
	if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
		return false;
	// Valida segundo dígito verificador
	for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
	{
		$soma += $cnpj{$i} * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}
	$resto = $soma % 11;
	return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
}


Editor::inst( $db, 'cliente', 'id_cliente' ) //id_cliente
	->fields(
	
		/*********************************************************************************************************/
		// CADASTRO RÁPIDO
		
		//nome - obrigatório
			//NÃO PERMITIR NÚMERO
		Field::inst( 'cliente.id_cliente' ),
		Field::inst( 'cliente.nome' )
			->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." ))
 			->validator( 'Validate::maxLen', array(
                'max' => 255,
                'message' => 'Permitido informar no máximo 255 caracteres.'
            )),

		Field::inst( 'cliente.tipo' )
		->validator( 'Validate::notEmpty', array(
                "message" => "Campo de preenchimento obrigatório."
            ) ),
			
		Field::inst( 'cliente.cpf' )
			->validator( 'Validate::numeric', array("message" => "Permitido informar somente números." ))
	        ->validator( 'Validate::maxLen', array(
	                                    'min' => 11,
	                                    'max' => 14,
	                                    'message' => 'Permitido informar apenas números com 11 caracteres.'
	        	)),		
			//->validator( 'Validate::unique', array("message" => "CPF anteriormente cadastrado." )),

		Field::inst( 'cliente.ie' )
			->validator( 'Validate::numeric', array("message" => "Permitido informar somente números." )),
		
		Field::inst( 'cliente.email' ),
		//orgao_expeditor
			//NÃO PERMITIR NÚMERO
		
		//dta_nascimento - obrigatório
		Field::inst( 'cliente.dta_nascimento' )
			->validator( 'Validate::dateFormat', array( 'format'=>'Y-m-d' ) )
			->getFormatter( 'Format::date_sql_to_format', 'Y-m-d' )
			->setFormatter( 'Format::date_format_to_sql', 'Y-m-d' ),
		
		
		//id_vendedor
		Field::inst( 'cliente.id_vendedor' )
			->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." ))
			->options( Options::inst()
                ->table( 'vendedor' )
                ->value( 'id_vendedor' )
                ->label( 'nome' )
                ->where( function ( $q ) {
				  $q->where( 'vendedor.situacao', '0', '=');
				})
            ),

			
			//id_vendedor - NOME VENDEDOR
			Field::inst( 'vendedor.nome' ),
		
		//telefone 01
		Field::inst( 'cliente.telefone_01' )
		->validator( 'Validate::numeric', array("message" => "Permitido informar somente números." ))		
        ->validator( 'Validate::maxLen', array(
                                    'max' => 20,
                                    'message' => 'Permitido informar no máximo 20 caracteres.'
        	)),		

		/*********************************************************************************************************/
		// CADASTRO COMPLETO
		
		//data_cadastro
		Field::inst( 'cliente.dta_cadastro' )
			->validator( 'Validate::dateFormat', array( 'format'=>'Y-m-d' ) )
			->getFormatter( 'Format::date_sql_to_format', 'Y-m-d' )
			->setFormatter( 'Format::date_format_to_sql', 'Y-m-d' ),
			
		//id_perfil
		Field::inst( 'cliente.id_perfil' )
			->validator( 'Validate::notEmpty',1)
			->options( Options::inst()
                ->table( 'perfil_cliente' )
                ->value( 'id_perfil' )
                ->label( 'descricao' )
            ),
			
			//id_perfil - ATACADISTA/VAREJISTA
			Field::inst( 'perfil_cliente.descricao' ),	
		
		//dta_atualizacao
		Field::inst( 'cliente.dta_atualizacao' )
	  		->setValue( date('c') )
  			->getFormatter( 'Format::date_sql_to_format', 'jS F Y' ),
		//dta_validade
		Field::inst( 'cliente.dta_validade' )
			->validator( 'Validate::dateFormat', array( 'format'=>'Y-m-d' ) )
			->getFormatter( 'Format::date_sql_to_format', 'Y-m-d' )
			->setFormatter( 'Format::date_format_to_sql', 'Y-m-d' ),
		
		
		Field::inst( 'cliente.numero' )

		->validator( 'Validate::numeric', array("message" => "Permitido informar somente números." ))		
        ->validator( 'Validate::maxLen', array(
                                    'max' => 8,
                                    'message' => 'Permitido informar no máximo 8 caracteres.'
        	)),	
		//logradouro
		Field::inst( 'cliente.logradouro' )
        ->validator( 'Validate::maxLen', array(
                                    'max' => 255,
                                    'message' => 'Permitido informar no máximo 255 caracteres.'
        	)),					
		
		//bairro
		Field::inst( 'cliente.bairro' )	
        ->validator( 'Validate::maxLen', array(
                                    'max' => 50,
                                    'message' => 'Permitido informar no máximo 50 caracteres.'
        	)),		
		
		//cep
		Field::inst( 'cliente.cep' )
		->validator( 'Validate::numeric', array("message" => "Permitido informar somente números." ))				
        ->validator( 'Validate::maxLen', array(
        							'min' => 9,
                                    'max' => 9,
                                    'message' => 'CEP em formato incorreto.'
        	)),		

		//cidade
		Field::inst( 'cliente.cidade' )
        ->validator( 'Validate::maxLen', array(
                                    'max' => 50,
                                    'message' => 'Permitido informar no máximo 50 caracteres.'
        	)),					
		
		//estado
		Field::inst( 'cliente.estado' )	
        ->validator( 'Validate::maxLen', array(
                                    'max' => 50,
                                    'message' => 'Permitido informar no máximo 50 caracteres.'
        	)),					
		
		//pais
		Field::inst( 'cliente.pais' )
        ->validator( 'Validate::maxLen', array(
                                    'max' => 50,
                                    'message' => 'Permitido informar no máximo 50 caracteres.'
        	)),					
		
		//dta_ultima_compra
		Field::inst( 'cliente.dta_ultima_compra' )
			->validator( 'Validate::dateFormat', array( 'format'=>'Y-m-d' ) )
			->getFormatter( 'Format::date_sql_to_format', 'd-m-Y' )
			->setFormatter( 'Format::date_format_to_sql', 'Y-m-d' ),
		
		//valor_credito
		Field::inst( 'cliente.valor_credito' )
			->validator( 'Validate::numeric', array("message" => "Insira apenas números" ) ),
		
		//dta_atualizacao_credito
		Field::inst( 'cliente.dta_atualizacao_credito' )
			->getFormatter( 'Format::date_sql_to_format', 'jS F Y' ),
		
		//observacao
		Field::inst( 'cliente.observacao' )
        ->validator( 'Validate::maxLen', array(
                                    'max' => 512,
                                    'message' => 'Permitido informar no máximo 500 caracteres.'
        	)),				

		Field::inst( 'cliente.situacao' )
		 ->setFormatter( function ( $val, $data, $opts ) {
                return ! $val ? 0 : 1;
         } )
	)
	->validator( function ( $editor, $editorAction, $data ) {
	    if ( $editorAction === Editor::ACTION_EDIT && $_SESSION['usuario']['perfil'] != 'A' ) {
           return 'Não é possível editar o cliente';
        }

	    if ( $editorAction === Editor::ACTION_CREATE || $editorAction === Editor::ACTION_EDIT ) {
	        if ( $editorAction === Editor::ACTION_CREATE ) {
	            $action = 'create';
	        } else {
	            $action = 'edit';
	        } //no return statement if validation passes
	        foreach ( $data['data'] as $pkey => $values ) {
	            return validateCashFlowDates($values, $action);
	        }
	    }
	} )
	// JOINS

	->leftJoin( 'vendedor', 'cliente.id_vendedor', '=', 'vendedor.id_vendedor' )
	
	->leftJoin( 'perfil_cliente', 'cliente.id_perfil', '=', 'perfil_cliente.id_perfil' )
	
	->where( function ( $q ) {
	  $q->where( 'cliente.situacao', '0', '=');
	})

	->process( $_POST ) 
	
	->json();