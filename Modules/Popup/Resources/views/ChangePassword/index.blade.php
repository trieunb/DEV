@extends('layouts.popup')

@section('title')
	パスワードの変更
@endsection

@section('stylesheet')	
	{!! public_url('modules/popup/css/change_password.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/popup/js/change_password.js')!!}
@endsection

@section('content')
	<!-- Main content -->
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">パスワードの変更</h5>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-4 control-label text-left text-bold no-padding-left required">新パスワード</label>
				</div>
				<div class="form-group">
					<input type="password" class="form-control text-left TXT_password required" maxlength="20" autocomplete="new-password" placeholder="新パスワードを入力してください。">
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label text-left text-bold no-padding-left required">新パスワード（確認用)</label>
				</div>
				<div class="form-group">
					<input type="password" class="form-control text-left TXT_confirm_password required" maxlength="20" autocomplete="new-password" placeholder="確認パスワードを入力してください。">
				</div>
				<div class="form-group">
					<div class="col-xs-6 col-md-6">
						<button type="button" id="btn-pass-ok" class="btn btn-primary btn-icon" style="width: 100%">変更</button>
					</div>
					<div class="col-xs-6 ">
						<button type="button" id="btn-pass-cancel" class="btn btn-primary btn-icon" style="width: 100%">戻る</button>
					</div>
				</div>
			</div>
		</div><!--/.panel-body -->
		</div><!--/.panel -->
	</div><!--/.row -->
	<!-- /main content -->
@endsection

