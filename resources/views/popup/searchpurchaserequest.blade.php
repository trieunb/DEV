<div class="input-group popup" data-id="purchaserequest_cd" data-nm="purchaserequest_nm" data-search="purchaserequest" data-istable="{{ $istable or 0}}" data-multi="{{ $multi or 0 }}">
	<input {{isset($is_disabled) && $is_disabled ?'readonly':''}} type="text" 
		class="form-control left-radius right-radius refer-search purchaserequest_cd
			{{isset($class_cd)?$class_cd:''}}
			{{isset($class_tab)?$class_tab:''}}
			{{isset($is_required) && $is_required ?'required':''}}" 
		value="{{ $val or '' }}" 
		id="{{ $id or '' }}"
		maxlength="14">
	<span class="input-group-btn">
		<button {{isset($is_disabled) && $is_disabled ?'disabled':''}} type="button" class="btn btn-primary btn-icon btn-search {{isset($class_tab)?$class_tab:''}}"><i class="icon-search4"></i></button>
	</span>
	<!-- <span class="input-group-text text-overfollow m-w-popup-label refer-search purchaserequest_nm"></span> -->
	@if(!isset($is_nm) || (isset($is_nm) && $is_nm != false))
	<span class="input-group-text text-overfollow m-w-popup-label refer-search purchaserequest_nm"></span>
	@endif
</div>