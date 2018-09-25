<div class="tab-pane" id="tab_03">
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">決済条件</label>
	</div>
	<div class="form-group">
		<!-- 54 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">基本条件</label>
		<div class="col-md-4">
			<select class="form-control payment_conditions_div" id="CMB_withdrawal_info_basic_condition" data-ini-target=true>
				<option></option>
					@if(isset($payment_conditions_div))
						@foreach($payment_conditions_div as $k=>$v)
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
		</div>
	</div>
	<div class="form-group">
		<!-- 55 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">付帯条件</label>
		<div class="col-md-8">
			<input type="text" id="TXT_incidental_condition" class="form-control ime-active" maxlength="40">
		</div>
	</div>
</div>