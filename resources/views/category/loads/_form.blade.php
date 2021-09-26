{!! Form::model($category, array('id' => 'category_form','class' => 'form-horizontal', 'method' => $method)) !!}

{!! Form::hidden('category_id', $category->id, ['id'=>'category_id']) !!}
<div class="form-group">
    {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::text('name', $category->name, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. Vestidos', 'maxlength' => '64')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('level','* Nivel:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::number('level', $category->level, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. 1,2', 'step'=>'1','min'=>'1','max'=>'3')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('parent_category_id','Categoría Padre:', array('class' => 'control-label  col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::select('parent_category_id', [''=>'--Seleccione--'] + $categories, $category->parent_category_id, ['class' => 'form-control', 'id'=>'categories'] ) !!}
    </div>
</div>
@if ($category->id != null)
<div class="form-group">
    {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::select('status', ['ACTIVE' => 'ACTIVO', 'INACTIVE' => 'INACTIVO'], $category->status,array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => '- Selecione -')); !!}
    </div>
</div>
@endif

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
{!! Form::close() !!}