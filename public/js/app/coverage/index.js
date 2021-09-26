var modalCoverage = null;
var modalCoverage = null;
var dataTable = null;
var imagesDeleted = [];
$(function () {
  
    dataTable = initDataTableAjax($('#coverage_table'),
    
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_coverages').val(),
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
                    title: 'Cobertura'
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
                        return '<button class="btn btn-dark btn-sm" onclick="editCoverage(' + row.id + ')">Editar</button> <button class="btn btn-danger btn-sm" onclick="deletedCoverage(' + row.id + ')">Eliminar</button> ';
                    }
                    
                }
            ]
        });   
    modalCoverage = $('#coverage_modal');
});

function deletedCoverage(id) {
    
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Confirmación para eliminar cobertura",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Si",
            cancelButtonText: "No",
            
        }).then(function(result) {
            if (result.value) {   
                ajaxRequest($('#action_deleted_coverage').val() + '/' + id, {
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

function editCoverage(id) {
    modalCoverage.find('.modal-title').html('Editar cobertura');
    getformCoverage($('#action_get_form_coverage').val() + '/' + id);
}

function newCoverage() {
    modalCoverage.find('.modal-title').html('Crear cobertura');
    getformCoverage($('#action_get_form_coverage').val());
}

function getformCoverage(url) {
    
    ajaxRequest(url, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalCoverage.find('.container_modal').html('');
            modalCoverage.find('.container_modal').html(data.html);
            formCoverage = $("#coverage_form");
            validateformCoverage();
            imagesDeleted = [];
            initDropZones();
             $('#type_coverage_id').select2({
                dropdownParent: $('#coverage_form'),
                width: '100%',
                placeholder: '-Seleccione-',
            }); 
           
            modalCoverage.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateformCoverage() {
    formCoverage.validate({
        rules: {
            name: {
                required: true,
                maxlength: 64,
        },
        type_coverage_id: {
            required: true,
            // remote: {
            //     url: $('#action_unique_coverage_type').val(),
            //     type: 'POST',
            //     data: {
            //         idPlan: function () {
            //             return $('#plan_id').val();
            //         },
            //         _token: $('meta[name="csrf-token"]').attr('content'),
            //         idTypeCoverage: function () {
            //             return $("#type_coverage_id").val().trim();
            //         },
            //
            //     }
            // }
        }
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
            saveCoverage();
        }
    });
    
}
function saveCoverage() {
    if ($('#coverage_form').valid()) {
        var data = $('#coverage_form').serializeArray();
        var files = getFiles();
        var dataForm = new FormData();
        var editor=CKEDITOR.instances['description'].getData();
        for (var i = 0; i < data.length; i++) {
            if(data[i].name ==='description'){
                data[i].value = editor
            }
        }

        for (var i = 0; i < data.length; i++) {
            dataForm.append(data[i].name, data[i].value);
        }
        $.each(imagesDeleted, function (index, idImage) {
            dataForm.append('filesDeleted[' + index + ']', idImage);
        });
        $.each(files, function (index, file) {
            dataForm.append('files[' + index + ']', file);
            dataForm.append('filesParams[' + index + ']',
                JSON.stringify(file.params));
        });
        ajaxRequest(
            $('#action_save_coverage').val(),
            {
                type: 'POST',
                data: dataForm,
                blockElement: '#coverage_modal .modal-content',//opcional: es para bloquear el elemento
                loading_message: 'Guardando...',
                error_message: 'Error al guardar la cobertura',
                success_message: 'Se guardó correctamente',
                success_callback: function (data) {
                    $('#coverage_modal').modal('hide');
                    dataTable.ajax.reload();
                }
            }, true);
    }
}
function initDropZones() {
    Dropzone.autoDiscover = false;
    $('.wrapper_image').each(function (i) {
        var config = $(this).data();
        initDropZone($(this), config);
    });
}

function getFiles() {
    var files = [];
    $.each($('.wrapper_image'), function (i) {
        var myDropZone = Dropzone.forElement(this);
        files = files.concat(myDropZone.files);
    });
    return files;
}

function deleteImage(id) {
    imagesDeleted.push(id);
    $('#' + id + '_wrapper_image').remove();
}
