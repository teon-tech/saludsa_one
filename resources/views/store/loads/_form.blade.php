{!! Form::model($store, array('id' => 'store_form','class' => 'form-horizontal', 'method' => $method)) !!}

{!! Form::hidden('store_id', $store->id, ['id'=>'store_id']) !!}
<div class="form-group">
    {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::text('name', $store->name, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. Textiles', 'maxlength' => '64')) !!}
    </div>
</div>

    @if (Auth::user()->provider_id)
        {!! Form::hidden('provider_id', Auth::user()->provider_id, array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. Textiles', 'maxlength' => '45', 'readonly'=>'readonly')) !!}
    @else
        <div class="form-group">
            {!! Form::label('provider_id','*Emprendedor:', array('class' => 'control-label  col-md-3')) !!}
            <div class="col-md-9">
                {!! Form::select('provider_id', [''=>'--Seleccione--'] + $providers, $store->provider_id, ['class' => 'form-control', 'required'=>'required', 'id'=>'provider_id'] ) !!}
            </div>
        </div>
    @endif

<div class="form-group">
    {!! Form::label('city_id','*Ciudad:', array('class' => 'control-label  col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::select('city_id', [''=>'--Seleccione--'] + $cities, $store->city_id, ['class' => 'form-control', 'id'=>'city_id'] ) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('address','* Dirección:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::text('address', $store->address, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. Av. Bolivar N-89 y Sucre', 'maxlength' => '256')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('status','*Estado:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::select('status', ['ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'], $store->status ? $store->status: 'ACTIVE', array('class' => 'form-control')); !!}
    </div>
</div>
{!! Form::close() !!}