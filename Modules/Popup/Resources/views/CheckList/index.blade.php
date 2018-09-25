@extends('layouts.popup')

@section('title')
	確認事項
@endsection

@section('stylesheet')
	{!! public_url('modules/popup/css/check_list.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/popup/js/check_list.js')!!}
@endsection

@section('content')

	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">確認事項</h5>
			</div>
			<div class="panel-body search-condition">
				<div class="table-responsive table-custom">
				<table class="table table-hover table-bordered table-xxs table-list table-checklist" id="table-checklist">
					<thead>
					<tr class="col-table-header text-center">
						<th class="text-center" width="5%">No</th>
						<th class="text-center" width="10%">コード</th>
						<th class="text-center" width="85%">確認事項</th>
					</tr>
					</thead>
					<tbody>
						@if(isset($checklist) && !empty($checklist))
							@for ($i = 1; $i <= count($checklist); $i++)
							<tr>
								<td class="text-left shipment_id text-center">{{$i or ''}}</td>
								<td class="text-left">{{$checklist[$i-1]['lib_val_cd'] or ''}}</td>
								<td class="text-left">{{$checklist[$i-1]['lib_val_nm_j'] or ''}}</td>
							</tr>
							@endfor
						@endif
					</tbody>
				</table>
			</div>
			</div>
		</div>
		<!-- /search field -->
	</div>

@endsection