@extends('layouts.main')

@section('title')
	@lang('title.stocking-detail')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-save','btn-export'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/stocking/css/stocking_detail.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/stocking/js/stocking_detail.js')!!}
@endsection

@section('content')
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.stocking-detail')</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">部品コード</label>
					<div class="col-md-2 col-sm-3">
						@include('popup.searchcomponentproduct', array(
												'class_cd' 		=> 'TXT_parts_cd',
												'is_required' 	=> false,
												'disabled_ime'	=> 'disabled-ime',
												'is_nm'    		=> true))
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">仕入先名</label>
					<div class="col-md-2 col-sm-3">
						<input type="text" class="form-control TXT_supplier_nm" name="TXT_supplier_nm" maxlength="120">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">発注書番号</label>
					<div class="col-md-2">
						<input type="text" class="form-control TXT_parts_order_no" name="TXT_parts_order_no" maxlength="14">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">製造指示書番号</label>
					<div class="col-md-2">
						<input type="text" class="form-control TXT_manufacture_no" name="TXT_manufacture_no" maxlength="8">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">発注書発行日</label>
					<div class="col-md-6 date-from-to">
						<input type="text" name="TXT_parts_order_date_no_from" class="form-control number-date TXT_parts_order_date_no_from disabled-ime" style="display: inline;" maxlength="3">
						<input type="tel" name="TXT_parts_order_date_from" class="datepicker form-control date-from TXT_parts_order_date_from disabled-ime" maxlength="10">
						
						<span class="">～</span>
		
						<input type="text" name="TXT_parts_order_date_no_to" class="form-control number-date TXT_parts_order_date_no_to disabled-ime" style="display: inline;" maxlength="3">
						<input type="tel" name="TXT_parts_order_date_to" class="datepicker form-control date-to TXT_parts_order_date_to disabled-ime" maxlength="10">
					</div>
				</div>
			</div>
		</div>
		
		<input type="hidden" id="purchase_detail_amt_round_div"	value="{{ $purchase_detail_amt_round_div or 1 }}">
		<div id="div-stock-list">
			@includeIf('stocking::StockingDetail.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
@endsection