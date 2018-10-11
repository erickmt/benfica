<?php

/*
 * Editor server script for DB table forma_pagamento
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


// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'forma_pagamento', 'id_forma_pagamento' )
	->fields(
		Field::inst( 'forma_pagamento.descricao' )
			->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." ))
 			->validator( 'Validate::maxLen', array(
                'max' => 255,
                'message' => 'Permitido informar no máximo 255 caracteres.'
            )),
		Field::inst( 'forma_pagamento.porcentagem_taxa' )
			->validator( 'Validate::numeric', array("message" => "Permitido informar somente números." ))				
			->validator( 'Validate::notEmpty', array(
	                "message" => "Campo de preenchimento obrigatório."
	            ) )
	        ->validator( 'Validate::minMaxNum', array(
	                                    'min' => 0,
	                                    'max' => 99.99,
	                                    'message' => 'Porcentagem válida entre 0% e 99.99%'
	        )),
	    
	    Field::inst( 'forma_pagamento.id_loja' )
		->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." ))
			->options( Options::inst()
                ->table( 'loja' )
                ->value( 'id' )
				->label( 'descricao' )
				->where( function ( $q ) {
					if($_SESSION['usuario']['id_loja'] <> 0)
					{	
						$q->where( 'loja.id', $_SESSION['usuario']['id_loja'], '=');
						$q->or_where( 'loja.id', '0', '=');
					}
				})
            )
            ->validator( 'Validate::dbValues' ),

        Field::inst( 'loja.descricao' )
		->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." )),

	    Field::inst( 'forma_pagamento.situacao' )
			->setFormatter( function ( $val, $data, $opts ) {
	                return ! $val ? 0 : 1;
	        } )
	)
	->leftJoin( 'loja', 'loja.id', '=', 'forma_pagamento.id_loja' )
	->where( function ( $q ) {
		if($_SESSION['usuario']['id_loja'] <> 0)
	  	{	
	  		$q->where( 'loja.id', $_SESSION['usuario']['id_loja'], '=');
	  		$q->or_where( 'loja.id', 0, '=');
		}
	})
	->process( $_POST )
	->json();
