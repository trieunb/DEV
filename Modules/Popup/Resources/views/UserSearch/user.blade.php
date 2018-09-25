@extends('layouts.popup')

@section('title')
	@lang('title.user-master-search')
@endsection

@section('stylesheet')	
	{!! public_url('modules/popup/css/search_user.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/popup/js/search_user.js')!!}

@endsection

@section('content')
	<!-- Main content -->
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.user-master-search')</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">@lang('label.user_cd')</label>
					<div class="col-md-3">
						<input type="text" id="TXT_user_cd" class="form-control TXT_user_cd" name="TXT_user_cd" maxlength="20">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">@lang('label.user_nm_j')</label>
					<div class="col-md-5">
						<input type="text" id="TXT_user_nm_j" class="form-control TXT_user_nm_j" name="TXT_user_nm_j" maxlength="50">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">@lang('label.user_nm_e')</label>
					<div class="col-md-5">
						<input type="text" id="TXT_user_nm_e" class="form-control TXT_user_nm_e" name="TXT_user_nm_e" maxlength="50">
					</div>
					<div class="col-md-2 text-right pull-right">
						<button type="button" id="btn-search-popup" class="btn btn-primary btn-icon w-60px">
							<i class="icon-search4"></i>
						</button>
					</div>
					
				</div>
			</div>
		</div><!--/.panel-body -->
			<!-- Both borders -->
		<div id="user_list">
			@includeIf('popup::UserSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
			<!-- /both borders -->
	</div><!--/.panel -->
	<!-- /main content -->
@endsection