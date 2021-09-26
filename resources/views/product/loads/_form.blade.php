{!! Form::model($model, array('id' => 'model_form','class' => 'form-horizontal', 'method' => $method)) !!}
{!! Form::hidden('model_id', $model->id,['id'=>'model_id']) !!}
<div class="form-group ">
    {!! Form::label('name','*Nombre:', array('class' => 'col-form-label col-2')) !!}
    <div class="col-12">
        {!! Form::text('name', $model->name, array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. Gorra', 'maxlength' => '45', 'required'=>'required')) !!}
    </div>
</div>
@if ($model->id)
<div class="form-group">
    {!! Form::label('status','Estado:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-12">
        {!! Form::select('status', ['ACTIVE' => 'ACTIVO', 'INACTIVE' => 'INACTIVO'], $model->status, array('class' => 'form-control') ) !!}
    </div>
</div>
@endif
{!! Form::close() !!}
