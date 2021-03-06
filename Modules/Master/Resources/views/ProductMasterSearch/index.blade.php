@extends('layouts.main')

@section('title')
	@lang('title.product-master-search')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-add-new', 'btn-export'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/master/css/product_master_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/master/js/product_master_search.js')!!}
@endsection

@section('content')
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.product-master-search')</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">製品名</label>
					<div class="col-md-5">
						<input type="text" id="TXT_product_nm" name="TXT_product_nm" class="form-control TXT_product_nm" maxlength="120">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">規格名</label>
					<div class="col-md-5">
						<input type="text" id="TXT_specification" name="TXT_specification" class="form-control TXT_specification" maxlength="120">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-2-cus control-label text-right text-bold">製品コード</label>
					<div class="col-md-1">
						<input type="text" id="TXT_product_cd" name="TXT_product_cd" class="form-control TXT_product_cd" maxlength="6">
					</div>
				</div>
			</div>
		</div>
		<div id="div-product-list">
			@includeIf('master::ProductMasterSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
@endsection