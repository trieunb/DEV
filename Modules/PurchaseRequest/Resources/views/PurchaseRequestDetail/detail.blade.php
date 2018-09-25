@extends('layouts.main')

@section('title')
	購入依頼書入力
@endsection

@section('button')
	{{Button::button_left(array('btn-back', 'btn-save','btn-delete', 'btn-approve', 'btn-issue'), $mode)}}
@endsection

@section('stylesheet')
	{!! public_url('modules/purchase/css/purchase_request_detail.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/purchase/js/purchase_request_detail.js')!!}
@endsection

@section('content')
	<script>
		var mode = "{{$mode}}";
		var from = "{{$from}}";
		var screenID = "purchase-request-detail";
		var isCheckedSuppliersPopup = "true";
	</script>
	<span class="hidden tax_rate"></span>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">購入依頼書入力</h5>
				<div class="infor-created">
					<!-- @includeIf('layouts._operator_info') -->
				</div>
			</div>
			<div class="panel-body">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">注文番号</label>
					<div class="col-md-2">
						@includeIf('popup.searchpurchaserequest', array(
																	'class_cd' 		=> 	'TXT_buy_no',
																	'val' 			=> 	isset($buy_no) ? $buy_no : '',
																	'is_required'	=>	true,
																	'is_nm'			=>	false
																))
					</div>
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">依頼日</label>
					<div class="col-md-1 date">
						<input type="tel" name="TXT_buy_date" class="form-control datepicker hasDatepicker required TXT_buy_date" value="{{date('Y/m/d')}}">
					</div>
					<label class="col-md-3 col-md-3-cus control-label text-right" style="float: right;">
						<span class="text-bold">ステータス</span>
						<span class="DSP_status"></span>
						<input type="text" class="hidden TXT_buy_status" name="TXT_buy_status" value="">
						<span class="DSP_buy_status_cre_datetime"></span>
					</label>
				</div>
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">仕入先コード</label>
					<div class="col-md-2">
						@include('popup.searchsuppliers', array(
															'class_cd' 		=> 'TXT_supplier_cd',
															'is_required'	=>	true
														))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">仕入先担当者名</label>
					<div class="col-md-2">
						<input type="text" class="form-control TXT_supplier_staff_nm" value="" maxlength="50">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">件名</label>
					<div class="col-md-2">
						<input type="text" name="TXT_subject_nm" class="form-control TXT_subject_nm" value="" maxlength="50">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">希望納期</label>
					<div class="col-md-1 date">
						<input type="tel" class="form-control datepicker hasDatepicker TXT_hope_delivery_date" value="">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">備考</label>
					<div class="col-md-4">
						<textarea class="form-control TXT_remarks disable-resize" rows="2" id="TXT_remarks" name="TXT_remarks" maxlength="200"></textarea>
					</div>
				</div>
				<div class="table-responsive sticky-table sticky-headers sticky-ltr-cells" id="div-purchase-request">
					@includeIf('purchaserequest::PurchaseRequestDetail.purchase_request_table')
				</div>
				<div class="table-responsive" style="max-height: 300px;">
					<table class="table table-total" style="border-top: none;">
						<tbody>
							<tr>
								<td colspan="7" class="text-right text-bold">小計</td>
								<td class="text-right DSP_total_detail_amt" style="width: 150px"></td>
								<td class="" style="width: 150px"></td>
								<td class="" style="width: 40px"></td>
							</tr>
							<tr>
								<td colspan="7" class="text-right text-bold">消費税</td>
								<td class="text-right DSP_tax_amt" style="width: 150px"></td>
								<td class="" style="width: 150px"></td>
								<td class="" style="width: 40px"></td>
							</tr>
							<tr>
								<td colspan="7" class="text-right text-bold">合計</td>
								<td class="text-right DSP_total_amt" style="width: 150px"></td>
								<td class="" style="width: 150px"></td>
								<td class="" style="width: 40px"></td>
							</tr>
						</tbody>
					</table>
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
				<td class="drag-handler text-center DSP_buy_detail_no"></td>
				<td class="text-center" style="width: 170px;">
					@includeIf('popup.searchcomponent', array(
															'class_cd' 		=> 'TXT_parts_cd',
															'is_required'	=>	true
														))
				</td>
				<td class="text-left DSP_item_nm_j">
					<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title=""></div>
				</td>
				<td class="text-left DSP_specification">
					<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title=""></div>
				</td>
				<td class="text-center">
					<input type="text" class="form-control money numeric required TXT_buy_qty" value="" maxlength="6">
				</td>
				<td class="text-center text-right DSP_unit">
				</td>
				<td class="text-center">
					<input type="text" class="form-control money numeric TXT_buy_unit_price" value="" maxlength="12">
				</td>
				<td class="text-center">
					<input type="text" class="form-control money TXT_buy_detail_amt" disabled="true" value="" maxlength="12">
					<input type="text" class="hidden form-control money TXT_buy_detail_tax" value="" maxlength="12">
				</td>
				<td class="text-center">
					<input type="text" class="form-control TXT_detail_remarks" value="" maxlength="">
				</td>
				<td class="w-40px text-center">
					<button type="button" class="form-control remove-row">
						<span class="icon-cross2 text-danger"></span>
					</button>
				</td>
			</tr>
		</tbody>
	</table>
	<!--/table row -->

@endsection