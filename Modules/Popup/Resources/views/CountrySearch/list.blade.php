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
							<th class="text-center">コード</th>
							<th class="text-center">名称</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($dataCountry) && !empty($dataCountry))
							@foreach($dataCountry as $country)
							<tr data-lib-cd 	  ="{{$country['lib_cd'] or ''}}"
								data-lib-val-cd   ="{{$country['lib_val_cd'] or ''}}"
								data-lib-val-nm-j ="{{$country['lib_val_nm_j'] or ''}}" class="tr-table">
								<td class="text-center country_cd">{{$country['lib_val_cd'] or ''}}</td>
								<td class="text-overfollow country_nm">{{$country['lib_val_nm_j'] or ''}}</td>
							</tr>
							@endforeach
						@else
							<tr>
								<td colspan="2" class="text-center dataTables_empty">&nbsp;</td>
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