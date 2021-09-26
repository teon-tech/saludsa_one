var modalPlanPrice = null;
var formPlanPrice = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax($('#planPrice_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_planPrices').val(),
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
                    data: "plan.name",
                    title: 'Plan'
                },
                {
                    data: "gender",
                    title: 'Género',
                    render: function (data, type, row, meta) {
                        if (row.gender === 'FEMALE') {
                            return 'Femenino';
                        } else {
                            return 'Masculino';
                        }
                    }
                },
                {
                    data: null,
                    title: 'Edad',
                    render: function (data, type, row, meta) {
                       return row.range_age_from + '-' + row.range_age_to;
                    }
                },
                {
                    data: "monthly_price",
                    title: 'Precio mensual'
                },
                {
                    data: "annual_price",
                    title: 'Precio anual'
                },
                {
                    data: null,
                    title: 'Acciones',
                    orderable: false,
                    width: "140px",
                    render: function (data, type, row, meta) {
                        return '<button class="btn btn-dark btn-sm" onclick="editPlanPrice(' + row.id + ')">Editar</button>';
                    }
                    
                }
            ]
        });
        
    modalPlanPrice = $('#planPrice_modal');
});

function editPlanPrice(id) {
    modalPlanPrice.find('.modal-title').html('Editar precio del plan');
    getformPlanPrice($('#action_get_form').val() + '/' + id);
}

function newPlanPrice() {
    modalPlanPrice.find('.modal-title').html('Crear precio de plan');
    getformPlanPrice($('#action_get_form').val());
}

function savePlanPrice() {
    if (formPlanPrice.valid()) {
        ajaxRequest($('#action_save_planPrice').val(), {
            type: 'POST',
            data: formPlanPrice.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar el precio del plan',
            success_message: 'El precio del plan se guardo correctamente',
            success_callback: function (data) {
                modalPlanPrice.modal('hide');
                dataTable.ajax.reload();
            }
        });
    }
}
function getformPlanPrice(url) {
    
    ajaxRequest(url, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalPlanPrice.find('.container_modal').html('');
            modalPlanPrice.find('.container_modal').html(data.html);
            formPlanPrice = $("#planPrice_form");
            validateformPlanPrice();
            $('#hospital_id').select2({
                dropdownParent: $('#planPrice_form'),
                width: '100%',
                placeholder: '-Seleccione-',
            });
            $('#plan_id').select2({
                dropdownParent: $('#planPrice_form'),
                width: '100%',
                placeholder: '-Seleccione-',
            });
            modalPlanPrice.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateformPlanPrice() {
    formPlanPrice.validate({
        rules: {
            gender: {
                required: true,
                maxlength: 100,
            }
        },
        messages: {
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            savePlanPrice();
        }
    });
}

$(function () {
    $(document).on('change', '#hospital_id', function() {
        var hospital_id = $(this).val();
        $.ajax({
            type: 'get',
            url: $('#action_plans_hospital').val(),
            data: {
                'hospital_id': hospital_id
            },
            dataType: 'json', //return data will be json.
        })
            .done(function(data) {
                $("#plan_id").empty();
                for (var i = 0; i < data.length; i++) {
                    var newOption = new Option(data[i].name, data[i].id, true, false);
                    $('#plan_id').append(newOption).trigger('change');
                    }
        });
    });

});