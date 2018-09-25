<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive table-custom sticky-table sticky-headers sticky-ltr-cells">
				<table class="table table-hover table-striped table-bordered table-xxs table-listtable-striped table-component-order tablesorter" id="table-component-order">
					<thead>
					<tr class="col-table-header text-center sticky-row">
						<th class="col-1 text-center sticky-cell th-first" width="1.5%">
							<input type="checkbox" id="check-all" name="">
						</th>
						<!-- parts_order_date -->
						<th class="text-center th-date sticky-cell th-first">発注日</th>
						<!-- parts_order_no -->
						<th class="text-center sticky-cell th-last" width="8%">注文番号</th>
						<!-- parts_order_detail_no -->
						<th class="text-center" width="5%">行番号</th>
						<!-- status -->
						<th class="text-center" width="5%">ステータス</th>
						<!-- supplier_cd -->
						<th class="text-center" width="8%">仕入先コード</th>
						<!-- supplier_nm -->
						<th class="text-center" width="10%">仕入先名</th>
						<!-- hope_delivery_date -->
						<th class="text-center th-date">希望納期</th>
						<!-- item_nm_j -->
						<th class="text-center" width="12%">品名</th>
						<!-- specification -->
						<th class="text-center" width="10%">規格</th>
						<!-- parts_order_qty -->
						<th class="text-center" width="5%">数量</th>
						<!-- unit_qty_div -->
						<th class="text-center" width="5%">単位</th>
						<!-- parts_order_unit_price -->
						<th class="text-center" width="8%">単価</th>
						<!-- parts_order_amt -->
						<th class="text-center" width="10%">金額</th>
						<!-- detail_remarks -->
						<th class="text-center" width="15%">備考</th>
						<!-- 購入依頼書番号 -->
						<th class="text-center" width="10%">購入依頼書番号</th>
						<th class="text-center" width="10%">社内発注書番号</th>
						<th class="text-center" width="10%">製造指示書番号</th>
					</tr>
					</thead>
					<tbody class="results">
						@if(isset($orderList) && !empty($orderList))
							@foreach($orderList as $order)
								<tr class="tr-table">
									<td class="col-1 text-center sticky-cell th-first">
										<input type="checkbox" name="" class="check-all">
										<span class="hidden DSP_status">{{$order['parts_order_status_div'] or ''}}</span>
									</td>

									<!-- parts_order_date -->
									<td class="text-center sticky-cell th-first">{{$order['parts_order_date'] or ''}}</td>

									<!-- parts_order_no -->
									<td class="text-left parts_order_no sticky-cell th-last">{{$order['parts_order_no'] or ''}}</td>

									<!-- parts_order_detail_no -->
									<td class="text-right">{{$order['parts_order_detail_no'] or ''}}</td>

									<!-- status -->
									<td class="text-left">{{$order['parts_order_status_div_nm'] or ''}}</td>

									<!-- supplier_cd -->
									<td class="text-left">{{$order['supplier_cd'] or ''}}</td>

									<!-- supplier_nm -->
									<td class="text-left min-width225">
										<div class="tooltip-overflow max-width20" title="{{$order['supplier_nm'] or ''}}" style="max-width: 20px;" data-toggle="tooltip" data-placement="top">
											{{$order['supplier_nm'] or ''}}
										</div>
									</td>

									<!-- hope_delivery_date -->
									<td class="text-center">{{$order['hope_delivery_date'] or ''}}</td>

									<!-- item_nm_j -->
									<td class="text-left min-width225">
										<div class="tooltip-overflow max-width20" title="{{$order['item_nm_j'] or ''}}" style="max-width: 20px;" data-toggle="tooltip" data-placement="top">
											{{$order['item_nm_j'] or ''}}
										</div>
									</td>

									<!-- specification -->
									<td class="text-left min-width120">
										<div class="tooltip-overflow max-width20" title="{{$order['specification'] or ''}}" style="max-width: 20px;" data-toggle="tooltip" data-placement="top">
											{{$order['specification'] or ''}}
										</div>
									</td>

									<!-- parts_order_qty -->
									<td class="text-right">{{$order['parts_order_qty'] or ''}}</td>

									<!-- unit_qty_div -->
									<td class="text-left min-width50">{{$order['unit_qty_div'] or ''}}</td>

									<!-- parts_order_unit_price -->
									<td class="text-right">
										{{$order['parts_order_unit_price'] or ''}}
										<span class="hidden">{{$order['parts_order_unit_price'] or ''}}</span>
									</td>

									<!-- parts_order_amt -->
									<td class="text-right">
										{{$order['parts_order_amt'] or ''}}
										<span class="hidden">{{$order['parts_order_amt'] or ''}}</span>
									</td>

									<!-- detail_remarks -->
									<td class="text-left min-width165">
										<div class="tooltip-overflow max-width20" title="{{$order['detail_remarks'] or ''}}" style="max-width: 20px;" data-toggle="tooltip" data-placement="top">
											{{$order['detail_remarks'] or ''}}
										</div>
									</td>
									<!-- 購入依頼書番号 -->
									<td class="text-left">
										{{$order['buy_no'] or ''}}
									</td>
									<!-- 社内発注書番号 -->
									<td class="text-left">
										{{$order['in_order_no'] or ''}}
									</td>
									<!-- 製造指示書番号 -->
									<td class="text-left">
										{{$order['manufacture_no'] or ''}}
									</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="18" class="text-center dataTables_empty">&nbsp;</td>
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