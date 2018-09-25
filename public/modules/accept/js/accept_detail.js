/**
 * ****************************************************************************
 * ACCEPT
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	Trieunb - ANS806 - trieunb@ans-asia.com
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	ACCEPT
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */
$(document).ready(function () {
 	initCombobox();
	initEvents();

	var date = $('.TXT_rcv_date').val();
	_getTaxRate(date);

	if (mode != 'I' && $(".TXT_rcv_no").val() != '') {
		$(".TXT_rcv_no").trigger("change");
	} else {
		emptyInputAfterRefer();
	}

	if (mode != 'I') {
		$(".TXT_rcv_no").addClass("required");
	} else {
		disableRcvNo();
		$(".TXT_rcv_no").removeClass("required");
		$(".TXT_rcv_no").val('');
	}
	if ((mode == 'I' || mode == 'U') && $(".TXT_rcv_no").val() == '') {
		$('.infor-created .heading-elements').addClass('hidden');
	}
	$(".TXT_sign_cd").val(cre_user_cd);
	$(".DSP_sign_nm").text(cre_user_nm);
});
/**
 * initCombobox
 * @author  :   ANS804 - 2018/01/05 - create
 * @param 	:
 * @return 	:  	null
 * @access 	:  	public
 * @see 	:
 */
function initCombobox() {
	try {
		var name = 'JP';
		//refer data from screen search to detail
		if (mode != 'I' && $(".TXT_rcv_no").val() != '') {
			$(".TXT_rcv_no").trigger("change");
		}
	} catch (e) {
		console.log('initCombobox: ' + e.message);
	}
}
/**
 * changeNmCombobox
 * @author  :   ANS804 - 2018/01/05 - create
 * @param 	:
 * @return 	:  	null
 * @access 	:  	public
 * @see 	:
 */
function changeNmCombobox(name) {
	try {
		_changeNmCombobox(name, 'port_country_div');
		_changeNmCombobox(name, 'port_city_div');
		_changeNmCombobox(name, 'shipment_div');
		_changeNmCombobox(name, 'currency_div');
		_changeNmCombobox(name, 'trade_terms_div');
		_changeNmCombobox(name, 'payment_conditions_div');
		_changeNmCombobox(name, 'unit_q_div');
		_changeNmCombobox(name, 'unit_w_div');
		_changeNmCombobox(name, 'unit_m_div');
		_changeNmCombobox(name, 'sales_detail_div');
		_changeNmCombobox(name, 'bank_div');
	} catch (e) {
		console.log('changeNmCombobox: ' + e.message);
	}
}
/**
 * init Events
 * @author  :   ANS - 2018/01/05 - create
 * @param 	:
 * @return 	:  	null
 * @access 	:  	public
 * @see 	:
 */
function initEvents() {
	try {
		setAttributeCommon();

		//init 1 row table at mode add new (I)
		_initRowTable('table-accept', 'table-row', 1, setClassUnitCombobox);

		//drap and drop row table
		_dragLineTable('table-accept', true, setClassUnitCombobox);

		//set tab index table
		_setTabIndexTable('table-accept');

		//show/hide address to
		$(document).on('click', '#show-address-to', function(){
			$(".address-to").toggleClass("hidden");
			if ($(this).text() == '住所非表示') {
				$(this).text('住所表示');
			} else {
				$(this).text('住所非表示');
			}
		});

		//show/hide address from
		$(document).on('click', '#show-address-from', function() {
			$(".address-from").toggleClass("hidden");
			if ($(this).text() == '住所非表示') {
				$(this).text('住所表示');
			} else {
				$(this).text('住所非表示');
			}
		});

		//remove row table
		$(document).on('click','.remove-row',function(e){
			var obj   = $(this);
			jMessage('C002', function(r) {
				if(r) {
					obj.closest('tr').remove();
					_updateTable('table-accept', true);
					$('#table-accept tbody tr:last :input:first').focus();
					calTotalDetailAmt();
		 			calTotalTaxAmt();
		 			calTotalAmt();
		 			calTotalNetWeight();
		 			calTotalGrossWeight();
		 			calTotalMeasure();
		 			calTotalQty();
				}
			});
		});

		//add row
		$(document).on('click', '#btn-add-row', function () {
			try {
				_addNewRowTable('table-accept', 'table-row', 30, updateTableRcvDetail);
				$('#table-accept tbody tr:last :input:first').focus();
			} catch (e) {
				console.log('btn-add-row: ' + e.message);
			}
		});

		//init back
		$(document).on('click', '#btn-back', function () {
			try {
				sessionStorage.setItem('detail', true);
				location.href = '/accept/accept-search';
			} catch (e) {
				console.log('btn-back: ' + e.message);
			}
		});

		//button save
		$(document).on('click', '#btn-save', function() {
			try {
				if (mode != 'I') {
					var msg = 'C003';
				} else {
					var msg = 'C001';
				}

				if(validate()){
					var _row_detail = $('#table-accept tbody tr').length;
					if(_row_detail > 0) {
						// have not line valid
						if (!checkLineValid()) {
							jMessage('E004', function(r) {
								if (r) {
									validateDetail();
	 							}
							});							
						// have line valid
	 					} else {
	 						if (validateDetail()) {
	 							if (validateErrorNumericDetail()) {
		 							jMessage(msg, function(r) {
										if (r) {
											saveAccept();
										}
									});
		 						}
	 						}
	 					}
					} else {
						jMessage('E004');
					}
				}
			} catch (e) {
				console.log('#btn-save: ' + e.message);
			}
		});

		//button delete
		$(document).on('click', '#btn-delete', function() {
			try {
				if(validateAcceptNo()){
					jMessage('C002', function(r) {
						if (r) {
							deleteAccept();
						}
					});	
				}		   
			} catch (e) {
				console.log('#btn-delete: ' + e.message);
			}
		});

 		//btn approve
 		$(document).on('click', '#btn-approve', function(){
 			try {
 				if(validateAcceptNo()){
					jMessage('C005', function(r) {
						if (r) {
							approveAccept();
						}
					});
				}		   
			} catch (e) {
				console.log('#btn-approve ' + e.message);
			}
 		});

 		//btn cancel approve
 		$(document).on('click', '#btn-cancel-approve', function(){
 			try {
 				if(validateAcceptNo()){
 					jMessage('C006', function(r) {
						if (r) {
							cancelApproveAccept();
						}
					});
				}		   
			} catch (e) {
				console.log('#btn-cancel-approve ' + e.message);
			}
 		});

 		//btn remove
 		$(document).on('click', '#btn-cancel-order', function(){
			if(validateAcceptNo()) {
				jMessage('C008',  function(r) {
					if (r) {
						cancelOrder();
					}
				});
			}
 		});

 		//focus out TXT_unit_price
 		$(document).on('focusout', '.TXT_unit_price', function() {
	   		var val = $(this).val().trim();
	   		if (val !== '') {
	   			$(this).removeClass('warning-item');
	   		}
	   	});

 		//change TXT_rcv_no 
		$(document).on('change', '.TXT_rcv_no', function(e) {
			try {
				var data = {
					rcv_no 		: 	$(this).val(),
					rcv_status 	: 	mode,
					mode 		: 	mode
				};

				if (e.isTrigger) {
					referAcceptDetail(data, function() {
							var lib_val_ctl1 = $('.CMB_trade_terms_div > option:selected').attr('data-ctl5');
					 		var lib_val_ctl2 = $('.CMB_trade_terms_div > option:selected').attr('data-ctl6');
					 		
					 		changeTrade(lib_val_ctl1, lib_val_ctl2);
						});
				} else {
					referAcceptDetail(data, showMessageW001);
				}
			} catch (e) {
				console.log('TXT_rcv_no: ' + e.message);
			}
		});
		//change TXT_rcv_date  
		$(document).on('change', '.TXT_rcv_date  ', function(e) {
			var date = $(this).val();
			_getTaxRate(date, calTotalTaxAmt);
		});
		//change TXT_cust_cd 
		$(document).on('change', '.TXT_cust_cd', function() {
			referSuppliers(true);
		});

		//change TXT_cust_city_div 
		$(document).on('change', '.TXT_cust_city_div', function() {
			var _this    = $(this);
			var city_div =	_this.val();
			_referCity(city_div, _this, $('.TXT_cust_country_div'), function() {
				var country_div =	$('.TXT_cust_country_div').val();
				changeNmCombobox(country_div);
				setItemCustCountryDiv(country_div);
				calTotalTaxAmt();
				calTotalAmt();
				_this.removeClass('error-item');
				checkCountry($('.TXT_cust_country_div').val());
			},true);
		});

		//change TXT_cust_country_div
 		$(document).on('change', '.TXT_cust_country_div', function() {
			var _this    	= $(this);
 			var country_div = $(this).val();
 			_referCountry(country_div, $('.TXT_cust_city_div'), _this, function() {
 				var country_div =	$('.TXT_cust_country_div').val();
 				changeNmCombobox(country_div);
 				setItemCustCountryDiv(country_div);
 				calTotalTaxAmt();
 				calTotalAmt();
				_this.removeClass('error-item');
				checkCountry($('.TXT_cust_country_div').val());
 			},true);
 		});

 		//change TXT_consignee_cd 
		$(document).on('change', '.TXT_consignee_cd ', function() {
			referSuppliers(false);
		});

		//change TXT_consignee_city_div 
		$(document).on('change', '.TXT_consignee_city_div ', function() {
			var _this    = $(this);
			var city_div =	$(this).val();
			_referCity(city_div, _this, $('.TXT_consignee_country_div'), function() {
				_this.removeClass('error-item');
			},true);
		});

 		//change TXT_consignee_country_div
 		$(document).on('change', '.TXT_consignee_country_div', function() {
			var _this 		= $(this);
 			var country_div = $(this).val();
 			_referCountry(country_div, $('.TXT_consignee_city_div'), _this, function() {
 				_this.removeClass('error-item');
 			},true);
 		});

 		//combobox trade terms
 		$(document).on('change', '.CMB_trade_terms_div', function(){
 			var lib_val_ctl1 = $('option:selected', this).attr('data-ctl5');
 			var lib_val_ctl2 = $('option:selected', this).attr('data-ctl6');
 			changeTrade(lib_val_ctl1, lib_val_ctl2);
 		});

 		//change TXT_dest_city_div
 		$(document).on('change', '.TXT_dest_city_div', function() {
			var _this    = $(this);
			var city_div = $(this).val();
 			_referCity(city_div, _this, $('.TXT_dest_country_div'), function() {
 				_this.removeClass('error-item');
 			},true);
 		});

 		//change TXT_dest_country_div
 		$(document).on('change', '.TXT_dest_country_div', function() {
			var _this       = $(this);
			var country_div =	$(this).val();
 			_referCountry(country_div, $('.TXT_dest_city_div'), _this, function() {
 				_this.removeClass('error-item');
 			},true);
 		});

 		//change CMB_currency_div
 		$(document).on('change', '.CMB_currency_div', function() {
 			var currency_div = $(this).val();
 			if (currency_div == 'JPY') {
 				$('#table-accept tbody tr').find('.price').addClass('currency_JPY');
 			} else {
 				$('#table-accept tbody tr').find('.price').removeClass('currency_JPY');
 			}
 		});

 		//change TXT_product_cd
 		$(document).on('change', '.TXT_product_cd', function() {
			var parent    = $(this).parents('#table-accept tbody tr');
			
			parent.addClass('refer-product-pos');
			parent.addClass('cal-refer-pos');
			
			var rcv_date  = $('.TXT_rcv_date').val();
			var client_cd = $('.TXT_cust_cd').val();
 			var data = {
 				'product_cd' 	: 	$(this).val(),
 				'rcv_date'      : 	rcv_date,
 				'client_cd'     : 	client_cd,
 				'country_cd' 	: 	$('.TXT_cust_country_div').val(),
 				'currency_div'  :   $('.CMB_currency_div').val()
 			}
 			referProduct(data, 'pos', $(this));
 		});

 		//change TXT_qty
 		$(document).on('change', '.TXT_qty', function() {
 			var parents = $(this).parents('#table-accept tbody tr');
 			parents.addClass('cal-refer-pos');
 			//cal amount
 			calAmount('pos');
 			//cal net weight
 			calNetWeight('pos');
 			//cal gross weight
 			calGrossWeight('pos');
 			//cal measure
 			calMeasure('pos');

 			calTotalDetailAmt();
 			calTotalTaxAmt();
 			calTotalAmt();
 			calTotalNetWeight();
 			calTotalGrossWeight();
 			calTotalMeasure();

 			calTotalQty();
 			//remover class parent
			parent.$('.cal-refer-pos').removeClass('cal-refer-pos');
			//validate for input numeric in table detail
			validateAmountDetail($(this));
			validateNetWeightDetail($(this));
			validateGrossWeightDetail($(this));
			validateMeasureDetail($(this));
			validateQtyDetail($(this));
 		});

 		//change TXT_unit_price
 		$(document).on('change', '.TXT_unit_price', function() {
 			var parents = $(this).parents('#table-accept tbody tr');
 			parents.addClass('cal-refer-pos');
 			calAmount('pos');
 			//remover class parent
			parent.$('.cal-refer-pos').removeClass('cal-refer-pos');

 			calTotalDetailAmt();
 			calTotalTaxAmt();
 			calTotalAmt();
 			//validate for input numeric in table detail
 			validateAmountDetail($(this));
			validateQtyDetail($(this));
 		});

 		//change TXT_unit_net_weight
 		$(document).on('change', '.TXT_unit_net_weight', function() {
 			var parents = $(this).parents('#table-accept tbody tr');
 			parents.addClass('cal-refer-pos');
 			calNetWeight('pos');
 			//remover class parent
			parent.$('.cal-refer-pos').removeClass('cal-refer-pos');

 			calTotalNetWeight();
 			//validate for input numeric in table detail
			validateNetWeightDetail($(this));
			validateQtyDetail($(this));
 		});

 		//change TXT_unit_gross_weight
 		$(document).on('change', '.TXT_unit_gross_weight', function() {
 			var parents = $(this).parents('#table-accept tbody tr');
 			parents.addClass('cal-refer-pos');
 			calGrossWeight('pos');
 			//remover class parent
			parent.$('.cal-refer-pos').removeClass('cal-refer-pos');

 			calTotalGrossWeight();
 			//validate for input numeric in table detail
			validateGrossWeightDetail($(this));
			validateQtyDetail($(this));
 		});

 		//change TXT_unit_measure_qty
 		$(document).on('change', '.TXT_unit_measure_qty', function() {
 			var parents = $(this).parents('#table-accept tbody tr');
 			parents.addClass('cal-refer-pos');
 			calMeasure('pos');
 			//remover class parent
			parent.$('.cal-refer-pos').removeClass('cal-refer-pos');

 			calTotalMeasure();
 			//validate for input numeric in table detail
			validateMeasureDetail($(this));
			validateQtyDetail($(this));
 		});
 		
 		//change CMB_unit_net_weight_div
 		$(document).on('change', '.unit_net_weight_div', function() {
 			var unit_nm 		= $(this).find('option:selected').text();
 			var unit_net_weight = $(this).find('option:selected').val();
 			$('.DSP_unit_total_gross_weight_nm').text(unit_nm);
 			$('.DSP_unit_total_gross_weight_div').text(unit_net_weight);
 			$('.DSP_unit_total_net_weight_nm').text(unit_nm);
 			$('.DSP_unit_total_net_weight_div').text(unit_net_weight);
 		});

 		//change CMB_unit_measure_price
 		$(document).on('change', '.unit_measure_price', function() {
 			var unit_measure_nm = $(this).find('option:selected').text();
 			var unit_measure 	= $(this).find('option:selected').val();
 			$(document).find('.DSP_unit_total_measure_nm').text(unit_measure_nm);
 			$(document).find('.DSP_unit_total_measure_div').text(unit_measure);
 		});

 		//change TXT_freigt_amt and TXT_insurance_amt
 		$(document).on('change', '.TXT_freigt_amt, .TXT_insurance_amt', function() {
 			calTotalAmt();
 		});

 		//change TXT_sign_cd
 		$(document).on('change', '.TXT_sign_cd', function() {
 			var user_cd = $(this).val();
 			_referUser(user_cd, $(this), '', true);
 		});
 		$(document).on('click','.btn-clear-info', function(e){
	    	try {
	    		$('.TXT_consignee_cd').val('');
	    		$('.TXT_consignee_nm').val('');
	    		$('.address-from').find(':input').val('');
	    		$('.address-from').find('.DSP_consignee_city_nm').text('');
	    		$('.address-from').find('.DSP_consignee_country_nm').text('');
	    		$('.TXT_consignee_cd').focus();
	    	} catch(e) {
	    		console.log('btn-clear-info: '+e.message);
	    	}
	    })
	} catch (e) {
		console.log('initEvents: ' + e.message);
	}
}
/**
 * validate
 *
 * @author		:	ANS804 - 2018/01/05 - create
 * @params		:	null
 * @return		:	null
 */
function validate(){
	try {
		var element = $('body');
		var error   = 0;

		_clearErrors();

		element.find('.required:not([readonly])').each(function() {
			if(($(this).is("input") || $(this).is("textarea")) &&  $.trim($(this).val()) == '' ) {
				$(this).errorStyle(_MSG_E001);
				error ++;
			} else if( $(this).is("select") &&  ($(this).val() == '' || $(this).val() == undefined) ) {
				$(this).errorStyle(_MSG_E001);
				error ++;
			} else if($(this).is("input[type=checkbox]") && !$(this).is(":checked")){
                $(this).errorStyle(_MSG_E001);
                error ++;
            }
		});

		element.find('input.email:enabled:not([readonly])').each(function(){
			if(!_validateEmail($(this).val())){
				$(this).errorStyle(_text['E015']);
				error++;
			}
		});

		element.find('input.fax:enabled:not([readonly])').each(function(){
		    if(!_validatePhoneFaxNumber($(this).val())){
		        $(this).errorStyle(_text['E015']);
		        error++;
		    }
		});

		if ($('.address-to').hasClass('hidden') && $('.address-to').find('.error-item').length > 0) {
			$('#show-address-to').trigger('click');
		}

		if ($('.address-from').hasClass('hidden') && $('.address-from').find('.error-item').length > 0) {
			$('#show-address-from').trigger('click');
		}

		$(document).find('.error-item:first').focus();

		if ( error > 0 ) {
			return false;
		} else {
			return true;
		}
	} catch (e) {
		console.log('validate: ' + e.message);
	}
}
/**
 * add new row table
 *
 * @author		:	ANS804 - 2018/01/05 - create
 * @params		:	null
 * @return		:	null
 */
function addNewRowTable(table) {
	try	{
		var row = $("#table-row tr").clone();
		var col_index =  $('.'+ table + ' tbody tr').length;
		if (col_index < 30) {
			$('.'+ table + ' tbody').append(row);
		}		
		_updateTable(table, true);
		//set first forcus input in row
		$('.'+ table + ' tbody tr:last :input:first').focus();
	} catch (e) {
		console.log('addNewRowTable: ' + e.message);
	}
}
/**
 * set Class Unit Combobox
 *
 * @author		:	ANS804 - 2018/01/05 - create
 * @params		:	null
 * @return		:	null
 */
function setClassUnitCombobox() {
	try {
		//remove class
		$('#table-accept tbody tr').find('.CMB_unit_net_weight_div').removeClass('unit_net_weight_div');
		$('#table-accept tbody tr').find('.CMB_unit_measure_price').removeClass('unit_measure_price');
		//add class
		$('#table-accept tbody tr:first').find('.CMB_unit_net_weight_div').addClass('unit_net_weight_div');
		$('#table-accept tbody tr:first').find('.CMB_unit_measure_price').addClass('unit_measure_price');
		calTotalGrossWeight();
		calTotalNetWeight();
		calTotalMeasure();
	} catch (e)  {
        console.log('setClassUnitCombobox:  ' + e.message);
    }
}
/**
 * total detail amt
 * 
 * @author : ANS804 - 2018/01/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalDetailAmt() {
	try {
		var _total_amount = 0;
		$('#table-accept tbody tr').each(function() {
			var amount = $(this).find('.TXT_amount').val();
			amount = amount.replace(/,/g, '');
			if (amount != '') {
				_total_amount = _roundNumeric(parseFloat(_total_amount) +  parseFloat(amount), 2, 2);
			}
		});
		$('.DSP_total_detail_amt').text(_convertMoneyToIntAndContra(_total_amount));
	} catch(e) {
        console.log('calTotalAmt: ' + e.message)
    }
}
/**
 * total tax amt
 * 
 * @author : ANS804 - 2018/01/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalTaxAmt() {
 	try {
 		var country_div 	=	$('.TXT_cust_country_div').val();
 		_addTaxRate(country_div);
 		if (country_div == 'JP') {
 			$('.DSP_tax_amt').text($('.tax_rate').text());
 			var _total_tax_amt 		= 0;
	 		var _tax_rate 			= 0;
				_tax_rate 			= parseFloat($('.DSP_tax_amt').text().replace(/,/g, ''));
				_tax_rate   		= !isNaN(_tax_rate) ? _tax_rate : 0;

	 		var _total_detail_amt 	= 0;
				_total_detail_amt 	= parseFloat($('.DSP_total_detail_amt').text().replace(/,/g, ''));
				_total_detail_amt   = !isNaN(_total_detail_amt) ? _total_detail_amt : 1;
			var _total_tax_rate 	= 0;

			$('#table-accept tbody tr').each(function() {
				var amount = $(this).find('.TXT_amount').val();
				amount = amount.replace(/,/g, '');
				if (amount != '') {
					_total_tax_rate = _roundNumeric(parseFloat(_tax_rate) *  parseFloat(amount), 2, 2);
					_total_tax_amt  = _roundNumeric(_total_tax_rate + _total_tax_amt, 2, 2);
				}
			});
			
			_total_tax_amt = _roundNumeric(_total_tax_amt, _constVal1['sales_tax_round_div']);

			$('.DSP_tax_amt').text(_convertMoneyToIntAndContra(_total_tax_amt));
 		} else {
 			$('.DSP_tax_amt').addClass('hidden');
 			$('.DSP_tax_amt').text('');
 		}
 		
 	} catch(e) {
        console.log('calTotalTaxAmt: ' + e.message)
    }
}
/**
 * calTotalAmt
 * 
 * @author : ANS804 - 2018/01/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalAmt() {
	try {
		var _total_amt 			= 0;
		var _total_detail_amt 	= 0;
			_total_detail_amt 	= parseFloat($('.DSP_total_detail_amt').text().replace(/,/g, ''));
			_total_detail_amt   = !isNaN(_total_detail_amt) ? _total_detail_amt : 0;
		var _freigt_amt 		= 0;

		if (!$('.TXT_freigt_amt').hasClass('hidden')) {
			_freigt_amt = parseFloat($('.TXT_freigt_amt').val().replace(/,/g, ''));
			_freigt_amt = !isNaN(_freigt_amt) ? _freigt_amt : 0;
		}

		var _insurance_amt 	= 0;
		if (!$('.TXT_insurance_amt').hasClass('hidden')) {
			_insurance_amt = parseFloat($('.TXT_insurance_amt').val().replace(/,/g, ''));
			_insurance_amt = !isNaN(_insurance_amt) ? _insurance_amt : 0;
		}

		var _tax_amt 	= 0;
		if (!$('.DSP_tax_amt').hasClass('hidden')) {
			_tax_amt = parseFloat($('.DSP_tax_amt').text().replace(/,/g, ''));
			_tax_amt = !isNaN(_tax_amt) ? _tax_amt : 0;
		}

		_total_amt = _roundNumeric(_total_detail_amt + _freigt_amt + _insurance_amt + _tax_amt, 2, 2);
		$('.DSP_total_amt').text(_convertMoneyToIntAndContra(_total_amt));
	} catch(e) {
        console.log('calTotalAmt: ' + e.message)
    }
}
/**
 * total net weight
 * 
 * @author : ANS804 - 2018/01/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalNetWeight() {
	try {
		var _total_net_weight = 0;
		$('#table-accept tbody tr').each(function() {
			var net_weight = $(this).find('.TXT_net_weight').val();
			net_weight = net_weight.replace(/,/g, '');
			if (net_weight != '') {
				_total_net_weight = _roundNumeric(parseFloat(_total_net_weight) +  parseFloat(net_weight), 2, 2);
			}
		});
		$('.DSP_total_net_weight').text(_convertMoneyToIntAndContra(_total_net_weight));
		var unit_total_net_weight_nm = $('#table-accept tbody tr:first').find('.unit_net_weight_div option:selected').text();
		var unit_total_net_weight_div = $('#table-accept tbody tr:first').find('.unit_net_weight_div option:selected').val();
		$('.DSP_unit_total_net_weight_nm').text(unit_total_net_weight_nm);
		$('.DSP_unit_total_net_weight_div').text(unit_total_net_weight_div);
	} catch(e) {
        console.log('calTotalNetWeight:' + e.message)
    }
}
/**
 * total gross weight
 * 
 * @author : ANS804 - 2018/01/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalGrossWeight() {
	try {
		var _total_gross_weight = 0;
		$('#table-accept tbody tr').each(function() {
			var gross_weight = $(this).find('.TXT_gross_weight').val();
			gross_weight = gross_weight.replace(/,/g, '');
			if (gross_weight != '') {
				_total_gross_weight = _roundNumeric(parseFloat(_total_gross_weight) +  parseFloat(gross_weight), 2, 2);
			}
		});
		$('.DSP_total_gross_weight').text(_convertMoneyToIntAndContra(_total_gross_weight));
		var unit_total_gross_weight_nm = $('#table-accept tbody tr:first').find('.unit_net_weight_div option:selected').text();
		var unit_total_gross_weight_div = $('#table-accept tbody tr:first').find('.unit_net_weight_div option:selected').val();
		$('.DSP_unit_total_gross_weight_nm').text(unit_total_gross_weight_nm);
		$('.DSP_unit_total_gross_weight_div').text(unit_total_gross_weight_div);
	} catch(e) {
        console.log('calTotalGrossWeight: ' + e.message)
    }
}
/**
 * total measure
 * 
 * @author : ANS804 - 2018/01/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalMeasure() {
	try {
		var _total_measure = 0;
		$('#table-accept tbody tr').each(function() {
			var measure = $(this).find('.TXT_measure').val();
			measure = measure.replace(/,/g, '');
			if (measure != '') {
				_total_measure = _roundNumeric(parseFloat(_total_measure) +  parseFloat(measure), 2, 2);
			}
		});
		$('.DSP_total_measure').text(_convertMoneyToIntAndContra(_total_measure));
		var unit_total_measure_nm = $('#table-accept tbody tr:first').find('.unit_measure_price option:selected').text();
		var unit_total_measure_div = $('#table-accept tbody tr:first').find('.unit_measure_price option:selected').val();
		$('.DSP_unit_total_measure_nm').text(unit_total_measure_nm);
		$('.DSP_unit_total_measure_div').text(unit_total_measure_div);
	} catch(e) {
        console.log('calTotalMeasure: ' + e.message)
    }
}
/**
 * total qty
 * 
 * @author : ANS804 - 2018/01/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalQty() {
	try {
		var _total_qty = 0;
		$('#table-accept tbody tr').each(function() {
			var qty = $(this).find('.TXT_qty').val();
			qty = qty.replace(/,/g, '');
			if (qty != '') {
				_total_qty = _roundNumeric(parseFloat(_total_qty) +  parseFloat(qty), 2, 2);
			}
		});
		$('.DSP_total_qty').text(_convertMoneyToIntAndContra(_total_qty));
	} catch(e) {
        console.log('calTotalQty: ' + e.message)
    }
}
/**
 * refer accept infomation
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referAcceptDetail(data, callback) {
	try	{
		$.ajax({
			type 		: 'GET',
			url 		: '/accept/refer-accept-detail',
			dataType	: 'json',
			data 		: data,
			success: function(res) {
				if (res.response) {
					$('.heading-btn-group').html(res.button);
					$('#div-table-accept').html(res.html_rcv_d);

					// set item
					setItemRcvH(res.rcv_h);

					// get and set tax date
					var date = $('.TXT_rcv_date').val();
					_getTaxRate(date);
					
					var name =	$('.TXT_cust_country_div').val();
					setSelectCombobox();
					changeNmCombobox(name);

					_setTabIndex();
					
					_setTabIndexTable('table-accept');

					//drap and drop row table
					if(res.rcv_status !== '20') {
						_dragLineTable('table-accept', true, setClassUnitCombobox);
					}

					var param = {
						'mode'		: mode,
						'from'		: 'AcceptDetail',
						'rcv_no'	: data.rcv_no,
					};

					if (from == 'AcceptSearch') {
						_postParamToLink('AcceptSearch', 'AcceptDetail', '', param)
					} else {
						_postParamToLink('AcceptDetail', 'AcceptDetail', '', param)
					}					

					_clearErrors();
					$('.infor-created .heading-elements').removeClass('hidden');
				} else {
					jMessage('W001', function(r) {
						if (r) {
							emptyInputAfterRefer();
						}
					});
				}
				if (typeof callback == 'function') {
					callback();
				}
				disableInputByMode(res.status);
			}
		});
	} catch (e) {
		console.log('referAcceptDetail: ' + e.message);
	}
}
/**
 * set Item RcvH
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setItemRcvH(data) {
	try {
		if (mode == 'I') {
			$('.TXT_rcv_no').val('');
			$('.TXT_rcv_no').attr('disabled', true);
			$('.TXT_rcv_no').parent().addClass('popup-rcv-search');

			$('.popup-rcv-search').find('.btn-search').attr('disabled', true);
			parent.$('.popup-rcv-search').removeClass('popup-rcv-search');
			$(".TXT_rcv_no").removeClass("required");
		} else {
			$('.TXT_rcv_no').attr('disabled', false);
			$('.TXT_rcv_no').parent().addClass('popup-rcv-search');

			$('.popup-rcv-search').find('.btn-search').attr('disabled', false);
			parent.$('.popup-rcv-search').removeClass('popup-rcv-search');
			$(".TXT_rcv_no").addClass("required");
		}

		$('.TXT_rcv_no').val(data.rcv_no);

		// Common
		$('#DSP_cre_user_cd').text(data.cre_user_cd +' '+ data.cre_user_nm);
		$('#DSP_cre_datetime').text(data.cre_datetime);
		$('#DSP_upd_user_cd').text(data.upd_user_cd +' '+ data.upd_user_nm);
		$('#DSP_upd_datetime').text(data.upd_datetime);

		
		$('.TXT_rcv_date').val(data.rcv_date);
		$('.DSP_status').text(data.rcv_status_nm);
		$('.TXT_rcv_status').val(data.rcv_status_div);
		$('.DSP_rcv_status_cre_datetime').text(data.rcv_status_cre_datetime);

		//cust
		$('.TXT_cust_cd').val(data.cust_cd);
		$('.TXT_cust_nm').val(data.cust_nm);
		$('.TXT_cust_adr1').val(data.cust_adr1);
		$('.TXT_cust_adr2').val(data.cust_adr2);
		$('.TXT_cust_zip').val(data.cust_zip);
		$('.TXT_cust_city_div').val(data.cust_city_div);
		$('.DSP_cust_city_nm').text(data.cust_city_nm);
		$('.TXT_cust_country_div').val(data.cust_country_div);
		_addTaxRate(data.cust_country_div);
		$('.DSP_cust_country_nm').text(data.cust_country_nm);
		$('.TXT_cust_tel').val(data.cust_tel);
		$('.TXT_cust_fax').val(data.cust_fax);
		$('.TXT_cust_fax').val(data.cust_fax);

		//consignee
		$('.TXT_consignee_cd').val(data.consignee_cd);
		$('.TXT_consignee_nm').val(data.consignee_nm);
		$('.TXT_consignee_adr1').val(data.consignee_adr1);
		$('.TXT_consignee_adr2').val(data.consignee_adr2);
		$('.TXT_consignee_zip').val(data.consignee_zip);
		$('.TXT_consignee_city_div').val(data.consignee_city_div);
		$('.DSP_consignee_city_nm').text(data.consignee_city_nm);
		$('.TXT_consignee_country_div').val(data.consignee_country_div);
		$('.DSP_consignee_country_nm').text(data.consignee_country_nm);
		$('.TXT_consignee_tel').val(data.consignee_tel);
		$('.TXT_consignee_fax').val(data.consignee_fax);
		$('.TXT_consignee_fax').val(data.consignee_fax);

		$('.TXT_shipping_mark_1').val(data.mark1);
		$('.TXT_shipping_mark_2').val(data.mark2);
		$('.TXT_shipping_mark_3').val(data.mark3);
		$('.TXT_shipping_mark_4').val(data.mark4);
		$('.TXT_packing').val(data.packing);

		if (data.shipment_div != '') {
			$('.CMB_shipment_div option[value='+data.shipment_div+']').prop('selected', true);
		} else {
			$('.CMB_shipment_div option:first').prop('selected', true);
		}
		if (data.currency_div != '') {
			$('.CMB_currency_div option[value='+data.currency_div+']').prop('selected', true);
		} else {
			$('.CMB_currency_div option:first').prop('selected', true);
		}
		if (data.port_city_div != '') {
			$('.CMB_port_city_div option[value='+data.port_city_div+']').prop('selected', true);
		} else {
			$('.CMB_port_city_div option:first').prop('selected', true);
		}
		if (data.port_country_div != '') {
			$('.CMB_port_country_div option[value='+data.port_country_div+']').prop('selected', true);
		} else {
			$('.CMB_port_country_div option:first').prop('selected', true);
		}
		if (data.trade_terms_div != '') {
			$('.CMB_trade_terms_div option[value='+data.trade_terms_div+']').prop('selected', true);
			$('.CMB_trade_terms_div').trigger('change');
		} else {
			$('.CMB_trade_terms_div option:first').prop('selected', true);
		}
		
		$('.TXT_dest_city_div').val(data.dest_city_div);
		$('.DSP_dest_city_nm').text(data.dest_city_nm);
		$('.TXT_dest_country_div').val(data.dest_country_div);
		$('.DSP_dest_country_nm').text(data.dest_country_nm);
		
		$('.TXT_payment_notes').val(data.payment_notes);
		$('.DSP_currency_div').text(data.currency_div);
		$('.TXT_our_freight_amt').val(data.our_freight_amt);

		// detail sum of amount
		$('.DSP_total_qty').text(data.total_qty.replace(/\.00$/,''));

		$('.DSP_total_gross_weight').text(data.total_gross_weight.replace(/\.00$/,''));

		$('.DSP_unit_total_gross_weight_nm').text(data.unit_total_gross_weight_nm);
		$('.DSP_unit_total_gross_weight_div').text(data.unit_total_gross_weight_div);

		$('.DSP_total_net_weight').text(data.total_net_weight.replace(/\.00$/,''));
		$('.DSP_unit_total_net_weight_nm').text(data.unit_total_gross_weight_nm);
		$('.DSP_unit_total_net_weight_div').text(data.unit_total_net_weight_div);

		$('.DSP_total_measure').text(data.total_measure.replace(/\.00$/,''));
		$('.DSP_unit_total_measure_nm').text(data.unit_total_measure_nm);
		$('.DSP_unit_total_measure_div').text(data.unit_total_measure_div);

		// sum of money
		$('.DSP_total_detail_amt').text(data.total_detail_amt.replace(/\.00$/,''));
		$('.TXT_freigt_amt').val(data.freigt_amt.replace(/\.00$/,''));
		$('.TXT_insurance_amt').val(data.insurance_amt.replace(/\.00$/,''));
		$('.TXT_freigt_amt').val(data.freigt_amt.replace(/\.00$/,''));
		$('.DSP_tax_amt').text(data.total_detail_tax.replace(/\.00$/,''));
		$('.DSP_total_amt').text(data.total_amt.replace(/\.00$/,''));
		
		$('.TXT_country_of_origin').val(data.country_of_origin);
		$('.TXT_manufacture').val(data.manufacture);
		$('.TXT_sign_cd').val(data.sign_user_cd);
		$('.DSP_sign_nm').text(data.sign_user_nm);
		$('.TXA_inside_remarks').val(data.inside_remarks);
		//
		var currency_div = $('.currency_div').find('option:selected').val();

		if (currency_div == 'JPY') {
			$('#table-accept tbody tr').find('.price').addClass('currency_JPY');
		} else {
			$('#table-accept tbody tr').find('.price').removeClass('currency_JPY');
		}
	} catch (e) {
		console.log('setItemRcvH: ' + e.message);
	}
}
/**
 * set maxlength common
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setAttributeCommon() {
	try {
	 	//set maxlength of input common
	 	var maxlength = {
	 		'7' 	: ['quantity', 'measure', 'month'],
	 		'9' 	: ['weight'],
	 		'10' 	: ['datepicker'],
	 		'12' 	: ['money', 'price'],
	 		'120'	: ['client_nm', 'client_adr1', 'client_adr2', 'item_nm_j', 'item_nm_e'],
	 		'200' 	: ['remarks', 'memo']
	 	};

	 	for (var keyObj in maxlength) {
	 		var obj = maxlength[keyObj];
	 		if (obj.length > 0) {
	 			for (var key in obj) {
		 			$("."+obj[key]).attr("maxlength", keyObj);
		 		}
	 		}
	 	}
 	} catch(e) {
        console.log('setAttributeCommon: ' + e.message)
    }
}
/**
 * change Trade
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function changeTrade(lib_val_ctl1, lib_val_ctl2) {
	try {
		_addFreigtAndInsurance(lib_val_ctl1, lib_val_ctl2);
		calTotalAmt();
	} catch (e) {
		console.log('changeTrade: ' + e.message);
	}
}
/**
 * show message W001
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function showMessageW001() {
	try {
		if (mode == 'U' && $('.TXT_rcv_no').val() == '') {
			jMessage('W001');
		}
	} catch (e) {
		console.log('showMessageW001: ' + e.message);
	}
}
/**
 * set Select Combobox
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setSelectCombobox() {
	try {
		$('#table-accept tbody tr').each(function() {
			var _sales_detail_div =	$(this).find('.CMB_sales_detail_div').attr('data-selected');
			if (_sales_detail_div != '') {
				$(this).find('.CMB_sales_detail_div option[value='+_sales_detail_div+']').prop('selected', true);
			} else {
				$(this).find('.CMB_sales_detail_div option:first').prop('selected', true);
			}

			var _unit_of_m_div = $(this).find('.CMB_unit_of_m_div').attr('data-selected');
			if (_unit_of_m_div != '') {
				$(this).find('.CMB_unit_of_m_div option[value='+_unit_of_m_div+']').prop('selected', true);
			} else {
				$(this).find('.CMB_unit_of_m_div option:first').prop('selected', true);
			}

			var _unit_net_weight_div = $(this).find('.CMB_unit_net_weight_div').attr('data-selected');
			if (_unit_net_weight_div != '') {
				$(this).find('.CMB_unit_net_weight_div option[value='+_unit_net_weight_div+']').prop('selected', true);
			} else {
				$(this).find('.CMB_unit_net_weight_div option:first').prop('selected', true);
			}
			
			var _unit_measure_price = $(this).find('.CMB_unit_measure_price').attr('data-selected');
			if (_unit_measure_price != '') {
				$(this).find('.CMB_unit_measure_price option[value='+_unit_measure_price+']').prop('selected', true);
			} else {
				$(this).find('.CMB_unit_measure_price option:first').prop('selected', true);
			}
		});
	} catch (e)  {
        console.log('setSelectCombobox: ' + e.message);
    }
}
/**
 * disabled input flow mode
 *
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function disableInputByMode(mode_status) {
	try {
		if (mode_status == 'A' || mode_status == 'L') {
			_disabldedAllInput();
			$('input[type=file]').attr('disabled', false);
			$('.remarks').attr('disabled', false);
			$('#show-address-to').attr('disabled', false);
			$('#show-address-from').attr('disabled', false);
			$('.ui-datepicker-trigger').on('click', function(e) {
				$('#ui-datepicker-div').css('display', 'none');
			})
		} else {
			$(":input:not(.TXT_amount):not(.TXT_net_weight):not(.TXT_gross_weight):not(.TXT_measure)").each(function (i) { 
				$(this).prop('disabled', false);
			});
			$('.ui-datepicker-trigger').on('click', function(e) {
				$('#ui-datepicker-div').css('display', 'block');
			});
		}
		if (mode == 'I') {
			disableRcvNo();
		}
	} catch (e)  {
        console.log('disableInputByMode: ' + e.message);
    }
}
/**
 * disabled received
 *
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function disableRcvNo() {
	try {
		$('.TXT_rcv_no').attr('disabled', true);
		$('.TXT_rcv_no').parent().addClass('popup-rcv-search')
		$('.popup-rcv-search').find('.btn-search').attr('disabled', true);
		parent.$('.popup-rcv-search').removeClass('popup-rcv-search');
	} catch (e) {
		console.log('disableRcvNo: ' + e.message);
	}
}
/**
 * refer cust m client
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : nullssss
 * @access : public
 * @see :
 */
function referSuppliers(flag) {
	try	{
		var cust_cd 	=	'';

		if (flag) {
			cust_cd 	=	$('.TXT_cust_cd').val();
		} else {
			cust_cd 	=	$('.TXT_consignee_cd').val();
		}

		var data = {
				cust_cd 	: 	cust_cd,
				cust_div 	:  	'1'
		};

		$.ajax({
			type 		: 'GET',
			url 		: '/accept/refer-suppliers',
			dataType	: 'json',
			data 		: data,
			success: function(res) {
				var data = {};
				if (res.response) {
					data 	=	res.data;
				}
				setItemReferSuppliers(data, flag);
			}
		});
	} catch (e) {
		console.log('referSuppliers: ' + e.message);
	}
}
/**
 * set item refer suppliers Cust and Consignee
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setItemReferSuppliers(data, flag) {
	try	{
		if (flag) {
			if (jQuery.isEmptyObject(data)) {// check data empty
				$('.TXT_cust_nm').val('');
			} else {
				// <得意先>
				$('.TXT_cust_nm').val(data.client_nm);
				$('.TXT_cust_adr1').val(data.client_adr1);
				$('.TXT_cust_adr2').val(data.client_adr2);
				$('.TXT_cust_zip').val(data.client_zip);
				$('.TXT_cust_city_div').val(data.client_city_div);
				$('.DSP_cust_city_nm').text(data.client_city_nm);
				$('.TXT_cust_country_div').val(data.client_country_div);
				$('.DSP_cust_country_nm').text(data.client_country_nm);
				$('.TXT_cust_tel').val(data.client_tel);
				$('.TXT_cust_fax').val(data.client_fax);
				// <Consignee>
				$('.TXT_consignee_cd').val('');
				$('.TXT_consignee_nm').val(data.consignee_nm);
				$('.TXT_consignee_adr1').val(data.consignee_adr1);
				$('.TXT_consignee_adr2').val(data.consignee_adr2);
				$('.TXT_consignee_zip').val(data.consignee_zip);
				$('.TXT_consignee_city_div').val(data.consignee_city_div);
				$('.DSP_consignee_city_nm').text(data.consignee_city_nm);
				$('.TXT_consignee_country_div').val(data.consignee_country_div);
				$('.DSP_consignee_country_nm').text(data.consignee_country_nm);
				$('.TXT_consignee_tel').val(data.consignee_tel);
				$('.TXT_consignee_fax').val(data.consignee_fax);
				// <他>
				$('.TXT_shipping_mark_1').val(data.mark1);
				$('.TXT_shipping_mark_2').val(data.mark2);
				$('.TXT_shipping_mark_3').val(data.mark3);
				$('.TXT_shipping_mark_4').val(data.mark4);

				$('.TXT_dest_city_div').val(data.consignee_city_div);
				$('.DSP_dest_city_nm').text(!jQuery.isEmptyObject(data) ? data.consignee_city_nm : '');
				$('.TXT_dest_country_div').val(data.consignee_country_div);
				$('.DSP_dest_country_nm').text(!jQuery.isEmptyObject(data) ? data.consignee_country_nm : '');
				// $('.TXT_payment_notes').val(data.payment_conditions_j);
				$('.TXT_payment_notes').attr('payment_conditions_j',  data.payment_conditions_j);
				$('.TXT_payment_notes').attr('payment_conditions_e',  data.payment_conditions_e);
				checkCountry (data.consignee_country_div);
				/*
				$('.TXT_dest_city_div').val(data.delivery_city_div);
				$('.DSP_dest_city_nm').text(data.deliverye_city_nm);
				$('.TXT_dest_country_div').val(data.delivery_country_div);
				$('.DSP_dest_country_nm').text(data.deliverye_country_nm);*/

 				$('.CMB_currency_div option:first').prop('selected', true);
				if (data.sales_currency_div != '') {
					$('.CMB_currency_div option[value='+data.sales_currency_div+']').prop('selected', true);
				}
			}

 			changeNmCombobox(data.client_country_div);
 			calTotalTaxAmt();
 			calTotalAmt();
 			setItemCustCountryDiv(data.client_country_div);
		} else {
			if (jQuery.isEmptyObject(data)) {// check data empty
				$('.TXT_consignee_nm').val('');
			} else {
				// <Consignee>
				$('.TXT_consignee_nm').val(data.client_nm);
				$('.TXT_consignee_adr1').val(data.client_adr1);
				$('.TXT_consignee_adr2').val(data.client_adr2);
				$('.TXT_consignee_zip').val(data.client_zip);
				$('.TXT_consignee_city_div').val(data.client_city_div);
				$('.DSP_consignee_city_nm').text(data.client_city_nm);
				$('.TXT_consignee_country_div').val(data.client_country_div);
				$('.DSP_consignee_country_nm').text(data.client_country_nm);
				$('.TXT_consignee_tel').val(data.client_tel);
				$('.TXT_consignee_fax').val(data.client_fax);
			}
		}
		_clearValidateMsg();
	} catch (e) {
		console.log('setItemReferSuppliers: ' + e.message);
	}
}
/**
 * set Item When change Cust Country Div
 *
 * @author      :   ANS342 - 2018/06/26 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   saveFunction
 */
function checkCountry (country_div){
	if (country_div == 'JP') {
		$('.TXT_payment_notes').val($('.TXT_payment_notes').attr('payment_conditions_j'));
	} else {
		$('.TXT_payment_notes').val($('.TXT_payment_notes').attr('payment_conditions_e'));
	}
}
/**
 * set Item When change Cust Country Div
 *
 * @author      :   ANS804 - 2018/01/08 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   saveFunction
 */
function setItemCustCountryDiv(country_div) {
	try {
		if (country_div == 'JP' || country_div =='jp' || country_div =='jP' || country_div =='Jp') {
			$('.TXT_packing').val(_constVal1['pi_packing']);
			$('.TXT_country_of_origin').val(_constVal1['pi_country_of_origin']);
			$('.TXT_manufacture').val(_constVal1['pi_manufacture']);
		} else {
			$('.TXT_packing').val(_constVal2['pi_packing']);
			$('.TXT_country_of_origin').val(_constVal2['pi_country_of_origin']);
			$('.TXT_manufacture').val(_constVal2['pi_manufacture']);
		}
	} catch (e)  {
        console.log('setItemCustCountryDiv:  ' + e.message);
    }
}
/**
 * check Line Valid
 *
 * @author      :   ANS804 - 2018/01/08 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function checkLineValid() {
    try {
        var detail  = $('#table-accept tbody tr');
        var exists  = 0;

        detail.each(function() {
            var CMB_sales_detail_div = $(this).find('.CMB_sales_detail_div').val();
            var TXT_product_cd       = $(this).find('.TXT_product_cd').val();
            var TXT_description      = $(this).find('.TXT_description ').val();
            if(!!CMB_sales_detail_div && !!TXT_product_cd && !!TXT_description){
                exists++;
            }
        });

        if ( exists > 0 ) {
            return true;
        } else {
            return false;
        }
    } catch (e) {
        console.log('checkLineValid: ' + e.message);
    }
}
/**
 * validate detail
 *
 * @author      :   ANS804 - 2018/01/08 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function validateDetail() {
	try {
		var detail 	= $('#table-accept tbody tr');
		var error 	= 0;
		
		// $('.TXT_product_cd').removeClass('warning-item');

		detail.find('.required_detail:enabled:not([readonly])').each(function() {
			if ($(this).is(':visible')) {
				if(($(this).is("input") || $(this).is("textarea")) &&  $.trim($(this).val()) == '' ) {
					$(this).errorStyle(_MSG_E001);
					error ++;
				} else if ($(this).is("select") &&  ($(this).val() == '' || $(this).val() == undefined)) {
					$(this).errorStyle(_MSG_E001);
					error ++;
				} else if ($(this).is("input[type=checkbox]") && !$(this).is(":checked")){
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
		console.log('validateDetail: ' + e.message);
	}
}
/**
 * validate Error Numeric Detail
 *
 * @author      :   ANS804 - 2018/01/08 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function validateErrorNumericDetail() {
	try {
		_clearErrors();
		var detail 	= $('#table-accept tbody tr');
		var error 	= 0;

		if (detail.find('.error-numeric').length > 0) {
			error ++;
		}

		detail.find('.error-numeric:first').focus();

		if( error > 0 ) {
			return false;
		} else {
			return true;
		}
	} catch (e) {
		console.log('validateErrorNumericDetail: ' + e.message);
	}
}
/**
 * save Accept
 *
 * @author      :   ANS804 - 2018/01/08 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function saveAccept() {
	try{
	    var data = getData();
	    $.ajax({
	        type        :   'POST',
	        url         :   '/accept/accept-detail/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd, function(r){
		        			if(r){
		            			getErrorItem(res.error_list,res.detail_error_list);
		            		}
	        			});
	            	} else {
	            		var msg = (mode == 'I') ? 'I001' : 'I003';
	            		jMessage(msg, function(r){
		                	if(r){
		                		mode = 'U';
		                		var data = {
		                			rcv_no 		: res.rcv_no,
		                			rcv_status 	: res.rcv_status,
		                			mode 		: mode,
		                		};
		                		referAcceptDetail(data);
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
        console.log('saveAccept: ' + e.message)
    }
}
/**
 * get data of input
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getData() {
	try {
		var _data = [];
		$('#table-accept tbody tr').each(function() {
			var _rcv_detail_no 		= ($(this).find('.DSP_rcv_detail_no').text() == "") ? 0 : $(this).find('.DSP_rcv_detail_no').text();
			var _qty 				= ($(this).find('.TXT_qty').val() == "") ? 0 : $(this).find('.TXT_qty').val().replace(/,/g, '');
			var _unit_price 		= ($(this).find('.TXT_unit_price ').val() == "") ? 0 : $(this).find('.TXT_unit_price').val().replace(/,/g, '');
			var _amount 			= ($(this).find('.TXT_amount').val() == "") ? 0 : $(this).find('.TXT_amount').val().replace(/,/g, '');
			var _unit_measure_qty	= ($(this).find('.TXT_unit_measure_qty').val() == "") ? 0 : $(this).find('.TXT_unit_measure_qty').val().replace(/,/g, '');
			var _unit_net_weight 	= ($(this).find('.TXT_unit_net_weight').val() == "") ? 0 : $(this).find('.TXT_unit_net_weight').val().replace(/,/g, '');
			var _net_weight 		= ($(this).find('.TXT_net_weight').val() == "") ? 0 : $(this).find('.TXT_net_weight').val().replace(/,/g, '');
			var _unit_gross_weight 	= ($(this).find('.TXT_unit_gross_weight').val() == "") ? 0 : $(this).find('.TXT_unit_gross_weight').val().replace(/,/g, '');
			var _gross_weight 		= ($(this).find('.TXT_gross_weight').val() == "") ? 0 : $(this).find('.TXT_gross_weight').val().replace(/,/g, '');
			var _measure 			= ($(this).find('.TXT_measure').val() == "") ? 0 : $(this).find('.TXT_measure').val().replace(/,/g, '');
	 		var _tax_rate 			= 0;
				_tax_rate 			= parseFloat($('.tax_rate').text().replace(/,/g, ''));
				_tax_rate   		= !isNaN(_tax_rate) ? _tax_rate : 0;
			var _detail_tax 		= 0;
			if (_amount != '') {
					_detail_tax 	= _roundNumeric(parseFloat(_tax_rate) *  parseFloat(_amount), 2, 2);
			}
			
			//get data table rcv detail
			var _t_rcv_d = {
					'rcv_detail_no' 		: parseInt(_rcv_detail_no),
					'sales_detail_div' 		: $(this).find('.CMB_sales_detail_div').val(),
					'product_cd' 			: $(this).find('.TXT_product_cd').val(),
					'description' 			: $(this).find('.TXT_description').val(),
					'qty' 					: parseInt(_qty),
					'unit_of_m_div' 		: $(this).find('.CMB_unit_of_m_div').val(),
					'unit_price' 			: parseFloat(_unit_price),
					'amount' 				: parseFloat(_amount),
					'detail_tax' 			: parseFloat(_detail_tax),
					'unit_measure_qty' 		: parseFloat(_unit_measure_qty),
					'unit_measure_price'	: $(this).find('.CMB_unit_measure_price').val(),
					'outside_remarks'		: $(this).find('.TXT_outside_remarks').val(),
					'unit_net_weight'		: parseFloat(_unit_net_weight),
					'unit_net_weight_div'	: $(this).find('.CMB_unit_net_weight_div').val(),
					'net_weight'			: parseFloat(_net_weight),
					'unit_gross_weight'		: parseFloat(_unit_gross_weight),
					'gross_weight'			: parseFloat(_gross_weight),
					'measure'				: parseFloat(_measure)
			};

			_data.push(_t_rcv_d);
		});


		var _total_qty          = ($('.DSP_total_qty').text() == "") ? 0 : $('.DSP_total_qty').text().replace(/,/g, '');
		var _total_gross_weight = ($('.DSP_total_gross_weight').text() == "") ? 0 : $('.DSP_total_gross_weight').text().replace(/,/g, '');
		var _total_net_weight   = ($('.DSP_total_net_weight').text()  == "") ? 0 : $('.DSP_total_net_weight').text().replace(/,/g, '');
		var _total_measure      = ($('.DSP_total_measure').text()  == "") ? 0 : $('.DSP_total_measure').text().replace(/,/g, '');
	 
		var _total_detail_amt   = ($('.DSP_total_detail_amt').text()  == "") ? 0 : $('.DSP_total_detail_amt').text().replace(/,/g, '');
		var _freigt_amt         = $('.TXT_freigt_amt').hasClass('hidden') ? 0 : $('.TXT_freigt_amt').val().replace(/,/g, '');
		var _insurance_amt      = $('.TXT_insurance_amt').hasClass('hidden') ? 0 : $('.TXT_insurance_amt').val().replace(/,/g, '');
		var _tax_amt            = ($('.DSP_tax_amt').text()  == "") ? 0 : $('.DSP_tax_amt').text().replace(/,/g, '');
	 
		var _total_amt          = ($('.DSP_total_amt').text()  == "" )? 0 : $('.DSP_total_amt').text().replace(/,/g, '')

		var STT_data = {
				'mode'							: mode, 
				'rcv_no'						: $('.TXT_rcv_no').val(),
				'rcv_status'					: $('.TXT_rcv_status').val(),
				'rcv_date'						: $('.TXT_rcv_date ').val(),

				//取引先		
				'cust_cd'						: $('.TXT_cust_cd ').val(),
				'cust_nm'						: $('.TXT_cust_nm ').val(),
				'cust_adr1'						: $('.TXT_cust_adr1 ').val(),
				'cust_adr2'						: $('.TXT_cust_adr2 ').val(),
				'cust_zip'						: $('.TXT_cust_zip ').val(),
				'cust_city_div'					: $('.TXT_cust_city_div ').val(),
				'cust_country_div'				: $('.TXT_cust_country_div ').val(),
				'cust_tel'						: $('.TXT_cust_tel ').val(),
				'cust_fax'						: $('.TXT_cust_fax ').val(),

				//Consignee		
				'consignee_cd'					: $('.TXT_consignee_cd ').val(),
				'consignee_nm'					: $('.TXT_consignee_nm ').val(),
				'consignee_adr1'				: $('.TXT_consignee_adr1 ').val(),
				'consignee_adr2'				: $('.TXT_consignee_adr2 ').val(),
				'consignee_zip'					: $('.TXT_consignee_zip ').val(),
				'consignee_city_div'			: $('.TXT_consignee_city_div ').val(),
				'consignee_country_div'			: $('.TXT_consignee_country_div ').val(),
				'consignee_tel'					: $('.TXT_consignee_tel ').val(),
				'consignee_fax'					: $('.TXT_consignee_fax ').val(),

				// <他>		
				'shipping_mark_1'				: $('.TXT_shipping_mark_1').val(),
				'shipping_mark_2'				: $('.TXT_shipping_mark_2').val(),
				'shipping_mark_3'				: $('.TXT_shipping_mark_3').val(),
				'shipping_mark_4'				: $('.TXT_shipping_mark_4').val(),
				'packing'						: $('.TXT_packing').val(),
				'shipment_div'					: $('.CMB_shipment_div').val(),
				'currency_div'					: $('.CMB_currency_div').val(),
				'port_city_div'					: $('.CMB_port_city_div').val(),
				'port_country_div'				: $('.CMB_port_country_div').val(),
				'trade_terms_div'				: $('.CMB_trade_terms_div').val(),
				'dest_city_div'					: $('.TXT_dest_city_div').val(),
				'dest_country_div'				: $('.TXT_dest_country_div').val(),
				'payment_notes'					: $('.TXT_payment_notes').val(),

				//<明細> data type json
				't_rcv_d' 						: _data,

				//<数量合計明細>
				'total_qty'						: parseInt(_total_qty),
				'unit_total_qty_div'			: $('#table-accept tbody tr:first').find('.CMB_unit_of_m_div option:selected').val(),
				'total_gross_weight'			: parseFloat(_total_gross_weight),
				'unit_total_gross_weight_div'	: $('.DSP_unit_total_gross_weight_div').text(),
				'total_net_weight'				: parseFloat(_total_net_weight),
				'unit_total_net_weight_div'		: $('.DSP_unit_total_net_weight_div').text(),
				'total_measure'					: parseFloat(_total_measure),
				'unit_total_measure_div'		: $('.DSP_unit_total_measure_div').text(),

				//<金額合計>
				'total_detail_amt'				: parseFloat(_total_detail_amt),
				'freigt_amt'					: (_freigt_amt == '') ? 0 : parseFloat(_freigt_amt),
				'insurance_amt'					: (_insurance_amt == '') ? 0 : parseFloat(_insurance_amt),
				'tax_amt'						: parseFloat(_tax_amt),
				'total_amt'						: parseFloat(_total_amt),

				//<フッタ>		
				'country_of_origin '			: $('.TXT_country_of_origin').val(),
				'manufacture '					: $('.TXT_manufacture ').val(),
				'sign_cd'						: $('.TXT_sign_cd').val(),
				'inside_remarks'				: $('.TXA_inside_remarks').val(),
				'our_freight_amt'				: $('.TXT_our_freight_amt').val() == ''? 0: $('.TXT_our_freight_amt').val().replace(/,/g, '')
			};
			
		return STT_data;
	} catch(e) {
        console.log('getData' + e.message)
    }
}
/**
 * validate Accept No
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function validateAcceptNo() {
	try {
		_clearErrors();
		var error 	= true;
		if ($('.accept_cd').val() == '') {
			$('.accept_cd').errorStyle(_MSG_E001);
			error 	= false;
		}
		return error;
	} catch (e) {
		console.log('validateAcceptNo: ' + e.message);
	}
}
/**
 * delete accept detail
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function deleteAccept() {
	try {
		var _rcv_no = $('.TXT_rcv_no').val();

		$.ajax({
	        type        :   'POST',
	        url         :   '/accept/accept-detail/delete',
	        dataType    :   'json',
	        data        :   {rcv_no : _rcv_no},
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		jMessage('I002', function(r){
		                	if(r){
		                		emptyAllInput();
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
	}  catch(e) {
        console.log('deleteAccept: ' + e.message)
    }
}
/**
 * approve accept detail
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function approveAccept() {
	try {
		var params = {
			rcv_no 		   : $('.TXT_rcv_no').val(),
			inside_remarks : $('.TXA_inside_remarks').val()
		}
		var _rcv_no = $('.TXT_rcv_no').val();
		
		$.ajax({
	        type        :   'POST',
	        url         :   '/accept/accept-detail/approve',
	        dataType    :   'json',
	        data        :   params,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		jMessage('I005', function(r){
		                	if(r){
		                		var data = {
		                			rcv_no 		: res.rcv_no,
		                			rcv_status 	: res.rcv_status,
		                			mode 		: mode,
		                		}
		                		referAcceptDetail(data);
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
	}  catch(e) {
        console.log('approveAccept: ' + e.message)
    }
}
/**
 * approve cancel accept detail
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function cancelApproveAccept() {
	try {
		var params = {
			rcv_no 		   : $('.TXT_rcv_no').val(),
		}

		$.ajax({
	        type        :   'POST',
	        url         :   '/accept/accept-detail/approve-cancel',
	        dataType    :   'json',
	        data        :   params,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		jMessage('I006', function(r){
		                	if(r){
		                		var data = {
		                			rcv_no 		: res.rcv_no,
		                			rcv_status 	: res.rcv_status,
		                			mode 	 	: mode,
		                		}
		                		referAcceptDetail(data);
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
        console.log('cancelApproveAccept: ' + e.message)
    }
}
/**
 * cancel order => print
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function cancelOrder() {
	try {
		var _rcv_no = $('.TXT_rcv_no').val();

		$.ajax({
	        type        :   'POST',
	        url         :   '/accept/accept-detail/cancel-order',
	        dataType    :   'json',
	        data        :   {rcv_no : _rcv_no},
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		jMessage('I004', function(r){
		                	if(r){
		                		var data = {
		                			rcv_no 		: res.rcv_no,
		                			rcv_status 	: res.rcv_status,
		                			mode 	 	: mode,
		                		}
		                		referAcceptDetail(data);
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
        console.log('cancelOrder: ' + e.message)
    }
}
/**
 * cancel order => print
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referProduct(data, pos, obj) {
	try	{
		$.ajax({
			type 		: 'GET',
			url 		: '/accept/refer-product',
			dataType	: 'json',
			data 		: data,
			timeout 	: 10000,
			success: function(res) {
				var data = {};
				$('.TXT_unit_price').removeClass('warning-item');
				if (res.response) {
					data 	=	res.data;
					clearErrorsTableDetail(obj.closest('tr'));

					if (data.unit_price == null) {
						jMessage('W002', function(r) {
							if (r) {
								 _removeErrorStyle(obj);
								var msg = _text['W002'];
								obj.closest('tr').find('.TXT_unit_price').addClass('warning-item');
								obj.closest('tr').find('.TXT_unit_price').focus();
							}
						});
					}
				}
				setItemReferProduct(data, pos)
			}
		});
	} catch (e) {
		console.log('referProduct: ' + e.message);
	}
}
/**
 * cal Amount
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calAmount(pos) {
	try {
		var qty 			= 	parent.$('.cal-refer-'+pos).find('.TXT_qty').val().replace(/,/g, '');
		var unit_price 		=	parent.$('.cal-refer-'+pos).find('.TXT_unit_price').val().replace(/,/g, '');
		var amount 			=	_roundNumeric(parseFloat(qty) * parseFloat(unit_price), 2, 2);
		if (isNaN(amount)) {
			amount = 0;
		}
		parent.$('.cal-refer-'+pos).find('.TXT_amount').val(_convertMoneyToIntAndContra(amount));
	} catch(e) {
        console.log('calAmount: ' + e.message)
    }
}
/**
 * cal net weight
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calNetWeight(pos) {
	try {
		var qty 				= 	parent.$('.cal-refer-'+pos).find('.TXT_qty').val().replace(/,/g, '');
		var unit_net_weight 	=	parent.$('.cal-refer-'+pos).find('.TXT_unit_net_weight').val().replace(/,/g, '');
		var net_weight 			=	_roundNumeric(parseFloat(qty) * parseFloat(unit_net_weight), 2, 2);
		if (isNaN(net_weight)) {
			net_weight = 0;
		}
		parent.$('.cal-refer-'+pos).find('.TXT_net_weight').val(_convertMoneyToIntAndContra(net_weight));
	} catch(e) {
        console.log('calNetWeight' + e.message)
    }
}
/**
 * cal gross weight
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calGrossWeight(pos) {
	try {
		var qty 				= 	parent.$('.cal-refer-'+pos).find('.TXT_qty').val().replace(/,/g, '');
		var unit_gross_weight 	=	parent.$('.cal-refer-'+pos).find('.TXT_unit_gross_weight').val().replace(/,/g, '');
		var gross_weight 			=	_roundNumeric(parseFloat(qty) * parseFloat(unit_gross_weight), 2, 2);
		if (isNaN(gross_weight)) {
			gross_weight = 0;
		}
		parent.$('.cal-refer-'+pos).find('.TXT_gross_weight').val(_convertMoneyToIntAndContra(gross_weight));
	} catch(e) {
        console.log('calGrossWeight' + e.message)
    }
}
/**
 * cal measure
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calMeasure(pos) {
	try {
		var qty 				= 	parent.$('.cal-refer-'+pos).find('.TXT_qty').val().replace(/,/g, '');
		var unit_measure_qty 	=	parent.$('.cal-refer-'+pos).find('.TXT_unit_measure_qty').val().replace(/,/g, '');
		var measure 			=	_roundNumeric(parseFloat(qty) * parseFloat(unit_measure_qty), 2, 2);
		if (isNaN(measure)) {
			measure = 0;
		}
		parent.$('.cal-refer-'+pos).find('.TXT_measure').val(_convertMoneyToIntAndContra(measure));
	} catch(e) {
        console.log('calMeasure' + e.message)
    }
}
/**
 * validate Amount Detail
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function validateAmountDetail(element) {
	try {
		var parent 			= element.parents('#table-accept tbody tr');
		var amount 			= parent.find('.TXT_amount').val().replace(/,/g, '');
			amount 			= parseFloat(amount);

		var flag_amount 	=	false;
		// detail_amt numeric(15,2)
		if (amount < -9999999999999.99 || amount > 9999999999999.99) {
			parent.find('.TXT_amount').addClass('error-numeric');
			flag_amount		=	true;
		} else {
			parent.find('.TXT_amount').removeClass('error-numeric');
		}

		if (flag_amount) {
			parent.find('.TXT_unit_price').addClass('error-numeric');
		} else {
			parent.find('.TXT_unit_price').removeClass('error-numeric');
		}
	} catch (e) {
		console.log('validateAmountDetail: ' + e.message);
	}
}
/**
 * validate Net Weight Detail
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function validateNetWeightDetail(element) {
	try {
		var parent 			= element.parents('#table-accept tbody tr');
		var net_weight 		= parent.find('.TXT_net_weight').val().replace(/,/g, '');
			net_weight 		= parseFloat(net_weight);

		var flag_net_weight 	=	false;
		// net_weight numeric(15,2)
		if (net_weight < -9999999999999.99 || net_weight > 9999999999999.99) {
			parent.find('.TXT_net_weight').addClass('error-numeric');
			flag_net_weight		=	true;
		} else {
			parent.find('.TXT_net_weight').removeClass('error-numeric');
		}

		if (flag_net_weight) {
			parent.find('.TXT_unit_net_weight').addClass('error-numeric');
		} else {
			parent.find('.TXT_unit_net_weight').removeClass('error-numeric');
		}
	} catch (e) {
		console.log('validateNetWeightDetail: ' + e.message);
	}
}
/**
 * validate Gross Weight Detail
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function validateGrossWeightDetail(element) {
	try {
		var parent 			= element.parents('#table-accept tbody tr');
		var gross_weight 	= parent.find('.TXT_gross_weight').val().replace(/,/g, '');
			gross_weight 	= parseFloat(gross_weight);

		var flag_gross_weight 	=	false;
		// gross_weight numeric(15,2)
		if (gross_weight < -9999999999999.99 || gross_weight > 9999999999999.99) {
			parent.find('.TXT_gross_weight').addClass('error-numeric');
			flag_gross_weight	=	true;
		} else {
			parent.find('.TXT_gross_weight').removeClass('error-numeric');
		}

		if (flag_gross_weight) {
			parent.find('.TXT_unit_gross_weight').addClass('error-numeric');
		} else {
			parent.find('.TXT_unit_gross_weight').removeClass('error-numeric');
		}
	} catch (e) {
		console.log('validateGrossWeightDetail: ' + e.message);
	}
}
/**
 * validate Measure Detail
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function validateMeasureDetail(element) {
	try {
		var parent 			= element.parents('#table-accept tbody tr');
		var measure 		= parent.find('.TXT_measure').val().replace(/,/g, '');
			measure 		= parseFloat(measure);

		var flag_measure 	=	false;
		// measure numeric(15,2)
		if (measure < -9999999999999.99 || measure > 9999999999999.99) {
			parent.find('.TXT_measure').addClass('error-numeric');
			flag_measure	=	true;
		} else {
			parent.find('.TXT_measure').removeClass('error-numeric');
		}

		if (flag_measure) {
			parent.find('.TXT_unit_measure_qty').addClass('error-numeric');
		} else {
			parent.find('.TXT_unit_measure_qty').removeClass('error-numeric');
		}
	} catch (e) {
		console.log('validateMeasureDetail: ' + e.message);
	}
}
/**
 * validate Qty Detail
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function validateQtyDetail(element) {
	try {
		var parent = element.parents('#table-accept tbody tr');

		parent.find('.TXT_qty').removeClass('error-numeric');

		if (parent.find('.error-numeric').length > 0) {
			parent.find('.TXT_qty').addClass('error-numeric');
		}
		parent.find('.error-numeric:first').focus();
	} catch (e) {
		console.log('validateQtyDetail: ' + e.message);
	}
}
/**
 * set item refer product
 * 
 * @author : ANS804 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setItemReferProduct(data, pos) {
	try {
		if (jQuery.isEmptyObject(data)) {
			parent.$('.refer-product-'+pos).find('.TXT_description').val('');
		} else {
			parent.$('.refer-product-'+pos).find('.TXT_product_cd').val(data.product_cd);
			parent.$('.refer-product-'+pos).find('.TXT_description').val(data.description);
			parent.$('.refer-product-'+pos).find('.CMB_unit_of_m_div option:first').prop('selected', true);
			parent.$('.refer-product-'+pos).find('.CMB_unit_measure_price option:first').prop('selected', true);
			parent.$('.refer-product-'+pos).find('.CMB_unit_net_weight_div option:first').prop('selected', true);		
			if (data.unit_qty_div != '') {
				parent.$('.refer-product-'+pos).find('.CMB_unit_of_m_div option[value='+data.unit_qty_div+']').prop('selected', true);
			}
			if (data.unit_measure_price != '') {
				parent.$('.refer-product-'+pos).find('.CMB_unit_measure_price option[value='+data.unit_measure_price+']').prop('selected', true);
			}
			if (data.unit_net_weight_div != '') {
				parent.$('.refer-product-'+pos).find('.CMB_unit_net_weight_div option[value='+data.unit_net_weight_div+']').prop('selected', true);
			}
			parent.$('.refer-product-'+pos).find('.TXT_unit_price').val(data.unit_price);
			parent.$('.refer-product-'+pos).find('.DSP_unit_price_JPY').text(data.unit_price_JPY);
			parent.$('.refer-product-'+pos).find('.DSP_unit_price_USD').text(data.unit_price_USD);
			parent.$('.refer-product-'+pos).find('.DSP_unit_price_EUR').text(data.unit_price_EUR);
			parent.$('.refer-product-'+pos).find('.TXT_unit_measure_qty').val(data.measure);
			parent.$('.refer-product-'+pos).find('.TXT_unit_net_weight').val(data.unit_net_weight);
			parent.$('.refer-product-'+pos).find('.TXT_unit_gross_weight').val(data.unit_gross_weight);
		}

		//cal amount
		calAmount(pos);
		//cal net weight
		calNetWeight(pos);
		//cal gross weight
		calGrossWeight(pos);
		//cal measure
		calMeasure(pos);
		//remover class parent
		parent.$('.cal-refer-'+pos).removeClass('cal-refer-'+pos);

		
		calTotalDetailAmt();
		calTotalTaxAmt();
		calTotalAmt();

		calTotalGrossWeight();
		calTotalNetWeight();
		calTotalMeasure();
		calTotalQty();
		// if (jQuery.isEmptyObject(data)) {
		// 	parent.$('.refer-product-'+pos).find('.TXT_qty').val('');
		// 	parent.$('.refer-product-'+pos).find('.TXT_outside_remarks').val('');
		// 	calTotalQty();
		// }
		//remove class parent refer-product
		parent.$('.refer-product-'+pos).removeClass('refer-product-'+pos);
	} catch (e) {
		console.log('setItemReferProduct: ' + e.message);
	}
}
/**
 * empty All Input
 * 
 * @author : ANS804 - 2018/01/17 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function emptyAllInput(){
	try {
		// not empty code of "destination_city", "destination_country", "署名者"(signer)
		//$(':input:not(.TXT_rcv_no):not(.TXT_dest_city_div):not(.TXT_dest_country_div):not(.TXT_sign_cd)').val('');
		$(':input').val('');
		$('.DSP_cust_city_nm').text('');
		$('.DSP_cust_country_nm').text('');
		$('.DSP_consignee_city_nm').text('');
		$('.DSP_consignee_country_nm').text('');
		$('.DSP_dest_city_nm').text('');
		$('.DSP_dest_country_nm').text('');
		$('.DSP_total_detail_amt').text('');
		$('.DSP_total_amt').text('');
		$('.DSP_total_qty').text('');
		$('.DSP_total_gross_weight').text('');
		$('.DSP_unit_total_gross_weight_div').text('');
		$('.DSP_total_net_weight').text('');
		$('.DSP_unit_total_net_weight_div').text('');
		$('.DSP_total_measure').text('');
		$('.DSP_unit_total_measure_div').text('');
		$('.DSP_tax_amt').text('');
		$('.DSP_sign_nm').text('');

		$('#DSP_cre_user_cd').text('');
		$('#DSP_cre_datetime').text('');
		$('#DSP_upd_user_cd').text('');
		$('#DSP_upd_datetime').text('');

		$('.DSP_status').text('');
		$('.DSP_rcv_status_cre_datetime').text('');

		$('.DSP_tax_amt').addClass('hidden');
		$('.title-jp').addClass('hidden');

		$('.TXT_rcv_date').val($('.TXT_rcv_date').attr('data-init'));

		$('.TXT_packing ').val(_constVal1['pi_packing']);
		$(".TXT_sign_cd").val(cre_user_cd);
		$(".DSP_sign_nm").text(cre_user_nm);

		//init 1 row table at mode add new (I)
		_initRowTable('table-accept', 'table-row', 1, setClassUnitCombobox);
		
		$('.infor-created .heading-elements').addClass('hidden');
	} catch (e) {
		console.log('emptyAllInput: ' + e.message)
	}
}
/**
 * empty input after refer not exists
 * 
 * @author : ANS804 - 2018/02/21 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function emptyInputAfterRefer(){
	try {
		// not empty code of "destination_city", "destination_country", "署名者"(signer)
		$(':input:not(.TXT_rcv_no):not(.TXT_dest_city_div):not(.TXT_dest_country_div):not(.TXT_sign_cd)').val('');

		$('.DSP_cust_city_nm').text('');
		$('.DSP_cust_country_nm').text('');
		$('.DSP_consignee_city_nm').text('');
		$('.DSP_consignee_country_nm').text('');
		$('.DSP_dest_city_nm').text('');
		$('.DSP_dest_country_nm').text('');
		$('.DSP_total_detail_amt').text('');
		$('.DSP_total_amt').text('');
		$('.DSP_total_qty').text('');
		$('.DSP_total_gross_weight').text('');
		$('.DSP_unit_total_gross_weight_div').text('');
		$('.DSP_total_net_weight').text('');
		$('.DSP_unit_total_net_weight_div').text('');
		$('.DSP_total_measure').text('');
		$('.DSP_unit_total_measure_div').text('');
		$('.DSP_tax_amt').text('');
		$('.DSP_sign_nm').text('');

		$('#DSP_cre_user_cd').text('');
		$('#DSP_cre_datetime').text('');
		$('#DSP_upd_user_cd').text('');
		$('#DSP_upd_datetime').text('');

		$('.DSP_status').text('');
		$('.DSP_rcv_status_cre_datetime').text('');

		$('.DSP_tax_amt').addClass('hidden');
		$('.title-jp').addClass('hidden');

		$('.TXT_rcv_date').val($('.TXT_rcv_date').attr('data-init'));

		$('.TXT_packing ').val(_constVal1['pi_packing']);
		$(".TXT_sign_cd").val(cre_user_cd);
		$(".DSP_sign_nm").text(cre_user_nm);

		//init 1 row table at mode add new (I)
		_initRowTable('table-accept', 'table-row', 1, setClassUnitCombobox);
		
		$('.infor-created .heading-elements').addClass('hidden');
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
		console.log('emptyInputAfterRefer: ' + e.message)
	}
}
/**
 * clear Errors Table Detail
 * 
 * @author : ANS804 - 2018/01/17 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function clearErrorsTableDetail(obj){
	try {
		obj.find('.error-item:not(.CMB_sales_detail_div)').removeErrorStyle();
		obj.find('.error-item:not(.CMB_sales_detail_div)').removeClass('error-item').removeAttr('index');
		obj.find('.error-tip-mesage').remove();
		obj.find('.space-error').empty();
		obj.find('.textbox-error').removeErrorStyle();
		obj.find('.row-error').removeClass('row-error');
	} catch (e) {
		console.log('clearErrorsTableDetail: ' + e.message)
	}
}
/**
 * update Table Rcv Detail
 * 
 * @author : ANS804 - 2018/01/17 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function updateTableRcvDetail() {
	try {
		_updateTable('table-accept', true);
		$('#table-accept tbody tr:first').find('.CMB_unit_net_weight_div').addClass('unit_net_weight_div')
		$('#table-accept tbody tr:first').find('.CMB_unit_measure_price').addClass('unit_measure_price')
	} catch (e) {
		console.log('updateTableRcvDetail: ' + e.message)
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
function getErrorItem(error_list,detail_error_list) {
 	try {
 		if (!!detail_error_list) {
 			$.each(detail_error_list,function(index, value){
 				var position = parseInt(value['position']);
 				if (!isNaN(position)) {
 					$('#table-accept tbody tr:nth-child(' + position + ')').find('.TXT_product_cd').errorStyle(_text['E005']);
 				}
 			});
 		}
 		if (!!error_list) {
 			if (jQuery.inArray('TXT_cust_city_div') || jQuery.inArray('TXT_cust_country_div')) {
 				$('.address-to').removeClass('hidden');
 			}
 			if (jQuery.inArray('TXT_consignee_city_div') || jQuery.inArray('TXT_consignee_country_div')) {
 				$('.address-from').removeClass('hidden');
 			}
 			$.each(error_list,function(index, value){
 				if (value != '' && $('.' + value).val() != '') {
 					$('.' + value).errorStyle(_text['E005']);
 				}
 			});
 		}
 	} catch (e) {
		console.log('getErrorItem: ' + e.message)
	}
}