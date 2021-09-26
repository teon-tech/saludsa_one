@section('content')
    @include('partials.admin_view',[
    'title'=>'Listado de suscripciones',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'subscription_table',
    ])

    <input id="action_load_subscriptions" type="hidden" value="{{ route("listDataSubscription") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/subscription/index.js")}}"></script>
@endsection