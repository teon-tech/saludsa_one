{!! Form::model($typeCoverage, array('id' => 'typeCoverage_form','class' => 'form-horizontal', 'method' => $method)) !!}

{!! Form::hidden('typeCoverage_id', $typeCoverage->id, ['id'=>'typeCoverage_id']) !!}
<div class="form-group">
    {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::text('name', $typeCoverage->name, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. Completa', 'maxlength' => '64')) !!}
    </div>
</div>
<div class="form-group ">
    {!! Form::label('weight','* Posición:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::number('weight', $typeCoverage->weight, array('class' => 'form-control', 'autocomplete' =>
        'off','id'=>'title', 'placeholder' => 'ej. 1,2,3', 'min' => '0')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$typeCoverage->status,array('class' => 'form-control') ) !!}
    </div>
</div>
{!! Form::close() !!}