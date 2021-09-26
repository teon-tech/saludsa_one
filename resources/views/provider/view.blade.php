@section('additional-styles')
    <link href="{{asset('nifty/plugins/dropzone/dropzone.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="flex-row-fluid ml-lg-8">

        <!--begin::Card-->
        <div class="card card-custom card-stretch">
            <!--begin::Header-->
            <div class="card-header py-3">
                <div class="card-title align-items-start flex-column">
                    <h3 class="card-label font-weight-bolder text-dark">Información personal</h3>
                </div>
                <div class="card-toolbar">
                    <button type="submit" onclick="saveProvider()" class="btn btn-success mr-2">Guardar cambios</button>
                </div>
            </div>
            <!--end::Header-->
            <div class="card-body">
                <!--begin::Form-->
                {!! Form::model($user, array('id' => 'user_form','class' => 'form', 'method' => 'POST')) !!}
                {!! Form::hidden('user_id', $user->id,['id'=>'user_id']) !!}
                {!! Form::hidden('provider_id', $provider->id,['id'=>'input_provider_id']) !!}
                <div class="form-group row">
                    {!! Form::label('name','* Nombre:', array('class' => 'col-xl-3 col-lg-3 col-form-label')) !!}
                    <div class="col-lg-9 col-xl-6">
                        {!! Form::text('name', $user->name, array('class' => 'form-control form-control-lg form-control-solid', 'required' , 'autocomplete' =>
                   'off', 'placeholder' => 'ej. Operador', 'maxlength' => '64')) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('email','* Email:', array('class' => 'col-xl-3 col-lg-3 col-form-label')) !!}
                    <div class="col-lg-9 col-xl-6">
                        {!! Form::email('email', $user->email, array('class' => 'form-control form-control-lg form-control-solid', 'required', 'autocomplete' =>
                   'off', 'placeholder' => 'ej. tienda@gmail.com', 'maxlength' => '64')) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('provider_name','* Marca:', array('class' => 'col-xl-3 col-lg-3 col-form-label')) !!}
                    <div class="col-lg-9 col-xl-6">
                        {!! Form::text('provider_name', $provider->name, array('class' => 'form-control form-control-lg form-control-solid', 'required', 'autocomplete' =>
                   'off', 'placeholder' => 'ej. Textil su ofertón', 'maxlength' => '128')) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('provider_owner','* Propietario:', array('class' => 'col-xl-3 col-lg-3 col-form-label')) !!}
                    <div class="col-lg-9 col-xl-6">
                        {!! Form::text('provider_owner', $provider->owner, array('class' => 'form-control form-control-lg form-control-solid', 'required', 'autocomplete' =>
                   'off', 'placeholder' => 'ej. Pedro', 'maxlength' => '128')) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('provider_address','* Dirección:', array('class' => 'col-xl-3 col-lg-3 col-form-label')) !!}
                    <div class="col-lg-9 col-xl-6">
                        {!! Form::text('provider_address', $provider->address, array('class' => 'form-control form-control-lg form-control-solid', 'required', 'autocomplete' =>
                   'off', 'placeholder' => 'ej. Bolivar N-89 y Sucre', 'maxlength' => '128')) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('provider_category','* Categoría:', array('class' => 'col-xl-3 col-lg-3 col-form-label')) !!}
                    <div class="col-lg-9 col-xl-6">
                        {!! Form::text('provider_category', $provider->category, array('class' => 'form-control form-control-lg form-control-solid', 'required', 'autocomplete' =>
                   'off', 'placeholder' => 'ej. Productor textil', 'maxlength' => '64')) !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('provider_code','*Código:', array('class' => 'col-xl-3 col-lg-3 col-form-label')) !!}
                    <div class="col-lg-9 col-xl-6">
                        {!!  Form::text('provider_code',$provider->code, ['class' => 'form-control form-control-lg form-control-solid', 'required', 'autocomplete' => 'off', 'placeholder' => 'ej. marcaropa', 'maxlength' => '10'])!!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('provider_phone','*Teléfono:', array('class' => 'col-xl-3 col-lg-3 col-form-label')) !!}
                    <div class="col-lg-9 col-xl-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button class="btn btn-secondary" type="button">+593</button>
                                {!! Form::hidden('provider_country_code', '593') !!}
                            </div>
                            {!!  Form::text('provider_phone', $provider->phone, ['class' => 'form-control form-control-lg form-control-solid', 'required' , 'autocomplete' => 'off', 'placeholder' => 'ej. 987654321', 'maxlength' => '10', 'required'=>'required'])!!}
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('provider_description','Descripción:', array('class' => 'control-label col-md-3')) !!}
                    <div class="col-lg-9 col-xl-6">
                        {!!  Form::textarea('provider_description', $provider->description, ['class' => 'form-control form-control-lg form-control-solid', 'autocomplete' => 'off', 'placeholder' => 'ej. Las mejores ofertas....', 'rows'=>3])!!}
                    </div>
                </div>
                <details>
                    <summary>Cambiar contraseña</summary>
                    <div class="form-group row">
                        {!! Form::label('password','* Contraseña:', array('class' => 'col-xl-3 col-lg-3 col-form-label')) !!}
                        <div class="col-lg-9 col-xl-6">
                            {!! Form::password('password', array('class' => 'form-control form-control-lg form-control-solid', 'required' , "id"=>'password', 'autocomplete' =>
                            'off', 'maxlength' => '64')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('confirm_password','* Confirmar Contraseña:', array('class' => 'col-xl-3 col-lg-3 col-form-label')) !!}
                        <div class="col-lg-9 col-xl-6">
                            {!! Form::password('confirm_password',  array('class' => 'form-control form-control-lg form-control-solid', 'required', 'autocomplete' =>
                            'off', "id"=>'confirm_password',)) !!}
                        </div>
                    </div>
                </details>
                <br>
                <div class="form-group row">
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
            <!--end::Form-->
            </div>
        </div>
    </div>

    <input type="hidden" id="action_get_view" value="{{route('viewProfileProvider')}}">
    <input type="hidden" id="action_unique_code" value="{{route('uniqueCodeProvider')}}">
    <input type="hidden" id="action_unique_email" value="{{route('uniqueEmailUser')}}">
    <input type="hidden" id="action_save" value="{{route('updateProfile')}}">
@endsection
@section('additional-scripts')
    <script src="{{asset("nifty/plugins/dropzone/dropzone.js")}}"></script>
    <script src="{{asset("js/app/provider/view.js")}}"></script>
@endsection