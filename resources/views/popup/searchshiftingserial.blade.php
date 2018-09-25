<div class="input-group popup" 
	 data-id="shifting_serial_cd" 
	 data-nm="shifting_serial_nm" 
	 data-search="shiftingserial" 
	 data-istable="{{ $istable or 0}}" 
	 data-multi="{{ $multi or 0 }}">
	<input 	type="text" 
			class="form-control text-right left-radius right-radius refer-search shifting_serial_cd quantity
				   {{isset($class_tab)?$class_tab:''}} 
				   {{isset($class_cd)?$class_cd:''}} 
				   {{isset($is_required) && $is_required ?'required':''}}" 
			value="{{ $val or '' }}" 
			id="{{ $id or '' }}"
			maxlength="8"
			{{isset($is_readonly) && $is_readonly ? 'readonly' : ''}} >
	<span class="input-group-btn">
		<button {{isset($is_disabled) && $is_disabled ? '' : 'disabled'}}
				type="button" 
				class="btn btn-primary btn-icon btn-search {{isset($class_tab)?$class_tab:''}}">
			<i class="icon-search4"></i>
		</button>
	</span>
	@if(!isset($is_nm) || (isset($is_nm) && $is_nm != false))
		<span class="input-group-text text-overfollow m-w-popup-label refer-search shifting_serial_nm"></span>
	@endif
</div>