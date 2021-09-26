@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de tiendas',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'store_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newStore()',
        'color' => 'btn-primary'
        ],
      ]
    ])

    @include('partials.modal',array(
    'title'=>'Crear tienda',
    'id' => 'store_modal',
    'action_buttons'=>array(
        array(
        'type'=>'submit',
        'form'=>'store_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ),
     )
    ))

    <input id="action_get_form" type="hidden" value="{{ route("getFormStore") }}"/>
    <input id="action_unique_name" type="hidden" value="{{ route("uniqueNameStore") }}"/>
    <input id="action_save_store" type="hidden" value="{{ route("saveStore") }}"/>
    <input id="action_load_stores" type="hidden" value="{{ route("listDataStore") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/store/index.js")}}"></script>
@endsection