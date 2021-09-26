{!! Form::model($province, array('id' => 'province_form','class' => 'form-horizontal', 'method' => $method)) !!}

{!! Form::hidden('province_id', $province->id,['id'=>'province_id']) !!}

<div class="form-group">
    {!! Form::label('region_id','* Región:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-12">
        {!! Form::select('region_id', [''=>'Seleccione']+$regions, $province->region_id, array('class' => 'form-control', 'autocomplete' =>
        'off','id'=>'region_id','required')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-12">
        {!! Form::text('name', $province->name, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. Pichincha', 'maxlength' => '64')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('external_code','* Código externo:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-12">
        {!! Form::text('external_code', $province->external_code, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. P01', 'maxlength' => '100')) !!}
    </div>
</div>
@if ($province->id)
    <div class="form-group">
        {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-12">
            {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$province->status,array('class' => 'form-control') ) !!}
        </div>
    </div>
@endif

{!! Form::close() !!}