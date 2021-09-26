{!! Form::model($model, array('id' => 'model_form','class' => 'form-horizontal', 'method' => $method)) !!}
{!! Form::hidden('model_id', $model->id,['id'=>'model_id']) !!}
<div class="row justify-content-center bg-gray-100 py-8 px-8 py-md-10 px-md-0">
    <div class="col-md-9">
        <div class="d-flex justify-content-between flex-column flex-md-row font-size-lg">
            <div class="d-flex flex-column mb-10 mb-md-0">
                <div class="font-weight-bolder font-size-lg mb-3">Pedido #{{$model->id}}</div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="mr-15 font-weight-bold">Cliente:</span>
                    <span class="text-right">{{$model->customer->name}} {{$model->customer->last_name}}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="mr-15 font-weight-bold">Email:</span>
                    <span class="text-right">{{$model->customer->email}}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="mr-15 font-weight-bold">Teléfono:</span>
                    <span class="text-right">{{$model->customer->phone}}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="mr-15 font-weight-bold">Fecha:</span>
                    <span class="text-right">{{$model->created_at->format('Y/m/d h:i')}}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="mr-15 font-weight-bold">Estado:</span>
                    <span class="text-right">{!! Form::select('status', $status, $model->status,array('class' => 'form-control') ) !!}</span>
                </div>
            </div>
            <div class="d-flex flex-column text-md-right">
                <span class="font-size-lg font-weight-bolder mb-1">TOTAL</span>
                <span class="font-size-h2 font-weight-boldest text-danger mb-1">${{number_format($model->total,2)}}</span>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center py-8 px-8 py-md-10 px-md-0">
    <div class="col-md-9">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th class="pl-0 font-weight-bold text-muted text-uppercase">Producto</th>
                    <th class="text-right font-weight-bold text-muted text-uppercase">Precio</th>
                    <th class="text-right font-weight-bold text-muted text-uppercase">Cantidad</th>
                    <th class="text-right pr-0 font-weight-bold text-muted text-uppercase">Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($model->details as $detail)
                <tr class="font-weight-boldest font-size-lg">
                    <td class="pl-0 pt-1">
                        <div>
                            <a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                {{$detail->product->name}}
                            </a>
                            <span class="text-muted font-weight-bold d-block">SKU: {{$detail->product->code}}</span>
                        </div>
                    </td>
                    <td class="text-right pt-1">${{number_format($detail->price,2)}}</td>
                    <td class="text-right pt-1">{{$detail->quantity}}</td>
                    <td class="text-danger pr-0 pt-1 text-right">${{number_format($detail->total,2)}}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
{!! Form::close() !!}