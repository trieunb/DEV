<div class="input-group popup" data-id="workingtime_cd" data-nm="workingtime_nm" data-search="workingtime" data-istable="{{ $istable or 0}}" data-multi="{{ $multi or 0 }}">
	<input {{isset($is_disabled) && $is_disabled ?'readonly':''}} type="text" class="form-control left-radius right-radius refer-search workingtime_cd {{isset($class_tab)?$class_tab:''}} {{isset($is_required) && $is_required ?'required':''}}" value="{{ $val or '' }}" id="{{ $id or '' }}" maxlength="12">
	<span class="input-group-btn">
		<button {{isset($is_disabled) && $is_disabled ?'disabled':''}} type="button" class="btn btn-primary btn-icon btn-search {{isset($class_tab)?$class_tab:''}}"><i class="icon-search4"></i></button>
	</span>
	<!-- <span class="input-group-text text-overfollow m-w-popup-label refer-search workingtime_nm"></span> -->
	@if(!isset($is_nm) || (isset($is_nm) && $is_nm != false))
	<span class="input-group-text text-overfollow m-w-popup-label refer-search workingtime_nm"></span>
	@endif
</div>