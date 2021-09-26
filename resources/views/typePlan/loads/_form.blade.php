{!! Form::model($typePlan, array('id' => 'typePlan_form','class' => 'form-horizontal', 'method' => $method)) !!}

{!! Form::hidden('typePlan_id', $typePlan->id, ['id'=>'typePlan_id']) !!}
<div class="form-group">
    {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-12">
        {!! Form::text('name', $typePlan->name, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. Básico', 'maxlength' => '64')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-12">
        {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$typePlan->status,array('class' => 'form-control') ) !!}
    </div>
</div>
{!! Form::close() !!}