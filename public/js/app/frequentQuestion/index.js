var modalQuestion = null;
var formQuestion = null;
var dataTable = null;

$(function () {
  
    dataTable = initDataTableAjax($('#question_table'),
    
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_questions').val(),
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
                    data: "title",
                    title: 'Título'
                },  
                {
                    data: "description",
                    title: 'Descripción'
                }, 
                {
                    data: null,
                    title: 'Acciones',
                    width: "140px",
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return '<button class="btn btn-dark btn-sm" onclick="editQuestion(' + row.id + ')">Editar</button> <button class="btn btn-danger btn-sm" onclick="deletedQuestion(' + row.id + ')">Eliminar</button> ';
                    }
                    
                }
            ]
        });   
    modalQuestion = $('#question_modal');
});

function deletedQuestion(id) {
    
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Confirmación para eliminar pregunta",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Si",
            cancelButtonText: "No",
            
        }).then(function(result) {
            if (result.value) {   
                ajaxRequest($('#action_deleted_question').val() + '/' + id, {
                    type: 'DELETE',
                    success_callback: function(data) {
                      dataTable.ajax.reload();
                    },
                  });
                  Swal.fire({
                    icon: "success",
                    title: "Eliminado correctamente",
                    showConfirmButton: false,
                    timer: 1500
                })

            }
        });
    
}

function editQuestion(id) {
    modalQuestion.find('.modal-title').html('Editar pregunta');
    getformQuestion($('#action_get_form_question').val() + '/' + id);
}

function newQuestion() {
    modalQuestion.find('.modal-title').html('Crear pregunta');
    getformQuestion($('#action_get_form_question').val());
}

function getformQuestion(url) {
    
    ajaxRequest(url, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalQuestion.find('.container_modal').html('');
            modalQuestion.find('.container_modal').html(data.html);
            formQuestion = $("#question_form");
            validateformQuestion(); 
            modalQuestion.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateformQuestion() {
    formQuestion.validate({
        rules: {
            title: {
                required: true,
                maxlength: 128,
        }, 
    },
        messages: {
            // type_coverage_id: {
            //     remote: 'Ya existe una cobertura de ese tipo.'
            // }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveQuestion();
        }
    });
    
}
function saveQuestion() {
    if ($('#question_form').valid()) {
        var data = $('#question_form').serializeArray();
        
        var editor=CKEDITOR.instances['description'].getData();
        for (var i = 0; i < data.length; i++) {
            if(data[i].name ==='description'){
                data[i].value = editor
            }
        }

        ajaxRequest(
            $('#action_save_question').val(),
            {
                type: 'POST',
                data: data,
                blockElement: '#question_modal .modal-content',//opcional: es para bloquear el elemento
                loading_message: 'Guardando...',
                error_message: 'Error al guardar la pregunta',
                success_message: 'Se guardó correctamente',
                success_callback: function (data) {
                    $('#question_modal').modal('hide');
                    dataTable.ajax.reload();
                }
            });
    }
}
