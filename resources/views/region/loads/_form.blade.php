{!! Form::model($region, array('id' => 'region_form','class' => 'form-horizontal', 'method' => $method)) !!}

{!! Form::hidden('region_id', $region->id,['id'=>'region_id']) !!}
<div class="form-group">
    {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-12">
        {!! Form::text('name', $region->name, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. Sierra , Costa', 'maxlength' => '100')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('external_code','* Código externo:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-12">
        {!! Form::text('external_code', $region->external_code, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. P01', 'maxlength' => '100')) !!}
    </div>
</div>
@if ($region->id)
    <div class="form-group">
        {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-12">
            {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$region->status,array('class' => 'form-control') ) !!}
        </div>
    </div>
@endif

{!! Form::close() !!}