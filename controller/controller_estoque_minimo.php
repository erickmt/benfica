<?php

/*
 * Editor server script for DB table produtos
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
Editor::inst( $db, 'produto', 'id_produto' )
	->fields(
		Field::inst( 'produto.id_produto' ),
		Field::inst( 'produto.descricao' ),
		Field::inst( 'produto.id_tipo_produto' )
            ->options( Options::inst()
                ->table( 'tipo_produto' )
                ->value( 'id_numero_produto' )
                ->label( 'descricao' )
            )
            ->validator( 'Validate::dbValues' ),
        Field::inst( 'tipo_produto.descricao' ),
		Field::inst( 'produto.modelo' ),
		Field::inst( 'produto.preco_varejo' ),
		Field::inst( 'produto.preco_atacado' ),
		Field::inst( 'produto.preco_custo' ),
		Field::inst( 'produto.quantidade_estoque' ),
		Field::inst( 'produto.alerta_minimo' ),
		Field::inst( 'produto.alerta_maximo' )



    )
    ->leftJoin( 'tipo_produto', 'tipo_produto.id_numero_produto', '=', 'produto.id_tipo_produto' )
	->where( function ( $q ) {
	  $q->where( 'produto.quantidade_estoque', 'produto.alerta_minimo', '<=', false);
	} )
	->process( $_POST )
	->json();
