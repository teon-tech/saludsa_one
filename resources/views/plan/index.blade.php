@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de planes',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'plan_table',
    'action_buttons'=>[
        [
        'label'=>'Crear plan',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newPlan()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear plan',
    'id'=>'plan_modal',
    'size'=>'modal-lg',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'plan_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])
    @include('partials.modal',[
        'title'=>'Crear coberturas',
        'id'=>'coverage_modal',
        'size'=>'modal-xl',
        'action_buttons'=>[
         ]
        ])
    <input id="action_get_form" type="hidden" value="{{ route("getFormPlan") }}"/>
    <input id="action_unique_name" type="hidden" value="{{ route("uniqueNamePlan") }}"/>
    <input id="action_unique_code" type="hidden" value="{{ route("uniqueCodePlan") }}"/>
    <input id="action_save_plan" type="hidden" value="{{ route("savePlan") }}"/>
    <input id="action_load_plans" type="hidden" value="{{ route("getListDataPlan") }}"/>
    <input id="action_index_coverage" type="hidden" value="{{ route("indexViewCoverage") }}"/>
    <input id="action_index_section" type="hidden" value="{{ route("indexViewSection") }}"/>
    <input id="action_index_question" type="hidden" value="{{ route("indexViewQuestion") }}"/>
    <input id="action_deleted_plan" type="hidden" value="{{ route("deletedPlan") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/plan/index.js")}}"></script>
@endsection