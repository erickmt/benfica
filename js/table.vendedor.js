
/*
 * Editor client script for DB table vendedor
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		ajax: 'controller/controller_vendedor.php',
		table: '#vendedor',
		fields: [
			{
				"label": "Nome completo:",
				"name": "vendedor.nome"
			},
			{
                label: "Loja:",
                name: "vendedor.id_loja",
                type: "select",
                "placeholder": "Selecionar a loja"
			},
			{
				"label": "Comissão:",
				"name": "vendedor.porcentagem_comissao",
				attr:  {
					placeholder: 'Ex: 0.00'
				}
			},
			{
				"label": "E-mail:",
				"name": "vendedor.email"
			},
			{
				"label": "Número de identidade:",
				"name": "vendedor.numero_rg",
				attr:  {
					placeholder: 'Ex: 9999999999'
				}
			},
			{
				"label": "CPF:",
				"name": "vendedor.cpf"
			},
			{
				"label": "Telefone Celular:",
				"name": "vendedor.telefone_01",
				attr:  {
					placeholder: 'Ex: 31999999999'
				}
			},
			{
				"label": "Logradouro:",
				"name": "vendedor.logradouro"
			},
			{
				"label": "CEP:",
				"name": "vendedor.cep",
				attr:  {
					placeholder: 'Ex: 30600100'
				}
			},
			{
				"label": "Cidade:",
				"name": "vendedor.cidade"
			},
			{
				"label": "Estado:",
				"name": "vendedor.estado",
				"type": "select",
				"options": [
					"AC",	 
				"AL",	 
				"AP",	 
				"AM",	 
				"BA",	 
				"CE",	 
				"DF",	 
				"ES",	 
				"GO",	 
				"MA",	 
				"MT",	 
				"MS",	 
				"MG",	 
				"PA",	 
				"PB",	 
				"PR",	 
				"PE",	 
				"PI",	 
				"RJ",	 
				"RN",	 
				"RS",	 
				"RO",	 
				"RR",	 
				"SC",	 
				"SP",	 
				"SE",
				"TO"
				],
				placeholder: ''
			},
			{
				"label": "País:",
				"name": "vendedor.pais"
			},
			{
	            label:     "Inativo:",
	            name:      "vendedor.situacao",
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
                title:  "Cadastrar Vendedor",
                submit: "Cadastrar"
            },
            edit: {
                button: "Editar",
                title:  "Editar Vendedor",
                submit: "Atualizar"
            },
            remove: {
                button: "Excluir",
                title:  "Excluir Vendedor",
                submit: "Excluir",
                confirm: {
                    _: "Deseja exlcuir os %d vendedores selecionados?",
                    1: "Deseja exlcuir o vendedor selecionado?"
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

	$('ul').on('click', 'a', function() {
      table
        .columns(5)
        .search($(this).text())
        .draw();
    });
 	
 	$('ul').on('click', 'a.todos', function() {
      table
        .search('')
        .columns(5)
        .search('')
        .draw();
    });

	var table = $('#vendedor').DataTable( {
		dom: 'Bfrtip',
		ajax: 'controller/controller_vendedor.php',
		columns: [
			{
				"data": "vendedor.id_vendedor"
			},
			{
				"data": "loja.descricao"
			},
			{
				"data": "vendedor.nome"
			},
			{
				"data": "vendedor.email"
			},
			{
				data: null, render: function ( data, type, row ) {
                // Combine the first and last names into a single table field
                return data.vendedor.porcentagem_comissao+'%';
				}
			},
			{
                "data": "vendedor.situacao",
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
			"decimal": ",",
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
			"sSearch": "Filtrar vendedores",
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