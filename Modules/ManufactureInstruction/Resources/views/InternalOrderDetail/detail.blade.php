@extends('layouts.main')

@section('title')
	社内発注書入力
@endsection

@section('button')
	{{Button::button_left(array('btn-back', 'btn-save','btn-delete', 'btn-issue'), $mode)}}
@endsection

@section('stylesheet')
	{!! public_url('modules/manufactureinstruction/css/internal_order_detail.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/manufactureinstruction/js/internal_order_detail.js')!!}
@endsection

@section('content')
	<script>
		var mode = "{{$mode}}";
		var from = "{{$from}}";
	</script>
	<div class="row form-horizontal content">
		
		<!-- Search field -->
			<div class="panel panel-flat">
				<div class="panel-heading header">
					<h5 class="panel-title text-bold">社内発注書入力</h5>
					<div id="operator_info">
						<!-- {!! infoMemberCreUp('', '', '', '') !!} -->
					</div>
				</div>

				<div class="panel-body">
					<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
					<div class="form-group">
						<!-- Item - required -->
						<label class="col-md-1 col-md-1-cus control-label text-right required" style="font-weight: bold;">社内発注書番号</label>
						<div class="col-md-1">
							@includeIf('popup.searchinternalorder', array(
											'id'			=> 'TXT_internalorder_cd',
											'class_cd' 		=> 'TXT_internalorder_cd',
											'disabled_ime'	=> 'disabled-ime',
											'val' 			=> isset($internal_order_no) ? $internal_order_no : ''))
						</div>
					</div>
					<div class="form-group clearfix" style="border-top: 1px solid #ddd;"></div>
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr class="">
									<th class="" style="border: none;"><span style="font-weight: bold; font-size: 15px;">【製品情報】</span></th>								
									<th class="text-center" style="border: none;"></th>
									<th class="text-center" style="border: none;"></th>
									<th class="text-center" style="border: none;">
									</th>
									<th class="text-center" style="border: none;">
										<input type="tel" class="form-control datepicker DSP_hope_delivery_date_header" value="" id="deliver_hope_date">
									</th>
									<th class="" style="border: none;">
										<button type="button" class="btn btn-primary btn-icon BTN_set_hope_delivery_date" style="width: 110px;" id="btn-set-date">
											希望納期セット
										</button>
									</th>
									<th style="border: none;"></th>
								</tr>
							</thead>
						</table>
					</div>
					<div class="table_content">
						@includeIf('manufactureinstruction::InternalOrderDetail.table')
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
				<td class="text-center DSP_in_order_detail_no" style="display: none;"></td>
				<td class="drag-handler text-center DSP_disp_order"></td>
				<td class="text-center" style="width: 120px;">
					@include('popup.searchproduct', array(
														'is_nm' 		=> false,
														'is_required' 	=> true,
														))
				</td>
				<td class="text-center">
					<select class="form-control manufacture_kind_div CMB_manufacture_kind_div" data-ini-target=true>
					<option></option>
					@if(isset($manufacture_kind_div))
						@foreach($manufacture_kind_div as $k=>$v)
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
				<td class="text-left">
					<div class="tooltip-overflow max-width20 product_nm DSP_product_nm" style="max-width: 20px;" data-toggle="tooltip" data-placement="top" title=""></div>
				</td>
				<td class="text-center">
					<input type="text" class="form-control money TXT_in_order_qty required numeric" real_len="8" value="">
				</td>
				<td class="text-center date">
					<input type="tel" class="form-control datepicker TXT_hope_delivery_date hasDatepicker" value="">
				</td>
				<td class="text-center">
					<input type="text" class="form-control TXT_detail_remarks" value="" maxlength="200">
				</td>
				<td class="text-right TXT_sum_manufacture_qty">
					0
				</td>
				<td class="w-40px text-center">
					<button type="button" class="form-control remove-row" tabindex="">
						<span class="icon-cross2 text-danger"></span>
					</button>
				</td>
			</tr>
		</tbody>
	</table>
	<!--/table row -->

@endsection