@section('content')
    @include('partials.admin_view',[
    'title'=>'Bandeja de ventas',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'model_table',
    'action_buttons'=>[
      ]
    ])
    @include('partials.modal',[
    'title'=>'Detalles',
    'id'=>'model_modal',
    'size'=>'modal-xl',
    'action_buttons'=>[
      [
        'type'=>'submit',
        'form'=>'model_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])
    @include('partials.modal',[
      'title'=>'Servicio de Ventas',
      'id'=>'modal_sale_service',
      'size'=>'modal-xl',
      'action_buttons'=>[
       ]
      ])

  @include('partials.modal',[
  'title'=>'Información servicio',
  'id'=>'modal_info_service',
  'size'=>'modal-lg',
  'action_buttons'=>[
   ]
  ])

    <input id="action_get_form" type="hidden" value="{{ route("getFormSaleTray") }}"/>
    <input id="action_get_form_service" type="hidden" value="{{ route("getFormSaleService") }}"/>
    <input id="action_get_form_info_service" type="hidden" value="{{ route("getInfoService") }}"/>
    <input id="action_load_models" type="hidden" value="{{ route("getListDataSaleTray") }}"/>
    <input id="action_retry_service" type="hidden" value="{{ route("retryService") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/saleTray/index.js")}}"></script>
@endsection