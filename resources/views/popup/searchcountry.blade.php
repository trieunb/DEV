<div class="input-group popup" data-id="country_cd" data-nm="country_nm" data-search="country" data-istable="{{ $istable or 0}}" data-multi="{{ $multi or 0 }}">
    <input {{isset($is_disabled) && $is_disabled ?'readonly':''}} 
        type="text" 
        name="{{ isset($class_cd)?$class_cd:'' }}" 
        class="form-control left-radius right-radius refer-search country_cd TXT_country_cd 
            {{ isset($class_cd)?$class_cd:'' }} 
            {{isset($class_tab)?$class_tab:''}} 
            {{isset($class_search)?'country-change':''}} 
            {{isset($is_required) && $is_required ?'required':''}}" 
            value="{{ $val or '' }}" 
            id="{{ $id or '' }}" 
            maxlength="2">

    <span class="input-group-btn">
        <button {{isset($is_disabled) && $is_disabled ?'disabled':''}} type="button" class="btn btn-primary btn-icon {{isset($class_search) ? $class_search : 'btn-search'}} {{isset($class_tab)?$class_tab:''}}"><i class="icon-search4"></i></button>
    </span>

    @if(!isset($is_nm) || (isset($is_nm) && $is_nm != false))
    <span class="input-group-text text-overfollow m-w-popup-label refer-search country_nm {{ isset($class_nm)?$class_nm:'' }}" @if(isset($different_jp) && $different_jp != false) style="display: table-cell; position: relative; top: 2px;" @endif></span>
    @endif

    @if(isset($different_jp) && $different_jp != false)
        <label class="checkbox-inline"  style="display: table-cell; white-space: nowrap">
            <input type="checkbox" class="check-box-different-jp" id="check-box-different-jp" name="check-box-different-jp">日本以外
        </label>
    @endif
</div>