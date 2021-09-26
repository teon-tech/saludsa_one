{!! Form::model($unit, array('id' => 'unit_form','class' => 'form-horizontal', 'method' => $method)) !!}

{!! Form::hidden('unit_id', $unit->id, ['id'=>'unit_id']) !!}
<div class="form-group">
    {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::text('name', $unit->name, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. Talla', 'maxlength' => '64')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('values','* Valores:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::text('values', $unit->values, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. small,large', 'maxlength' => '64')) !!}
    </div>
</div>
{!! Form::close() !!}