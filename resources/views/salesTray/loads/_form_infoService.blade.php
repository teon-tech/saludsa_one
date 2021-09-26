<div class="row bg-gray-100">
    <div class="col-md-6">
        <br>
        <div class="d-flex">
            <span class="font-weight-bold">Servicio:</span>
            <span class="text-right">
                @if ($service->name === 'paymentHeaderEntry')
                    Registro de cabecera
                @endif
                @if ($service->name === 'createPaymentDetail')
                    Creación de detalle
                @endif
                @if ($service->name === 'createPaymentMethod')
                    Creación forma de pago
                @endif
                @if ($service->name === 'ServiceSigmep')
                    Creación de contrato sigmep
                @endif
            </span>
        </div>
        <div class="d-flex">
            <span class="font-weight-bold">URL:</span>
            <span class="text-right"> {{ $service->url }}</span>
        </div>
        <div class="d-flex">
            <span class="font-weight-bold">Estado:</span>
            <span class="text-right"> {{ $service->status_code }}</span>
        </div>
        <br>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <br>
        <div class="font-weight-bolder mb-3">Datos enviados</div>
        @if ($service->name === 'createPaymentMethod')
            <p>
                @foreach ($service->payload[0] as $index => $item)
                    {{ $index }} : {{ $item }} <br>
                @endforeach
            </p>
        @else
            @if ($service->name === 'ServiceSigmep')
                <p>
                    @foreach ($service->payload as $index => $item)
                        {{ $index }} : [ <br>
                        @foreach ($item as $saludsaSales => $saludsaSale)
                            {{ $saludsaSales }} : [ <br>
                            @foreach ($saludsaSale as $datosSaludsaS => $info)
                                {{ $datosSaludsaS }} : {{ $info }} <br>
                            @endforeach
                            ]
                        @endforeach
                        <br>]
                    @endforeach
                </p>
            @else
                <p>
                    @foreach ($service->payload as $index => $item)
                        {{ $index }} : {{ $item }} <br>
                    @endforeach
                </p>
            @endif
        @endif

    </div>
    <div class="col-md-6">
        <br>
        <div class="font-weight-bolder mb-3">Respuesta</div>
        @if ($service->name === 'createPaymentMethod')
            <p>
                @foreach ($service->response[0] as $index => $item)
                    {{ $index }} : {{ $item }} <br>
                @endforeach
            </p>
        @else
            @if ($service->name === 'ServiceSigmep')
                <p>
                    @foreach ($service->response['Error'] as $index => $item)
                        {{ $index }} : {{ $item }} <br>
                    @endforeach
                </p>
            @else
                <p>
                    @foreach ($service->response as $index => $item)
                        {{ $index }} : {{ $item }} <br>
                    @endforeach
                </p>
            @endif
        @endif
    </div>
</div>
