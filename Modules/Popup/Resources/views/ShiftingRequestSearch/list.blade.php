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
						@if(isset($list) && !empty($list))
							@foreach($list as $val)
							<tr class="tr-table">
								<td class="text-left DSP_move_no">
									{{$val['move_no']}}
								</td>

								<td class="text-left DSP_manufacturing_instruction_number">
									{{$val['manufacture_no']}}
								</td>

								<td class="text-center DSP_cre_datetime">
									{{$val['cre_datetime']}}
								</td>

								<td class="text-center DSP_move_preferred_date">
									{{$val['move_preferred_date']}}
								</td>

								<td class="text-right DSP_item_cd">
									{{$val['item_cd']}}
								</td>

								<td class="text-left max-width50 DSP_item_nm" style="max-width: 100px;">
									<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$val['item_nm_j']}}">
										{{$val['item_nm_j']}}
									</div>
								</td>

								<td class="text-left max-width50 DSP_spectification" style="max-width: 100px;">
									<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$val['specification']}}">
										{{$val['specification']}}
									</div>
								</td>

								<td class="text-right DSP_move_qty">
									{{$val['move_qty']}}
								</td>

								<td class="text-left DSP_status">
									{{$val['move_status_name']}}
								</td>
							</tr>
							@endforeach
						@else
							<tr>
								<td colspan="9" class="text-center dataTables_empty">&nbsp;</td>
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