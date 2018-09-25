/**
 * ****************************************************************************
 * Selling Unit Price By Client Detail
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	TrieuNB
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	INVOICE
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

 $(document).ready(function () {
	initEvents();
	// _settingButtonDetele(mode);
	if (from == 'SellingUnitPriceByClientSearch' && mode == 'U') {
		_getProductName(_productCd, $('.TXT_product_cd'));
		_getClientName(_clientCd, $('.TXT_client_cd'), function(){
			$('.TXT_product_cd').focus();
		});
		//標準単価
		if(_clientCd == ''){
			$('#CHK_standard_unit_price').prop('checked', true);
			$('.TXT_client_cd').val('');
			$('.suppliers_nm').text('');
			$('.TXT_client_cd').attr('disabled', 'disabled');
			$('.TXT_client_cd').next().find('.btn-search').attr('disabled', 'disabled');
		}
		if ($('.TXT_product_cd').val() != '') {
			referSalesPriceInfo();
		}
	}
});

/**
 * init Events
 * @author  :   TrieuNB - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//init back
		$(document).on('click', '#btn-back', function () {
			if (from == 'SellingUnitPriceByClientSearch') {
				sessionStorage.setItem('detail', true);
				location.href = '/master/selling-unit-price-by-client-search';
			}
		});
		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				if(validate()){
					var msg = (mode == 'I')?'C001':'C003';
					jMessage(msg,function(r){
						if(r){
							save();
						}
					});
			   	}
			} catch (e) {
				console.log('#btn-save ' + e.message);
			}
		});
		// button delete
		$(document).on('click', '#btn-delete', function() {
			try {
				if(validate()){
					jMessage('C002',function(r){
						if(r){
							postDelete();
						}
					});
				}
			} catch (e) {
				alert('#btn-delete ' + e.message);
			}
		});
		// Change 製品コード
		$(document).on('change', '.TXT_product_cd', function() {
			try {
				_getProductName($(this).val(), $(this), function(){
					referSalesPriceInfo();
				}, true);
			} catch (e) {
				console.log('change: .TXT_product_cd ' + e.message);
			}
		});
		// Change 取引先コード
		$(document).on('change', '.TXT_client_cd', function() {
			try {
				_getClientName($(this).val(), $(this), function(){
					referSalesPriceInfo();
				}, true);
			} catch (e) {
				console.log('change: .TXT_client_cd ' + e.message);
			}
		});
		// Change 開始日
		$(document).on('change', '#TXT_start_date', function() {
			try {
				referSalesPriceInfo();
			} catch (e) {
				console.log('change: #TXT_start_date ' + e.message);
			}
		});
 		//chk-standard-unit-price
 		$(document).on('click', '#CHK_standard_unit_price', function(){
 			try {
				if($(this).is(':checked')){
					$('.TXT_client_cd').val('');
					$('.suppliers_nm').text('');
					$('.TXT_client_cd').attr('disabled', 'disabled');
					$('.TXT_client_cd').next().find('.btn-search').attr('disabled', 'disabled');
				}else{
					$('.TXT_client_cd').removeAttr('disabled');
					$('.TXT_client_cd').next().find('.btn-search').removeAttr('disabled', 'disabled');
				}
				referSalesPriceInfo();
			} catch (e) {
				console.log('change: #CHK_standard_unit_price ' + e.message);
			}
 		});
 		// Change 単価 (JPY)
		$(document).on('change', '#TXT_unit_price_JPY', function() {
			try {
				calculateMarkupRatio();
			} catch (e) {
				console.log('change: #TXT_unit_price_JPY ' + e.message);
			}
		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * refer sales price information
 * 
 * @author : ANS796 - 2017/12/12 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referSalesPriceInfo(){
	try{
		//get data
		var product_cd 			= $('.TXT_product_cd').val().trim();
		var standard_unit_price = ($('#CHK_standard_unit_price').is(':checked'))?'1':'0';
		var client_cd 			= $('.TXT_client_cd').val().trim();
		var start_date 			= $('#TXT_start_date').val().trim();
	    $.ajax({
	        type        :   'POST',
	        url         :   '/master/selling-unit-price-by-client-detail/refer',
	        dataType    :   'json',
	        data        :   {
							product_cd: 			product_cd,
							standard_unit_price: 	standard_unit_price,
							client_cd: 				client_cd,
							start_date: 			start_date,
							mode: 					mode
			},
	        success: function(res) {
	            if(res.response == true) {
	            	if(res.price != null) {
						//clear all error
						_clearErrors();
		            	$('#TXT_unit_price_JPY').val(res.price['sales_unit_price_JPY']);
		            	$('#TXT_unit_price_USD').val(res.price['sales_unit_price_USD']);
		            	$('#TXT_unit_price_EUR').val(res.price['sales_unit_price_EUR']);
		            	$('#TXT_unit_price_JPY').trigger('blur');
		            	$('#TXT_unit_price_USD').trigger('blur');
		            	$('#TXT_unit_price_EUR').trigger('blur');
		            	$('#TXA_remarks').val(res.price['remarks']);
		            	// mode = 'U';
		            }
	            	//標準単価
	            	if(res.standardPrice != null){
		            	$('#DSP_standard_unit_price_JPY').text(res.standardPrice['standard_unit_price_JPY'].replace(/\.00$/,''));
		            	$('#DSP_standard_unit_price_USD').text(res.standardPrice['standard_unit_price_USD'].replace(/\.00$/,''));
		            	$('#DSP_standard_unit_price_EUR').text(res.standardPrice['standard_unit_price_EUR'].replace(/\.00$/,''));
		            	calculateMarkupRatio();
		            }
		            mode = 'U';
	            	$('#operator_info').html(res.header);
	            } else {
	            	if(product_cd == '' || client_cd == '' || start_date == ''){
	            		if (mode == 'U') {
		            		clearAllItem();
		            	} else if (mode == 'I') {
		            		//clear operator_info
							$('#operator_info').html('');
		            	}
		            }else{
						if (mode == 'U') {
							clearAllItem();
							/*jMessage('W001',function(r){
								if(r){
				            			clearAllItem();
				            		}
								});*/
		            	} else if (mode == 'I') {
		            		//clear operator_info
							$('#operator_info').html('');
		            	}
		            	//標準単価
		            	if(res.standardPrice != null){
			            	$('#DSP_standard_unit_price_JPY').text(res.standardPrice['standard_unit_price_JPY'].replace(/\.00$/,''));
			            	$('#DSP_standard_unit_price_USD').text(res.standardPrice['standard_unit_price_USD'].replace(/\.00$/,''));
			            	$('#DSP_standard_unit_price_EUR').text(res.standardPrice['standard_unit_price_EUR'].replace(/\.00$/,''));
			            	calculateMarkupRatio();
			            }
		            }
					mode = 'I';
	            }
	            // _settingButtonDetele(mode);
	            $('.heading-btn-group').html(res.button);
	            autoTabindexButton(15, parentClass = '.navbar-nav', childClass = '.btn-link');
	        },
	    });
	} catch(e) {
        console.log('referUserInfo' + e.message)
    }
}
/**
 * save data all - insert/update
 * 
 * @author : ANS796 - 2017/12/12 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function save(){
	try{
	    var data = {
	    	product_cd 			: $('.TXT_product_cd').val().trim(),
	    	client_cd 			: $('.TXT_client_cd').val().trim(),
	    	start_date 			: $('#TXT_start_date').val().trim(),
	    	unit_price_JPY		: ($('#TXT_unit_price_JPY').val() == '')?0:$('#TXT_unit_price_JPY').val().trim().replace(/,/g,''),
	    	unit_price_USD		: ($('#TXT_unit_price_USD').val() == '')?0:$('#TXT_unit_price_USD').val().trim().replace(/,/g,''),
	    	unit_price_EUR		: ($('#TXT_unit_price_EUR').val() == '')?0:$('#TXT_unit_price_EUR').val().trim().replace(/,/g,''),
	    	remarks 			: $('#TXA_remarks').val().trim(),
	    	mode 				: mode
	    };
	    $.ajax({
	        type        :   'POST',
	        url         :   '/master/selling-unit-price-by-client-detail/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	//display E005 error when 製品コード and 取引先コード not exists
	        	if(res.data_err != null){
	        		jMessage('E005', function(r){
	        			if(r){
	        				for(var i = 0; i < res.data_err.length; i++){
	        					$('.'+res.data_err[i]['item_err']).errorStyle(_text['E005']);
	        				}
	        			}
	        		});
	        	}else if(res.error_cd != ''){
            		jMessage(res.error_cd);
            	}else if(res.response == true){
	            	var msg = (mode == 'I')?'I001':'I003';
	            	jMessage(msg,function(r){
						if(r){
							referSalesPriceInfo();
						}
					});
	            }else{
	            	//catch DB error and display
	            	var msg_e999 = _text['E999'].replace('{0}', res.error);
	            	jMessage_str('E999', msg_e999, '', msg_e999);
	            }
	        },
	    });
	} catch(e) {
        console.log('postSave' + e.message)
    }
}
/**
 * delete User
 * 
 * @author : ANS796 - 2017/11/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postDelete(){
	try{
	    var data = {
	    	product_cd 			: $('.TXT_product_cd').val().trim(),
	    	client_cd 			: $('.TXT_client_cd').val().trim(),
	    	start_date 			: $('#TXT_start_date').val().trim(),
	    };
	    $.ajax({
	        type        :   'POST',
	        url         :   '/master/selling-unit-price-by-client-detail/delete',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	if(res.error_cd != ''){
            		jMessage(res.error_cd);
            	}else if(res.response == true){
            		if (is_new == 'true') {
						mode	=	'U';
					} else {
						mode	=	'I';
					}
	            	jMessage('I002',function(r){
						if(r){
		            		var param = {
								'mode'		: 'I',
								'from'		: 'SellingUnitPriceByClientSearch',
								'is_new'	: is_new
							};
							_postParamToLink('SellingUnitPriceByClientSearch', 'SellingUnitPriceByClientDetail', '/master/selling-unit-price-by-client-detail', param);
						}
					});
	            }else{
	            	//catch DB error and display
	            	var msg_e999 = _text['E999'].replace('{0}', res.error);
	            	jMessage_str('E999', msg_e999, '', msg_e999);
	            }
	        },
	    });
	} catch(e) {
        console.log('postDelete' + e.message)
    }
}
/**
 * setting Button Delete
 *
 * @author      :   ANS796 - 2017/11/08 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function _settingButtonDetele(mode) {
    try {
        if(mode=='I'){
            $('#btn-delete').hide();
        }
        if(mode=='U'){
            $('#btn-delete').show();
        }
    } catch (e) {
        console.log('_settingButtonDetele' + e.message);
    }
}
/**
 * clear all item screen
 *
 * @author      :   ANS796 - 2017/11/08 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function clearAllItem() {
    try {
		//clear all error
		//_clearErrors();
       	$('#TXT_unit_price_JPY').val('');
    	$('#TXT_unit_price_USD').val('');
    	$('#TXT_unit_price_EUR').val('');
    	$('#TXA_remarks').val('');
    	$('#DSP_standard_unit_price_JPY').text('');
    	$('#DSP_standard_unit_price_USD').text('');
    	$('#DSP_standard_unit_price_EUR').text('');
    	$('#DSP_markup_ratio').text('');
    	$('#operator_info').html('');
    } catch (e) {
        console.log('clearAllItem' + e.message);
    }
}
/**
 * calculate Markup Ratio
 *
 * @author      :   ANS796 - 2017/12/12 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function calculateMarkupRatio() {
    try {
    	var customer_unit_price = $('#TXT_unit_price_JPY').val().trim().replace(/,/g,'');
    	var standard_unit_price = $('#DSP_standard_unit_price_JPY').text().trim().replace(/,/g,'');
    	if(customer_unit_price == '' || standard_unit_price == '' || standard_unit_price == '0'){
    		 $('#DSP_markup_ratio').text('');
    	}else{
	        $('#DSP_markup_ratio').text(parseFloat((1-(parseFloat(customer_unit_price)/
		            			parseFloat(standard_unit_price)))*100).toFixed(2)+'%');
	    }
    } catch (e) {
        console.log('calculateMarkupRatio' + e.message);
    }
}
/**
 * validate
 *
 * @author		:	DuyTP - 2017/06/15 - create
 * @params		:	null
 * @return		:	null
 */
function validate(){
	var _errors = 0;
	if(!_validate($('body'))){
		_errors++;
	}
	if(_errors>0)
		return false;

	return true;
}