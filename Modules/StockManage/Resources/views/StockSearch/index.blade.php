@extends('layouts.main')

@section('title')
	@lang('title.stock-manage-search-stock')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-export'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/stockmanage/css/stock_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/stockmanage/js/stock_search.js')!!}
@endsection


@section('content')
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.stock-manage-search-stock')</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">品目コード</label>
					<div class="col-md-2">
						@includeIf('popup.searchcomponentproduct', array('id'=> 'TXT_item_cd', 'is_nm'=> true))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">倉庫</label>
					<div class="col-md-2">
						@includeIf('popup.searchwarehouse', array('id'=> 'TXT_warehouse_cd', 'is_nm'=> true, 'maxlength'=> 6))
					</div>
				</div>
			</div>
		</div>
		<div id="stock_list">
			@includeIf('stockmanage::StockSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
	</div>
@endsection


