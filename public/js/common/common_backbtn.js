/**
 * ****************************************************************************
 * APEL
 * button back 
 *
 * 処理概要         :   
 * 作成日          :   
 * 作成者          :   trieunb – trieunb@ans-asia.com
 *
 * 更新日          :
 * 更新者          :
 * 更新内容         :
 *
 * @package     :   MASTER
 * @copyright   :   Copyright (c) ANS-ASIA
 * @version     :   1.0.0
 * ****************************************************************************
 */
$(document).ready(function() {
    // button back keep condition search
    if (sessionStorage.getItem('detail')) {
        _fillDataConditionSearch();
        sessionStorage.removeItem('detail');
    }
});

/**
 * save html to session
 *
 * @author      :   trieunb - 2017/11/15 - create
 * @author      :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function _postSaveHtmlToSession() {
    try {
        sessionStorage.clear();
        var data = []
        $('.search-condition :input').each(function(item) {
            var _key = $(this).attr('name');
            var _val = $(this).val();
            //checkbox or radio
            if($(this).is('[type="checkbox"]') || $(this).is('[type="radio"]')) {
                if ($(this).is(':checked')) {
                    _val = 'checked';
                } else {
                    $(this).removeAttr('checked');
                    _val = '';
                }
            }
            var obj = {
                name    :   _key,
                value   :   _val
            };
            data.push(obj);
        });
        sessionStorage.setItem('condition_input', JSON.stringify(data));
        sessionStorage.setItem('_PAGE', _PAGE);
        sessionStorage.setItem('_PAGE_SIZE', _PAGE_SIZE);
    } catch (e) {
        alert('_postSaveHtmlToSession' + e.message);
    }
}
/**
 * fill data to input condition search
 *
 * @author      :   trieunb - 2017/11/15 - create
 * @author      :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function _fillDataConditionSearch() {
    try {
        var _data = JSON.parse(sessionStorage.getItem("condition_input"));
        if(_data != null){
            _PAGE = JSON.parse(sessionStorage.getItem("_PAGE"));
            _PAGE_SIZE = JSON.parse(sessionStorage.getItem("_PAGE_SIZE"));
            _isBackScreen = true;
            $('.search-condition :input').each(function(item) {
                // Trieunb - add 2018/02/05
                var element = $(this);
                var _key    = $(this).attr('name');
                for (var i = 0; i < _data.length; i++) {
                    var obj = _data[i];
                    var key = obj.name;
                    var value = obj.value;
                    if (_key == key) {
                        $("."+key).val(value);
                        if ($("."+key).parent().hasClass('popup')) {
                            if ( $("."+key).val() != '') {
                                $("."+key).trigger('change');
                            }
                        }

                        if(element.is("select")) {
                            if (value != '') {
                                $(element).find("."+_key+" option[value="+value+"]").attr('selected', 'selected');
                            }
                        }
                        //checkbox or radio
                        if($(this).is('[type="checkbox"]') || $(this).is('[type="radio"]')) {
                            if (value === 'checked') {
                                $("."+key).attr('checked', true);
                            }
                        }
                    }
                }
            });
            $('#btn-search').trigger("click");
             _isBackScreen = false;
        }
    } catch (e) {
        alert('_fillDataConditionSearch' + e.message);
    }
}