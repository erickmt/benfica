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
Editor::inst( $db, 'venda', 'id_venda' )
	->fields(
		Field::inst( 'venda.id_venda' ),
        Field::inst( 'venda.id_perfil'),
		Field::inst( 'venda.dta_venda')
			->getFormatter( 'Format::date_sql_to_format', 'd-m-Y' ),
		Field::inst( 'venda.valor_total_pago'),
		Field::inst( 'venda.valor_total_outros'),
		Field::inst( 'venda.valor_total_comissao'),
		Field::inst( 'venda.valor_total_taxas'),
		Field::inst( 'venda.id_vendedor' )
            ->options( Options::inst()
                ->table( 'vendedor' )
                ->value( 'id_vendedor' )
                ->label( 'nome' )
            )
            ->validator( 'Validate::dbValues' ),
        Field::inst( 'vendedor.nome')
    )
	->leftJoin( 'vendedor', 'venda.id_vendedor', '=', 'vendedor.id_vendedor' )
	->where( function ( $q ) {
		$q->where( 'left(venda.dta_venda,7)', 'left(adddate(curdate(), INTERVAL -1 MONTH),7)', '=', false);
	} )
	->process( $_POST )
	->json();
