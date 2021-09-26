@section('content')
@include('partials.admin_view',[
    'title'=>'Administración de tipos de cobertura',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'typeCoverage_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newTypeCoverage()',
        'color'=>'btn-primary'
        ],
      ]
    ])

    @include('partials.modal',array(
    'title'=>'Crear tipo de cobertura',
    'id' => 'typeCoverage_modal',
    'action_buttons'=>array(
        array(
        'type'=>'submit',
        'form'=>'typeCoverage_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ),
     )
    ))

    <input id="action_get_form" type="hidden" value="{{ route("getFormTypeCoverage") }}"/>
    <input id="action_unique_name" type="hidden" value="{{ route("uniqueNameTypeCoverage") }}"/>
    <input id="action_save_typeCoverage" type="hidden" value="{{ route("saveTypeCoverage") }}"/>
    <input id="action_load_typeCoverages" type="hidden" value="{{ route("listDataTypeCoverage") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/typeCoverage/index.js")}}"></script>
@endsection