<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive table-custom sticky-table sticky-headers sticky-ltr-cells">
				<table class="table table-hover table-bordered table-xxs table-list table-striped table-product" id="table-product">
					<thead>
					<tr class="col-table-header text-center sticky-row">
						<th class="text-center sticky-cell th-first" width="5%">製品コード</th>
						<th class="text-center sticky-cell th-last" style="padding:0 37px" width="10%">製品名和文</th>
						<th class="text-center" width="10%" style="padding:0 37px">製品名英文</th>
						<th class="text-center" width="10%" style="padding:0 50px">規格名</th>
						<th class="text-center" width="5%"  style="padding:0 20px">単位</th>
						<th class="text-center" width="5%">内製／外注</th>
						<th class="text-center" width="5%">在庫管理有無</th>
						<th class="text-center" width="6%">最終シリアル番号</th>
						<th class="text-center" width="4%" style="padding:0 37px">JANコード</th>
						<th class="text-center" width="5%">Net Weight</th>
						<th class="text-center" width="4%">NW単位</th>
						<th class="text-center" width="5%">Gross Weight</th>
						<th class="text-center" width="3%">GW単位</th>
						<th class="text-center" width="5%">Measurement</th>
						<th class="text-center" width="6%">Measurement単位</th>
						<th class="text-center" width="10%"  style="padding:0 50px">備考</th>
					</tr>
					</thead>
					<tbody  class="results">
						@if(isset($productList) && !empty($productList))
							@foreach($productList as $row)
								<tr class="tr-table">
									<td class="text-left sticky-cell th-first DSP_product_cd">{{$row['product_cd']}}</td>
									<td class="text-left sticky-cell th-last DSP_product_nm_j">
										<div class="tooltip-overflow max-width225" data-toggle="tooltip" data-placement="top" title="{{$row['item_nm_j']}}">{{$row['item_nm_j']}}</div>
									</td>
									<td class="text-left DSP_product_nm_e">
										<div class="tooltip-overflow max-width225" data-toggle="tooltip" data-placement="top" title="{{$row['item_nm_e']}}">{{$row['item_nm_e']}}</div>
									</td>
									<td class="text-left DSP_specification">
										<div class="tooltip-overflow max-width225" data-toggle="tooltip" data-placement="top" title="{{$row['specification']}}">{{$row['specification']}}</div>
									</td>
									<td class="text-left DSP_unit">{{$row['unit_qty_div_nm_j']}}</td>
									<td class="text-left DSP_internal_manufacturing_outsource">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$row['outsourcing_div_nm_j']}}">{{$row['outsourcing_div_nm_j']}}</div>
									</td>
									<td class="text-left DSP_inventory_control">{{$row['stock_management_div_nm_j']}}</td>
									<td class="text-left DSP_last_serial_no">{{$row['last_serial_no']}}</td>
									<td class="text-left DSP_jan_code">
										<div class="tooltip-overflow max-width120" data-toggle="tooltip" data-placement="top" title="{{$row['jan_code']}}">{{$row['jan_code']}}</div>
									</td>
									<td class="text-right DSP_net_weight">{{$row['net_weight']}}</td>
									<td class="text-left DSP_nw_unit">{{$row['unit_net_weight_div_nm_j']}}</td>
									<td class="text-right DSP_gross_weight">{{$row['gross_weight']}}</td>
									<td class="text-left DSP_gw_unit">{{$row['unit_gross_weight_div_nm_j']}}</td>
									<td class="text-right DSP_measurement">{{$row['measure']}}</td>
									<td class="text-left DSP_measurement_unit">{{$row['unit_measure_div_nm_j']}}</td>
									<td class="text-left DSP_remarks">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$row['remarks']}}">{{$row['remarks']}}</div>
									</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="16" class="text-center dataTables_empty">&nbsp;</td>
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