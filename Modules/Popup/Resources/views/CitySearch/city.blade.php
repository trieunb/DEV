@extends('layouts.popup')

@section('title')
	City検索
@endsection

@section('stylesheet')	
	{!! public_url('modules/popup/css/search_city.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/popup/js/search_city.js')!!}

@endsection

@section('content')
	<div id="btnid" class="hidden" btnid="{{ $data["btnid"] or ''}}"></div>
	<div id="istable" class="hidden" istable="{{ $data["istable"] or '' }}"></div>
	<div id="puredata" class="hidden" puredata="{{ $data["puredata"] or '' }}"></div>
	<div id="multi" class="hidden" multi="{{ $data["multi"] or '' }}"></div>
	<!-- Main content -->
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">City検索</h5>
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
						<input type="text" id="lib_cd" class="form-control" name="" maxlength="10">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 control-label text-right">名称</label>
					<div class="col-md-3">
						<input type="text" id="lib_nm" class="form-control" name="" maxlength="100">
					</div>
					<div class="col-md-2 text-right pull-right">
						@if (isset($data["multi"]))
						<button type="button" id="btn-select" class="btn btn-primary btn-icon w-60px">
							選択
						</button>
						@endif
						<button type="button" id="btn-search-popup" class="btn btn-primary btn-icon w-60px">
							<i class="icon-search4"></i>
						</button>
					</div>
				</div>
			</div>
		</div><!--/.panel-body -->

		<div id="city_list">
			@includeIf('popup::CitySearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
	</div>
	<!-- /main content -->
@endsection

