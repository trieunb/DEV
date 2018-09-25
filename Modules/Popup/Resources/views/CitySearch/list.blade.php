<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive">
				<table class="table table-hover table-bordered table-xxs table-text table-popup tablesorter" id="table-popup">
					<thead>
						<tr class="col-table-header">
							<th class="text-center city_cd">コード</th>
							<th class="text-center">名称</th>
							<th class="text-center">国コード</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($dataCity) && !empty($dataCity))
							@foreach($dataCity as $city)
							<tr class ="tr-table">
								<td class="text-center city_cd">{{$city['lib_val_cd'] or ''}}</td>
								<td class="text-overfollow city_nm">{{$city['lib_val_nm_j'] or ''}}</td>
								<td class="text-overfollow lib_val_ctl1">{{$city['lib_val_ctl1'] or ''}}</td>
							</tr>
							@endforeach
						@else
							<tr>
								<td colspan="3" class="text-center dataTables_empty">&nbsp;</td>
							</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
		<div class="nav-pagination">
			{!! $paginate !!}
		</div>
	</div>
</div>