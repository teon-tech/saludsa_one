@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de provincias',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'province_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newProvince()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear Provincia',
    'id'=>'modal',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'province_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])
    <input id="action_get_form" type="hidden" value="{{ action("Geographic\ProvinceController@getFormProvince") }}"/>
    <input id="action_unique_name" type="hidden" value="{{ action("Geographic\ProvinceController@postIsNameUnique") }}"/>
    <input id="action_save_province" type="hidden" value="{{ action("Geographic\ProvinceController@postSave") }}"/>
    <input id="action_load_provinces" type="hidden"
           value="{{ action("Geographic\ProvinceController@getListProvinces") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/province/index.js")}}"></script>
@endsection