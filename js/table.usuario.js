
/*
 * Editor client script for DB table usuario
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		ajax: 'controller/controller_usuario_acessos.php',
		table: '#usuario',
		fields: [
			{
				"label": "Login:",
				"name": "usuario.login"
			},
			{
                label: "Loja:",
                name: "usuario.id_loja",
                type: "select",
                "placeholder": 'Selecione a loja',
			},
			{
				"label": "Perfil:",
				"name": "usuario.perfil",
				"type": "select",
				"placeholder": 'Selecione o perfil',
				"options": [
					{ label: "Administrador", value: "A" },
					{ label: "Consulta", value: "C" },
					{ label: "Funcionário", value: "F" },					
					{ label: "Sub-gerente", value: "S" }
				]
			},
			{
	            label:     "Inativo:",
	            name:      "usuario.situacao",
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
                title:  "Cadastrar Usuário",
                submit: "Cadastrar"
            },
            edit: {
                button: "Editar",
                title:  "Editar Usuário",
                submit: "Atualizar"
            },
            remove: {
                button: "Excluir",
                title:  "Excluir Usuário",
                submit: "Excluir",
                confirm: {
                    _: "Deseja excluir os %d usuários selecionados?",
                    1: "Deseja excluir o usuário selecionado?"
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

	$('#usuario').on( 'click', 'td.details-control', function () {
		var id_selecionado = table.cell( this, 0 ).data();
		var usuario_selecionado = table.cell( this, 2 ).data();
		
		var nomeMetodo     = "alterarSenha";
        var nomeController = "Cadastro";

    	//alert(cell);
    	$.confirm({
		    title: 'Nova senha',
		    content: '' +
			'<form class="formName">' +
			'<div class="form-group">' +
			'<label>Nova senha para '+usuario_selecionado+'</label>' +
			'<input type="password" placeholder="Digite nova senha" class="novaSenha form-control" required />' +
			'</div>' +
			'</form>',
		    buttons: {
		        formSubmit: {
		            text: 'Alterar',
		            btnClass: 'btn-blue',
		            action: function () {
					   var novaSenha = this.$content.find('.novaSenha').val();
					   if(!novaSenha){
							$.alert('Insira uma senha');
							return false;
						}
						novaSenha = CryptoJS.MD5(novaSenha);

					   $.confirm({
						    title: 'Confirmar',
						    content: 'Confirma alterar a senha do usuário?',
						    buttons: {
						        confirmar: function () {
						        	$.ajax({
										   type: "POST",
										   url: "transferencia/transferencia.php",
										   data: '&nomeMetodo=' + nomeMetodo + '&nomeController=' + nomeController + '&id_selecionado=' + id_selecionado + '&novaSenha=' + novaSenha,
										   success: function( retorno )
										   {
											   $.alert(retorno.replace("'",""));
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

	var table = $('#usuario').DataTable( {
		dom: 'Bfrtip',
		ajax: 'controller/controller_usuario_acessos.php',
		columns: [
			{
				"data": "usuario.id_usuario"
			},
			{
                "data": "loja.descricao"
            },
			{
				"data": "usuario.login"
			},
			{
				"data": "usuario.perfil",
				render: function (data, type, row) {
                    // Filtering and display get the rendered string
                    if(data == 'A')   
                        return "Administrador";
                    if(data == 'S')   
                        return "Sub-gerente";
                    if(data == 'F')   
                        return "Funcionário";
                    if(data == 'C')   
                        return "Consulta";
                    return '';
                    // Otherwise just give the original data
                    
                }
			},
			{
                "data": "usuario.situacao",
                render: function (data, type, row) {
                         // Filtering and display get the rendered string
                        return data == 0 ? "Ativo" : "Desativado";
                    // Otherwise just give the original data
                    
                },
                orderable:      false,
                className:      'situacaoUsuario'
            },
			{
				className:      'details-control',
                orderable:      false,
                data:           null,
                defaultContent: '<center><i class="fa fa-key" aria-hidden="true"></i></center>'
			}
		],
		select: true,
		lengthChange: false,
		buttons: [
			{ extend: 'create', editor: editor },
			{ extend: 'edit',   editor: editor },
			{ extend: 'remove', editor: editor }
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
			"sSearch": "Filtrar usuário: ",
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

	$('ul').on('click', 'a', function() {
		table
		  .columns(4)
		  .search($(this).text())
		  .draw();
	  });
	   
	   $('ul').on('click', 'a.todos', function() {
		table
		  .search('')
		  .columns(4)
		  .search('')
		  .draw();
	  });

} );

}(jQuery));

