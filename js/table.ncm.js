
/*
 * Editor client script for DB table ncm
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		ajax: 'controller/controller_ncm.php',
		table: '#ncm',
		fields: [
			{
				"label": "Descrição:",
				"name": "ncm.descricao"
			},
			{
				"label": "NCM:",
				"name": "ncm.ncm"
			},
			{
                label: "Loja:",
                name: "ncm.id_loja",
                type: "select",
                placeholder: ""
            }
		],
        i18n: {
            create: {
                button: "Criar",
                title:  "Cadastrar NCM",
                submit: "Cadastrar"
            },
            edit: {
                button: "Editar",
                title:  "Editar NCM",
                submit: "Atualizar"
            },
            remove: {
                button: "Excluir",
                title:  "Excluir NCM",
                submit: "Excluir",
                confirm: {
                    _: "Deseja excluir %d NCMs?",
                    1: "Deseja excluir esse NCM?"
                }
            },
            error: {
                system: "Ocorreu um erro! Entre em contato com o administrador do sistema!"
            },
            datetime: {
                previous: 'Anterior',
                next:     'Próxima',
                months:   [ 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dec' ],
                weekdays: [ 'Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab' ]
            }
        }
	} );


	var table = $('#ncm').DataTable( {
		dom: 'Bfrtip',
		ajax: 'controller/controller_ncm.php',
		columns: [
			{
				"data": "loja.descricao"
			},
			{
				"data": "ncm.descricao"
			},
			{
				"data": "ncm.ncm"
			}
		],
		select: true,
		lengthChange: false,
		buttons: [
			{ extend: 'create', editor: editor },
			{ extend: 'edit',   editor: editor }
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
			"sSearch": "Filtrar NCM: ",
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

