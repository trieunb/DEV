@extends('layouts.popup')

@section('title')
	@lang('title.stock-manage-search')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-add-new', 'btn-export'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/popup/css/stockmanage_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/popup/js/stockmanage_search.js')!!}
@endsection


@section('content')
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.stock-manage-search')</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">入出庫日</label>
					<div class="col-md-6 date-from-to">
						<input type="text" class="form-control number-date" style="display: inline;" value="">
						<input value="" type="tel" class="datepicker form-control date-from">
						
						<span class="">～</span>
		
						<input type="text" class="form-control number-date" style="display: inline;" value="">
						<input value="" type="tel" class="datepicker form-control date-to">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">入出庫No</label>
					<div class="col-md-2">
						<input type="text" class="form-control" name="" value="textbox">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">部品コード</label>
					<div class="col-md-2">
						@includeIf('popup.searchcomponentproduct', array('val'=>'textbox'))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">倉庫</label>
					<div class="col-md-2">
						@includeIf('popup.searchwarehouse', array('val'=>'textbox'))
						<!-- <span class="text-right-input">本社</span> -->
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">入出庫区分</label>
					<div class="col-md-2">
						<select class="form-control" id="test-select">
							<option value=""></option>
							<option value="4" selected>text</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">入力種別</label>
					<div class="col-md-2">
						<select class="form-control" id="test-select">
							<option value=""></option>
							<option value="4" selected>text</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		@includeIf('popup::StockManageSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
	</div>
@endsection


