@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de unidades de medida',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'unit_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newUnit()',
        'color' => 'btn-primary'
        ],
      ]
    ])

    @include('partials.modal',array(
    'title'=>'Crear Unidad de medida',
    'id' => 'unit_modal',
    'action_buttons'=>array(
        array(
        'type'=>'submit',
        'form'=>'unit_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ),
     )
    ))

    <input id="action_get_form" type="hidden" value="{{ route("getFormUnit") }}"/>
    <input id="action_unique_name" type="hidden" value="{{ route("uniqueNameUnit") }}"/>
    <input id="action_save_unit" type="hidden" value="{{ route("saveUnit") }}"/>
    <input id="action_load_units" type="hidden" value="{{ route("listDataUnit") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/unit/index.js")}}"></script>
@endsection