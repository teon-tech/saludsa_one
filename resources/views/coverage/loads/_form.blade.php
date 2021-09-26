{!! Form::model($coverage, array('id' => 'coverage_form','class' => 'form-horizontal', 'method' => $method)) !!}
{!! Form::hidden('coverage_id', $coverage->id, ['id'=>'coverage_id']) !!}
{!! Form::hidden('plan_id', $plans->id, ['id'=>'plan_id']) !!}

        <div class="form-group ">
            {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-3')) !!}
            <div class="col-md-12">
                {!! Form::text('name', $coverage->name, array('class' => 'form-control', 'autocomplete' =>
                'off','id'=>'name', 'placeholder' => 'ej. Gastos médicos', 'maxlength' => '64')) !!}
            </div>
        </div>
    <div class="row">
        <div class="form-group col-4">
            {!! Form::label('type_coverage_id','* Tipo:', array('class' => 'control-label  col-md-9')) !!}
            <div class="col-md-9">
                {!! Form::select('type_coverage_id', [''=>'--Seleccione--'] + $types, $coverage->type_coverage_id, ['class' => 'form-control', 'required'=>'required', 'id'=>'type_coverage_id'] ) !!}
            </div>
        </div>
        <div class="form-group col-4">
            {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-9')) !!}
            <div class="col-md-9">
                {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$coverage->status,array('class' => 'form-control') ) !!}
            </div>
        </div>
        @if ($plans->product->name === 'ONE')
        <div class="form-group col-4">
            {!! Form::label('isMaternity','* Cobertura de maternidad:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-9">
                {!! Form::select('isMaternity', array( 'YES' => 'SI', 'NO' => 'NO'),$coverage->isMaternity,array('class' => 'form-control') ) !!}
            </div>
        </div>
        @endif
    </div>
    
    <div class="form-group">
        {!! Form::label('description','* Descripción:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-12">
        {!! Form::textarea('description', $coverage->description, array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. ', 'id'=>'description', 'rows'=> 5)) !!}
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
<script>
    CKEDITOR.replace('description', {
        extraPlugins: 'colorbutton,colordialog'
    });
</script>

