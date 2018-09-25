@extends('layouts.main')

@section('title')
	@lang('title.system-management-authority')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-save', 'btn-upload', 'btn-export'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/systemmanagement/css/authority.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/systemmanagement/js/authority.js')!!}
@endsection

@section('content')
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.system-management-authority')</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold required">権限ロール</label>
					<div class="col-md-2">
						<select class="form-control auth_role_div required" id="CMB_auth_role_div">
							<option></option>
							@if(isset($auth_role_div))
								@foreach($auth_role_div as $k=>$v)
									<option value="{{$v['lib_val_cd']}}" 
											data-ctl1="{{$v['lib_val_ctl1']}}" data-ctl2="{{$v['lib_val_ctl2']}}"
											data-ctl5="{{$v['lib_val_ctl5']}}" data-ctl6="{{$v['lib_val_ctl6']}}"
											data-nm-j="{{$v['lib_val_nm_j']}}" data-nm-e="{{$v['lib_val_nm_e']}}"
											data-ini_target_div="{{$v['ini_target_div']}}">
										{{$v['lib_val_nm_j']}} 
									</option>
								@endforeach
							@endif
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">画面名</label>
					<div class="col-md-2">
						<input type="text" class="form-control" id="TXT_prg_nm" maxlength="100">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">機能名</label>
					<div class="col-md-2">
						<input type="text" class="form-control" id="TXT_fnc_nm" maxlength="30">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">利用可否</label>
					<div class="col-md-6">
						<label class="radio-inline">
							<input class="styled" name="CHK_fnc_use_div_header" type="radio" data-fnc_use_div="1">
							 利用可
						</label>

						<label class="radio-inline">
							<input class="styled" name="CHK_fnc_use_div_header" type="radio" data-fnc_use_div="0">
							利用不可
						</label>

						<label class="radio-inline">
							<input class="styled" name="CHK_fnc_use_div_header" type="radio" data-fnc_use_div="2">
							未設定
						</label>

						<label class="radio-inline">
							<input class="styled" name="CHK_fnc_use_div_header" type="radio" data-fnc_use_div="3" checked="checked">
							全て
						</label>
					</div>
				</div>
			</div>
		</div>
		<div id="div-authority-list">
			@includeIf('systemmanagement::authority.list')
		</div>
	</div>
	<input type="file" name="upload-csv" class="hidden" id="upload-csv">
@endsection