@extends('layouts.main')

@section('title')
	@lang('title.order-confirm')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-save'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/order/css/order_confirm.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/order/js/order_confirm.js')!!}
@endsection

@section('content')
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.order-confirm')</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group order-confirm-date">
					<label class="col-md-1 control-label text-right text-bold">見積日</label>
					<div class="col-md-6 date-from-to">
						<input type="text" name="TXT_pi_date_no_from" class="form-control number-date TXT_pi_date_no_from" style="display: inline;" value="">
						<input value="" type="tel" name="TXT_pi_date_from" class="datepicker form-control date-from TXT_pi_date_from">
						<span class="">～</span>
						<input type="text" name="TXT_pi_date_no_to" class="form-control number-date TXT_pi_date_no_to" style="display: inline;" value="">
						<input value="" type="tel" name="TXT_pi_date_to" class="datepicker form-control date-to TXT_pi_date_to">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">取引先名</label>
					<div class="col-md-3">
						<input type="text" name="TXT_cust_nm" class="form-control TXT_cust_nm" value="" maxlength="120">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">PI No</label>
					<div class="col-md-2">
						<input type="text" name="TXT_pi_no" class="form-control TXT_pi_no" value="" maxlength="12">
					</div>
				</div>
			</div>
		</div>
		<div id="order-confirm-list">
		@includeIf('order::OrderConfirm.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
@endsection