@extends('layouts.main')

@section('title')
	@lang('title.stocking-check-outside-ordered-products')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-save'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/stocking/css/check_outside_ordered_products.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/stocking/js/check_outside_ordered_products.js')!!}
@endsection

@section('content')
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.stocking-check-outside-ordered-products')</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">製造指示書発行日</label>
					<div class="col-md-6 date-from-to">
						<input type="text" class="form-control number-date" style="display: inline;" value="">
						<input value="" type="tel" class="datepicker form-control date-from">
						
						<span class="">～</span>
		
						<input type="text" class="form-control number-date" style="display: inline;" value="">
						<input value="" type="tel" class="datepicker form-control date-to">
					</div>
				</div>
			</div>
		</div>
		@includeIf('stocking::CheckOutsideOrderedProducts.list', ['paginate' => $paginate, 'fillter' => $fillter])
@endsection