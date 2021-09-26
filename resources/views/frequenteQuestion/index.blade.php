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
    'title'=>'Administración de preguntas frecuentes',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'question_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newQuestion()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear una pregunta frecuente',
    'id'=>'question_modal',
    'size'=>'modal-lg',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'question_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])

    <input id="action_get_form_question" type="hidden" value="{{ route("getFormQuestion", $plan->id) }}"/>
    <input id="action_save_question" type="hidden" value="{{ route("saveQuestion") }}"/>
    <input id="action_load_questions" type="hidden" value="{{ route("getListDataQuestion", $plan->id) }}"/>
    <input id="action_deleted_question" type="hidden" value="{{ route("deletedQuestion") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/frequentQuestion/index.js")}}"></script>
@endsection