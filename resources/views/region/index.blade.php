@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de regiones',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'region_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newRegion()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear Región',
    'id'=>'modal',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'region_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])
    <input id="action_get_form" type="hidden" value="{{ route("getFormRegion") }}"/>
    <input id="action_unique_name" type="hidden" value="{{ route("uniqueNameRegion") }}"/>
    <input id="action_save_region" type="hidden" value="{{ route("saveRegion") }}"/>
    <input id="action_load_regions" type="hidden" value="{{ route("listDataRegion") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/region/index.js")}}"></script>
@endsection