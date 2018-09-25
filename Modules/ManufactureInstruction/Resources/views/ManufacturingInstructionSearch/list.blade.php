<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div id="table-listing">
			<div class="table-responsive table-custom sticky-table sticky-headers sticky-ltr-cells">
				<table class="table table-hover table-bordered table-xxs table-list table-striped tbl-manufacturing-instruction" id="tbl-manufacturing-instruction">
					<thead>
						<tr class="col-table-header text-center sticky-row">
							<th class="col-1 text-center check-box sticky-cell th-first" width="1%">
								<input type="checkbox" id="check-all" name="">
							</th>
							<th class="text-center sticky-cell th-last" width="5%">製造指示番号</th>
							<th class="text-center" width="7%">社内発注書番号</th>
							<th class="text-center" width="7%">発注日</th>
							<th class="text-center" width="7%">製造指示日</th>
							<th class="text-center" width="3%">希望納期</th>
							<th class="text-center" width="3%">外注状況</th>
							<th class="text-center" width="5%">発注者コード</th>
							<th class="text-center" width="5%">発注者名</th>
							<th class="text-center" width="3%">&nbsp;処理&nbsp;</th>
							<th class="text-center" width="3%">製品コード</th>
							<th class="text-center" width="3%">製品名</th>
							<th class="text-center" width="8%">シリアルFrom</th>
							<th class="text-center" width="8%">シリアルTo</th>
							<th class="text-center" width="3%">製造状況</th>
							<th class="text-center" width="3%">製造完了日</th>
							<th class="text-center" width="3%">出庫元作成日</th>
						</tr>
					</thead>
					<tbody class="results">
						@if( isset($manufacturingInstructionList) && !empty($manufacturingInstructionList) )
							@foreach($manufacturingInstructionList as $data)
								<tr class="tr-class">
									<td class="col-1 text-center sticky-cell th-first">
										<input type="checkbox" name="" class="check-all">
									</td>
									<td class="text-left sticky-cell th-last DSP_manufacture_no">{{$data['manufacture_no']}}</td>
									<td class="text-left DSP_in_order_no">{{$data['in_order_no']}}</td>
									<td class="text-center DSP_internal_ordering_date">{{$data['internal_ordering_date']}}</td>
									<td class="text-center DSP_production_instruction_date">{{$data['production_instruction_date']}}</td>
									<td class="text-center DSP_hope_delivery_date">{{$data['hope_delivery_date']}}</td>
									<td class="text-left DSP_outsourcing_status">{{$data['outsourcing_status']}}</td>
									<td class="text-right DSP_orderer_cd">{{$data['orderer_cd']}}</td>
									<td class="text-left DSP_orderer_nm">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$data['orderer_nm']}}">{{$data['orderer_nm']}}</div>
									</td>
									<td class="text-left DSP_processing">{{$data['processing']}}</td>
									<td class="text-right DSP_product_cd">{{$data['product_cd']}}</td>
									<td class="text-left DSP_product_nm">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$data['product_nm']}}">{{$data['product_nm']}}</div>
									</td>
									<td class="text-right DSP_serial_no_from">{{$data['serial_no_from']}}</td>
									<td class="text-right DSP_serial_no_to">{{$data['serial_no_to']}}</td>
									<td class="text-left DSP_production_status">{{$data['production_status']}}</td>
									<td class="text-center DSP_complete_date">{{$data['manufacturing_completion_date']}}</td>
									<td class="text-center DSP_required_calc_datetime">{{$data['required_calc_datetime']}}</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="17" class="text-center dataTables_empty">&nbsp;</td>
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
