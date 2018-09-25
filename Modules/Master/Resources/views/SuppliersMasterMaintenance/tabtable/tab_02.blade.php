<div class="tab-pane" id="tab_02">
	<!-- 40 -->
	<div class="form-group">
		<label class="col-md-1  col-md-1-cus control-label text-right text-bold required">基本条件</label>
		<div class="col-md-4">
			<select class="form-control payment_conditions_div required" id="CMB_payment_info_basic_condition" data-ini-target=true>
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
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">付帯条件</label>
		<!-- 41 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">支払回数</label>
		<div class="col-md-1" style="padding-left: 25px">
			<select class="form-control payment_nums_div required" id="CMB_payment_nums_div" data-ini-target=true>
				<option></option>
					@if(isset($payment_nums_div))
						@foreach($payment_nums_div as $k=>$v)
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
		<label class="col-md-1"></label>
		<!-- 42 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">後払</label>
		<div class="col-md-1">
			<select class="form-control exists_div required" id="CMB_postpay" data-ini-target=true>
				<option></option>
					@if(isset($exists_div))
						@foreach($exists_div as $k=>$v)
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

	<div class="form-group hidden">
		<div class="col-md-1 col-md-1-cus"></div>
		<!-- 43 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold ">後払起算日</label>
		<div class="col-md-2" style="padding-left: 25px">
			<select class="form-control postpay_date_div " id="CMB_post_payment_date" data-ini-target=true>
				<option></option>
					@if(isset($postpay_date_div))
						@foreach($postpay_date_div as $k=>$v)
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
		<!-- 44 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold ">配分方法</label>
		<div class="col-md-2">
			<select class="form-control allocation_div " id="CMB_allocation_method" data-ini-target=true>
				<option></option>
					@if(isset($allocation_div))
						@foreach($allocation_div as $k=>$v)
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

	<div class="form-group hidden">
		<div class="col-md-6 col-md-offset-3 table-master-tab2">
			<table class="table table-hover table-bordered table-xxs table-text table-payment-nums">
				<thead>
					<tr class="col-table-header">
						<th class="text-center">回数</th>
						<th class="text-center" width="25%">比率</th>
						<th class="text-center" width="25%">支払い額</th>
						<th class="text-center">条件</th>
						<th class="text-center">日数</th>
					</tr>
				</thead>
				<tbody>
					<tr id="row_1" style="background-color: #FFF2CC;">
						<th class="text-center col-table-header DSP_frequency">1回目</th>
						<td class="text-right">
							<!-- 46 -->
							<input  type 	  ="text"
									class     ="form-control percent measure disabled disable-ime TXT_rate" 
									maxlength ="5" 
									name      ="" 
									disabled  ="true">
							<span class="disabled">%</span>
						</td>
						<td class="">
							<!-- 47 -->
							<input type="text" class="form-control rates money disabled TXT_payment_amount" name="" disabled="true" maxlength="11">
						</td>
						<td class="text-right">
							<!-- 48 -->
							<select class="form-control disabled paydate_condition_div CMB_conditions" disabled="true">
								<option></option>
								@if(isset($paydate_condition_div))
									@foreach($paydate_condition_div as $k=>$v)
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
						<td class="">
							<!-- 49 -->
							<select class="form-control disabled payday_condition_div CMB_days" disabled="true">
								<option></option>
								@if(isset($payday_condition_div))
									@foreach($payday_condition_div as $k=>$v)
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
					</tr>

					<tr id="row_2">
						<th class="text-center col-table-header DSP_frequency">2回目</th>
						<td class="text-right">
							<!-- 46 -->
							<input  type 	  ="text" 
									class     ="form-control percent measure disabled disable-ime TXT_rate" 
									maxlength ="5" 
									name      ="" 
									disabled  ="true">
							<span class="disabled">%</span>
						</td>
						<td class="">
							<!-- 47 -->
							<input type="text" class="form-control rates money disabled TXT_payment_amount" name="" disabled="true" maxlength="11">
						</td>
						<td class="text-right">
							<!-- 48 -->
							<select class="form-control disabled paydate_condition_div CMB_conditions" disabled="true">
								<option></option>
								@if(isset($paydate_condition_div))
									@foreach($paydate_condition_div as $k=>$v)
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
						<td class="">
							<!-- 49 -->
							<select class="form-control disabled payday_condition_div CMB_days" disabled="true">
								<option></option>
								@if(isset($payday_condition_div))
									@foreach($payday_condition_div as $k=>$v)
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
					</tr>

					<tr id="row_3" style="background-color: #FFF2CC;">
						<th class="text-center col-table-header DSP_frequency">3回目</th>
						<td class="text-right">
							<!-- 46 -->
							<input  type 	  ="text" 
									class     ="form-control percent measure disabled disable-ime TXT_rate" 
									maxlength ="5" 
									name      ="" 
									disabled  ="true">
							<span class="disabled">%</span>
						</td>
						<td class="">
							<!-- 47 -->
							<input type="text" class="form-control rates money disabled TXT_payment_amount" name="" disabled="true" maxlength="11">
						</td>
						<td class="text-right">
							<!-- 48 -->
							<select class="form-control disabled paydate_condition_div CMB_conditions" disabled="true">
								<option></option>
								@if(isset($paydate_condition_div))
									@foreach($paydate_condition_div as $k=>$v)
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
						<td class="">
							<!-- 49 -->
							<select class="form-control disabled payday_condition_div CMB_days" disabled="true">
								<option></option>
								@if(isset($payday_condition_div))
									@foreach($payday_condition_div as $k=>$v)
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
					</tr>

					<tr id="row_4">
						<th class="text-center col-table-header DSP_frequency">4回目</th>
						<td class="text-right">
							<!-- 46 -->
							<input  type 	  ="text"
									class     ="form-control percent measure disabled disable-ime TXT_rate" 
									maxlength ="5" 
									name      ="" 
									disabled  ="true">
							<span class="disabled">%</span>
						</td>
						<td class="">
							<!-- 47 -->
							<input type="text" class="form-control rates money disabled TXT_payment_amount" name="" disabled="true" maxlength="11">
						</td>
						<td class="text-right">
							<!-- 48 -->
							<select class="form-control disabled paydate_condition_div CMB_conditions" disabled="true">
								<option></option>
								@if(isset($paydate_condition_div))
									@foreach($paydate_condition_div as $k=>$v)
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
						<td class="">
							<!-- 49 -->
							<select class="form-control disabled payday_condition_div CMB_days" disabled="true">
								<option></option>
								@if(isset($payday_condition_div))
									@foreach($payday_condition_div as $k=>$v)
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
					</tr>

					<tr id="row_5" style="background-color: #FFF2CC;">
						<th class="text-center col-table-header DSP_frequency">5回目</th>
						<td class="text-right">
							<!-- 46 -->
							<input  type 	  ="text"
									class     ="form-control percent measure disabled disable-ime TXT_rate" 
									maxlength ="5" 
									name      ="" 
									disabled  ="true">
							<span class="disabled">%</span>
						</td>
						<td class="">
							<!-- 47 -->
							<input type="text" class="form-control rates money disabled TXT_payment_amount" name="" disabled="true" maxlength="11">
						</td>
						<td class="text-right">
							<!-- 48 -->
							<select class="form-control disabled paydate_condition_div CMB_conditions" disabled="true">
								<option></option>
								@if(isset($paydate_condition_div))
									@foreach($paydate_condition_div as $k=>$v)
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
						<td class="">
							<!-- 49 -->
							<select class="form-control disabled payday_condition_div CMB_days" disabled="true">
								<option></option>
								@if(isset($payday_condition_div))
									@foreach($payday_condition_div as $k=>$v)
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
					</tr>

					<tr class="">
						<th class="text-center col-table-header DSP_frequency">合計</th>
						<!-- 50 -->
						<td class="text-right" id="sum_TXT_rate">0%</td>
						<!-- 51 -->
						<td class="text-right" id="sum_TXT_payment_amount"> </td>
						<td class="text-right"> </td>
						<td class=""></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<!-- 52 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">その他</label>
		<div class="col-md-10">
			<input type="text" id="TXT_other" class="form-control ime-active" maxlength="200">
		</div>
	</div>
</div>