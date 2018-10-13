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
if (!isset($_SESSION)) {
	session_start();
}	  

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'ncm', 'id_ncm' )
	->fields(
		Field::inst( 'ncm.descricao' )
		->validator( 'Validate::notEmpty', array(
                "message" => "Campo de preenchimento obrigatório."
            ))
        ->validator( 'Validate::maxLen', array(
                                    'max' => 255,
                                    'message' => 'Permitido informar no máximo 255 caracteres.'
        	))
        ->validator( 'Validate::unique', array("message" => "Descriçao anteriormente cadastrado." )),

        Field::inst( 'ncm.ncm' )
		->validator( 'Validate::notEmpty', array(
                "message" => "Campo de preenchimento obrigatório."
            ))
		->validator( 'Validate::unique', array("message" => "NCM anteriormente cadastrado." )),

		Field::inst( 'ncm.id_loja' )
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
				} )
            )
            ->validator( 'Validate::dbValues' ),

        Field::inst( 'loja.descricao' )
		->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." ))
			
	)
	->leftJoin( 'loja', 'loja.id', '=', 'ncm.id_loja' )
	->where( function ( $q ) {
		if($_SESSION['usuario']['id_loja'] <> 0)
	  	{
	  		$q->where( 'loja.id', $_SESSION['usuario']['id_loja'], '=');
	  		$q->or_where( 'loja.id', '0', '=');
	  		$q->and_where( 'ncm.id_ncm', '0', '<>');
		}
	  	else
	  		$q->where( 'ncm.id_ncm', '0', '<>');
	})
	->process( $_POST )
	->json();
