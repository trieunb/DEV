<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive table-custom ">
				<table class="table table-hover table-bordered table-xxs table-list table-puschase-request tablesorter" id="table-puschase-request">
					<thead>
					<tr class="col-table-header text-center">
						<th class="text-center th-date">依頼日</th>
						<th class="text-center" width="8%">注文番号</th>
						<th class="text-center" width="5%">行番号</th>
						<th class="text-center" width="5%">ステータス</th>
						<th class="text-center" width="8%">発注先コード</th>
						<th class="text-center" width="9%">発注先名</th>
						<th class="text-center th-date">発注日</th>
						<th class="text-center" width="4%">コード</th>
						<th class="text-center" width="9%">品名</th>
						<th class="text-center" width="7%">規格</th>
						<th class="text-center" width="4%">数量</th>
						<th class="text-center" width="5%">単位</th>
						<th class="text-center" width="5%">単価</th>
						<th class="text-center" width="5%">金額</th>
						<th class="text-center" width="9%">備考</th>
					</tr>
					</thead>
					<tbody>
						@if(isset($List) && !empty($List))
						@foreach($List as $val)
						<tr>
							<td class="text-center">{{$val['buy_date'] or ''}}</td>
							<td class="text-left purchaserequest_cd">{{$val['buy_no'] or ''}}</td>
							<td class="text-right">{{$val['buy_detail_no'] or ''}}</td>
							<td class="text-left">
								{{$val['buy_status'] or ''}} 
								<span class="hidden DSP_buy_status_div">{{$val['buy_status_div'] or ''}}</span>
							</td>
							<td class="text-left">{{$val['supplier_cd'] or ''}}</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" style="max-width: 20px;" data-toggle="tooltip" data-placement="top" title="{{$val['supplier_nm'] or ''}}">{{$val['supplier_nm'] or ''}}</div>
							</td>
							<td class="text-center">{{$val['parts_order_date'] or ''}}</td>
							<td class="text-right">{{$val['parts_cd'] or ''}}</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" style="max-width: 20px;" data-toggle="tooltip" data-placement="top" title="{{$val['parts_nm'] or ''}}">{{$val['parts_nm'] or ''}}</div>
							</td>
							<td class="text-left">{{$val['specification'] or ''}}</td>
							<td class="text-right">{{$val['buy_qty'] or ''}}</td>
							<td class="text-left">{{$val['buy_unit_nm'] or ''}}</td>
							<td class="text-right">{{$val['qty_unit_price'] or ''}}</td>
							<td class="text-right">{{$val['buy_detail_amt'] or ''}}</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" style="max-width: 20px;" data-toggle="tooltip" data-placement="top" title="{{$val['detail_remarks'] or ''}}">{{$val['detail_remarks'] or ''}}</div>
							</td>
						</tr>
						@endforeach
					@else
						<tr>
							<td colspan="15" class="text-center dataTables_empty">&nbsp;</td>
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