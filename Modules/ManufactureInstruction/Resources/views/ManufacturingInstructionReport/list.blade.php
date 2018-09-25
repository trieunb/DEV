<div id="result" class="panel panel-flat">
			<div class="panel-heading">
			</div>
			<div class="panel-body">
				<div class="no-padding">
					<div class="nav-pagination">
						{!! $fillter !!}
						{!! $paginate !!}
					</div>
					<div class="table-responsive table-custom ">
						<table class="table table-hover table-bordered table-xxs table-list table-internal-order tablesorter" id="table-internal-order">
							<thead>
							<tr class="col-table-header text-center">
								<th class="col-1 text-center check-box" width="2%">
									<input type="checkbox" id="check-all" name="">
								</th>
								<th class="text-center DSP_in_order_no" width="7%">社内発注書番号</th>
								<th class="text-center DSP_disp_order" width="3%">枝番</th>
								<th class="text-center hidden DSP_in_order_detail_no">Hidden</th>
								<th class="text-center DSP_product_cd" width="5%">製品コード</th>
								<th class="text-center DSP_product_nm" width="15%">製品名</th>
								<th class="text-center DSP_hope_delivery_date" width="10%">希望納期</th>
								<th class="text-center DSP_in_order_qty" width="7%">発注数量</th>
								<th class="text-center TXT_manufacture_qty" width="7%">指示数量</th>
								<th class="text-center DSP_remaining_qty" width="5%">残数量</th>
								<th class="text-center DSP_manufacture_kind_div_nm" width="5%">特殊</th>
								<th class="text-center TXT_remarks" width="20%">備考</th>
							</tr>
							</thead>
							<tbody>
							@if( isset($mReportList) && !empty($mReportList) )
								@foreach($mReportList as $datalist)
									<tr>
										<td class="col-1 text-center">
											<input type="checkbox" name="" class="check-all">
										</td>
										<td class="text-left DSP_in_order_no">{{$datalist['in_order_no']}}</td>
										<td class="text-right DSP_disp_order">{{$datalist['disp_order']}}</td>
										<td class="text-right hidden DSP_in_order_detail_no">{{$datalist['in_order_detail_no']}}</td>
										<td class="text-right DSP_product_cd">{{$datalist['product_cd']}}</td>
										<td class="text-right DSP_last_serial_no hidden">{{$datalist['last_serial_no']}}</td>
										<td class="DSP_product_nm">
											<div class="tooltip-overflow max-width30 DSP_product_nm" data-toggle="tooltip" data-placement="top" title="{{$datalist['product_nm']}}">{{$datalist['product_nm']}}</div>
										</td>
										<td class="text-center DSP_hope_delivery_date">{{$datalist['hope_delivery_date']}}</td>
										<td class="text-right DSP_in_order_qty">{{$datalist['in_order_qty']}}</td>
										<td class="text-right">
											<input type="text" class="form-control text-right TXT_manufacture_qty money required numeric" maxlength="8" value="">
										</td>
										<td class="text-right DSP_remaining_qty">{{$datalist['subtract_manufacture_qt']}}</td>
										<input type="hidden" class="DSP_hdn_remaining_qty" value="{{$datalist['subtract_manufacture_qt']}}">

										<td class="text-left DSP_manufacture_kind_div_nm">
											{{$datalist['manufacture_kind_div_nm']}}
										</td>				
										<td class="">
											<input type="text" class="form-control text-left TXT_remarks" value="" data-toggle="tooltip" maxlength="200" data-placement="top">
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
					<div class="nav-pagination">
						{!! $paginate !!}
					</div>
				</div>
			</div>
		</div>