{!! Form::model($model, array('id' => 'model_form','class' => 'form-horizontal', 'method' => $method)) !!}
{!! Form::hidden('config_id', $model->id,['id'=>'config_id']) !!}
<div class="form-group ">
    {!! Form::label('name','*Nombre:', array('class' => 'col-form-label col-2')) !!}
    <div class="col-12">
        {!! Form::text('name', $model->name, array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. Gorra', 'maxlength' => '45', 'readonly'=>true)) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('value','Valor:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-12">
        {!! Form::number('value', $model->value,array('class' => 'form-control', 'autocomplete' =>'off',  'min'=> 1 , 'required'=>'required')); !!}
    </div>
</div>

{!! Form::close() !!}
