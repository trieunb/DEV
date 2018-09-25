<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive table-custom ">
				<table class="table table-hover table-bordered table-xxs table-shifting table-list tablesorter" id="table-shifting">
					<thead>
					<tr class="col-table-header text-center">
						<th class="col-1 text-center check-box" width="1%">
							<input type="checkbox" id="check-all" name="">
						</th>
						<th class="text-center" width="5%">移動依頼票No</th>
						<th class="text-center" width="7%">製造指示書番号</th>
						<th class="text-center th-date">登録日</th>
						<th class="text-center th-date">移動希望日</th>
						<th class="text-center" width="6%">品目コード</th>
						<th class="text-center" width="16%">品目名</th>
						<th class="text-center" width="10%">規格</th>
						<th class="text-center" width="6%">移動依頼数</th>
						<th class="text-center" width="5%">ステータス</th>
					</tr>
					</thead>
					<tbody>
					@if( isset($mShiftingRequestList) && !empty($mShiftingRequestList) )
						@foreach($mShiftingRequestList as $datalist)
						<tr>
							<!-- none display section -->
							<input type="hidden" id="move_no_hidden" value="{{ $datalist['move_no'] or '' }}">
							<input type="hidden" id="move_status_hidden" value="{{ $datalist['move_status'] or '' }}">
							<input type="hidden" id="move_detail_no_hidden" value="{{ $datalist['move_detail_no'] or '' }}">
							<input type="hidden" id="out_warehouse_div_hidden" value="{{ $datalist['out_warehouse_div'] or '' }}">
							<input type="hidden" id="in_warehouse_div_hidden" value="{{ $datalist['in_warehouse_div'] or '' }}">
							<input type="hidden" id="item_cd_hidden" value="{{ $datalist['item_cd'] or '' }}">
							<input type="hidden" id="move_qty_hidden" value="{{ $datalist['move_qty'] or '' }}">
							<input type="hidden" id="detail_remarks_hidden" value="{{ $datalist['detail_remarks'] or '' }}">
							<input type="hidden" id="stock_available_qty_hidden" value="{{ $datalist['stock_available_qty'] or '' }}">

							<!-- display section -->
							<td class="col-1 text-center">
								<input type="checkbox" name="" class="check-all">
							</td>

							<td class="text-left DSP_move_no">
								{{$datalist['move_no']}}
							</td>

							<td class="text-left DSP_manufacturing_instruction_number">
								{{$datalist['manufacture_no']}}
							</td>

							<td class="text-center DSP_cre_datetime">
								{{$datalist['cre_datetime']}}
							</td>

							<td class="text-center DSP_move_preferred_date">
								{{$datalist['move_preferred_date']}}
							</td>

							<td class="text-right DSP_item_cd">
								{{$datalist['item_cd']}}
							</td>

							<td class="text-left max-width50 DSP_item_nm">
								<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$datalist['item_nm_j']}}">
									{{$datalist['item_nm_j']}}
								</div>
							</td>

							<td class="text-left max-width50 DSP_spectification">
								<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$datalist['specification']}}">
									{{$datalist['specification']}}
								</div>
							</td>

							<td class="text-right DSP_move_qty">
								{{$datalist['move_qty']}}
							</td>

							<td class="text-left DSP_status">
								{{$datalist['move_status_name']}}
							</td>
						</tr>						
						@endforeach
					@else
						<tr>
							<td colspan="10" class="text-center dataTables_empty">&nbsp;</td>
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