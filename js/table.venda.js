
/*
 * Editor client script for DB table venda
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		ajax: 'php/table.venda.php',
		table: '#venda',
		fields: [
			{
				"label": "id_cliente:",
				"name": "id_cliente"
			},
			{
				"label": "id_vendedor:",
				"name": "id_vendedor"
			},
			{
				"label": "id_perfil:",
				"name": "id_perfil"
			},
			{
				"label": "dta_venda:",
				"name": "dta_venda",
				"type": "datetime",
				"format": "MM\/DD\/YY"
			},
			{
				"label": "valor_total_pago\t:",
				"name": "valor_total_pago"
			},
			{
				"label": "valor_total_comissao:",
				"name": "valor_total_comissao"
			},
			{
				"label": "valor_total_taxas:",
				"name": "valor_total_taxas"
			},
			{
				"label": "valor_total_outros:",
				"name": "valor_total_outros"
			},
			{
				"label": "valor_total_liquido:",
				"name": "valor_total_liquido"
			}
		]
	} );

	var table = $('#venda').DataTable( {
		dom: 'Bfrtip',
		ajax: 'php/table.venda.php',
		columns: [
			{
				"data": "id_cliente"
			},
			{
				"data": "id_vendedor"
			},
			{
				"data": "id_perfil"
			},
			{
				"data": "dta_venda"
			},
			{
				"data": "valor_total_pago"
			},
			{
				"data": "valor_total_comissao"
			},
			{
				"data": "valor_total_taxas"
			},
			{
				"data": "valor_total_liquido"
			}
		],
		select: true,
		lengthChange: false,
		buttons: [
			{ extend: 'create', editor: editor },
			{ extend: 'edit',   editor: editor },
			{ extend: 'remove', editor: editor }
		]
	} );
} );

}(jQuery));

