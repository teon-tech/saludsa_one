@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de países',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'country_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newCountry()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear Pais',
    'id'=>'modal',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'country_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])
    <input id="action_get_form" type="hidden" value="{{ action("Geographic\CountryController@getFormCountry") }}"/>
    <input id="action_unique_name" type="hidden" value="{{ action("Geographic\CountryController@postIsNameUnique") }}"/>
    <input id="action_save_country" type="hidden" value="{{ action("Geographic\CountryController@postSave") }}"/>
    <input id="action_load_countries" type="hidden"
           value="{{ action("Geographic\CountryController@getListCountries") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/country/index.js")}}"></script>
@endsection