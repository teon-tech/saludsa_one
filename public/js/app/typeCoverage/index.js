var modalTypeCoverage = null;
var formTypeCoverage = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax(
        $('#typeCoverage_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_typeCoverages').val(),
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
                        return '<button class="btn btn-dark btn-sm" onclick="editTypeCoverage(' +
                        row.id + ')">Editar</button>';
                    }
                }
            ]
        });

        modalTypeCoverage = $('#typeCoverage_modal'); // busca un elemento con id modal
});

function editTypeCoverage(id) {
    modalTypeCoverage.find('.modal-title').html('Editar tipo de cobertura');
    getFormTypeCoverage($('#action_get_form').val() + '/' + id);
}

function newTypeCoverage() {
    modalTypeCoverage.find('.modal-title').html('Crear tipo de cobertura');
    getFormTypeCoverage($('#action_get_form').val());
}

function saveTypeCoverage() {
    if (formTypeCoverage.valid()) {
        ajaxRequest(
            $('#action_save_typeCoverage').val(),
            {
                type: 'POST',
                data: formTypeCoverage.serialize(), // datos del formulario .serialize()
                blockElement: '#typeCoverage_modal .modal-content',//opcional: es para bloquear el elemento
                loading_message: 'Guardando...',
                error_message: 'Error al guardar el tipo de cobertura',
                success_message: 'El tipo de cobertura ha sido guardado correctamente',
                success_callback: function (data) {
                    modalTypeCoverage.modal('hide'); // ocultar modal
                    dataTable.ajax.reload();
                }
            });
    }
}
function getFormTypeCoverage(url) {
    ajaxRequest(url, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalTypeCoverage.find('.container_modal').html('');
            modalTypeCoverage.find('.container_modal').html(data.html);
            formTypeCoverage = $("#typeCoverage_form");
            validateFormTypeCoverage();
            modalTypeCoverage.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateFormTypeCoverage() {
    formTypeCoverage.validate({
        rules: {
            name: {
                required: true,
                maxlength: 45,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#typeCoverage_id').val();
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
                remote: 'Ya existe un tipo de cobertura con ese nombre.'
            }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveTypeCoverage();
        }
    });
}