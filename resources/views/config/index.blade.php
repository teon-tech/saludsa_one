@section('additional-styles')
    <link href="{{asset('nifty/plugins/dropzone/dropzone.css')}}" rel="stylesheet">
@endsection
@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de configuración de contrato',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'model_table',
    'action_buttons'=>[
        
      ]
    ])
    @include('partials.modal',[
    'title'=>'Actualizar Rango de Contratos',
    'id'=>'modal',
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
    <input id="action_get_form" type="hidden" value="{{ route("getFormConfig") }}"/>
    <input id="action_save_model" type="hidden" value="{{ route("saveConfig") }}"/>
    <input id="action_load_models" type="hidden" value="{{ route("getListDataConfig") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/config/index.js")}}"></script>
@endsection