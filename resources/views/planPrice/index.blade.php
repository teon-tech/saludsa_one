@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de Precios de Planes',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'planPrice_table',
    'action_buttons'=>[
        [
        'label'=>'Crear precio de plan',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newPlanPrice()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear Precio de Plan',
    'id'=>'planPrice_modal',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'planPrice_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])
    <input id="action_get_form" type="hidden" value="{{ route("getFormPlanPrice") }}"/>
    <input id="action_save_planPrice" type="hidden" value="{{ route("savePlanPrice") }}"/>
    <input id="action_load_planPrices" type="hidden" value="{{ route("getListDataPlanPrice") }}"/>
    <input id="action_plans_hospital" type="hidden" value="{{ route("PlansByHospital") }}"/>
    
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/planPrice/index.js")}}"></script>
@endsection