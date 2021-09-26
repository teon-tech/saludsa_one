@section('content')
<div class="bg-white " style="margin-top: -75px ; text-align: center; width: 1040px ; align-self: center;">
    
      <br> 
      <div class="d-flex flex-column mb-10 mb-md-0">
    <label style="text-aling: center"><b>Información del plan</b> </label><br>
   <span><label><b>Nombre :</b> </label>
    <label>{{$plan->name}} </label></span>
    <span><label><b>Tipo :</b> </label>
    <label>{{$plan->typePlan->name}} </label></span>
    <span><label><b>Estado :</b> </label>
      @if ($plan->status == 'ACTIVE')
      <label> Activo</label></span>
      @else
      <label>Inactivo </label></span>
      @endif
    </div> 
       <div  style="text-align: left; margin: 10px;">
        <a href="{{route('indexViewPlan')}}" target=""><span class="btn btn-secondary btn-left"><i class="fas fa-angle-double-left"></i> Atrás</span></a> </div>       
    </div>
    
  <br><br><br>
    @include('partials.admin_view',[
    'title'=>'Administración de coberturas',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'coverage_table',
    'action_buttons'=>[
        [
        'label'=>'Crear cobertura',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newCoverage()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear cobertura',
    'id'=>'coverage_modal',
    'size'=>'modal-lg',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'coverage_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])

    <input id="action_get_form_coverage" type="hidden" value="{{ route("getFormCoverage", $plan->id) }}"/>
    <input id="action_unique_coverage_type" type="hidden" value="{{ route("uniqueCoverageType") }}"/>
    <input id="action_save_coverage" type="hidden" value="{{ route("saveCoverage") }}"/>
    <input id="action_load_coverages" type="hidden" value="{{ route("getListDataCoverage", $plan->id) }}"/>
    <input id="action_deleted_coverage" type="hidden" value="{{ route("deletedCoverage") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/coverage/index.js")}}"></script>
@endsection