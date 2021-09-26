var modalSection = null;
var formSection = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax($('#section_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_sections').val(),
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
                    width: "140px",
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return '<button class="btn btn-dark btn-sm" onclick="editSection(' + row.id + ')">Editar</button> <button class="btn btn-danger btn-sm" onclick="deletedSection(' + row.id + ')">Eliminar</button>';
                    }

                }
            ]
        });

    modalSection = $('#section_modal');
});

function editSection(id) {
    modalSection.find('.modal-title').html('Editar sección');
    getformSection($('#action_get_form').val() + '/' + id);
}

function newSection() {
    modalSection.find('.modal-title').html('Crear sección');
    getformSection($('#action_get_form').val());
}

function deletedSection(id) {

    Swal.fire({
        title: "¿Estás seguro?",
        text: "Confirmación para eliminar sección",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "No",

    }).then(function (result) {
        if (result.value) {
            ajaxRequest($('#action_deleted_section').val() + '/' + id, {
                type: 'DELETE',
                success_callback: function (data) {
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

function saveSection() {
    if ($('#section_form').valid()) {
        var data = $('#section_form').serializeArray();
        var editor = CKEDITOR.instances['description'].getData();
        for (var i = 0; i < data.length; i++) {
            if (data[i].name === 'description') {
                data[i].value = editor
            }
        }
        ajaxRequest(
            $('#action_save_section').val(),
            {
                type: 'POST',
                data: data,
                blockElement: '#section_modal .modal-content',//opcional: es para bloquear el elemento
                loading_message: 'Guardando...',
                error_message: 'Error al guardar la sección',
                success_message: 'Se guardó correctamente',
                success_callback: function (data) {
                    $('#section_modal').modal('hide');
                    dataTable.ajax.reload();
                }
            });
    }
}

function getformSection(url) {

    ajaxRequest(url, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalSection.find('.container_modal').html('');
            modalSection.find('.container_modal').html(data.html);
            formSection = $("#section_form");
            validateformSection();
            modalSection.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateformSection() {
    formSection.validate({
        rules: {
            title: {
                required: true,
                maxlength: 64,
            }
        },

        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveSection();
        }
    });

}

function addUrl() {
    var count = document.getElementById('url_table').getElementsByTagName('tr').length - 1;
    var url = document.getElementById('url').value;
    if (url && (/^https?:\/\/[\w\-]+(\.[\w\-]+)+[/#?]?.*$/.test(url))) {
        document.getElementById("url_table").insertRow(-1).innerHTML =
            '<tbody><tr><td style="width:15px"><input type="button" class= "btn btn-danger btn-sm" value="x" onclick="delRow(this)"></td><td>' +
            '<a target="_blank" href=' + url + '>' + url + '</a>' +
            '<input readonly style="border: 0; width : 100%; heigth : 15px"  name = "url_element[' + count + ']" type = "hidden" value = ' + url + '>' +
            '</td></tr></tbody>'
        document.getElementById("count").value = document.getElementById('url_table').getElementsByTagName('tr').length - 1;
        setUrl();
    }
}

function setUrl() {
    document.getElementById('url').value = '';
}

function delRow(currentElement) {
    $(currentElement).closest('tr').remove();
}