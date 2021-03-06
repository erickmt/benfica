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

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'perfil_cliente', 'id_perfil' )
	->fields(
		Field::inst( 'id_perfil' ),
		Field::inst( 'descricao' )
		->validator( 'Validate::notEmpty', array(
                "message" => "Campo de preenchimento obrigatório."
            ) )
        ->validator( 'Validate::maxLen', array(
                                    'max' => 255,
                                    'message' => 'Permitido informar no máximo 255 caracteres.'
        	)),		

		Field::inst( 'quantidade_minima' )
		->validator( 'Validate::numeric', array("message" => "Permitido informar somente números." ))				
		->validator( 'Validate::notEmpty', array(
                "message" => "Campo de preenchimento obrigatório."
            ) )
        ->validator( 'Validate::minMaxNum', array(
                                    'min' => 1,
                                    'max' => 999,
                                    'message' => 'Permitido informar apenas números positivos.'
        	)),		

		Field::inst( 'dias_validade' )
		->validator( 'Validate::numeric', array("message" => "Permitido informar somente números." ))				
		->validator( 'Validate::notEmpty', array(
                "message" => "Campo de preenchimento obrigatório."
            ) )
        ->validator( 'Validate::minMaxNum', array(
                                    'min' => 1,
                                    'max' => 9999,
                                    'message' => 'Permitido informar apenas números positivos.'
        ))
		
	)
	->where( function ( $q ) {
	    $q->where( 'descricao', 'Atacadista');
	} )
	->process( $_POST )
	->json();
