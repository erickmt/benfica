
/*
 * Editor client script for DB table lojas
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		ajax: 'controller/controller_lojas.php',
		table: '#lojas',
		fields: [
			{
				"label": "Descrição:",
				"name": "descricao",
			},
			{
				"label": "Token Tiny:",
				"name": "token_tiny",
			},
			{
				"label": "Telefone Nota:",
				"name": "telefone_nota",
			},
			{
				"label": "Contato Rodapé Nota:",
				"name": "contato_nota",
				type: "textarea",
				attr:  {
					class: "form-control",
				    rows: "5",
				    cols: "8",
				    length : "255",
					placeholder: 'Máximo de 255 caracteres'
				}
			},
			{
				label: "Detalhes Nota:",
				name: "descricao_nota",
				type: "textarea",
				className: 'block',
				attr:  {
					class: "form-control",
				    rowspan: "7",
					cols: "12"
				}
			}
		],
        i18n: {
            create: {
                button: "Criar",
                title:  "Cadastrar Loja",
                submit: "Criar"
            },
            edit: {
                button: "Editar",
                title:  "Editar Loja",
                submit: "Atualizar"
            },
            remove: {
                button: "Excluir",
                title:  "Excluir Loja",
                submit: "Excluir",
                confirm: {
                    _: "Deseja excluir %d lojas?",
                    1: "Deseja excluir essa loja?"
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

	var table = $('#lojas').DataTable( {
		dom: 'Bfrtip',
		ajax: 'controller/controller_lojas.php',
		columns: [
			{
				"data": "id"
			},
			{
				"data": "descricao"
			},
			{
				"data": "telefone_nota"
			}
		],
		select: true,
		lengthChange: false,
		buttons: [
			{ extend: 'create', editor: editor },
			{ extend: 'edit',   editor: editor },
			{ extend: 'remove', editor: editor },
			// {
            //     extend: "selected",
            //     text: 'Nota',
            //     action: function ( e, dt, node, config ) {
			// 		var rows = table.rows( {selected: true} ).indexes();
			// 		abreModalEditaNota(rows[0]);
            //     }
            // }
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
			"sSearch": "Filtrar lojas:",
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

