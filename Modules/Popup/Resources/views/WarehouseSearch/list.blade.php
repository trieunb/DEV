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
						@if(isset($dataWarehouse) && !empty($dataWarehouse))
							@foreach($dataWarehouse as $warehouse)
							<tr data-lib-cd 	  ="{{$warehouse['lib_cd'] or ''}}"
								data-lib-val-cd   ="{{$warehouse['lib_val_cd'] or ''}}"
								data-lib-val-nm-j ="{{$warehouse['lib_val_nm_j'] or ''}}"  class="tr-tbl">
								<td class="text-left warehouse_id" width="35%">{{$warehouse['lib_val_cd'] or ''}}</td>
								<td class="text-overfollow warehouse_nm">{{$warehouse['lib_val_nm_j'] or ''}}</td>
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