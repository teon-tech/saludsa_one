@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de ciudades',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'city_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newCity()',
        'color'=>'btn-primary'
        ],
      ]
    ])

    @include('partials.modal',[
    'title'=>'Crear Ciudad',
    'id'=>'modal',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'city_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])
    <input id="action_get_form" type="hidden" value="{{ route("getFormCity") }}"/>
    <input id="action_unique_name" type="hidden" value="{{ route("uniqueNameCity") }}"/>
    <input id="action_save_City" type="hidden" value="{{ route("saveCity") }}"/>
    <input id="action_load_Cities" type="hidden" value="{{ route("listDataCity") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/city/index.js")}}"></script>
@endsection