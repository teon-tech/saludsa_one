<div class="font-weight-bolder font-size-lg mb-3">Venta # {{ $model->id }} 
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
    <div class="col-md-10">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-right pr-0 font-weight-bold text-uppercase">Estado de la transacción</th>
                        <th class="text-right pr-0 font-weight-bold text-uppercase">Fecha de pago</th>
                        <th class="text-right pr-0 font-weight-bold text-uppercase">Cód. Aprobación</th>
                        <th class="text-right pr-0 font-weight-bold text-uppercase">Cód. Transacción</th>
                        <th class="text-right pr-0 font-weight-bold text-uppercase">Nro. Ticket</th>
                        <th class="text-right pr-0 font-weight-bold  text-uppercase">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($model->subscription()->exists())
                    @foreach ($model->subscription[0]->subscriptionLog as $log)
                    <tr>
                        <td class="text-right pt-1">
                            @if ($log->transaction_status === 'subscriptionDelete')
                                Subscripción eliminada
                            @endif
                            @if ($log->transaction_status === 'failedRetry')
                                Reintento fallido
                            @endif
                            @if ($log->transaction_status === 'declinedCharge')
                                Rechazado
                            @endif
                            @if ($log->transaction_status === 'succesfullCharge')
                            Aprobado
                            @endif
                            @if ($log->transaction_status === 'UPDATED_SUBSCRIPTION')
                            Actualización de subscripción
                            @endif
                        </td>
                        <td class="text-right pt-1">{{ $log->payment_date }}</td>
                        <td class="text-right pt-1">{{ $log->approval_code }}</td>
                        <td class="text-right pt-1">{{ $log->transaction_code }}</td>
                        <td class="text-right pt-1">{{ $log->ticket_number }}</td>
                        <td class="text-right pt-1">{{ $log->total }}</td>
                    </tr>
                @endforeach 
                @endif
                </tbody>
            </table>
        </div>
    </div>
    {!! Form::close() !!}
