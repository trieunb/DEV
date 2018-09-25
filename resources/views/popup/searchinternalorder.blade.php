<div class="input-group popup" data-id="internalorder_cd" data-nm="internalorder_nm" data-search="internalorder" data-istable="" data-multi="">
	<input type="text" 
		class="form-control left-radius right-radius refer-search internalorder_cd 	{{isset($class_cd)?$class_cd:''}} 
		{{$required or ''}} 
		{{isset($is_required) && $is_required ?'required':''}}"
		value="{{ $val or '' }}" 
		id="{{$id or ''}}"
		maxlength="10" 
		>		
	<span class="input-group-btn">
		<button type="button" class="btn btn-primary btn-icon btn-search {{isset($class_tab)?$class_tab:''}}"><i class="icon-search4"></i></button>
	</span>
	@if(!isset($is_nm) || (isset($is_nm) && $is_nm != false))
	<span class="input-group-text text-overfollow m-w-popup-label refer-search internalorder_nm"></span>
	@endif
</div>