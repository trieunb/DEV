<div class="input-group popup" data-id="componentproduct_cd" data-nm="componentproduct_nm" data-search="componentproduct" data-istable="{{ $istable or 0}}" data-multi="{{ $multi or 0 }}">
	<input {{isset($is_disabled) && $is_disabled ?'readonly':''}} type="text" 
		name="{{ isset($class_cd)?$class_cd:'' }}"
		class="form-control left-radius right-radius refer-search componentproduct_cd 
			{{isset($class_cd)?$class_cd:''}} 
			{{isset($class_tab)?$class_tab:''}} 
			{{isset($is_required) && $is_required ?'required':''}} 
			{{$disabled_ime or ''}}" 
		value="{{ $val or '' }}" 
		id="{{ $id or '' }}" 
		maxlength="6">
	<span class="input-group-btn">
		<button {{isset($is_disabled) && $is_disabled ?'disabled':''}} type="button" class="btn btn-primary btn-icon btn-search {{isset($class_tab)?$class_tab:''}}"><i class="icon-search4"></i></button>
	</span>
	@if(!isset($is_nm) || (isset($is_nm) && $is_nm != false))
	<div id="contain-name">
		<span class="text-overfollow refer-search componentproduct_nm"></span>
		<span class="text-overfollow refer-search specification"></span>
	</div>
	@endif
</div>