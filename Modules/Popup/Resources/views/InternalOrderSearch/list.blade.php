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
					<div class="table-responsive table-custom ">
						<table class="table table-hover table-bordered table-xxs table-list table-internal-order tablesorter" id="table-internal-order">
							<thead>
							<tr class="col-table-header text-center">
								<th class="col-1 text-center check-box">
									<input type="checkbox" id="check-all" class="CHK_select" name="CHK_select">
								</th>
								<th class="text-center w-40px DSP_in_order_no" width="10%">社内発注書番号</th>
								<th class="text-center DSP_disp_order">行番号</th>
								<th class="text-center th-date DSP_order_date" width="8%">発注日</th>
								<th class="text-center th-date DSP_production_instruction_date" width="8%">製造指示日</th>
								<th class="text-center th-date DSP_hope_delivery_date" width="8%">希望納期</th>
								<th class="text-center DSP_orderer_cd" width="8%">発注者コード</th>
								<th class="text-center DSP_orderer_nm" width="14%">発注者名</th>
								<th class="text-center DSP_processcing" width="5%">処理</th>
								<th class="text-center DSP_product_cd" width="8%">製品コード</th>
								<th class="text-center DSP_product_nm" width="14%">製品名</th>
								<th class="text-center DSP_in_order_qty" width="5%">発注数量</th>
								<th class="text-center DSP_manufacture_status_div" width="8%">製造指示状況</th>
							</tr>
							</thead>
							<tbody>
							@if( isset($internalList) && !empty($internalList) )
								@foreach($internalList as $datalist)
									<tr>
										<td class="col-1 text-center">
											<input type="checkbox" name="CHK_select" class="check-all CHK_select">
										</td>
										<td class="text-left DSP_in_order_no">{{$datalist['in_order_no']}}</td>
										<td class="text-right DSP_disp_order">{{$datalist['disp_order']}}</td>
										<td class="text-center DSP_order_date" width="8%">{{$datalist['cre_datetime']}}</td>
										<td class="text-center DSP_production_instruction_date" width="8%">{{$datalist['cre_datetime_manufacture']}}</td>
										<td class="text-center DSP_hope_delivery_date" width="8%">{{$datalist['hope_delivery_date']}}</td>
										<td class="text-left DSP_cre_user_cd">{{$datalist['cre_user_cd']}}</td>
										<td class="DSP_user_nm_j">
											<div class="tooltip-overflow max-width20" style="max-width: 20px;" data-toggle="tooltip" data-placement="top" title="{{$datalist['user_nm_j']}}">{{$datalist['user_nm_j']}}</div>
										</td>
										<td class="text-left DSP_processcing">{{$datalist['manufacture_kind_div_nm']}}</td>
										<td class="text-left DSP_orderer_cd">{{$datalist['product_cd']}}</td>
										<td class="text-left DSP_orderer_nm">
											<div class="tooltip-overflow max-width20" style="max-width: 20px;" data-toggle="tooltip" data-placement="top" title="{{$datalist['item_nm_j']}}">{{$datalist['item_nm_j']}}</div>
										</td>
										<td class="text-right DSP_in_order_qty">
											<span class="hidden">{{$datalist['in_order_qty']}}</span>
											{{$datalist['in_order_qty']}}
										</td>
										<td class="text-left DSP_manufacture_status_div">{{$datalist['manufacture_status_div_nm']}}</td>
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