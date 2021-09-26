@section('content')
@include('partials.admin_view',[
    'title'=>'Administración de familias de plan',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'typePlan_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newTypePlan()',
        'color'=>'btn-primary'
        ],
      ]
    ])

    @include('partials.modal',array(
    'title'=>'Crear familia de plan',
    'id' => 'typePlan_modal',
    'action_buttons'=>array(
        array(
        'type'=>'submit',
        'form'=>'typePlan_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ),
     )
    ))

    <input id="action_get_form" type="hidden" value="{{ route("getFormTypePlan") }}"/>
    <input id="action_unique_name" type="hidden" value="{{ route("uniqueNameTypePlan") }}"/>
    <input id="action_save_typePlan" type="hidden" value="{{ route("saveTypePlan") }}"/>
    <input id="action_load_typePlans" type="hidden" value="{{ route("listDataTypePlan") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/typePlan/index.js")}}"></script>
@endsection