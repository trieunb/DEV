<div class="tab-pane" id="tab_01">
	<div class="form-group date-from-to">
		<!-- 32 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">取引開始日</label>
		<div class="col-md-2 date">
			<input  type 		="tel" 
					id 			='TXT_client_st_date'
					class       ="form-control datepicker date-from" 
					value       ="" 
					placeholder ="yyyy/mm/dd" 
					maxlength   ="10">
		</div>
		<!-- 33 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">取引終了日</label>
		<div class="col-md-2 date">
			<input  type 		="tel" 
					id 			="TXT_client_ed_date"
					class       ="form-control datepicker date-to"
					value       ="" 
					placeholder ="yyyy/mm/dd"
					maxlength   ="10">
		</div>
	</div>

	<!-- 34 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Shipping Mark1</label>
		<div class="col-md-10">
			<input type="text" id="TXT_mark1" class="form-control ime-active" maxlength="120">
		</div>
	</div>

	<!-- 35 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Shipping Mark2</label>
		<div class="col-md-10">
			<input type="text" id="TXT_mark2" class="form-control ime-active" maxlength="120">
		</div>
	</div>

	<!-- 36 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Shipping Mark3</label>
		<div class="col-md-10">
			<input type="text" id="TXT_mark3" class="form-control ime-active" maxlength="120">
		</div>
	</div>

	<!-- 37 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Shipping Mark4</label>
		<div class="col-md-10">
			<input type="text" id="TXT_mark4" class="form-control ime-active" maxlength="120">
		</div>
	</div>

	<!-- 38 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">通貨</label>
		<div class="col-md-1 date">
			<select class="form-control currency_div required" id="CMB_currency_div" maxlength="3" data-ini-target=true>
				<option></option>
					@if(isset($currency_div))
						@foreach($currency_div as $k=>$v)
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

	<!-- 39 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">金額端数処理</label>
		<div class="col-md-1 date">
			<select class="form-control round_div required" id="CMB_amount_rounding" maxlength="1" data-ini-target=true>
				<option></option>
					@if(isset($round_div))
						@foreach($round_div as $k=>$v)
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