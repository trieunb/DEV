<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div id="table-listing">
			<div class="table-responsive table-custom sticky-table sticky-headers sticky-ltr-cells">
				<table class="table table-hover table-bordered table-xxs table-list table-striped table-component" id="table-component">
					<thead>
						<tr class="col-table-header text-center sticky-row">
							<th class="text-center sticky-cell th-first" width="5%">部品コード</th>
							<th class="text-center sticky-cell th-last" width="7%">部品名和文</th>
							<th class="text-center" width="7%">部品名英文</th>
							<th class="text-center" width="7%">規格名</th>
							<th class="text-center" width="3%">単位</th>
							<th class="text-center" width="3%">入数</th>
							<th class="text-center" width="5%">分類</th>
							<th class="text-center" width="5%">在庫管理有無</th>
							<th class="text-center" width="3%">管理方法</th>
							<th class="text-center" width="3%">発注点</th>
							<th class="text-center" width="3%">EOQ</th>
							<th class="text-center" width="8%">メイン発注先コード</th>
							<th class="text-center" width="8%">メイン発注先名</th>
							<th class="text-center" width="3%">単価(JPY)</th>
							<th class="text-center" width="3%">単価(USD)</th>
							<th class="text-center" width="3%">単価(EUR)</th>
							<th class="text-center" width="6%">発注ロットサイズ</th>
							<th class="text-center" width="6%">下限ロットサイズ</th>
							<th class="text-center" width="6%">上限ロットサイズ</th>
							<th class="text-center" width="10%">備考</th>
						</tr>
					</thead>
					<tbody class="results">
						@if(isset($componentList) && !empty($componentList))
							@foreach($componentList as $row)
								<tr class="tr-table">
									<td class="text-left sticky-cell th-first parts_cd DSP_parts_cd">{{$row['parts_cd']}}</td>
									<td class="text-left sticky-cell th-last DSP_part_nm_j">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$row['item_nm_j']}}">{{$row['item_nm_j']}}</div>
									</td>
									<td class="text-left DSP_part_nm_e">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$row['item_nm_e']}}">{{$row['item_nm_e']}}</div>
									</td>
									<td class="text-left DSP_specification">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$row['specification']}}">{{$row['specification']}}</div>
									</td>
									<td class="text-left DSP_unit">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$row['unit_qty_div_nm_j']}}">{{$row['unit_qty_div_nm_j']}}</div>
									</td>
									<td class="text-right DSP_contained_qty">{{$row['contained_qty']}}</td>
									<td class="text-left DSP_classification">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$row['parts_kind_div_nm_j']}}">{{$row['parts_kind_div_nm_j']}}</div>
									</td>
									<td class="text-left DSP_inventory_control">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$row['stock_management_div_nm_j']}}">{{$row['stock_management_div_nm_j']}}</div>
									</td>
									<td class="text-left DSP_management_method">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$row['parts_order_div_nm_j']}}">{{$row['parts_order_div_nm_j']}}</div>
									</td>
									<td class="text-right DSP_order_point_qty">{{$row['order_point_qty']}}</td>
									<td class="text-right DSP_economic_order_qty">{{$row['economic_order_qty']}}</td>
									<td class="text-left DSP_main_purchaser_order_cd">{{$row['supplier_cd']}}</td>
									<td class="text-left DSP_main_purchaser_order_nm">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$row['client_nm']}}">{{$row['client_nm']}}</div>
									</td>
									<td class="text-right DSP_unit_price_JPY">{{$row['purchase_unit_price_JPY']}}</td>
									<td class="text-right DSP_unit_price_USD">{{$row['purchase_unit_price_USD']}}</td>
									<td class="text-right DSP_unit_price_EUR">{{$row['purchase_unit_price_EUR']}}</td>
									<td class="text-right DSP_order_lot_size">{{$row['order_lot_qty']}}</td>
									<td class="text-right DSP_lower_limit_lot_size">{{$row['lower_limit_lot_qty']}}</td>
									<td class="text-right DSP_maximum_lot_size">{{$row['upper_limit_lot_qty']}}</td>
									<td class="text-left DSP_remarks">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$row['remarks']}}">{{$row['remarks']}}</div>
									</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="20" class="text-center dataTables_empty">&nbsp;</td>
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
</div>