var modalPlan = null;
var formPlan = null;
var dataTable = null;
var imagesDeleted = [];
var docsDeleted = [];
$(function () {
    dataTable = initDataTableAjax($('#plan_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_plans').val(),
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
                    data: "type_plan.name",
                    title: 'Familia'
                },
                {
                    data: "product.name",
                    title: 'Producto'
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
                    title: 'Ver lista',
                    orderable: false,
                    width: "270px",
                    render: function (data, type, row, meta) {
                        return '<a href="'+ $('#action_index_coverage').val() +'/'+ row.id+'" target=""><span class="btn btn-dark btn-sm">Coberturas</span></a> <a href="'+ $('#action_index_section').val() +'/'+ row.id+'" target=""><span class="btn btn-dark btn-sm">Secciones</span></a> <a href="'+ $('#action_index_question').val() +'/'+ row.id+'" target=""><span class="btn btn-dark btn-sm">Preguntas</span></a>';
                    }
                    
                },
                {
                    data: null,
                    title: 'Acciones',
                    orderable: false,
                    width: "140px",
                    render: function (data, type, row, meta) {
                        return '<button class="btn btn-dark btn-sm" onclick="editPlan(' + row.id + ')">Editar</button> <button class="btn btn-danger btn-sm" onclick="deletedPlan(' + row.id + ')">Eliminar</button>';
                    }
                    
                }
            ]
        });
        
    modalPlan = $('#plan_modal');
    modalCoverage = $('#coverage_modal');
});

function editPlan(id) {
    modalPlan.find('.modal-title').html('Editar plan');
    getformPlan($('#action_get_form').val() + '/' + id);
}

function newPlan() {
    modalPlan.find('.modal-title').html('Crear plan');
    getformPlan($('#action_get_form').val());
}

function savePlan() {
    if ($('#plan_form').valid()) {
        var data = $('#plan_form').serializeArray();
        var files = getFiles();
        var filesDocs = getFilesDocs()
        var dataForm = new FormData();
        
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
        $.each(docsDeleted, function (index, idFile) {
            dataForm.append('docsDeleted[' + index + ']', idFile);
        });
        $.each(filesDocs, function (index, file) {
            dataForm.append('filesDocs[' + index + ']', file);
        });
        ajaxRequest(
            $('#action_save_plan').val(),
            {
                type: 'POST',
                data: dataForm,
                blockElement: '#plan_modal .modal-content',//opcional: es para bloquear el elemento
                loading_message: 'Guardando...',
                error_message: 'Error al guardar el plan',
                success_message: 'Se guardó correctamente',
                success_callback: function (data) {
                    $('#plan_modal').modal('hide');
                    dataTable.ajax.reload();
                }
            }, true);
    }
}
function getformPlan(url) {
    
    ajaxRequest(url, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalPlan.find('.container_modal').html('');
            modalPlan.find('.container_modal').html(data.html);
            formPlan = $("#plan_form");
            validateformPlan();
            imagesDeleted = [];
            initDropZones();
            initDropZoneFIle();
            $('#type_plan_id').select2({
                dropdownParent: $('#plan_form'),
                width: '100%',
                placeholder: '-Seleccione-',
            });
            $('#keywords').select2({
                dropdownParent: $('#plan_form'),
                width: '100%',
                placeholder: '-Seleccione-',
                tags: true
            });
            $('#product_id').select2({
                dropdownParent: $('#plan_form'),
                width: '100%',
                placeholder: '-Seleccione-',
            });
            modalPlan.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateformPlan() {
    formPlan.validate({
        rules: {
            name: {
                required: true,
                maxlength: 45,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#plan_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: function () {
                            return $("#name").val().trim();
                        },
                       
                    }
                }
                
            },
            code: {
                required: true,
                maxlength: 64,
                remote: {
                    url: $('#action_unique_code').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#plan_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        code: function () {
                            return $("#code").val().trim();
                        },

                    }
                }

            }
        },
        messages: {
            name: {
                remote: 'Ya existe un plan con ese nombre.'
            }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            savePlan();
        }
    });
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

function initDropZoneFIle() {
    Dropzone.autoDiscover = false;
    $("div#wrapper_file").dropzone({
        url: $('#action_get_form').val(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        uploadMultiple: true,
        autoProcessQueue: true,
        acceptedFiles: '.pdf, .docx, .xlsx, .csv',
        previewTemplate: document
            .querySelector('#preview_file')
            .innerHTML,
        init: function () {
            var myDropzone = this;
            myDropzone.accept = function (file, done) {
                if (!Dropzone.isValidFile(file, this.options.acceptedFiles)) {
                    showAlert('warning', 'Solo se permiten archivos en formato: *' + this.options.acceptedFiles);
                    return this.removeFile(file);
                }
            }
        }
    });
}

function getFilesDocs() {
    var filesDocs = [];
    $.each($('div#wrapper_file'), function (i) {
        var myDropZone = Dropzone.forElement(this);
        filesDocs = filesDocs.concat(myDropZone.files);
    });
    return filesDocs;
}
function deleteFile(id) {
    docsDeleted.push(id);
    $('#' + id + '_wrapper_file').remove();
}
function deletedPlan(id) {
    
    Swal.fire({
        title: "Estás seguro?",
        text: "Confirmación para eliminar plan",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "No",
        
    }).then(function(result) {
        if (result.value) {   
            ajaxRequest($('#action_deleted_plan').val() + '/' + id, {
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