var modalHospital = null;
var formHospital = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax($('#hospital_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_hospitals').val(),
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
                    data: "region.name",
                    title: 'Región'
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
                    width: "140px",
                    render: function (data, type, row, meta) {
                        return '<button class="btn btn-dark btn-sm" onclick="editHospital(' + row.id + ')">Editar</button>';
                    }
                    
                }
            ]
        });
        
    modalHospital = $('#hospital_modal');
});

function editHospital(id) {
    modalHospital.find('.modal-title').html('Editar Hospital');
    getformHospital($('#action_get_form').val() + '/' + id);
}

function newHospital() {
    modalHospital.find('.modal-title').html('Crear Hospital');
    getformHospital($('#action_get_form').val());
}

function saveHospital() {
    if (formHospital.valid()) {
        ajaxRequest($('#action_save_hospital').val(), {
            type: 'POST',
            data: formHospital.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar el hospital',
            success_message: 'La región se guardo correctamente',
            success_callback: function (data) {
                modalHospital.modal('hide');
                dataTable.ajax.reload();
            }
        });
    }
}
function getformHospital(url) {
    
    ajaxRequest(url, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalHospital.find('.container_modal').html('');
            modalHospital.find('.container_modal').html(data.html);
            formHospital = $("#hospital_form");
            validateformHospital();
            $('#region_id').select2({
                dropdownParent: $('#hospital_form'),
                width: '100%',
                placeholder: '-Seleccione-',
            });
            $('#plan_id').select2({
                dropdownParent: $('#hospital_form'),
                width: '100%',
                placeholder: '-Seleccione-',
            });
            $('#keywords').select2({
                dropdownParent: $('#hospital_form'),
                width: '100%',
                placeholder: '-Seleccione-',
                tags: true
            });
            modalHospital.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateformHospital() {
    formHospital.validate({
        rules: {
            name: {
                required: true,
                maxlength: 100,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#hospital_id').val();
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
                remote: 'Ya existe un hospital con ese nombre.'
            }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveHospital();
        }
    });
}
