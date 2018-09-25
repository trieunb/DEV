<div class="input-group popup" data-id="inputoutput_cd" data-nm="inputoutput_nm" data-search="inputoutput" data-istable="{{ $istable or 0}}" data-multi="{{ $multi or 0 }}">
	<input {{isset($is_disabled) && $is_disabled ?'readonly':''}} type="text" 
	class="form-control left-radius right-radius refer-search inputoutput_cd 
		{{isset($class_cd)?$class_cd:''}} 
		{{$required or ''}} 
		{{isset($class_tab)?$class_tab:''}} 
		{{isset($is_required) && $is_required ?'required':''}}" 
	value="{{ $val or '' }}"
	maxlength="14"
	id="{{ $id or '' }}">
	<span class="input-group-btn">
		<button {{isset($is_disabled) && $is_disabled ?'disabled':''}} type="button" class="btn btn-primary btn-icon btn-search {{isset($class_tab)?$class_tab:''}}"><i class="icon-search4"></i></button>
	</span>
	<!-- <span class="input-group-text text-overfollow m-w-popup-label refer-search inputoutput_nm"></span> -->
	@if(!isset($is_nm) || (isset($is_nm) && $is_nm != false))
	<span class="input-group-text text-overfollow m-w-popup-label refer-search inputoutput_nm"></span>
	@endif
</div>