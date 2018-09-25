<div class="input-group popup" data-id="shipment_serial_cd" data-nm="shipment_serial_nm" data-search="shipmentserial" data-istable="{{ $istable or 0}}" data-multi="{{ $multi or 0 }}">
	<input {{isset($is_readonly) && $is_readonly ?'readonly':''}} type="text" class="form-control text-right left-radius right-radius refer-search shipment_serial_cd quantity
	{{isset($class_tab)?$class_tab:''}} 
	{{isset($class_cd)?$class_cd:''}} 
	{{isset($is_required) && $is_required ?'required':''}}" 
	value="{{ $val or '' }}" id="{{ $id or '' }}">
	<span class="input-group-btn">
		<button {{isset($is_disabled) && $is_disabled ?'disabled':''}} type="button" class="btn btn-primary btn-icon btn-search {{isset($class_tab)?$class_tab:''}}"><i class="icon-search4"></i></button>
	</span>
	@if(!isset($is_nm) || (isset($is_nm) && $is_nm != false))
	<span class="input-group-text text-overfollow m-w-popup-label refer-search shipment_serial_nm"></span>
	@endif
</div>