<?php

/*
 * Editor server script for DB table vendedor
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

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'vendedor', 'id_vendedor' )
	->fields(
		Field::inst( 'vendedor.id_vendedor' ),
		Field::inst( 'vendedor.nome' )
			->validator( 'Validate::unique', array("message" => "RG anteriormente cadastrado." ))
			->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." ))
			->validator( 'Validate::maxLen', array(
                'max' => 255,
                'message' => 'Permitido informar no máximo 255 caracteres.'
            )),
		Field::inst( 'vendedor.email' )
			->validator( 'Validate::email', array("message" => "E-mail inválido." )),

		Field::inst( 'vendedor.numero_rg' )
			->validator( 'Validate::unique', array("message" => "RG já cadastrado." )),

		Field::inst( 'vendedor.orgao_expeditor' )
		->validator( 'Validate::maxLen', array(
	                                    'min' => 1,
	                                    'max' => 8,
	                                    'message' => 'Permitido informar no máximo 8 caracteres.'
				)),
				
		Field::inst( 'vendedor.cpf' ),

		Field::inst( 'vendedor.telefone_01' )
			->validator( 'Validate::numeric', array("message" => "Permitido informar somente números." ))
			->validator( 'Validate::maxLen', array(
                                    'max' => 20,
                                    'message' => 'Permitido informar no máximo 20 caracteres.'
        	)),	
		Field::inst( 'vendedor.logradouro' )
			->validator( 'Validate::maxLen', array(
                                    'max' => 255,
                                    'message' => 'Permitido informar no máximo 255 caracteres.'
        	)),	
		Field::inst( 'vendedor.cep' ),
		Field::inst( 'vendedor.cidade' ),
		Field::inst( 'vendedor.estado' )
		->validator( 'Validate::maxLen', array(
                                    'max' => 50,
                                    'message' => 'Permitido informar no máximo 50 caracteres.'
        	)),
		Field::inst( 'vendedor.pais' )
		->validator( 'Validate::maxLen', array(
                                    'max' => 50,
                                    'message' => 'Permitido informar no máximo 50 caracteres.'
        	)),
		Field::inst( 'vendedor.porcentagem_comissao' )
		->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." ))
		->validator( 'Validate::numeric', array("message" => "Permitido informar somente números." ))		
        ->validator( 'Validate::maxLen', array(
                                    'max' => 5,
                                    'message' => 'Permitido informar no máximo 20 caracteres.'
			)),
		
		Field::inst( 'vendedor.id_loja' )
		->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." ))
			->options( Options::inst()
                ->table( 'loja' )
                ->value( 'id' )
                ->label( 'descricao' )
            )
            ->validator( 'Validate::dbValues' ),

        Field::inst( 'loja.descricao' )
		->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." )),	

		Field::inst( 'vendedor.situacao' )
		 ->setFormatter( function ( $val, $data, $opts ) {
                return ! $val ? 0 : 1;
         } )
	)
	->leftJoin( 'loja', 'loja.id', '=', 'vendedor.id_loja' )	
	->process( $_POST )
	->json();
