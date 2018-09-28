/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	DuyTP
 *
 * 更新日		: 	2018/01/08
 * 更新者		: 	HaVV - ANS 817
 * 更新内容		: 	New Development
 *
 * @package		:	INVOICE
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

var data_empty_deposit = {
	deposit_no 			: '', 
	invoice_no 			: '', 
	rcv_no 				: '', 
	cust_cd 			: '', 
	client_nm 			: '', 
	deposit_div 		: '', 
	deposit_date 		: '', 
	split_deposit_div 	: '', 
	initial_deposit_date: '', 
	deposit_bank_div 	: '', 
	country_div 		: '', 
	country_div_nm 		: '', 
	currency_div 		: '', 
	deposit_way_div 	: '', 
	remittance_amt 		: '', 
	fee_foreign_amt 	: '', 
	fee_yen_amt 		: '', 
	arrival_foreign_amt : '', 
	deposit_yen_amt 	: '', 
	exchange_rate 		: '', 
	rate_confirm_div 	: '', 
	notices 			: '', 
	inside_remarks 		: '', 
};

var data_empty_rcv_header = {
	total_amt 				: '', 
	currency_div_nm			: '', 
	total_amount_entered 	: '', 
};

var obj_refer_mode = {
	'deposit_no'	: 	1,
	'invoide_no'	: 	2,
	'rcv_no'		: 	3,
	'client_cd'		: 	4,
};

$(document).ready(function () {
	initEvents();
 	initCombobox();
	initDisplayView();
});
/**
 * init display view
 * @author  :   ANS817 - 2018/01/08 - create
 * @param
 * @return
 */
function initDisplayView() {
    setMode(mode);
	if(mode=='I'){
		//mode insert

		//remove button delete
        $('#btn-delete').remove();
    }
}
/**
 * init data combobox
 * @author  :   DuyTP - 2017/06/09 - create
 * @param
 * @return
 */
function initCombobox() {
	var name = 'JP';
	//get combobox
	// $.when(
	// 	_getComboboxData(name, 'deposit_div'),
	// 	_getComboboxData(name, 'target_div'),
	// 	_getComboboxData(name, 'bank_div'),
	// 	_getComboboxData(name, 'currency_div'),
	// 	_getComboboxData(name, 'deposit_way_div'),
	// 	_getComboboxData(name, 'rate_confirm_div')
	// ).done(function(){
	// 	//refer data from screen search to detail
	// 	if (mode == 'U') {
	// 		$('#TXT_deposit_no').trigger('change');
	// 	}
	// });
	if (mode == 'U') {
		$('#TXT_deposit_no').trigger('change');
	}
}
/**
 * init Events
 * @author  :   DuyTP - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//init back
		$(document).on('click', '#btn-back', function () {
			if (from == 'DepositSearch') {
				sessionStorage.setItem('detail', true);
				location.href = '/deposit/deposit-search';
			}
		});

		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				var isCheck1 = validate();
				var isCheck2 = validateDepositYenAmt();

				//validate not ok
				if (!isCheck1 || !isCheck2) {
					return;
				}

				//check total order amount < total amount entered
				validateTotalAmt();
			} catch (e) {
				alert('#btn-save ' + e.message);
			}
		});

		// button delete
		$(document).on('click', '#btn-delete', function() {
			try {
				if($.trim($('#TXT_deposit_no').val()) == '' ) {
					$('#TXT_deposit_no').errorStyle(_MSG_E001);
				}else{
					jMessage('C002', function(r){
						if(r){
							postDelete();
						}
					});
				}
			} catch (e) {
				alert('#btn-delete ' + e.message);
			}
		});

		//change TXT_client_country_div
		$(document).on('change', '#TXT_client_country_div', function() {
			try {
				_referCountry($(this).val(), '', $(this), '', true);			   
			} catch (e) {
				alert('change #TXT_client_country_div: ' + e.message);
			}
		});

		//calculate DSP_arrival_foreign_amt
		$(document).on('change', '#TXT_remittance_amt, #TXT_fee_foreign_amt', function() {
			try {
				var value = convertStringToNumeric($('#TXT_remittance_amt').val().trim());
				if (value == 0) {
					$('#TXT_remittance_amt').val('');
				}

				calculateArrivalForeignAmt();
				calculateDepositYenAmt();
				var total_entered_amt_db = convertStringToNumeric($('#DSP_total_entered_amt').attr('total_entered_amt_db'));
				calculateTotalEnteredAmt(total_entered_amt_db);
			} catch (e) {
				alert('calculate DSP_arrival_foreign_amt: ' + e.message);
			}
		});

		//calculate DSP_deposit_yen_amt
		$(document).on('change', '#TXT_exchange_rate', function() {
			try {
				calculateDepositYenAmt();
			} catch (e) {
				alert('calculate DSP_deposit_yen_amt: ' + e.message);
			}
		});

		//change CMB_currency_div
		$(document).on('change', '#CMB_currency_div', function(event, isRefer) {
			try {
				var value = $(this).val();
				var text  = $("#CMB_currency_div option:selected").text();

				//set value choose for DSP_currency_div
				$('.DSP_currency_div.deposit_currency_div').html(text);

				//IF refer THEN return
				if (isRefer) {
					return;
				}

				if (value == 'JPY') {
					//IF choose JPY THEN set TXT_exchange_rate = 1 and disabled
					$('#TXT_exchange_rate').val(1);
					$('#TXT_exchange_rate').trigger('change');
					$('#TXT_exchange_rate').attr('disabled', true);
					_removeErrorStyle($('#TXT_exchange_rate'));
				} else {
					//IF not choose JPY THEN set reset item TXT_exchange_rate
					$('#TXT_exchange_rate').val('');
					$('#TXT_exchange_rate').trigger('change');
					$('#TXT_exchange_rate').attr('disabled', false);
				}
			} catch (e) {
				alert('change #CMB_currency_div: ' + e.message);
			}
		});

		//change TXT_deposit_no
		$(document).on('change', '#TXT_deposit_no', function() {
			try {
				referDeposit();
			} catch (e) {
				alert('change #TXT_deposit_no: ' + e.message);
			}
		});

		//change TXT_rcv_no
		$(document).on('change', '#TXT_rcv_no', function() {
			try {
				referRcv();
			} catch (e) {
				alert('change #TXT_rcv_no: ' + e.message);
			}
		});

		//change TXT_inv_no
		$(document).on('change', '#TXT_inv_no', function() {
			try {
				referInvoice();
			} catch (e) {
				alert('change #TXT_inv_no: ' + e.message);
			}
		});

		//change TXT_client_cd
		$(document).on('change', '#TXT_client_cd', function() {
			try {
				referClient();
			} catch (e) {
				alert('change #TXT_client_cd: ' + e.message);
			}
		});
	} catch (e) {
		alert('initEvents: ' + e.message);
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
	if(!_validate($('body'))) {
		_errors++;
	}

	if(_errors>0)
		return false;

	return true;
}

/**
 * validate deposit_yen_amt
 *
 * @author		:	ANS817 - 2018/01/23 - create
 * @params		:	null
 * @return		:	null
 */
function validateDepositYenAmt() {
	try {
		var flag = true;

		var deposit_yen_amt = convertStringToNumeric($('#DSP_deposit_yen_amt').html().trim());

		if (deposit_yen_amt < -999999999999999.99 || deposit_yen_amt > 9999999999999.99) {
			$('#DSP_deposit_yen_amt').addClass('error-numeric');
			flag = false;
		} else {
			$('#DSP_deposit_yen_amt').removeClass('error-numeric');
			flag = true;
		}

		return flag;
	} catch (e) {
		alert('validateDepositYenAmt: ' + e.message);
	}
}

/**
 * check total order amount < total amount entered
 *
 * @author      :   ANS817 - 2018/01/10 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function validateTotalAmt() {
	try {
		//get data from view
		var arrival_foreign_amt = $('#DSP_arrival_foreign_amt').html();
		arrival_foreign_amt     = arrival_foreign_amt == '' ? 0 : arrival_foreign_amt.replace(/,/g, '');
		var data                = {
			deposit_no			: 	$('#TXT_deposit_no').val().trim(),
			rcv_no				: 	$('#TXT_rcv_no').val().trim(),
			arrival_foreign_amt	: 	parseFloat(arrival_foreign_amt),
		}

		$.ajax({
	        type        :   'POST',
	        url         :   '/deposit/deposit-detail/validate-total-amt',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	if(res.error_cd != ''){
            		jMessage(res.error_cd, function(r){
						if(r){
							//validate ok
							var msg = (mode == 'I')?'C001':'C003';
							jMessage(msg,function(r){
								if(r){
									save();
								}
							});
						}
					});
            	} else {
            		//validate ok
					var msg = (mode == 'I')?'C001':'C003';
					jMessage(msg,function(r){
						if(r){
							save();
						}
					});
            	}
	        },
	    });
    } catch (e) {
        alert('save: ' + e.message);
    }
}

/**
 * save deposit - insert/update
 *
 * @author      :   ANS817 - 2018/01/09 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function save() {
	try {
		//get data from view
		var data = getDataFromView();

		$.ajax({
	        type        :   'POST',
	        url         :   '/deposit/deposit-detail/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	//display message E005 error when 入金NO, Invoice No, 受注No, 取引先, 国 not exists
	        	if(res.data_err != null){
	        		jMessage('E005', function(r){
	        			if(r){
	        				for(var i = 0; i < res.data_err.length; i++){
	        					$('#' + res.data_err[i]['item_err']).errorStyle(_text['E005']);
	        				}
	        			}
	        		});
	        	}else if(res.error_cd != ''){
            		jMessage(res.error_cd);
            	}else if(res.response == true){
	            	var msg = (mode == 'I')?'I001':'I003';
	            	jMessage(msg,function(r){
						if(r){
							mode = 'U';
							$('#TXT_deposit_no').val(res.deposit_no);
							setMode(mode);
							referDeposit();
						}
					});
	            }else{
	            	//catch DB error and display
	            	var msg_e999 = _text['E999'].replace('{0}', res.error);
	            	jMessage_str('E999', msg_e999, '', msg_e999);
	            }
	        },
	    });
    } catch (e) {
        alert('save: ' + e.message);
    }
}

/**
 * delete deposit
 * 
 * @author : ANS817 - 2018/01/10 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postDelete(){
	try{
	    //get data
		var deposit_no 	= $('#TXT_deposit_no').val().trim();
	    var data = {
	    	deposit_no 	: deposit_no
	    };
	    $.ajax({
	        type        :   'POST',
	        url         :   '/deposit/deposit-detail/delete',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	if(res.error_cd != ''){
            		jMessage(res.error_cd);
            	}else if(res.response == true){
	            	jMessage('I002',function(r){
						if(r){
							$('#TXT_deposit_no').val('');
							var param = {
								'mode'		: mode,
								'from'		: from
							};
							_postParamToLink(from, 'DepositDetail', '/deposit/deposit-detail', param);
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
        alert('postDelete: ' + e.message)
    }
}

/**
 * get data from view
 *
 * @author      :   ANS817 - 2018/01/09 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function getDataFromView() {
	try {
		var remittance_amt      = $('#TXT_remittance_amt').val().trim();
		remittance_amt          = remittance_amt == '' ? 0 : remittance_amt.replace(/,/g, '');
		var fee_foreign_amt     = $('#TXT_fee_foreign_amt').val().trim();
		fee_foreign_amt         = fee_foreign_amt == '' ? 0 : fee_foreign_amt.replace(/,/g, '');
		var fee_yen_amt         = $('#TXT_fee_yen_amt').val().trim();
		fee_yen_amt             = fee_yen_amt == '' ? 0 : fee_yen_amt.replace(/,/g, '');
		var arrival_foreign_amt = $('#DSP_arrival_foreign_amt').html();
		arrival_foreign_amt     = arrival_foreign_amt == '' ? 0 : arrival_foreign_amt.replace(/,/g, '');
		var deposit_yen_amt     = $('#DSP_deposit_yen_amt').html();
		deposit_yen_amt         = deposit_yen_amt == '' ? 0 : deposit_yen_amt.replace(/,/g, '');
		var exchange_rate       = $('#TXT_exchange_rate').val().trim();
		exchange_rate           = exchange_rate == '' ? 0 : exchange_rate.replace(/,/g, '');

		var data = {
			mode					: 	mode,
			deposit_no				: 	$('#TXT_deposit_no').val().trim(),
			deposit_div				: 	$('#CMB_deposit_div').val().trim(),
			rcv_no					: 	$('#TXT_rcv_no').val().trim(),
			inv_no					: 	$('#TXT_inv_no').val().trim(),
			deposit_date			: 	$('#TXT_deposit_date').val().trim(),
			split_deposit_div		: 	$('#CMB_split_deposit_div').val().trim(),
			initial_deposit_date	: 	$('#TXT_initial_deposit_date').val().trim(),
			deposit_bank_div		: 	$('#CMB_deposit_bank_div').val().trim(),
			client_cd				: 	$('#TXT_client_cd').val().trim(),
			country_div				: 	$('#TXT_client_country_div').val().trim(),
			currency_div			: 	$('#CMB_currency_div').val().trim(),
			deposit_way_div			: 	$('#CMB_deposit_way_div').val().trim(),
			remittance_amt			: 	parseFloat(remittance_amt),
			fee_foreign_amt			: 	parseFloat(fee_foreign_amt),
			fee_yen_amt				: 	parseFloat(fee_yen_amt),
			arrival_foreign_amt		: 	parseFloat(arrival_foreign_amt),
			deposit_yen_amt			: 	parseFloat(deposit_yen_amt),
			exchange_rate			: 	parseFloat(exchange_rate),
			rate_confirm_div		: 	$('#CMB_rate_confirm_div').val().trim(),
			notices					: 	$('#TXT_notices').val().trim(),
			inside_remarks			: 	$('#TXT_inside_remarks').val().trim()
		};

		return data;
    } catch (e) {
        alert('save: ' + e.message);
    }
}

/**
 * calculate Arrival Foreign Amt
 *
 * @author		:	ANS817 - 2018/01/08 - create
 * @params		:	null
 * @return		:	null
 */
function calculateArrivalForeignAmt() {
	try {
		var TXT_remittance_amt  = convertStringToNumeric($('#TXT_remittance_amt').val().trim());
		var TXT_fee_foreign_amt = convertStringToNumeric($('#TXT_fee_foreign_amt').val().trim());

		//calculate DSP_arrival_foreign_amt
		var DSP_arrival_foreign_amt = TXT_remittance_amt - TXT_fee_foreign_amt;
		//set new DSP_arrival_foreign_amt
		$('#DSP_arrival_foreign_amt').html(addCommas(DSP_arrival_foreign_amt.toFixed(2)).replace('.00',''));
	} catch (e) {
		alert('calculateArrivalForeignAmt: ' + e.message);
	}
}

/**
 * calculate Deposit Yen Amt
 *
 * @author		:	ANS817 - 2018/01/08 - create
 * @params		:	null
 * @return		:	null
 */
function calculateDepositYenAmt() {
	try {
		var DSP_arrival_foreign_amt = convertStringToNumeric($('#DSP_arrival_foreign_amt').html());
		var TXT_exchange_rate       = convertStringToNumeric($('#TXT_exchange_rate').val().trim());
		// var TXT_fee_yen_amt         = convertStringToNumeric($('#TXT_fee_yen_amt').val().trim());

		//calculate DSP_deposit_yen_amt
		var DSP_deposit_yen_amt = DSP_arrival_foreign_amt * TXT_exchange_rate 
		// - TXT_fee_yen_amt;

		//round normal with 
		// var ctl_val = 1;
		var ctl_val = 3;
		//set new DSP_deposit_yen_amt
		// $('#DSP_deposit_yen_amt').html(addCommas(_roundNumeric(DSP_deposit_yen_amt, ctl_val)).replace('.00',''));
		$('#DSP_deposit_yen_amt').html(addCommas(_roundNumeric(DSP_deposit_yen_amt.toFixed(4), ctl_val, 0)).replace('.00',''));

		validateDepositYenAmt();
	} catch (e) {
		alert('calculateDepositYenAmt: ' + e.message);
	}
}

/**
 * calculate total_entered_amt
 *
 * @author		:	ANS817 - 2018/01/18 - create
 * @params		:	total_amount_entered - float
 * @return		:	null
 */
function calculateTotalEnteredAmt(total_amount_entered) {
	try {
		var DSP_arrival_foreign_amt = convertStringToNumeric($('#DSP_arrival_foreign_amt').html());

		var total_entered_amt   	= 0;
		if (total_amount_entered) {
			total_entered_amt 		= DSP_arrival_foreign_amt + parseFloat(total_amount_entered);
		} else {
			total_entered_amt 		= DSP_arrival_foreign_amt;
		}

		$('#DSP_total_entered_amt').html(addCommas(total_entered_amt.toFixed(2)).replace('.00',''));
	} catch (e) {
		alert('calculateTotalEnteredAmt: ' + e.message);
	}
}

/**
 * refer deposit
 *
 * @author		:	ANS817 - 2018/01/11 - create
 * @params		:	null
 * @return		:	null
 */
function referDeposit() {
	try {
		//clear all error
		_clearErrors();
		$('#DSP_deposit_yen_amt').removeClass('error-numeric');

		var deposit_no = $('#TXT_deposit_no').val().trim();

		var data = {
	    	deposit_no 	: deposit_no,
	    	mode		: mode,
	    };

	    $.ajax({
	        type        :   'POST',
	        url         :   '/deposit/deposit-detail/refer-deposit',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if(res.response == true) {
	            	//set button
	            	$('.heading-btn-group').html(res.button_header);
	            	//set header info
	            	$('#operator_info').html(res.info_header);

	            	//set value to items
	            	setValueItems(res, obj_refer_mode.deposit_no);

	            	//set enable TXT_deposit_no
	            	$('#TXT_deposit_no').removeAttr('readonly');
	            	$('#TXT_deposit_no').parent().find('button').removeAttr('disabled');
	            	
	            	initDisplayView();
	            	_setTabIndex();
	            }else{
	            	if (deposit_no == '') {
	            		if (mode == 'U') {
		            		clearAllItem(obj_refer_mode.deposit_no);
		            	} else if (mode == 'I') {
		            		//clear operator_info
							$('#operator_info').html('');
		            	}
		            	//set button
		            	$('.heading-btn-group').html(res.button_header);
	            		initDisplayView();
			            _setTabIndex();
	            	} else {
	            		jMessage('W001',function(r){
							if(r){
				            	if (mode == 'U') {
				            		clearAllItem(obj_refer_mode.deposit_no);
				            	} else if (mode == 'I') {
				            		//clear operator_info
									$('#operator_info').html('');
				            	}
								//set button
								$('.heading-btn-group').html(res.button_header);
					            initDisplayView();
					            _setTabIndex();
							}
						});
	            	}
	            }
	        },
	    });
	} catch (e) {
		alert('referDeposit: ' + e.message);
	}
}

/**
 * refer with rcv_no
 *
 * @author		:	ANS817 - 2018/01/12 - create
 * @params		:	null
 * @return		:	null
 */
function referRcv() {
	try {
		//clear all error
		_clearErrors();
		$('#DSP_deposit_yen_amt').removeClass('error-numeric');

		var deposit_no = $('#TXT_deposit_no').val().trim();
		var rcv_no     = $('#TXT_rcv_no').val().trim();
		var data = {
			deposit_no 	: deposit_no,
	    	rcv_no 		: rcv_no
	    };

	    $.ajax({
	        type        :   'POST',
	        url         :   '/deposit/deposit-detail/refer-rcv',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if(res.response == true) {
	            	setValueItems(res, obj_refer_mode.rcv_no);

	            	_setTabIndex();
	            	$('#TXT_rcv_no').focus();
	            }else{
	            	if (rcv_no == '') {
		            	clearAllItem(obj_refer_mode.rcv_no);

						_setTabIndex();
						$('#TXT_rcv_no').focus();
					} else {
						jMessage('W001',function(r){
							if(r){
				            	clearAllItem(obj_refer_mode.rcv_no);

								_setTabIndex();
	            				$('#TXT_rcv_no').focus();
							}
						});
					}
	            }
	        },
	    });
	} catch (e) {
		alert('referRcv: ' + e.message);
	}
}

/**
 * refer with invoice_no
 *
 * @author		:	ANS817 - 2018/01/17 - create
 * @params		:	null
 * @return		:	null
 */
function referInvoice() {
	try {
		//clear all error
		_clearErrors();
		$('#DSP_deposit_yen_amt').removeClass('error-numeric');

		var deposit_no = $('#TXT_deposit_no').val().trim();
		var inv_no     = $('#TXT_inv_no').val().trim();
		var data = {
			deposit_no 	: deposit_no,
	    	inv_no 		: inv_no
	    };

	    $.ajax({
	        type        :   'POST',
	        url         :   '/deposit/deposit-detail/refer-invoice',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if(res.response == true) {
	            	setValueItems(res, obj_refer_mode.invoice_no);

	            	_setTabIndex();
	            	$('#TXT_inv_no').focus();
	            }else{
	            	if (inv_no == '') {
		            	clearAllItem(obj_refer_mode.invoice_no);

						_setTabIndex();
						$('#TXT_inv_no').focus();
					} else {
						jMessage('W001',function(r){
							if(r){
				            	clearAllItem(obj_refer_mode.invoice_no);

								_setTabIndex();
	            				$('#TXT_inv_no').focus();
							}
						});
					}
	            }
	        },
	    });
	} catch (e) {
		alert('referInvoice: ' + e.message);
	}
}

/**
 * refer with client_cd
 *
 * @author		:	ANS817 - 2018/01/17 - create
 * @params		:	null
 * @return		:	null
 */
function referClient() {
	try {
		//clear all error
		_clearErrors();
		$('#DSP_deposit_yen_amt').removeClass('error-numeric');
		
		var deposit_no = $('#TXT_deposit_no').val().trim();
		var client_cd  = $('#TXT_client_cd').val().trim();
		var data = {
			deposit_no 	: deposit_no,
	    	client_cd 	: client_cd
	    };

	    $.ajax({
	        type        :   'POST',
	        url         :   '/deposit/deposit-detail/refer-client',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if(res.response == true) {
	            	setValueItems(res, obj_refer_mode.client_cd);

	            	_setTabIndex();
	            	$('#TXT_client_cd').focus();
	            }else{
	            	if (client_cd == '') {
		            	clearAllItem(obj_refer_mode.client_cd);

						_setTabIndex();
						$('#TXT_client_cd').focus();
					} else {
						jMessage('W001',function(r){
							if(r){
				            	clearAllItem(obj_refer_mode.client_cd);

								_setTabIndex();
	            				$('#TXT_client_cd').focus();
							}
						});
					}
	            }
	        },
	    });
	} catch (e) {
		alert('referClient: ' + e.message);
	}
}

/**
 * set value to items
 *
 * @author      :   ANS817 - 2018/01/17 - create
 * @param       : 	res - object
 * @param       : 	refer_mode - int
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function setValueItems(res, refer_mode) {
	try{
		//set data deposit
    	setValueDeposit(res.deposit_data, refer_mode);

    	if (refer_mode != obj_refer_mode.client_cd) {
	    	//set data rcv_header
	    	setValueRcvHeader(res.rcv_h_data);
	    	//set data rcv_detail
	    	$('#div-table-deposit').html(res.table_rcv_d);
	    }
	} catch(e) {
        alert('setValueItems: ' + e.message)
    }
}

/**
 * clear all item
 *
 * @author      :   ANS817 - 2017/12/13 - create
 * @param       : 	refer_mode - int
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function clearAllItem(refer_mode) {
	try{
		if (refer_mode == obj_refer_mode.deposit_no) {
			//clear operator_info
			$('#operator_info').html('');
		}

		//clear item
		setValueDeposit(data_empty_deposit, refer_mode, true);

		if (refer_mode != obj_refer_mode.client_cd) {
			setValueRcvHeader(data_empty_rcv_header);

			//remove all row in table
			$('#div-table-deposit tbody tr').remove();
		}
	} catch(e) {
        alert('clearAllItem: ' + e.message)
    }
}

/**
 * set value deposit to view
 *
 * @author		:	ANS817 - 2018/01/11 - create
 * @params		:	data - object
 * @params		:	refer_mode - int
 * @params		:	isClear - bool
 * @return		:	null
 */
function setValueDeposit(data, refer_mode, isClear) {
	try {
		if (data.length == 0) {
			return;
		}

		//refer with deposit_no
		if (refer_mode == obj_refer_mode.deposit_no) {
			if (isClear != true) {
				$('#TXT_deposit_no').val(data.deposit_no);
			}
			$('#TXT_deposit_date').val(data.deposit_date);
			$('#CMB_split_deposit_div').val(data.split_deposit_div);
			$('#TXT_initial_deposit_date').val(data.initial_deposit_date);
			$('#TXT_remittance_amt').val(data.remittance_amt);
			$('#TXT_fee_foreign_amt').val(data.fee_foreign_amt);
			$('#TXT_fee_yen_amt').val(data.fee_yen_amt);
			$('#DSP_arrival_foreign_amt').html(data.arrival_foreign_amt);
			$('#DSP_deposit_yen_amt').html(data.deposit_yen_amt);
			$('#TXT_exchange_rate').val(data.exchange_rate);
			$('#CMB_rate_confirm_div').val(data.rate_confirm_div);
			$('#TXT_notices').val(data.notices);
			$('#TXT_inside_remarks').val(data.inside_remarks);
		}

		//refer with deposit_no or invoice_no
		if (		refer_mode 	== obj_refer_mode.deposit_no 
				|| 	(refer_mode == obj_refer_mode.invoice_no && isClear != true)) {
			$('#TXT_inv_no').val(data.invoice_no);
		}

		//refer with deposit_no or invoice_no or rcv_no
		if (		refer_mode 	== obj_refer_mode.deposit_no 
				|| 	refer_mode 	== obj_refer_mode.invoice_no 
				|| 	(refer_mode == obj_refer_mode.rcv_no && isClear != true)) {
			$('#TXT_rcv_no').val(data.rcv_no);
		}

		//refer with deposit_no or invoice_no or rcv_no or client_cd
		if (	   	refer_mode 	== obj_refer_mode.deposit_no 
				|| 	refer_mode 	== obj_refer_mode.invoice_no 
				|| 	refer_mode 	== obj_refer_mode.rcv_no
				|| 	(refer_mode == obj_refer_mode.client_cd && isClear != true)) {
			$('#TXT_client_cd').val(data.cust_cd);
		}
		if (	   	refer_mode == obj_refer_mode.deposit_no 
				|| 	refer_mode == obj_refer_mode.invoice_no 
				|| 	refer_mode == obj_refer_mode.rcv_no
				|| 	refer_mode == obj_refer_mode.client_cd) {
			// $('.client_nm').html(data.client_nm);
			$('#CMB_deposit_div').val(data.deposit_div);
			$('#CMB_deposit_bank_div').val(data.deposit_bank_div);
			$('#TXT_client_country_div').val(data.country_div);
			//$('.country_nm').html(data.country_div_nm);
			$('#CMB_currency_div').val(data.currency_div);
			$('#CMB_currency_div').trigger('change', [true]);
			$('#CMB_deposit_way_div').val(data.deposit_way_div);
			$('.DSP_currency_div.deposit_currency_div').html(data.currency_div_nm);
		}

		//refer client_nm
    	_getClientName($('#TXT_client_cd').val().trim(), $('#TXT_client_cd'), function() {
    		switch (refer_mode) {
    			case obj_refer_mode.deposit_no:
    				$(':input:visible:not([disabled]):not([readonly]):first').focus();
    				break;
    			case obj_refer_mode.invoice_no:
    				$('#TXT_inv_no').focus();
    				break;
    			case obj_refer_mode.rcv_no:
    				$('#TXT_rcv_no').focus();
    				break;
    			case obj_refer_mode.client_cd:
    				$('#TXT_client_cd').focus();
    				break;
    		}
    	}, true);
    	//refer country_nm
    	_referCountry($('#TXT_client_country_div').val(), '', $('#TXT_client_country_div'), function() {
    		switch (refer_mode) {
    			case obj_refer_mode.deposit_no:
    				$(':input:visible:not([disabled]):not([readonly]):first').focus();
    				break;
    			case obj_refer_mode.invoice_no:
    				$('#TXT_inv_no').focus();
    				break;
    			case obj_refer_mode.rcv_no:
    				$('#TXT_rcv_no').focus();
    				break;
    			case obj_refer_mode.client_cd:
    				$('#TXT_client_cd').focus();
    				break;
    		}
    	}, true);
	} catch (e) {
		alert('setValueDeposit: ' + e.message);
	}
}

/**
 * set value rcv_header to view
 *
 * @author		:	ANS817 - 2018/01/11 - create
 * @params		:	data - object
 * @return		:	null
 */
function setValueRcvHeader(data) {
	try {
		if (data.length == 0) {
			return;
		}

		$('#DSP_total_amt').html(data.total_amt);
		$('.DSP_currency_div.rcv_currency_div').html(data.currency_div_nm);
		calculateTotalEnteredAmt(data.total_amount_entered);
		$('#DSP_total_entered_amt').attr('total_entered_amt_db', data.total_amount_entered);
	} catch (e) {
		alert('setValueRcvHeader: ' + e.message);
	}
}

/**
 * set value rcv_header to view
 *
 * @author		:	ANS342 - 2018/05/18 - create
 * @params		:	data - object
 * @return		:	null
 */
function setMode(mode) {
	try {
		if (mode == 'U'){
			$('.TXT_deposit_no').removeAttr('readonly');
			$('.TXT_deposit_no').parent().addClass('popup-deposit-search');
			$('.popup-deposit-search').find('.btn-search').attr('disabled', false);
			parent.$('.popup-fwd-search').removeClass('popup-deposit-search');
			$(".TXT_fwd_no").addClass("required");
		} else {
			$('.TXT_deposit_no').attr('readonly');
			$('.TXT_deposit_no').val('');
			$('.TXT_deposit_no').parent().addClass('popup-deposit-search');
			$('.popup-deposit-search').find('.btn-search').attr('disabled', true);
			parent.$('.popup-fwd-search').removeClass('popup-deposit-search');
		}
	} catch (e) {
		alert('setValueRcvHeader: ' + e.message);
	}
}