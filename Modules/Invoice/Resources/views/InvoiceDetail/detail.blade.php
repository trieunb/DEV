@extends('layouts.main')

@section('title')
	Invoice入力
@endsection

@section('button')
	{{Button::button_left(array('btn-back', 'btn-save','btn-delete', 'btn-invoice', 'btn-delivery-note', 'btn-print-packing', 'btn-print-mark'), $mode)}}
	<!-- , 'btn-cancel-order', 'btn-cancel-document', 'btn-approve', 'btn-cancel-approve', 'btn-loss-order' -->
@endsection

@section('stylesheet')
	{!! public_url('modules/invoice/css/invoice_detail.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/invoice/js/invoice_detail.js')!!}
	{!! public_url('modules/invoice/js/cal_total_invoice_detail.js')!!}
@endsection

@section('content')
	<script>
		var mode 			= "{{$mode}}";
		var from 			= "{{$from}}";
		var cre_user_cd 	=	"{{$cre_user_cd}}"
		var cre_user_nm 	=	"{{$cre_user_nm}}"
	</script>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">Invoice入力</h5>
				<div class="infor-created">
					@includeIf('layouts._operator_info')
				</div>
			</div>
			<div class="panel-body" style="padding-bottom: 0px;">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">Invoice No</label>
					<div class="col-md-2">
						@includeIf('popup.searchinvoice', array(
															'val' 			=> 	isset($inv_no) ? $inv_no : '',
															'class_cd'		=>	'TXT_inv_no	',
															'is_required'	=>	true
														))
					</div>
					<input type="text" name="TXT_deposit_no" class="TXT_deposit_no hidden" value="">
				</div>
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">仮／確定</label>
					<div class="col-md-2">
						<label class="radio-inline">
							<input class="styled" name="RDI_inv_data_div" type="radio" value="0">
							仮
						</label>

						<label class="radio-inline">
							<input class="styled" name="RDI_inv_data_div" checked="checked" type="radio" value="1">
							確定
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">仮出荷指示No</label>
					<div class="col-md-2">
						@includeIf('popup.searchprovisionalshipment', array(
																	'class_cd'          => 'TXT_p_fwd_no',
																	'is_required' 		=> true,
																))
						<input type="text" class="TXT_fwd_status hidden" name="">
					</div>
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">仮受注No</label>
					<div class="col-md-2" style="margin-top: 5px;">
						<span class="DSP_p_rcv_no"></span>
						<input type="text" class="TXT_rcv_status hidden" name="">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">確定出荷指示No</label>
					<div class="col-md-2">
						
						@includeIf('popup.searchshipment', array(
															'val' 			=> 	isset($invoiceDetail) ? 'RT-170808123' : '',
															'class_cd'		=>	'TXT_fwd_no',
															'is_required'	=>	true
														))
					</div>
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">確定受注No</label>
					<div class="col-md-2" style="margin-top: 5px;">
						<span class="DSP_rcv_no"></span>
					</div>
				</div>
				<input type="text" class="TXT_warehouse_div hidden" name="">
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">Invoice Date</label>
					<div class="col-md-2 date">
						<input type="tel" class="form-control datepicker required TXT_invoice_date" value="{{date('Y/m/d')}}">
					</div>
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">売上計上日</label>
					<div class="col-md-1 date">
						<input type="tel" class="form-control datepicker required TXT_sales_date" value="{{date('Y/m/d')}}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">L/C No</label>
					<div class="col-md-2">
						<input type="text" class="form-control TXT_lc_no" maxlength="35" value="" style="width: 190px;">
					</div>
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Date of Shipment</label>
					<div class="col-md-1 date">
						<input type="tel" class="form-control datepicker TXT_date_of_shipment" value="">
					</div>
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">P/O No</label>
					<div class="col-md-2">
						<input type="tel" class="form-control TXT_po_no" maxlength="30">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">取引先</label>
					<div class="col-md-2 group-input-popup">
						@includeIf('popup.searchsuppliers', array(
																'class_cd' 		=> 'TXT_cust_cd',
																'is_required'	=>	true,
																'is_nm'			=>	false
															))
					</div>
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">Name</label>
					<div class="col-md-5">
						<input type="text" class="form-control required TXT_cust_nm" value="" maxlength="120">
					</div>
					<button type="button" class="btn btn-primary btn-icon" style="width: 80px;" id="show-address-to">
						住所表示
					</button>
				</div>
				<div class="form-group address-to hidden">
					<div class="col-md-4"></div>
					<div class="col-md-6 boder-address">				
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Address1</label>
						<div class="col-md-5">
							<input type="text" class="form-control TXT_cust_adr1" value="" maxlength="120">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Address2</label>
						<div class="col-md-5">
							<input type="text" class="form-control TXT_cust_adr2" value="" maxlength="120">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Zipcode</label>
						<div class="col-md-5">
							<input type="text" class="form-control TXT_cust_zip" value="" maxlength="8">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">City</label>
						<div class="col-md-2">
							@includeIf('popup.searchcity', array(
															'val' 		=> 	isset($invoiceDetail) ? '123456' : '',
															'class_cd'	=>	'TXT_cust_city_div',
															'class_nm'  => 	'DSP_cust_city_nm',
															'is_nm'		=>	true
														))
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Country</label>
						<div class="col-md-2">
							@includeIf('popup.searchcountry', array(
																'class_cd'		=>	'TXT_cust_country_div',
																'class_nm'     	=> 	'DSP_cust_country_nm',
																'is_nm'			=>	true
															))
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Tel</label>
						<div class="col-md-3">
							<input type="text" class="form-control TXT_cust_tel" value="" maxlength="20">
						</div>
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Fax</label>
						<div class="col-md-3">
							<input type="text" class="form-control TXT_cust_fax" value="" maxlength="20">
						</div>
					</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Consignee</label>
					<div class="col-md-2">
						@includeIf('popup.searchsuppliers', array(
															'class_cd' => 'TXT_consignee_cd',
															'is_nm'    => false
														))
					</div>
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Name</label>
					<div class="col-md-5">
						<input type="text" class="form-control TXT_consignee_nm" value="" maxlength="120">
					</div>
					<button type="button" class="btn btn-primary btn-icon" style="width: 80px;" id="show-address-from">
						住所表示
					</button>
				</div>
				<div class="form-group address-from hidden">
					<div class="col-md-4"></div>
					<div class="col-md-6 boder-address">
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Address1</label>
						<div class="col-md-5">
							<input type="text" class="form-control TXT_consignee_adr1" value="" maxlength="120">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Address2</label>
						<div class="col-md-5">
							<input type="text" class="form-control TXT_consignee_adr2" value="" maxlength="120">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Zipcode</label>
						<div class="col-md-5">
							<input type="text" class="form-control TXT_consignee_zip" value="" maxlength="8">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">City</label>
						<div class="col-md-2">
							@includeIf('popup.searchcity', array(
																'val'		=> 	isset($invoiceDetail) ? '123456' : '',
																'class_cd'	=>	'TXT_consignee_city_div',
																'class_nm'  => 	'DSP_consignee_city_nm',
																'is_nm'		=>	true
															))
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Country</label>
						<div class="col-md-2">
							@includeIf('popup.searchcountry', array(
																'val' 		=> 	isset($invoiceDetail) ? '123456' : '',
																'class_cd'	=>	'TXT_consignee_country_div',
																'class_nm'  => 	'DSP_consignee_country_nm',
																'is_nm'		=>	true
															))
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Tel</label>
						<div class="col-md-3">
							<input type="text" class="form-control TXT_consignee_tel" value="" maxlength="20">
						</div>
						<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Fax</label>
						<div class="col-md-3">
							<input type="text" class="form-control TXT_consignee_fax" value="" maxlength="20">
						</div>
					</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold">Shipping Mark</label>
					<div class="col-md-2">
						<span class="text-bg text-bold">1</span>
						<input type="text" name="TXT_shipping_mark_1" class="form-control TXT_shipping_mark_1" value="" maxlength="120" style="width: 120px; display: inline;">
					</div>
					<div class="col-md-2">
						<span class="text-bg text-bold">2</span>
						<input type="text" name="TXT_shipping_mark_2" class="form-control TXT_shipping_mark_2" value="" maxlength="120" style="width: 120px; display: inline;">
					</div>
					<div class="col-md-2">
						<span class="text-bg text-bold">3</span>
						<input type="text" name="TXT_shipping_mark_3" class="form-control TXT_shipping_mark_3" value="" maxlength="120" style="width: 120px; display: inline;">
					</div>
					<div class="col-md-2">
						<span class="text-bg text-bold">4</span>
						<input type="text" name="TXT_shipping_mark_4" class="form-control TXT_shipping_mark_4" value="" maxlength="120" style="width: 120px; display: inline;">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold required">Packing</label>
					<div class="col-md-3">
						<input type="text" class="form-control required TXT_packing" maxlength="32" value="">
					</div>
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold">Shipment</label>
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
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold">Currency</label>
					<div class="col-md-1">
						<select class="form-control currency_div CMB_currency_div" data-ini-target=true>
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
			<!-- </div> -->

				<div class="form-group">
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold required">Port of shipment</label>
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
					<label class="col-md-2 control-label text-right text-bold required">Trade Terms</label>
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

				<div class="form-group">
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold">Destination</label>
					<div class="col-md-4 group-input-popup" style="padding-right: 20%;">
						@includeIf('popup.searchcity', array(
															'val' 		=> 	isset($invoiceDetail) ? '123456' : '',
															'class_cd'	=>	'TXT_dest_city_div',
															'class_nm'  => 	'DSP_dest_city_nm',
														))
					</div>
					<div class="col-md-3 group-input-popup">
						@includeIf('popup.searchcountry', array(
															'val' 		=> 	isset($invoiceDetail) ? '123456' : '',
															'class_cd'	=>	'TXT_dest_country_div',
															'class_nm'  => 	'DSP_dest_country_nm',
														))
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold required">支払条件</label>
					<div class="col-md-4">
						<select class="form-control required payment_conditions_div CMB_payment_conditions_div" data-ini-target=true>
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
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">Payment</label>
					<div class="col-md-5">
						<textarea class="form-control required disable-resize TXT_payment_notes" maxlength="200"></textarea>
					</div>
				</div>
			<div class="panel-body">
				<div class="form-group clearfix" style="border-top: 1px dotted #ddd"></div>
				<div class="table-responsive sticky-table sticky-headers sticky-ltr-cells" id="div-table-invoice">
					@includeIf('invoice::InvoiceDetail.table_invoice')
				</div>
			
				<div class="form-group" style="margin-top: 5px;">
					<div class="col-md-6 table-responsive">
						<table class="table table-hover table-bordered table-xxs table-text" style="    background-color: #FFFFFF;">
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
									<td class="text-right" style="width: 100px;">
										<span class="DSP_total_qty"></span>
									</td>
									<td class="text-right" style="width: 100px;">
										<span class="DSP_total_gross_weight"></span>
									</td>
									<td class="" style="width: 100px;">
										<span class="DSP_unit_total_gross_weight_nm"></span>
										<span class="DSP_unit_total_gross_weight_div hidden"></span>
									</td>
									<td class="text-right" style="width: 100px;">
										<span class="DSP_total_net_weight"></span>
									</td>
									<td class="" style="width: 100px;">
										<span class="DSP_unit_total_net_weight_nm"></span>
										<span class="DSP_unit_total_net_weight_div hidden"></span>
									</td>
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
					<div class="col-md-3"></div>
					<div class="col-md-3 table-responsive">
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
										<input type="text" value="" class="form-control value-freight money hidden TXT_freight_amt" maxlength="15">
									</td>
								</tr>
								<tr class="">
									<td class="">
										<span class="title-insurance hidden text-bold">Insurance</span>
									</td>
									<td class="text-right">
										<input type="text" value="" class="form-control value-insurance money hidden TXT_insurance_amt" maxlength="15">
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
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<div class="col-md-1">
						<button type="button" class="btn btn-primary btn-icon" style="" id="show-table-carton">
							カートン表示
						</button>
					</div>
				</div>
			</div>
			<div class="panel-body table-carton-hidden hidden" id="table-carton-hidden">
				@includeIf('invoice::InvoiceDetail.table_carton')
			</div>
			<div class="panel-body area-bottom">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold">貯蔵管理責任者</label>
					<div class="col-md-3">
						<select class="form-control storage_manager_div CMB_storage_user_cd" data-ini-target=true>
						<option></option>
						@if(isset($storage_manager_div))
							@foreach($storage_manager_div as $k=>$v)
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
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold">Country of Origin</label>
					<div class="col-md-2">
						<input type="text" class="form-control TXT_country_of_origin" maxlength="32" value="">
					</div>
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold required">Manufacture</label>
					<div class="col-md-2">
						<input type="text" class="form-control required TXT_manufacture" maxlength="32" value="">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold">署名者</label>
					<div class="col-md-3">
						@includeIf('popup.searchuser', array(
															'val'		=>	$cre_user_cd,
															'class_nm'  => 	'DSP_sign_nm',
															'class_cd'	=>	'TXT_sign_cd'
														))
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold">社内用備考</label>
					<div class="col-md-9">
						<textarea class="form-control disable-resize TXA_inside_remarks" rows="2" maxlength="200"></textarea>
					</div>
				</div>
			</div>
		<!-- /search field -->
	</div>

@endsection

@section('content_hidden')
	<!-- table row invoice detail -->
	<table class="hide">
		<tbody id="table-row">
			<tr class="">
			<td class="drag-handler text-center DSP_inv_detail_no"></td>
			<td class="text-center">
				<button class="btn tab-top btn-popup-carton-item-set">セット</button>
			</td>
			<td class="text-center" style="vertical-align: initial;">
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
				<div class="boder-line"></div>
				<p class="DSP_remaining_qty" style="float:right; height: 15px;"></p>
			</td>
			<td class="text-center">
				<!-- <input type="text" class="form-control tab-top TXT_product_cd" value="" maxlength="6"> -->
				<div class="input-city-popup">
					@includeIf('popup.searchproduct', array(
														'class_tab' => 	'tab-top', 
														'is_nm' 	=> 	false,
														'class_cd'	=>	'TXT_product_cd'
													))
				</div>
				<div class="boder-line"></div>
				<p class="DSP_pi_no"></p>
			</td>
			<td class="text-center">
				<input type="text" class="form-control tab-top TXT_description" value="" maxlength="200">
				<div class="boder-line"></div>
				<input type="text" class="form-control tab-bottom TXT_outside_remarks" value="" >
			</td>
			<td class="text-center">
				<input type="text" class="form-control quantity tab-top TXT_qty" maxlength="6" value="">
				<div class="boder-line"></div>
				<input type="text" class="form-control weight tab-bottom TXT_unit_net_weight" maxlength="9" value="">
			</td>
			<td class="text-center">
				<select class="form-control tab-top unit_q_div CMB_unit_of_m_div" data-ini-target=true>
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
			<td class="text-center">
				<input type="text" class="form-control price tab-top TXT_unit_price"  maxlength="12" value="">
				<div class="boder-line"></div>
				<input type="text" class="form-control weight tab-bottom TXT_net_weight" maxlength="20" value="">
			</td>
			<td class="text-center">
				<input type="text" class="form-control money tab-top TXT_amount" maxlength="20" value="">
				<div class="boder-line"></div>
				<input type="text" class="form-control weight tab-bottom TXT_unit_gross_weight" maxlength="9" value="">
			</td>
			<td class="text-center">
				<input type="text" class="form-control measure tab-top TXT_unit_measure_qty"  maxlength="7" value="">
				<div class="boder-line"></div>
				<input type="text" class="form-control weight tab-bottom TXT_gross_weight" maxlength="20" value="">
			</td>
			<td class="text-center">
				<select class="form-control tab-top unit_m_div CMB_unit_measure_price" data-ini-target=true>
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
				<input type="text" class="form-control measure tab-bottom TXT_measure" maxlength="20" value="">
			</td>
		</tr>
		</tbody>
	</table>
	<!--/table row -->

	<!-- table row carton detail -->
	<table class="hide">
		<tbody id="table-row-carton">
			<tr class="">
				<td class="drag-handler text-center DSP_inv_carton_detail_no"></td>
				<td class="text-center">
					<input type="tel" class="form-control numeric TXT_carton_number required_carton" id="control-number" value="" maxlength="6">
					<span class="DSP_fwd_detail_no_table_carton hidden"></span>
				</td>
				<td class="text-left">
					<div class="tooltip-overflow max-width20 DSP_product_nm_table_carton" data-toggle="tooltip" data-placement="top" title=""></div>
					<input type="text" class="TXT_product_cd_table_carton hidden" name="DSP_product_cd_table_carton">
				</td>
				<td class="text-center">
					<input type="text" class="form-control quantity TXT_qty_table_carton required_carton" value="" maxlength="6">
				</td>
				<td class="text-center">
					<select class="form-control unit_w_div CMB_unit_net_weight_div_table_carton" data-ini-target=true>
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
					<input type="text" class="form-control weight TXT_unit_net_weight_table_carton" value="" maxlength="9">
				</td>
				<td class="text-right DSP_total_net_weight_table_carton"></td>
				<td class="text-center">
					<input type="text" class="form-control weight TXT_unit_gross_weight_table_carton" value="" maxlength="9">
				</td>
				<td class="text-right DSP_total_gross_weight_table_carton"></td>
				<td class="text-center">
					<select class="form-control unit_m_div CMB_unit_measure_table_carton" data-ini-target=true>
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
				<td class="text-center">
					<input type="text" class="form-control measure TXT_unit_measure_table_carton" value="" maxlength="7">
				</td>
				<td class="text-right DSP_total_measure_table_carton"></td>
				<td class="w-40px text-center">
					<button type="button" class="form-control remove-row" id="remove-row">
						<span class="icon-cross2 text-danger"></span>
					</button>
				</td>
			</tr>
		</tbody>
	</table>
	<!--/table row -->
@endsection