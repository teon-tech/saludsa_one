{!! Form::model($hospital, array('id' => 'hospital_form','class' => 'form-horizontal', 'method' => 'POST')) !!}
{!! Form::hidden('hospital_id', $hospital->id, ['id'=>'hospital_id']) !!}
<div class="row">
    <div class="form-group col-6">
        {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-6')) !!}
        <div class="col-md-12">
            {!! Form::text('name', $hospital->name, array('class' => 'form-control', 'autocomplete' =>
            'off', 'placeholder' => 'ej. Salud', 'maxlength' => '100')) !!}
        </div>
    </div>
    <div class="form-group col-6">
        {!! Form::label('keywords','* Palabras claves:', array('class' => 'control-label col-md-9')) !!}
        <div class="col-md-12">
            {!! Form::select('keywords[]',$options, $words, array('class' => 'form-control', 'autocomplete' =>
            'off', 'multiple'=>'true','id'=>'keywords')) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-6">
        {!! Form::label('region','*Región:', array('class' => 'control-label  col-md-3')) !!}
        <div class="col-md-12">
            {!! Form::select('region_id', [''=>'--Seleccione--'] + $regions, $hospital->region_id, ['class' => 'form-control', 'required'=>'required', 'id'=>'region_id'] ) !!}
        </div>
    </div>
    <div class="form-group col-6">
        {!! Form::label('plan_id','*Plan:', array('class' => 'control-label  col-md-3')) !!}
        <div class="col-md-12">
            {!! Form::select('plan_id', [''=>'--Seleccione--'] + $plans, $planSelected, ['class' => 'form-control', 'required'=>'required', 'id'=>'plan_id'] ) !!}
        </div>
    </div>
    <div class="form-group col-6">
        {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-12">
            {!! Form::select('status', ['ACTIVE' => 'ACTIVO', 'INACTIVE' => 'INACTIVO'], $hospital->status,array('class' => 'form-control', 'autocomplete' =>
            'off')); !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-12">
        {!! Form::label('description','* Descripción:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-12">
            {!! Form::textarea('description', $hospital->description, array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. ', 'id'=>'description', 'rows'=> 5)) !!}
        </div>
    </div>
</div>
{!! Form::close() !!}
