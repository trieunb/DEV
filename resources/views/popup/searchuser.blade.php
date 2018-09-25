<div class="input-group popup" data-id="user_cd" data-nm="user_nm" data-search="user" data-istable="" data-multi="">
	<input type="text" class="form-control left-radius right-radius refer-search user_cd disable-ime {{ isset($class_cd)?$class_cd:'' }} {{$required or ''}} {{isset($class_tab)?$class_tab:''}} {{isset($is_required) && $is_required ?'required':''}}" 
			value="{{ $val or '' }}" 
			id="{{$id or ''}}" 
			maxlength="20" 
			name="{{ isset($class_cd)?$class_cd:'' }}">
	<span class="input-group-btn">
		<button type="button" class="btn btn-primary btn-icon btn-search {{isset($class_tab)?$class_tab:''}}"><i class="icon-search4"></i></button>
	</span>
	@if(!isset($is_nm) || (isset($is_nm) && $is_nm != false))
	<span class="input-group-text text-overfollow m-w-popup-label refer-search user_nm {{ isset($class_nm)?$class_nm:'' }}"></span>
	@endif
</div>
