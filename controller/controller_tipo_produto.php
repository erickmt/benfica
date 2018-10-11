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
$db->sql( "CREATE TABLE IF NOT EXISTS `tipo_produto` (
	`id_numero_produto` int(10) NOT NULL auto_increment,
	`descricao` varchar(255),
	PRIMARY KEY( `id_numero_produto` )
);" );

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'tipo_produto', 'id_numero_produto' )
	->fields(
		Field::inst( 'descricao' )
		->validator( 'Validate::notEmpty', array(
                "message" => "Campo de preenchimento obrigatório."
            ))
        ->validator( 'Validate::maxLen', array(
                                    'max' => 255,
                                    'message' => 'Permitido informar no máximo 255 caracteres.'
        	))
        ->validator( 'Validate::unique', array("message" => "Tipo de produto anteriormente cadastrado." ))
			
	)
	->process( $_POST )
	->json();
