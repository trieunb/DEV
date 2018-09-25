<div class="input-group popup popup-checklist" data-id="check_list_cd" data-nm="check_list_nm" data-search="check-list" data-istable="{{ $istable or 0}}" data-multi="{{$multi or 0}}">
	<span class="">
		<button {{isset($is_disabled) && $is_disabled ?'disabled':''}} type="button" class="btn btn-primary btn-icon btn-search {{isset($class_tab)?$class_tab:''}}"><i class="icon-search4"></i></button>
	</span>
	@if(!isset($is_nm) || (isset($is_nm) && $is_nm != false))
	<span class="input-group-text text-overfollow m-w-popup-label refer-search check-list-nm"></span>
	@endif
</div>