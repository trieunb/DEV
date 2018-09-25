@extends('layouts.popup')

@section('title')
	製品・部品一覧
@endsection

@section('stylesheet')
	{!! public_url('modules/popup/css/component_product_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/popup/js/component_product_search.js')!!}
@endsection

@section('content')

	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">製品・部品一覧</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold">名称</label>
					<div class="col-md-2">
						<input type="text" id="" class="form-control TXT_item_nm" maxlength="120" value="">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold">規格名</label>
					<div class="col-md-2">
						<input type="text" id="" class="form-control TXT_specification" maxlength="120" value="">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold">製品・部品</label>
					<div class="col-md-4">
						<label class="checkbox-inline">
							<input type="checkbox" class="product" name="product" value="1">製品
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" class="component" name="component" value="1">部品
						</label>
					</div>
				</div>
					<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">品目コード</label>
					<div class="col-md-1">
						<input type="text" id="TXT_item_cd" name="TXT_item_cd" class="form-control TXT_item_cd" maxlength="6">
					</div>
					<div class="col-md-2 text-right pull-right">
						<button type="button" id="btn-search-popup" class="btn btn-primary btn-icon w-60px">
							<i class="icon-search4"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /search field -->
		<!-- List PI -->
		<div id="component-product-list">
		@includeIf('popup::ComponentProductSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
	<!-- </div> -->
@endsection