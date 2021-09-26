@section('content')
    @include('partials.admin_view', [
        'title'=>'Administración de Publicidades',
        'icon'=>'<i class="flaticon-cogwheel-2"></i>',
        'id_table'=>'publicity_table',
        'action_buttons'=>[
            [
            'label'=>'Registrar',
            'icon'=>'<i class="la la-plus"></i>',
            'handler_js'=>'newPublicity()',
            'color'=>'btn-primary'
            ],
        ]
    ])
    @include('partials.modal', [
    'title' =>'Crear Publicidades',
    'id' => 'publicity_modal',
    'action_buttons' => [
            [
            'type'=>'submit',
            'form'=>'publicity_form',
            'id'=>'btn_save',
            'label'=>'Guardar',
            'color'=>'btn-primary'
            ],
        ]
    ])
    <input type="hidden" id="action_get_list_data" value="{{route('getListDataPublicity')}}">
    <input type="hidden" id="action_get_form" value="{{route('getFormPublicity')}}">
    <input type="hidden" id="action_save" value="{{route('savePublicity')}}">
@endsection

@section('additional-scripts')
    <script src="{{asset('js/app/publicity/index.js')}}"></script>
@endsection