
/*
 * Editor client script for DB table forma_pagamento
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		ajax: 'controller/controller_forma_pagamento.php',
		table: '#forma_pagamento',
		fields: [
			{
                label: "Loja:",
                name: "forma_pagamento.id_loja",
                type: "select",
                placeholder: ""
            },
            {
				"label": "Descrição:",
				"name": "forma_pagamento.descricao"
			},
			{
				"label": "Taxa em Porcentagem:",
				"name": "forma_pagamento.porcentagem_taxa"
			},
			{
	            label:     "Inativo:",
	            name:      "forma_pagamento.situacao",
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
                title:  "Cadastrar Forma de Pagamento",
                submit: "Criar"
            },
            edit: {
                button: "Editar",
                title:  "Editar Forma de Pagamento",
                submit: "Atualizar"
            },
            remove: {
                button: "Excluir",
                title:  "Excluir Forma de Pagamento",
                submit: "Excluir",
                confirm: {
                    _: "Deseja excluir %d formas de pagamento?",
                    1: "Deseja excluir essa forma de pagamento?"
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

	$('ul').on('click', 'a', function() {

      table
        .columns(3)
        .search($(this).text())
        .draw();
    });

    $('ul').on('click', 'a.todos', function() {
      table
        .search('')
        .columns(3)
        .search('')
        .draw();
    });
	
	var table = $('#forma_pagamento').DataTable( {
		dom: 'Bfrtip',
		ajax: 'controller/controller_forma_pagamento.php',
		columns: [
            {
                "data": "loja.descricao"
            },
            {
				"data": "forma_pagamento.descricao"
			},
			{
				"data": "forma_pagamento.porcentagem_taxa", render: $.fn.dataTable.render.number( ',', '.', 3, '% ' )
			},
			{
                "data": "forma_pagamento.situacao",
                render: function (data, type, row) {
                         // Filtering and display get the rendered string
                        return data == 0 ? "Ativo" : "Desativado";
                    // Otherwise just give the original data
                    
                },
                orderable:      false,
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
			"sSearch": "Filtrar forma de pagamento: ",
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

