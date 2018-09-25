<div class="input-group popup" data-id="suppliers_cd" data-nm="suppliers_cd" data-search="suppliers" data-istable="" data-multi="">
	<input {{isset($is_disabled) && $is_disabled ?'readonly':''}} 
		type="text" 
		name="{{ isset($class_cd)?$class_cd:'' }}" 
		class="form-control left-radius right-radius refer-search suppliers_cd TXT_client_cd 
			{{isset($class_cd)?$class_cd:''}} 
			{{isset($is_required) && $is_required ?'required':''}} 
			{{$disabled_ime or ''}}" 
		value="{{ $val or '' }}" 
		maxlength="6" 
		id="{{$id or ''}}">
	<span class="input-group-btn">
		<button {{isset($is_disabled) && $is_disabled ?'disabled':''}} type="button" class="btn btn-primary btn-icon btn-search"><i class="icon-search4"></i></button>
	</span>
	@if(!isset($is_nm) || (isset($is_nm) && $is_nm != false))
	<span class="input-group-text suppliers_nm client_nm {{ isset($class_nm)?$class_nm:'' }}"></span>
	@endif
</div>