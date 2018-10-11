<?php

/**
 * Produto
 *
 * Operações relacionadas a página de Produtos
 * 
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
Editor::inst( $db, 'produto', 'id_produto' )
	->fields(
		Field::inst( 'produto.id_produto' ),
		Field::inst( 'produto.descricao' )
		->validator( 'Validate::notEmpty', array(
            "message" => "Campo de preenchimento obrigatório."
        ) ) // This field is required
        ->validator( 'Validate::maxLen', array(
                                    'max' => 255,
                                    'message' => 'Permitido informar no máximo 255 caracteres.'
       	)),		
		
		Field::inst( 'produto.id_tipo_produto' ) 
			->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." ))
            ->options( Options::inst()
                ->table( 'tipo_produto' )
                ->value( 'id_numero_produto' )
                ->label( 'descricao' )
            )
            ->validator( 'Validate::dbValues' ),
		
		Field::inst( 'produto.nf_descricao' ) 
			->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." ))
            ->options( Options::inst()
                ->table( 'ncm' )
                ->value( 'id_ncm' )
                ->label( 'descricao' )
            )
            ->validator( 'Validate::dbValues' ),

        Field::inst( 'tipo_produto.descricao' )
        	->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." )),

		Field::inst( 'produto.modelo' ),

		Field::inst( 'produto.preco_varejo' )
		->validator( 'Validate::numeric', array("message" => "Permitido informar somente números." ))		
		->validator( 'Validate::notEmpty', array(
                "message" => "Campo de preenchimento obrigatório."
            ) )
        ->validator( 'Validate::maxLen', array(
                                    'max' => 10,
                                    'message' => 'Permitido informar no máximo 10 caracteres.'
        	)),		

		Field::inst( 'produto.preco_atacado' )
		->validator( 'Validate::numeric', array("message" => "Permitido informar somente números." ))		
		->validator( 'Validate::notEmpty', array(
                "message" => "Campo de preenchimento obrigatório."
            ) )
        ->validator( 'Validate::maxLen', array(
                                    'max' => 10,
                                    'message' => 'Permitido informar no máximo 10 caracteres.'
        	)),		

		Field::inst( 'produto.preco_custo' )
		->setFormatter( 'Format::ifEmpty', 0 )
		->validator( 'Validate::numeric', array("message" => "Permitido informar somente números." ))	
        ->validator( 'Validate::maxLen', array(
                                    'max' => 10,
                                    'message' => 'Permitido informar no máximo 10 caracteres.'
        	)),		

		Field::inst( 'produto.peso')
			->setFormatter( 'Format::ifEmpty', 0 )
			->validator( 'Validate::numeric', array("message" => "Permitido informar somente números." )),		

		Field::inst( 'produto.alerta_minimo' )
			->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." ))
			->validator( 'Validate::numeric', array("message" => "Permitido informar somente números." ) ),

		Field::inst( 'produto.alerta_maximo' )
			->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." ))
			->validator( 'Validate::numeric', array("message" => "Permitido informar somente números." ) ),

		Field::inst( 'produto.quantidade_estoque' )
			->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." ))
			->validator( 'Validate::numeric', array("message" => "Permitido informar somente números." ) ),

		Field::inst( 'produto.id_loja' )
		->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." ))
			->options( Options::inst()
                ->table( 'loja' )
                ->value( 'id' )
				->label( 'descricao' )
				->where( function ( $q ) {
					if($_SESSION['usuario']['id_loja'] <> 0)
						$q->where( 'loja.id', $_SESSION['usuario']['id_loja'], '=');
					$q->where( 'id', '0', '<>');
				} )
            )
            ->validator( 'Validate::dbValues' ),

        Field::inst( 'loja.descricao' )
		->validator( 'Validate::notEmpty', array("message" => "Campo de preenchimento obrigatório." )),
		
		Field::inst( 'produto.situacao' )
		 ->setFormatter( function ( $val, $data, $opts ) {
                return ! $val ? 0 : 1;
         } )
    )
    ->leftJoin( 'tipo_produto', 'tipo_produto.id_numero_produto', '=', 'produto.id_tipo_produto' )
    ->leftJoin( 'ncm', 'ncm.id_ncm', '=', 'produto.nf_descricao' )
    ->leftJoin( 'loja', 'loja.id', '=', 'produto.id_loja' )
	->where( function ( $q ) {
		if($_SESSION['usuario']['id_loja'] <> 0)
	  		$q->where( 'loja.id', $_SESSION['usuario']['id_loja'], '=');
	})
	->process( $_POST )
	->json();

    