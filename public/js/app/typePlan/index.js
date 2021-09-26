var modalTypePlan = null;
var formTypePlant = null;
var dataTable = null;

$(function () {
    dataTable = initDataTableAjax(
        $('#typePlan_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_typePlans').val(),
                data: function (filterDateTable) {
                    //additional params for ajax request
                    // filterDateTable.vendor_id = 3;
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
                        return '<button class="btn btn-dark btn-sm" onclick="editTypePlan(' +
                        row.id + ')">Editar</button>';
                    }
                }
            ]
        });

    modalTypePlan = $('#typePlan_modal'); // busca un elemento con id modal
});

function editTypePlan(id) {
    modalTypePlan.find('.modal-title').html('Editar familia de plan');
    getFormTypePlan($('#action_get_form').val() + '/' + id);
}

function newTypePlan() {
    modalTypePlan.find('.modal-title').html('Crear familia de plan');
    getFormTypePlan($('#action_get_form').val());
}

function saveTypePlan() {
    if (formTypePlan.valid()) {
        ajaxRequest(
            $('#action_save_typePlan').val(),
            {
                type: 'POST',
                data: formTypePlan.serialize(), // datos del formulario .serialize()
                blockElement: '#typePlan_modal .modal-content',//opcional: es para bloquear el elemento
                loading_message: 'Guardando...',
                error_message: 'Error al guardar la familia de plan',
                success_message: 'La familia de plan se guardó correctamente',
                success_callback: function (data) {
                    modalTypePlan.modal('hide'); // ocultar modal
                    dataTable.ajax.reload();
                }
            });
    }
}
function getFormTypePlan(url) {
    ajaxRequest(url, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalTypePlan.find('.container_modal').html('');
            modalTypePlan.find('.container_modal').html(data.html);
            formTypePlan = $("#typePlan_form");
            validateFormTypePlan();
            modalTypePlan.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateFormTypePlan() {
    formTypePlan.validate({
        rules: {
            name: {
                required: true,
                maxlength: 45,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#typePlan_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: function () {
                            return $("#name").val().trim();
                        },
                    }
                }
            }
        },
        messages: {
            name: {
                remote: 'Ya existe una familia de plan con ese nombre'
            }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveTypePlan();
        }
    });
}