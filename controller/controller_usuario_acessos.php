<?php

/*
 * Editor server script for DB table usuario
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
Editor::inst( $db, 'usuario', 'id_usuario' )
	->fields(
		Field::inst( 'usuario.id_usuario' ),
		Field::inst( 'usuario.login' )
		->validator( 'Validate::notEmpty', array(
                "message" => "Campo de preenchimento obrigatório."
            ) )
        ->validator( 'Validate::maxLen', array(
                                    'max' => 32,
                                    'message' => 'Permitido informar no máximo 32 caracteres.'
        	)),

		Field::inst( 'usuario.senha' )
		->validator( 'Validate::notEmpty', array(
                "message" => "Campo de preenchimento obrigatório."
            ) ) // This field is required
            ->validator( 'Validate::maxLen', array(
                                    'max' => 32,
                                    'message' => 'Permitido informar no máximo 32 caracteres.'
                                ))
			->setFormatter( function ( $val ) {
		        return md5( $val );
		    }),
		Field::inst( 'usuario.perfil' )
		->validator( 'Validate::notEmpty', array(
                "message" => "Campo de preenchimento obrigatório."
            ) ),
        
		Field::inst( 'usuario.id_loja' )
		->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." ))
			->options( Options::inst()
                ->table( 'loja' )
                ->value( 'id' )
                ->label( 'descricao' )
            )
			->validator( 'Validate::dbValues' ),

        Field::inst( 'loja.descricao' )
		->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." )),
		
		Field::inst( 'usuario.situacao' )
		->setFormatter( function ( $val, $data, $opts ) {
			   return ! $val ? 0 : 1;
		} )
    )
    ->leftJoin( 'loja', 'loja.id', '=', 'usuario.id_loja' )      
	->where( function ( $q ) {
	    $q->where( 'login', 'administrador', '<>');
    } )
	->process( $_POST )
	->json();
