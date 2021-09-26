{!! Form::model($country, array('id' => 'country_form','class' => 'form-horizontal', 'method' => $method)) !!}

{!! Form::hidden('country_id', $country->id,['id'=>'country_id']) !!}
<div class="form-group">
    {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-12">
        {!! Form::text('name', $country->name, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. Ecuador', 'maxlength' => '64')) !!}
    </div>
</div>
@if ($country->id)
    <div class="form-group">
        {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-9">
            {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$country->status,array('class' => 'form-control') ) !!}
        </div>
    </div>
@endif

{!! Form::close() !!}