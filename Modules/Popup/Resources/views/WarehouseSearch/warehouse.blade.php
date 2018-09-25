@extends('layouts.popup')

@section('title')
	倉庫検索
@endsection

@section('stylesheet')	
	{!! public_url('modules/popup/css/search_warehouse.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/popup/js/search_warehouse.js')!!}
@endsection

@section('content')
	<!-- Main content -->
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">倉庫検索</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 control-label text-right">コード</label>
					<div class="col-md-3">
						<input type="text" class="form-control" name="" id="lib_cd" maxlength="10" tabindex="1">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 control-label text-right">名称</label>
					<div class="col-md-3">
						<input type="text" class="form-control" name="" id="lib_nm" maxlength="100" tabindex="2">
					</div>
					<div class="col-md-2 text-right pull-right">
						@if (isset($data["multi"]))
						<button type="button" id="btn-select" class="btn btn-primary btn-icon w-60px">
							選択
						</button>
						@endif
						<button type="button" id="btn-search-popup" class="btn btn-primary btn-icon w-60px" tabindex="3">
							<i class="icon-search4"></i>
						</button>
					</div>
					
				</div>
					
			</div>
		</div>
			<div id="warehouse_list">
				@includeIf('popup::WarehouseSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
			</div>			
		</div>
	</div>
@endsection

