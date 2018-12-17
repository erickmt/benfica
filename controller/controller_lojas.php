<?php

/*
 * Editor server script for DB table perfil_cliente
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

if (!isset($_SESSION)) {
	session_start();
}	  

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'loja', 'id' )
	->fields(
		Field::inst( 'id' ),
		Field::inst( 'descricao' )
		->validator( 'Validate::notEmpty', array(
                "message" => "Campo de preenchimento obrigatório."
            ) )
        ->validator( 'Validate::maxLen', array(
                                    'max' => 255,
                                    'message' => 'Permitido informar no máximo 255 caracteres.'
		)),
		Field::inst( 'telefone_nota' )
		->validator( 'Validate::notEmpty', array(
                "message" => "Campo de preenchimento obrigatório."
            ) )
        ->validator( 'Validate::maxLen', array(
                                    'max' => 15,
                                    'message' => 'Permitido informar no máximo 255 caracteres.'
        	)),
		Field::inst( 'token_tiny' )
		->validator( 'Validate::notEmpty', array(
				"message" => "Campo de preenchimento obrigatório."
			) )
		->validator( 'Validate::maxLen', array(
									'max' => 15,
									'message' => 'Permitido informar no máximo 255 caracteres.'
			))
		
	)
	->process( $_POST )
	->where( function ( $q ) {
		if($_SESSION['usuario']['id_loja'] <> 0)
	  		$q->where( 'id', $_SESSION['usuario']['id_loja'], '=');
	  	else
	  		$q->where( 'id', '0', '<>');
	})
	->json();
