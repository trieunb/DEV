@extends('layouts.main')

@section('title')
	@lang('title.selling-unit-price-by-client-detaill')
@endsection

@section('button')
	{{Button::button_left(array('btn-back', 'btn-save','btn-delete'), $mode)}}
@endsection

@section('stylesheet')
	{!! public_url('modules/master/css/selling_unit_price_by_client_detail.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/master/js/selling_unit_price_by_client_detail.js')!!}
@endsection


@section('content')
	<script>
		var mode 		= "{{$mode}}";
		var from 		= "{{$from}}";
		var is_new 		= "{{$is_new}}";
		var _productCd 	= "{{$product_cd}}";
		var _clientCd 	= "{{$client_cd}}";
	</script>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.selling-unit-price-by-client-detaill')</h5>
				<div id="operator_info">
					{!! infoMemberCreUp('', '', '', '') !!}
				</div>
			</div>
			<div class="panel-body">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">製品コード</label>
					<div class="col-md-1">
						@includeIf('popup.searchproduct', array('val'=>isset($product_cd) ? $product_cd : '', 'key' => $product_cd, 'is_required'=>'required'))
					</div>
					<!-- <label class="col-md-2 control-label text-left">製品名</label> -->
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">標準単価</label>
					<div class="col-md-1">
						<label class="checkbox-inline">
							<input type="checkbox" id="CHK_standard_unit_price" name="">&nbsp;
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">取引先コード</label>
					<div class="col-md-1">
						@includeIf('popup.searchsuppliers', array('val'=>isset($client_cd) ? $client_cd : '', 'key' => $client_cd, 'is_required'=>'required'))
					</div>
					<!-- <label class="col-md-2 control-label text-left">取引先名</label> -->
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">開始日</label>
					<div class="col-md-2 date">
						<input type="tel" class="datepicker form-control required" id="TXT_start_date" value="{{$apply_st_date or ''}}">
					</div>
				</div>
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">単価&nbsp;(JPY)</label>
					<div class="col-md-2">
						<input type="text" id="TXT_unit_price_JPY" class="form-control money currency_JPY" maxlength="11" real_len="10" decimal_len="2">
					</div>
					<label class="col-md-4 control-label" style="padding-left: 100px;">
						<span class="text-bold">標準単価&nbsp;(JPY)</span>
						<span id="DSP_standard_unit_price_JPY" class="money"></span>
					</label>
					<label class="col-md-2 control-label">
						<span class="text-bold">掛率</span>
						<span id="DSP_markup_ratio"></span>
					</label>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">単価&nbsp;(USD)</label>
					<div class="col-md-2">
						<input type="text" id="TXT_unit_price_USD" class="form-control money" maxlength="11" real_len="10" decimal_len="2">
					</div>
					<label class="col-md-4 control-label" style="padding-left: 100px;">
						<span class="text-bold">標準単価&nbsp;(USD)</span>
						<span id="DSP_standard_unit_price_USD" class="money"></span>
					</label>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">単価&nbsp;(EUR)</label>
					<div class="col-md-2">
						<input type="text" id="TXT_unit_price_EUR" class="form-control money" maxlength="11" real_len="10" decimal_len="2">
					</div>
					<label class="col-md-4 control-label" style="padding-left: 100px;">
						<span class="text-bold">標準単価&nbsp;(EUR)</span>
						<span id="DSP_standard_unit_price_EUR" class="money"></span>
					</label>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">備考</label>
					<div class="col-md-10">
						<textarea class="form-control disable-resize" id="TXA_remarks" rows="2"></textarea>
					</div>
				</div>
			</div>
	</div>
@endsection