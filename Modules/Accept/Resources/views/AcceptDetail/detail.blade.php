@extends('layouts.main')

@section('title')
	@lang('title.accept-detail')
@endsection

@section('button')
	{{Button::button_left(array('btn-back','btn-save', 'btn-delete', 'btn-approve', 'btn-cancel-approve', 'btn-cancel-order'), $mode)}}
	{{--Button::button_left(array('btn-back','btn-save', 'btn-delete', 'btn-cancel-order', 'btn-approve', 'btn-cancel-approve', 'btn-remove'), $mode)--}}
@endsection

@section('stylesheet')
	{!! public_url('modules/accept/css/accept_detail.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/accept/js/accept_detail.js')!!}
@endsection


@section('content')
	<script>
		var mode        = "{{$mode}}";
		var from        = "{{$from}}";
		var cre_user_cd = "{{$cre_user_cd or ''}}";
		var cre_user_nm = "{{$cre_user_nm or ''}}";
	</script>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.accept-detail')</h5>
				<div class="infor-created">
					@includeIf('layouts._operator_info')
				</div>
			</div>

			<div class="panel-body">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				
				<!-- 受注No -->
				<!-- ステータス -->
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">受注No</label>
					<div class="col-md-2">
						@includeIf('popup.searchaccept',
								    array('class_cd'	=> "TXT_rcv_no",
								    	  'val' 		=> isset($acceptDetail_no) ? $acceptDetail_no : '',
								          'is_required' => true,
								          'disable_ime' => 'disable-ime',
								          'is_nm'		=> false))
					</div>
					
					<label class="col-md-3 col-md-3-cus control-label text-right" style="float: right;">
						<span class="text-bold">ステータス</span>
						<span class="DSP_status">申請中</span>
						<input type="text" class="hidden TXT_rcv_status" name="TXT_pi_status" value="">
						<span class="DSP_rcv_status_cre_datetime">yyyy/mm/dd hh:mm:ss</span>
					</label>
				</div>
		
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				
				<!-- 受注日 -->
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">受注日</label>
					<div class="col-md-2 date">
						<input type="tel" class="form-control datepicker TXT_rcv_date required disable-ime" data-init="{{date('Y/m/d')}}" value="{{date('Y/m/d')}}">
					</div>
				</div>
				
				<!-- 取引先 -->
				<!-- Name -->
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">取引先</label>
					<div class="col-md-2">
						@includeIf('popup.searchsuppliers',
								    array('class_cd'	=> "TXT_cust_cd",
								          'is_required' => true,
								          'disable_ime' => 'disable-ime',
								          'is_nm'		=> false))
					</div>
					
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">Name</label>
					<div class="col-md-4">
						<input type="text" class="form-control client_nm TXT_cust_nm required">
					</div>

					<button type="button" class="btn btn-primary btn-icon" style="width: 80px;" id="show-address-to">
						住所表示
					</button>
				</div>
				
				<!-- address of 取引先 -->
				<div class="form-group address-to hidden">	
					<div class="col-md-5"></div>
					<div class="col-md-6 boder-address">				
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Address1</label>
						<div class="col-md-5">
							<input type="text" name="TXT_cust_adr1" class="form-control client_adr1 TXT_cust_adr1" maxlength="120">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Address2</label>
						<div class="col-md-5">
							<input type="text" name="TXT_cust_adr2" class="form-control client_adr2 TXT_cust_adr2" maxlength="120">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Zipcode</label>
						<div class="col-md-5">
							<input type="text" name="TXT_cust_zip" class="form-control TXT_cust_zip" maxlength="8">
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">City</label>
						<div class="col-md-3">
							@includeIf('popup.searchcity', array(
												'class_cd' 		=> 'TXT_cust_city_div', 
												'class_nm' 		=> 'DSP_cust_city_nm',
												'is_required' 	=> true,
												'disabled_ime'	=> 'disabled-ime',
												'is_nm'    		=> true))
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">Country</label>
						<div class="col-md-3">
							@includeIf('popup.searchcountry', array(
												'class_cd'     	=> 'TXT_cust_country_div', 
												'class_nm'     	=> 'DSP_cust_country_nm',
												'is_required' 	=> true,
												'disabled_ime'	=> 'disabled-ime',
												'is_nm'        	=> true))
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Tel</label>
						<div class="col-md-3">
							<input type="text" name="TXT_cust_tel" class="form-control fax TXT_cust_tel" maxlength="20">
						</div>

						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Fax</label>
						<div class="col-md-3">
							<input type="text" name="TXT_cust_fax" class="form-control fax TXT_cust_fax" maxlength="20">
						</div>
					</div>
					</div>
				</div>
				
				<!-- Consignee -->
				<!-- Name -->
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Consignee</label>
					<div class="col-md-2">
						@includeIf('popup.searchsuppliers', array(
											'class_cd'  	=> 'TXT_consignee_cd',
											'is_nm'     	=> false,
											'disabled_ime'	=> 'disabled-ime',
											'is_btn_clear' 	=> true))
					</div>

					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">Name</label>
					<div class="col-md-4">
						<input type="text" class="form-control client_nm TXT_consignee_nm required" maxlength="120">
					</div>

					<button type="button" class="btn btn-primary btn-icon" style="width: 80px;" id="show-address-from">
						住所表示
					</button>
				</div>

				<!-- address of Consignee -->
				<div class="form-group address-from hidden">
					<div class="col-md-5"></div>
					<div class="col-md-6 boder-address">
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Address1</label>
						<div class="col-md-5">
							<input type="text" name="TXT_consignee_adr1" class="form-control client_adr1 TXT_consignee_adr1" maxlength="120">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Address2</label>
						<div class="col-md-5">
							<input type="text" name="TXT_consignee_adr2" class="form-control client_adr2 TXT_consignee_adr2" maxlength="120">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Zipcode</label>
						<div class="col-md-5">
							<input type="text" name="TXT_consignee_zip" class="form-control TXT_consignee_zip" maxlength="8">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">City</label>
						<div class="col-md-3">
							@includeIf('popup.searchcity', array(
												'class_cd'    	=> 'TXT_consignee_city_div', 
												'class_nm'    	=> 'DSP_consignee_city_nm',
												'is_required' 	=> true,
												'disabled_ime'	=> 'disabled-ime',
												'is_nm'       	=> true))
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">Country</label>
						<div class="col-md-3">
							@includeIf('popup.searchcountry', array(
													'class_cd'    	=> 'TXT_consignee_country_div',
													'class_nm'    	=> 'DSP_consignee_country_nm',
													'is_required' 	=> true,
													'disabled_ime'	=> 'disabled-ime',
													'is_nm'       	=> true))
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Tel</label>
						<div class="col-md-3">
							<input type="text" name="TXT_consignee_tel" class="form-control fax TXT_consignee_tel" maxlength="20">
						</div>
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Fax</label>
						<div class="col-md-3">
							<input type="text" name="TXT_consignee_fax" class="form-control fax TXT_consignee_fax" maxlength="20">
						</div>
					</div>
					</div>
				</div>
				
				<!-- Shipping Mark -->
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Shipping Mark</label>
					<div class="col-md-2">
						<span class="text-bg text-bold">1</span>
						<input type="text" name="TXT_shipping_mark_1" class="form-control TXT_shipping_mark_1" maxlength="120" style="width: 120px; display: inline;">
					</div>

					<div class="col-md-2">
						<span class="text-bg text-bold">2</span>
						<input type="text" name="TXT_shipping_mark_2" class="form-control TXT_shipping_mark_2" maxlength="120" style="width: 120px; display: inline;">
					</div>

					<div class="col-md-2">
						<span class="text-bg text-bold">3</span>
						<input type="text" name="TXT_shipping_mark_3" class="form-control TXT_shipping_mark_3" maxlength="120" style="width: 120px; display: inline;">
					</div>

					<div class="col-md-2">
						<span class="text-bg text-bold">4</span>
						<input type="text" name="TXT_shipping_mark_4" class="form-control TXT_shipping_mark_4" maxlength="120" style="width: 120px; display: inline;">
					</div>
				</div>
				
				<!-- Packing -->
				<!-- Shipment -->
				<!-- Currency -->
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">Packing</label>
					<div class="col-md-2">
						<input type="text" name="TXT_packing" class="form-control TXT_packing required" maxlength="32">
					</div>

					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Shipment</label>
					<div class="col-md-2">
						<select class="form-control shipment_div CMB_shipment_div" data-ini-target=true>
							<option></option>
							@if(isset($shipment_div))
								@foreach($shipment_div as $k=>$v)
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

					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">Currency</label>
					<div class="col-md-1">
						<select class="form-control currency_div CMB_currency_div required" data-ini-target=true>
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
				
				<!-- Port of shipment -->
				<!-- Trade Terms -->
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">Port of shipment</label>
					<div class="col-md-1">
						<select class="form-control required port_city_div CMB_port_city_div" data-ini-target=true>
							<option></option>
							@if(isset($port_city_div))
								@foreach($port_city_div as $k=>$v)
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
					</div><div class="col-md-1">
						<select class="form-control required port_country_div CMB_port_country_div" data-ini-target=true>
							<option></option>
							@if(isset($port_country_div))
								@foreach($port_country_div as $k=>$v)
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
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">Trade Terms</label>
					<div class="col-md-2">
						<select class="form-control required trade_terms_div CMB_trade_terms_div" data-ini-target=true>
							<option></option>
							@if(isset($trade_terms_div))
								@foreach($trade_terms_div as $k=>$v)
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
				
				<!-- Destination -->
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">Destination</label>
					<div class="col-md-2">
						@includeIf('popup.searchcity', array(
													'class_cd' 		=>	'TXT_dest_city_div',
													'class_nm'		=>	'DSP_dest_city_nm',
													'disabled_ime'	=> 	'disabled-ime',
													'is_required' 	=> 	true,
													'is_nm' 		=>	true))
					</div>

					<label class="col-md-1 col-md-1-cus"></label>
					<div class="col-md-2">
						@includeIf('popup.searchcountry', array(
													'class_cd' 		=> 	'TXT_dest_country_div',
													'class_nm'		=> 	'DSP_dest_country_nm',
													'disabled_ime'	=> 	'disabled-ime',
													'is_required' 	=> 	true,
													'is_nm' 		=>	true))
					</div>
				</div>
				
				<!-- Payment -->
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">Payment</label>
					<div class="col-md-6">
						<input type="text" class="form-control disable-resize TXT_payment_notes required" maxlength="256">
					</div>
				</div>
				
				<!-- 当社負担運賃(US$) -->
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">当社負担運賃(<span class="DSP_currency_div">US$</span>)</label>
					<div class="col-md-2">
						<input type="text" id="control-number" class="form-control money TXT_our_freight_amt disable-ime" maxlength="15">
					</div>
				</div>
				
				<!-- detail -->
				<div class="table-responsive sticky-table sticky-headers sticky-ltr-cells" id="div-table-accept">
					@includeIf('accept::acceptdetail.table_accept')
				</div>
				
				<!-- sum amount detail -->
				<div class="form-group" style="margin-top: 5px;">
					<div class="col-md-6 table-responsive">
						<table class="table table-hover table-bordered table-xxs table-text">
							<thead>
								<tr class="col-table-header">
									<th class="text-center">Q'ty</th>
									<th colspan="2" class="text-center">G/W</th>
									<th colspan="2" class="text-center">N/W</th>
									<th colspan="2" class="text-center">Measurement</th>
								</tr>
							</thead>
							<tbody>
								<tr class=""  style="height: 30px;">
									<!-- Q'ty -->
									<td class="text-right" style="width: 100px;">
										<span class="DSP_total_qty"></span>
									</td>

									<!-- G/W -->
									<td class="text-right" style="width: 100px;">
										<span class="DSP_total_gross_weight"></span>
									</td>
									<td class="" style="width: 100px;">
										<span class="DSP_unit_total_gross_weight_nm"></span>
										<span class="DSP_unit_total_gross_weight_div hidden"></span>
									</td>

									<!-- N/W -->
									<td class="text-right" style="width: 100px;">
										<span class="DSP_total_net_weight"></span>
									</td>									
									<td class="" style="width: 100px;">
										<span class="DSP_unit_total_net_weight_nm"></span>
										<span class="DSP_unit_total_net_weight_div hidden"></span>
									</td>

									<!-- Measurement -->
									<td class="text-right" style="width: 100px;">
										<span class="DSP_total_measure"></span>
									</td>
									<td class="" style="width: 100px;">
										<span class="DSP_unit_total_measure_nm"></span>
										<span class="DSP_unit_total_measure_div hidden"></span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="col-md-3 table-responsive" style="float: right;">
						<table class="table table-hover table-bordered table-xxs table-text table-total">
							<thead>
								<tr class="col-table-header">
									<th class="" style="width: 30%">小計</th>
									<th class="text-right total-header" style="width: 30%">
										<span class="DSP_total_detail_amt"></span>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr class="">
									<td class="">
										<span class="title-freight hidden text-bold">Freight</span>
									</td>
									<td class="text-right">
										<input type="text" class="form-control value-freight money hidden TXT_freigt_amt disable-ime" maxlength="15">
									</td>
								</tr>

								<tr class="">
									<td class="">
										<span class="title-insurance hidden text-bold">Insurance</span>
									</td>
									<td class="text-right">
										<input type="text" class="form-control value-insurance money hidden TXT_insurance_amt disable-ime" maxlength="15">
									</td>
								</tr>

								<tr class="">
									<td class="">
										<span class="title-jp hidden text-bold">消費税</span>
									</td>
									<td class="text-right">
										<span class="value-jp numeric hidden DSP_tax_amt"></span>
										<span class="hidden tax_rate"></span>
									</td>
								</tr>
							</tbody>
							<tfoot>
								<tr class="">
									<td class="col-table-header">総合計</td>
									<td class="text-right total-footer">
										<span class="DSP_total_amt"></span>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				
				<!-- Country of Origin -->
				<!-- Manufacture -->
				<div class="form-group">
					<!-- Country of Origin -->
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">Country of Origin</label>
					<div class="col-md-2">
						<input type="text" name="TXT_country_of_origin" class="form-control TXT_country_of_origin required" maxlength="32">
					</div>

					<!-- Manufacture -->
					<label class="col-md-1 control-label text-right text-bold required">Manufacture</label>
					<div class="col-md-2">
						<input type="text" name="TXT_manufacture" class="form-control TXT_manufacture required" maxlength="32">
					</div>
				</div>
				
				<!-- 署名者 -->
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">署名者</label>
					<div class="col-md-3">
						@includeIf('popup.searchuser', array(
											'class_cd'    	=> 	'TXT_sign_cd',
											'class_nm'    	=> 	'DSP_sign_nm',
											'is_required' 	=> 	true,
											'disabled_ime'	=> 	'disabled-ime',
											'val'			=>	$cre_user_cd,
											'is_nm'       	=> 	true))
					</div>
				</div>

				<!-- 社内用備考 -->
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">社内用備考</label>
					<div class="col-md-9">
						<textarea class="form-control disable-resize remarks TXA_inside_remarks" maxlength="200" rows="2"></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('content_hidden')
	<!-- table row -->
	<table class="hide">
		<tbody id="table-row">
			<tr class="">
				<!-- NO -->
				<td class="drag-handler text-center DSP_rcv_detail_no"></td>

				<!-- 区分 -->
				<td class="text-center">
					<select class="form-control tab-top sales_detail_div CMB_sales_detail_div required_detail" data-ini-target=true>
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
				</td>

				<!-- Code -->
				<td class="text-center">
					@includeIf('popup.searchproduct', [
											'class_cd'    => 'TXT_product_cd',
											'class_tab'   => 'tab-top', 
											'is_nm'       => false,
			          						'disable_ime' => 'disable-ime'])
				</td>

				<!-- Description/社外用備考 -->
				<td class="text-center">
					<input type="text" class="form-control tab-top TXT_description required_detail" maxlength="120">
					<div class="boder-line"></div>
					<input type="text" class="form-control tab-bottom TXT_outside_remarks" maxlength="200">
				</td>

				<!-- Q'ty/Unit N/W -->
				<td class="text-center">
					<input type="text" class="form-control quantity tab-top TXT_qty disable-ime" maxlength="7">
					<div class="boder-line"></div>
					<input type="text" class="form-control weight tab-bottom TXT_unit_net_weight disable-ime" maxlength="7">
				</td>

				<!-- Unit of M/Unit of W -->
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
					<select class="form-control tab-bottom unit_w_div CMB_unit_net_weight_div" data-ini-target=true>
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

				<!-- Unit Price/N/W -->
				<td class="text-center">
					<input type="text" class="form-control price tab-top TXT_unit_price disable-ime" maxlength="12">
					<span class="hidden DSP_unit_price_JPY">{{ $val['unit_price_JPY'] or ''}}</span>
					<span class="hidden DSP_unit_price_USD">{{ $val['unit_price_USD'] or ''}}</span>
					<span class="hidden DSP_unit_price_EUR">{{ $val['unit_price_EUR'] or ''}}</span>
					<div class="boder-line"></div>
					<input type="text" class="form-control weight tab-bottom TXT_net_weight" disabled="disabled" maxlength="20">
				</td>

				<!-- Amount/Unit G/W -->
				<td class="text-center">
					<input type="text" class="form-control money tab-top TXT_amount disable-ime" disabled="disabled" maxlength="20">
					<div class="boder-line"></div>
					<input type="text" class="form-control weight tab-bottom TXT_unit_gross_weight disable-ime" maxlength="9">
				</td>

				<!-- Unit Measurement(G/W) -->
				<td class="text-center">
					<input type="text" class="form-control measure tab-top TXT_unit_measure_qty disable-ime" maxlength="7">
					<div class="boder-line"></div>
					<input type="text" class="form-control weight tab-bottom TXT_gross_weight disable-ime" disabled="disabled" maxlength="20">
				</td>

				<!-- Unit Measurement(Measure) -->
				<td class="text-center">
					<select class="form-control tab-top unit_m_div CMB_unit_measure_price " data-ini-target=true>
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
					<input type="text" class="form-control measure tab-bottom TXT_measure disable-ime" disabled="disabled" maxlength="20">
				</td>

				<td class="text-center">
					<button type="button" class="form-control tab-bottom remove-row" id="remove-row">
						<span class="icon-cross2 text-danger"></span>
					</button>
				</td>
			</tr>
		</tbody>
	</table>
	<!--/table row -->
@endsection