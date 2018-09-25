/**
 * ****************************************************************************
 * APEL COMMON.JS
 *
 * 処理概要		:	common.js
 * 作成日		:	2017/05/26
 * 作成者		:	ANS806 – Trieunb@ans-asia.com
 * @package		:	MODULE NAME
 * @copyright	:	Copyright (c) ANS-ASIA
 * @version		:	1.0.0
 * ****************************************************************************
 */
//Global variables
var _PAGE 						= 1			// page of search screen
var _PAGE_SIZE 					= 10 		// number of record in a page
var _isBackScreen				= false 	// check is back from detail to search screen
var _MSG_E001 					= _text['E001']	//message E001
var _text_empty					= "該当するデータが存在しません。";
var UNAUTHORIZED        		= 401;
var PAGE_NOT_FOUND      		= 404;
var PERMISSION_CODE     		= 405;
$(document).ready(function() {
	//init disable ime
    initDisableIME();
    //init textarea disable resize
    initTextareaDisableResize();
	/**
	 * @author: Trieunb - 2017/10/10
	 * @reason: init token value for all post method, show block screen and close block screen when use ajax
	 */
	$(document).ajaxStart(function(e) {
		var $el = $(e.target.activeElement);
		// if click on link menu and button function then not callWaiting
		if (!$el.parent().parent().hasClass('hidden-ul') &&
			!$el.parent().hasClass('cl-btn-add-new')) {
			//callWaiting();
		}
	});

	$(document).ajaxComplete(function() {
		closeWaiting();
	});

	$(document).ajaxError(function() {
		closeWaiting();
	});

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		//HungNV add
		beforeSend:function(){
			if(this.loading){
				callWaiting();
			}
		},
		success: function(res){
			//removeError();
			closeWaiting();
		},
		error : function(response){
			closeWaiting();
			if (response['status'] == PERMISSION_CODE) {
	            jMessage('E488', function(r){
	                //window.location.href = '/handle/dashboard';
	            });
	        };
			return false;
		},
		// DuyTP 2017/02/09 Add event back to login when session expires
		complete: function(res){
			//Trieunb - add init datepicker when run done ajax
			_formatDatepicker();
			//HaVV - 2018/01/12 - set tabindex for datepicker
			_setTabindexForDatepicker();
			if (res.status != null && res.status == 404) {
				location.href = '/';
			} else if(res.status==409) {
				location.href = '/example';
			}
			//該当するデータが存在しません。
			if (this.url.indexOf('search') > -1) {
				if (res.status != PERMISSION_CODE) {
					$('.dataTables_empty').html(_text_empty);
				}
			}
		}
	});
	//init control's event
	initControls();
	//init one time control's event
	initOneTimeControls();
    // Initialize mini sidebar
    miniSidebar();
    initEventsCommon();
});

/**
 * init event for common code
 *
 * @author      :   Trieunb - 2017/08/20 - create
 * @author      :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function initEventsCommon() {
	try {
		//init attribute common for input
		_setAttributeCommon();
		// _setMaxLength();

		$('[data-toggle="tooltip"]').tooltip();
		$(document).on('focus','input.numeric,input.money,input.date,input.time,input.time24,input.time48,input.phone,input.none-full-size',function(e) {
            $(this).attr('type', 'tel');
        });

        // set active link menu when selected
		$('.navigation-accordion').find('.active').removeClass('active');
	    var url      = 	window.location.pathname;
	    $('a[href$="'+url+'"]').parent().addClass('active');
	    $('a[href$="'+url+'"]').parent().parent().parent().addClass('active');
	    $('a[href$="'+url+'"]').parent().parent().css('display','block')

	    $('.navigation').children().find('a').attr('tabindex', 0);
	    //set maxlength input number date 4
	    $(".number-date").attr('maxlength','4');

		$('.hidden-ul li a').on('click', function(event) {
	    	event.preventDefault();
	    	var url = $(this).attr('href');
	    	_postParamToLink('SELF', 'SELF', url, '');
	    	//clear session when click on menu 2018/05/04
	    	sessionStorage.clear();
	    });

	    $(document).on('keydown','.btn,.ui-datepicker-trigger',function (event) {
	        if(event.keyCode == 13){
	            $(this).trigger('click');
	            event.preventDefault();
	        }
	    });

	    $(document).on('change','input.datepicker', function () {
	        $(this).prev().val('');
	    });

	    $(".mail").keyup(function () {
		    var val = this.value;
		    // Regex for matching ALL Japanese common & uncommon Kanji
		    var email = new RegExp('^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$');

		    if (email.test(val)) {
		        alert('Great, you entered an E-Mail-address');
		    }
		});

	   	$(document).on('focusout', '.required', function() {
	   		var val  = '';
		    if ($(this).is("input[type=checkbox]")) {
		       if ($(this).is(":checked")) {
		        	val = 'true';
			    } else {
			        val = '';
			    }
		    } else {
		       val = $.mbTrim($(this).val());
		    }
	   		if (val !== '' && $(this).attr('has-balloontip-message') == _MSG_E001) {
	   			_removeErrorStyle($(this));
	   		}
	   	});
 		//set maxlength
 		$(document).on('keyup', '.number-date', function(e) {
			var val_sub = parseInt($(this).val());
			if (val_sub > 99) {
				$(".number-date").attr('maxlength', '3');
			} else {
				$(".number-date").attr('maxlength', '4');
			}
		});

 		// only enter input numeric
		$(document).on('keydown', '.number-date', function(e){
			if (
				e.keyCode == 229
				|| 	!((e.keyCode > 47 && e.keyCode < 58)
				||  (e.keyCode > 95 && e.keyCode < 106)
				//////////// PERIOD SIGN ////////////////////////////////////////////////////////////////
				||  ((e.keyCode == 190 || e.keyCode == 110) && $(this).val().indexOf('.') === -1)
				||  e.keyCode == 173
				||  e.keyCode == 189
				||  e.keyCode == 109
				||  e.keyCode == 116
				||  e.keyCode == 46
				||  e.keyCode == 37
				||  e.keyCode == 39
				|| 	e.keyCode == 36
				|| 	e.keyCode == 359
				||  e.keyCode == 8
				||  e.keyCode == 9
				||  e.keyCode == 13)
			){
				e.preventDefault();
			}
		});

 		// change sub and plus date current
 		$(document).on('change', '.number-date', function(e) {
 			var val_sub = $(this).val();
 			if (val_sub !== '' && !isNaN(val_sub)) {
				if (val_sub.indexOf('-') != '-1') {
					val_sub = val_sub.replace('-', '')
					$(this).next().val(_subAndPlusDate(val_sub, '-'));
	 			} else {
	 				$(this).next().val(_subAndPlusDate(val_sub));
	 			}
 			} else {
 				$(this).val('');
 				$(this).next().val('');
 			}
 			e.preventDefault();
 		});

		//change input freight tax
		// $(document).on('change', '.value-freight', function() {
		// 	_totalTaxTable();
		// });

		//change input insurance tax
		// $(document).on('change', '.value-insurance', function() {
		// 	_totalTaxTable();
		// });
    } catch (e) {
		alert('initEventsCommon: ' + e.message);
	}
}
/**
 * setup maxlength common
 *
 * @author      :   trieunb - 2017/10/20 - create
 * @author      :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
 function _setAttributeCommon() {
	// set placeholder for
	$(".datepicker").attr("placeholder", "yyyy/mm/dd");
	$(".datepicker").attr("maxlength", "10");
	$(".month").attr("placeholder", "yyyy/mm");
}
// Setup
function miniSidebar() {
    if ($('body').hasClass('sidebar-xs')) {
        $('.sidebar-main.sidebar-fixed .sidebar-content').on('mouseenter', function () {
            if ($('body').hasClass('sidebar-xs')) {
            	//close all select
				$('select').blur();
                // Expand fixed navbar
                $('body').removeClass('sidebar-xs').addClass('sidebar-fixed-expanded');
            }
        }).on('mouseleave', function () {
            if ($('body').hasClass('sidebar-fixed-expanded')) {

                // Collapse fixed navbar
                $('body').removeClass('sidebar-fixed-expanded').addClass('sidebar-xs');
            }
        });
    }
}

/**
 * block screen
 *
 * @author      :   vuongvt - 2016/10/20 - create
 * @author      :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function callWaiting() {
	$.blockUI({
		message: '<i class="icon-spinner4 spinner"></i>',
		overlayCSS: {
			backgroundColor: '#1b2024',
			opacity: 0.8,
			zIndex: 1200,
			cursor: 'wait'
		},
		css: {
			border: 0,
			color: '#fff',
			padding: 0,
			zIndex: 1201,
			backgroundColor: 'transparent'
		}
	});
}

/**
 * unblock screen
 *
 * @author      :   vuongvt - 2016/10/20 - create
 * @author      :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function closeWaiting() {
	$.unblockUI({});
}
/**
 * validate data common
 *
 * @author        :    Trieunb - 2017/10/10 - create
 * @update		  :    Trieunb - 2017/08/08
 * @params        :    null
 * @return        :    null
 */
function _validate(element) {
	if(!element){
		element = $('body');
	}
	var error = 0;
	try{
		_clearErrors();
		element.find('.required:enabled:not([readonly])').each(function() {
			if ($(this).is(':visible')) {
				if(($(this).is("input") || $(this).is("textarea")) &&  $.trim($(this).val()) == '' ) {
					$(this).errorStyle(_MSG_E001);
					error ++;
				}else if( $(this).is("select") &&  ($(this).val() == '' || $(this).val() == undefined) ) {
					$(this).errorStyle(_MSG_E001);
					error ++;
				}else if($(this).is("input[type=checkbox]") && !$(this).is(":checked")){
                    $(this).errorStyle(_MSG_E001);
                    error ++;
                }
			}
		});

		element.find('input.email:enabled:not([readonly])').each(function(){
			if(!_validateEmail($(this).val())){
				$(this).errorStyle('フォーマットが正しくありません。');
				error++;
			}
		});
		element.find('input.tel:enabled:not([readonly])').each(function(){
		    if(!_validatePhoneFaxNumber($(this).val())){
		        $(this).errorStyle(_text[21]);
		        error++;
		    }
		});
		if( error > 0 ) {
			return false;
		} else {
			return true;
		}
	}catch(e){
		alert('_validate: ' + e.toString());
	}
}
/**
 * validate only item key
 *
 * @author        :    Trieunb - 2017/12/29 - create
 * @update		  :
 * @params        :    null
 * @return        :    null
 */
function _validateKey(element) {
	if(!element){
		element = $('body');
	}
	try {
		var error = 0;
		_clearErrors();

		element.find('.key .required:enabled:not([readonly])').each(function() {
			if ($(this).is(':visible')) {
				if(($(this).is("input") || $(this).is("textarea")) &&  $.trim($(this).val()) == '' ) {
					$(this).errorStyle(_MSG_E001);
					error ++;
				}else if( $(this).is("select") &&  ($(this).val() == '' || $(this).val() == undefined) ) {
					$(this).errorStyle(_MSG_E001);
					error ++;
				}else if($(this).is("input[type=checkbox]") && !$(this).is(":checked")){
                    $(this).errorStyle(_MSG_E001);
                    error ++;
                }
			}
		});

		if( error > 0 ) {
			return false;
		} else {
			return true;
		}
	} catch (e) {
		alert('validatePiNo: ' + e.message);
	}
}
/**
 * remover message validate item
 *
 * @author        :    Trieunb - 2017/12/29 - create
 * @update		  :
 * @params        :    null
 * @return        :    null
 */
function _clearValidateMsg(element) {
	try {
		if(!element){
			element = $('body');
		}
		element.find('.required:enabled:not([readonly])').each(function() {
			if(($(this).is("input") || $(this).is("textarea")) &&  $.trim($(this).val()) !== '' ) {
				_removeErrorStyle($(this));
			}else if( $(this).is("select") &&  ($(this).val() !== '') ) {
				_removeErrorStyle($(this));
			}else if($(this).is("input[type=checkbox]") && $(this).is(":checked")){
                _removeErrorStyle($(this));
            }
		});
	} catch (e) {
		alert('clearValidateMsg: ' + e.message);
	}
}
/**
 * format phone, fax number
 * @param string
 * @param input
 */
function _validatePhoneFaxNumber(string){
	try {
		string = _formatString(string);
		var reg = /[\`\~\!\@\#\$\%\^\&\*\_\=\[\]\{\}\|\\;\:\'\"\,\<\.\>\/\?a-z]/;
		// var reg1 = /^[+]?(\d?([+]?\([+]?\d{1,}\))?\d?[-]?){1,}$/;
		if(reg.test(string)){
			return false;
		}
		return true;
	} catch (e){
		alert('_validatePhoneFaxNumber: '+e);
	}
}
/**
 * initItem
 *
 * @author : vuongvt - 2016/10/27 - create
 * @author :
 * @params : null
 * @return : null
 * @access : public
 * @see :
 */
function initItem(obj) {
	try {
		// int element
		$.each(obj, function(key, element) {
			var selector = $('#' + key);
			if ($.type(element['selector']) !== 'undefined') {
				selector = $('.' + key);
			}
			if ($.type(element['attr']) !== 'undefined') {
				$.each(element['attr'], function(k, e) {
					if (k == 'class') {
						selector.addClass(element['attr'][k]);
					} else {
						selector.attr(k, e);
					}
				});
			}
		});
		// format input
		//_formatInput();
	} catch (e) {
		alert('initItem' + e.message);
	}
}
/**
 * post param into Link controller to save session param
 *
 * @author : thanhnv - 2015/12/08 - create
 * @modify : Trieunb - 2017/08/20 - update
 * @param  : string - screenId
 * @param  : json object - parram
 * @return : null
 * @access : public
 * @see :
 */
function _postParamToLink(fromScreenId, toScreenId, referUrl, parram, callback) {
	try {
		if (referUrl == null){
			return;
		}
		$.ajax({
			type : 'GET',
			url : '/common/link/linksession',
			dataType : 'json',
			data : {
				'from_ScreenId' : fromScreenId,
				'to_ScreenId' 	: toScreenId,
				'parram' 		: parram
			},
			success : function(res) {
				if (callback) {
                    callback();
                }
				if (referUrl !='') {
					location.href = referUrl;
				}
			}
		});
	} catch (e) {
		alert('_postParamToLink' + e.message);
	}
}

/**
 * showPopup
 *
 * @author  :   vuongvt - 2016/11/04 - create
 * @author  :
 * @param   :   href,callback
 * @return  :   null
 * @access  :   public
 * @see     :
 */
function showPopup(href, callback, width, height) {
	if(width == undefined || window.innerWidth<900){
		width = "90%";
	}
	if(height == undefined){
		height = "85%";
	}
	var properties = {
		href : href+'&w='+window.innerWidth+'&h='+window.innerHeight,
		open : true,
		iframe : true,
		fastIframe : false,
		opacity : 0.2,
		escKey : true,
		overlayClose : false,
		width : width,
		height : height,
		reposition : true,
		speed : 0,
		trapFocus : true,
		onComplete : function(res) {
			$('#colorbox-width').val(width);
			$('#colorbox-height').val(height);
		},
		onClosed : function() {
			if (callback) {
				callback();
			}
		}
	};
	$.colorbox(properties);
}

/**
 * Convert Full-width to Half-width Characters
 *
 * @param string
 * @returns string
 */
function _formatString(string) {
	try {
		string = $.textFormat(string, '9');
		string = $.textFormat(string, '@');
		string = $.textFormat(string, 'a');
		string = $.textFormat(string, 'A');
		return string;
	} catch (e) {
		alert('_formatString: ' + e);
	}
}

/**
 * padZeroLeft
 *
 * @author : viettd - 2015/10/02 - create
 * @author :
 * @param :
 *            $data
 * @param :
 *            $max
 * @return : null
 * @access : public
 * @see :
 */
function padZeroLeft($data, $max) {
	try {
		var length = $max - $data.length; // alert(length);
		var zero = '';
		if (length == $max) {
			return '';
		}
		for (var i = 0; i < length; i++) {
			zero = zero + '0';
		}
		return zero + $data;
	} catch (e) {
		alert('padZeroLeft' + e.message);
	}
}

/**
 * padZeroRight
 *
 * @author  : vuongvt - 2016/11/14 - create
 * @author  :
 * @param   : $data
 * @param   : $max
 * @return  : null
 * @access  : public
 * @see :
 */
function padZeroRight($data, $max) {
	try {
		var length = $max - $data.length; // alert(length);
		var zero = '';
		if (length == $max) {
			return '';
		}
		for (var i = 0; i < length; i++) {
			zero = zero + '0';
		}
		return $data+zero;
	} catch (e) {
		alert('padZeroRight' + e.message);
	}
}

/**
 * Check Time
 *
 * @param string
 * @returns {Boolean}
 */
function _validateTime(string) {
	var reg = /^(([0-1][0-9])|(2[0-3])):[0-5][0-9]|[2][4]:[0][0]$/;
	if (string.match(reg) || string == '') {
		return true;
	} else {
		return false;
	}
}

/**
 * validate email
 *
 * @author      :   vuongvt - 2016/10/26 - create
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
// function isValidEmailAddress(emailAddress) {
// 	var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
// 	return pattern.test(emailAddress);
// };

/**
 * Check Email
 * @param string
 * @returns {Boolean}
 */
function _validateEmail(string){
	if(string == '') {
		return true;
	}
	string = _formatString(string);
	var reg = /^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/;
	if (string.match(reg)){
		return true;
	}else{
		return false;
	}
}

/**
 * init all controls event
 *
 * @author  :   vuongvt - 2016/11/04 - create
 * @author  :
 * @param   :   href,callback
 * @return  :   null
 * @access  :   public
 * @see     :
 */
function initOneTimeControls() {
	//init focus first item
	_focusFirstItem();

	//init enter key search
	_enterKeySearch();

	//btn search
	$(document).on('click', '.btn-search', function(){
		var data = {};
		var parent = $(this).parents('.popup');
		var input = parent.find('input');
		var btn = input.attr('tabindex');

		data.id = parent.data('id');
		data.search = parent.data('search');
		data.istable = parent.data('istable');
		data.multi = parent.data('multi');
		data.btnid = btn;
		parent.addClass('popup-'+ data.search);
		showPopup('/popup/search/' + data.search + '?' + _setGetPrams(data), function(){
			input.focus();
			parent.removeClass('popup-'+ data.search);
		});

	});
}
/**
 * init all controls event
 *
 * @author  :   vuongvt - 2016/11/04 - create
 * @author  :
 * @param   :   href,callback
 * @return  :   null
 * @access  :   public
 * @see     :
 */
function initControls() {
	//init placeholder for class time
	$('.time').prop('placeholder', ':');
	//button login
	$(document).on('click','#logout-link',function(){
		try {
			var _url = '/logout';

			$.ajax({
				type        :   'POST',
				url         :   _url,
				dataType    :   'json',
				success: function(res) {
					location.href = '/';
				},
				// Ajax error
				error : function(res) {
					location.href = '/';
				},
			});
		} catch (e) {
			alert('search' + e.message);
		}
	});
	//format datepicker
	_formatDatepicker();
	//format yearmonth picker
	_formatYearMonthPicker();
	//auto format date items when lose focus
	_autoFormattingDate("input.datepicker");
	_autoFormattingMonth("input.month");
	//dynamically set tab index
	_setTabIndex();
	/**
	 * @author: VuongVT - 2016/10/26
	 * @reason: init for numeric control (positive and negative)
	 */
	//$(".numeric").autoNumeric('init', {vMax: '999999999', vMin: '-999999999'});
	$(document).on('keydown', 'input.numeric:enabled', function(e){
		if (    e.keyCode == 229
			||  !(      (e.keyCode > 47 && e.keyCode < 58)
					||  (e.keyCode > 95 && e.keyCode < 106)
					//////////// PERIOD SIGN ////////////////////////////////////////////////////////////////
					||  ((e.keyCode == 190 || e.keyCode == 110) && $(this).val().indexOf('.') === -1)
					||  e.keyCode == 173
					||  e.keyCode == 109
					||  e.keyCode == 189
					||  e.keyCode == 116
					||  e.keyCode == 46
					||  e.keyCode == 37
					||  e.keyCode == 39
					|| e.keyCode == 36
					|| e.keyCode == 35
					||  e.keyCode == 8
					||  e.keyCode == 9
					||  e.keyCode == 13)
		){
				e.preventDefault();
				return false;
		}

        // debugger;
		var negativeEnabled = $(this).attr('negative');
		if(
				e.keyCode != 116
			&&  e.keyCode != 46
			&&   e.keyCode != 35
			&&   e.keyCode != 36
			&&  e.keyCode != 37
			&&  e.keyCode != 39
			&&  e.keyCode != 8
			&&  e.keyCode != 9
			&&  e.keyCode != 173
			&&  e.keyCode != 189
			&&  e.keyCode != 109
			&&  ($(this).get(0).selectionEnd - $(this).get(0).selectionStart) < $(this).val().length
		){
			// DEFAULT PARAMS (NUMERIC (10, 0))
			var ml = 10;
			var dc = 0;
			if(parseInt($(this).attr('maxlength')) * 1 > 2){
				ml = 1 * $(this).attr('maxlength') - 1;
			}
			if(parseInt($(this).attr('decimal')) > 0){
				dc = 1 * $(this).attr('decimal');
				if(dc >= ml - 1){
					dc = 0;
				}
			}
			var it = (ml - (dc>0?(dc + 1):0));

			// CURRENT STATES
			var val = $(this).val();
			var negative = val.indexOf('-') > -1;
			var selectionStart = $(this).get(0).selectionStart;
			var selectionEnd = $(this).get(0).selectionEnd;
			if(negative){
				val = val.substring(1);
				selectionStart--;
				selectionEnd--;
			}
			// OUTPUT STATES
			var destSelectionStart = undefined;
			var destSelectionEnd = undefined;
			var destVal = undefined;
			// SKIP PERIOD KEY WHEN DECIMAL = 0
			if(dc == 0 && (e.keyCode == 190 || e.keyCode == 110)){
				e.preventDefault();
			}
			// EXCEED THE ACCEPTED NUMBER OF INTEGERS
			if(val.match(new RegExp('[0-9]{' + it + '}')) && selectionStart <= it){
				// PERIOD DOES NOT EXIST
				if(val.indexOf('.') === -1){
					// PERIOD KEY NOT RECEIVED (USER FORGETS TO TYPE PERIOD)
					// DECIMAL > 0
					if(e.keyCode != 190 && e.keyCode != 110 && dc > 0){
						e.preventDefault();
						var output =    val.substring(0, selectionStart)
									+   String.fromCharCode((96 <= e.keyCode && e.keyCode <= 105)? e.keyCode-48 : e.keyCode)
									+   val.substring(selectionStart);
						// INSERT PERIOD
						destVal = output.substring(0, ml - (dc + 1)) + '.' + output.substring(ml - (dc + 1));
					}
				// PERIOD EXISTS
				// CARET STARTS NEXT TO THE PERIOD
				}else if(
						selectionStart == val.indexOf('.')
				){
					// EXCEED THE ACCEPTED NUMBER OF DECIMALS
					if(val.match(new RegExp('\\.[0-9]{'+dc+'}$'))){
						e.preventDefault();
					}else{
						// JUMP TO THE NEXT POSITION THEN INSERT THE DIGIT
						destSelectionStart = selectionStart + 1;
					}
				// CARET STARTS BEFORE THE PERIOD AND NOTHING HIGHLIGHTED
				}else if(
						selectionStart < val.indexOf('.')
					&&  selectionStart == selectionEnd
				){
					e.preventDefault();
				// CARET STARTS BEFORE THE PERIOD AND ENDS AFTER THE PERIOD (HIGHLIGHTS OVER THE PERIOD)
				}else if(
						selectionEnd > val.indexOf('.')
					&&  selectionStart < val.indexOf('.')
				){
					e.preventDefault();
					var output =    val.substring(0, selectionStart)
					+   String.fromCharCode((96 <= e.keyCode && e.keyCode <= 105)? e.keyCode-48 : e.keyCode)
					+   val.substring(selectionEnd);
					destVal = output.substring(0, ml - (dc + 1)) + '.' + output.substring(ml - (dc + 1));
					destSelectionStart = selectionStart + 1;
					destSelectionEnd = selectionStart + 1;
				}
			// INTEGERS CAN BE ADDED BUT...
			// EXCEED THE ACCEPTED NUMBER OF DECIMALS
			}else if(val.match(new RegExp('\\.[0-9]{'+dc+'}$'))){
				// PERIOD EXISTS
				// CARET STARTS AFTER THE PERIOD
				if(val.indexOf('.') != -1 && selectionStart > val.indexOf('.')){
					e.preventDefault();
				}
			}

			// CARET RESULT
			if(destVal && negative){
				destVal = '-' + destVal;
			}
			if(destVal){
				$(this).val(destVal);
			}
			if(negative && destSelectionStart){
				destSelectionStart++;
			}
			if(destSelectionStart){
				$(this).get(0).selectionStart = destSelectionStart;
			}
			if(negative && destSelectionEnd){
				destSelectionEnd++;
			}
			if(destSelectionEnd){
				$(this).get(0).selectionEnd = destSelectionEnd;
			}
		}else if(
				e.keyCode == 173
			||  e.keyCode == 109
			||  e.keyCode == 189
		){
			e.preventDefault();

			if(negativeEnabled){
				var val = $(this).val();
				var negative = val.indexOf('-') > -1;
				if(negative){
				{
					$(this).val(val.substring(1));
				}
				}else{
					$(this).val('-' + val);
				}
			}
		}

		// fix maxlenght
		val = $(this).val();
		if($(this).attr('fixed') != undefined && val.indexOf('-') > -1)
		{
			f_maxlenght = (parseInt($(this).attr('maxlengthfixed')) + 1)+'';

			if(val.length <= f_maxlenght)
				$(this).attr('maxlength',f_maxlenght);
			else
				$(this).attr('maxlength',f_maxlenght);
		}
		else if($(this).attr('maxlength') > $(this).attr('maxlengthfixed'))
		{
			$(this).attr('maxlength',$(this).attr('maxlengthfixed'));
		}


	});
	$(document).on('keydown', '.currency_JPY', function(event){
		//var ctrlDown = event.ctrlKey||event.metaKey; // Mac support
		if (event.keyCode == 110 ) {
			event.preventDefault();
		}
	});
	/**
	 * @author: KienNT - 2017/02/23
	 * @reason: init for numeric control (decimal) example numeric(5,2)
	 */
	$(document).on('keydown', '.money, .price, .weight, .numeric-only', function(event){
		//var ctrlDown = event.ctrlKey||event.metaKey; // Mac support
		// var current = $('.currency_div').val();
		// if (current == 'JPY' && event.keyCode == 110 && ($(this).hasClass('money') || $(this).hasClass('price')) ) {
		// 	event.preventDefault();
		// }
		if (event.keyCode == 110 && $(this).hasClass('quantity')) {
			event.preventDefault();
		}
		if (
			(	!(	(event.keyCode > 47 && event.keyCode < 58)  // 0 ~ 9
					||	(event.keyCode > 95 && event.keyCode < 106) // numpad 0 ~ numpad 9
					||	event.keyCode == 116 	// F5
					||	event.keyCode == 46 	// del
					||	event.keyCode == 35 	// end
					||	event.keyCode == 36 	// home
					||	event.keyCode == 37		//　←
					||	event.keyCode == 39		// →
					||	event.keyCode == 8		// backspace
					||	event.keyCode == 9		// tab
					||  event.keyCode == 188	// ,
					||  event.keyCode == 189	// -
					||  event.keyCode == 109	// numpad -
					||  event.keyCode == 173	// - (firefox only)
					||  event.keyCode == 190	// .
					||  event.keyCode == 110	// numpad .
					||	(event.shiftKey && event.keyCode == 35) // shift + end
					||	(event.shiftKey && event.keyCode == 36) // shift + home
					||	event.ctrlKey // allow all ctrl combination
				)
			)
			||	(event.shiftKey && (event.keyCode > 47 && event.keyCode < 58)) // exlcude Shift + [0~9]
		)
			event.preventDefault();
	});

	$(document).on('keydown', '.quantity', function(event){
		if (event.keyCode == 110 && $(this).hasClass('quantity')) {
			event.preventDefault();
		}
		if (
			(	!(	(event.keyCode > 47 && event.keyCode < 58)  // 0 ~ 9
					||	(event.keyCode > 95 && event.keyCode < 106) // numpad 0 ~ numpad 9
					||	event.keyCode == 116 	// F5
					||	event.keyCode == 46 	// del
					||	event.keyCode == 35 	// end
					||	event.keyCode == 36 	// home
					||	event.keyCode == 37		//　←
					||	event.keyCode == 39		// →
					||	event.keyCode == 8		// backspace
					||	event.keyCode == 9		// tab
					||  event.keyCode == 188	// ,
					||  event.keyCode == 189	// -
					||  event.keyCode == 109	// numpad -
					||  event.keyCode == 173	// - (firefox only)
					// ||  event.keyCode == 190	// .
					||  event.keyCode == 110	// numpad .
					||	(event.shiftKey && event.keyCode == 35) // shift + end
					||	(event.shiftKey && event.keyCode == 36) // shift + home
					||	event.ctrlKey // allow all ctrl combination
				)
			)
			||	(event.shiftKey && (event.keyCode > 47 && event.keyCode < 58)) // exlcude Shift + [0~9]
		)
			event.preventDefault();
	});

	$(document).on('keypress','.money, .price, .quantity, .weight, .measure, .numeric-only',function(event) {
        // debugger;
		var $this = $(this);
		// var decimal_len = (typeof $this.attr('decimal_len') === 'undefined')?0:(1*$this.attr('decimal_len'));
		// var real_len = (typeof $this.attr('real_len') === 'undefined')?0:(1*$this.attr('real_len'));
		var decimal_len = 2;
		var real_len 	= 8;

		var val_sub = parseInt($(this).val());

		if ($(this).hasClass('money')) {
			if (val_sub > 9999999) {
				real_len 	= 8;
			} else {
				real_len 	= 9;
			}
		}

		if ($(this).hasClass('price')) {
			if (val_sub > 9999999) {
				real_len 	= 8;
			} else {
				real_len 	= 9;
			}
		}

		if ($(this).hasClass('quantity')) {
			if (val_sub > 99999) {
				real_len 	= 6;
			} else {
				real_len 	= 7;
			}
		}

		if ($(this).hasClass('weight')) {
			if (val_sub > 9999) {
				real_len 	= 5;
			} else {
				real_len 	= 6;
			}
		}

		if ($(this).hasClass('measure')) {
			if (val_sub > 99) {
				real_len 	= 3;
			} else {
				real_len 	= 4;
			}
		}

		var negative = (typeof $this.attr('negative') === 'undefined')?0:(1*$this.attr('negative'));

		if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
			(((event.which!=45 &&event.which < 48) || event.which > 57) &&
			(event.which != 0 && event.which != 8))) {
			event.preventDefault();
		}

		var text = $(this).val();
		var str_real = text;
		var str_decimail = '';

		if(text.indexOf('.') != -1){
			str_real = text.substring(0,$this.val().indexOf('.'));
			str_decimail = text.substring($this.val().indexOf('.')+1,text.length);
		}
		if ((event.which == 46) && (text.indexOf('.') == -1)) {
			setTimeout(function() {
				if ($this.val().substring($this.val().indexOf('.')).length > decimal_len+1) {
					$this.val($this.val().substring(0, $this.val().indexOf('.') + decimal_len+1));
				}
			}, 1);
		}
		// out of decimal
		if ((text.indexOf('.') != -1) &&
			(text.substring(text.indexOf('.')).length > decimal_len) &&
			(event.which != 0 && event.which != 8) &&
			($(this)[0].selectionStart >= text.length - 2)) {
			event.preventDefault();
		}

		// out of real
		var selectionText  = '';
		if (window.getSelection) {
			selectionText = window.getSelection().toString();
		} else if (document.selection && document.selection.type != "Control") {
			selectionText = document.selection.createRange().text;
		}

		if(
			(((str_real.length >= real_len) && (event.which != 0 && event.which != 8 && event.which != 46) && (text.indexOf('.') == -1))||
			((str_real.length >= real_len) && (event.which != 0 && event.which != 8) && (text.indexOf('.') != -1) && ($(this)[0].selectionStart<=text.indexOf('.')))) && selectionText == ''
		) {
			event.returnValue = false;
    		event.preventDefault();
		}

		// negative
		if ($this.hasClass('price')) {
			if((negative!=1 || text.indexOf('-') > -1)&&(event.which == 45)) {
				event.preventDefault();
			} else if(text.indexOf('-')==-1 && event.which==45 && negative==1) {
	            $this.val('-'+text);
	        }
		}
	});

	$(document).on('blur', '.money:not([readonly]), .price:not([readonly]), .quantity:not([readonly]), .weight:not([readonly]), .measure:not([readonly])', function(event){
		var item = $(this);
		var value = item.val().replace(/,/gi,'');
		if(value != ''){
			if ( $.isNumeric(value) ) {
				value = addCommas(value*1);
				item.val(value);
			}  else {
				item.val('');
			}
		}
	});

	// $('.currency_div').on('change', function(event) {
	// 	if ($(this).val() == 'JPY') {
	// 		$('.money, .price').each(function(k, v) {
	// 			var text = $(this).val();
	// 				if ($(this).val().indexOf('.') !== -1) {
	// 					str_real = text.substring(0,$(this).val().indexOf('.'));
	// 					$(this).val(str_real);
	// 				}
	// 		});
	// 	}
	// 	triggerReferProduct();
	// });

	$(document).on('focus','.money, .price, .quantity, .weight, .measure',function(){
		$(this).val($(this).val().replace(/,/g,''));
        $(this).select();
	});
	// END : KienNT add format numeric 2017/02/23

	/**
	 * @author: VuongVT - 2016/10/26
	 * @reason: init for numeric control (decimal)
	 */
	// $(".numeric-decimal").autoNumeric('init', {aDec: '.', vMax: '999999999.00', vMin: '-999999999.00'});

	/***************************************START REGION**************************************************/
	/******************************************************************************************************
	 * @author: VuongVT - 2016/10/10
	 * @reason: popup jquery function
	 *****************************************************************************************************/
	// button close popup
	$(document).on('click', '#btn-close-popup', function() {
		parent.$.colorbox.close();
	});

	// close colobox Cm0120i
	$(document).on('click', '#btn-cancel', function() {
		parent.$.colorbox.close();
	});

	//Configure colorbox call back to resize with custom dimensions
	$.colorbox.settings.onLoad = function() {
		colorboxResize();
	}

	// Customize colorbox dimensions
	var colorboxResize = function(resize) {
		var width = $('#colorbox-width').val();
		var height = $('#colorbox-height').val();

		if(width == undefined || window.innerWidth < 900){
			width = '90%';
		}
		if(height == undefined){
			height = '85%';
		}

		$.colorbox.settings.height = height;
		$.colorbox.settings.width = width;

		//if window is resized while lightbox open
		if(resize) {
			$.colorbox.resize({
				'height': height,
				'width': width
			});
		}
	}

	//In case of window being resized
	$(window).resize(function() {
		colorboxResize(true);
	});

	/*********************************************END REGION**********************************************/

	/***************************************START REGION**************************************************
	 * @author: VuongVT - 2016/11/14
	 * @reason: time control
	 *
	 *****************************************************************************************************/
	$(document).on('keydown', 'input.time, input.second', function(event) {
		// Mac support
		if ((!((event.keyCode > 47 && event.keyCode < 58) // 0 ~
			// 9
			|| (event.keyCode > 95 && event.keyCode < 106) // numpad
			// 0 ~
			// numpad
			// 9
			|| event.keyCode == 116 // F5
			|| event.keyCode == 46 // del
			|| event.keyCode == 35 // end
			|| event.keyCode == 36 // home
			|| event.keyCode == 37 // ←
			|| event.keyCode == 39 // →
			|| event.keyCode == 8 // backspace
			|| event.keyCode == 9 // tab
			|| event.keyCode == 188 // ,
			|| event.keyCode == 190 // .
			|| event.keyCode == 110 // numpad .
			|| (event.shiftKey && event.keyCode == 35) // shift
			// +
			// end
			|| (event.shiftKey && event.keyCode == 36) // shift
			// +
			// home
			|| event.ctrlKey // allow all ctrl combination
			|| event.keyCode == 229 // ten-key processing
			))
			|| (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58)) // exlcude
		)
		event.preventDefault();
	});

	// input blur time
	/* DuyTP 2016/12/19 fix format when input */
	$(document).on('blur', 'input.time', function() {
		var string =   '';
		string     = padZeroLeft($(this).val(), 4);
		var reg1   = /^(([0-1][0-9])|(2[0-3])):[0-5][0-9]$/;// DuyTP 2017/03/01 only accept 00:00 ~ 23:59
		var reg2   = /^(([0-1][0-9])|(2[0-3]))[0-5][0-9]$/;// DuyTP 2017/03/01 only accept 00:00 ~ 23:59
		if (string.match(reg1)) {
			$(this).val(string);
		} else if (string.match(reg2)) {
			$(this).val( string.substring(0, 2) + ':' + string.substring(2));
		} else {
			$(this).val('');
		}
		if (!_validateTime($(this).val())) {
			$(this).val('');
		}
	});

	// focus time item and remove [:] character
	$(document).on('focus', 'input.time, input.second', function() {
		$(this).val($(this).val().replace(/:/g, ''));
		$(this).select();
	});

	// input blur second DuyTP 2017/02/13
	$(document).on('blur', 'input.second', function() {
		var string  =   '';
		string 		= padZeroLeft($(this).val(), 6);
		var reg1 	= /^(?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/;
		var reg2 	= /^(?:2[0-3]|[01][0-9])[0-5][0-9][0-5][0-9]$/;

		if (string.match(reg1)) {
			$(this).val(string);
		} else if (string.match(reg2)) {
			$(this).val( string.substring(0, 2) + ':' + string.substring(2,4) + ':' + string.substring(4));
		} else {
			$(this).val('');
		}
	});

	// input method for tel class
	$(document).on('keydown', 'input.tel, input.postal_code', function(event) {
		// var ctrlDown = event.ctrlKey||event.metaKey; //
		// Mac support
		if ((!((event.keyCode > 47 && event.keyCode < 58) // 0 ~
				// 9
				|| (event.keyCode > 95 && event.keyCode < 106) // numpad
				// 0 ~
				// numpad
				// 9
				|| event.keyCode == 116 // F5
				|| event.keyCode == 46 // del
				|| event.keyCode == 35 // end
				|| event.keyCode == 36 // home
				|| event.keyCode == 37 // ←
				|| event.keyCode == 39 // →
				|| event.keyCode == 8 // backspace
				|| event.keyCode == 9 // tab
				|| event.keyCode == 189 // -
				|| ($(this).hasClass('tel') && event.keyCode == 107) // +
				|| event.keyCode == 109 // numpad -
				|| event.keyCode == 173 // - (firefox only)
				///|| event.keyCode == 107 // numpad +
				//|| (event.shiftKey && event.keyCode == 187) // shift
				// +
				// add
				|| (event.shiftKey && event.keyCode == 35) // shift
				// +
				// end
				|| (event.shiftKey && event.keyCode == 36) // shift
				// +
				// home
				|| event.ctrlKey // allow all ctrl combination
				|| event.keyCode == 229 // ten-key processing
				))
				|| (event.shiftKey && (event.keyCode > 47
						&& event.keyCode < 58 || event.keyCode == 189)) // exlcude
		// Shift
		// +
		// [0~9]
		)
			event.preventDefault();
	});

	 //input method for date class
	$(document).on('keydown', 'input.datepicker, input.month', function(event){
		if (
				(   !(  (event.keyCode > 47 && event.keyCode < 58)  // 0 ~ 9
					||  (event.keyCode > 95 && event.keyCode < 106) // numpad 0 ~ numpad 9
					||  event.keyCode == 116    // F5
					||  event.keyCode == 46     // del
					||  event.keyCode == 35     // end
					||  event.keyCode == 36     // home
					||  event.keyCode == 37     //　←
					||  event.keyCode == 39     // →
					||  event.keyCode == 8      // backspace
					||  event.keyCode == 9      // tab
					||  event.keyCode == 191    // forward slash
					||  event.keyCode == 111    // divide
					||  (event.shiftKey && event.keyCode == 35) // shift + end
					||  (event.shiftKey && event.keyCode == 36) // shift + home
					||  event.ctrlKey // allow all ctrl combination
				)
			)
				||  (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58)) // exlcude Shift + [0~9]
			)
			event.preventDefault();
	});

	// blur tel
	// $(document).on('blur', 'input.tel', function() {
	// 	try {
	// 		var string  =   $(this).val();
	// 		var reg2    =   /^[0-9-]+$/;
	// 		if(!string.match(reg2)){
	// 			$(this).val('');
	// 		}
	// 	} catch (e) {
	// 		alert(e.message);
	// 	}
	// });

	// blur postal_code
	$(document).on('blur', 'input.postal_code', function() {
		var string = $(this).val();
		if (!_validateZipCd($(this).val())) {
			$(this).val('');
		}
	});

	// 2017/05/29: VuongVT: Edit for input full-size
	$(document).on('change',
			'input.tel, input.number-date, input.number, input.numeric, input.rate, input.percentage, input.postal_code, input.time, input.datepicker, input.month', function(e) { //2016/03/30 sangtk add postal_code_en
 	  	var newValue = convertKana($(this).val(), 'h', false);
        $(this).val(newValue);
	});

	$(document).on('change', 'input.money', function(e) { //2016/03/30 sangtk add postal_code_en
     	var newValue = convertKana($(this).val(), 'h', true);
        $(this).val(newValue);
	});
	//2017/05/29: End edit

	//esc to clear errors
	$("body").keydown(function (e) {
		if (e.keyCode === 27) {
			_clearErrors();
		}
	});

	if (parent.jQuery && parent.jQuery.colorbox) {
		jQuery(document).bind('keydown', function (e) {
			if (e.keyCode === 27) {
				e.preventDefault();
				parent.jQuery.colorbox.close();
			}
		});
	}
	//btn-change-password
	$(document).on('click', '#btn-change-password', function() {
		var data = {};
		var parent = $(this).parents('.popup');
		var input = parent.find('input');
		data.search = $(this).data('search');
		parent.addClass('popup-'+ data.search);
		showPopup('/popup/changePassword/index?' + _setGetPrams(data), function(){}, '65%', '55%');
	});
	$(document).on('keypress', '.numeric-only', function(event) {
	   	var $this   = $(this);
	   	var negative  = (typeof $this.attr('negative') === 'undefined')?0:(1*$this.attr('negative'));
	   	var text   = $(this).val();
	    if((negative != 1 || text.indexOf('-') > -1) && (event.which == 45) || (event.which == 46)) {
	    	event.preventDefault();
	    } else if(text.indexOf('-')==-1 && event.which==45 && negative==1) {
            $this.val('-'+text);
        }
	});
	/*********************************************END REGION**********************************************/

}

/**
 * add comma function
 * @param	nStr
 * @return	str
 */
function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

/**
 * calculate string to numeric
 * ex: 99,999.99 => 99999.99
 *
 * @author		:	ANS817 - 2018/01/009 - create
 * @params		:	str - string
 * @return		:	num - float
 */
function convertStringToNumeric(str) {
	try {
		var num = 0;

		if (str == '' || str == undefined) {
			num = 0;
		} else {
			if (str.indexOf(',') > -1) {
				num = parseFloat(str.replace(/,/g, ''));
			} else {
				num = parseFloat(str);
			}
		}

		return num;
	} catch (e) {
		alert('convertStringToNumeric: ' + e.message);
	}
}

/**
 * check all checkbox
 *
 * @author      :   duytp - 2016/11/22 - create
 * @author      :   Trieunb
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function checkAll(checkID){
	//check, uncheck All
	$(document).on('click', '#'+ checkID, function(){

		var isChecked = $('#' + checkID).is(":checked");

		$('.' + checkID).each(function(){
			if (isChecked) {
				$(this).prop('checked', true);
			} else {
				$(this).prop('checked', false);
			}

		});

	});

	//check, uncheck one -> all ?
	$(document).on('click', '.'+ checkID, function(){

		var isChecked = $(this).is(":checked");

		if (isChecked) {
			var allRowIsChecked = true;
			$('.' + checkID + ':visible').each(function(){
				if (!$(this).is(":checked")) {
					allRowIsChecked = false;
				}
			});
			if (allRowIsChecked == true) {
				$('#' + checkID).prop('checked', true);
			} else {
				if ($('#'+checkID).is(':radio')) {
					var name = $('#'+checkID).attr('name');
					$('input[name='+name+']').prop('checked', false);
				}
			}
			$(this).prop('checked', true);
		} else {
			$('#' + checkID).prop('checked', false);
		}

	});

}

/**
 * ADD NEW ROW TO TABLE
 *
 * @author      : tannq@ans-asia - 2016/11/28 - created
 * @param       : config.button: attibute event update row [class/html tag/html attributes] | attribute data-target required = id table
 * @param       : config.html : this call from file blade template or read from js
 * @param       : config.buttonRemove: event remove row
 * @return      : null
 * @access      : public
 * @see         : init
 */
// function addNewRowToTable(config,callback)
// {
// 	// if define var not found return error log
// 	"use strict";

// 	// Example of config
// 	// var config = {
// 	//     button: '[data-target="#table-area"]', ##### class or id ....
// 	//     html:html,
// 	// };

// 	// Begin event update
// 	$(config.button).on('click', function(e)
// 	{
// 		e.preventDefault();
// 		var table = $(this).data('target'); // call id table
// 		var tbody = table + ' tbody';
// 		var html  = config.html;
// 		var $this = $(this);
// 		if(typeof callback === "function") {
// 			if(callback($this) && typeof callback($this)==='string')
// 			{
// 				html = callback($this);
// 			}
// 		}
// 		if(html) {
// 			if (isEmptyHtml($(tbody))) {
// 				$(tbody).html(html);
// 				return;
// 			}
// 			$(tbody + " tr:last").after(html);
// 			initControls();
// 		}
// 		// disable event default

// 	});
// }
// /**
//  * Check empty content in html tag
//  *
//  * @author      :   tannq@ans-asia - 2016/11/28 - created
//  * @param       :   el: [$('#id')] ...
//  * @access      :   public
//  * @see         :   init
//  */
// function isEmptyHtml( el ){
// 	return !$.trim(el.html())
// }
/**
 * Clear all red items. Call when no error detected.
 */
function _clearErrors(){
	$('.error-item').removeErrorStyle();
	$('.error-item').removeClass('error-item').removeAttr('index');
	$('.error-tip-mesage').remove();
	$('.space-error').empty();
	$('.textbox-error').removeErrorStyle();
	$('.row-error').removeClass('row-error');
}
/**
 * focus first item on form
 */
function _focusFirstItem(){
	$('input:enabled:visible:not([readonly]), select:enabled:not([readonly]), textarea:enabled:not([readonly])').first().focus();
	// $(':input:enabled:not([readonly]):visible:first').focus();
}
/**
 * format datepicker
 */
function _formatDatepicker() {
	$(".datepicker").each(function() {
		try{
			if($(this).hasClass('hasDatepicker')){
				if($('#ui-datepicker-div').length>0)
					$('#ui-datepicker-div').remove();
				$(this).next('img').remove();
				$(this).removeClass('hasDatepicker');
				$(this).datepicker("destroy");
			}
		} catch(e) {
			console.log('dapicker destroy '+e.message);
		}
	});

	$( ".datepicker:not(:disabled):not([readonly]):visible" ).datepicker({
		showOn: "button",
		buttonImage: "/images/calendar-icon.ico",
		buttonText : '日付を選択してください',
		buttonImageOnly: true,
		changeYear: true,
		changeMonth: true,
		showButtonPanel: true,
		onSelect: function(d,i){
			if(d !== i.lastVal){
				$(this).change();
			}
			$(this).focus();
		}
	});
	$( ".datepicker:disabled, .datepicker[readonly]" ).datepicker({
		showOn: "button",
		buttonImage: "/images/calendar-icon.ico",
		buttonText : '日付を選択してください',
		buttonImageOnly: true,
		changeYear: true,
		changeMonth: true,
		showButtonPanel: true,
		disabled: true,
		onSelect: function(){
			$(this).focus();
		}
	});
	$(".datepicker").each(function() {
		if ($(this).is(':disabled')) {
			//".datepicker:disabled"
			$(this).datepicker( "option", "disabled", true);
			$(this).next('img').css('opacity', '0.5');
		} else {
			//".datepicker:not(:disabled):not([readonly]):visible"
			$(this).datepicker( "option", "disabled", false);
			$(this).next('img').removeClass('opacity');
		}
	});
}
/**
 * format year month picker
 */
function _formatYearMonthPicker(){
	// destroy month picker
	$(".month").each(function(){
		try{
			$(this).next().remove();
			$(this).next().remove();
		}catch(e){
			console.log('dapicker destroy '+e.message);
		}
	});
	// end destroy
	if( $('input.month') &&  $('input.month').length > 0 ) {
		$('input.month').each(function(){
			if($(this).is('[readonly]') || $(this).is('[disabled]')){
				$.appendYmpicker($(this),"","",true);
			} else {
				 $.appendYmpicker($(this));
			}
		});
	}

}
/**
 * format datepicker on lose focus
 */
function _autoFormattingDate(target){
	$(target).focusout(function(){
		var string = $(this).val();
	if(string.length == 8){
		string = string.substring(0, 4) + '/' + string.substring(4, 6) + '/' + string.substring(6);
	}
	var reg = /^((19|[2-9][0-9])[0-9]{2})[\/.](0[13578]|1[02])[\/.]31|((19|[2-9][0-9])[0-9]{2}[\/.](01|0[3-9]|1[0-2])[\/.](29|30))|((19|[2-9][0-9])[0-9]{2}[\/.](0[1-9]|1[0-2])[\/.](0[1-9]|1[0-9]|2[0-8]))|((((19|[2-9][0-9])(04|08|[2468][048]|[13579][26]))|2000)[\/.](02)[\/.]29)$/;
	if (string.match(reg)){
		$(this).val(string);
	} else {
		$(this).val('');
	}
	});
}
/**
 *
 * format year month on lose focus
 */
function _autoFormattingMonth(target){
	$(target).focusout(function(){
		var string = $(this).val();
		if(string.length == 6){
			string = string.substring(0, 4) + '/' + string.substring(4, 6);
		}
		var reg = /^((19|[2-9][0-9])[0-9]{2})[\/.](0[1-9]|1[0-2])$/;
		if (string.match(reg)){
			$(this).val(string);
		} else {
			$(this).val('');
		}
	});

}
/**
 *
 * prepare param to send to colorbox
 */
function _setGetPrams(obj) {
	var param = '';
	$.each(obj, function(key, element) {
		param += '&' + key + '=' + encodeURI(element);
	});
	return param.slice( 1 );
}
/**
 * validate zip code
 *
 * @param string
 * @returns {boolean}
 */
function _validateZipCd(postal_code) {
	try {
		postal_code = _formatString(postal_code);
		var reg1 = /^[0-9]{3}-[0-9]{4}$/;
		var reg2 = /^[0-9]{3}[0-9]{4}$/;
		//
		if (postal_code.match(reg1) || postal_code.match(reg2) || postal_code == '') {
			return true;
		} else {
			return false;
		}
	} catch (e) {
		alert('_validateZipCd: ' + e);
	}
}

/**
 * function enterKeySearch
 *
 * @author    : DuyTP - 2017/04/20 - create
 * @params    : data - object
 * @return    : null
 */
function _enterKeySearch() {
	//init enter search
	$(document).on('keydown', '.content', function(e){
		var focus_item = $('body').find('input:focus');
		if (e.keyCode == 13
				&& focus_item.attr('id')!='paggging-number'
				&& !focus_item.hasClass('report_point')
				&& !focus_item.hasClass('score')
				&& !focus_item.hasClass('average_score')
				&& !focus_item.hasClass('perfect_score')) {
			// $('#btn-search').trigger('click');
		}
	});
}
/**
 * function check regex for character
 * @author: VuongVT - 2016/12/16 - create
 *
 */
function isMatchCharacter(target) {
	// Regex for matching ALL Japanese common & uncommon Kanji
	var allJapaneseCommon = /^[一-龯]/;
	// Regex for matching Hirgana or Katakana or basic punctuation (、。’)
	var allHiraganaOrKatakana = /^[ぁ-んァ-ン]/;
	// var symbolsAndPunctuation = /^[\x3000-\x303F]/;
	// Regex for matching Hirgana or Katakana and random other characters
	var allHiraganaOrKatakanaAndOther = /^[ぁ-んァ-ン！：／]/;
	// Regex for matching full-width Letters (zenkaku 全角)
	var fullwidthLetter = /^[Ａ-ｚ]/;
	// Regex for matching hafl-width Letters (zenkaku 全角)
	var halfwidthLetter = /^[A-z]/;
	// Regex for matching half-width (hankaku) Katakana codespace characters (this is an old character set so the order is inconsistent with the hiragana)
	var halfwidthkatakanaOldcharacter = /^[ｦ-ﾟ]/;
	// Regex for matching full-width (zenkaku) Katakana codespace characters (includes non phonetic characters)
	var halfwidthkatakana = /^[ァ-ヶ]/;
	// Regex for matching Hiragana codespace characters (includes non phonetic characters)
	var hiraganaCodeSpace = /^[ぁ-ゞ]/;
	if (target.match(allJapaneseCommon) || target.match(allHiraganaOrKatakana)
		|| target.match(allHiraganaOrKatakanaAndOther) || target.match(fullwidthLetter)
		|| target.match(halfwidthkatakanaOldcharacter) || target.match(halfwidthkatakana)
		|| target.match(hiraganaCodeSpace)
		|| target.match(halfwidthLetter)) {
		return true;
	} else {
		return false;
	}
}
/**
 * function convert from hiragana number
 * @author: VuongVT - 2016/12/16 - create
 *
 */
function convertKana(target, type, isMoney) {
    try {
    	var newTarget = "";
    	for (var i = 0, len = target.length; i < len; i++) {
    		if (isMatchCharacter(target[i])) {
    			newTarget = "";
    			break;
    		} else {
    			newTarget = newTarget + target[i];
    		}
		}
        var _deleteStack = '';
        var katakana = new Array('0', '1', '2', '3', '4', '5', '6', '7',
                '8', '9', ',', '');
        var hiragana = new Array('０', '１', '２', '３', '４', '５', '６', '７',
                '８', '９', '、', '　');
        //
        if (type === 'h') {
            newTarget = _formatConvert(newTarget, hiragana, katakana);
            _deleteStack += katakana.join('');
        } else if (type === 'f') {
            newTarget = _formatConvert(newTarget, katakana, hiragana);
            _deleteStack += hiragana.join('');
        }

        if (isMoney == false && (newTarget.indexOf("．") >= 0 || newTarget.indexOf(".") >= 0 || newTarget.indexOf("・") >= 0)) {
        	return "";
        }

        if (isMoney == false) {
        	newTarget = newTarget.replace("，", "");
        	newTarget = newTarget.replace("．", "");
        } else {
        	newTarget = newTarget.replace("．", ".");
        }
        newTarget = newTarget.replace(",", "");
        newTarget = newTarget.replace("。", "");

        return newTarget;
    } catch (e) {
    	alert("C");
        return ('');
    }
}

function _formatConvert(target, original, format, escape) {
    try {
        var object = null;
        var i = 0;
        var len = original.length;
        //
        if (escape === true) {
            for (i = 0; i < len; i++) {
                object = new RegExp(_formatConvertEscapeCheck(original[i]),
                        'gm');
                target = target.replace(object, format[i]);
            }
        } else {
            for (i = 0; i < len; i++) {
                object = new RegExp(original[i], 'gm');
                target = target.replace(object, format[i]);
            }
        }
        delete (object);
        return (target);
    } catch (e) {
        return ('');
    }
}

function _formatConvertEscapeCheck(character) {
    try {
        var escape = '\\/^$*+-?{|}[].()';
        var i = 0;
        var len = escape.length;
        for (i = 0; i < len; i++) {
            if (character.indexOf(escape[i], 0) !== -1) {
                return ('\\' + character);
            }
        }
        return (character);
    } catch (e) {
        return ('');
    }
}
/**
 * function dynamically set tabindex
 * @author: DuyTP - 2016/12/16 - create
 * @author: DuyTP - 2017/02/09 - update
 * @modify: Trieunb - 2017/08/08 - update
 * @modify: HaVV - 2018/01/12 - update
 */
function _setTabIndex() {
	var index = 0;
	$(":input").each(function (i) {
		$(this).attr('tabindex', i + 1);
		if ($(this).hasClass('hasDatepicker') || $(this).hasClass('month')) {
			$(this).next('.ui-datepicker-trigger').attr('tabindex', i + 1);
		}
		index = i+1;
	});
	// autoTabindexButton(index, parentClass = '.navbar-nav', childClass = '.btn-link');
	// $('.btn-add-row').attr('tabindex', '-1')
	// $('.remove-row').attr('tabindex', '-1')
	$('input[disabled], input[readonly], textarea[disabled], textarea[readonly], select[disabled], button[disabled]').attr('tabindex', '-1');
	// $('input:first').focus();
	$(':input:visible:not([disabled]):not([readonly]):first').focus();
}
/**
 * set tabindex Datepicker
 * @author: HaVV - 2018/01/12 - create
 */
function _setTabindexForDatepicker() {
	$(":input").each(function (i) {
		if ($(this).hasClass('hasDatepicker') || $(this).hasClass('month')) {
			var index = $(this).attr('tabindex');
			$(this).next('.ui-datepicker-trigger').attr('tabindex', index);
		}
	});
}
/**
 * function set table index for table
 *
 * @author : Trieunb - 2017/08/16 - create
 * @author :
 * @return : true/false
 * @access : public
 * @see :
 */
function _setTabIndexTable(table) {
	var start 	= parseInt($('.'+table+' tr input:first').attr('tabindex'));
	if (isNaN(start)) {
		start = 1;
	}
 	var start_2 = start + 1;
	$('.'+table+' tbody tr').each(function(i) {
		$(this).children().each(function(j) {
			$(this).find('.tab-top').attr('tabindex', i + start);

			if ($(this).find('.tab-top').hasClass('datepicker') || $(this).find('.tab-top').hasClass('month')) {
				$(this).find('.tab-top').next().attr('tabindex', i + start);
			}

			$(this).find('.tab-bottom').attr('tabindex', i + start_2);

		});
		start 		= start_2;
		start_2 	= 1 + start_2;
	});
}

/**
 * update tabl after drap or add add new row or remove row
 *
 * @author      :   Trieunb - 2017/08/03 - create
 * @param       :   object class table
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function _updateTable(table, isNumberLine) {
	$('.'+table+' tbody tr').css('background-color','#FFFFFF');
    $('.'+table+' tbody tr:odd').css('background-color','#FFF2CC');
    if (isNumberLine) {
		$('.'+table+' tbody tr').each(function(i){
	    	$(this).find('.drag-handler').text(i+1);
	    });
	}
	_setTabIndex();
	_setTabIndexTable(table);
}

/**
 * check Date From/To
 *
 * @author : Trieunb - 2017/02/14 - create
 * @author :
 * @return : true/false
 * @access : public
 * @see :
 */
function _checkDateFromTo(obj) {
	var obj 		=	$('.'+obj);
	var dateFrom 	=	obj.find('.date-from').val();
	var dateTo 		=	obj.find('.date-to').val();
	_clearErrors();
	try {
        var isCheck = true;
        var message = _text['E014'];
        if(!_validateFromToDate(dateFrom, dateTo)) {
            isCheck = false;
            obj.find('.date-from').errorStyle(message);
            obj.find('.date-to').errorStyle(message);
        } else {
            _removeErrorStyle(obj.find('.date-from'));
            _removeErrorStyle(obj.find('.date-to'));
        }
        //return error check
        return isCheck;
    } catch(e) {
        alert('checkDate' + e.message);
    }
}

/**
 * remove error style
 *
 * @author : Trieunb - 2017/02/14 - create
 * @author :
 * @return :
 * @access : public
 * @see :
 */
function _removeErrorStyle(obj) {
	obj.removeErrorStyle();
    obj.removeClass('error-item');
}
/**
 * _validateFromToDate
 *
 * @author : Trieunb - 2017/02/14 - create
 * @author :
 * @return : true/false
 * @access : public
 * @see :
 */
function _validateFromToDate(from, to) {
    try {
        if (from != '' && to != '') {
            var fromDate 	= new Date(from);
            var toDate 		= new Date(to);
            if (fromDate.getTime() > toDate.getTime()) {
                return false;
            }
        }
        return true;
    } catch (e) {
        alert('_validateFromToDate:' + e.message);
    }
}
/**
 * drap and drop row tabl
 *
 * @author      :   Trieunb - 2017/08/03 - create
 * @param       :   object class table
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function _dragLineTable(table, isNumberLine, callback) {
  	Sortable.create(
        $('.'+table+' tbody')[0],
        {
            animation: 150,
            scroll: true,
            handle: '.drag-handler',
            onEnd: function (evt) {
            	_updateTable(table, isNumberLine);
            	//set tabindex for element dragged
            	$('.'+ table + ' tbody tr:eq(' + evt.newIndex + ') :input:first').focus();
            	if (typeof callback == 'function') {
					callback();
				}
            }
        }
    );

}
/**
 * sort column table
 *
 * @author      :   Trieunb - 2017/08/09 - create
 * @param       :   object class table
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function _sortTableEvent(table) {
	$(document).on('click', '#' + table + ' thead th', function() {
		var index = $(this).index();
		if (!$(this).hasClass('check-box')) {
			_sortTable(index, table);
		}
	});
}

/**
 * subtract and plus date current
 *
 * @author      :   Trieunb - 2017/08/10 - create
 * @param       :   string date
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function _subAndPlusDate(date, operation) {
	try {
		var d = new Date();
		if (operation == '-') {
			d.setDate(d.getDate() - parseInt(date));
		} else {
			d.setDate(d.getDate() + parseInt(date));
		}
	    var date = _formatDate(d);
	    return date;
    } catch (e) {
		alert('_numDate: ' + e.message);
	}
}
/**
 * format date in js to yyyy/mm/đ
 *
 * @author      :   Trieunb - 2017/10/12 - create
 * @param       :   date object
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function _formatDate(date) {
	try {
		if (date == '' || typeof date == "undefined") {
			date 	= new Date();
		}
		var day 	= date.getDate();
	    var month 	= date.getMonth() + 1;
	    var year 	= date.getFullYear();
	    if (day < 10) {
	        day = "0" + day;
	    }
	    if (month < 10) {
	        month = "0" + month;
	    }
	    var date = year + "/" + month + "/" + day;
	    return date;
	} catch (e) {
		alert('_formatDate: ' + e.message);
	}
}
function _numDate(date) {
	try {
		var diffDays = 0;
		if (date.length == 8) {
			var y = date.substring(0, 4);
			var m = parseInt(date.substring(4, 6), 10);
			var d = date.substring(6, 8);
			date = y + "/" + m + "/" +d;
		}
		if (date.length != 9 && date.length != 10) {
			diffDays = 0;
		} else {
			var current_date 	= moment().format("YYYY/MM/DD");
 			current_date		= moment(current_date);
			var date2 			= moment(date); // some date
			var diffDays  		= date2.diff(current_date, 'days') // 1
		}
		return diffDays ;
	} catch (e) {
		alert('_numDate: ' + e.message);
	}
}
/**
 * disabled all input
 * @author      :   Trieunb - 2017/08/28 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function _disabldedAllInput(callback) {
	try {
		$(":input").each(function (i) {
			$(this).attr('disabled', 'disabled');
		});
		$('input[type=file]').attr('disabled', false);
		if (typeof callback == 'function') {
			callback();
		}
	} catch (e) {
		alert('_disabldedAllInput: ' + e.message);
	}
}

/**
 * check approved
 * @author      :   Trieunb - 2017/08/29 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function _checkApproved(table) {
	var flag = true;
	$('.' + table + ' tbody input[type="checkbox"]:checked').each(function() {
		var	approve = $(this).parent().parent().find('.approve').attr('data-approve');
		if (approve == 0) {
			flag = false;
		}
	});
	return flag;
}
/**
 * api conver currencty
 * @author      :   Trieunb - 2017/08/10 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function converCurrency() {
	// set endpoint and your access key
	endpoint 	= 'live';
	access_key 	= '37092d6d36da4f96d2547abf64d4afb6';
	// define from currency, to currency, and amount
	from 		= 'USD';
	to 			= 'VND';
	// execute the conversion using the "convert" endpoint:
	$.ajax({
	    url: 'http://apilayer.net/api/' + endpoint + '?access_key=' + access_key +'&curresourcencies=' + from + '&currencies=' + to + '&format =1',
	    dataType: 'jsonp',
	    success: function(json) {
	        // access the conversion result in json.result
	        console.log(json);

	    }
	});

}
/**
 * init row table at mode add new
 *
 * @author		:	Trieunb - 2017/08/28 - create
 * @params		:	table
 * @params		:	obj line new
 * @params		:	int rw: số row khởi tạo cho table khi ở mode I
 * @return		:	null
 */
function _initRowTable(table, objLineNew, numRow, callback) {
	$('#'+table+' tbody').html('');
	// if (mode == 'I') {
		for (var i = 0; i < numRow; i ++) {
			var row = $("#" + objLineNew + " tr").clone();
			$('#' + table + ' tbody').append(row);
		}
	// }
	if (typeof callback == 'function') {
		callback();
	}
	_updateTable(table, true);
}
/**
 * add new row table
 *
 * @author		:	Trieunb - 2017/08/28 - create
 * @params		:	obj talbe, obj line add new, total row need add
 * @return		:	null
 */
function _addNewRowTable(table, objLineNew, totalLine, callback) {
	try	{
		var row = $("#" + objLineNew + " tr").clone();
		var col_index =  $('.'+ table + ' tbody tr').length;
		if (totalLine > 0 && !isNaN(totalLine)) {
			if (col_index < totalLine) {
				$('.'+ table + ' tbody').append(row);
			}
		} else {
			$('.'+ table + ' tbody').append(row);
		}
		if (typeof callback == 'function') {
			callback();
		}
		// _updateTable(table, true);
		//set first forcus input in row
		$('.'+ table + ' tbody tr:last :input:first').focus();
	} catch (e) {
		alert('_addNewRowTable: ' + e.message);
	}
}
/**
 * remove row table
 *
 * @author		:	Trieunb - 2017/08/28 - create
 * @params		:	talbe, obj, index row
 * @return		:	null
 */

 function _removeRowTable(table, obj, idMessage, isNumberLine, callback) {
 	try	{
 		var index = obj.closest('tr').index();
	 	jMessage(idMessage, function(r) {
			if(r) {
				// remove line
				obj.closest('tr').remove();
				// check callback function
				if (typeof callback == 'function') {
					callback();
				}
				// update table when remove line
				_updateTable(table, isNumberLine);

				// jSuccess('削除しました。', 1, function(r) {
				// 	var countRow = $('.' + table + ' tbody tr:last').index();
				// 	// if remover row last then set set focus...
				// 	if (countRow == (index - 1)) {
				// 		index = index - 1;
				// 	}
				// 	$('.' + table + ' tbody tr:eq('+index+') :input:first').focus();
				// });
			}
		});
	} catch (e) {
		alert('_removeRowTable: ' + e.message);
	}
 }
 /**
 * get Data Library code for combobox
 *
 * @author      :   ANS806 - 2017/07/05
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   saveFunction
 */
 function _getComboboxData(countryCode, libCd, callback) {
    try {
        return $.ajax({
            type        :   'GET',
            url         :   '/common/combobox/get-combobox-data',
            dataType    :   'json',
            data 		: 	{
            					countryCode : countryCode,
            					libCd 		: libCd
            				},
            success: function(res) {
                if (res.response) {
                    html = '<option></option>';
                    var lib_val_nm 	= "lib_val_nm_e";
                    var hdn_nm 		= "lib_val_nm_j";
                	if (countryCode == 'JP') {
                		lib_val_nm 	= "lib_val_nm_j";
                		hdn_nm 		= "lib_val_nm_e";
                	}

                	var selected 		= '';
                	var data_selected 	= $('.'+libCd).attr('data-lib-cd');
                	var data_ini_target = $('.'+libCd).attr('data-ini-target');
                	if (res.data != null) {
                		$.each(res.data, function(item, value) {
		                	if (value.lib_val_cd == data_selected || (data_ini_target == "true" && value.ini_target_div === '1')) {	//ini_target_div=1: Selected 2018/05/03
		                		selected = 'selected';
		                	} else {
		                		selected = '';
		                	}
		                	var name = value[lib_val_nm];
			                html += "<option " + selected +
				                		" value=" + value.lib_val_cd +
				                		" data-ctl1='" + value.lib_val_ctl1 +
				                		"' data-ctl2='" + value.lib_val_ctl2 +
				                		"' data-ctl5='" + value.lib_val_ctl5 +
				                		"' data-ctl6='" + value.lib_val_ctl6 +
				                		"' data-nm-j='" + value.lib_val_nm_j +
				                		"' data-nm-e='" + value.lib_val_nm_e +
				                		"' data-ini_target_div='" + value.ini_target_div +
				                		"' data-hdn_nm='" + value[hdn_nm] +
				                		"'>" + name +
				                	"</option>";
		                });
                	}
	                $('.'+libCd).empty();
                    $('.'+libCd).append(html);
                    $('.'+libCd).trigger('change');
                    // check callback function
					if (typeof callback == 'function') {
						callback();
					}
                }
            }
        }).done(function(res){
        	if (sessionStorage.getItem('detail')) {
				_fillDataConditionSearch();
				// sessionStorage.removeItem('detail');
			}
        });
    } catch (e) {
        alert('_getComboboxData' + e.message);
    }
 }
 /**
 * change display name jp or name en  for combobox
 *
 * @author      :   ANS806 - 2017/07/05
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   saveFunction
 */
function _changeNmCombobox(country_div, obj) {
	try {

		$("."+obj+" > option").each(function() {
			var name_jp 	=	$(this).text();
			var hdn_nm 		=	name_jp;
			if (country_div == 'JP') {
				hdn_nm 		=	$(this).attr('data-nm-j');
			} else {
				hdn_nm 		=	$(this).attr('data-nm-e');
			}
		    $(this).text(hdn_nm);
		});
	} catch (e)  {
        alert('changeNmCombobox:  ' + e.message);
    }
}
 /**
 * _add Trade Terms
 *
 * @author      :   ANS806 - 2017/07/05
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   saveFunction
 */
 function _addFreigtAndInsurance(lib_val_ctl1, lib_val_ctl2) {
    try {
        if (lib_val_ctl1 == '1') {
			$('.table-total tbody tr td .title-freight').removeClass('hidden');
			$('.table-total tbody tr td .value-freight').removeClass('hidden');
		} else {
			$('.table-total tbody tr td .title-freight').addClass('hidden');
			$('.table-total tbody tr td .value-freight').addClass('hidden');
			$('.TXT_freigt_amt').val('');
		}

		if (lib_val_ctl2 == '1') {
			$('.table-total tbody tr td .title-insurance').removeClass('hidden');
			$('.table-total tbody tr td .value-insurance').removeClass('hidden');
		} else {
			$('.table-total tbody tr td .title-insurance').addClass('hidden');
			$('.table-total tbody tr td .value-insurance').addClass('hidden');
			$('.TXT_insurance_amt').val('');
		}
    } catch (e) {
        alert('_addFreigtAndInsurance' + e.message);
    }
 }
 /**
 * _add Trade to Table Total by country
 *
 * @author      :   ANS806 - 2017/07/05
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   saveFunction
 */
 function _addTaxRate(name) {
    try {
    	var tax_rate 	=	$('.tax_rate').text().trim();
        if (name == 'JP') {
			$('.table-total tbody tr td .title-jp').removeClass('hidden');
			$('.table-total tbody tr td .value-jp').removeClass('hidden');
			$('.DSP_tax_amt').text(tax_rate);
		} else {
			$('.table-total tbody tr td .title-jp').addClass('hidden');
			$('.table-total tbody tr td .value-jp').addClass('hidden');
		}

    } catch (e) {
        alert('_addTaxRate' + e.message);
    }
 }
  /**
 * _ operation total Tax Table
 *
 * @author      :   ANS806 - 2017/07/05
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   saveFunction
 */
 function _totalTaxTable() {
 	try {
 		var total_header 	=	$('.total-header').text().trim();
 		var freight_val		=	0;
 		var insurance_val	=	0;
 		var tax_jp 			=	0;
 		if (!$('.value-freight').hasClass('hidden')) {
	 		freight_val 	= 	$('.value-freight').val().trim();
		}
 		if (!$('.value-insurance').hasClass('hidden')) {
			insurance_val 	= 	$('.value-insurance').val().trim();
		}
 		if (!$('.value-jp').hasClass('hidden')) {
			tax_jp 			= 	$('.value-jp').val().trim();
		}
		var total_footer 	=	parseFloat(freight_val) + parseFloat(insurance_val) + parseFloat(tax_jp) + parseFloat(total_header);
		// $('.total-footer').text(_convertMoneyToIntAndContra(total_footer));
	} catch (e) {
        alert('_totalTaxTable' + e.message);
    }
 }
   /**
 * conver int to money and
 *
 * @author      :   ANS806 - 2017/07/05
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   saveFunction
 */
 function _convertMoneyToIntAndContra(num) {
 	try {
 		if (!isNaN(num)) {
 			num = num.toString();
 		} else {
 			num =	'';
 		}
 		if (num.indexOf(',') > -1) {
 			num = num.replace(/,/g,'');
 		} else {
 			num = num.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
 		}
 		/*return (num != 0) ? num : '';*/
 		return num;
 	} catch (e) {
        alert('_convertMoneyToIntAndContra' + e.message);
    }
 }
 /**
 * Import CSV
 *
 * @author      :   ANS806 - 2017/10/10 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function _ImportCSV(input, url, callback) {
    try {
    	if (_isCSV(input)) {
    		var formData = new FormData();
	        formData.append('file', input[0].files[0]);
	        $.ajax({
	            url         :   url,
	            type        :   "POST",
	            data        :   formData,
	            processData :   false,
	            contentType :   false,
	            success     :   function(res) {
	            	if(res.error_cd != ''){
	            		jMessage(res.error_cd);
	            	} else if (res.response) {
	            		// jSuccess('保存しました。');
	            		jMessage('I010', function(r){
	            			if (r) {
	            				if(callback){
			            			callback(res.data);
			            		}
	            			}
	            		});
	            	} else {
	            		//catch DB error and display
		            	var msg_e999 = _text['E999'].replace('{0}', res.error);
		            	jMessage_str('E999', msg_e999, '', msg_e999);
	            	}
	            }
	        });
    	} else {
    		// jError('取込ファイル形式が違います。');
    		jMessage('E015');
    	}
    } catch(e) {
        alert('ImportCSV' + e.message);
    }
}
/**
 * check file import have extension is csv
 *
 * @author      :   ANS806 - 2017/10/10 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
 function _isCSV(input) {
 	try {
		var flag    = false;
		var pattern = "^.+\.(csv)$";
        if (input.val().match(pattern)) {
            flag = true;
        } else {
            flag = false;
        }
        return flag;
 	} catch(e) {
        alert('ImportCSV' + e.message);
    }
 }
/**
 * set tabindex
 *
 * @author      :   ANS796 - 2017/11/14 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function tabindex(start, parentClass, childClass) {
    try {
        var i = start;
        if (parentClass == null && childClass == null) return 0;

        $(parentClass).find(childClass + ':not(".disabled")').each(function(index,element) {
            $(element).attr('tabindex',i);
            i++;
        });
        return i;
    } catch (e) {
        alert('tabindex:  ' + e.message);
    }
}
/**
 * set tabindex for button pagination and item in table
 *
 * @author      :   ANS796 - 2017/11/14 - create
 * @param       :
 * @return      :   ''
 * @access      :   public
 * @see         :   init
 */
function autoTabindexInTable(start, parentClassTopDatatable, childClassTopDatatable, parentClassInDatatable, childClassInDatatable, parentClassBottomDatatable, childClassBottomDatatable) {
    try {
        var i = start;
        if (parentClassTopDatatable != null && childClassTopDatatable != null) {
            i = tabindex(i,parentClassTopDatatable,childClassTopDatatable);
        }
        if (parentClassInDatatable != null && childClassInDatatable != null) {
            i = tabindex(i,parentClassInDatatable,childClassInDatatable);
        }
        if (parentClassBottomDatatable != null && childClassBottomDatatable != null) {
            i = tabindex(i,parentClassBottomDatatable,childClassBottomDatatable);
        }
        return i;
    } catch (e) {
        alert('autoTabindexInTable:  ' + e.message);
    }
}
/**
 * set tabindex for button header
 *
 * @author      :   ANS796 - 2017/11/14 - create
 * @param       :
 * @return      :   ''
 * @access      :   public
 * @see         :   init
 */
function autoTabindexButton(start, parentClass, childClass) {
    try {
        var i = start;
        if (parentClass != null && childClass != null) {
            i = tabindex(i,parentClass,childClass);
        }
        return (i + 1);
    } catch (e)  {
        alert('autoTabindexButton:  ' + e.message);
    }
}
/**
 * round numeric
 *
 * @author      :   trieunb - 2017/11/15 - create
 * @author      :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
 function _roundNumeric(numeric, ctl_val, pos) {
 	try {
 		if (pos ==	undefined || pos == '') {
 			pos = 	0;
 		}

 		//sign of numeric
 		var sign = 1;
 		if (numeric >= 0 ) {
 			sign = 1;
 		} else {
 			sign = -1;
 		}

 		numeric 	=	Math.abs(numeric).toString();
 		if (numeric.indexOf(".") != -1) {
 			var	arr 		=	numeric.split(".");
	 		var num_real 	=	arr[0];
	 		var num_decimal =	arr[1];

	 		var num_decimal_r 	=	0;
	 		var num_decimal_l 	=	0;

	 		if (pos == 0) {
	 			num_decimal_r 	=	num_decimal.substr(0, 1);
	 			num_decimal_l 	=	num_decimal.substr(0, 1);
	 		} else {
	 			num_decimal_r 	=	num_decimal.substr(0, parseInt(pos));
	 			num_decimal_l 	=	num_decimal.substr(parseInt(pos), 2);
	 		}
	 		// round for tax rate
 			//四捨五入_Làm tròn bình thường
	 		if (ctl_val == 1) {
	 			if (num_decimal_l >= 5) {
	 				if (pos != 0) {
	 					length_r = num_decimal_r.length;

	 					if(num_decimal_r.substr(1,1) != 0) {
	 						num_decimal_r 	= 	parseInt(num_decimal_r)+1;
	 					} else {
	 						num_decimal_r 	= 	num_decimal_r.substr(1,length_r - 1) + (parseInt(num_decimal_r.substr(length_r - 1, length_r)) + 1)
	 					}
	 				} else {
	 					numeric 		=	parseInt(num_real)+1;
	 				}
	 			}
	 		}
	 		//切り上げ_Làm tròn lên
	 		if (ctl_val == 2) {
	 			if (num_decimal_l != 0) {
	 				if (pos != 0) {
	 					length_r = num_decimal_r.length;

	 					if(num_decimal_r.substr(1,1) != 0) {
	 						num_decimal_r 	= 	parseInt(num_decimal_r)+1;
	 					} else {
	 						num_decimal_r 	= 	num_decimal_r.substr(1,length_r - 1) + (parseInt(num_decimal_r.substr(length_r - 1, length_r)) + 1)
	 					}
	 				} else {
	 					numeric 		=	parseInt(num_real)+1;
	 				}
	 			}
	 		}
	 		//切り捨て_Làm tròn xuống
	 		if (ctl_val == 3) {
	 			if (pos != 0) {
	 				num_decimal_r 	= 	num_decimal_r;
	 			} else {
	 				numeric 		=	num_real;
	 			}
	 		}
	 		//四捨五入_Làm tròn bình thường
	 		if (ctl_val == 4) {
	 			if (num_decimal_l > 5) {
	 				if (pos != 0) {
	 					length_r = num_decimal_r.length;

	 					if(num_decimal_r.substr(1,1) != 0) {
	 						num_decimal_r 	= 	parseInt(num_decimal_r)+1;
	 					} else {
	 						num_decimal_r 	= 	num_decimal_r.substr(1,length_r - 1) + (parseInt(num_decimal_r.substr(length_r - 1, length_r)) + 1)
	 					}
	 				} else {
	 					numeric 		=	parseInt(num_real)+1;
	 				}
	 			}
	 		}
	 		//偶数丸め_Làm tròn lấy chẵn - pending
	 		//
	 		if (pos == 0) {
	 			numeric = parseInt(numeric);
	 		} else {
	 			if (num_decimal_r.toString().length > pos) {
		 			numeric = parseInt(num_real)+1;
	 			} else {
	 				numeric = num_real + '.' + num_decimal_r;
	 			}
	 		}
 			numeric 	=	numeric.toString();
 		}
 		return sign * parseFloat(numeric.replace(/\.0$/,''));
 	} catch (e)  {
        alert('_roundNumeric:  ' + e.message);
    }
 }
/**
 * setup maxlength common
 *
 * @author      :   trieunb - 2017/11/15 - create
 * @author      :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function _setMaxLength() {
	//set maxlength of input common
 	var maxlength = {
 		'10' 	: ['lib_val_cd'],
 		'50' 	: ['lib_val_ab_j', 'lib_val_ab_e', 'lib_val_ctl1', 'lib_val_ctl2', 'lib_val_ctl3',
	 				'lib_val_ctl4', 'lib_val_ctl5', 'lib_val_ctl6', 'lib_val_ctl7', 'lib_val_ctl8',
	 				'lib_val_ctl9', 'lib_val_ctl10'
	 			],
 		'100' 	: ['lib_val_nm_j', 'lib_val_nm_e'],
 	};

 	for (var keyObj in maxlength) {
 		var obj = maxlength[keyObj];
 		if (obj.length > 0) {
 			for (var key in obj) {
	 			$(".TXT_"+obj[key]).attr("maxlength", keyObj);
	 		}
 		}
 	}
}

/**
 * convert halfsize to fullsize character
 *
 * @author      :   ANS817 - 2017/12/11 - create
 * @author      :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function toHalfsize(chars) {
    var str = '';
    for(var i=0, l=chars.length; i<l; i++) {
		var c = chars[i];
		c     = c.charCodeAt(0);

        // make sure we only convert half-full width char
        if (c >= 0xFF00 && c <= 0xFFEF) {
           c = 0xFF & (c + 0x20);
        }

        str += String.fromCharCode(c);
    }

    return str;
}

/**
 * init disable ime
 * using for key item
 * convert:
 *		- alphanumeric fullsize to halfsize
 *		- character katakana, hiragana, Kanji to space(0)
 *
 * @author      :   ANS817 - 2017/12/11 - create
 * @author      :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function initDisableIME(){
    // ime-modeが使えるか
    var supportIMEMode = ('ime-mode' in document.body.style);

    // 非ASCII
    var noSbcRegex = /[^\x00-\x7E]+/g;

    //set attribute of input.disable-ime is type=tel
    $('input.disable-ime').attr('type', 'tel');
    //set css disable imd mode for IE, Firefox
    $('input.disable-ime').css('ime-mode', 'disabled');

    $('input.disable-ime').on('blur paste', function(e) {
        if (e.type == 'keyup' || e.type == 'blur') {
            if (supportIMEMode) return;
        }

        var target = $(this);

        window.setTimeout( function() {
            if(!target.val().match(noSbcRegex)) {
                return;
            }

			var newValue = toHalfsize(target.val());
			newValue     = newValue.replace(noSbcRegex, '');
            target.val( newValue );
        }, 1);
    });
}
/**
 * get product information
 *
 * @author      :   ANS796 - 2017/12/12
 * @updater     :   ANS817 - 2017/12/26
 * @param       : 	product_cd 	- string 		- key refer
 * @param       : 	element 	- DOM element
 * @param       : 	callback 	- function
 * @param       : 	isSearch 	- bool 			- default is false
 * @return      :   null
 * @access      :   public
 * @see         :   saveFunction
 */
 function _getProductName(product_cd, element, callback, isSearch) {
    try {
    	if (isSearch == undefined) {
			isSearch = false;
		}
        $.ajax({
            type        :   'POST',
            url         :   '/common/refer/product-cd',
            dataType    :   'json',
            data 		: 	{
            					product_cd : product_cd
            				},
            success: function(res) {
                if (res.response) {
                	if (res.data != null) {
                		//remove error
                		_removeErrorStyle(element.parents('.popup').find('.product_cd'));
                		element.parents('.popup').find('.product_cd').val(res.data['product_cd']);
                		element.parents('.popup').find('.product_nm').text(res.data['item_nm_j']);
                	}else{
                		if (!isSearch) {
	                		element.parents('.popup').find('.product_cd').val('');
	                	}
                		element.parents('.popup').find('.product_nm').text('');
                	}
                }else{
                	if (!isSearch) {
                		element.parents('.popup').find('.product_cd').val('');
                	}
            		element.parents('.popup').find('.product_nm').text('');
                }
                //element.parents('.popup').find('.product_cd').focus();

                // check callback function
				if (typeof callback == 'function') {
					callback();
				}
            }
        });
    } catch (e) {
        console.log('_getProductName' + e.message);
    }
 }
 /**
 * get client information
 *
 * @author      :   ANS796 - 2017/12/12
 * @updater     :   ANS817 - 2017/12/25
 * @param       : 	client_cd 	- string 		- key refer
 * @param       : 	element 	- DOM element
 * @param       : 	callback 	- function
 * @param       : 	isSearch 	- bool 			- default is false
 * @return      :   null
 * @access      :   public
 * @see         :   saveFunction
 */
 function _getClientName(client_cd, element, callback, isSearch) {
    try {
    	if (isSearch == undefined) {
			isSearch = false;
		}
        $.ajax({
            type        :   'POST',
            url         :   '/common/refer/client-cd',
            dataType    :   'json',
            data 		: 	{
            					client_cd : client_cd
            				},
            success: function(res) {
                if (res.response) {
                	if (res.data != null) {
                		//remove error
                		_removeErrorStyle(element.parents('.popup').find('.TXT_client_cd'));
                		element.parents('.popup').find('.TXT_client_cd').val(res.data['client_cd']);
                		element.parents('.popup').find('.client_nm').text(res.data['client_nm']);
                	} else {
                		if (!isSearch) {
	                		element.parents('.popup').find('.TXT_client_cd').val('');
	                	}
                		element.parents('.popup').find('.client_nm').text('');
                	}
                }else{
                	if (!isSearch) {
                		element.parents('.popup').find('.TXT_client_cd').val('');
                	}
            		element.parents('.popup').find('.client_nm').text('');
                }
                //element.parents('.popup').find('.TXT_client_cd').focus();

                // check callback function
				if (typeof callback == 'function') {
					callback();
				}
            }
        });
    } catch (e) {
        console.log('_getClientName' + e.message);
    }
 }

 /**
 * refer user cd
 *
 * @author : ANS806 - 2017/12/12 - create
 * @params :
 * @return : null
 * @access : public
 * @see :
 */
function _referUser(user_cd, element, callback, isSearch) {
	try	{
		if (isSearch == undefined) {
			isSearch = false;
		}
		$.ajax({
			type 		: 'GET',
			url 		: '/common/refer/refer-user',
			dataType	: 'json',
			data 		: {user_cd : user_cd},
			success: function(res) {
				if (res.response) {
					//remove error
                	_removeErrorStyle(element.parents('.popup').find('.user_cd'));
					element.parents('.popup').find('.user_cd').val(res.data.user_cd);
					element.parents('.popup').find('.user_nm').text(res.data.user_nm);
				} else {
					if (!isSearch) {
						element.parents('.popup').find('.user_cd').val('');
					}
					element.parents('.popup').find('.user_nm').text('');
				}
				//element.parents('.popup').find('.user_cd').focus();

				// check callback function
				if (typeof callback == 'function') {
					callback();
				}
			}
		});
	} catch (e) {
		console.log('referUser: ' + e.message);
	}
}
/**
 * refer library city and country
 *
 * @author : ANS806 - 2017/12/07 - create
 * @params :
 * @return : null
 * @access : public
 * @see :
 */
function _referCity(city_div, element_city, element_country, callback, isSearch) {
	try	{
		if (isSearch == undefined) {
			isSearch = false;
		}
		$.ajax({
			type 		: 'GET',
			url 		: '/common/refer/refer-city',
			dataType	: 'json',
			data 		: {country_div : city_div},
			success: function(res) {
				var data 	= 	{};
				if (res.response) {
					data 	=	res.data;
					//remove error
                	_removeErrorStyle(element_city.parents('.popup').find('.city_cd'));
					element_city.parents('.popup').find('.city_cd').val(data.city_div);
					element_city.parents('.popup').find('.city_nm').text(data.city_nm);
					element_country.parents('.popup').find('.country_cd').val(data.country_div);
					element_country.parents('.popup').find('.country_nm').text(data.country_nm);
					// clear message error for id country_cd
					if (data.country_div != '') {
						element_country.parents('.popup').find('.country_cd').focus().blur();
					}
				} else {
					if (!isSearch) {
						element_city.parents('.popup').find('.city_cd').val('');
					}
					element_country.parents('.popup').find('.country_cd').val('');
					element_city.parents('.popup').find('.city_nm').text('');
					element_country.parents('.popup').find('.country_nm').text('');
				}
				// check callback function
				if (typeof callback == 'function') {
					callback();
				}
			}
		});
	} catch (e) {
		console.log('referCity: ' + e.message);
	}
}
/**
 * refer library country
 *
 * @author : ANS806 - 2017/12/07 - create
 * @params :
 * @return : null
 * @access : public
 * @see :
 */
function _referCountry(country_div, element_city, element_country, callback, isSearch) {
	try	{
		if (isSearch == undefined) {
			isSearch = false;
		}
		$.ajax({
			type 		: 'GET',
			url 		: '/common/refer/refer-country',
			dataType	: 'json',
			data 		: {country_div : country_div},
			success: function(res) {
				var data = {};
				if (res.response) {
					data 	=	res.data;
					//remove error
                	_removeErrorStyle($(element_country).parents('.popup').find('.country_cd'));
					$(element_country).parents('.popup').find('.country_cd').val(data.country_div);
					$(element_country).parents('.popup').find('.country_nm').text(data.country_nm);
				} else {
					if (!isSearch) {
						$(element_country).parents('.popup').find('.country_cd').val('');
					}
					$(element_country).parents('.popup').find('.country_nm').text('');
				}
				// set blank for city cd and city nm
				if(!($(element_city).parents('.popup').find('.city_cd').val() == '999' && element_country.attr('name') == 'TXT_dest_country_div')){
					$(element_city).parents('.popup').find('.city_cd').val('');
					$(element_city).parents('.popup').find('.city_nm').text('');
				}
				// check callback function
				if (typeof callback == 'function') {
					callback();
				}
			}
		});
	} catch (e) {
		console.log('referCountry: ' + e.message);
	}
}
/**
 * refer accept
 *
 * @author : ANS806 - 2017/12/18 - create
 * @updater     :   ANS817 - 2017/12/26
 * @param       : 	accept_cd 	- string 		- key refer
 * @param       : 	element 	- DOM element
 * @param       : 	callback 	- function
 * @param       : 	isSearch 	- bool 			- default is false
 * @return : null
 * @access : public
 * @see :
 */
function _referPiAccept(accept_cd, element, callback, isSearch) {
	try	{
		if (isSearch == undefined) {
			isSearch = false;
		}
		$.ajax({
			type 		: 'GET',
			url 		: '/common/refer/refer-pi-accept',
			dataType	: 'json',
			data 		: {accept_cd : accept_cd},
			success: function(res) {
				if (res.response) {
					//remove error
                	_removeErrorStyle(element.parents('.popup').find('.accept_cd'));
					element.parents('.popup').find('.accept_cd').val(res.data.accept_cd);
					element.parents('.popup').find('.accept_nm').text(res.data.accept_nm);
				} else {
					if (!isSearch) {
						element.parents('.popup').find('.accept_cd').val('');
					}
					element.parents('.popup').find('.accept_nm').text('');
				}
				//element.parents('.popup').find('.accept_cd').focus();

				// check callback function
				if (typeof callback == 'function') {
					callback();
				}
			}
		});
	} catch (e) {
		console.log('referaccept: ' + e.message);
	}
}
/**
 * get tax rate
 *
 * @author : ANS806 - 2017/12/05 - create
 * @params :
 * @return : null
 * @access : public
 * @see :
 */
function _getTaxRate(date, callback) {
	try {
		$.ajax({
	        type        :   'GET',
	        url         :   '/common/refer/get-tax-rate',
	        dataType    :   'json',
	        data        :   {date : date},
	        success: function(res) {
	            if (res.response == true) {
	            	var _tax_rate = res.tax_rate/100;
	            	$('.tax_rate').text(_tax_rate)
	            }
	            // check callback function
				if (typeof callback == 'function') {
					callback();
				}
	        }
	    });
	} catch(e) {
        console.log('getTaxRate' + e.message)
    }
}
/**
 *refer m item
 *
 * @author : ANS806 - 2017/12/20 - create
 * @params :
 * @return : null
 * @access : public
 * @see :
 */
function _referMItem(data, element, callback, isSearch) {
	try{
		if (isSearch == undefined) {
			isSearch = false;
		}
		$.ajax({
			type 		: 'GET',
			url 		: '/common/refer/refer-item',
			dataType	: 'json',
			data 		: data,
			success: function(res) {
				if (res.response) {
					//remove error
                	_removeErrorStyle(element.parents('.popup').find('.componentproduct_cd'));
					element.parents('.popup').find('.componentproduct_cd').val(res.data.item_cd);
					element.parents('.popup').find('.componentproduct_nm').text(res.data.item_nm);
				} else {
					if (!isSearch) {
						// element.parents('.popup').find('.componentproduct_cd').val('');
					}
					element.parents('.popup').find('.componentproduct_nm').text('');
				}
				//element.parents('.popup').find('.componentproduct_cd').focus();

				// check callback function
				if (typeof callback == 'function') {
					callback();
				}
			}
		});

	} catch(e) {
        console.log('referMItem' + e.message)
    }
}
/**
 *refer data warehouse
 *
 * @author : ANS804 - 2017/12/26 - create
 * @params :
 * @return : null
 * @access : public
 * @see :
 */
function _referWarehouse(data, element, callback, isSearch) {
	try{
		if (isSearch == undefined) {
			isSearch = false;
		}
		$.ajax({
			type 		: 'GET',
			url 		: '/common/refer/refer-warehouse',
			dataType	: 'json',
			data 		: {warehouse_div : data},
			success: function(res) {
				if (res.response) {
					//remove error
                	_removeErrorStyle(element.parents('.popup').find('.warehouse_cd'));
					element.parents('.popup').find('.warehouse_cd').val(res.data.warehouse_cd);
					element.parents('.popup').find('.warehouse_nm').text(res.data.warehouse_nm);
				} else {
					if (!isSearch) {
						element.parents('.popup').find('.warehouse_cd').val('');
					}
					element.parents('.popup').find('.warehouse_nm').text('');
				}
				if (typeof callback == 'function') {
					callback();
				}
				//element.parents('.popup').find('.warehouse_cd').focus();
			}
		});

	} catch(e) {
        console.log('_referWarehouse' + e.message)
    }
}
/**
 * init textarea disable resize
 *
 * @author : ANS817 - 2017/12/25 - create
 * @params :
 * @return : null
 * @access : public
 * @see :
 */
function initTextareaDisableResize() {
	try {
		$('textarea.disable-resize').attr('rows', 2);
	} catch (e) {
		console.log('initTextareaDisableResize: ' + e.message);
	}
}
/**
 * setting Button Delete
 *
 * @author      :   ANS806 - 2018/05/07 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function _settingButtonByMode(mode, selector) {
    try {
        if(mode == 'I'){
            $('.cl-'+selector).hide();
        }
        if(mode == 'U'){
            $('.cl-'+selector).show();
        }
    } catch (e) {
        console.log('_settingButtonByMode' + e.message);
    }
}

/**
 * import excel
 *
 * @author      :   ANS817 - 2018/06/04 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function _ImportExcel(input, url, callback, callbackErr) {
    try {
    	if (_isExcel(input)) {
    		var formData = new FormData();
	        formData.append('file', input[0].files[0]);
	        $.ajax({
	            url         :   url,
	            type        :   "POST",
	            data        :   formData,
	            processData :   false,
	            contentType :   false,
	            success     :   function(res) {
	            	if(res.error_cd != ''){
	            		jMessage(res.error_cd);

	            		if(callbackErr){
	            			callbackErr(res.data);
	            		}
	            	} else if (res.response) {
	            		// jSuccess('保存しました。');
	            		jMessage('I010', function(r){
	            			if (r) {
	            				if(callback){
			            			callback(res.data);
			            		}
	            			}
	            		});
	            	} else {
	            		//catch DB error and display
		            	var msg_e999 = _text['E999'].replace('{0}', res.error);
		            	jMessage_str('E999', msg_e999, '', msg_e999);
	            	}
	            }
	        });
    	} else {
			// The file is not formatted correctly
    		jMessage('E015');
    	}
    } catch(e) {
        console.log('ImportExcel' + e.message);
    }
}
/**
 * check file import have extension is excel
 *
 * @author      :   ANS817 - 2018/06/04 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
 function _isExcel(input) {
 	try {
		var flag    = false;

		// xlsx is extension of Excel 2007 or higher
		// xls is extension of Excel 1997 ~ Excel 2003
		var pattern = "^.+\.(xlsx|xls)$";

        if (input.val().match(pattern)) {
            flag = true;
        } else {
            flag = false;
        }
        return flag;
 	} catch(e) {
        console.log('_isExcel' + e.message);
    }
 }
