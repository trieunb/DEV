@extends('layouts.main')

@section('title')
	@lang('title.component-master-detail')
@endsection

@section('button')
	@if ($mode == 'U')
		{{Button::button_left(array('btn-back', 'btn-save','btn-delete'))}}
	@else
		{{Button::button_left(array('btn-back', 'btn-save'))}}
	@endif
@endsection

@section('stylesheet')
	{!! public_url('modules/master/css/component_master_detail.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/master/js/component_master_detail.js')!!}
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
				<h5 class="panel-title text-bold">@lang('title.component-master-detail')</h5>
				<div id="operator_info">
					{!! infoMemberCreUp('', '', '', '') !!}
				</div>
			</div>
			<div class="panel-body">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">部品コード</label>
					<div class="col-md-1" style="min-width: 115px;">
						@includeIf('popup.searchcomponent', array(
							'id' 			=> 'TXT_parts_cd', 
							'val' 			=> $component_id, 
							'is_required' 	=> true
						))
					</div>
					<div class="col-md-6" style="margin-top: 3px">
						<span style="max-width:500px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: block;">
							<span id="label_parts">{{!empty($lastestComponent['parts_cd']) ? '最終登録部品：' : ''}}</span>
							<span id='lastest_parts_cd'>{{!empty($lastestComponent['parts_cd']) ? $lastestComponent['parts_cd'] : ''}}</span>
							<span>&nbsp;&nbsp;&nbsp;</span>	
							<span id='lastest_item_nm_j'>{{!empty($lastestComponent['item_nm_j']) ? $lastestComponent['item_nm_j'] : ''}}</span>
						</span>
					</div>
				</div>
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">部品名和文</label>
					<div class="col-md-5">
						<input type="text" id="TXT_part_nm_j" name="TXT_part_nm_j" class="form-control required" value="" maxlength="120">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">部品名英文</label>
					<div class="col-md-5">
						<input type="text" id="TXT_part_nm_e"  name="TXT_part_nm_e" class="form-control required" value="" maxlength="120">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">規格名</label>
					<div class="col-md-5">
						<input type="text" id="TXT_specification" name="TXT_specification" class="form-control" value="" maxlength="120">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">単位</label>
					<div class="col-md-1">
						<select class="form-control unit_q_div CMB_unit" data-ini-target=true id="CMB_unit" name="CMB_unit">
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
					<label class="col-md-1 col-md-1-cus control-label text-left" id="lbl_CMB_unit_e"></label>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">入数</label>
					<div class="col-md-1">
						<input type="text" id="TXT_contained_qty" name="TXT_contained_qty" class="form-control quantity" value="" maxlength="8">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">分類</label>
					<div class="col-md-1">
						<select class="form-control parts_kind_div CMB_classification required" data-ini-target=true id="CMB_classification" name="CMB_classification">
							<option></option>
							@if(isset($parts_kind_div))
								@foreach($parts_kind_div as $k=>$v)
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
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">在庫管理有無</label>
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
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">管理方法</label>
					<div class="col-md-2">
						<select class="form-control parts_order_div CMB_management_method" data-ini-target=true id="CMB_management_method" name="CMB_management_method">
							<option></option>
							@if(isset($parts_order_div))
								@foreach($parts_order_div as $k=>$v)
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
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">発注点</label>
					<div class="col-md-1">
						<input type="text" id="TXT_order_point_qty" name="TXT_order_point_qty" class="form-control quantity" value="" maxlength="6">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">EOQ</label>
					<div class="col-md-1">
						<input type="text" id="TXT_economic_order_qty" name="TXT_economic_order_qty" class="form-control quantity" value="" maxlength="6">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">発注レベル</label>
					<div class="col-md-2">
						<select class="form-control order_level_div CMB_order_level" data-ini-target=true id="CMB_order_level" name="CMB_order_level">
							<option></option>
							@if(isset($order_level_div))
								@foreach($order_level_div as $k=>$v)
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
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">備考</label>
					<div class="col-md-10">
						<textarea class="form-control disable-resize" rows="2" id="TXT_remarks" name="TXT_remarks" maxlength="200"></textarea>
					</div>
				</div>
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="table-responsive sticky-table sticky-headers sticky-ltr-cells" style="max-height: 300px;" id="div-table-purchase-price">
					@includeIf('master::ComponentMasterDetail.table')
				</div>
			</div>
	</div>
@endsection

@section('content_hidden')
	<!-- table row -->
	<table class="hide">
		<tbody id="table-row">
			<tr class="">
				<td class="drag-handler text-center">
					<label class="radio-inline" style="margin-top: -20px;">
						<input class="RDI_main styled" name="RDI_main" type="radio">
					</label>
				</td>
				<td class="text-center">
					@includeIf('popup.searchsuppliers', array(
						'class_cd'    => 'TXT_purchaser_order_cd',
						'is_required' => true,
						'is_nm'       => false
					))
				</td>
				<td class="text-left">
					<div class="tooltip-overflow max-width20 DSP_purchaser_order_nm" data-toggle="tooltip" data-placement="top" title=""></div>
				</td>
				<td class="text-left">
					<input type="text" class="form-control price TXT_standard_unit_price_JPY currency_JPY" value="" maxlength="11" real_len="10" decimal_len="2">
				</td>
				<td class="text-center">
					<input type="text" class="form-control price TXT_standard_unit_price_USD" value="" maxlength="11" real_len="10" decimal_len="2">
				</td>
				<td class="text-center text-right">
					<input type="text" class="form-control price TXT_standard_unit_price_EUR" value="" maxlength="11" real_len="10" decimal_len="2">
				</td>
				<td class="text-right">
					<input type="text" class="form-control quantity TXT_order_lot_size" value="" maxlength="6">
				</td>
				<td class="text-center">
					<input type="text" class="form-control quantity TXT_lower_limit_lot_size" value="" maxlength="6">
				</td>
				<td class="text-center">
					<input type="text" class="form-control quantity TXT_maximum_lot_size" value="" maxlength="6">
				</td>
				<td class="w-40px text-center">
					<button type="button" class="form-control remove-row BTN_Delete_line">
						<span class="icon-cross2 text-danger"></span>
					</button>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="hide">
		<tbody id="table-row-empty">
			<tr id="row-empty">
				<td colspan="10" class="text-center dataTables_empty">&nbsp;</td>
			</tr>
		</tbody>
	</table>
@endsection