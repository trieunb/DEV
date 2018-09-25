<div class="input-group popup" data-id="manufacturinginstruction_cd" data-nm="manufacturinginstruction_nm" data-search="manufacturinginstruction" data-istable="" data-multi="">
	<input type="text" value="{{ $val or '' }}" id="{{$id or ''}}" @if((isset($is_disabled) && $is_disabled == true)) disabled="disabled" @endif maxlength="8"
		name="{{ isset($class_cd)?$class_cd:'' }}"
		class="form-control left-radius right-radius refer-search manufacturinginstruction_cd 
		{{isset($is_required) && $is_required ?'required':''}}
		{{isset($class_cd)?$class_cd:''}}
		{{$disabled_ime or ''}}">
	<span class="input-group-btn">
		<button type="button" class="btn btn-primary btn-icon btn-search"><i class="icon-search4"></i></button>
	</span>
	@if(!isset($is_nm) || (isset($is_nm) && $is_nm != false))
	<span class="input-group-text text-overfollow m-w-popup-label refer-search manufacturinginstruction_nm"></span>
	@endif
</div>