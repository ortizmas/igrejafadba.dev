var urlAjaxHandlerCms = '/';

$(document).ready(function(){
	$('#alert').hide();

	$('.btn-delete').click(function(e){
		e.preventDefault();
		if (! confirm("Têm certeza para eliminar!")) {
			return false; //não se executa
		}

		var row = $(this).parents('tr');
		var form = $(this).parents('form');
		var url = form.attr('action');

		$('#alert').show();

		$.post(url, form.serialize(), function(result) {
			row.fadeOut();
			$('#tasks-total').html(result.total);
			$('#alert').html(result.message);
		}).fail(function(){
			$('#alert').html("Algo errado aconteceu.");
		});															
	});

    //Ajax para ativar e desativar o campo estado envia array da vista + itemArray[0] + '/' + itemArray[1] + '/' + itemArray[2] perfil/Perfil/3
	$('[data-list-boolean]').on('click', function () {

        var itemArray = $(this).data('list-boolean').split('_');
        var field = $(this).data('list-name');
        var onObj = $(this).find(".text-success");
        var offObj = $(this).find(".text-error");
        var value = ( onObj.hasClass('hidden') ) ? 1 : 0;
        $.ajax({
                //url: urlAjaxHandlerCms + 'painel/update/updateItemField' + itemArray[0] + '/' + itemArray[1],
                url: urlAjaxHandlerCms + 'painel/' + itemArray[0] + '/' + itemArray[1] + '/' + itemArray[2],
                data: {
                    model: itemArray[0],
                    field: field,
                    value: value
                },
                type: "GET",
                dataType: 'json',
                cache: false,
                success: function (response) {
                    //  suppress
                    onObj.toggleClass('hidden');
                    offObj.toggleClass('hidden');
                    $.notify(response.message, "success");
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $.notify("Something went Wrong please" + xhr.responseText + thrownError);

                }
            }
        );
    });

    $('[data-role="delete-item"]').on('click', function (e) {
        e.preventDefault();
        var curItem = this;
        bootbox.setLocale('es');
        bootbox.confirm("<h4>Têm certeza que quer excluir?</h4>", function (confirmed) {
            if (confirmed) {
                location.href = curItem.href;
            }
        });
    });


});