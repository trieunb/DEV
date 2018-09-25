<div id="result" class="panel panel-flat">
<div class="panel-body">
	<div class="no-padding">
		<div class="nav-pagination">
			{!! $fillter or ''!!}
			{!! $paginate or ''!!}
		</div>
		<div class="table-responsive table-custom ">
			<table class="table table-hover table-bordered table-xxs table-list table-pi tablesorter" id="table-pi">
				<thead>
				<tr class="col-table-header text-center">
					<th class="col-1 text-center check-box" width="1%">
						<input type="checkbox" id="check-all" name="">
					</th>
					<th class="text-center" width="5%">PI No</th>
					<th class="text-center" width="3%">受注 No</th>
					<th class="text-center th-date">見積日</th>
					<th class="text-center" width="5%">取引先コード</th>
					<th class="text-center" width="5%">取引先名</th>
					<th class="text-center" width="2%">国</th>
					<th class="text-center" width="3%">Code</th>
					<th class="text-center" width="7%">Item Name</th>
					<th class="text-center" width="1%">Unit Price</th>
					<th class="text-center" width="1%">Q'ty</th>
					<th class="text-center" width="1%">Cur</th>
					<th class="text-center" width="1%">Amount</th>
					<th class="text-center" width="1%">ステータス</th>
				</tr>
				</thead>
				<tbody>
					@if(isset($piList) && !empty($piList))
						@foreach($piList as $pi)
						<tr>
							<td class="col-1 text-center">
								<input type="checkbox" name="" class="check-all">
								<span class="hidden DSP_pi_status_div">{{$pi['pi_status_div'] or ''}}</span>
							</td>
							<td class="text-left DSP_pi_no">{{$pi['pi_no'] or ''}}</td>
							<td class="text-left DSP_rcv_no">{{$pi['rcv_no'] or ''}}</td>
							<td class="text-center DSP_pi_date">{{$pi['pi_date'] or ''}}</td>
							<td class="text-left DSP_cust_cd">{{$pi['cust_cd'] or ''}}</td>
							<td class="text-left DSP_cust_nm">
								<div class="tooltip-overflow max-width30" data-toggle="tooltip" data-placement="top" title="{{$pi['cust_nm'] or ''}}">{{$pi['cust_nm'] or ''}}</div>
							</td>
							<td class="text-left DSP_cust_country_div">{{$pi['cust_country_div'] or ''}}</td>
							<td class="text-left DSP_product_cd">{{$pi['product_cd'] or ''}}</td>
							<td class="text-left DSP_description">
								<div class="tooltip-overflow max-width30" data-toggle="tooltip" data-placement="top" title="{{$pi['description'] or ''}}">{{$pi['description'] or ''}}</div>
							</td>
							<td class="text-right DSP_unit_price">{{$pi['unit_price'] or ''}}</td>
							<td class="text-right DSP_qty">{{$pi['qty'] or ''}}</td>
							<td class="text-left DSP_currency_div">{{$pi['currency_div'] or ''}}</td>
							<td class="text-right DSP_qty_unit_price">{{$pi['qty_unit_price'] or ''}}</td>
							<td class="text-left DSP_pi_status_nm">{{$pi['pi_status_nm'] or ''}}</td>
						</tr>
						@endforeach
					@else
					<tr>
						<td colspan="14" class="text-center dataTables_empty">&nbsp;</td>
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
