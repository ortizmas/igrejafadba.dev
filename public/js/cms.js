var urlAjaxHandlerCms = _SERVER_PATH + ''; // percorso  del contenuto del  dialog

var curItem;

var Cms = function () {

    function handleBootstrap() {
        /*Bootstrap Carousel*/
        /*
         jQuery('.carousel').carousel({
         interval: 15000,
         pause: 'hover'
         });
         */

        $('[data-toggle="tooltip"]').tooltip();

        /*Popovers*/
        jQuery('.popovers').popover();
        jQuery('.popovers-show').popover('show');
        jQuery('.popovers-hide').popover('hide');
        jQuery('.popovers-toggle').popover('toggle');
        jQuery('.popovers-destroy').popover('destroy');

        jQuery('[data-toggle="buttons-radio"]').button();
    }


    function handleToggle() {
        jQuery('.list-toggle').on('click', function () {
            jQuery(this).toggleClass('active');
        });
    }
    function handleFlashMessage() {
        jQuery('div.flash').not('.alert-important').delay(1500).slideUp();
    }

    function listHandler() {

        $(':input[data-list-value]').on('change', function () {
            var value = $(this).val();
            var itemArray = $(this).data('list-value').split('_');
            var field = $(this).data('list-name');
            if ($(this).is(':checkbox')) {
                value = ( $(this).is(":checked") ) ? 1 : 0;
            }
            $.ajax({
                    url: urlAjaxHandlerCms + 'update/updateItemField/' + itemArray[0] + '/' + itemArray[1],
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
                        $.notify(response.message, "success");
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $.notify("Something went Wrong please" + xhr.responseText + thrownError);

                    }
                }
            );
        });


        $('[data-list-boolean]').on('click', function () {

            var itemArray = $(this).data('list-boolean').split('_');
            var field = $(this).data('list-name');
            var onObj = $(this).find(".text-success");
            var offObj = $(this).find(".text-error");
            var value = ( onObj.hasClass('hidden') ) ? 1 : 0;
            $.ajax({
                    url: urlAjaxHandlerCms + 'update/updateItemField/' + itemArray[0] + '/' + itemArray[1],
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
            bootbox.setLocale(_LOCALE);
            bootbox.confirm("<h4>Are you sure?</h4>", function (confirmed) {
                if (confirmed) {
                    location.href = curItem.href;
                }
            });
        });


        //  gestione  check  box  liste
        $('input.checkbox').click(function () {
            var itemSel = $("input.checkbox:checked").length;
            if (itemSel > 0) {
                $('#toolbar_cerca').hide();

                if (itemSel == 1) {
                    curItem = $("input.checkbox:checked").val();
                    $('#toolbar_editButtonHandler').show();
                }
                else $('#toolbar_editButtonHandler').hide();
                $('#list-action-bar').fadeIn('1000');
            } else {
                $('#list-action-bar').hide();
                $('#toolbar_cerca').fadeIn('1000');
            }
        });

        $('#toolbar_editButtonHandler').on('click', function (e) {
            e.preventDefault();
            //  redirect to edit page
            location.href = $('#row_' + curItem + ' [data-role="edit-item"] ')[0].href;
        });

        $('#toolbar_deleteButtonHandler').on('click', function (e) {
            e.preventDefault();
            //  redirect to edit page
            var role = $(this).data('role');
            bootbox.setLocale(_LOCALE);
            bootbox.confirm("<h4>Are you sure?</h4>", function (confirmed) {
                if (confirmed) {
                    $('input.checkbox:checked').each(function (index) {
                        $('#row_' + $(this).val()).fadeOut('1000');
                        deleteUrl = $('#row_' + $(this).val() + ' [data-role="delete-item"] ')[0].href;
                        // Do delete
                        curModel = _CURMODEL;
                        $.ajax({
                                url: urlAjaxHandlerCms + 'delete/' + curModel + '/' + $(this).val(),
                                type: "GET",
                                dataType: 'json',
                                cache: false,
                                success: function (response) {
                                    //  suppress
                                    //$.notify(response.message, "success");
                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                    $.notify("Something went Wrong please" + xhr.responseText + thrownError);

                                }
                            }
                        );
                    });
                    $.notify("Selected items have been deleted");
                }
            });
        });
    }

	$("#sidebar").mCustomScrollbar({
		theme: "minimal",
		scrollInertia: 200
	});

	var sidebar_compressed = localStorage.sidebar_compressed == 'true';

	if (sidebar_compressed) {
		$('#sidebar').addClass('compressed');
		$('body').addClass('expanded');
	}

	$('#sidebar-button').click(function() {
		$('#sidebar').toggleClass('compressed');
		$('body').toggleClass('expanded');
		sidebar_compressed = !sidebar_compressed;
		localStorage.setItem('sidebar_compressed', sidebar_compressed);
	});


    return {
        init: function () {
            handleBootstrap();
            //handleIEFixes();
            listHandler();
            handleFlashMessage();
            handleToggle();
        },

        initDatePicker: function() {
            $( ".datepicker" ).datepicker({
                dateFormat: "dd-mm-yy"
            });
        },

        initUploadifiveSingle: function () {
            $('.file_upload_single').each(function() {
				var elem = $(this);
				elem.uploadifive({
					'auto'             : true,
					//'checkScript'      : 'check-exists.php',
					'queueID'          : 'queue_' + elem.data('key'),
					'uploadScript'     : urlAjaxHandlerCms+'uploadifiveSingle',
					'onAddQueueItem' : function(file) {
						this.data('uploadifive').settings.formData = {
							'timestamp' : '1451682058',
							'token'     : '4b9fe8f26d865150e4b26b2a839d4f2b',
							'Id'        :  $('#itemId').val(),
							'myImgType' :  $('#myImgType').val(),
							'model'     :  _CURMODEL,
							'key'		: elem.data('key'),
							"_token" :  $('[name=_token]').val()
						};
					},
					'onUploadComplete' : function(file, data) {
						var responseObj = jQuery.parseJSON(data);
						var mediaType   =  responseObj.data;
						var filename = responseObj.filename;
						$('[name="' + elem.data('key') + '"]').val(filename);
					}
				});
			});
		},
		initUploadifiveMedia:function () {
			var elem = $('#file_upload_media');
			elem.uploadifive({
                'auto'             : true,
                //'checkScript'      : 'check-exists.php',
                'queueID'          : 'queue_media',
                'uploadScript'     : urlAjaxHandlerCms+'uploadifiveMedia',
                'onAddQueueItem' : function(file) {
                    this.data('uploadifive').settings.formData = {
                        'timestamp' : '1451682058',
                        'token'     : '4b9fe8f26d865150e4b26b2a839d4f2b',
                        'Id'        :  $('#itemId').val(),
                        'myImgType' :  $('#myImgType').val(),
                        'model'     :  _CURMODEL,
						'key'		: elem.data('key'),
                        "_token" :  $('[name=_token]').val()
                    };
                },
                'onUploadComplete' : function(file, data) {
                    var responseObj = jQuery.parseJSON(data);
                    var mediaType   =  responseObj.data;
                    $("#" + mediaType + "ListBody").load( urlAjaxHandlerCms + 'updateHtml/' + mediaType + '/' + _CURMODEL + '/'+ $('#itemId').val());
                 }
            });
        },


        initTinymce: function () {

            tinymce.init({
                selector: "textarea.wyswyg",
                plugins: [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
            });
        },

		initColorPicker: function () {
			$('.color-picker').colorpicker({
				'format': 'hex'
			});
		},

        initSortableList: function (object) {

            $(object).sortable({
                revert: true,
                update: function (ev, ui) {
                    var order = $(object).sortable('serialize');
                 $("#info").load(urlAjaxHandlerCms + "updateMediaSortList?" + order);
                }
            });
            $("ul#simpleGallery").disableSelection()

        },
        initImageRelationList: function() {
            $('[data-image-relation]').on('click', function () {
                var targetField   = $(this).data('image-relation');
                var targetFieldValue = $(this).data('image-id')
                $("#"+targetField).val(targetFieldValue);
                $('[data-image-relation="'+targetField +'"]').each(function (index) {
                    $(this).removeClass('active');
                    $(this).addClass('inactive');
                });
                $(this).addClass('active');
            });
        },
        initDateTimePicker:function () {
            $('.datetimepicker').datetimepicker({
                controlType: 'select',
                oneLine: true,
                dateFormat: 'dd-mm-yy',
                timeFormat: 'HH:mm:ss',
                hourMin: 6,
                hourMax: 22
            });
        }
    }
}();


function deleteImages(obj) {

    bootbox.setLocale(_LOCALE);
    bootbox.confirm("<h4>Are you sure?</h4>", function (confirmed) {
        var curItem = obj;
        var value = "";
        var itemArray = curItem.id.split('-');
        var field = itemArray[1];
        var boxObj = $("#box_" + itemArray[1] + "_" + itemArray[2]);


        if (confirmed) {
            $.ajax({
                    url: urlAjaxHandlerCms + 'update/updateItemField/' + _CURMODEL + '/' + itemArray[2],
                    data: {
                        model: _CURMODEL,
                        field: field,
                        value: value
                    },
                    type: "GET",
                    dataType: 'json',
                    cache: false,
                    success: function (response) {
                        //  suppress
                        $.notify(response.message, "success");
                        // hide  the   media  preview  container
                        boxObj.hide();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $.notify("Something went Wrong please" + xhr.responseText + thrownError);

                    }
                }
            );
        }
    });

}


function deleteItem(obj) {

    bootbox.setLocale(_LOCALE);
    bootbox.confirm("<h4>Are you sure?</h4>", function (confirmed) {
        var curItem = obj;
        var value = "";
        var itemArray = curItem.id.split('_');
        var boxObj = $("#box_" + itemArray[1] + "_" + itemArray[2]);


        if (confirmed) {
            $.ajax({
                    url: urlAjaxHandlerCms + 'delete/' + itemArray[1] + '/' + itemArray[2],

                    type: "GET",
                    dataType: 'json',
                    cache: false,
                    success: function (response) {
                        //  suppress
                        $.notify(response.message, "success");
                        // hide  the   media  preview  container
                        boxObj.hide();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $.notify("Something went Wrong please" + xhr.responseText + thrownError);

                    }
                }
            );
        }
    });

}
