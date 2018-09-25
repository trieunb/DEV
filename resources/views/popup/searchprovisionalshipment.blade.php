<div class="input-group popup " data-id="provisional_shipment_cd" data-nm="provisional_shipment_nm" data-search="provisional-shipment" data-istable="{{ $istable or 0}}" data-multi="{{ $multi or 0 }}">
	<input {{isset($is_disabled) && $is_disabled ?'readonly':''}} 
	type="text" 
	class="	form-control 
			left-radius 
			right-radius 
			refer-search 
			provisional_shipment_cd
			{{isset($class_cd)?$class_cd:''}}
			{{isset($class_tab)?$class_tab:''}} 
			{{isset($is_required) && $is_required ?'required':''}}" 
	value="{{ $val or '' }}" 
	id="{{ $id or '' }}"
	maxlength="12">
	<span class="input-group-btn">
		<button {{isset($is_disabled) && $is_disabled ?'disabled':''}} type="button" class="btn btn-primary btn-icon btn-search {{isset($class_tab)?$class_tab:''}}"><i class="icon-search4"></i></button>
	</span>
	@if(!isset($is_nm) || (isset($is_nm) && $is_nm != false))
	<span class="input-group-text text-overfollow m-w-popup-label refer-search provisional_shipment_nm"></span>
	@endif
</div>