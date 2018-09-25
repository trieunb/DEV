<div class="input-group popup" data-id="city_cd" data-nm="city_nm" data-search="city" data-istable="{{$istable or 0}}" data-multi="{{$multi or 0}}">
	<input {{isset($is_disabled) && $is_disabled ?'readonly':''}} type="text" name="{{isset($class_cd)?$class_cd:''}}" 
		class="form-control left-radius right-radius refer-search city_cd 
			{{isset($class_cd)?$class_cd:''}} 
			{{isset($class_tab)?$class_tab:''}} 
			{{isset($is_required) && $is_required ?'required':''}} 
			{{$disabled_ime or ''}}" 
		value="{{$val or ''}}"
	 	id="{{$id or ''}}" 
	 	maxlength="3">
	<span class="input-group-btn">
		<button {{isset($is_disabled) && $is_disabled ?'disabled':''}} type="button" class="btn btn-primary btn-icon btn-search {{isset($class_tab)?$class_tab:''}}"><i class="icon-search4"></i></button>
	</span>
	@if(!isset($is_nm) || (isset($is_nm) && $is_nm != false))
	<span class="input-group-text text-overfollow m-w-popup-label refer-search city_nm {{ isset($class_nm)?$class_nm:'' }}"></span>
	@endif
</div>