var modalRegion = null;
var formRegion = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax($('#region_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_regions').val(),
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
                    data: "external_code",
                    title: 'Código externo'
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
                        return '<button class="btn btn-dark btn-sm" onclick="editRegion(' + row.id + ')">Editar</button>';
                    }
                }
            ]
        });
    modalRegion = $('#modal');
});

function editRegion(id) {
    modalRegion.find('.modal-title').html('Editar región');
    getFormRegion($('#action_get_form').val() + '/' + id);
}

function newRegion() {
    modalRegion.find('.modal-title').html('Crear región');
    getFormRegion($('#action_get_form').val());
}

function saveRegion() {
    if (formRegion.valid()) {
        ajaxRequest($('#action_save_region').val(), {
            type: 'POST',
            data: formRegion.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar la región',
            success_message: 'La región se guardo correctamente',
            success_callback: function (data) {
                modalRegion.modal('hide');
                dataTable.ajax.reload();
            }
        });
    }
}

function getFormRegion(action) {
    ajaxRequest(action, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalRegion.find('.container_modal').html('');
            modalRegion.find('.container_modal').html(data.html);
            formRegion = $("#region_form");
            validateFormRegion();
            modalRegion.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateFormRegion() {
    formRegion.validate({
        rules: {
            name: {
                required: true,
                maxlength: 100,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#region_id').val();
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
                remote: 'Ya existe una región con ese nombre.'
            }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveRegion();
        }
    });
}