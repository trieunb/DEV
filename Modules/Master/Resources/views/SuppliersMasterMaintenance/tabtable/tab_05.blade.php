<div class="tab-pane" id="tab_05">
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right required text-bold">当方口座</label>
		<div class="col-md-3">
			<select class="form-control bank_div required" id="CMB_bank_div" data-ini-target=true>
				<option></option>
					@if(isset($bank_div))
						@foreach($bank_div as $k=>$v)
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
</div>