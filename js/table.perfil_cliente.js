
/*
 * Editor client script for DB table perfil_cliente
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		ajax: 'controller/controller_perfil_cliente.php',
		table: '#perfil_cliente',
		fields: [
			{
				"label": "Descrição:",
				"name": "descricao",
			},
			{
				"label": "Quantidade mínima de produtos:",
				"name": "quantidade_minima",
			},
			{
				"label": "Validade do perfil (em dias):",
				"name": "dias_validade",
			}
		],
        i18n: {
            create: {
                button: "Criar",
                title:  "Cadastrar Perfil",
                submit: "Criar"
            },
            edit: {
                button: "Editar",
                title:  "Editar Perfil",
                submit: "Atualizar"
            },
            remove: {
                button: "Excluir",
                title:  "Excluir Perfil",
                submit: "Excluir",
                confirm: {
                    _: "Deseja excluir %d perfis?",
                    1: "Deseja excluir esse perfil?"
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

	var table = $('#perfil_cliente').DataTable( {
		dom: 'Bfrtip',
		ajax: 'controller/controller_perfil_cliente.php',
		columns: [
			{
				"data": "descricao"
			},
			{
				"data": "quantidade_minima"
			},
			{
				"data": "dias_validade"
			}
		],
		select: true,
		lengthChange: false,
		buttons: [
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
			"sSearch": "Filtrar perfil de cliente:",
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

