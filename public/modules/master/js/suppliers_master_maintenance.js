/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/12/12
 * 作成者		:	ANS804
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
 // declare obj city country
var objCityReferCountry = {
	'TXT_city_cd'            : 'TXT_country_cd',
	'TXT_post_city_cd'       : 'TXT_post_country_cd',
	'TXT_billing_city_div'   : 'TXT_billing_country_div',
	'TXT_delivery_city_div'  : 'TXT_delivery_country_div',
	'TXT_consignee_city_div' : 'TXT_consignee_country_div',
};
var objCountryReferCity = {
	'TXT_country_cd'            : 'TXT_city_cd',
	'TXT_post_country_cd'       : 'TXT_post_city_cd',
	'TXT_billing_country_div'   : 'TXT_billing_city_div',
	'TXT_delivery_country_div'  : 'TXT_delivery_city_div',
	'TXT_consignee_country_div' : 'TXT_consignee_city_div',
};
$(document).ready(function () {
 	initCombobox();
	initEvents();
	changeTab02();
	if (mode != 'I') {
		disableClient(mode);
		$("#TXT_client_cd").addClass("required");
	} else {
		disableClient(mode);
		$("#TXT_client_cd").removeClass("required");
		$("#TXT_client_cd").val('');
	}

	// set again ajaxSetup (clear init datepicker)
	$.ajaxSetup({
		complete: function(res){
			_setTabindexForDatepicker();
			if (res.status != null && res.status == 404) {
				location.href = '/';
			} else if(res.status==409) {
				location.href = '/example';
			}
			//該当するデータが存在しません。
			if (this.url.indexOf('search') > -1) {
				$('.dataTables_empty').html(_text_empty);
			}
		}
	});

});

/**
 * initCombobox
 *
 * @author 	:	ANS804 - 2017/12/12 - create
 * @params 	:	null
 * @return 	:	null
 * @access 	: 	public
 * @see 	:
 */
function initCombobox() {
	// _getComboboxData('JP', 'payment_conditions_div');
	// _getComboboxData('JP', 'payment_nums_div');
	// _getComboboxData('JP', 'postpay_date_div');
	// _getComboboxData('JP', 'exists_div');
	// _getComboboxData('JP', 'allocation_div');
	// _getComboboxData('JP', 'paydate_condition_div');
	// _getComboboxData('JP', 'payday_condition_div');
	// _getComboboxData('JP', 'bank_div');
	// _getComboboxData('JP', 'currency_div');
	// _getComboboxData('JP', 'round_div', function(){
		//refer data from screen search to detail
	if (from == 'SuppliersMasterSearch' && mode == 'U' && $('#TXT_client_cd').val() != '') {
		referClientInfo();
	}

	// init change combobox
	$('.payment_nums_div').trigger('change');
	$('.allocation_div').trigger('change');
	// });
}

/**
 * initEvents
 *
 * @author 	:	ANS804 - 2017/12/12 - create
 * @params 	:	null
 * @return 	:	null
 * @access 	: 	public
 * @see 	:
 */
function initEvents() {
	try {
		// Select all tabs
		$('.nav-tabs a').click(function(){
		    try {
			    setTabIndex();
			} catch (e) {
				console.log('nav-tabs a: ' + e.message);
			}
		});

		// init back
		$(document).on('click', '#btn-back', function () {
			try {
				sessionStorage.setItem('detail', true);
				location.href = '/master/suppliers-master-search';
			} catch (e) {
				console.log('#btn-back ' + e.message);
			}
		});

		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				if(validate($('body'))){
					var msg = (mode == 'I') ? 'C001' : 'C003';
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
				if($.trim($('#TXT_client_cd').val()) == '' ) {
					$('#TXT_client_cd').errorStyle(_MSG_E001);
				} else {
					jMessage('C002',function(r){
						if(r){
							postDelete();
						}
					});
				}
			   
			} catch (e) {
				console.log('#btn-delete ' + e.message);
			}
		});

		// button copy
		$(document).on('click', '#btn-copy', function() {
			try {
				//clear all error
				_clearErrors();
				checkTabHaveInputErrorRequired();
				mode = 'I';
				$("#btn-delete").remove();
				$("#btn-copy").remove();
				$("#TXT_client_cd").val('');
				disableClient(mode);
			} catch (e) {
				console.log('#btn-delete ' + e.message);
			}
		});

		// click tab_01
		$(document).on('click', 'a[href="#tab_01"]', function() {
			try {
				_formatDatepicker();
			} catch (e) {
				console.log('click tab_01: ' + e.message);
			}
		});

		// change payment nums div
		$(document).on('change', '.payment_nums_div', function() {
			try {
				$('.table-payment-nums tbody tr').find('td:eq(0) span').addClass('disabled');
				$('.table-payment-nums tbody tr').find('td :input').addClass('disabled');
				$('.table-payment-nums tbody tr td :input').attr('disabled', false);

				var allocation_div = $('.allocation_div').val();
				var key            = $(this).val();

				$('.table-payment-nums tbody tr').each(function(k, v) {
					if (k < key) {
						if (allocation_div !== '') {
							if (allocation_div == 0) {
								$(this).find('td:eq(0) input').removeClass('disabled');
								$(this).find('td:eq(0) span').removeClass('disabled');
							} else {
								$(this).find('td:eq(1) input').removeClass('disabled');
							}
						}
						$(this).find('td:gt(1) :input').removeClass('disabled');
					}
				});
				
				$('.table-payment-nums tbody tr').find('.disabled').attr('disabled', true);
				$('.table-payment-nums tbody tr td').find('.disabled').val('');

				setTabIndex();
			} catch (e) {
				console.log('payment_nums_div: ' + e.message);
			}
		});

		// change allocation div
		$(document).on('change', '.allocation_div', function() {
			try {
				$('.table-payment-nums tbody tr').find('td:lt(2) input').addClass('disabled');
				$('.table-payment-nums tbody tr').find('td:eq(0) span').addClass('disabled');
				$('.table-payment-nums tbody tr td :input').attr('disabled', false);

				var allocation_div = $(this).val();
				var key            = $('.payment_nums_div').val();

				$('.table-payment-nums tbody tr').each(function(k, v) {
					if (allocation_div != '' && key != '') {
						if (allocation_div == 0) {
							if (key == 0 || k < key) {
								$(this).find('td:eq(0) input').removeClass('disabled');
								$(this).find('td:eq(0) span').removeClass('disabled');
								$('#sum_TXT_payment_amount').text('0');
							}
						} else {
							if (key == 0 || k < key) {
								$(this).find('td:eq(1) input').removeClass('disabled');
								$('#sum_TXT_rate').text('0%');
							}
						}
					} else {
						$('#sum_TXT_payment_amount').text('0');
						$('#sum_TXT_rate').text('0%');
					}
				});

				$('.table-payment-nums tbody tr').find('.disabled').attr('disabled', true);
				$('.table-payment-nums tbody tr td').find('.disabled').val('')
				setTabIndex();
			} catch (e) {
				console.log('allocation_div: ' + e.message);
			}
		});

		// calculate sum of TXT_payment_rate
		$(document).on('change', '.TXT_rate', function() {
			try {
				var _this = $(this);
				if (1*_this.val() > 100) {
					_this.val('');
				}

				var sum1 = 0;
				var sum2 = 0;
				$('.TXT_rate:not(:disabled)').each(function(index, element) {
					sum1 += 1*$(element).val();
					if (sum1 > 100) {
						_this.val('');
					} else {
						sum2 = sum1;
					}

				});
				$('#sum_TXT_rate').text(sum2 + '%');
			} catch (e) {
				console.log('TXT_rate: ' + e.message);
			}
		});

		// calculate sum of TXT_payment_amount
		$(document).on('change', '.TXT_payment_amount', function() {
			try {
				var sum = 0;
				$('.TXT_payment_amount:not(:disabled)').each(function(index, element) {
					sum += 1*$(element).val().replace(/,/g,'');
				});

				sum = Math.round(sum * 100) / 100;

				$('#sum_TXT_payment_amount').text(addCommas(sum));
			} catch (e) {
				console.log('TXT_payment_amount: ' + e.message);
			}
		});

		// refer data from client_cd (取引先)
		$(document).on('change', '#TXT_client_cd', function() {
			try {
				referClientInfo();
			} catch (e) {
				console.log('refer data from client_cd (取引先) ' + e.message);
			}
		});

		// keycode TAB and ENTER
		$(document).keyup(function(event){
			try {
				// keycode TAB
			    if (event.keyCode == 9) {
			    	var curElement = document.activeElement;

			    	if ($(curElement).is('a[href="#tab_00"]')) {
			    		var tabindex = $(curElement).attr('tabindex');
			    		$(document).find('[tabindex="'+ ++tabindex + '"]').focus();
			    	}
			    }

			    // keycode ENTER
			    if (event.keyCode == 13) {
			    	var curElement = document.activeElement;

			    	if ($(curElement).is('a[href^="#tab_"]')) {
			    		var tabindex = $(curElement).attr('tabindex');
			    		$(document).find('[tabindex="'+ ++tabindex + '"]').focus();
			    	}
			    }
			} catch(e) {
				console.log('keycode TAB and ENTER: ' + e.message);
			}
		});

		//change 都市コード
		$(document).on('change', '.city_cd', function(event, focusInputFirst) {
			try {
				var element_city    = $(this);
				var city_id         = $(this).attr('id');
				
				var country_id      = objCityReferCountry[city_id];
				var element_country = $('#'+country_id);

				if (focusInputFirst) {
					_referCity(element_city.val(), element_city, element_country, function () {
						$('#TXT_client_cd').focus();

						if(element_city.is('#TXT_city_cd')) {
							referAutoCityCountry();
						}

						checkTabHaveInputErrorRequired();
					},true);
				} else {
					_referCity(element_city.val(), element_city, element_country, function () {
						if(element_city.is('#TXT_city_cd')) {
							referAutoCityCountry();
						}

						checkTabHaveInputErrorRequired();
					},true);
				}
			} catch(e) {
				console.log('change .city_cd: ' + e.message);
			}
		});

		//change 国コード
		$(document).on('change', '.country_cd', function(event, focusInputFirst) {
			try {
				var element_country = $(this);
				var country_id      = $(this).attr('id');
				
				var city_id         = objCountryReferCity[country_id];
				var element_city    = $('#'+city_id);

				if (focusInputFirst) {
					_referCountry(element_country.val(), element_city, element_country, function () {
						$('#TXT_client_cd').focus();
						if(element_country.is('#TXT_country_cd')) {
							referAutoCityCountry();
						}
					},true);
				} else {
					_referCountry(element_country.val(), element_city, element_country,function (){
						if(element_country.is('#TXT_country_cd')) {
							referAutoCityCountry();
						}
					},true);
				}
			} catch(e) {
				console.log('change .country_cd: ' + e.message);
			}
		});

		//change 親取引先コード
		$(document).on('change', '#TXT_parent_client_cd', function() {
			try {
				if ( $(this).val() == $('#TXT_client_cd').val() ) {
					var client_nm = $('#TXT_client_nm').val();
					$(this).closest('.popup').find('.client_nm').text(client_nm);
				} else {
					_removeErrorStyle($('#TXT_parent_client_cd'));
					getClientName($(this).val().trim(), $(this), '', false);
				}
			} catch(e) {
				console.log('change #TXT_parent_client_cd: ' + e.message);
			}
		});

		//change 倉庫コード
		$(document).on('change', '.warehouse_cd', function() {
			try {
				$(this).parent().addClass('popup-warehouse');
				_referWarehouse($(this).val().trim(), $(this), '', true);
				checkTabHaveInputErrorRequired();
			} catch(e) {
				console.log('change .warehouse_cd: ' + e.message);
			}
		});

		// focusout item error E005: 都市コード, 親取引先コード, 倉庫コード, 都市コード, 国コード
		$(document).on('focusout', '#TXT_city_cd, #TXT_warehouse_cd, #TXT_billing_city_div, #TXT_billing_country_div, #TXT_delivery_city_div, #TXT_delivery_country_div, #TXT_consignee_city_div, #TXT_consignee_country_div', function(){
			if ($(this).val() == '') {
				_removeErrorStyle($(this));
				checkTabHaveInputErrorE005($(this));
			} else {
				checkTabHaveInputErrorE005($(this));
			}
		});

		// focusout combobox 通貨, 金額端数処理, 基本条件, 支払回数, 後払起算日, 後払, 配分方法, 当方口座
		$(document).on('focusout', '#CMB_currency_div, #CMB_amount_rounding, #CMB_payment_info_basic_condition, #CMB_payment_nums_div, #CMB_post_payment_date, #CMB_postpay, #CMB_allocation_method, #CMB_bank_div', checkTabHaveInputErrorRequired);

		// focusout checkbox required
	   	$(document).on('focusout', '.required-checkbox', function() {
			try {
		        if ($(this).is(":checked")) {// remove error WHEN have error _MSG_E001
		        	if( $('#CHK_customer').attr('has-balloontip-message') == _MSG_E001) {
		        		_removeErrorStyle($('#CHK_customer'));
		        	}
		        	if( $('#CHK_suppliers').attr('has-balloontip-message') == _MSG_E001) {
		        		_removeErrorStyle($('#CHK_suppliers'));
		        	}
		        	if( $('#CHK_outsourcer').attr('has-balloontip-message') == _MSG_E001) {
		        		_removeErrorStyle($('#CHK_outsourcer'));
		        	}
			    } else {// remove error WHEN have error _text['E487']
			        if (!$(this).is('#CHK_customer') 
			          && $('#CHK_suppliers').attr('has-balloontip-message') == _text['E487'] 
			          && $('#CHK_outsourcer').attr('has-balloontip-message') == _text['E487']) {
			        	_removeErrorStyle($('#CHK_suppliers'));
			        	_removeErrorStyle($('#CHK_outsourcer'));
			        }
			    }
			} catch(e) {
				console.log('focusout .required-checkbox: ' + e.message);
			}
	   	});
	} catch (e) {
		console.log('initEvents: ' + e.message);
	}
}

/**
 * validate
 * 
 * @author 	: ANS804 - 2017/12/12 - create
 * @params 	: 
 * @return 	: null
 * @access 	: public
 * @see 	:
 */
function validate(element) {
	var error = 0;
	try {
		_clearErrors();
		var inputRequired,inputCheckbox, inputEmail, inputFax, inputDatepicker;

		var firstRequired    	 = 0,
			firstEmail           = 0,
			firstFax             = 0,
			firstDatepicker      = 0;
		
		var tabindexRequired 	 = -1, 
		 	tabindexCheckbox 	 = -1, 
			tabindexEmail        = -1, 
			tabindexFax          = -1,
			tabindexDatepicker   = -1;

		var arr;


		// Checkbox required
		var CHK_customer   = $('#CHK_customer').prop('checked');
		var CHK_suppliers  = $('#CHK_suppliers').prop('checked');
		var CHK_outsourcer = $('#CHK_outsourcer').prop('checked');

		//case check: not check any checkbox
		if(!CHK_customer && !CHK_suppliers && !CHK_outsourcer) {
			$('#CHK_customer').errorStyle(_MSG_E001);
			$('#CHK_suppliers').errorStyle(_MSG_E001);
			$('#CHK_outsourcer').errorStyle(_MSG_E001);

			inputCheckbox    = $('#CHK_customer');
			tabindexCheckbox = 1*inputCheckbox.attr('tabindex');

			error++;
		}

		//case check: check CHK_suppliers and CHK_outsourcer
		if (CHK_suppliers && CHK_outsourcer) {
			$('#CHK_suppliers').errorStyle(_text['E487']);// 同時に仕入先と外注先を選択できません。
			$('#CHK_outsourcer').errorStyle(_text['E487']);// 同時に仕入先と外注先を選択できません。

			inputCheckbox    = $('#CHK_suppliers');
			tabindexCheckbox = 1*inputCheckbox.attr('tabindex');

			error++;
		}

		// Required
		element.find(':input.required:not([readonly])').each(function() {
			// check input, textarea, select
			if(( $(this).is("input") 
			  || $(this).is("textarea") 
			  || $(this).is("select") )
			&& ( $.trim($(this).val()) == '' 
			  || $(this).val() == undefined) ) 
			{
				if (firstRequired < 1) {
					inputRequired     = $(this);
					if (inputRequired != undefined) {
						tabindexRequired = 1*inputRequired.attr('tabindex');
					}
					
				}
				firstRequired++;
				// add message
				$(this).errorStyle(_MSG_E001);
				error++;
            }

		});

		// Email
		element.find('input.email:enabled:not([readonly])').each(function(){
			if(!_validateEmail($(this).val())) {
				if (firstEmail < 1) {
					inputEmail    = $(this);
					tabindexEmail = 1*inputEmail.attr('tabindex');
				}
				firstEmail++;

				$(this).errorStyle(_text['E015']);//フォーマットが正しくありません。
				error++;
			}
		});


		// Fax
		element.find('input.fax:enabled:not([readonly])').each(function(){
		    if(!_validatePhoneFaxNumber($(this).val())){
				if (firstFax < 1) {
					inputFax    = $(this);
					tabindexFax = 1*inputFax.attr('tabindex');
				}
				firstFax++;

		        $(this).errorStyle(_text['E015']);
		        error++;
		    }
		});


		// date from to
		element.find('input.date-from:enabled:not([readonly])').each(function(){
			var obj = $(this).parents('.date-from-to');
		    if(!checkDateFromTo(obj)){
				if (firstDatepicker < 1) {
					inputDatepicker    = $(this);
					tabindexDatepicker = 1*inputDatepicker.attr('tabindex');
				}
				firstDatepicker++;
		        error++;
		    }
		});
		

		// check tab contain input error
		checkTabHaveInputErrorRequired();

		arr = [
			{
				'input'     : inputRequired,
				'tabindex' 	: tabindexRequired
			},
			{
				'input'     : inputCheckbox,
				'tabindex' 	: tabindexCheckbox
			},
			{
				'input'     : inputEmail,
				'tabindex' 	: tabindexEmail
			},
			{
				'input'     : inputFax,
				'tabindex' 	: tabindexFax
			},
			{
				'input'     : inputDatepicker,
				'tabindex' 	: tabindexDatepicker
			}
		];

		// return obj have tabindex smallest
		var result = arr.reduce(function(res, obj) {
			if (res.tabindex == -1) {
				return obj;
			} else if (obj.tabindex == -1) {
				return res;
			} else {
				if (obj.tabindex < res.tabindex) {
					return obj;
				} else {
					return res;
				}
			}
		});

		if (typeof result.input != "undefined") {
			referTabError(result.input);
		}

		if( error > 0 ) {
			return false;
		} else {
			return true;
		}
	} catch(e) {
		console.log('validate: ' + e.toString());
	}
}

/**
 * refer tab have input errors
 * 
 * @author 	: ANS804 - 2017/12/22 - create
 * @params 	: 
 * @return 	: null
 * @access 	: public
 * @see 	:
 */
function referTabError(element){
	try {
		var first = 0;
		var idParent;

		// get id of tab first store input error
		if (first < 1) {
			idParent = element.parents('.tab-pane').attr('id');
		}
		first++;

		// refer to tab have input error first
		if (typeof idParent != "undefined") {
			$('.nav-tabs-component').find('li').removeClass('active');
			$("a[href=#" + idParent + "]").trigger('click');
		}
		
		// focus input errors first
		element.focus();
	} catch(e) {
		console.log('referTabError: ' + e.toString());
	}
}

/**
 * check Tab Have Input Error Required
 * 
 * @author : ANS804 - 2017/12/28 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function checkTabHaveInputErrorRequired() {
	element = $(document).find('.tab-content .tab-pane');
	try {
		$(document).find('.nav-tabs-component li a').removeClass('tabError');

		element.each(function() {
			if( $(this).find('.error-item').length > 0 ) {
				var id = $(this).attr('id');
				$(document).find('a[href="#' + id + '"').addClass('tabError');
			}
		});
	} catch(e) {
		console.log('checkTabHaveInputErrorRequired: ' + e);
	}
}

/**
 * check Tab Have Input Error E005
 * 
 * @author : ANS804 - 2017/12/28 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function checkTabHaveInputErrorE005(element) {
	try {
		$(document).find('.nav-tabs-component li a').removeClass('tabError');

		element = $(document).find('.tab-content .tab-pane');

		element.each(function() {
			if( $(this).find('.error-item').length > 0 ) {
				var id = $(this).attr('id');
				$(document).find('a[href="#' + id + '"').addClass('tabError');
			}
		});
	} catch(e) {
		console.log('checkTabHaveInputErrorE005: ' + e);
	}
}

/**
 * save data all - insert/update
 * 
 * @author 	: ANS804 - 2017/12/12 - create
 * @params 	: 
 * @return 	: null
 * @access 	: public
 * @see 	:
 */
function save(){
	try{
	    var data = {
			client_cd 						: $.mbTrim($('#TXT_client_cd').val()),
			client_nm 						: $.mbTrim($('#TXT_client_nm').val()),
			cust_div 						: $('#CHK_customer').is(':checked') ? '1' : '0',
			supplier_div 					: $('#CHK_suppliers').is(':checked') ? '1' : '0',
			outsourcer_div 					: $('#CHK_outsourcer').is(':checked') ? '1' : '0',

			// tab_00
			client_ab 						: $.mbTrim($('#TXT_client_ab').val()),
			client_staff_nm 				: $.mbTrim($('#TXT_contact_nm').val()),
			client_zip 						: $.mbTrim($('#TXT_client_zip').val()),
			client_adr1 					: $.mbTrim($('#TXT_client_adr1').val()),
			client_adr2 					: $.mbTrim($('#TXT_client_adr2').val()),
			client_city_div					: $.mbTrim($('#TXT_city_cd').val()),
			client_country_div 				: $.mbTrim($('#TXT_country_cd').val()),
			port_city_div 					: $.mbTrim($('#TXT_post_city_cd').val()),
			port_country_div 				: $.mbTrim($('#TXT_post_country_cd').val()),
			client_tel 						: $.mbTrim($('#TXT_client_tel').val()),
			client_fax 						: $.mbTrim($('#TXT_fax_no').val()),
			e_mail1 						: $.mbTrim($('#TXT_e_mail1').val()),
			e_mail2 						: $.mbTrim($('#TXT_e_mail2').val()),
			e_mail3 						: $.mbTrim($('#TXT_e_mail3').val()),
			client_url 						: $.mbTrim($('#TXT_client_url').val()),
			parent_client_cd 				: $.mbTrim($('#TXT_parent_client_cd').val()),
			in_warehouse_div 				: $.mbTrim($('#TXT_warehouse_cd').val()),
			remarks 						: $.mbTrim($('#TXA_remarks').val()),

			// tab_01
	    	client_st_date 					: $.mbTrim($('#TXT_client_st_date').val()),
	    	client_ed_date 					: $.mbTrim($('#TXT_client_ed_date').val()),
	    	mark1 							: $.mbTrim($('#TXT_mark1').val()),
	    	mark2 							: $.mbTrim($('#TXT_mark2').val()),
	    	mark3 							: $.mbTrim($('#TXT_mark3').val()),
	    	mark4 							: $.mbTrim($('#TXT_mark4').val()),
	    	sales_currency_div				: $.mbTrim($('#CMB_currency_div').val()),
	    	sales_amt_round_div				: $.mbTrim($('#CMB_amount_rounding').val()),

	    	// tab_02
	    	payment_conditions_div 			: $.mbTrim($('#CMB_payment_info_basic_condition').val()),
	    	payment_nums_div 				: $.mbTrim($('#CMB_payment_nums_div').val()),
	    	postpay_exists_div 				: $.mbTrim($('#CMB_postpay').val()),
	    	postpay_date_div 				: $.mbTrim($('#CMB_post_payment_date').val()),
	    	allocation_div 					: $.mbTrim($('#CMB_allocation_method').val()),
	    	payment_remarks					: $.mbTrim($('#TXT_other').val()),

	    	// tab_03
	    	withdrawal_conditions_div 		: $.mbTrim($('#CMB_withdrawal_info_basic_condition').val()),
	    	withdrawal_conditions_notes 	: $.mbTrim($('#TXT_incidental_condition').val()),

	    	// tab_05
	    	bank_div 						: $.mbTrim($('#CMB_bank_div').val()),

	    	// tab_06
	    	billing_nm 						: $.mbTrim($('#TXT_billing_nm').val()),
	    	billing_staff_nm 				: $.mbTrim($('#TXT_billing_staff_nm').val()),
	    	billing_zip 					: $.mbTrim($('#TXT_billing_zip').val()),
	    	billing_adr1 					: $.mbTrim($('#TXT_billing_adr1').val()),
	    	billing_adr2 					: $.mbTrim($('#TXT_billing_adr2').val()),
	    	billing_city_div 				: $.mbTrim($('#TXT_billing_city_div').val()),
	    	billing_country_div 			: $.mbTrim($('#TXT_billing_country_div').val()),
	    	billing_tel 					: $.mbTrim($('#TXT_billing_tel').val()),
	    	billing_fax 					: $.mbTrim($('#TXT_billing_fax').val()),
	    	billing_mail 					: $.mbTrim($('#TXT_billing_mail').val()),

	    	// tab_07
	    	delivery_nm 					: $.mbTrim($('#TXT_delivery_nm').val()),
	    	delivery_staff_nm 				: $.mbTrim($('#TXT_delivery_staff_nm').val()),
	    	delivery_zip 					: $.mbTrim($('#TXT_delivery_zip').val()),
	    	delivery_adr1 					: $.mbTrim($('#TXT_delivery_adr1').val()),
	    	delivery_adr2 					: $.mbTrim($('#TXT_delivery_adr2').val()),
	    	delivery_city_div 				: $.mbTrim($('#TXT_delivery_city_div').val()),
	    	delivery_country_div			: $.mbTrim($('#TXT_delivery_country_div').val()),
	    	delivery_tel 					: $.mbTrim($('#TXT_delivery_tel').val()),
	    	delivery_fax 					: $.mbTrim($('#TXT_delivery_fax').val()),
	    	delivery_mail 					: $.mbTrim($('#TXT_delivery_mail').val()),

	    	// tab_08
	    	consignee_nm 					: $.mbTrim($('#TXT_consignee_nm').val()),
	    	consignee_staff_nm 				: $.mbTrim($('#TXT_consignee_staff_nm').val()),
	    	consignee_zip 					: $.mbTrim($('#TXT_consignee_zip').val()),
	    	consignee_adr1 					: $.mbTrim($('#TXT_consignee_adr1').val()),
	    	consignee_adr2 					: $.mbTrim($('#TXT_consignee_adr2').val()),
	    	consignee_city_div 				: $.mbTrim($('#TXT_consignee_city_div').val()),
	    	consignee_country_div			: $.mbTrim($('#TXT_consignee_country_div').val()),
	    	consignee_tel 					: $.mbTrim($('#TXT_consignee_tel').val()),
	    	consignee_fax 					: $.mbTrim($('#TXT_consignee_fax').val()),
	    	consignee_mail 					: $.mbTrim($('#TXT_consignee_mail').val()),

	    	// other
	    	mode 							: mode,
	    	out_warehouse_div				: _constVal1['out_warehouse_div'],
	    	purchase_currency_div 			: 'JPY',
	    	purchase_amt_round_div			: '1'
	    };

	    // m_client_payment
		var m_client_payment_arr = [];
		var number_times         = $('#CMB_payment_nums_div').val().trim();
		if (!!number_times) {
			for (var i = 1; i <= number_times; i++) {
				var m_client_payment     = {
			    	'payment_condition_div' : i,
			    	'payment_rate' 			: $('#row_' + i).find('.TXT_rate').val().replace(/,/g,''),
			    	'payment_amt' 			: $('#row_' + i).find('.TXT_payment_amount').val().replace(/,/g,''),
			    	'paydate_condition_div' : $('#row_' + i).find('.CMB_conditions').val(),
			    	'payday_condition_div' 	: $('#row_' + i).find('.CMB_days').val(),
			    };
			    m_client_payment_arr.push(m_client_payment);
			}
		}
		
		data.m_client_payment = m_client_payment_arr;

	    $.ajax({
	        type        :   'POST',
	        url         :   '/master/suppliers-master-maintenance/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	if (res.response) {
	        		if( res.error_cd != '' ){
	            		jMessage(res.error_cd, function(r){
		        			if(r){
		            			setItemErrorE005(res.error_list);
		            		}
	        			});
	            	} else {
		            	var msg = (mode == 'I') ? 'I001' : 'I003';
		            	jMessage(msg,function(r){
							if(r){
								$('#TXT_client_cd').val(res.client_cd);
								referClientInfo();
							}
						});
		            }
	        	} else {
	            	//catch DB error and display
	            	var msg_e999 = _text['E999'].replace('{0}', res.error);
	            	jMessage_str('E999', msg_e999, '', msg_e999);
	            }
	        },
	    });
	} catch(e) {
        console.log('postSave: ' + e.message)
    }
}

/**
 * referClientInfo
 * 
 * @author : ANS804 - 2017/12/12 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referClientInfo(){
	try{
		//clear all error
		_clearErrors();

		//get data
		var client_cd = $('#TXT_client_cd').val().trim();

		if (mode == 'U') {
    		clearAllItem();
    	}
		
		var data      = {
	    	client_cd 	: client_cd,
	    	mode 		: mode
	    };

	    $.ajax({
	        type        :   'POST',
	        url         :   '/master/suppliers-master-maintenance/refer',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if(res.response == true) {
	            	// m_client
	            	$('#TXT_client_cd').val(res.client['client_cd']),
					$('#TXT_client_nm').val(res.client['client_nm']);
					if (res.client['cust_div'] == 1) {
						$('#CHK_customer').prop('checked',true)
					}
					if (res.client['supplier_div'] == 1) {
						$('#CHK_suppliers').prop('checked',true)
					}
					if (res.client['outsourcer_div'] == 1) {
						$('#CHK_outsourcer').prop('checked',true)
					}

					var TXT_parent_client_cd = res.client['parent_client_nm'] || res.client['client_nm'];

					// tab_00
					$('#TXT_client_ab').val(res.client['client_ab']),
					$('#TXT_contact_nm').val(res.client['client_staff_nm']),
					$('#TXT_client_zip').val(res.client['client_zip']),
					$('#TXT_client_adr1').val(res.client['client_adr1']),
					$('#TXT_client_adr2').val(res.client['client_adr2']),
					$('#TXT_city_cd').val(res.client['client_city_div']),
					$('#TXT_city_cd').closest('.popup').find('.city_nm').text(res.client['client_city_nm']),
					$('#TXT_country_cd').val(res.client['client_country_div']),
					$('#TXT_country_cd').closest('.popup').find('.country_nm').text(res.client['client_country_nm']),
					$('#TXT_post_city_cd').val(res.client['port_city_div']),
					$('#TXT_post_city_cd').closest('.popup').find('.city_nm').text(res.client['port_city_nm']),
					$('#TXT_post_country_cd').val(res.client['port_country_div']),
					$('#TXT_post_country_cd').closest('.popup').find('.country_nm').text(res.client['port_country_nm']),
					$('#TXT_client_tel').val(res.client['client_tel']),
					$('#TXT_fax_no').val(res.client['client_fax']),
					$('#TXT_e_mail1').val(res.client['client_mail1']),
					$('#TXT_e_mail2').val(res.client['client_mail2']),
					$('#TXT_e_mail3').val(res.client['client_mail3']),
					$('#TXT_client_url').val(res.client['client_url']),
					$('#TXT_parent_client_cd').val(res.client['parent_client_cd']),
					$('#TXT_parent_client_cd').closest('.popup').find('.client_nm').text(res.client['parent_client_nm']),
					$('#TXT_warehouse_cd').val(res.client['in_warehouse_div']),
					$('#TXT_warehouse_cd').closest('.popup').find('.warehouse_nm').text(res.client['in_warehouse_nm']),
					$('#TXA_remarks').val(res.client['remarks']);

					// tab_01
					var TXT_client_st_date = !!res.client['client_st_date'] ? res.client['client_st_date'].replace(/-/g,'/') : '';
					var TXT_client_ed_date = !!res.client['client_ed_date'] ? res.client['client_ed_date'].replace(/-/g,'/') : '';
					$('#TXT_client_st_date').val(TXT_client_st_date),
					$('#TXT_client_ed_date').val(TXT_client_ed_date),
					$('#TXT_mark1').val(res.client['mark1']),
					$('#TXT_mark2').val(res.client['mark2']),
					$('#TXT_mark3').val(res.client['mark3']),
					$('#TXT_mark4').val(res.client['mark4']),
					$('#CMB_currency_div').val(res.client['sales_currency_div']),
					$('#CMB_amount_rounding').val(res.client['sales_amt_round_div']),

					// tab_02
					$('#CMB_payment_info_basic_condition').val(res.client['payment_conditions_div']),
					$('#CMB_payment_nums_div').val(res.client['payment_nums_div']),
					$('#CMB_postpay').val(res.client['postpay_exists_div']),
					$('#CMB_post_payment_date').val(res.client['postpay_date_div']),
					$('#CMB_allocation_method').val(res.client['allocation_div']),
					$('#TXT_other').val(res.client['payment_remarks']),

					// tab_03
					$('#CMB_withdrawal_info_basic_condition').val(res.client['withdrawal_conditions_div']),
					$('#TXT_incidental_condition').val(res.client['withdrawal_conditions_notes']),

					// tab_05
					$('#CMB_bank_div').val(res.client['bank_div']),

					// tab_06
					$('#TXT_billing_nm').val(res.client['billing_nm']),
					$('#TXT_billing_staff_nm').val(res.client['billing_staff_nm']),
					$('#TXT_billing_zip').val(res.client['billing_zip']),
					$('#TXT_billing_adr1').val(res.client['billing_adr1']),
					$('#TXT_billing_adr2').val(res.client['billing_adr2']),
					$('#TXT_billing_city_div').val(res.client['billing_city_div']),
					$('#TXT_billing_city_div').closest('.popup').find('.city_nm').text(res.client['billing_city_nm']),
					$('#TXT_billing_country_div').val(res.client['billing_country_div']),
					$('#TXT_billing_country_div').closest('.popup').find('.country_nm').text(res.client['billing_country_nm']),
					$('#TXT_billing_tel').val(res.client['billing_tel']),
					$('#TXT_billing_fax').val(res.client['billing_fax']),
					$('#TXT_billing_mail').val(res.client['billing_mail']),
					$('#TXT_delivery_nm').val(res.client['delivery_nm']),

					// tab_07
					$('#TXT_delivery_staff_nm').val(res.client['delivery_staff_nm']),
					$('#TXT_delivery_zip').val(res.client['delivery_zip']),
					$('#TXT_delivery_adr1').val(res.client['delivery_adr1']),
					$('#TXT_delivery_adr2').val(res.client['delivery_adr2']),
					$('#TXT_delivery_city_div').val(res.client['delivery_city_div']),
					$('#TXT_delivery_city_div').closest('.popup').find('.city_nm').text(res.client['delivery_city_nm']),
					$('#TXT_delivery_country_div').val(res.client['delivery_country_div']),
					$('#TXT_delivery_country_div').closest('.popup').find('.country_nm').text(res.client['delivery_country_nm']),
					$('#TXT_delivery_tel').val(res.client['delivery_tel']),
					$('#TXT_delivery_fax').val(res.client['delivery_fax']),
					$('#TXT_delivery_mail').val(res.client['delivery_mail']),

					// tab_08
					$('#TXT_consignee_nm').val(res.client['consignee_nm']),
					$('#TXT_consignee_staff_nm').val(res.client['consignee_staff_nm']),
					$('#TXT_consignee_zip').val(res.client['consignee_zip']),
					$('#TXT_consignee_adr1').val(res.client['consignee_adr1']),
					$('#TXT_consignee_adr2').val(res.client['consignee_adr2']),
					$('#TXT_consignee_city_div').val(res.client['consignee_city_div']),
					$('#TXT_consignee_city_div').closest('.popup').find('.city_nm').text(res.client['consignee_city_nm']),
					$('#TXT_consignee_country_div').val(res.client['consignee_country_div']),
					$('#TXT_consignee_country_div').closest('.popup').find('.country_nm').text(res.client['consignee_country_nm']),
					$('#TXT_consignee_tel').val(res.client['consignee_tel']),
					$('#TXT_consignee_fax').val(res.client['consignee_fax']),
					$('#TXT_consignee_mail').val(res.client['consignee_mail']),

	            	$('#operator_info').html(res.header);

					// m_client_payment
	            	changeTab02(res.client_payment);

	            	mode = 'U';
	            } else {
	            	if ($('#TXT_client_cd').val() != ''){	
						jMessage('W001',function(r){
							if(r){
		            			clearAllItem();
							}
						});
					} else {
		            		clearAllItem();
					}
	            }
	            disableClient(mode);
	            _settingButtonDelete(mode);

	            $('.heading-btn-group').html(res.button);
	            autoTabindexButton(15, parentClass = '.navbar-nav', childClass = '.btn-link');

	            // check tab contain input error
	            checkTabHaveInputErrorRequired();
	        },
	    });
	} catch(e) {
        console.log('referClientInfo' + e.message)
    }
}

/**
 * clear all item screen
 *
 * @author      :   ANS804 - 2017/12/12 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function clearAllItem() {
    try {
        $('#TXT_client_nm').val('');
        $('#CHK_customer').prop('checked',false);
        $('#CHK_suppliers').prop('checked',false);
        $('#CHK_outsourcer').prop('checked',false);

        // tab_00
        $('#TXT_client_ab').val('');
        $('#TXT_contact_nm').val('');
        $('#TXT_client_zip').val('');
        $('#TXT_client_adr1').val('');
        $('#TXT_client_adr2').val('');
        $('#TXT_city_cd').val('');
        $('#TXT_country_cd').val('');
        $('.country_nm').val('');
        $('#TXT_post_city_cd').val('');
        $('#TXT_post_country_cd').val('');
        $('#TXT_client_tel').val('');
        $('#TXT_fax_no').val('');
        $('#TXT_e_mail1').val('');
        $('#TXT_e_mail2').val('');
        $('#TXT_e_mail3').val('');
        $('#TXT_client_url').val('');
        $('#TXT_parent_client_cd').val('');
        $('.client_nm').text('');
        $('#TXT_warehouse_cd').val('');
        $('.warehouse_nm').text('');
        $('#TXA_remarks').val('');

        // tab_01
        $('#TXT_client_st_date').val('');
        $('#TXT_client_ed_date').val('');
        $('#TXT_mark1').val('');
        $('#TXT_mark2').val('');
        $('#TXT_mark3').val('');
        $('#TXT_mark4').val('');
        $('#CMB_currency_div').val('');
        $('#CMB_amount_rounding').val('');

        // tab_02
        $('#CMB_payment_info_basic_condition').val('');
        $('#CMB_payment_nums_div').val('');
        $('#CMB_postpay').val('');
        $('#CMB_post_payment_date').val('');
        $('#CMB_allocation_method').val('');
        $('#TXT_other').val('');

        //// table
        $('.TXT_rate').val('');
        $('#sum_TXT_rate').text('0%');

        $('.TXT_payment_amount').val('');
        $('#sum_TXT_payment_amount').text('0');
        
        $('.CMB_conditions').val('');
        
        $('.CMB_days').val('');

        $('.payment_nums_div').trigger('change');

        // tab_03
        $('#CMB_withdrawal_info_basic_condition').val('');
        $('#TXT_incidental_condition').val('');

        // tab_05
        $('#CMB_bank_div').val('');

        // tab_06
        $('#TXT_billing_nm').val('');
        $('#TXT_billing_staff_nm').val('');
        $('#TXT_billing_zip').val('');
        $('#TXT_billing_adr1').val('');
        $('#TXT_billing_adr2').val('');
        $('#TXT_billing_city_div').val('');
        $('#TXT_billing_country_div').val('');
        $('#TXT_billing_tel').val('');
        $('#TXT_billing_fax').val('');
        $('#TXT_billing_mail').val('');
        $('#TXT_delivery_nm').val('');

        // tab_07
        $('#TXT_delivery_staff_nm').val('');
        $('#TXT_delivery_zip').val('');
        $('#TXT_delivery_adr1').val('');
        $('#TXT_delivery_adr2').val('');
        $('#TXT_delivery_city_div').val('');
        $('#TXT_delivery_country_div').val('');
        $('#TXT_delivery_tel').val('');
        $('#TXT_delivery_fax').val('');
        $('#TXT_delivery_mail').val('');

        // tab_08
        $('#TXT_consignee_nm').val('');
        $('#TXT_consignee_staff_nm').val('');
        $('#TXT_consignee_zip').val('');
        $('#TXT_consignee_adr1').val('');
        $('#TXT_consignee_adr2').val('');
        $('#TXT_consignee_city_div').val('');
        $('#TXT_consignee_country_div').val('');
        $('#TXT_consignee_tel').val('');
        $('#TXT_consignee_fax').val('');
        $('#TXT_consignee_mail').val('');

        // other
        $('.city_nm').text('');
        $('.country_nm').text('');

    	$('#operator_info').html('');

    	//set default value of select
    	$('select').each(function() {
    		if($(this).attr('data-ini-target') == 'true'){
	    		var objParent = $(this);
	    		objParent.find('option').each(function(){
	    			if($(this).attr('data-ini_target_div') == 1){
						objParent.val($(this).attr('value'));
						objParent.trigger('change');
					}
	    		});
	    	}
		});
    } catch (e) {
        console.log('clearAllItem' + e.message);
    }
}

/**
 * setting Button Delete
 *
 * @author      :   ANS804 - 2017/12/12 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function _settingButtonDelete(mode) {
    try {
        if (mode=='I') {
            $('#btn-delete').hide();
        }
        if(mode=='U'){
            $('#btn-delete').show();
        }
    } catch (e) {
        console.log('_settingButtonDelete' + e.message);
    }
}

/**
 * changeTab02
 *
 * @author      :   ANS804 - 2017/12/12 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function changeTab02(data) {
	try {
		$(document).find('.payment_nums_div').trigger('change');
		$(document).find('.allocation_div').trigger('change');
		
		var len = 0;
		var CMB_allocation_method = 1*$('#CMB_allocation_method').val();
		if (typeof data !== 'undefined' && data.length > 0) {
			len = data.length;

			for (var i = 0;i < len; i++) {
				var j = i + 1;
				var payment_rate          = (1*data[i].payment_rate.replace(/,/g,'')).toFixed(2).replace(/\.00/,'');
				var payment_amt           = (1*data[i].payment_amt.replace(/,/g,'')).toFixed(2).replace(/\.00/,'');
				var paydate_condition_div = data[i].paydate_condition_div;
				var payday_condition_div  = data[i].payday_condition_div;

			 	if (CMB_allocation_method == 0) {
			 		$('.table-payment-nums').find('tr:nth-child('+ j +') td:nth-child(2) input').val(payment_rate);
			 	} else if (CMB_allocation_method == 1) {
			 		$('.table-payment-nums').find('tr:nth-child('+ j +') td:nth-child(3) input').val(payment_amt);
			 	}

			 	if (!!paydate_condition_div) {
			 		$('.table-payment-nums').find('tr:nth-child('+ j +') td:nth-child(4) select').val(paydate_condition_div);
			 	}

			 	if (!!payday_condition_div) {
			 		$('.table-payment-nums').find('tr:nth-child('+ j +') td:nth-child(5) select').val(payday_condition_div);
			 	}
			}	
		}
		// calculate sum
		$('.TXT_rate').trigger('change');
		$('.TXT_payment_amount').trigger('change');
		$('.TXT_payment_amount').trigger('blur');
	} catch(e) {
        console.log('changeTab02: ' + e.message)
    }
}

/**
 * delete
 * 
 * @author : ANS804 - 2017/12/12 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postDelete(){
	try{
	    var data = {
	    	client_cd 	: $('#TXT_client_cd').val().trim()
	    };

	    $.ajax({
	        type        :   'POST',
	        url         :   '/master/suppliers-master-maintenance/delete',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	if(res.error_cd != ''){
            		jMessage(res.error_cd);
            	} else if(res.response == true) {
					mode	=	'U';

	            	jMessage('I002',function(r){
						if(r){
							$('.client_cd').val('');
		            		var param = {
								'mode'		: mode,
								'from'		: 'SuppliersMasterSearch',
								'is_new'	: is_new
							};
							_postParamToLink('SuppliersMasterSearch', 'SuppliersMasterMaintenance', '/master/suppliers-master-maintenance', param);
						}
					});
	            } else {
	            	//catch DB error and display
	            	var msg_e999 = _text['E999'].replace('{0}', res.error);
	            	jMessage_str('E999', msg_e999, '', msg_e999);
	            }
	        },
	    });
	} catch(e) {
        console.log('postDelete: ' + e.message)
    }
}

/**
 * setTabIndex
 * 
 * @author : ANS804 - 2017/12/21 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setTabIndex() {
	try {
		var start = 1;
		start     = setTabIndexGroup(start,'.search-field','');

		start     = setTabIndexGroup(start,'#tab_00','tab');
		start     = setTabIndexGroup(start,'#tab_01','tab');
		start     = setTabIndexGroup(start,'#tab_02','tab');
		start     = setTabIndexGroup(start,'#tab_03','tab');
		start     = setTabIndexGroup(start,'#tab_05','tab');
		start     = setTabIndexGroup(start,'#tab_06','tab');
		start     = setTabIndexGroup(start,'#tab_07','tab');
		start     = setTabIndexGroup(start,'#tab_08','tab');

		autoTabindexButton(start, parentClass = '.navbar-nav', childClass = '.btn-link');

		$('input[disabled], input[readonly], textarea[disabled], textarea[readonly], select[disabled], button[disabled]').attr('tabindex', '-1');
		// $('input:first').focus();
	} catch(e) {
        console.log('setTabIndex: ' + e.message)
    }
}

/**
 * setTabIndexGroup
 * 
 * @author : ANS804 - 2017/12/21 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setTabIndexGroup(start,dom,group) {
	try {
		if (group == 'tab') {
			// set tabindex for tab in navbar
			$('.nav-tabs').find('a[href="' + dom + '"]').attr('tabindex', start++); 
		}

		$(dom).find(':input:not(".disabled")').each(function(index, element) {
				$(this).attr('tabindex', start++);
				if ($(this).hasClass('datepicker') || $(this).hasClass('month')) {
					$(this).next().attr('tabindex', start++);
				}
			});
		return start;
	} catch(e) {
        console.log('setTabIndexGroup: ' + e.message)
    }
}

/**
 * check Date From To
 * 
 * @author : ANS804 - 2017/12/27 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function checkDateFromTo(obj) {
	var dateFrom 	=	obj.find('.date-from').val();
	var dateTo 		=	obj.find('.date-to').val();

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
        console.log('checkDateFromTo: ' + e.message);
    }
}

/**
 * refer library city and country
 * 
 * @author : ANS804 - 2017/12/27 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referCity(city_div, element, callback) {
	try	{
		$.ajax({
			type 		: 'GET',
			url 		: '/common/refer/refer-city',
			dataType	: 'json',
			data 		: {city_div : city_div},
			success: function(res) {
				if (res.response) {
					element.parents('.popup').find('.city_nm').text(res.data.city_nm);
					element.parents('.form-group').next().find('.country_cd').val(res.data.country_div);
					element.parents('.form-group').next().find('.country_nm').text(res.data.country_nm);
				} else {
					element.parents('.popup').find('.city_cd').val('');
					element.parents('.popup').find('.city_nm').text('');
					element.parents('.form-group').next().find('.country_cd').val('');
					element.parents('.form-group').next().find('.country_nm').text('');
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
 * @author : ANS804 - 2017/12/27 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referCountry(country_div, element, callback) {
	try	{
		$.ajax({
			type 		: 'GET',
			url 		: '/common/refer/refer-country',
			dataType	: 'json',
			data 		: {country_div : country_div},
			success: function(res) {
				if (res.response) {
					element.parents('.popup').find('.country_nm').text(res.data.country_nm);
					element.parents('.form-group').prev().find('.city_cd').val('');
					element.parents('.form-group').prev().find('.city_nm').text('');
				} else {
					element.parents('.popup').find('.country_cd').val('');
					element.parents('.popup').find('.country_nm').text('');
					element.focus();
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
 * get Client Name
 * 
 * @author : ANS804 - 2018/05/04 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getClientName(client_cd, element, callback, deleteKey) {
    try {
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
                		if (deleteKey) {
	                		element.parents('.popup').find('.TXT_client_cd').val('');
	                	}

                		element.parents('.popup').find('.client_nm').text('');
                	}
                } else {
                	if (deleteKey) {
                		element.parents('.popup').find('.TXT_client_cd').val('');
                	}

            		element.parents('.popup').find('.client_nm').text('');
                }

                element.parents('.popup').find('.TXT_client_cd').focus();

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
 * get Error Item
 * 
 * @author : ANS804 - 2018/01/30 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setItemErrorE005(error_list) {
 	try {
 		if (!!error_list) {
 			// show error
 			$.each(error_list,function(index, value){
 				if (value != '' && $('#' + value).val() != '') {
 					// set message error
 					$('#' + value).errorStyle(_text['E005']);

 					// set tab error
 					var tabError = $('#' + value).closest('.tab-pane').attr('id');
 					$('.nav-tabs-component a[href=#'+tabError+']').addClass('tabError');
 				}	
 			});

 			// trigger click tab store first error
 			var itemErrorE005First = $('.error-item[has-balloontip-message]').first();

 			// trigger click tab
 			var tabTrigger 		   = itemErrorE005First.closest('.tab-pane').attr('id'); 			
 			$('.nav-tabs-component a[href=#'+tabTrigger+']').trigger('click');

 			// focus item error E005
 			itemErrorE005First.focus();
 		}
 	} catch (e) {
		console.log('setItemErrorE005: ' + e.message)
	}
}

/**
 * refer auto city/country cd/nm
 * 
 * @author : ANS804 - 2018/05/29 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referAutoCityCountry() {
	try {
		// refer city
		if ($('#TXT_city_cd').nextAll('.city_nm').text() != '') {
			// set cd
			$('#TXT_post_city_cd').val($('#TXT_city_cd').val());
			// set nm
			$('#TXT_post_city_cd').nextAll('.city_nm').text($('#TXT_city_cd').nextAll('.city_nm').text());
			// focus out
			$('#TXT_post_city_cd').trigger('focusout');
		}

		// refer coutry
		if ($('#TXT_country_cd').nextAll('.country_nm').text() != '') {
			// set cd
			$('#TXT_post_country_cd').val($('#TXT_country_cd').val());
			// set nm
			$('#TXT_post_country_cd').nextAll('.country_nm').text($('#TXT_country_cd').nextAll('.country_nm').text());
			// focus out
			$('#TXT_post_country_cd').trigger('focusout');
		}
	} catch(e){
		console.log('referAutoCityCountry: '+ e.message);
	}
}

/**
 * disable Client
 * 
 * @author : ANS342 - 2018/05/30 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function disableClient(mode) {
	try {
		if(mode == 'I'){
			$('#TXT_client_cd').attr('disabled', true);

			// label
			$('.supplier-label').removeClass('required');
			$('#TXT_client_cd').removeClass('required');

			$('#TXT_client_cd').parent().addClass('popup-client-search')

			$('.popup-client-search').find('.btn-search').attr('disabled', true);

			parent.$('.popup-client-search').removeClass('popup-client-search');
		} else {
			$('#TXT_client_cd').attr('disabled', false);
			
			// label
			$('.supplier-label').addClass('required');
			$('#TXT_client_cd').addClass('required');

			$('#TXT_client_cd').parent().addClass('popup-client-search')

			$('.popup-client-search').find('.btn-search').attr('disabled', false);

			parent.$('.popup-client-search').removeClass('popup-client-search');
		}
	} catch (e) {
		alert('disableClient: ' + e.message);
	}
}