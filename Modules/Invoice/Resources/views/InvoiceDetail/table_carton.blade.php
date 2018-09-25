<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
<div class="form-group">
	<div class="col-md-1">
		<input type="tel" class="form-control numeric text-right" id="count-add-blank-row" value="" maxlength="3">
	</div>
	<div class="col-md-1">
		<button class="btn btn-primary btn-icon" id="btn-add-blank-row">空行追加</button>
	</div>
</div> 
<div class="table-responsive sticky-table sticky-headers sticky-ltr-cells" id="div-table-carton" style="max-height: 300px !important;">
	<table class="table table-hover table-bordered table-xxs table-text table-carton table-list" id="table-carton">
		<thead>
			<tr class="col-table-header sticky-row">
				<th rowspan="2" class="text-center" width="3%">NO</th>
				<th rowspan="2" class="text-center" width="8%">カートンNo</th>
				<th rowspan="2" class="text-center" width="15%">製品</th>
				<th rowspan="2" class="text-center" width="7%">数量</th>
				<th rowspan="2" class="text-center" width="7%">重量単位</th>
				<th colspan="2" class="text-center">Net重量</th>
				<th colspan="2" class="text-center">Gross重量</th>
				<th rowspan="2" class="text-center" width="5%">容積単位</th>
				<th colspan="2" class="text-center">容積</th>
				<th rowspan="2" class="text-center" width="3%" style="text-align: center;">
					<button type="button" class="btn btn-primary btn-icon btn-add-row" id="btn-add-row-second">
						<i class="icon-plus3"></i>
					</button>
				</th>
			</tr>
			<tr class="col-table-header sticky-row">
				<th class="text-center" width="7%">単位</th>
				<th class="text-center" width="7%">計</th>
				<th class="text-center" width="7%">単位</th>
				<th class="text-center" width="7%">計</th>
				<th class="text-center" width="7%">単位</th>
				<th class="text-center" width="7%">計</th>
			</tr>
		</thead>
		<tbody>
		@if(isset($carrton_d) && !empty($carrton_d))
		@foreach($carrton_d as $key => $val)
			<tr class="">
				<td class="drag-handler text-center DSP_inv_carton_detail_no">{{ $val['inv_carton_no'] or '' }}</td>
				<td class="text-center">
					<input type="tel" class="form-control numeric TXT_carton_number required_carton" id="control-number" value="{{ $val['carton_number'] or '' }}" maxlength="6">
					<span class="DSP_fwd_detail_no_table_carton hidden">{{ $val['fwd_detail_no'] or '' }}</span>
				</td>
				<td class="text-left">
					<div class="tooltip-overflow max-width20 DSP_product_nm_table_carton" data-toggle="tooltip" data-placement="top" title="{{ $val['description'] or '' }}">{{ $val['description'] or '' }}</div>
					<input type="text" class="TXT_product_cd_table_carton hidden" name="DSP_product_cd_table_carton">
				</td>
				<td class="text-right">
					<input type="text" class="form-control quantity TXT_qty_table_carton required_carton" value="{{ $val['qty'] or '' }}" maxlength="7">
				</td>
				<td class="text-center">
					<select class="form-control unit_w_div CMB_unit_net_weight_div_table_carton" data-selected="{{ $val['unit_net_weight_div'] or ''}}" data-ini-target=true>
					<option></option>
					@if(isset($unit_w_div))
						@foreach($unit_w_div as $k=>$v)
							<option value="{{$v['lib_val_cd']}}" 
									data-ctl1="{{$v['lib_val_ctl1']}}" data-ctl2="{{$v['lib_val_ctl2']}}"
									data-ctl5="{{$v['lib_val_ctl5']}}" data-ctl6="{{$v['lib_val_ctl6']}}"
									data-nm-j="{{$v['lib_val_nm_j']}}" data-nm-e="{{$v['lib_val_nm_e']}}"
									data-ini_target_div="{{$v['ini_target_div']}}" @if($v['ini_target_div']=='1') selected @endif>
								{{$v['lib_val_nm_j']}} 
							</option>
						@endforeach
					@endif
					</select>
				</td>
				<td class="text-right">
					<input type="text" class="form-control weight TXT_unit_net_weight_table_carton" value="{{ $val['unit_net_weight'] or ''}}" maxlength="9">
				</td>
				<td class="text-right DSP_total_net_weight_table_carton">{{ $val['total_net_weight'] or ''}}</td>
				<td class="text-right">
					<input type="text" class="form-control weight TXT_unit_gross_weight_table_carton" value="{{ $val['unit_gross_weight'] or ''}}" maxlength="9">
				</td>
				<td class="text-right DSP_total_gross_weight_table_carton">{{ $val['total_gross_weight'] or ''}}</td>
				<td class="text-center">
					<select class="form-control unit_m_div CMB_unit_measure_table_carton" data-selected="{{ $val['unit_measure_div'] or ''}}" data-ini-target=true>
					<option></option>
					@if(isset($unit_m_div))
						@foreach($unit_m_div as $k=>$v)
							<option value="{{$v['lib_val_cd']}}" 
									data-ctl1="{{$v['lib_val_ctl1']}}" data-ctl2="{{$v['lib_val_ctl2']}}"
									data-ctl5="{{$v['lib_val_ctl5']}}" data-ctl6="{{$v['lib_val_ctl6']}}"
									data-nm-j="{{$v['lib_val_nm_j']}}" data-nm-e="{{$v['lib_val_nm_e']}}"
									data-ini_target_div="{{$v['ini_target_div']}}" @if($v['ini_target_div']=='1') selected @endif>
								{{$v['lib_val_nm_j']}} 
							</option>
						@endforeach
					@endif
					</select>
				</td>
				<td class="text-right">
					<input type="text" class="form-control measure TXT_unit_measure_table_carton" value="{{ $val['unit_measure'] or ''}}" maxlength="7">
				</td>
				<td class="text-right DSP_total_measure_table_carton">{{ $val['total_measure'] or ''}}</td>
				<td class="w-40px text-center">
					<button type="button" class="form-control remove-row" id="remove-row">
						<span class="icon-cross2 text-danger"></span>
					</button>
				</td>
			</tr>
			@endforeach
		@endif
		</tbody>
	</table>
</div>
<div class="row">
	<table class="table table-xxs table-text">
		<tbody>
			<tr>
				<td width="3%"></td>
				<td width="8%"></td>
				<td class="text-right" width="25%"><strong>合計</strong></td>
				<td class="text-right DSP_carton_total_qty" width="7%"></td>
				<td width="5%"></td>
				<td width="7%"></td>
				<td class="text-right DSP_carton_total_net_weight" width="7%"></td>
				<td width="7%"></td>
				<td class="text-right DSP_carton_total_gross_weight" width="7%"></td>
				<td width="7%"></td>
				<td width="7%"></td>
				<td class="text-right DSP_carton_total_measure" width="7%"></td>
				<td></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
<div class="row">
	<div class="col-md-4">
		<strong>総カートン数</strong>
		<span class="DSP_total_carton_num"></span>
	</div>
</div>
<div class="form-group" style="margin-top: 5px;">
	<div class="col-md-4 table-responsive">
		<table class="table table-hover table-bordered table-xxs table-text table-carton-total" id="table-carton-total">
			<thead>
				<tr class="col-table-header">
					<th class="text-center">カートンNo</th>
					<th class="text-center">NET重量</th>
					<th class="text-center">ＧROSS重量</th>
					<th class="text-center">容積</th>
				</tr>
			</thead>
			<tbody>
				<tr class="">
					<td class="text-right DSP_carton_number"></td>
					<td class="text-right DSP_net_weight"></td>
					<td class="text-right DSP_gross_weight"></td>
					<td class="text-right DSP_measure"></td>
				</tr>
			</tbody>
			<tfoot>
				<tr class="">
					<td class="col-table-header text-right">総合計</td>
					<td class="text-right DSP_total_net_weight_carton"></td>
					<td class="text-right DSP_total_gross_weight_carton"></td>
					<td class="text-right DSP_total_measure_carton"></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>