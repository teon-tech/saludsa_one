@section('additional-styles')
    <link href="{{asset('nifty/plugins/dropzone/dropzone.css')}}" rel="stylesheet">
@endsection
@section('content')
    @include('partials.admin_view', [
      'title'=>'Administración de emprendedores',
      'icon'=>'<i class="flaticon-cogwheel-2"></i>',
      'id_table'=>'provider_table',
      'action_buttons'=>[
          [
          'label'=>'Registrar',
          'icon'=>'<i class="la la-plus"></i>',
          'handler_js'=>'newProvider()',
          'color'=>'btn-primary'
          ],
          
        ]
      ])
    @include('partials.modal', [
       'title' =>'Crear Emprendedor',
       'id' => 'provider_modal',
       'size'=>'modal-lg',
       'action_buttons' => [
           [
           'type'=>'submit',
           'form'=>'provider_form',
           'id'=>'btn_save',
           'label'=>'Guardar',
           'color'=>'btn-primary'
           ],
        ]
       ])
    <input type="hidden" id="action_get_list_data" value="{{route('getListDataProvider')}}">
    <input type="hidden" id="action_get_form" value="{{route('getFormProvider')}}">
    <input type="hidden" id="action_save" value="{{route('saveProvider')}}">
    <input type="hidden" id="action_unique_code" value="{{route('uniqueCodeProvider')}}">
@endsection
@section('additional-scripts')
    <script src="{{asset("nifty/plugins/dropzone/dropzone.js")}}"></script>
    <script src="{{asset("js/app/provider/index.js")}}"></script>
@endsection
