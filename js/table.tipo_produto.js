
/*
 * Editor client script for DB table tipo_produto
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		ajax: 'controller/controller_tipo_produto.php',
		table: '#tipo_produto',
		fields: [
			{
				"label": "Descrição:",
				"name": "descricao"
			}
		],
        i18n: {
            create: {
                button: "Criar",
                title:  "Cadastrar Tipo de Produto",
                submit: "Cadastrar"
            },
            edit: {
                button: "Editar",
                title:  "Editar Tipo de Produto",
                submit: "Atualizar"
            },
            remove: {
                button: "Excluir",
                title:  "Excluir Tipo de Produto",
                submit: "Excluir",
                confirm: {
                    _: "Deseja excluir %d tipos de produtos?",
                    1: "Deseja excluir esse tipo de produto?"
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


	var table = $('#tipo_produto').DataTable( {
		dom: 'Bfrtip',
		ajax: 'controller/controller_tipo_produto.php',
		columns: [
			{
				"data": "descricao"
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
			"sSearch": "Filtrar tipo de produto: ",
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

