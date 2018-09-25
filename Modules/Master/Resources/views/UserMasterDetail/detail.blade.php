@extends('layouts.main')

@section('title')
	@lang('title.user-master-detail')
@endsection

@section('button')
	{{Button::button_left(array('btn-back', 'btn-save','btn-delete'),$mode)}}
@endsection

@section('stylesheet')
	{!! public_url('modules/master/css/user_master_detail.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/master/js/user_master_detail.js')!!}
@endsection


@section('content')
	<script>
		var mode 	=	"{{$mode}}";
		var from 	=	"{{$from}}";
		var is_new 	=	"{{$is_new}}";
	</script>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.user-master-detail')</h5>
				<div id="operator_info">
					{!! infoMemberCreUp('', '', '', '') !!}
				</div>
			</div>
			<div class="panel-body">
				<div class="form-group clearfix"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold required">@lang('label.user_cd')</label>
					<div class="col-md-2">
						@includeIf('popup.searchuser', array('val'=>isset($userCd) ? $userCd : '', 'is_nm'=>false, 'required'=>'required'))
					</div>
				</div>
				<div class="form-group clearfix"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold required">@lang('label.user_nm_j')</label>
					<div class="col-md-3">
						<input type="text" id="TXT_user_nm_j" name="TXT_user_nm_j" class="form-control required" value="{{$user['user_nm_j'] or ''}}" maxlength="50">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold required">@lang('label.user_ab_j')</label>
					<div class="col-md-3">
						<input type="text" id="TXT_user_ab_j" name="TXT_user_ab_j" class="form-control required" value="{{$user['user_ab_j'] or ''}}" maxlength="20">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold required">@lang('label.user_nm_e')</label>
					<div class="col-md-3">
						<input type="text" id="TXT_user_nm_e" name="TXT_user_nm_e" class="form-control required" value="{{$user['user_nm_e'] or ''}}" maxlength="50">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold required">@lang('label.user_ab_e')</label>
					<div class="col-md-3">
						<input type="text" id="TXT_user_ab_e" name="TXT_user_ab_e" class="form-control required" value="{{$user['user_ab_e'] or ''}}" maxlength="20">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold required">@lang('label.pwd')</label>
					<div class="col-md-2">
						<input type="password" id="TXT_pwd" name="TXT_pwd" autocomplete="new-password" class="form-control required" value="{{$user['pwd'] or ''}}" maxlength="20">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold required">@lang('label.belong_div')</label>
					<div class="col-md-2">
						<select class="form-control belong_div required" data-ini-target=true name="CMB_belong_div" id="CMB_belong_div">
							<option></option>
							@if(isset($belong_div))
								@foreach($belong_div as $k=>$v)
									<option value="{{$v['lib_val_cd']}}" 
											data-ctl1="{{$v['lib_val_ctl1']}}" data-ctl2="{{$v['lib_val_ctl2']}}"
											data-ctl5="{{$v['lib_val_ctl5']}}" data-ctl6="{{$v['lib_val_ctl6']}}"
											data-nm-j="{{$v['lib_val_nm_j']}}" data-nm-e="{{$v['lib_val_nm_e']}}"
											data-ini_target_div="{{$v['ini_target_div']}}" @if($v['ini_target_div']=='1') selected @endif>
										{{$v['lib_val_nm_j']}} 
									</option>
								@endforeach
							@endif
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold required">@lang('label.position_div')</label>
					<div class="col-md-2">
						<select class="form-control position_div required" data-ini-target=true name="CMB_position_div" id="CMB_position_div">
							<option></option>
							@if(isset($position_div))
								@foreach($position_div as $k=>$v)
									<option value="{{$v['lib_val_cd']}}" 
											data-ctl1="{{$v['lib_val_ctl1']}}" data-ctl2="{{$v['lib_val_ctl2']}}"
											data-ctl5="{{$v['lib_val_ctl5']}}" data-ctl6="{{$v['lib_val_ctl6']}}"
											data-nm-j="{{$v['lib_val_nm_j']}}" data-nm-e="{{$v['lib_val_nm_e']}}"
											data-ini_target_div="{{$v['ini_target_div']}}" @if($v['ini_target_div']=='1') selected @endif>
										{{$v['lib_val_nm_j']}} 
									</option>
								@endforeach
							@endif
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold required">@lang('label.auth_role_div')</label>
					<div class="col-md-2">
						<select class="form-control auth_role_div required" data-ini-target=true name="CMB_auth_role_div" id="CMB_auth_role_div">
							<option></option>
							@if(isset($auth_role_div))
								@foreach($auth_role_div as $k=>$v)
									<option value="{{$v['lib_val_cd']}}" 
											data-ctl1="{{$v['lib_val_ctl1']}}" data-ctl2="{{$v['lib_val_ctl2']}}"
											data-ctl5="{{$v['lib_val_ctl5']}}" data-ctl6="{{$v['lib_val_ctl6']}}"
											data-nm-j="{{$v['lib_val_nm_j']}}" data-nm-e="{{$v['lib_val_nm_e']}}"
											data-ini_target_div="{{$v['ini_target_div']}}" @if($v['ini_target_div']=='1') selected @endif>
										{{$v['lib_val_nm_j']}} 
									</option>
								@endforeach
							@endif
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold required">@lang('label.incumbent_div')</label>
					<div class="col-md-1">
						<select class="form-control incumbent_div required" data-ini-target=true name="CMB_incumbent_div" id="CMB_incumbent_div">
							<option></option>
							@if(isset($incumbent_div))
								@foreach($incumbent_div as $k=>$v)
									<option value="{{$v['lib_val_cd']}}" 
											data-ctl1="{{$v['lib_val_ctl1']}}" data-ctl2="{{$v['lib_val_ctl2']}}"
											data-ctl5="{{$v['lib_val_ctl5']}}" data-ctl6="{{$v['lib_val_ctl6']}}"
											data-nm-j="{{$v['lib_val_nm_j']}}" data-nm-e="{{$v['lib_val_nm_e']}}"
											data-ini_target_div="{{$v['ini_target_div']}}" @if($v['ini_target_div']=='1') selected @endif>
										{{$v['lib_val_nm_j']}} 
									</option>
								@endforeach
							@endif
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">@lang('label.pwd_upd_datetime')</label>
					<div class="col-md-2">
						<input type="text" id="TXT_pwd_upd_datetime" name="TXT_pwd_upd_datetime" class="form-control" value="{{$user['pwd_upd_datetime'] or ''}}" readonly="readonly" maxlength="50">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">@lang('label.login_datetime')</label>
					<div class="col-md-2">
						<input type="text" id="TXT_login_datetime" name="TXT_login_datetime" class="form-control" value="{{$user['login_datetime'] or ''}}" readonly="readonly" maxlength="50">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">@lang('label.memo')</label>
					<div class="col-md-10">
						<textarea class="form-control disable-resize" id="TXA_memo" maxlength="200" name="TXA_memo" rows="2" tabindex="27">{{$user['memo'] or ''}}</textarea>
					</div>
				</div>
			</div>
	</div>
@endsection