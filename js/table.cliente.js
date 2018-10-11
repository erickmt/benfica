
/*
 * Editor client script for DB table cliente
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		ajax: 'controller/controller_cliente.php',
		table: '#cliente',
		formOptions: {
	        main: {
	            onBackground: 'none'
	        }
    	},
		fields: [
			{
				"label": "Nome completo:",
				"name": "cliente.nome"
			},
			{
				"label": "Tipo:",
				"name": "cliente.tipo",
				"type": "select",
				"placeholder": 'Selecione o perfil',
				"options": [
					"PF",
					"PJ	",
				]
			},
			{
				"label": "CPF / CNPJ",
				"name": "cliente.cpf"
			},
			{
				"label": "Inscrição Estadual",
				"name": "cliente.ie"
			},
			{
				"label": "Data de nascimento:",
				"name": "cliente.dta_nascimento",
				"type": "date",
				"format": "DD-MM-YYYY"
			},
			{
				"label": "Email",
				"name": "cliente.email"
			},
			{
				"label": "Vendedor:",
				"name": "cliente.id_vendedor",
				type: "select",
				def: "1"
			},
			{
				"label": "Telefone Celular:",
				"name": "cliente.telefone_01",
				attr:  {
					placeholder: 'Ex: 31999999999'
				}
			},
			
			
			/*{
                label: "Status:",
                name: "done",
                type: "todo", // Using the custom field type
                def: 0
            }, */
			{
				"label": "CEP:",
				"name": "cliente.cep",
				attr:  {
					placeholder: '30600100'
				}
			},
			{
				"label": "Rua:",
				"name": "cliente.logradouro"
			},
			{
				"label": "Numero:",
				"name": "cliente.numero"
			},
			{
				"label": "Bairro:",
				"name": "cliente.bairro"
			},

			{
				"label": "Cidade:",
				"name": "cliente.cidade"
			},
			{
				"label": "Estado:",
				"name": "cliente.estado",
				type: "select",
                options: [ 	"AC",
                			"AL",
							"AM",
							"AP",
							"BA",
							"CE",
							"DF",
							"ES",
							"GO",
							"MA",
							"MG",
							"MS",
							"MT",
							"PA",
							"PB",
							"PE",
							"PI",
							"PR",
							"RJ",
							"RN",
							"RS",
							"RO",
							"RR",
							"SC",
							"SE",
							"SP",
							"TO"],
                def: "MG"
			},
			{
				"label": "País:",
				"name": "cliente.pais",
				def: "BR"
			},
			{
				"label": "Crédito:",
				"name": "cliente.valor_credito",
				attr:  {
					placeholder: 'R$ 0,00'
				}
			},
			{
				"label": "Observação:",
				"name": "cliente.observacao",
				type: "textarea",
				attr:  {
					class: "form-control",
				    rows: "5",
				    cols: "8",
				    length : "255",
					placeholder: 'Máximo de 255 caracteres'
				}
			}
			
		],

        i18n: {
            create: {
                button: "Criar",
                title:  "Cadastrar Cliente",
                submit: "Criar"
            },
            edit: {
                button: "Editar",
                title:  "Editar cliente",
                submit: "Atualizar"
            },
            remove: {
                button: "Excluir",
                title:  "Excluir Cliente",
                submit: "Excluir",
                confirm: {
                    _: "Deseja exlcuir os %d clientes selecionados?",
                    1: "Deseja exlcuir o cliente selecionado?"
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
	
    editor.dependent( 'cliente.tipo', function ( val ) {
    	
    	//editor.field('cliente.ie').val('');
    	//editor.field('cliente.dta_nascimento').val('');

        return val === 'PF' ?
            { hide: ['cliente.ie'], show: ['cliente.dta_nascimento'] } :
            { show: ['cliente.ie'], hide: ['cliente.dta_nascimento'] };
    } );


	editor.dependent('cliente.cep', function (val, data) {
	 $.getJSON("https://viacep.com.br/ws/"+ val +"/json/?callback=?", function(dados) {
        if (!("erro" in dados)) {
                editor.field('cliente.logradouro').val(dados.logradouro);
                editor.field('cliente.bairro').val(dados.bairro);
                editor.field('cliente.cidade').val(dados.localidade);
                editor.field('cliente.estado').val(dados.uf);
            } //end if.
        });
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
	
	$('#cadastro_cliente_rapido').on('click', function() {

     editor
	    .create( {
	        title: 'Cadastrar Cliente',
	        buttons: 'Cadastrar'
	    } )
	    .set( values );
    });


	/*$('ul').on('click', 'a', function() {

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
    });*/

	var table = $('#cliente').DataTable( {
		dom: 'Bfrtip',
		ajax: 'controller/controller_cliente.php',
		columns: [
			{
				"data": "cliente.id_cliente"
			},
			{
				"data": "cliente.nome"
			},
			{
				"data": "perfil_cliente.descricao",
				className: 'edita_perfil'
			},
			{
				"data": "cliente.telefone_01"
			},
			{
				"data": "cliente.cpf"
			},
			{
				"data": "cliente.dta_ultima_compra"
			},
			{
                "data": "cliente.situacao",
                render: function (data, type, row) {
                         // Filtering and display get the rendered string
                        //return data == 0 ? "<button class='btn btn-xs btn-primary'><i class='fa fa-thumbs-o-up' aria-hidden='true'></i></button>" : "<button class='btn btn-xs'><i class='fa fa-thumbs-down' aria-hidden='true'></i></button>";
                        return "<center><button class='btn btn-xs btn-primary'><i class='fa fa-thumbs-o-up' aria-hidden='true'></i></button></center>";
                    // Otherwise just give the original data
                    
                },
                orderable:      false,
                className:      'situacaoCliente'
            },
            {
				className:      'details-control',
                orderable:      false,
                data:           null,
                defaultContent: ''
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
			"sSearch": "Filtrar cliente",
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

	$('#cliente').on( 'click', 'td.edita_perfil', function () {

    	var id_selecionado = table.cell( this, 0 ).data();
    	var cliente_selecionado = table.cell( this, 1 ).data();
    	var perfil_selecionado = table.cell( this, 2 ).data();
    	//alert(cell);
    	$.confirm({
		    title: 'Editar perfil '+perfil_selecionado+' de </br></br>'+cliente_selecionado,
		    content: '' +
		    '<form id="form_comissao" name="form_comissao" >' +
		    '<div class="form-group">' +
		    '<label>Deseja alterar o Perfil do cliente? </br> Essa operação não poderá ser desfeita!</label>' +
		    '</div>' +
		    '</form>',
		    buttons: {
		        formSubmit: {
		            text: 'Alterar',
		            btnClass: 'btn-blue',
		            action: function () {
		               $.confirm({
						    title: 'Confirmar',
						    content: 'Deseja alterar o perfil do cliente?',
						    buttons: {
						        confirmar: function () {
						        	$.ajax({
										   type: "POST",
										   url: "controller/controller_edita_perfil.php",
										   data: "dado_cliente=" + id_selecionado + ',' + perfil_selecionado,
										   success: function(msg){
										   $.alert('Perfil alterado com sucesso!');
										   table.ajax.reload();
										}
									});
						        },
						        cancelar: function () {
						            $.alert('Cancelado');
						        }
						    }
						});
				    }
		        },
		        cancelar: function () {
		            //close
		        },
		    },
		    onContentReady: function () {
		        // bind to events
		        var jc = this;
		        this.$content.find('form').on('submit', function (e) {
		            // if the user submits the form by pressing enter in the field.
		            e.preventDefault();
		            jc.$$formSubmit.trigger('click'); // reference the button and click it
		        });
		    }
		});
    	
	} );


	$('#cliente').on( 'click', 'td.details-control', function () {

    	var cell = table.cell( this, 0 ).data();
	   	var cliente_selecionado = table.cell( this, 1 ).data();

		table1
	        .search(cell + ' - ' + cliente_selecionado)
	        .columns(0)
	        .draw();

	    document.getElementById('historico').style.display='block';
	} );

	$('#cliente').on( 'click', 'td.situacaoCliente', function () {

		var id_selecionado = table.cell( this, 0 ).data();
    	var cliente_selecionado = table.cell( this, 1 ).data();

    	var nomeMetodo 		= "alterarSituacao";
      	var nomeController 	= "Cadastro";

        var dados = '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&idCliente=' + id_selecionado;

    	var perfil_selecionado = table.cell( this, 2 ).data();
    	//alert(cell);
    	$.confirm({
		    title: 'Alterar situação </br></br>'+cliente_selecionado,
		    content: '' +
		    '<form id="form_comissao" name="form_comissao" >' +
		    '<div class="form-group">' +
		    '<label>Confirma alterar situação do cliente?</label>' +
		    '</div>' +
		    '</form>',
		    buttons: {
		        formSubmit: {
		            text: 'Alterar',
		            btnClass: 'btn-blue',
		            action: function () {
		               $.ajax({
							   type: "POST",
							   url: "transferencia/transferencia.php",
							   data: dados,
							   success: function(msg){
							   $.alert('Perfil alterado com sucesso!');
							   table.ajax.reload();
							}
						});
				    }
		        },
		        cancelar: function () {
		            //close
		        },
		    },
		    onContentReady: function () {
		        // bind to events
		        var jc = this;
		        this.$content.find('form').on('submit', function (e) {
		            // if the user submits the form by pressing enter in the field.
		            e.preventDefault();
		            jc.$$formSubmit.trigger('click'); // reference the button and click it
		        });
		    }
		});
	} );


	var table1 = $('#venda').DataTable( {
		dom: 'Bfrtip',
		ajax: 'controller/controller_historico.php',
		columns: [
			{ 
				"data": null, render: function ( data, type, row ) 
				{
                // Combine the first and last names into a single table field
                return data.cliente.id_cliente+' - '+data.cliente.nome;
            	} 
        	},
			{
				"data": "loja.descricao",
				"searchable": false
			},
			{
				"data": "cliente.numero_rg",
				"searchable": false
			},
			{
				"data": "vendedor.nome",
				"searchable": false
			},
			{
				"data": "venda.dta_venda",
				"searchable": false
			},
			{
				"data": "venda.valor_total_pago",
				"searchable": false
			},
			{
				"data": "venda.valor_total_liquido",
				"searchable": false
			}
		],
		select: true,
		"info":     false,
		buttons: [
		]
	} );	
} );

}(jQuery));

