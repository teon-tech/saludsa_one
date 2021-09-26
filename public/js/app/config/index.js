var modal_model = null;
var form_model = null;
var dataTable = null;

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
                    title: 'Nombre',
                    render: function(data, type, row, meta){
                        if(row.name === 'initial_contract_number'){
                            return 'Número de contrato Inicial';
                        }
                        if(row.name === 'limit_contract_number'){
                            return 'Número de contrato final';
                        }
                    }
                },
                {
                    data: "value",
                    title: 'Valor',
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
    modal_model.find('.modal-title').html('Actualizar valor de contrato');
    getFormModel($('#action_get_form').val() + '/' + id);
}

function saveModel() {
    if (form_model.valid()) {
console.log('guardando');
        ajaxRequest($('#action_save_model').val(), {
            type: 'POST',
            data: form_model.serializeArray(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar el valor del contrato',
            success_message: 'El valor del contrato se guardo correctamente',
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
            modal_model.find('.container_modal').html('');
            modal_model.find('.container_modal').html(data.html);
            form_model = $("#model_form");
            validateFormModel();
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
        },
        messages: {
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


