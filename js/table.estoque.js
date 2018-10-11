
/*
 * Editor client script for DB table produtos
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		ajax: 'controller/controller_estoque.php',
		table: '#estoque',
		fields: [

			
			
		]
	} );

	var table = $('#estoque').DataTable( {
		dom: 'Bfrtip',
		ajax: 'controller/controller_estoque.php',
		columns: [
			{
				"data": "produto.id_produto"
			},
			{
				"data": "produto.descricao"
			},
			{
				"data": "tipo_produto.descricao"
			},
			{
				"data": "produto.modelo"
			},
			{
				"data": "produto.quantidade_estoque"
			}
		],
		select: true,
		lengthChange: false,
		buttons: [

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
			"sSearch": "Filtrar Estoque",
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

	var table = $('#estoque_minimo').DataTable( {
		dom: 'Bfrtip',
		ajax: 'controller/controller_estoque_minimo.php',
		columns: [
			{
				"data": "produto.id_produto"
			},
			{
				"data": "produto.descricao"
			},
			{
				"data": "tipo_produto.descricao"
			},
			{
				"data": "produto.modelo"
			},
			{
				"data": "produto.alerta_minimo"
			},
			{
				"data": "produto.quantidade_estoque"
			}
		],
		select: true,
		lengthChange: false,
		buttons: [

		],
		"createdRow": function( row, data, dataIndex ) {
			if ( data.quantidade_estoque = "0" ) {
				$(row).addClass( 'danger' );
			}
		},
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
			"sSearch": "Filtrar Estoque",
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
	
	var table = $('#estoque_maximo').DataTable( {
	dom: 'Bfrtip',
		ajax: 'controller/controller_estoque_maximo.php',
		columns: [
			{
				"data": "produto.id_produto"
			},
			{
				"data": "produto.descricao"
			},
			{
				"data": "tipo_produto.descricao"
			},
			{
				"data": "produto.modelo"
			},
			{
				"data": "produto.alerta_maximo"
			},
			{
				"data": "produto.quantidade_estoque"
			}
		],
		select: true,
		lengthChange: false,
		buttons: [

		],
		"createdRow": function( row, data, dataIndex ) {
			if ( data.quantidade_estoque == data.alerta_maximo ) {
				$(row).addClass( 'warning' );
			}
		},
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
			"sSearch": "Filtrar Estoque",
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