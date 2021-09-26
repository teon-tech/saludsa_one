@section('content')
    @include('partials.admin_view',[
    'title'=>'Listado de Pedidos',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'model_table',
    'action_buttons'=>[

        ]
    ])
    @include('partials.modal',[
    'title'=>'Crear Empresa',
    'id'=>'modal',
    'size'=>'modal-lg',
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

    <input id="action_get_form" type="hidden" value="{{ route('getFormOrder') }}"/>
    <input id="action_load_models" type="hidden" value="{{ route('getListOrder') }}"/>
    <input id="action_save_model" type="hidden" value="{{ route('saveOrder') }}"/>
    <!--BEGIN FORM WITH FILTERS TO EXPORT-->
    <form id="form_export" class="hide" method="get">
        <input type="hidden" name="eventId" id="inputEventId">
    </form>
    <!--END FOR WITH FILTERS TO EXPORT-->
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/order/index.js")}}"></script>
@endsection