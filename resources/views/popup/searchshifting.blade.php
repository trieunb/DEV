<div class="input-group popup" data-id="shifting_cd" data-nm="shifting_nm" data-search="shifting" data-istable="{{ $istable or 0}}" data-multi="{{ $multi or 0 }}">
	<input {{isset($is_disabled) && $is_disabled ?'readonly':''}} 
        type      ="text" 
        class     ="form-control left-radius right-radius refer-search shifting_cd {{isset($class_tab)?$class_tab:''}} {{isset($is_required) && $is_required ?'required':''}} {{isset($class_search) ? 'shifting-change' : '-'}}" 
        value     ="{{ $val or '' }}" 
        id        ="{{ $id or '' }}" 
        maxlength ="14">
	<span class="input-group-btn">
		<button {{isset($is_disabled) && $is_disabled ?'disabled':''}} 
            type="button" 
            class="btn btn-primary btn-icon {{isset($class_search) ? $class_search : 'btn-search'}} {{isset($class_tab)?$class_tab:''}}">
            <i class="icon-search4"></i>
        </button>
	</span>
	@if(!isset($is_nm) || (isset($is_nm) && $is_nm != false))
	   <span class="input-group-text text-overfollow m-w-popup-label refer-search shifting_nm"></span>
	@endif
</div>
