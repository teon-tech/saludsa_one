{!! Form::model($modelProvider, array('id' => 'provider_form','class' => 'form-horizontal ', 'method' => 'POST')) !!}

{!! Form::hidden('provider_id', $modelProvider->id, ['id'=>'provider_id']) !!}
<div class="form-group">
    {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-12">
        {!!  Form::text('name', $modelProvider->name, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. Marca de ropa', 'maxlength' => '128'])!!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('owner','* Propietario:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-12">
        {!!  Form::text('owner', $modelProvider->owner, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. Pedro ', 'maxlength' => '128'])!!}
    </div>
</div>
<div class="row">
    <div class="form-group col-6">
        {!! Form::label('category','*Categoría:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-12">
            {!!  Form::text('category', $modelProvider->category, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. Productor textil', 'maxlength' => '64'])!!}
        </div>
    </div>
    <div class="form-group col-6">
        {!! Form::label('address','*Dirección:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-12">
            {!!  Form::text('address', $modelProvider->address, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. Av. Bolivar N-89 y Sucre', 'maxlength' => '64'])!!}
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-4">
        {!! Form::label('code','*Código:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-12">
            {!!  Form::text('code', $modelProvider->code, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. marcaropa', 'maxlength' => '10'])!!}
        </div>
    </div>
    <div class="form-group col-4">
        {!! Form::label('phone','*Teléfono:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-12">
            <div class="input-group">
                <div class="input-group-prepend">
                    <button class="btn btn-secondary" type="button">+593</button>
                    {!! Form::hidden('country_code', '593') !!}
                </div>
                {!!  Form::text('phone', $modelProvider->phone, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. 987654321', 'maxlength' => '10', 'required'=>'required'])!!}
            </div>
        </div>
    </div>
    <div class="form-group col-4">
        {!! Form::label('status','*Estado:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-12">
            {!! Form::select('status', ['ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'], $modelProvider->status ? $modelProvider->status: 'INACTIVE', array('class' => 'form-control')); !!}
        </div>
    </div>
</div>
<div class="form-group">
    {!! Form::label('description','Descripción:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-12">
        {!!  Form::textarea('description', $modelProvider->description, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. Las mejores ofertas....', 'rows'=>3])!!}
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        @foreach($image_parameters  as $image_parameter)
            @include('partials._dropzone_partial',[
                          'title'=>"{$image_parameter['label']} ({$image_parameter['width']}px * {$image_parameter['height']}px) {$image_parameter['extension']}",
                          'max_width'=>$image_parameter['width'],
                          'max_height'=>$image_parameter['height'],
                          'entity_id'=>$image_parameter['id'],
                          'wrapper_class'=>'wrapper_image',
                          'accepted_files'=>$image_parameter['extension'],
                          'auto_process_queue'=>'no',
                          'images'=>$image_parameter['images'],
                          'handle_js_delete'=>'deleteImage',
             ])
        @endforeach
    </div>
</div>
{!! Form::close() !!}