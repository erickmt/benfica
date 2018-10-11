<?php

/*
 * Editor server script for DB table tipo_produto
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

// The following statement can be removed after the first run (i.e. the database
// table has been created). It is a good idea to do this to help improve
// performance.

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'ncm', 'id_ncm' )
	->fields(
		Field::inst( 'descricao' )
		->validator( 'Validate::notEmpty', array(
                "message" => "Campo de preenchimento obrigatório."
            ))
        ->validator( 'Validate::maxLen', array(
                                    'max' => 255,
                                    'message' => 'Permitido informar no máximo 255 caracteres.'
        	))
        ->validator( 'Validate::unique', array("message" => "Tipo de produto anteriormente cadastrado." )),
        Field::inst( 'ncm' )
		->validator( 'Validate::notEmpty', array(
                "message" => "Campo de preenchimento obrigatório."
            ))
			
	)
	->where( function ( $q ) {
	    $q->where( 'id_ncm', '0', '<>');
    })
	->process( $_POST )
	->json();
