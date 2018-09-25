@extends('layouts.main')

@section('title')
	@lang('title.stock-manage-detail')
@endsection

@section('button')
	{{Button::button_left(array('btn-back', 'btn-save'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/stockmanage/css/input_output_detail.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/stockmanage/js/input_output_detail.js')!!}
@endsection

@section('content')
	<script>
		var mode = "{{$mode}}";
		var from = "{{$from}}";
	</script>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.stock-manage-detail')</h5>
				<div class="infor-created"></div>
			</div>
			<div class="panel-body input-output-header">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">入出庫No</label>
					<div class="col-md-3">
						@includeIf('popup.searchinputoutput',
								    array(
								    		'class_cd'		=> "TXT_in_out_no",
								    		'val' 			=> 	isset($in_out_no) ? $in_out_no : '',
											'is_required'	=>	true,
											'is_nm'			=>	false
								        ))
					</div>
				</div>
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">入出庫区分</label>
					<div class="col-md-2">
						<select name="CMB_in_out_div" class="form-control CMB_in_out_div in_out_div required" id="in_out_div">
							<option></option>
							@if(isset($in_out_div))
								@foreach($in_out_div as $k=>$v)
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
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">入出庫日</label>
					<div class="col-md-2 date">
						<input value="{{date('Y/m/d')}}" name="TXT_in_out_date" type="tel" class="datepicker TXT_in_out_date form-control required">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">入力種別</label>
					<div class="col-md-2">
						<select name="CMB_in_out_data_div" class="form-control CMB_in_out_data_div in_out_data_div required" id="test-select">
							<option></option>
							@if(isset($in_out_data_div))
								@foreach($in_out_data_div as $k=>$v)
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
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">倉庫</label>
					<div class="col-md-2">
						@includeIf('popup.searchwarehouse', array(
																'class_cd'		=>	'TXT_warehouse_div',
																'is_nm'			=>	true,
																'is_required'	=>	true
															))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">備考</label>
					<div class="col-md-10">
						<textarea class="form-control TXT_remarks disable-resize" rows="2" id="TXT_remarks" name="TXT_remarks" maxlength="200"></textarea>
					</div>
				</div>
			</div>
			<div class="panel-body input-output-detail">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="table-responsive sticky-table sticky-headers sticky-ltr-cells" id="div-input-output" style="max-height: 300px;">
					@includeIf('stockmanage::InputOutputDetail.input_output_table')
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
				<td class="drag-handler text-center DSP_in_out_detail_no"></td>
				<td class="text-center" style="width: 120px;">
					@includeIf('popup.searchcomponentproduct', array(
																'class_cd'		=>	'TXT_item_cd',
																'is_nm'			=>	false,
																'is_required'	=>	true,
															))
				</td>
				<td class="text-left">
					<div class="tooltip-overflow max-width20 DSP_item_nm" data-toggle="tooltip" data-placement="top" title=""></div>
				</td>
				<td class="text-left">
					<div class="tooltip-overflow max-width20 DSP_specification" data-toggle="tooltip" data-placement="top" title=""></div>
				</td>
				<td class="text-left">
					<input type="text" name="TXT_serial_no" class="form-control TXT_serial_no" value="" maxlength="7">
					<span class="hide DSP_serial_management_div"></span>
					<span class="hide DSP_stock_management_div"></span>
				</td>
				<td class="text-center">
					<input type="text" name="TXT_in_out_qty" class="form-control quantity TXT_in_out_qty required"  value="" maxlength="8">
				</td>
				<td class="text-center">
					<input type="text" name="TXT_detail_remarks" class="form-control TXT_detail_remarks" value="" maxlength="200">
				</td>
				<td class="w-40px text-center">
					<button type="button" class="form-control remove-row">
						<span class="icon-cross2 text-danger"></span>
					</button>
				</td>
			</tr>
		</tbody>
	</table>
@endsection