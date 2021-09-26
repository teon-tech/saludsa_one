@section('content')
    <div id="wrapper_zone" class="hide" style="margin-top: -75px ; important!">
        <!--begin::Card-->
			<div class="card card-custom">
				<div class="card-header">
					<div class="card-title">
						<span class="card-icon">
							<i class="flaticon2-favourite text-primary"></i>
						</span>
						<h3 class="card-label">Mensajes por familia de plan</h3>
					</div>
					<div class="card-toolbar">
                        <a class="btn btn-primary mr-3" href="#" onclick="saveMessage()">Guardar Cambios</a><br>	
					</div>
				</div>
				<div class="card-body">
                    {!! Form::model($typePlan, array('id' => 'messages_type','class' => 'form-horizontal', 'method' => $method)) !!}

                    <table class="table table-bordered ">
                            <thead>
                            <tr>
                                <th></th>
                                @foreach($typePlan as $type)
                                    <th>{{$type->name}}</th>
                                @endforeach
                            </tr>
                            </thead>
                    <tbody>
                        @foreach($typePlan as $type)
                            <tr>
                                <th>{{$type->name}}</th>
                                @foreach($typePlan as $type1)
                                    <td>
                                        <table style="width: 100%;" class="table-bordered">
                                            <textarea style="font-size: 11px; border-radius: 5px" name="message[{{$type->id}}][{{$type1->id}}]" >
                                                {{$messages->getMessage($type->id,$type1->id)}}
                                            </textarea>   
                                        </table>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                     </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        @foreach($typePlan as $type)
                            <th>{{$type->name}}</th>
                        @endforeach
                    </tr>
                    </tfoot>
                        </table>

                        {!! Form::close() !!}
				</div>
			</div>
			<!--end::Card-->
    </div>
    <input id="action_save" type="hidden" value="{{ route('saveMessage') }}"/>

@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/message/index.js")}}"></script>
@endsection