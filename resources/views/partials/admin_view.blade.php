<div class="content d-flex flex-column flex-column-fluid" id="kt_content" style="margin-top: -75px ; important!">
	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid">
		<!--begin::Container-->
		<div class="container">
			<!--begin::Card-->
			<div class="card card-custom">
				<div class="card-header">
					<div class="card-title">
						<span class="card-icon">
							<i class="flaticon2-favourite text-primary"></i>
						</span>
						<h3 class="card-label">{{$title}}</h3>
					</div>
					<div class="card-toolbar">
						@if(isset($action_buttons) && is_array($action_buttons))
                @foreach( $action_buttons as $item_button)
                    <a class="btn {{isset($item_button['color'])? $item_button['color']: 'btn-primary'}} mr-3" href="#" onclick="{{$item_button['handler_js']}}">
                        {!! $item_button['icon'] !!}  {{ $item_button['label'] }}
                    </a>
                @endforeach
            @endif
					</div>
				</div>
				<div class="card-body">
					<!--begin: Datatable-->
					<table class="table table-bordered table-hover table-checkable"  id="{{ isset($id_table) ? $id_table : 'kt_datatable' }}" style="margin-top: 13px !important">
					
					</table>
					<!--end: Datatable-->
				</div>
			</div>
			<!--end::Card-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>
<!--end::Content-->