@section('additional-styles')
    <link href="{{asset('nifty/plugins/dropzone/dropzone.css')}}" rel="stylesheet">
@endsection
@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de productos',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'model_table',
    'action_buttons'=>[
        [
        'label'=>'Crear producto',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newModel()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear Empresa',
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
    <input id="action_get_form" type="hidden" value="{{ action("ProductController@getForm") }}"/>
    <input id="action_unique_code" type="hidden" value="{{ action("ProductController@postIsNameUnique") }}"/>
    <input id="action_save_model" type="hidden" value="{{ action("ProductController@postSave") }}"/>
    <input id="action_load_models" type="hidden"
           value="{{ action("ProductController@getList") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("nifty/plugins/dropzone/dropzone.js")}}"></script>
    <script src="{{asset("js/app/product/index.js")}}"></script>
@endsection