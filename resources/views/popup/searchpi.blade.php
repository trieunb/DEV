<div class="input-group popup" data-id="pi_cd" data-nm="pi_nm" data-search="pi" data-istable="" data-multi="">
	<input type="text" name="{{isset($class_cd)?$class_cd:''}}" 
		class="form-control left-radius right-radius refer-search pi_cd	
			{{isset($class_cd)?$class_cd:''}} 
			{{$required or ''}} 
			{{isset($class_tab)?$class_tab:''}} 
			{{isset($is_required) && $is_required ?'required':''}}" 
		value="{{ $val or '' }}" 
		id="" 
		maxlength="12">
	<span class="input-group-btn">
		<button type="button" class="btn btn-primary btn-icon btn-search {{isset($class_tab)?$class_tab:''}}">
			<i class="icon-search4"></i>
		</button>
	</span>
	@if(!isset($is_nm) || (isset($is_nm) && $is_nm != false))
	<span class="input-group-text text-overfollow m-w-popup-label refer-search pi_nm {{ isset($class_nm)?$class_nm:'' }}"></span>
	@endif
</div>
