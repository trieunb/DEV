<table class="table table-hover table-bordered table-xxs table-text table-invoice table-list" id="table-invoice">
	<thead>
		<tr class="col-table-header sticky-row">
			<th rowspan="2" class="text-center" style="width: 40px;">No</th>
			<th rowspan="2" class="text-center" width="15px"></th>
			<th class="text-center" style="width: 50px">区分</th>
			<th class="text-center">Code</th>
			<th class="text-center">Description</th>
			<th class="text-center">Q'ty</th>
			<th class="text-center" style="width: 100px">Unit of M</th>
			<th class="text-center">Unit Price</th>
			<th class="text-center">Amount</th>
			<th colspan="2" class="text-center">Unit Measurement</th>
			<!-- <th class="text-center" width="4%">納期</th> -->
			<!-- <th rowspan="2" class="w-40px text-center" style="width: 40px;">
				<button type="button" class="btn btn-primary btn-icon btn-add-row" id="btn-add-row">
					<i class="icon-plus3"></i>
				</button>
			</th> -->
		</tr>
		<tr class="col-table-header sticky-row">
			<th class="text-center" style="width: 80px;">受注残数</th>
			<th class="text-center" style="width: 120px;">PI No</th>
			<th class="text-center">社外用備考</th>
			<th class="text-center" style="width: 100px;">Unit N/W</th>
			<th class="text-center" style="width: 100px;">Unit of W</th>
			<th class="text-center" style="width: 100px;">N/W</th>
			<th class="text-center" style="width: 100px;">Unit G/W</th>
			<th class="text-center" style="width: 100px;">G/W</th>
			<th class="text-center" style="width: 100px;">Measure</th>
			<!-- <th class="text-center"><button class="btn btn-default">納期一括コピー</button></th> -->
		</tr>
	</thead>
	<tbody>
	@if(isset($inv_d) && !empty($inv_d))
		@foreach($inv_d as $key => $val)
		<tr class="">
			<td class="drag-handler text-center DSP_inv_detail_no">
				{{ $val['inv_detail_no'] or '' }}
			</td>
			<td class="text-center popup">
				<button class="btn tab-top btn-popup-carton-item-set" data-search="carton">セット</button>
				<span class="DSP_fwd_detail_no hidden">{{ $val['fwd_detail_no'] or '' }}</span>
			</td>
			<td class="text-center">
				<select class="form-control tab-top sales_detail_div CMB_sales_detail_div" data-ini-target=true>
				<option></option>
				@if(isset($sales_detail_div))
					@foreach($sales_detail_div as $k=>$v)
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
				<!-- <div class="input-group" style="padding: 1px 0px;">
					<select class="form-control tab-top sales_detail_div CMB_sales_detail_div" data-selected="{{ $val['sales_detail_div'] or ''}}">
					</select>
				</div> -->
				<div class="boder-line"></div>
				<p class="DSP_remaining_qty" style="float:right; height: 15px;">{{ $val['remaining_qty'] or '' }}</p>
			</td>
			<td class="text-center">
				<!-- <input type="text" class="form-control tab-top TXT_product_cd" value="{{  $val['product_cd'] or '' }}" maxlength="6">
				<input type="text" class="TXT_product_nm hidden" name="" value="{{ $val['product_nm'] }}"> -->
				<div class="input-city-popup">
					@includeIf('popup.searchproduct', array(
														'val'		=>  $val['product_cd'],
														'class_tab' => 	'tab-top', 
														'is_nm' 	=> 	false
													))
					<input type="text" class="TXT_product_nm hidden" name="" value="{{ $val['product_nm'] }}">
				</div>
				<div class="boder-line"></div>
				<p class="DSP_pi_no">{{ $val['pi_no'] or '' }}</p>
			</td>
			<td class="text-center">
				<input type="text" class="form-control tab-top TXT_description" data-toggle="tooltip" data-placement="top" title="{{$val['description'] or ''}}" value="{{ $val['description'] or '' }}" maxlength="200">
				<div class="boder-line"></div>
				<input type="text" class="form-control tab-bottom TXT_outside_remarks" value="{{ $val['outside_remarks'] or '' }}" >
			</td>
			<td class="text-center">
				<input type="text" class="form-control quantity tab-top TXT_qty" maxlength="7" value="{{ $val['inv_qty'] or '' }}">
				<div class="boder-line"></div>
				<input type="text" class="form-control weight tab-bottom TXT_unit_net_weight" maxlength="9" value="{{ $val['unit_net_weight'] or ''}}">
			</td>
			<td class="text-center">
				<select class="form-control tab-top unit_q_div CMB_unit_of_m_div" data-selected="{{ $val['unit_qty_div'] or ''}}" data-ini-target=true>
				<option></option>
				@if(isset($unit_q_div))
					@foreach($unit_q_div as $k=>$v)
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
				<div class="boder-line"></div>
				<select class="form-control tab-bottom unit_w_div CMB_unit_net_weight_div" data-selected="{{ $val['unit_net_weight_div'] or ''}}" data-ini-target=true>
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
			<td class="text-center">
				<input type="text" class="form-control price tab-top TXT_unit_price"  maxlength="12" value="{{ $val['unit_price'] or ''}}">
				<div class="boder-line"></div>
				<input type="text" class="form-control weight tab-bottom TXT_net_weight" maxlength="20" value="{{ $val['net_weight'] or ''}}">
			</td>
			<td class="text-center">
				<input type="text" class="form-control money tab-top TXT_amount" maxlength="20" value="{{ $val['amount'] or ''}}">
				<div class="boder-line"></div>
				<input type="text" class="form-control weight tab-bottom TXT_unit_gross_weight" maxlength="9" value="{{ $val['unit_gross_weight'] or ''}}">
			</td>
			<td class="text-center">
				<input type="text" class="form-control measure tab-top TXT_unit_measure_qty"  maxlength="7" value="{{ $val['unit_measure'] or ''}}">
				<div class="boder-line"></div>
				<input type="text" class="form-control weight tab-bottom TXT_gross_weight" maxlength="20" value="{{ $val['gross_weight'] or ''}}">
			</td>
			<td class="text-center">
				<select class="form-control tab-top unit_m_div CMB_unit_measure_price" data-selected="{{ $val['unit_measure_div'] or ''}}" data-ini-target=true>
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
				<div class="boder-line"></div>
				<input type="text" class="form-control measure tab-bottom TXT_measure" maxlength="20" value="{{ $val['measure'] or ''}}">
			</td>
		</tr>
		@endforeach
	@endif
	</tbody>
</table>