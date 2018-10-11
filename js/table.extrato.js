
/*
 * Editor client script for DB table produtos
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		ajax: 'controller/controller_extrato.php',
		table: '#extrato_atual',
		fields: [
			
		]
	} );

	var table = $('#extrato_atual').DataTable( {
		dom: 'Bfrtip',
		ajax: 'controller/controller_extrato.php',
		columns: [
			{
				"data": "venda.id_venda"
			},
			{
				"data": "venda.dta_venda"
			},
			{
				"data": "vendedor.nome"
			},
			{
				"data": null, render: function ( data, type, row ) 
				{
                // Combine the first and last names into a single table field
                return (data.venda.valor_total_pago - data.venda.valor_total_taxas - data.venda.valor_total_outros).toFixed(2);
            	} 
			},
			{
				"data": "venda.valor_total_taxas"
			},
			{
				"data": "venda.valor_total_comissao", render: $.fn.dataTable.render.number( '.', ',', 2, 'R$ ' )
			},
			{
				"data": "venda.id_perfil",
                render: function (data, type, row) {
                         // Filtering and display get the rendered string
                        return data == 1 ? "Varejo" : "Atacado";
                    // Otherwise just give the original data
                    
                }
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
			"sSearch": "Filtrar",
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

	var table = $('#extrato_passado').DataTable( {
		dom: 'Bfrtip',
		ajax: 'controller/controller_extrato_passado.php',
		columns: [
			{
				"data": "venda.id_venda"
			},
			{
				"data": "venda.dta_venda"
			},
			{
				"data": "vendedor.nome"
			},
			{
				"data": null, render: function ( data, type, row ) 
				{
                // Combine the first and last names into a single table field
                return (data.venda.valor_total_pago - data.venda.valor_total_taxas - data.venda.valor_total_outros).toFixed(2);
            	} 
			},
			{
				"data": "venda.valor_total_taxas"
			},
			{
				"data": "venda.valor_total_comissao", render: $.fn.dataTable.render.number( '.', ',', 2, 'R$ ' )
			},
			{
				"data": "venda.id_perfil",
                render: function (data, type, row) {
                         // Filtering and display get the rendered string
                        return data == 1 ? "Varejo" : "Atacado";
                    // Otherwise just give the original data
                    
                }
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
			"sSearch": "Filtrar",
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