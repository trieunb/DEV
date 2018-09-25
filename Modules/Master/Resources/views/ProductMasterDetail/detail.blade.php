@extends('layouts.main')

@section('title')
	@lang('title.product-master-detail')
@endsection

@section('button')
	@if ($mode == 'U')
		{{Button::button_left(array('btn-back', 'btn-save', 'btn-delete'))}}
	@else
		{{Button::button_left(array('btn-back', 'btn-save'))}}
	@endif
@endsection

@section('stylesheet')
	{!! public_url('modules/master/css/product_master_detail.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/master/js/product_master_detail.js')!!}
@endsection


@section('content')
	<script>
		var mode 	= "{{$mode}}";
		var from 	= "{{$from}}";
		var is_new 	= "{{$is_new}}";
	</script>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.product-master-detail')</h5>
				<div id="operator_info">
					{!! infoMemberCreUp('', '', '', '') !!}
				</div>
			</div>
			<div class="panel-body">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold required">製品コード</label>
					<div class="col-md-1">
						@includeIf('popup.searchproduct', array(
							'id'          => 'TXT_product_cd',
							'is_required' => true,
							'val'         => $product_cd,
							'is_nm'		  => false
						))
					</div>
				</div>
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold required">製品名和文</label>
					<div class="col-md-5">
						<input type="text" id="TXT_product_nm_j" name="TXT_product_nm_j" class="form-control required TXT_product_nm_j" value="" maxlength="120">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold required">製品名英文</label>
					<div class="col-md-5">
						<input type="text" id="TXT_product_nm_e" name="TXT_product_nm_e" class="form-control required TXT_product_nm_e" value="" maxlength="120">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">規格名</label>
					<div class="col-md-5">
						<input type="text" id="TXT_specification" name="TXT_specification" class="form-control TXT_specification" value="" maxlength="120">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">単位</label>
					<div class="col-md-1">
						<select class="form-control unit_q_div CMB_unit" id="CMB_unit" data-ini-target=true name="CMB_unit">
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
					</div>
					<label class="col-md-1 control-label text-left" id="lbl_CMB_unit_e"></label>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">内製／外注</label>
					<div class="col-md-1">
						<select class="form-control outsourcing_div CMB_internal_manufacturing_outsource" data-ini-target=true id="CMB_internal_manufacturing_outsource" name="CMB_internal_manufacturing_outsource">
							<option></option>
							@if(isset($outsourcing_div))
								@foreach($outsourcing_div as $k=>$v)
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
					<label class="col-md-2 control-label text-right text-bold required">在庫管理有無</label>
					<div class="col-md-1">
						<select class="form-control exists_div CMB_inventory_control required" data-ini-target=true id="CMB_inventory_control" name="CMB_inventory_control">
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
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold required">シリアル管理</label>
					<div class="col-md-2">
						@if (isset($dataSerialMnagement))
							@for ($i = count($dataSerialMnagement) - 1; $i >= 0; $i--)
								<label class="radio-inline">
									<input class="styled" name="RDI_serial_management" type="radio" value="{{$dataSerialMnagement[$i]['lib_val_cd']}}"
										@if ($dataSerialMnagement[$i]['ini_target_div'] == '1')
											checked 
										@elseif ($dataSerialMnagement[$i]['lib_val_cd'] == '1')
											checked
										@endif
									>{{$dataSerialMnagement[$i]['lib_val_nm_j']}}
								</label>
							@endfor
						@endif
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">最終シリアル番号</label>
					<label class="col-md-2 control-label text-left DSP_last_serial_no" id="DSP_last_serial_no" name="DSP_last_serial_no"></label>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">JANコード</label>
					<div class="col-md-2">
						<input type="text" id="TXT_jan_code" name="TXT_jan_code" class="form-control TXT_jan_code" value="" maxlength="16">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">Net Weight</label>
					<div class="col-md-1">
						<input type="tel" id="TXT_net_weight" name="TXT_net_weight" class="form-control weight TXT_net_weight" value="" maxlength="9" real_len="5" decimal_len="2">
					</div>
					<span class="text-input-left text-bold span-cus">単位</span>
					<div class="col-md-1">
						<select class="form-control unit_w_div CMB_nw_unit" id="CMB_nw_unit" data-ini-target=true name="CMB_nw_unit">
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
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">Gross Weight</label>
					<div class="col-md-1">
						<input type="tel" id="TXT_gross_weight" name="TXT_gross_weight" class="form-control weight TXT_gross_weight" value="" maxlength="9" real_len="5" decimal_len="2">
					</div>
					<span class="text-input-left text-bold span-cus">単位</span>
					<div class="col-md-1">
						<select class="form-control unit_w_div CMB_gw_unit" id="CMB_gw_unit" data-ini-target=true name="CMB_gw_unit">
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
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">Measurement</label>
					<div class="col-md-1">
						<input type="tel" id="TXT_measurement" name="TXT_measurement" class="form-control measure TXT_measurement" value="" maxlength="7" real_len="3" decimal_len="2">
					</div>
					<span class="text-input-left text-bold span-cus">単位</span>
					<div class="col-md-1">
						<select class="form-control unit_m_div CMB_measurement_unit" data-ini-target=true id="CMB_measurement_unit" name="CMB_measurement_unit">
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
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">備考</label>
					<div class="col-md-10">
						<!-- <input type="text" id="TXT_remarks" name="TXT_remarks" class="form-control TXT_remarks" value="" maxlength="200"> -->
						<textarea class="form-control TXT_remarks disable-resize" rows="2" id="TXT_remarks" name="TXT_remarks" maxlength="200" value=""></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection