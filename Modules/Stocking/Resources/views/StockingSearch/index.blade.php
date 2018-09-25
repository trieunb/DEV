@extends('layouts.main')

@section('title')
	仕入一覧
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-export'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/stocking/css/stocking_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/stocking/js/stocking_search.js')!!}
@endsection

@section('content')
	<script>
		var isCheckedSuppliersPopup = "true";
		var isCheckedOutsourcerPopup = "true";
	</script>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">仕入一覧</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>

			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>

				<div class="form-group">
					<label class="col-md-1 control-label text-right text-bold min-width120">部品発注日</label>
					<div class="col-md-6 date-from-to date-order">
						<input type="text" name="TXT_parts_order_date_no_from" class="TXT_parts_order_date_no_from form-control number-date" style="display: inline;">
						<input type="tel" name="TXT_parts_order_date_from" class="TXT_parts_order_date_from datepicker form-control date-from">
						
						<span class="">～</span>
		
						<input type="text" name="TXT_parts_order_date_no_to" class="TXT_parts_order_date_no_to form-control number-date" style="display: inline;">
						<input type="tel" name="TXT_parts_order_date_to" class="TXT_parts_order_date_to datepicker form-control date-to">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 control-label text-right text-bold min-width120">仕入先</label>
					<div class="col-md-1">
						@include('popup.searchsuppliers', array(
												'class_cd' 		=> 'TXT_supplier_cd',
												'is_required' 	=> false,
												'disabled_ime'	=> 'disabled-ime',
												'is_nm'    		=> true))
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 control-label text-right text-bold min-width120">部品</label>
					<div class="col-md-1">
						@includeIf('popup.searchcomponentproduct', array(
																	'class_cd'	=>	'TXT_parts_cd',
																))
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 control-label text-right text-bold min-width120">仕入日</label>
					<div class="col-md-6 date-from-to date-purchase">
						<input type="text" name="TXT_purchase_date_no_from" class="TXT_purchase_date_no_from form-control number-date" style="display: inline;">
						<input type="tel" name="TXT_purchase_date_from" class="TXT_purchase_date_from datepicker form-control date-from">
						
						<span class="">～</span>
		
						<input type="text" name="TXT_purchase_date_no_to" class="TXT_purchase_date_no_to form-control number-date" style="display: inline;">
						<input type="tel" name="TXT_purchase_date_to" class="TXT_purchase_date_to datepicker form-control date-to">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold min-width120">部品発注番号</label>
					<div class="col-md-2">					
						@includeIf('popup.searchorder', array(
																	'class_cd'	=>	'TXT_parts_order_no',
																))
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold min-width120">製造指示番号</label>
					<div class="col-md-2">
						@includeIf('popup.searchmanufacturinginstruction', array(
																	'class_cd'	=>	'TXT_manufacture_no',
																))
					</div>
				</div>
			</div>
		</div>
		<div id="stocking-search-list">
			@includeIf('stocking::StockingSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
@endsection