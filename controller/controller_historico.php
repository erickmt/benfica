<?php

/*
 * Editor server script for DB table venda
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
		Field::inst( 'venda.id_cliente' )
		->options( Options::inst()
                ->table( 'cliente' )
                ->value( 'id_cliente' )
                ->label( 'nome' )
            )
            ->validator( 'Validate::dbValues' ),
        Field::inst( 'cliente.nome' ),
        Field::inst( 'cliente.id_cliente' ),
		Field::inst( 'venda.id_vendedor' )
		->options( Options::inst()
                ->table( 'vendedor' )
                ->value( 'id_vendedor' )
                ->label( 'nome' )
            )
            ->validator( 'Validate::dbValues' ),

        Field::inst( 'venda.id_loja' )
		->options( Options::inst()
                ->table( 'loja' )
                ->value( 'id' )
                ->label( 'descricao' )
            )
            ->validator( 'Validate::dbValues' ),
        Field::inst( 'loja.descricao' ),

        Field::inst( 'cliente.numero_rg' ),
        Field::inst( 'vendedor.nome' ),    
		Field::inst( 'venda.id_perfil' ),
		Field::inst( 'venda.dta_venda' )
			->validator( 'Validate::dateFormat', array( 'format'=>'Y-m-d' ) )
			->getFormatter( 'Format::date_sql_to_format', 'd-m-Y' )
			->setFormatter( 'Format::date_format_to_sql', 'Y-m-d' )->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório" )),
		Field::inst( 'venda.valor_total_pago' ),
		Field::inst( 'venda.valor_total_comissao' ),
		Field::inst( 'venda.valor_total_taxas' ),
		Field::inst( 'venda.valor_total_outros' ),
		Field::inst( 'venda.valor_total_liquido' ),
		Field::inst( 'venda.dta_cancelamento_venda' )
	)
	->leftJoin( 'cliente', 'venda.id_cliente', '=', 'cliente.id_cliente' )
	->leftJoin( 'vendedor', 'venda.id_vendedor', '=', 'vendedor.id_vendedor' )
	->leftJoin( 'loja', 'venda.id_loja', '=', 'loja.id' )
	->where( function ( $q ) {
		$q->where( 'venda.dta_cancelamento_venda', '0000-00-00', '=');
	} )
	->process( $_POST )
	->json();
