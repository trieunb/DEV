/**
 * init jquery
 *
 * @author      :   vuongvt - 2016/10/20 - create
 * @author      :   DuyTP 2017/02/09 - update: Add event back to login when session expires
 * @author      :   DuyTP 2017/03/01 - update: Only accept 00:00 ~ 23:59
 * @author      :   DuyTP 2017/04/13 - update: No need validate tel form - ( )
 * @author      :   DuyTP 2017/04/20 - update: search when press enter
 * @author      :   DuyTP 2017/05/10 - update: add placeholder「:」 for class .time

 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
$(document).ready(function() {
	/**
	 * @author: VuongVT - 2016/10/26
	 * @reason: init token value for all post method, show block screen and close block screen when use ajax
	 */
	 $('[data-toggle="tooltip"]').tooltip(); 
	$(document).ajaxStart(function() {

		// show block screen
		//HungNV comment
		//callWaiting();
	});
	$(document).ajaxComplete(function() {
		// close block screen
		closeWaiting();
	});
	$(document).ajaxError(function() {
		// close block screen
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
			// console.log('suces');
			//removeError();
			closeWaiting();
		},
		error : function(response){
			closeWaiting();
			return false;
		},
		// DuyTP 2017/02/09 Add event back to login when session expires
		complete: function(res){
			if (res.status != null && res.status == 404) {
				location.href = '/';
			}else if(res.status==409){
				location.href = '/example';
			}
			// if (res.status != null && res.status == 401) {
			//     location.href = '/';
			// }
		}
	});
	
	/**
	 * @author: VuongVT - 2016/10/26
	 * @reason: init control's event
	 */
	initControls();
	/**
	 * @author: DuyTP - 2017/04/21
	 * @reason: init one time control's event
	 */
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
		$(document).on('focus','input.numeric,input.money,input.date,input.time,input.time24,input.time48,input.phone,input.none-full-size',function(e) {
            $(this).attr('type', 'tel');
        });
		$('.navigation-accordion').find('.active').removeClass('active');
	    var url      = 	window.location.pathname;
	    $('a[href$="'+url+'"]').parent().addClass('active');
	    $('a[href$="'+url+'"]').parent().parent().parent().addClass('active');
	    $('a[href$="'+url+'"]').parent().parent().css('display','block')

	    $('.navigation').children().find('a').attr('tabindex', 0);
	    //set maxlength input number date 4
	    $(".number-date").attr('maxlength','4');

		$('.navigation li a').on('click', function(event) {
	    	event.preventDefault(); 
	    	var url = $(this).attr('href');
	    	_postParamToLink('SELF', 'SELF', url, '')
	    });

	    $(document).on('keydown','.btn,.ui-datepicker-trigger',function (event) {
	        if(event.keyCode == 13){
	            $(this).trigger('click');
	            event.preventDefault();
	        }
	    });

	    $(document).on('change','input.datepicker', function () {
	        var date 	= $(this).val();
	        var numDate = _numDate(date);
	        // if ($(this).prev().hasClass('number-date')) {
	        // 	if (numDate != 0 && !isNaN(numDate)) {
		       //  	$(this).prev().val(numDate);
		       //  } else {
		       //  	$(this).prev().val('');
		       //  }
	        // }
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

	   	$(document).on('focusout', 'input[type=text], select, textarea', function() {
	   		var val = $(this).val().trim();
	   		if (val !== '') {
	   			_removeErrorStyle($(this));
	   		}
	   	});

	   	//combobox trade terms
 		$(document).on('change', '.trade_terms_div', function(){
 			var lib_val_ctl1 = $('option:selected', this).attr('data-ctl1');
 			var lib_val_ctl2 = $('option:selected', this).attr('data-ctl2');
 			_addTradeTermsTableTotal(lib_val_ctl1, lib_val_ctl2);
 			_totalTaxTable();
 		});

 		// change sub and plus date current
 		$(document).on('change', '.country-change', function() {
 			var name = $(this).val();
 			initCombobox();
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
		$(document).on('change', '.value-freight', function() {
			_totalTaxTable();
		});

		//change input insurance tax
		$(document).on('change', '.value-insurance', function() {
			_totalTaxTable();
		});

    } catch (e) {
		alert('initEventsCommon: ' + e.message);
	}
}
 // Setup
function miniSidebar() {
    if ($('body').hasClass('sidebar-xs')) {
        $('.sidebar-main.sidebar-fixed .sidebar-content').on('mouseenter', function () {
            if ($('body').hasClass('sidebar-xs')) {

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
 * init validate
 *
 * @author : kyvd - 2016/08/09 - create
 * @author :
 * @params : null
 * @return : null
 * @access : public
 * @see :
 */
function initValidate(obj, flag) {
	try {
		_clearErrors();
		if (flag == undefined){
			flag = true;
		}
		$.each(obj, function(key, element) {
			//get type element
			var type = element['type'];
			//set key is id or class element
			if (element['attr']['isClass'] === true) {
				//validate by id
				key = '.' + key;
			} else {
				//validate by class
				key = '#' + key;
			}
			$(document).on('change',key,function(){
				try {
					// check required
					var required = element['attr']['required'];
					if (required === true) {
						// var msg_required = "This is a required field! ";
						if( $(this).val()==='' || ((type==='select'||type==='number') && convertNumber($(this).val())===0) ){
							$(this).errorStyle(_text[5]);
						}
					}
					// check maxlength
					var maxlength = element['attr']['maxlength'];
					if (maxlength != undefined) {
						var msg_maxlength = "Maxlength! ";
						if ($(this).val().length > maxlength){
							$(this).errorStyle(msg_maxlength);
						}
					}
					// check email
					var isEmail = element['attr']['isEmail'];
					if (isEmail != undefined) {
						var msg_email = "メールアドレスとして正しくありません";
						if (isValidEmailAddress($(this).val().trim()) != true) {
							$(this).errorStyle(msg_email);
						}
					}

					//check error tab && item focus
					//flag=false: if don't check tab error
					if (flag) {
						//checkCurrentError(this);
					}
				} catch (e) {
					alert(event + ' ' + key + ': ' + e.message);
				}
			});
		});
	} catch (e) {
		alert('initValidate' + e.message);
	}
}

/**
 * validate data common
 *
 * @author        :    KienNT - 2017/02/16 - create
 * @modify		  :    Trieunb - 2017/08/08
 * @params        :    null
 * @return        :    null
 */
function _validate(element){
	if(!element){
		element = $('body');
	}
	var error = 0;
	try{
		_clearErrors();
		element.find('.required:enabled:not([readonly])').each(function() {
			if ($(this).is(':visible')) {
				if(($(this).is("input") || $(this).is("textarea")) &&  $.trim($(this).val()) == '' ) {
					$(this).errorStyle('必須入力です。');
					error ++;
				}else if( $(this).is("select") &&  ($(this).val() == '0' || $(this).val() == undefined) ) {
					$(this).errorStyle('必須入力です。');
					error ++;
				}else if($(this).is("input[type=checkbox]") && !$(this).is(":checked")){
                    $(this).errorStyle('必須入力です。');
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
		//
		// element.find('input.tel:enabled:not([readonly])').each(function(){

		//     if(!_validatePhoneFaxNumber($(this).val())){
		//         $(this).errorStyle(_text[21]);
		//         error++;
		//     }
		// });
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
 * Check Email
 * @param string
 * @returns {Boolean}
 */
function _validateEmail(string){
	if(string == '') {
		return true;
	}

	string = _formatString(string);
	//var reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	//var reg =   /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	var reg = /^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/;
	if (string.match(reg)){
		return true;
	}else{
		return false;
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
		var reg = /^[0-9]+[-][0-9]+[-][0-9]+$/;
		if(string.match(reg)||string == ''){
			return true;
		}
		return false;
	} catch (e){
		alert('_validatePhoneFaxNumber: '+e);
	}
}

/**
 * check all tab eror
 *
 * @author : kyvd - 2016/08/09 - create
 * @author :
 * @return : null
 * @access : public
 * @see :
 */
function checkAllTabError() {
	try {
		var isCheckHasError = false;
		//remove class tab-error
		$('.tab-error').removeClass('tab-error');
		//background tab error
		$('.tab-content .tab-pane').each(function() {
			if(!isCheckHasError){
				var elem = $(this).find('.item-error:first');
				if(elem.parents('.tab-pane').length > 0){
					var index = elem.parents('.tab-pane').index();
					index = (index - 1)/2;
					//mobile
					$('.tab-content').find('a.r-tabs-anchor').eq(index).addClass('tab-error');
					$('.tab-content').find('a.r-tabs-anchor').eq(index).trigger('click');
					//destop
					$('.nav-tabs').find('li').eq(index).find('a').addClass('tab-error');
					$('.nav-tabs').find('li').eq(index).find('a').trigger('click');
					isCheckHasError = true;
				}
			}
		});
		//tab error click
		var elem = $('.item-error:first');
		if(elem.parents('.tab-pane').length > 0){
			var index = elem.parents('.tab-pane').index();
		}
		//focus item error
		elem.focus();
		//_balloontipMouseover('', jQuery(elem));
	} catch (e) {
		alert('checkAllTabError:' + e.message);
	}

}

/**
 * convert number
 *
 * @author : kyvd - 2016/08/22 - create
 * @author :
 * @param  : string
 * @return : number
 * @access : public
 * @see :
 */
function convertNumber(string) {
	try {
		var num = 0;
		if(!isNaN(string) && string!==''){
			num = parseInt(string);
		}
		return num;
	} catch (e) {
		return 0;
	}
}

/**
 * @author: VuongVT
 * @param {Object} selector
 * @param {Object} count
 */
function hoverGroup (selector, count, colorhover) {
	if(typeof count == 'undefined'){
		count = 1;
	}
	if(typeof colorhover == 'undefined'){
		colorhover = '#ffff88';
	}

	var selectorTr = selector + ' tbody tr';
	var eq, eq0, el, color;

	try {
		$(document).on({
			mouseover: function(){
				if($(this).find('td.no-hover').length > 0){
					return;
				}
				eq    = $(this).index();
				color = $(this).css("background-color");
				eq0   = eq - eq%count;
				var x = count;
				while(x--){
					var index = eq0 + x;
					el  = selector + ' tbody tr:eq(' + index +')';
					$(el).css("background-color", colorhover);
					eltd  = selector + ' tbody tr:eq(' + index +') td';
					$(eltd).css("background-color", colorhover);
				}
			},
			mouseleave: function(){
				if($(this).find('td.no-hover').length > 0){
					return;
				}
				var x = count;
				while(x--){
					var index = eq0 + x;
					el  = selector + ' tbody tr:eq(' + index +')';
					eltd  = selector + ' tbody tr:eq(' + index +') td';
					var style = $(el).attr('style').split(";");
					for(var i = style.length - 1; i--;){
						if (style[i].indexOf("background-color") != -1) style.splice(i, 1);
					}
					style = style.join(";");
					$(el).attr('style', style);
					$(eltd).attr('style', style);
				}
			}
		}, selectorTr);
	} catch (e){
		console.log(e);
	}
}

/**
 * getData
 *
 * @author : vuongvt - 2016/10/27 - create
 * @author :
 * @return : null
 * @access : public
 * @see :
 */
function getData(obj) {
	try {
		var data = {};
		$.each(obj, function(key, element) {
			switch (element.type) {
			case 'text':
				if($('#' + key).hasClass('padding-zero')){
					data[key] = $.trim($('#' + key).val()) == '' ? 0 : $.trim($('#' + key).val())*1;
				}else{
					data[key] = $.trim($('#' + key).val());
				}
				if($('#' + key).hasClass('money') || $('#' + key).hasClass('numeric')){
					data[key] = data[key].replace(/,/g, '');
				}
				break;
			case 'textarea':
				data[key] = $.trim($('#' + key).val());
				break;
			case 'time':
				data[key] = $.trim($('#' + key).val()).replace(':', '');
				break;
			case 'refer':
				data[key] = $.trim($('#' + key).val());
				break;
			case 'select':
				data[key] = $('#' + key).val();
				if (!data[key]) {
					data[key] = 0;
				}
				break;
			case 'multiselect':
				if($.isArray($('#'+key).val())){
					var values=$("#"+key).val();
					data[key]=[];
					for(var i=0;i<values.length;i++){
						var obj={};
						obj[key]=values[i];
						data[key].push(obj);
					}
				}else{
					data[key]=$('#' + key).val();
					if (!data[key]) {
						data[key] = 0;
					}
				}
				break;
			case 'radiobox':
				var name = element['attr']['name'];
				data[key] = $("input[name='" + name + "']:checked").val();
				break;
			case 'checkbox':
				data[key] = false;
				if ($('#' + key).is(":checked")) {
					data[key] = true;
				}
				break;
			case 'money':
				data[key] = $.trim($('#' + key).val()).replace(/,/g, '');
				break;
			// add by phonglv
			case 'numeric':
				data[key] = 1*($.trim($('#' + key).val()).replace(/,/gi,'').replace(/%/gi,''));
				break;
			case 'title':
				data[key] = $.trim($('#' + key).text());
				break;
			default:
				break;
			};
		});
		return data;
	} catch (e) {
		alert('getData: ' + e.message);
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
 * add responsive for pagging after search
 *
 * @author : vuongvt - 2016/10/27 - create
 * @author :
 * @params : null
 * @return : null
 * @access : public
 * @see :
 */
function addResponsivePagging() {
	// Adding toggle for Reponsive
	$('.panel-heading, .page-header-content, .panel-body, .panel-footer').has('> .heading-elements').append('<a class="heading-elements-toggle"><i class="icon-more"></i></a>');
	// Toggle visible state of heading elements
	$('.heading-elements-toggle').on('click', function() {
		$(this).parent().children('.heading-elements').toggleClass('visible');
	});
}

/**
 * get html contiditon
 *
 * @author      :   vuongvt - 2016/10/28 - create
 * @author      :
 * @params      :   null
 * @return      :   null
 * @access      :   public
 * @see         :
 */
function getHtmlCondition(id){
	$('select option').each(function(){ $(this).attr('selected',this.selected); });
	$(id).find('input').each(function() {
		if ($(this).is('[type="text"]') || $(this).is('[type="tel"]')) {
			$(this).attr('value', $(this).val());
			// $('.date').removeClass('hasDatepicker');    //add by longvv at 201609012
			// $('.date').next('img').remove();            //add by longvv at 201609012
		} else if($(this).is('[type="checkbox"]')) {
			if ($(this).is(':checked')) {
			  $(this).attr('checked', true);
			} else {
				$(this).removeAttr('checked');
			}
		} else if($(this).is('[type="radio"]')) {
			if ($(this).is(':checked')) {
				$(this).attr('checked', true);
			} else {
				$(this).removeAttr('checked');
			}
		}
	});
	return $(id).html();
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
				'to_ScreenId' : toScreenId,
				'parram' : parram
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
	console.log(height);
	var properties = {
		href : href,
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
	// string = _formatString(string);
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
function isValidEmailAddress(emailAddress) {
	var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
	return pattern.test(emailAddress);
};

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
	//var reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	//var reg =   /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
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

	//btn search country
	$(document).on('click', '.btn-search-country', function(){
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
			initCombobox();
		});
		
	});

	//refer search
	// $(document).off( "change", ".refer-search");
	// $(document).on('change', '.refer-search', function(){
	// 	var parent = $(this).parents('.popup');
	// 	var temp_elem = $(this);
	// 	var id = parent.data('id');
	// 	var nm = parent.data('nm');
	// 	var istable = parent.data('istable');
	// 	$.post('/common/refername', {key: id, value : $(this).val()}, function (res){
	// 		if (istable) {
	// 			if(res[id]==''){
	// 				temp_elem.val('');
	// 			}
	// 			parent.parents('tr').find('.' + nm).text(res[nm]);
	// 			parent.parents('tr').find('.' + nm).prop('title',res[nm]);

	// 			if(parent.data('search')=='searchstudent'){
	// 				var root=parent.parents('tr');
	// 				root.find('.school_building_nm').text(res['school_building_nm']);
	// 				root.find('.student_nm').text((res['student_nm']));
	// 				root.find('.grade_type_nm').text((res['grade_type_nm']));
	// 				root.find('.student_status_type_nm').text(res['student_status_type_nm']);
	// 			}
	// 		} else {
	// 			if(res[id]==''){
	// 				temp_elem.val('');
	// 			}
	// 			parent.find('.' + nm).text(res[nm]);
	// 			parent.find('.' + nm).prop('title',res[nm]);
	// 		}

	// 	});

	// });

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
	//DuyTP 2017/05/10 - update: add placeholder「:」 for class .time
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

	//HungNV comment 2017/01/24
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
		// if (e.keyCode == 229) {
		// 	var val = $(this).val();
		// 	var newValue = convertKana($('#fullsize_number').val(), 'h');
	 //        $(this).val(newValue);
	 //        return false;
		// }
		// if (	!(      (e.keyCode > 47 && e.keyCode < 58)
		// 			||  (e.keyCode > 95 && e.keyCode < 106)
		// 			//////////// PERIOD SIGN ////////////////////////////////////////////////////////////////
		// 			||  ((e.keyCode == 190 || e.keyCode == 110) && $(this).val().indexOf('.') === -1)
		// 			||  e.keyCode == 173
		// 			||  e.keyCode == 109
		// 			||  e.keyCode == 189
		// 			||  e.keyCode == 116
		// 			||  e.keyCode == 46
		// 			||  e.keyCode == 37
		// 			||  e.keyCode == 39
		// 			|| e.keyCode == 36
		// 			|| e.keyCode == 35
		// 			||  e.keyCode == 8
		// 			||  e.keyCode == 9
		// 			||  e.keyCode == 13)
		// ){
		// 		e.preventDefault();
		// 		return false;
		// }

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

	/**
	 * @author: KienNT - 2017/02/23
	 * @reason: init for numeric control (decimal) example numeric(5,2)
	 */
	$(document).on('keydown', 'input.money', function(event){
		//var ctrlDown = event.ctrlKey||event.metaKey; // Mac support
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

	$(document).on('keypress','.money',function(event) {
        // debugger;
		var $this = $(this);
		var decimal_len = (typeof $this.attr('decimal_len') === 'undefined')?0:(1*$this.attr('decimal_len'));
		var real_len = (typeof $this.attr('real_len') === 'undefined')?0:(1*$this.attr('real_len'));
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
			event.preventDefault();
		}

		// negative
		if((negative!=1 || text.indexOf('-') > -1)&&(event.which == 45)){
			event.preventDefault();
		}else if(text.indexOf('-')==-1 && event.which==45 && negative==1)
        {
            $this.val('-'+text);
        }


	});

	$(document).on('blur', 'input.money:not([readonly])', function(event){
		var item = $(this);
		var value = item.val().replace(/,/gi,'');
//		value = value * 1;
		if(value != ''){
			if ( $.isNumeric(value) ) {
				value = addCommas(value*1);
				item.val(value);
			}  else {
				item.val('');
			}
		}
	});

	$(document).on('focus','input.money',function(){
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
		var string  =   '';
		// if ($(this).val().length==1){
		//     string  = padZeroRight($(this).val(), 3);
		//     string  = padZeroLeft(string, 4);
		// }else{
		//     string = padZeroRight($(this).val(), 4);
		// }
		string = padZeroLeft($(this).val(), 4);

		// var reg1 = /^(([0-1][0-9])|(2[0-3])):[0-5][0-9]|[2][4]:[0][0]$/;
		// var reg2 = /^(([0-1][0-9])|(2[0-3]))[0-5][0-9]|[2][4][0][0]$/;

		var reg1 = /^(([0-1][0-9])|(2[0-3])):[0-5][0-9]$/;// DuyTP 2017/03/01 only accept 00:00 ~ 23:59
		var reg2 = /^(([0-1][0-9])|(2[0-3]))[0-5][0-9]$/;// DuyTP 2017/03/01 only accept 00:00 ~ 23:59

		// var reg3 = /^[2][4][0][0]$/;
		if (string.match(reg1)) {
			$(this).val(string);
		} else if (string.match(reg2)) {
			// if($(this).val().length <=2){
			//     $(this).val( string.substring(2) + ':' + string.substring(0, 2));
			// }else if($(this).val().length ==3){
			//     $(this).val( string.substring(2) + ':' + string.substring(0, 1));
			// }else{
			//     $(this).val( string.substring(0, 2) + ':' + string.substring(2));
			// }
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
		// if ($(this).val().length==1){
		//     string  = padZeroRight($(this).val(), 3);
		//     string  = padZeroLeft(string, 4);
		// }else{
		//     string = padZeroRight($(this).val(), 4);
		// }
		string = padZeroLeft($(this).val(), 6);

		var reg1 = /^(?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/;
		var reg2 = /^(?:2[0-3]|[01][0-9])[0-5][0-9][0-5][0-9]$/;

		if (string.match(reg1)) {
			$(this).val(string);
		} else if (string.match(reg2)) {
			// if($(this).val().length <=2){
			//     $(this).val( string.substring(2) + ':' + string.substring(0, 2));
			// }else if($(this).val().length ==3){
			//     $(this).val( string.substring(2) + ':' + string.substring(0, 1));
			// }else{
			//     $(this).val( string.substring(0, 2) + ':' + string.substring(2));
			// }
			$(this).val( string.substring(0, 2) + ':' + string.substring(2,4) + ':' + string.substring(4));

		} else {
			$(this).val('');
		}
		// if (!_validateTime($(this).val())) {
		//     $(this).val('');
		// }
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
	$(document).on('blur', 'input.tel', function() {
		try {
			var string  =   $(this).val();
			var reg2    =   /^[0-9-]+$/;
			if(!string.match(reg2)){
				$(this).val('');
			}
		} catch (e) {
			alert(e.message);
		}
	});

	// blur postal_code
	$(document).on('blur', 'input.postal_code', function() {
		var string = $(this).val();
		if (!_validateZipCd($(this).val())) {
			$(this).val('');
		}
	});

	//keyup ten-key
	$(document).on('keyup',
			'input.tel, input.number-date, input.number, input.numeric, input.money, input.rate, input.percentage, input.postal_code, input.time, input.datepicker, input.month', function(e) { //2016/03/30 sangtk add postal_code_en
		// if (e.keyCode != 48 && e.keyCode != 49 && e.keyCode != 50 && e.keyCode != 51 && e.keyCode != 52 && e.keyCode != 53 && e.keyCode != 54 && e.keyCode != 55 && e.keyCode != 56 && e.keyCode != 57) {			
		// 	var noSbcRegex = /[^\x00-\x7E]+/g;
		// 	var target = $(this);
		// 	try {
		// 	 	if(target.val().match(noSbcRegex))  {
		// 			 target.val( target.val().replace(noSbcRegex, '') );
		// 		}
		// 	} catch (e) {
		// 		alert(e.message);
		// 	}
		// }
	});
	// 2017/05/29: VuongVT: Edit for input full-size
	$(document).on('change',
			'input.tel, input.number-date, input.number, input.numeric, input.rate, input.percentage, input.postal_code, input.time, input.datepicker, input.month', function(e) { //2016/03/30 sangtk add postal_code_en
 	  	var newValue = convertKana($(this).val(), 'h', false);
        // console.log(newValue);
        $(this).val(newValue);
	});

	$(document).on('change', 'input.money', function(e) { //2016/03/30 sangtk add postal_code_en
     	var newValue = convertKana($(this).val(), 'h', true);
     	console.log(newValue);
        $(this).val(newValue);
	});
	//2017/05/29: End edit

	//esc to clear errors
	$("body").keydown(function (e) {
		if (e.keyCode === 27) {
			//$('.error-tip-mesage').hide();
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
	// console.log(x2);
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
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
function addNewRowToTable(config,callback)
{
	// if define var not found return error log
	"use strict";

	// Example of config
	// var config = {
	//     button: '[data-target="#table-area"]', ##### class or id ....
	//     html:html,
	// };

	// Begin event update
	$(config.button).on('click', function(e)
	{
		e.preventDefault();
		var table = $(this).data('target'); // call id table
		var tbody = table + ' tbody';
		var html = config.html;
		var $this = $(this);
		if(typeof callback === "function") {
			if(callback($this) && typeof callback($this)==='string')
			{
				html = callback($this);
			}
		}
		if(html)
		{
			if (isEmptyHtml($(tbody))){
				$(tbody).html(html);
				return;
			}
			$(tbody + " tr:last").after(html);
			initControls();
		}
		//console.log(1);
		// disable event default

	});


}


/**
 * Check empty content in html tag
 *
 * @author      :   tannq@ans-asia - 2016/11/28 - created
 * @param       :   el: [$('#id')] ...
 * @access      :   public
 * @see         :   init
 */
function isEmptyHtml( el ){
	return !$.trim(el.html())
}

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
function _formatDatepicker(){

	$(".datepicker").each(function(){
		try{
			if($(this).hasClass('hasDatepicker')){
				if($('#ui-datepicker-div').length>0)
					$('#ui-datepicker-div').remove();
				$(this).next('img').remove();
				$(this).removeClass('hasDatepicker');
				$(this).datepicker("destroy");
			}
		}catch(e){
			console.log('dapicker destroy '+e.message);
		}
	});

	// // destroy old date picker
	// $('#ui-datepicker-div').remove();
	// $('.hasDatepicker').next('img').remove();
	// $('.hasDatepicker').removeClass('hasDatepicker');
	// // end destroy

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
 * Display Error Item
 * @author  Canh - 2015/05/28 - update
 * @param Array error
 * @returns {Boolean}
 */
function _showError(error) {
	var flag = true;
	var messageError = ['71', '72', '73', '292', '437', '438', '456', '457'];
	var msgDuplicatedRow = '';
	var errorFirst = '';
	// console.log(error);
	for(var i in error){

		if(error[i].Data==1){
			jError(_text[error[i].Code]);
		} else if(error[i].Data.indexOf('#') != -1){
			if($.inArray(error[i].Code, messageError) != -1){// case message inclusde data dynamic
				var replace_obj = {};
				var msg = _text[error[i].Code];
				replace_obj = JSON.parse(htmlEntities(error[i].Message));
				for(var key in replace_obj){
					msg = msg.replace(key,replace_obj[key]);
				}
				$(error[i].Data).errorStyle(msg);
			}else{
				$(error[i].Data).errorStyle(_text[error[i].Code]);
				flag = false;
			}
		} else if(error[i].Data.indexOf('.') != -1){
			if(error[i].Code == -1){
				$(error[i].Data).eq(error[i].Id - 1).errorStyle(error[i].Message);
			}else{
				$(error[i].Data).eq(error[i].Id - 1).errorStyle(_text[error[i].Code]);
			}
			/*$(error[i].Data).eq(error[i].Id - 1).errorStyle(_text[error[i].Code]);*/
			//end update
			flag = false;
		}
	}
	return flag;
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
 * htmlEntities
 *
 * @param string
 * @returns string
 */
function htmlEntities(str) {
	if(str == undefined ) {
		str = '';
	}
	return str.replace( /&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"');
}

/**
 * KienNT init common auto complete
 * @param selector
 * @param type
 * @param callback
 */
function initAutoCompleteSingle(selector,type,callback){
	$(selector).each(function(){
		var main_selecttor = $(this);
		var parent = $(this).parents('.single-auto-complete');
		var id = type+'_id';
		var url = '';
		if(type=="employee"){
			url = '/common/getallemployee';
		}else if(type=="group"){
			url = '/common/getallgroup';
		}else if(type=="school_building"){
			url = '/common/getallschoolbuilding';
		}else if(type=="school"){
			url = '/common/getallschool';
		}else if(type=="student"){
			url = '/common/getallstudent';
		}else if(type=="employee_group"){
			url = '/common/getallemployeegroup';
		}else if(type=="lecturer"){
			url = '/common/getalllecturer';
		}else if(type=="service"){
			url = '/common/getallservice';
			main_selecttor.blur(function(){
			   if(!checkInt($(this).val())){
				   $(this).val('')
			   }
			});
		}else if(type=="service2"){
			url = '/common/getallservice2';
			main_selecttor.blur(function(){
				if(!checkInt($(this).val())){
					$(this).val('')
				}
			});
		}else if(type=="service3"){
			url = '/common/getallservice3';
			main_selecttor.blur(function(){
				if(!checkInt($(this).val())){
					$(this).val('')
				}
			});
		}else if(type=="staff"){
			url = '/common/getallstaff';
		}

		if(!main_selecttor.hasClass('refer-search')){
			main_selecttor.change(function(){
				$.post('/common/refername', {key: id, value : $(this).val()}, function (res){
					for(var key in res){
						if(key==id && res[key]==''){
							$(main_selecttor).val('');
							parent.find('.display_info').val('');
							parent.find('.display_info').text('');
						}else{
							if(typeof parent.find('.'+key)!='undefined'){
								parent.find('.'+key).text(htmlEntities(res[key]));
								parent.find('.'+key).val(htmlEntities(res[key]));
							}
						}
					}
					if(typeof callback!='undefined'){
						callback();
					}
				});
			});
		}

		$(main_selecttor).autocomplete({
			source: url,
			minLength: 0,
			autoFocus: false,
			delay: 100,
			select: function(event, ui) {
				event.preventDefault();
				$(main_selecttor).val(ui.item[id]);
				for(var res_key in ui.item){
					if(typeof parent.find('.'+res_key)){
						parent.find('.'+res_key).val(ui.item[res_key]);
						parent.find('.'+res_key).text(ui.item[res_key]);
					}
				}
				//only trigger when control on table
				if(typeof $(main_selecttor).parents('.popup').data('istable')!='undefined' && $(main_selecttor).parents('.popup').data('istable')==1)
				{
					$(main_selecttor).trigger('change');
				}

				$(main_selecttor).focus();
				if(typeof callback!='undefined'){
					callback();
				}
			}
		}).on('compositionstart', function(e) {
			$(main_selecttor).autocomplete('disable');
		}).on('compositionend', function(e){
			$(main_selecttor).autocomplete('enable').autocomplete('search');
		});

	});
}

function checkInt(str){
	return !isNaN(str);
}
/**
 * KienNT
 * init auto complete set common
 */
function initAutoCompleteCoporationSet(){
	$('.coporation-set').each(function(){
		var coporation = $(this).find('.coporation_id');
		var company = $(this).find('.company_id');
		var division = $(this).find('.division_id');
		
		
	});
}

/**
 * KienNT
 * init auto complete set common
 */
function initAutoCompleteBlockSet(){
	$('.block-set').each(function(){

	});
}

/**
 * @param _id : id of selector
 * @param _url : to post
 * @param _callback : function after get post result, existed : function call when data existed.....
 */
function initSwitchData(_id,_url,obj,_callback){
	$(document).on('change','#'+_id,function(){
		var val = $(this).val();
		var _data = {value : val};
		$.ajax({
			type        :   'post',
			url         :   _url,
			dataType    :   'json',
			loading     :   true,
			data        :   _data,
			success: function(res) {
				//if exists data -> edit
				if(res['data'][0][0][_id] != ''){
					var data  = res['data'][0][0];
					fillData(data,obj);
					$("#mode").val('U');
					$('#btn-delete').show();
					if(typeof _callback['existed']!='undefined')
						_callback['existed'](res);
				}else{ //else add new
					$("#mode").val('A');
					$('#btn-delete').hide();
					clearData(obj,_callback['except_id']);
					if(typeof _callback['not_existed']!='undefined')
						_callback['not_existed'](res);
				}
				if(typeof _callback['all']!='undefined')
					_callback['all'](res);

				$('#'+_id).focus();
			},
			// Ajax error
			error : function(res) {
			}

		});
	});
}

/**
 * function fillData
 *
 * @author    : DuyTP - 2017/02/02 - create
 * @params    : data - object
 * @return    : null
 */
function fillData(data,_obj){
	_clearErrors();
	$.each(_obj, function(key, element) {
		switch (element.type) {
			case 'text':
				$('#' + key).val((typeof (data[key])=='undefined')?'':data[key]);
				break;
			case 'textarea':
				$('#' + key).val((typeof (data[key])=='undefined')?'':data[key]);
				break;
			case 'time':
				$('#' + key).val((typeof (data[key])=='undefined')?'':data[key]);
				break;
			case 'refer':
				$('#' + key).val((typeof (data[key])=='undefined')?'':data[key]);
				break;
			case 'select':
				// $('#' + key).val((typeof (data[key])=='undefined')?'0':data[key]);
				$('#' + key).find('option[value="'+data[key]+'"]').prop('selected',true);
				break;
			case 'multiselect':

				break;
			case 'radiobox':
				var name = element['attr']['name'];
				$("input[name='" + name + "'][value='"+data[key]+"']").prop('checked',true);
				break;
			case 'checkbox':
				$('#' + key).prop('checked',data[key]=='1');
				break;
			case 'display':
				$('#' + key).text(data[key]);
				$('#' + key).val(data[key]);
				break;
			default:
				break;
		};
	});
}

/**
 * function clearData
 *
 * @author    : HungNV - 2017/01/23 - create
 * @params    : data - object
 * @return    : null
 */
function clearData(_obj,except_key)
{
	_clearErrors();
	if(Array.isArray(except_key)){
		$.each(_obj, function(key, element){
			if(except_key.indexOf(key)<0){
				switch (element.type) {
					case 'text':
						$('#' + key).val('');
						break;
					case 'textarea':
						$('#' + key).val('');
						break;
					case 'time':
						$('#' + key).val('');
						break;
					case 'refer':
						$('#' + key).val('');
						break;
					case 'select':
						$('#' + key).val('0');
						break;
					case 'multiselect':

						break;
					case 'radiobox':
						break;
					case 'checkbox':
						$('#' + key).prop('checked',false);
						break;
					case 'display':{
						$('#' + key).text('');
						$('#' + key).val('');
					}break;
					default:
						break;
				};
			}
		});
	}else{
		$.each(_obj, function(key, element){
			if(typeof except_key=='undefined'){
				except_key = 'khong xac dinh';
			}
			if(key!=except_key){
				switch (element.type) {
					case 'text':
						$('#' + key).val('');
						break;
					case 'textarea':
						$('#' + key).val('');
						break;
					case 'time':
						$('#' + key).val('');
						break;
					case 'refer':
						$('#' + key).val('');
						break;
					case 'select':
						$('#' + key).val('0');
						break;
					case 'multiselect':

						break;
					case 'radiobox':
						break;
					case 'checkbox':
						$('#' + key).prop('checked',false);
						break;
					case 'display':{
						$('#' + key).text('');
						$('#' + key).val('');
					}break;
					default:
						break;
				};
			}
		});
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
			$('#btn-search').trigger('click');
		}
	});
}

/**
 * function dynamically set tabindex
 * @author: DuyTP - 2016/12/16 - create
 * @author: DuyTP - 2017/02/09 - update
 * @modify: Trieunb - 2017/08/08 - update
 */
function _setTabIndex() {
	$(":input").each(function (i) { 
		$(this).attr('tabindex', i + 1);
		if ($(this).hasClass('datepicker') || $(this).hasClass('month')) {
			$(this).next().attr('tabindex', i + 1);
		}
		
	});
	$('.btn-add-row').attr('tabindex', '-1')
	$('.remove-row').attr('tabindex', '-1')
	$('input[disabled], input[readonly], textarea[disabled], textarea[readonly], select[disabled], button[disabled]').attr('tabindex', '-1'); 	
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

function activeLineTableRowspan(table) {
	$("."+table).each(function() {
	    var $this = $(this);
	    var numTD = $this.find("tr:has(td[rowspan]):first td").length;
	    $this.data('numTD', numTD).find("tr").filter(function() {
	        var $this = $(this);
	        return $this.children().length == $this.closest('table').data('numTD');
	    }).filter(':even').addClass('even');

	})

	$("."+table+" tr.even td[rowspan]").each(function() {
	    $(this).parent().nextAll().slice(0, this.rowSpan - 1).addClass('even');
	});

	$("."+table+" tr").each(function() {
		if (!$(this).hasClass('even')){
			$(this).addClass('odd');
		}
	});
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
function checkDateFromTo(obj) {
	var obj 		=	$('.'+obj);
	var dateFrom 	=	obj.find('.date-from').val();
	var dateTo 		=	obj.find('.date-to').val();
	try {
        var isCheck = true;
        var message = '日付Toは日付From以降の日付を指定してください。';
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
function _sortTable(n, tableObj) {
  	var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  	table = document.getElementById(tableObj);
  	switching = true;
  	dir = "asc"; 
  	while (switching) {
	    switching = false;
	    rows = table.getElementsByTagName("TR");
	    for (i = 1; i < (rows.length - 1); i++) {
	      	shouldSwitch = false;
	      	x = rows[i].getElementsByTagName("TD")[n];
	      	y = rows[i + 1].getElementsByTagName("TD")[n];
	      	if (dir == "asc") {
	        	if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
	          		shouldSwitch= true;
	          		break;
	        	}
	      	} else if (dir == "desc") {
	        	if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
	          		shouldSwitch= true;
	          		break;
	        	}
	      	}
	    }
	    if (shouldSwitch) {
	      	rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
	     	switching = true;
	      	switchcount ++; 
	    } else {
	      	if (switchcount == 0 && dir == "asc") {
	        	dir = "desc";
	        	switching = true;
	      	}
	    }
  	}
  	_updateTable(tableObj, true);
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
	    var date = formatDate(d);
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
function formatDate(date) {
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
		alert('formatDate: ' + e.message);
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
function _disabldedAllInput() {
	$(":input").each(function (i) { 
		$(this).attr('disabled', 'disabled');
	});
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
	if (mode == 'I') {
		for (var i = 0; i < numRow; i ++) {
			var row = $("#" + objLineNew + " tr").clone();
			$('.' + table + ' tbody').append(row);
		}
	}
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
		_updateTable(table, true);
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

 function _removeRowTable(table, obj, callback) {
 	try	{
 		var index = obj.closest('tr').index();
	 	jConfirm('削除してもよろしいですか？', 1, function(r) {
			if(r) {
				// remove line
				obj.closest('tr').remove();
				// check callback function
				if (typeof callback == 'function') {
					callback();
				}
				// update table when remove line
				_updateTable(table, true);

				jSuccess('削除しました。', 1, function(r) {
					var countRow = $('.' + table + ' tbody tr:last').index();
					// if remover row last then set set focus...
					if (countRow == (index - 1)) {
						index = index - 1;
					}
					$('.' + table + ' tbody tr:eq('+index+') :input:first').focus();
				});
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
 function _getComboboxData(countryCode, libCd) {
    try {
        $.ajax({
            type        :   'GET',
            url         :   '/common/combobox/get-combobox-data',
            dataType    :   'json',
            data 		: 	{
            					countryCode : countryCode,
            					libCd 		: libCd
            				},
            success: function(res) {
                if (res.response) {
                    html = '<option value="0"></option>';
                    var lib_val_nm 	= "lib_val_nm_e";
                    var hdn_nm 		= "lib_val_nm_j";
                	if (countryCode == 'JP') {
                		lib_val_nm 	= "lib_val_nm_j";
                		hdn_nm 		= "lib_val_nm_e";
                	}

                	var selected 		= '';
                	var data_selected 	= $('.'+libCd).attr('data-lib-cd');

	                $.each(res.data, function(item, value) {
	                	if (value.lib_val_cd == data_selected) {
	                		selected = 'selected';
	                	} else {
	                		selected = '';
	                	}
	                	var name = value[lib_val_nm];
		                html += "<option " + selected + " value=" + value.lib_val_cd + " data-ctl1='" + value[hdn_nm] + "' data-ctl2='" + value[hdn_nm] + "'>" + name + "</option>";
	                });
	                $('.'+libCd).empty();
                    $('.'+libCd).append(html);
                }
            }
        });
    } catch (e) {
        alert('_getComboboxData' + e.message);
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
 function _addTradeTermsTableTotal(lib_val_ctl1, lib_val_ctl2) {
    try {
        if (lib_val_ctl1 == '1') {
			$('.table-total tbody tr td .title-freight').removeClass('hidden');
			$('.table-total tbody tr td .value-freight').removeClass('hidden');
		} else {
			$('.table-total tbody tr td .title-freight').addClass('hidden');
			$('.table-total tbody tr td .value-freight').addClass('hidden');
		}

		if (lib_val_ctl2 == '1') {
			$('.table-total tbody tr td .title-insurance').removeClass('hidden');
			$('.table-total tbody tr td .value-insurance').removeClass('hidden');
		} else {
			$('.table-total tbody tr td .title-insurance').addClass('hidden');
			$('.table-total tbody tr td .value-insurance').addClass('hidden');
		}
    } catch (e) {
        alert('_addTradeTermsTableTotal' + e.message);
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
 function _addCountryTradeTableTotal(name) {
    try {
        if (name == 'JP') {
			$('.table-total tbody tr td .title-jp').removeClass('hidden');
			$('.table-total tbody tr td .value-jp').removeClass('hidden');
		} else {
			$('.table-total tbody tr td .title-jp').addClass('hidden');
			$('.table-total tbody tr td .value-jp').addClass('hidden');
		}
    } catch (e) {
        alert('_addCountryTradeTableTotal' + e.message);
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
		$('.total-footer').text(_convertMoneyToIntAndContra(total_footer));
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
 		}
 		if (num.indexOf(',') > -1) {
 			num = num.replace(/,/g,'');
 		} else {
 			num = num.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
 		}
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
function ImportCSV(input, url) {
    try {
        var formData = new FormData();
        formData.append('file', input[0].files[0]);
        $.ajax({
             url         :   url,
            type        :   "POST",
            data        :   formData,
            processData :   false,
            contentType :   false,
            success     :   function(res){
                console.log(res);
            }
        });      
    } catch(e) {
        alert('ImportCSV' + e.message);
    }
}
