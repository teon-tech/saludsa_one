var modalModel = null;
var formModel = null;
var dataTable = null;
var modalSaleService = null;
var formSaleService = null;
var modalInfoService = null;
var datatableService = null;

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
                    data: "contract_number",
                    title: 'Nro. Contrato',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return row.contract_number;
                    }
                },
                {
                    data: null,
                    title: 'Cliente',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return `${row.customer_data.name} ${row.customer_data.father_last_name}`;
                    }
                },
                {
                    data: null,
                    title: 'Fecha ',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return moment(row.created_at).format('YYYY/MM/DD HH:mm:ss');
                    }
                },
                {
                    data: "customer_data.email",
                    title: 'Email',
                    orderable: false,
                },
                {
                    data: null,
                    title: 'Estado de pago',
                    orderable: false,
                    render: function(data, type, row, meta){
                        if(row.status_payment === 'PENDING'){
                            return 'Pendiente';
                        }
                        if(row.status_payment === 'APPROVED'){
                            return 'Aprobado';
                        }
                        if(row.status_payment === 'REJECTED'){
                            return 'Rechazado';
                        }
                        if(row.status_payment === 'REJECT_ATEMPT'){
                            return 'Intento Rechazado';
                        }
                    }
                },
                {
                    data: 'subscription[0].last_payment_at',
                    title: 'Fecha último pago',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        if(data){
                            return moment(data).format('YYYY/MM/DD HH:mm:ss');
                        }else{
                            return "";
                        }  
                    }
                },
                {
                    data: "details[0].plan_price.plan.name",
                    title: 'Plan',
                    orderable: false,
                },
                {
                    data: null,
                    title: 'Acciones',
                    orderable: false,
                    width:170,
                    render: function (data, type, row, meta) {
                        return ' <button class="btn btn-dark btn-sm" onclick="editModel(' + row.id + ')">Ver</button> <button class="btn btn-dark btn-sm" onclick="viewSaleService(' + row.id + ')">Integraciones</button>';
                    }
                }
            ]
        });
    modalModel = $('#model_modal');
    modalSaleService = $('#modal_sale_service');
    modalInfoService = $('#modal_info_service');
});

function editModel(id) {
    modalModel.find('.modal-title').html('Información de la venta');
    getFormModel($('#action_get_form').val() + '/' + id);
}

function viewSaleService(id) {
    modalSaleService.find('.modal-title').html('Información servicio de venta');
    getFormModelService($('#action_get_form_service').val() + '/' + id);
}

function viewInfoService(id) {
    modalInfoService.find('.modal-title').html('Información del servicio');
    getFormModalInfoService($('#action_get_form_info_service').val() + '/' + id);
}

function getFormModel(action) {
    ajaxRequest(action, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalModel.find('.container_modal').html('');
            modalModel.find('.container_modal').html(data.html);
            formModel = $("#model_form");
            modalModel.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function getFormModelService(action) {
    ajaxRequest(action, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalSaleService.modal('hide');
            modalSaleService.find('.container_modal').html('');
            modalSaleService.find('.container_modal').html(data.html);
            //datatableService = $('#table_service_sale').initDataTableAjax();
            modalSaleService.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function getFormModalInfoService(action) {
    ajaxRequest(action, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalInfoService.find('.container_modal').html('');
            modalInfoService.find('.container_modal').html(data.html);
            modalInfoService.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function retryService(id){
    let saleId = $('#sale_id').val();
    ajaxRequest(
        $('#action_retry_service').val(),
        {
            type: 'POST',
            data : { 
                'id' : id
            },
            blockElement: '#modal_sale_service .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Reintento en proceso..',
            error_message: 'Error',
            success_message: 'Reintento exitoso',
            success_callback: function (data) {
                modalSaleService.modal('hide');
                viewSaleService(saleId);
            }
        });
}