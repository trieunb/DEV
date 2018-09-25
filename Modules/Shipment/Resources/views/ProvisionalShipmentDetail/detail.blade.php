@extends('layouts.main')

@section('title')
	@lang('title.provisional-shipment-detail')
@endsection

@section('button')
	{{Button::button_left(array('btn-back', 'btn-save','btn-delete', 'btn-issue'), $mode)}}
@endsection

@section('stylesheet')
	{!! public_url('modules/shipment/css/provisional_shipment_detail.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/shipment/js/provisional_shipment_detail.js')!!}
@endsection


@section('content')
	<script>
		var mode = "{{$mode}}"
		var from = "{{$from}}"
		var isCheckAllShipment = 1
	</script>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.provisional-shipment-detail')</h5>
				<div id="operator_info">
					{!! infoMemberCreUp('', '', '', '') !!}
				</div>	
			</div>
			<div class="panel-body">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">仮出荷指示No</label>
					<div class="col-md-3">
						@includeIf('popup.searchprovisionalshipment', array(
							'id'          => 'TXT_fwd_no',
							'is_required' => false,
							'is_nm'		  => false,
							'val' 		  => isset($shipment_no) ? $shipment_no : ''))
					</div>
					<label class="col-md-4 col-md-3-cus control-label text-right" style="float: right;">
						<span class="text-bold hide" id="STT">ステータス</span>
						<span class="DSP_status" id="DSP_status"></span>
						<span class="DSP_status_tm" id="DSP_status_tm"></span>
					</label>
				</div>

				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>

				<div class="form-group hide">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">出荷区分</label>
					<div class="col-md-2">
						<select class="form-control forwarding_div CMB_forwarding_div" id="CMB_forwarding_div" name="CMB_forwarding_div" data-ini-target=true>
							<option></option>
							@if(isset($forwarding_div))
								@foreach($forwarding_div as $k=>$v)
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
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">引渡予定日</label>
					<div class="col-md-2">
						<input type="tel" class="form-control datepicker TXT_deliver_date" id="TXT_deliver_date" maxlength="10" name="TXT_deliver_date" value="">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">受注No</label>
					<div class="col-md-2">
						@includeIf('popup.searchaccept', array(
							'id'          => 'TXT_rcv_no',
							'is_required' => true,
							'is_nm'		  => false))
					</div>
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">Pi NO</label>
					<div class="col-md-2">
						@includeIf('popup.searchpi', array(
							'class_cd'    => 'TXT_pi_no',
							'is_nm'		  => false))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required DSP_cust_cd" id="DSP_cust_cd">取引先</label>
					<label class="col-md-5 col-md-5-cus control-label text-left DSP_cust_nm" id="DSP_cust_nm">{{isset($shipmentDetail) ? '' : ''}}</label>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required DSP_dest_country_div" id="DSP_dest_country_div">仕向地国名</label>
					<label class="col-md-2 col-md-1-cus control-label text-left DSP_dest_country_nm" id="DSP_dest_country_nm">{{isset($shipmentDetail) ? '' : ''}}</label>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required DSP_dest_city_div" id="DSP_dest_city_div">仕向地都市名</label>
					<label class="col-md-2 col-md-1-cus control-label text-left DSP_dest_city_nm" id="DSP_dest_city_nm">{{isset($shipmentDetail) ? '' : ''}}</label>
				</div>

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">発送手段</label>
					<div class="col-md-4">
						<select class="form-control forwarding_way_div CMB_forwarding_way_div required" id="CMB_forwarding_way_div" name="CMB_forwarding_way_div" data-ini-target=true>
							<option></option>
							@if(isset($forwarding_way_div))
								@foreach($forwarding_way_div as $k=>$v)
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
					<label class="col-md-1 control-label text-right text-bold">その他</label>
					<div class="col-md-2">
						<input type="text" maxlength="80" id="TXT_forwarding_way_remarks" class="form-control TXT_forwarding_way_remarks" name="TXT_forwarding_way_remarks" value="{{isset($shipmentDetail) ? 'その他' : ''}}" readonly="">
					</div>
					<label class="col-md-1 control-label text-right text-bold">出荷先</label>
					<div class="col-md-2 date">
						<select class="form-control forwarding_warehouse_div CMB_forwarding_dest_div" id="CMB_forwarding_dest_div" name="CMB_forwarding_dest_div" data-ini-target=true>
							<option></option>
							@if(isset($forwarding_warehouse_div))
								@foreach($forwarding_warehouse_div as $k=>$v)
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
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">通関業者(乙仲)</label>
					<div class="col-md-4">
						<select class="form-control forwarder_div CMB_forwarder_div" id="CMB_forwarder_div" name="CMB_forwarder_div" data-ini-target=true>
							<option></option>
							@if(isset($forwarder_div))
								@foreach($forwarder_div as $k=>$v)
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
					<label class="col-md-1 control-label text-right text-bold">その他</label>
					<div class="col-md-2">
						<input type="text" maxlength="80" id="TXT_forwarder_remarks" name="TXT_forwarder_remarks" class="form-control" value="{{isset($shipmentDetail) ? 'その他' : ''}}" readonly="">
					</div>
					<label class="col-md-3 control-label text-left">※輸出港以外に出荷の場合、預け先倉庫を指定</label>
				</div>

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">梱包方法</label>
					<div class="col-md-4">
						<select class="form-control packing_method_div CMB_packing_method_div" id="CMB_packing_method_div" name="CMB_packing_method_div" data-ini-target=true>
							<option></option>
							@if(isset($packing_method_div))
								@foreach($packing_method_div as $k=>$v)
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
					<label class="col-md-1 control-label text-right text-bold">その他</label>
					<div class="col-md-2">
						<input type="text" maxlength="80" id="TXT_packing_method_remarks" name="TXT_packing_method_remarks" class="form-control" value="{{isset($shipmentDetail) ? 'その他' : ''}}" readonly="">
					</div>
					<!-- <label class="col-md-1 control-label text-right text-bold required">確認事項</label>
					<div class="col-md-2 popup">
						<label class="checkbox-inline">
							<input type="checkbox" class="required" id="CHK_confirmation_div" name="CHK_confirmation_div" style="">
							a~h
						</label>
						@includeIf('popup.checklist', array())
					</div> -->
				</div>
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="table-responsive sticky-table sticky-headers sticky-ltr-cells">
					@includeIf('shipment::ProvisionalShipmentDetail.table')
				</div>
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">社内用備考</label>
					<div class="col-md-9">
						<textarea maxlength="200" class="form-control remark TXT_inside_remarks" id="TXT_inside_remarks" name="TXT_inside_remarks" rows=2></textarea>
					</div>
				</div>
			</div>
		<!-- /search field -->
	</div>

@endsection

@section('content_hidden')
	
	<!-- table row -->
	<table class="hide">
		<tbody id="table-row">
			<tr class="">
				<td class="drag-handler text-center DSP_no">
				</td>
				<td class="text-right DSP_code">
				</td>
				<td class="text-left DSP_product_nm">
				</td>
				<td class="text-right DSP_rcv_amount">
				</td>
				<td class="text-right DSP_remain_amount">
				</td>
				<td class="text-center">
					<input type="text" id="" class="form-control TXT_instructed_amount required quantity" value="">
				</td>
				<td class="text-center DSP_remaining"></td>
				<td class="text-center BTN_clear">
					<button type="button" class="form-control remove-row" id="remove-row">
						<span class="icon-cross2 text-danger"></span>
					</button>
				</td>
			</tr>
		</tbody>
	</table>
	<!--/table row -->

@endsection