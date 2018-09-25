<div class="input-group popup" data-id="deposit_cd" data-nm="deposit_nm" data-search="deposit" data-istable="" data-multi="">
	<input {{isset($is_disabled) && $is_disabled ?'readonly':''}} 
		type="text" 
		name="{{ isset($class_cd)?$class_cd:'' }}" 
		class="form-control left-radius right-radius refer-search deposit_cd 
			{{$required or ''}} 
			{{isset($is_required) && $is_required ?'required':''}} 
			{{isset($class_tab)?$class_tab:''}} 
			{{isset($class_cd)?$class_cd:''}}"
		value="{{ $val or '' }}" 
		maxlength="10" 
		id="{{ $id or '' }}">
	<span class="input-group-btn">
		<button {{isset($is_disabled) && $is_disabled ?'disabled':''}} type="button" class="btn btn-primary btn-icon btn-search {{isset($class_tab)?$class_tab:''}}"><i class="icon-search4"></i></button>
	</span>

	@if(!isset($is_nm) || (isset($is_nm) && $is_nm != false))
	<span class="input-group-text text-overfollow m-w-popup-label refer-search deposit_nm"></span>
	@endif
</div>
