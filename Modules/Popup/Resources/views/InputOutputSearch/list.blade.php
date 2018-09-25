<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive table-custom ">
				<table class="table table-hover table-bordered table-xxs table-list table-stock-manager tablesorter" id="table-stock-manager">
					<thead>
					<tr class="col-table-header">
						<th class="text-center" width="8%">入出庫No</th>
						<th class="text-center" width="8%">入出庫区分</th>
						<th class="text-center th-date">入出庫日</th>
						<th class="text-center" width="5%">入力種別</th>
						<th class="text-center" width="5%">倉庫コード</th>
						<th class="text-center" width="15%">倉庫名</th>
						<th class="text-center" width="8%">品目コード</th>
						<th class="text-center" width="15%">品目名</th>
						<th class="text-center" width="10%">規格</th>
						<th class="text-center" width="5%">数量</th>
						<th class="text-center">摘要</th>
					</tr>
					</thead>
					<tbody>
						@if(isset($Lists) && !empty($Lists))
							@foreach($Lists as $list)
						<tr>
							<td class="text-left DSP_in_out_no inputoutput_cd">{{ $list['in_out_no'] or '' }}</td>
							<td class="text-left">{{ $list['in_out_nm'] or '' }}</td>
							<td class="text-center">{{ $list['in_out_date'] or '' }}</td>
							<td class="text-left">{{ $list['in_out_data_nm'] or '' }}</td>
							<td class="text-left">{{ $list['warehouse_div'] or '' }}</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{ $list['warehouse_nm'] or '' }}">{{ $list['warehouse_nm'] or '' }}</div>
							</td>
							<td class="text-left">{{ $list['item_cd'] or '' }}</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{ $list['item_nm_j'] or '' }}">{{ $list['item_nm_j'] or '' }}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{ $list['specification'] or '' }}">{{ $list['specification'] or '' }}</div>
							</td>
							<td class="text-right">{{ $list['in_out_qty'] or '' }}</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{ $list['detail_remarks'] or '' }}">{{ $list['detail_remarks'] or '' }}</div>
							</td>
						</tr>
						@endforeach
						@else
						<tr>
							<td colspan="11" class="text-center dataTables_empty">&nbsp;</td>
						</tr>
						@endif
					</tbody>
				</table>
			</div>
			<div class="nav-pagination">
				{!! $paginate !!}
			</div>
		</div>
	</div>
</div>