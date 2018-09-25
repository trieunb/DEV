@extends('layouts.main')

@section('title')
	@lang('title.user-master-search')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-add-new', 'btn-export'))}}
@endsection

@section('stylesheet')	
	{!! public_url('modules/master/css/user_master_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/master/js/user_master_search.js')!!}

@endsection

@section('content')
	<?php $to_url = explode('/', Request::url());?>
	<!-- Main content -->
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="<?php echo end($to_url).'_content'; ?>">
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
						<div class="col-md-2">
							<input type="text" id="TXT_user_cd" class="form-control TXT_user_cd" name="TXT_user_cd" maxlength="20">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label text-right text-bold">@lang('label.user_nm_j')</label>
						<div class="col-md-3">
							<input type="text" id="TXT_user_nm_j" class="form-control TXT_user_nm_j" name="TXT_user_nm_j" maxlength="50">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label text-right text-bold">@lang('label.user_nm_e')</label>
						<div class="col-md-3">
							<input type="text" id="TXT_user_nm_e" class="form-control TXT_user_nm_e" name="TXT_user_nm_e" maxlength="50">
						</div>
						
					</div>
				</div>
			</div><!--/.panel-body -->
		</div>
			<!-- Both borders -->
			
			<!-- /both borders -->
		</div><!--/.panel -->
		<!-- /search field -->
		<div id="user_list">
			@includeIf('master::UserMasterSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
@endsection


		