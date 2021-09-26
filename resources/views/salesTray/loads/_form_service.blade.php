<div class="font-weight-bolder font-size-lg mb-3">Venta # {{ $model->id }}
    <input type="hidden" value = {{$model->id}} id="sale_id">
     <br> Nro. Contrato : {{ $model->contract_number}}
     <br> Estado de pago :  
     @if($model->status_payment === 'PENDING')
         Pendiente
     @endif
     @if($model->status_payment === 'APPROVED')
         Aprobado
     @endif
     @if($model->status_payment === 'REJECTED')
         Rechazado
      @endif
    </div>

<div class="row bg-gray-100">
    <div class="col-md-4">
        <br>
        <div class="font-weight-bolder mb-3">Cliente</div>
        <div class="d-flex justify-content-between">
            <span class="font-weight-bold">Nombre:</span>
            <span class="text-right"> {{ $model->customerData->name }}
                {{ $model->customerData->father_last_name }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="mr-15 font-weight-bold">Email:</span>
            <span class="text-right"> {{ $model->customerData->email }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="font-weight-bold">Género:</span>
            <span class="text-right">
                @if ($model->customerData->gender === 'MALE')
                    Masculino
                @else
                    Femenino
                @endif
            </span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="font-weight-bold">Edad:</span>
            <span class="text-right"> {{ $model->customerData->years_old }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="font-weight-bold">Documento:</span>
            <span class="text-right"> {{ $model->customerData->document }}</span>
        </div>
        <br>
    </div>

    <div class="col-md-4">
        <br>
        <div class="font-weight-bolder mb-3">Subcripción</div>
        @if ($model->subscription()->exists())
        <div class="d-flex justify-content-between">
            <span class="font-weight-bold">Estado de pago:</span>
            <span class="text-right">
                @if ($model->subscription[0]->status_subscription === 'PENDING')
                    Pendiente
                @endif
                @if ($model->subscription[0]->status_subscription === 'APPROVED')
                    Aprobado
                @endif
                @if ($model->subscription[0]->status_subscription === 'REJECTED')
                    Rechazado
                @endif
            </span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="mr-15 font-weight-bold">Tipo:</span>
            <span class="text-right">
                @if ($model->subscription[0]->type === 'MONTHLY')
                    Mensual
                @else
                    Anual
                @endif
            </span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="font-weight-bold">Estado:</span>
            <span class="text-right">
                @if ($model->subscription[0]->status === 'ACTIVE')
                    Activo
                @else
                    Inactivo
                @endif
            </span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="font-weight-bold">Fecha de inicio:</span>
            <span class="text-right"> {{ $model->subscription[0]->start_date }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="font-weight-bold">Última fecha de pago:</span>
            <span class="text-right"> {{ $model->subscription[0]->last_payment_at }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="font-weight-bold">Total:</span>
            <span class="text-right"> {{ $model->subscription[0]->total }}</span>
        </div>
        <br>
        @else
        <div class="d-flex justify-content-between">
            <span class="font-weight-bold">Susbcripción no creada</span>
        </div>
            @endif
    </div>

    <div class="col-md-4">
        <br>
        <div class="font-weight-bolder mb-3">Plan</div>
        <div class="d-flex justify-content-between">
            <span class="font-weight-bold">Nombre:</span>
            <span class="text-right">{{ $model->details[0]->planPrice->plan->name }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="mr-15 font-weight-bold">Tipo:</span>
            <span class="text-right">{{ $model->details[0]->planPrice->plan->typePlan->name }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="mr-15 font-weight-bold">Gastos administrativo:</span>
            <span class="text-right">{{ $model->details[0]->planPrice->plan->administrative_price }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="mr-15 font-weight-bold">Porcentaje campesino:</span>
            <span class="text-right">{{ $model->details[0]->planPrice->plan->farmer_tax }}</span>
        </div>
        <br>
    </div>
</div>
<div class="row justify-content-center py-8 px-8 py-md-10 px-md-0">
        <div class="col-md-6" id ='ServiceSale'>
            <label class="font-weight-bold text-center text-uppercase">Servicio ventas</label>
            <div class="table-responsive">
                <table class="table" id='table_service_sale'>
                    <thead>
                        <tr>
                            <th class="text-right pr-0 font-weight-bold">Servicio</th>
                            <th class="text-right pr-0 font-weight-bold">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        @foreach ($servicesSale as $service)
                        <tr>
                            <td class="text-right">
                            @if ($service->name === 'paymentHeaderEntry')
                               Registro de cabecera 
                            @endif
                            @if ($service->name === 'createPaymentDetail')
                                Creación de detalle
                            @endif
                            @if ($service->name === 'createPaymentMethod')
                                Creación forma de pago
                            @endif
                            </td>
                            <td class="text-right pt-1">
                                <button class="btn btn-dark btn-sm" onclick="viewInfoService( {{$service->id}})"> 
                                     {{$service->status_code}}
                                </button>
                                @if($service->status_code === 'Error')
                                <a onclick="retryService({{$service->id}})"> 
                                    <i title = 'Ejecutar servicio nuevamente' class="flaticon2-reload icon-md text-dark"></i>
                                    </a>
                                @endif 
                            </td>
                        </tr>
                    @endforeach 
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <label class="font-weight-bold text-uppercase" >Servicio Sigmep</label>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-right pr-0 font-weight-bold">Servicio</th>
                            <th class="text-right pr-0 font-weight-bold">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        @foreach ($servicesSigmep as $service)
                        <tr>
                            <td class="text-right">
                            @if ($service->name === 'ServiceSigmep')
                               Creación de contrato
                            @endif
                            </td>
                            <td class="text-right pt-1">
                               <button class="btn btn-dark btn-sm" onclick="viewInfoService( {{$service->id}})">
                                @if($service->status_code === '0') 
                                Error
                                @else
                                  OK  
                                @endif
                                </button>
                                @if($service->status_code === '0')
                                <a onclick="retryService({{$service->id}})"> 
                                    <i title = 'Ejecutar servicio nuevamente' class="flaticon2-reload icon-md text-dark"></i>
                                    </a>
                                @endif 
                            </td>
                        </tr>
                    @endforeach 
                    </tbody>
                </table>
            </div>
        </div>
</div>