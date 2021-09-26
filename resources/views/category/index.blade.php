@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de categorías',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'category_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newCategory()',
        'color' => 'btn-primary'
        ],
      ]
    ])

    @include('partials.modal',array(
    'title'=>'Crear Unidad de medida',
    'id' => 'category_modal',
    'action_buttons'=>array(
        array(
        'type'=>'submit',
        'form'=>'category_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ),
     )
    ))

    <input id="action_get_form" type="hidden" value="{{ route("getFormCategory") }}"/>
    <input id="action_unique_name" type="hidden" value="{{ route("uniqueNameCategory") }}"/>
    <input id="action_save_category" type="hidden" value="{{ route("saveCategory") }}"/>
    <input id="action_load_categorys" type="hidden" value="{{ route("getListDataCategory") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/category/index.js")}}"></script>
@endsection