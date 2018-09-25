<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive table-custom sticky-table sticky-headers sticky-ltr-cells">
				<table class="table table-hover table-striped table-bordered table-xxs table-listtable-striped table-stocking-search tablesorter" id="table-stocking-search">
					<thead>
					<tr class="col-table-header text-center">
						<th class="text-center" width="8%">部品発注書番号</th>
						<th class="text-center" width="8%">仕入番号</th>
						<th class="text-center th-date" width="5%">仕入日</th>
						<th class="text-center" width="8%">仕入先コード</th>
						<th class="text-center">仕入先名</th>
						<th class="text-center" width="5%">明細No</th>
						<th class="text-center" width="8%">部品コード</th>
						<th class="text-center">部品名</th>
						<th class="text-center">規格</th>
						<th class="text-center" width="5%">仕入数量</th>
						<th class="text-center" width="8%">仕入単価</th>
						<th class="text-center" width="8%">仕入金額</th>
						<th class="text-center" width="8%">備考</th>
					</tr>
					</thead>
					<tbody class="results">
						@if(isset($stockingList) && !empty($stockingList))
							@foreach($stockingList as $order)
								<tr>
									<td class="text-left DSP_parts_order_no">{{$order['parts_order_no'] or ''}}</td>
									<td class="text-left DSP_purchase_no">{{$order['purchase_no'] or ''}}</td>
									<td class="text-center">{{$order['purchase_date'] or ''}}</td>
									<td class="text-left">{{$order['supplier_cd'] or ''}}</td>
									<!-- supplier_nm -->
									<td class="text-left min-width120">
										<div class="tooltip-overflow max-width20" title="{{$order['supplier_nm'] or ''}}" style="max-width: 20px;" data-toggle="tooltip" data-placement="top">
											{{$order['supplier_nm'] or ''}}
										</div>
									</td>
									<td class="text-right DSP_purchase_detail_no">{{$order['purchase_detail_no'] or ''}}</td>

									<td class="text-left">{{$order['parts_cd'] or ''}}</td>
									<!-- parts_nm -->
									<td class="text-left min-width120">
										<div class="tooltip-overflow max-width20" title="{{$order['parts_nm'] or ''}}" style="max-width: 20px;" data-toggle="tooltip" data-placement="top">
											{{$order['parts_nm'] or ''}}
										</div>
									</td>
									<!-- specification -->
									<td class="text-left min-width120">
										<div class="tooltip-overflow max-width20" title="{{$order['specification'] or ''}}" style="max-width: 20px;" data-toggle="tooltip" data-placement="top">
											{{$order['specification'] or ''}}
										</div>
									</td>
									<!-- purchase_qty -->
									<td class="text-right">{{$order['purchase_qty'] or ''}}</td>

									<!-- purchase_unit_price -->
									<td class="text-right">{{$order['purchase_unit_price'] or ''}}</td>

									<!-- purchase_amt -->
									<td class="text-right">{{$order['purchase_amt'] or ''}}</td>

									<td class="text-left">
										<div class="tooltip-overflow max-width20" title="{{$order['remarks'] or ''}}" style="max-width: 20px;" data-toggle="tooltip" data-placement="top">
											{{$order['remarks'] or ''}}
										</div>
									</td>
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