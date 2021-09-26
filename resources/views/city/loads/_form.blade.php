{!! Form::model($city, array('id' => 'city_form','class' => 'form-horizontal', 'method' => $method)) !!}

{!! Form::hidden('city_id', $city->id,['id'=>'city_id']) !!}

<div class="form-group">
    {!! Form::label('province_id','* Provincia:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::select('province_id',[''=>'Seleccione']+$provinces, $city->province_id, array('class' => 'form-control', 'id'=>'province_id' )) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::text('name', $city->name, array('id'=>"name_city",'class' => 'form-control', 'autocomplete' =>
            'off','placeholder' => 'ej. Ibarra', 'maxlength' => '64')) !!}
    </div>
</div>

    <div class="form-group">
        {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-9">
            {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$city->status,array('class' => 'form-control') ) !!}
        </div>
    </div>



{!! Form::close() !!}