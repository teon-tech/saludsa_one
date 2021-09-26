@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de Hospitales',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'hospital_table',
    'action_buttons'=>[
        [
        'label'=>'Crear hospital',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newHospital()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear hospital',
    'id'=>'hospital_modal',
    'size'=>'modal-lg',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'hospital_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])
    <input id="action_get_form" type="hidden" value="{{ route("getFormHospital") }}"/>
    <input id="action_unique_name" type="hidden" value="{{ route("uniqueNameHospital") }}"/>
    <input id="action_save_hospital" type="hidden" value="{{ route("saveHospital") }}"/>
    <input id="action_load_hospitals" type="hidden" value="{{ route("getListDataHospital") }}"/>
    
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/hospital/index.js")}}"></script>
@endsection