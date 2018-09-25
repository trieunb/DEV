@extends('layouts.main')

@section('title')
	入金票入力
@endsection

@section('components')
	{!! public_url('assets/js/plugins/forms/styling/uniform.min.js')!!}
	{!! public_url('assets/js/plugins/notifications/pnotify.min.js')!!}
	{!! public_url('assets/js/plugins/forms/selects/bootstrap_multiselect.js')!!}
@endsection

@section('stylesheet')
	{!! public_url('modules/deposit/css/deposit_detail.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/deposit/js/deposit_detail.js')!!}
@endsection

@section('button')
	{{Button::button_left(array('btn-back', 'btn-save', 'btn-delete'), $mode)}}
@stop

@section('content')
	<script>
		var mode = "{{$mode}}";
		var from = "{{$from}}";
	</script>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">入金票入力</h5>
				<div id="operator_info">
					{!! infoMemberCreUp('', '', '', '') !!}
				</div>
			</div>

			<div class="panel-body">	
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">入金NO</label>
					<div class="col-md-3">
						@includeIf('popup.searchdeposit', array(
							'id'          => 'TXT_deposit_no',
							'class_cd'    => 'TXT_deposit_no',
							'val'         => $deposit_no,
							'is_disabled' => ($mode=='I') ? true : false,
							'is_required' => ($mode=='I') ? false : true,
							'is_nm'       => false
						))
					</div>
				</div>

				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Invoice No</label>
					<div class="col-md-3">
						@includeIf('popup.searchinvoice', array(
							'id'       => 'TXT_inv_no',
							'class_cd' => 'TXT_inv_no',
							'is_nm'    => false
						))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">受注No</label>
					<div class="col-md-3">
						@includeIf('popup.searchaccept', array(
							'id'       => 'TXT_rcv_no',
							'class_cd' => 'TXT_rcv_no',
							'is_nm'    => false
						))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">取引先</label>
					<div class="col-md-2">
						@includeIf('popup.searchsuppliers', array(
							'id'       => 'TXT_client_cd',
							'class_cd' => 'TXT_client_cd',
							'is_nm'    => true
						))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right required text-bold">入金分類</label>
					<div class="col-md-2">
						<select class="form-control deposit_div CMB_deposit_div required" id="CMB_deposit_div" name="CMB_deposit_div" data-ini-target=true>
						<option></option>
						@if(isset($deposit_div))
							@foreach($deposit_div as $k=>$v)
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
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">入金日</label>
					<div class="col-md-2 date">
						<input type="tel" class="datepicker form-control TXT_deposit_date required" id="TXT_deposit_date" name="TXT_deposit_date" maxlength="10" value="{{date('Y/m/d')}}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right required text-bold">分割入金管理</label>
					<div class="">
						<div class="col-md-2" style="float:left">
							<select class="form-control target_div CMB_split_deposit_div required" id="CMB_split_deposit_div" name="CMB_split_deposit_div" data-ini-target=true>
							<option></option>
							@if(isset($target_div))
								@foreach($target_div as $k=>$v)
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
						<div style="float:left">
							<span class="input-group-text employee_nm">※対象選択時、分割入金管理票に表示されます。</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">当初入金予定日</label>
					<div class="">
						<div style="float:left">
							<div class="col-md-2 date">
								<input type="tel" class="datepicker form-control TXT_initial_deposit_date" id="TXT_initial_deposit_date" name="TXT_initial_deposit_date" maxlength="7">
							</div>
						</div>
						<div style="float:left">
							<span class="input-group-text employee_nm">※分割入金管理対象の場合、当初の入金予定日を指定して下さい。</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right required text-bold">入金銀行</label>
					<div class="col-md-3">
						<select class="form-control bank_div CMB_deposit_bank_div required" id="CMB_deposit_bank_div" name="CMB_deposit_bank_div" data-ini-target=true>
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
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">国</label>
					<div class="col-md-3">
						@includeIf('popup.searchcountry', array(
							'id'          => 'TXT_client_country_div',
							'class_cd'    => 'TXT_client_country_div',
							'is_nm'       => true
						))
					</div>
				</div><!-- end/.form-group -->	

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right required text-bold">通貨</label>
					<div class="col-md-2">
						<select class="form-control currency_div CMB_currency_div required" id="CMB_currency_div" name="CMB_currency_div" data-ini-target=true>
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
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right required text-bold">入金区分</label>
					<div class="col-md-2">
						<select class="form-control deposit_way_div CMB_deposit_way_div required" id="CMB_deposit_way_div" name="CMB_deposit_way_div" data-ini-target=true>
						<option></option>
						@if(isset($deposit_way_div))
							@foreach($deposit_way_div as $k=>$v)
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
				</div><!-- end/.form-group -->

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right required text-bold">先方送金額</label>
					<div class="col-md-2">
						<input type="text" id="TXT_remittance_amt" name="TXT_remittance_amt" class="form-control money TXT_remittance_amt required" maxlength="15" real_len="8" decimal_len="2">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">手数料(外貨)</label>
					<div class="col-md-2">
						<input type="text" id="TXT_fee_foreign_amt" name="TXT_fee_foreign_amt" class="form-control money TXT_fee_foreign_amt" maxlength="15" real_len="8" decimal_len="2">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">手数料(円貨)</label>
					<div class="col-md-2">
						<input type="text" id="TXT_fee_yen_amt" name="TXT_fee_yen_amt" class="form-control money TXT_fee_yen_amt currency_JPY" maxlength="15" real_len="8" decimal_len="2">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">着金額(外貨)</label>
					<label class="col-md-2 control-label text-right DSP_arrival_foreign_amt lbl-numeric" id="DSP_arrival_foreign_amt"></label>
				</div>

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">円入金額</label>
					<label class="col-md-2 control-label text-right DSP_deposit_yen_amt lbl-numeric" id="DSP_deposit_yen_amt"></label>
					<div class="col-md-7 text-right">
						<label class="control-label text-right" style="float:left;width: 200px;">
							<label class="required">レート</label> 
							<span>1</span>
							<span class="DSP_currency_div deposit_currency_div"></span>
							<span>= ￥</span>
						</label>
						<div style="float:left; margin-left: 6px; margin-right: 6px; width: 100px;">
							<input type="text" id="TXT_exchange_rate"  name="TXT_exchange_rate" class="form-control money TXT_exchange_rate required" maxlength="15" real_len="8" decimal_len="2">
						</div>
						<div style="float:left">
							<select class="form-control rate_confirm_div CMB_rate_confirm_div required" id="CMB_rate_confirm_div" name="CMB_rate_confirm_div" style="width: 100px;" data-ini-target=true>
							<option></option>
							@if(isset($rate_confirm_div))
								@foreach($rate_confirm_div as $k=>$v)
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

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">特記事項</label>
					<div class="col-md-10">
						<input type="text" class="form-control left-radius TXT_notices" id="TXT_notices" name="TXT_notices" maxlength="200">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">社内用備考</label>
					<div class="col-md-10">
						<input type="text" class="form-control left-radius TXT_inside_remarks" id="TXT_inside_remarks" name="TXT_inside_remarks" maxlength="200">
					</div>
				</div>
				<div class="form-group clearfix" style="border-top: 2px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">受注金額</label>
					<label class="col-md-1 col-md-1-cus control-label text-right DSP_total_amt" id="DSP_total_amt" style="padding-right: 10px;"></label>
					<label class="col-md-2 col-md-2-cus control-label text-right">
						<span class="text-bold">通貨</span>
						&nbsp;
						<span class="DSP_currency_div rcv_currency_div"></span>
					</label>
					<label class="col-md-3 col-md-3-cus control-label text-right">
						<span class="text-bold">入金額合計</span>
						&nbsp;
						<span id="DSP_total_entered_amt"></span>
					</label>
				</div>

				<div class="form-group">
					<div class="col-md-12">
						<div id="div-table-deposit">
							@includeIf('deposit::DepositDetail.table')
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /search field -->
	</div>

@endsection