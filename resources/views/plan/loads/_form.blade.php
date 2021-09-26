{!! Form::model($plan, array('id' => 'plan_form','class' => 'form-horizontal', 'method' => 'POST')) !!}
{!! Form::hidden('plan_id', $plan->id, ['id'=>'plan_id']) !!}
<div class="row">

    <div class="form-group col-7">
        {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-6')) !!}
        <div class="col-md-12">
            {!! Form::text('name', $plan->name, array('class' => 'form-control', 'autocomplete' =>
            'off', 'placeholder' => 'ej. Salud', 'maxlength' => '64')) !!}
        </div>
    </div>
    <div class="form-group col-5">
        {!! Form::label('code','* Código:', array('class' => 'control-label col-md-6')) !!}
        <div class="col-md-12">
            {!! Form::text('code', $plan->code, array('class' => 'form-control', 'autocomplete' =>
            'off', 'placeholder' => 'ej. plan-esencial', 'maxlength' => '64')) !!}
        </div>
    </div>


</div>
<div class="row">
    <div class="form-group col-4">
        {!! Form::label('type_plan_id','*Familia:', array('class' => 'control-label  col-md-6')) !!}
        <div class="col-md-12">
            {!! Form::select('type_plan_id', [''=>'--Seleccione--'] + $types, $plan->type_plan_id, ['class' => 'form-control', 'required'=>'required', 'id'=>'type_plan_id'] ) !!}
        </div>
    </div>
    <div class="form-group col-8">
        {!! Form::label('keywords','* Palabras claves:', array('class' => 'control-label col-md-9')) !!}
        <div class="col-md-12">
            {!! Form::select('keywords[]',$options, $words, array('class' => 'form-control', 'autocomplete' =>
            'off', 'multiple'=>'true','id'=>'keywords')) !!}
        </div>
    </div>


</div>
<div class="row">

    <div class="form-group col-4">
        {!! Form::label('color_primary','* Color primario:', array('class' => 'control-label col-md-9')) !!}
        <div class="col-md-12">
            {!! Form::color('color_primary', $plan->color_primary ? $plan->color_primary : '#00338d', array('class' => 'form-control', 'autocomplete' =>
            'off', 'placeholder' => 'ej. Salud niños', 'maxlength' => '64', 'value' => '#00338d')) !!}
        </div>
    </div>
    <div class="form-group col-4">
        {!! Form::label('color_secondary','* Color secundario:', array('class' => 'control-label col-md-9')) !!}
        <div class="col-md-12">
            {!! Form::color('color_secondary', $plan->color_secondary ? $plan->color_secondary : '#0073cf' , array('class' => 'form-control', 'autocomplete' =>
            'off', 'placeholder' => 'ej. Salud niños', 'maxlength' => '64')) !!}
        </div>
    </div>
    <div class="form-group col-4">
        {!! Form::label('isComparative','* Es comparativo:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-12">
            {!! Form::select('isComparative', ['NO' => 'NO', 'YES' => 'SI',], $plan->isComparative,array('class' => 'form-control', 'autocomplete' =>
            'off')); !!}

        </div>
    </div>

</div>
<div class="row">
    <div class="form-group col-3">
        {!! Form::label('weight','* Posición:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-9">
            {!! Form::number('weight', $plan->weight, array('class' => 'form-control ', 'autocomplete' =>
            'off','id'=>'title', 'placeholder' => 'ej. 1,2,3', 'min' => '0')) !!}
        </div>
    </div>
    <div class="form-group col-3">
        {!! Form::label('default','* Por defecto:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-12">
            {!! Form::select('default', ['NO' => 'NO', 'YES' => 'SI',], $plan->default,array('class' => 'form-control', 'autocomplete' =>
            'off')); !!}

        </div>
    </div>
    <div class="form-group col-3">
        {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-12">
            {!! Form::select('status', ['ACTIVE' => 'ACTIVO', 'INACTIVE' => 'INACTIVO'], $plan->status,array('class' => 'form-control', 'autocomplete' =>
            'off')); !!}
        </div>
    </div>
    <div class="form-group col-3">
        {!! Form::label('product_id','* Producto:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-12">
            {!! Form::select('product_id', [''=>'Seleccione']+$products, $plan->product_id, array('class' => 'form-control', 'autocomplete' =>
            'off','id'=>'product_id','required')) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-6">
        {!! Form::label('administrative_price','* Gastos Administrativos:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-12">
            {!! Form::number('administrative_price', $plan->administrative_price, array('class' => 'form-control ', 'autocomplete' =>
            'off','id'=>'title', 'placeholder' => 'ej. 10,50', 'min' => '0', 'step' => '0.01')) !!}
        </div>
    </div>
    <div class="form-group col-6">
        {!! Form::label('farmer_tax','* Porcentaje Campesino:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-12">
            {!! Form::number('farmer_tax', $plan->farmer_tax, array('class' => 'form-control ', 'autocomplete' =>
            'off','id'=>'title', 'placeholder' => 'ej. 10', 'min' => '0', 'step' => '0.01')) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-12">
        {!! Form::label('description','* Descripción:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-12">
            {!! Form::textarea('description', $plan->description, array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. ', 'id'=>'description', 'rows'=> 5)) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        @include('partials._dropzone_partial_files',[
                      'title'=>"Archivos",
                      'entity_id'=>$plan->id,
                      'auto_process_queue'=>'no',
                      'files'=>$filePlan,
                      'handle_js_delete'=>'deleteFile',
         ])
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
