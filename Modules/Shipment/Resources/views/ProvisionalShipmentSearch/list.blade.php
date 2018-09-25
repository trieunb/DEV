<div id="result" class="panel panel-flat">
	<div class="panel-heading">
		{{-- {{ PaggingHelper::show() }} --}}
	</div>
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
						<th class="text-center" width="7%">入金No</th>
						<th class="text-center" width="8%">仮出荷指示No</th>
						<th class="text-center" width="2%">行番号</th>
						<th class="text-center" width="4%">出荷区分</th>
						<th class="text-center" width="10%">取引先名</th>
						<th class="text-center" width="6%">仕向地国名</th>
						<th class="text-center" width="7%">仕向地都市名</th>
						<th class="text-center" width="14%">製品名</th>
						<th class="text-center" width="4%">数量</th>
						<th class="text-center" width="5%">数量単位</th>
						<th class="text-center" width="3%">Gross重量</th>
					</tr>
					</thead>
					<tbody>
						@if(isset($shipmentList) && !empty($shipmentList))
							@foreach($shipmentList as $row)
							<tr class="tr-class">
								<td class="col-1 text-center">
									<input type="checkbox" name="" class="check-all CHK_Select">
								</td>
								<td class="text-left DSP_deposit_no">{{ $row['deposit_no'] or ''}}</td>
								<!-- <td class="text-left">yyyy/mm/dd</td> -->
								<td class="text-left DSP_fwd_no">{{ $row['fwd_no'] or ''}}</td>
								<td class="text-left DSP_fwd_detail_no">{{ $row['fwd_detail_no'] or ''}}</td>
								<td class="text-left DSP_forwarding_div">{{ $row['lib_val_nm_j_forwarding_div'] or ''}}</td>
								<td class="text-left max-width50 DSP_client_nm">
									<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{ $row['cust_nm'] or ''}}">{{ $row['cust_nm'] or ''}}</div>
								</td>
								<td class="text-left max-width50 DSP_dest_country_div">
									<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{ $row['lib_val_nm_j_country'] or ''}}">{{ $row['lib_val_nm_j_country'] or ''}}</div>
								</td>
								<td class="text-left max-width50 DSP_dest_city_div">
									<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{ $row['lib_val_nm_j_city'] or ''}}">{{ $row['lib_val_nm_j_city'] or ''}}</div>
								</td>
								<td class="text-left max-width50 DSP_item_nm_j">
									<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{ $row['item_nm_j'] or ''}}">{{ $row['item_nm_j'] or ''}}</div>
								</td>
								<td class="text-right DSP_qty">
									<span class="hidden">{{ $row['fwd_qty'] or ''}}</span>
									{{ $row['fwd_qty'] or ''}}
								</td> 
								<td class="text-left DSP_unit_q_div">{{ $row['lib_val_nm_j_unit_q_div'] or ''}}</td>
								<td class="text-right DSP_gross_weight">
									<span class="hidden">{{ $row['gross_weight'] or ''}}</span>
									{{ $row['gross_weight'] or ''}}
								</td>
							</tr>
							@endforeach
						@else
							<tr>
								<td colspan="12" class="text-center dataTables_empty">&nbsp;</td>
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