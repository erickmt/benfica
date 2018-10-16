
/*
 * Editor client script for DB table produtos
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		ajax: 'controller/controller_produto.php',
		table: '#produtos',
		fields: [
			{
				"label": "Descrição:",
				"name": "produto.descricao",
				type:  "text"
			},
			{
                label: "Loja:",
                name: "produto.id_loja",
                type: "select",
                placeholder: ""
			},
			{
                label: "Tipo:",
                name: "produto.id_tipo_produto",
                type: "select",
                placeholder: ""
			},
			{
                label: "NF Detalhes:",
                name: "produto.nf_descricao",
                type: "select",
                placeholder: ""
			},
			{
				"label": "Modelo:",
				"name": "produto.modelo",
				"type": "select",
				"options": [
					"M",
					"F"
				],
                placeholder: ''
			},
			{
				"label": "Preço de varejo:",
				"name": "produto.preco_varejo", 
				attr:  {
					placeholder: '0.00'
				}
			},
			{
				"label": "Preço de atacado:",
				"name": "produto.preco_atacado",
				attr:  {
					placeholder: '0.00'
				}
			},
			{
				"label": "Preço de custo:",
				"name": "produto.preco_custo",
				attr:  {
					placeholder: '0.00'
				}
			},
			{
				"label": "Parâmetro alerta de falta:",
				"name": "produto.alerta_minimo"
			},
			{
				"label": "Parâmetro alerta de excesso:",
				"name": "produto.alerta_maximo"
			},
			{
				"label": "Peso (em gramas):",
				"name": "produto.peso"
			},
			{
				"label": "Quantidade em estoque:",
				"name": "produto.quantidade_estoque"
			},
			{
	            label:     "Inativo:",
	            name:      "produto.situacao",
	         	type:      "checkbox",
                separator: "|",
                options:   [
                    { label: '', value: 1 }
                ]
            }
		],
        i18n: {
            create: {
                button: "Criar",
                title:  "Cadastrar produto",
                submit: "Criar"
            },
            edit: {
                button: "Editar",
                title:  "Editar produto",
                submit: "Atualizar"
            },
            remove: {
                button: "Excluir",
                title:  "Excluir produto",
                submit: "Excluir",
                confirm: {
                    _: "Deseja excluir %d produtos?",
                    1: "Deseja excluir esse produto?"
                }
            },
            error: {
                system: "Ocorreu um erro! Contato o administrador do sistema!"
            },
            datetime: {
                previous: 'Anterior',
                next:     'Próxima',
                months:   [ 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dec' ],
                weekdays: [ 'Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab' ]
            }
        }
	} );


	editor.dependent('produto.descricao', function (val, data) {
		var upper = val.toUpperCase();
        editor.field('produto.descricao').val(upper);
	});


	editor.on( 'edit', function ( e, type ) {
     // Type is 'main', 'bubble' or 'inline'
		$.confirm({
		    title: 'Concluído',
		    content: 'Alterações realizadas com sucesso',
		    type: 'blue',
    		typeAnimated: true,
		    autoClose: 'OK|1000',
		    buttons: {
		        OK: function () {
		        }
		    }
		});
	} );

	editor.on( 'create', function ( e, type ) {
     // Type is 'main', 'bubble' or 'inline'
		$.confirm({
		    title: 'Concluído',
		    content: 'Cadastro realizadas com sucesso',
		    type: 'green',
    		typeAnimated: true,
		    autoClose: 'OK|1000',
		    buttons: {
		        OK: function () {
		        }
		    }
		});
	} );
   	
	$('#cadastro_rapido').on('click', function() {

     editor
	    .create( {
	        title: 'Cadastrar Produto',
	        buttons: 'Cadastrar'
	    } )
	    .set( values );
    });

    $('#descProduto').on('input',function(e){
    	pesquisarProduto(1,$('#descProduto').val());
	});
    
    $('#tipoProduto').on('input',function(e){
    	pesquisarProduto(2,$('#tipoProduto').val());
	});

    $('#ncmProduto').on('input',function(e){
    	pesquisarProduto(3,$('#ncmProduto').val());
	});
	
	$('#varejoProduto').on('input',function(e){
    	pesquisarProduto(5,$('#varejoProduto').val());
	});
	
	$('#atacadoProduto').on('input',function(e){
    	pesquisarProduto(6,$('#atacadoProduto').val());
	});

	$('#estoqueProduto').on('input',function(e){
    	pesquisarProduto(7,$('#estoqueProduto').val());
	});

    function pesquisarProduto(col, texto){
      table
        .columns(col)
        .search(texto)
        .draw();
    }

   	$('ul').on('click', 'a', function() {
      table
        .columns(6)
        .search($(this).text())
        .draw();
    });
 	
 	$('ul').on('click', 'a.todos', function() {

      table
        .search('')
        .columns(6)
        .search('')
        .draw();
    });

	var table = $('#produtos').DataTable( {
		dom: 'Bfrtip',
		ajax: 'controller/controller_produto.php',
		columns: [
			{
                "data": "loja.descricao"
            },
			{
				"data": "produto.descricao"
			},
			{
				"data": "tipo_produto.descricao"
			},
			{
				"data": "ncm.ncm"
			},
			{
				"data": "produto.preco_custo", render: $.fn.dataTable.render.number( '.', ',', 2, 'R$ ' )
			},
			{
				"data": "produto.preco_varejo", render: $.fn.dataTable.render.number( '.', ',', 2, 'R$ ' )
			},
			{
				"data": "produto.preco_atacado", render: $.fn.dataTable.render.number( '.', ',', 2, 'R$ ' )
			},
			{
				"data": "produto.quantidade_estoque"
			},
			{
                "data": "produto.situacao",
                render: function (data, type, row) {
                         // Filtering and display get the rendered string
                        return data == 0 ? "Ativo" : "Desativado";
                    // Otherwise just give the original data
                    
                },
                orderable:      false,
                className:      'situacaoCliente'
            }
		],
		select: true,
		lengthChange: false,
		buttons: [
			{ extend: 'create', editor: editor },
			{ extend: 'edit',   editor: editor },
			{
                extend: "selected",
                text: 'Desativar',
                action: function ( e, dt, node, config ) {
                    var rows = table.rows( {selected: true} ).indexes();
 
                    editor
                        .hide( editor.fields() )
                        .one( 'close', function () {
                            setTimeout( function () { // Wait for animation
                                editor.show( editor.fields() );
                            }, 500 );
                        } )
                        .edit( rows, {
                            title: 'Desativar',
                            message: 'Confirma desativar o item selecionado?',
                            buttons: 'Confirmar'
                        } )
                        .val( 'produto.situacao', 1 );
                }
            },
			{
                extend: "selected",
                text: 'Ativar',
                action: function ( e, dt, node, config ) {
                    var rows = table.rows( {selected: true} ).indexes();
 
                    editor
                        .hide( editor.fields() )
                        .one( 'close', function () {
                            setTimeout( function () { // Wait for animation
                                editor.show( editor.fields() );
                            }, 500 );
                        } )
                        .edit( rows, {
                            title: 'Ativar',
                            message: 'Confirma ativar o item selecionado?',
                            buttons: 'Confirmar'
                        } )
                        .val( 'produto.situacao', 0 );
                }
            }
		],
		language: {
			"decimal":        ",",
			"thousands": ".",
			"sEmptyTable": "Nenhum registro encontrado",
			"sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
			"sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
			"sInfoFiltered": "(Filtrados de _MAX_ registros)",
			"sInfoPostFix": "",
			"sInfoThousands": ".",
			"sLengthMenu": "_MENU_ resultados por página",
			"sLoadingRecords": "Carregando...",
			"sProcessing": "Processando...",
			"sZeroRecords": "Nenhum registro encontrado",
			"sSearch": "Filtrar produto",
			"oPaginate": {
				"sNext": "Próximo",
				"sPrevious": "Anterior",
				"sFirst": "Primeiro",
				"sLast": "Último"
			},
			"oAria": {
				"sSortAscending": ": Ordenar colunas de forma ascendente",
				"sSortDescending": ": Ordenar colunas de forma descendente"
			},
			"buttons": {
                    "create": "Novo",
                    "edit": "Editar",
                    "remove": "Excluir",
                    "copy": "Copiar",
                    "csv": "CSV",
                    "excel": "Excel",
                    "pdf": "PDF",
                    "print": "Imprimir",
                },
			select: {
                rows: {
                    _: '%d Linhas selecionadas',
                    0: 'Nenhum registro selecionado',
                    1: 'Linha selecionada'
                }
            }
		}
	} );

} );

}(jQuery));

