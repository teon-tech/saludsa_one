{!! Form::model($modelPublicity, array('id' => 'publicity_form','class' => 'form-horizontal ', 'method' => 'POST')) !!}

{!! Form::hidden('publicity_id', $modelPublicity->id, ['id'=>'publicity_id']) !!}
<div class="form-group">
    {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-12">
        {!! Form::text('name', $modelPublicity->name, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. Título publicidad', 'maxlength' => '45'])!!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('link','* Link:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-12">
        {!! Form::text('link', $modelPublicity->link, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. www.facebook.com', 'maxlength' => '1024'])!!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$modelPublicity->status,array('class' => 'form-control') ) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('description','Descripción:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-12">
        {!! Form::textarea('description', $modelPublicity->description, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. algo', 'rows' => 3])!!}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        @foreach($image_parameters as $image_parameter)
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