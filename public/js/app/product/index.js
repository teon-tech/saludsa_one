var modal_model = null;
var form_model = null;
var dataTable = null;
var imagesDeleted = [];
var unitsData = [];

$(function () {
    dataTable = initDataTableAjax($('#model_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_models').val(),
                data: function (filterDateTable) {
                    //additional params for ajax request
                }
            },
            "responsive": true,
            "language": {
                "paginate": {
                    "previous": '<i class="demo-psi-arrow-left"></i>',
                    "next": '<i class="demo-psi-arrow-right"></i>'
                }
            },
            columns: [
                {
                    data: "name",
                    title: 'Nombre'
                },
                {
                    data: "status",
                    title: 'Estado',
                    render: function (data, type, row, meta) {
                        if (row.status === 'ACTIVE') {
                            return '<span class="label label-primary label-inline font-weight-lighter">Activo</span>';
                        } else {
                            return '<span class="label label-danger label-pill label-inline">Inactivo</span>';
                        }
                    }
                },
                {
                    data: null,
                    title: 'Acciones',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return ' <button class="btn btn-dark btn-sm" onclick="editModel(' + row.id + ')">Editar</button>';
                    }
                }
            ]
        });
    modal_model = $('#modal');
});

function editModel(id) {
    modal_model.find('.modal-title').html('Editar Producto');
    getFormModel($('#action_get_form').val() + '/' + id);
}

function newModel() {
    modal_model.find('.modal-title').html('Crear Producto');
    getFormModel($('#action_get_form').val());
}

function saveModel() {
    if (form_model.valid()) {

        ajaxRequest($('#action_save_model').val(), {
            type: 'POST',
            data: form_model.serializeArray(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar el producto',
            success_message: 'El producto se guardo correctamente',
            success_callback: function (data) {
                modal_model.modal('hide');
                dataTable.ajax.reload();
            }
        });
    }
}

function getFormModel(action) {
    ajaxRequest(action, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            unitsData = data.unitJson;
            modal_model.find('.container_modal').html('');
            modal_model.find('.container_modal').html(data.html);
            form_model = $("#model_form");
            validateFormModel();
            imagesDeleted = [];
            modal_model.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}


function validateFormModel() {
    form_model.validate({
        rules: {

            name: {
                required: true,
                maxlength: 45,
                remote: {
                    url: $('#action_unique_code').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#model_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: function () {
                            return $("#name").val().trim();
                        },
                    }
                }
            },
        },
        messages: {
            name: {
                remote: 'Ya existe un producto con ese nombre.'
            }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveModel();
        }
    });
}

