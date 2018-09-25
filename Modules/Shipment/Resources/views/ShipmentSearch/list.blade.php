<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive table-custom">
				<table class="table table-hover table-bordered table-xxs table-list table-shipment tablesorter" id="table-shipment">
					<thead>
					<tr class="col-table-header text-center">
						<th class="col-1 text-center check-box" width="1%">
							<input type="checkbox" id="check-all" name="">
						</th>
						<th class="text-center" width="7%">入金NO</th>
						<!-- <th class="text-center" width="4%">引渡予定日</th> -->
						<th class="text-center" width="7%">出荷指示NO</th>
						<th class="text-center" width="4%">行番号</th>
						<th class="text-center" width="4%">出荷区分</th>
						<th class="text-center" width="12%">取引先名</th>
						<th class="text-center" width="6%">仕向地国名</th>
						<th class="text-center" width="7%">仕向地都市名</th>
						<th class="text-center" width="10%">製品名</th>
						<th class="text-center" width="4%">数量</th>
						<th class="text-center" width="5%">数量単位</th>
						<th class="text-center" width="3%">Gross重量</th>
						<th class="text-center" width="8%">ステータス</th>
					</tr>
					</thead>
					<tbody>
						@if(isset($List) && !empty($List))
							@foreach($List as $val)
						<tr>
							<td class="col-1 text-center">
								<input type="checkbox" name="" class="check-all">
							</td>
							<td class="text-left DSP_deposit_no">{{ $val['deposit_no'] or '' }}</td>
							<!-- <td class="text-left">yyyy/mm/dd</td> -->
							<td class="text-left DSP_fwd_no">{{ $val['fwd_no'] or '' }}</td>
							<td class="text-right">{{ $val['fwd_detail_no'] or '' }}</td>
							<td class="text-left">{{ $val['forwarding_div'] or '' }}</td>
							<td class="text-left max-width50">
								<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{ $val['client_nm'] or '' }}">{{ $val['client_nm'] or '' }}</div>
							</td>
							<td class="text-left max-width50">
								<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{ $val['dest_country_nm'] or '' }}">{{ $val['dest_country_nm'] or '' }}</div>
							</td>
							<td class="text-left max-width50">
								<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{ $val['dest_city_nm'] or '' }}">{{ $val['dest_city_nm'] or '' }}</div>
							</td>
							<td class="text-left max-width50">
								<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{ $val['item_nm'] or '' }}">{{ $val['item_nm'] or '' }}</div>
							</td>
							<td class="text-right">{{ $val['qty'] or '' }}</td>
							<td class="text-left">{{ $val['unit_q_div'] or '' }}</td>
							<td class="text-right">{{ $val['gross_weight'] or '' }}</td>
							<td class="text-left">{{ $val['fwd_status_div'] or '' }}</td>
						</tr>
						@endforeach
						@else
							<tr>
								<td colspan="13" class="text-center dataTables_empty">&nbsp;</td>
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