{!! Form::model($planPrice, array('id' => 'planPrice_form','class' => 'form-horizontal', 'method' => 'POST')) !!}
{!! Form::hidden('planPrice_id', $planPrice->id, ['id'=>'planPrice_id']) !!}

    <div class="form-group">
        {!! Form::label('hospital','*Plan:', array('class' => 'control-label  col-md-3')) !!}
        <div class="col-md-12">
            {!! Form::select('plan_id', [''=>'--Seleccione--'] + $plans, $planPrice->plan_id, ['class' => 'form-control', 'required'=>'required', 'id'=>'hospital_id'] ) !!}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-5">
            {!! Form::label('gender','* Género:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
                {!! Form::select('gender', ['MALE' => 'Masculino', 'FEMALE' => 'Femenino'], $planPrice->gender,array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => '- Seleccione -', 'required'=>'required')); !!}
            </div>
        </div>
        <div class="form-group col-md-7">
            {!! Form::label('range','* Rango de edad:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-5">
                        {!! Form::number('range_age_from', $planPrice->range_age_from,array('class' => 'form-control', 'autocomplete' =>
                        'off',  'min'=> 1 , 'required'=>'required')); !!}
                    </div>-
                    <div class="col-md-5">
                        {!! Form::number('range_age_to', $planPrice->range_age_to,array('class' => 'form-control', 'autocomplete' =>
                        'off', 'min'=> 1 , 'required'=>'required')); !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-6">
            {!! Form::label('base_value','* Valor base:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
                {!! Form::number('base_value', $planPrice->base_value , array('class' => 'form-control', 'autocomplete' =>
                'off', 'required'=>'required')); !!}
            </div>
        </div>
        <div class="form-group col-6">
            {!! Form::label('enable_discounted','* Activar descuento anual:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
                {!! Form::select('enable_discounted', array( 'YES' => 'SI', 'NO' => 'NO'),$planPrice->enable_discounted,array('class' => 'form-control') ) !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-12">
            {!! Form::select('status', ['ACTIVE' => 'ACTIVO', 'INACTIVE' => 'INACTIVO'], $planPrice->status,array('class' => 'form-control', 'autocomplete' =>
            'off')); !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('label_discount','* Descripción de descuento:', array('class' => 'control-label col-md-6')) !!}
        <div class="col-md-12">
            {!! Form::text('label_discount', $planPrice->label_discount, array('class' => 'form-control', 'autocomplete' =>
            'off', 'placeholder' => 'ej. ¡Tu primera cuota gratis!', 'maxlength' => '256')) !!}
        </div>
    </div>

{!! Form::close() !!}