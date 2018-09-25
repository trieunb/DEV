/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	DuyTP
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
 //set tax
var current_date 	= new Date().toJSON().slice(0,10).replace(/-/g,'/');
$('.TXT_buy_date').val(current_date);
_getTaxRate(current_date);
//set constant
var purchase_currency				=	_constVal1['purchase_currency'];			
var purchase_exchange_rate			=	_constVal1['purchase_exchange_rate'];
var purchase_detail_amt_round_div	=	_constVal1['purchase_detail_amt_round_div'];
var purchase_detail_tax_round_div	=	_constVal1['purchase_detail_tax_round_div'];
var purchase_summary_tax_round_div	=	_constVal1['purchase_summary_tax_round_div'];
//message code error
var error_key						=	'E005';
$(document).ready(function () {
	initEvents();
	if (mode != 'I') {
		$(".TXT_buy_no").addClass("required");
		if ($(".TXT_buy_no").val() != '') {
			$(".TXT_buy_no").trigger('change');
		}
	} else {
		disableBuyNo();
		$(".TXT_buy_no").removeClass("required");
		$(".TXT_buy_no").val('');
	}
});

/**
 * init Events
 * @author  :   DuyTP - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//init 1 row table at mode add new (I)
		_initRowTable('table-purchase', 'table-row', 1);
		//drap and drop row table
		_dragLineTable('table-purchase', true);
		//init back
		$(document).on('click', '#btn-back', function () {
			sessionStorage.setItem('detail', true);
			location.href = '/purchase-request/purchase-request-search';
		});
		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				if (mode !== 'I') {
					var msg = 'C003';
				} else {
					var msg = 'C001';
				}
				if(validateHeader()){
					var _row_detail = $('#table-purchase tbody tr').length;
					if(_row_detail > 0) {
						if (!validateDetail(is_e004 = true)) {
								jMessage('E004', function(ok) {
									validateDetail(is_e004 = false);
								});
						} else {
							if (validateDetail(is_e004 = false)) {
								if (validateErrorNumericDetail()) {
									jMessage(msg, function(r) {
										if (r) {
											savePurchaseRequest();
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
				alert('#btn-save ' + e.message);
			}
		});
		// button delete
		$(document).on('click', '#btn-delete', function() {
			try {
				if(validateBuyNo()){
					jMessage('C002', function(r) {
						if (r) {
							deletePurchaseRequest();
						}
					});
				}
			} catch (e) {
				alert('#btn-delete ' + e.message);
			}
		});
 		//btn print
 		$(document).on('click', '#btn-issue', function(){
 			if(validateBuyNo()){
	 			jMessage('C004',  function(r) {
					if (r) {
						purchaseRequestExport();
					}
				});
 			}
 		});
 		//btn approve
 		$(document).on('click', '#btn-approve', function(){
 			if(validateApprove()){
				jMessage('C005', function(r) {
					if (r) {
						approvePurchaseRequest();
					}
				});
			}
 		});
		// remove row table
		$(document).on('click','.remove-row',function(e){
			var obj   = $(this);
			jMessage('C002', function(r) {
				if(r) {
					obj.closest('tr').remove();
					_updateTable('table-purchase', true);
					//cal total detail amt
					calTotalDetailAmt();
					//cal total detail tax
					calTotalDetailTax()
					//cal total amount
					calTotalAmt();
					$('.table-purchase tbody tr:last :input:first').focus();
				}
			});
		});

		//add row
		$(document).on('click', '#btn-add-row', function () {
			try {
				_addNewRowTable('table-purchase', 'table-row', 30, function() {
					_updateTable('table-purchase', true);
				});
			} catch (e) {
				alert('add new row' + e.message);
			}

		});
 		//change TXT_buy_no  
		$(document).on('change', '.TXT_buy_no', function() {
			if ($(this).val().trim() == '') {
				setItemBuyH('');
				//init 1 row table at mode add new (I)
				_initRowTable('table-purchase', 'table-row', 1);
				$('.TXT_buy_date').val(current_date);
				var date = current_date;
				_getTaxRate(date)
			} else {
				var data = {
					buy_no : $(this).val().trim()
				};
				referPurchaseRequestDetail(data, $(this), '', true);
			}
			
		});
		//change TXT_pi_date  
		$(document).on('change', '.TXT_buy_date  ', function(e) {
			var date = $(this).val();
			console.log(date);
			_getTaxRate(date, function() {
				//cal total detail tax
				calTotalDetailTax()
				//cal total amount
				calTotalAmt();
			});
		});
		//change TXT_supplier_cd  div  
		$(document).on('change', '.TXT_supplier_cd ', function() {
			getClientName($(this).val().trim(), $(this), '', false);
		});
		//change TXT_buy_qty
 		$(document).on('change', '.TXT_buy_qty', function() {
 			var parents = $(this).parents('#table-purchase tbody tr');
 			parents.addClass('cal-refer-pos');
 			//cal buy detail amt
 			calBuyDetailAmt('cal-refer-pos');
 			//cal buy detail tax
 			calBuyDetailTax('cal-refer-pos');
 			//remover class parent
			parent.$('.cal-refer-pos').removeClass('cal-refer-pos');
			//cal total detail amt
			calTotalDetailAmt();
			//cal total detail tax
			calTotalDetailTax()
			//cal total amount
			calTotalAmt();
			validateBuyAmtDetail($(this));
 		});
 		//change TXT_buy_unit_price
 		$(document).on('change', '.TXT_buy_unit_price', function() {
 			var parents = $(this).parents('#table-purchase tbody tr');
 			parents.addClass('cal-refer-pos');
 			//cal buy detail amt
 			calBuyDetailAmt('cal-refer-pos');
 			//cal buy detail tax
 			calBuyDetailTax('cal-refer-pos');
 			//remover class parent
			parent.$('.cal-refer-pos').removeClass('cal-refer-pos');
			//cal total detail amt
			calTotalDetailAmt();
			//cal total detail tax
			calTotalDetailTax()
			//cal total amount
			calTotalAmt();
			validateBuyAmtDetail($(this));
 		});
 		//change TXT_parts_cd
 		$(document).on('change', '.TXT_parts_cd', function() {
 			_clearErrors();
 			var data = {
 				parts_cd 	: 	$(this).val(),
 				supplier_cd : 	$('.TXT_supplier_cd ').val()
 			};
 			var parents = $(this).parents('#table-purchase tbody tr');
 			parents.addClass('cal-refer-pos');
 			//cal buy detail amt
 			referParts(data, 'cal-refer-pos');
 		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * validate header
 *
 * @author		:	Trieunb - 2018/02/07 - create
 * @params		:	null
 * @return		:	null
 */
function validateHeader() {
	try {
		var element = $('body');
		var error = 0;
		_clearErrors();
		element.find('.required:not([readonly])').each(function() {
			if ($(this).is(':visible')) {
				if (!$(this).hasClass('TXT_parts_cd') && !$(this).hasClass('TXT_buy_qty')) {
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
				}
			}
		});
		if( error > 0 ) {
			return false;
		} else {
			return true;
		}
	} catch (e) {
		alert('validateHeader: ' + e.message);
	}
}
/**
 * validate detail
 *
 * @author		:	Trieunb - 2018/02/07 - create
 * @params		:	null
 * @return		:	null
 */
function validateDetail(is_e004) {
	try {
		var element = $('#table-purchase tbody tr');
		var error = 0;
		_clearErrors();
		var flag 	= false;
		if (is_e004) {
			element.each(function() {
				error 	= 0;
				$(this).find('.required:enabled:not([readonly])').each(function() {
					if ($(this).is(':visible')) {
						if(($(this).is("input") || $(this).is("textarea")) &&  $.trim($(this).val()) !== '' ) {
							error ++;
						} else if( $(this).is("select") &&  ($(this).val() !== '') ) {
							error ++;
						} else if($(this).is("input[type=checkbox]") && !$(this).is(":checked")){
				            error ++;
				        }
				        if( error == 2 ) {
							flag 	= true;
						}
					}
				});
			});
		} else {
			element.find('.required:enabled:not([readonly])').each(function() {
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
			if( error > 0 ) {
				flag 	= false;
			} else {
				flag 	= true;
			}
		}
		return flag;
	} catch (e) {
		alert('validateDetail: ' + e.message);
	}
}
/**
 * get data of input
 * 
 * @author : ANS806 - 2017/11/15 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getData() {
	try {
		var _data = [];
		var sum_total_detail_tax   =   0;
		$('#table-purchase tbody tr').each(function() {

			var _buy_detail_no 		=	($(this).find('.DSP_buy_detail_no').text() == "") ? 0 : $(this).find('.DSP_buy_detail_no').text();
			var _parts_cd 			=	$(this).find('.TXT_parts_cd').val();
			var _buy_qty 			=	($(this).find('.TXT_buy_qty').val() == "") ? 0 : $(this).find('.TXT_buy_qty').val().replace(/,/g, '');
			var _buy_unit_price 	=	($(this).find('.TXT_buy_unit_price ').val() == "") ? 0 : $(this).find('.TXT_buy_unit_price').val().replace(/,/g, '');
			var _buy_detail_amt 	=	($(this).find('.TXT_buy_detail_amt').val() == "") ? 0 : $(this).find('.TXT_buy_detail_amt').val().replace(/,/g, '');
			var _buy_detail_tax 	=	($(this).find('.TXT_buy_detail_tax').val() == "") ? 0 : $(this).find('.TXT_buy_detail_tax').val().replace(/,/g, '');
			var _detail_remarks		=	($(this).find('.TXT_detail_remarks').val() == "") ? '' : $(this).find('.TXT_detail_remarks').val().replace(/,/g, '');
			//get data table buy detail
			sum_total_detail_tax	=	parseFloat(sum_total_detail_tax) + parseFloat(_buy_detail_tax) ;
			var _t_buy_d = {
					'buy_detail_no' 		: parseInt(_buy_detail_no),
					'parts_cd' 				: _parts_cd,
					'buy_qty' 				: parseInt(_buy_qty),
					'buy_unit_price' 		: parseFloat(_buy_unit_price),
					'buy_detail_amt' 		: parseFloat(_buy_detail_amt),
					'buy_detail_tax' 		: parseFloat(_buy_detail_tax),
					'detail_remarks' 		: _detail_remarks,
				};
			_data.push(_t_buy_d);

		});
		//<Footer>
		var _total_detail_amt 		=	($('.DSP_total_detail_amt').text() == "") ? 0 : $('.DSP_total_detail_amt').text().replace(/,/g, '');
		var _tax_amt 				=	($('.DSP_tax_amt').text() == "") ? 0 : $('.DSP_tax_amt').text().replace(/,/g, '');
		var _total_amt 				=	($('.DSP_total_amt').text()  == "") ? 0 : $('.DSP_total_amt').text().replace(/,/g, '');
		var buy_status 				= 	'10';
		if (mode !== 'I') {
			buy_status = $('.TXT_buy_status').val();
		}
		var STT_data = {
				'mode'					: mode, 
				'buy_no'				: $('.TXT_buy_no').val(),
				'buy_status'			: buy_status,
				'buy_date'				: $('.TXT_buy_date').val(),
				//<Header>
				'supplier_cd '			: $('.TXT_supplier_cd').val(),
				'supplier_staff_nm '	: $('.TXT_supplier_staff_nm').val(),
				'subject_nm '			: $('.TXT_subject_nm').val(),
				'hope_delivery_date  '	: $('.TXT_hope_delivery_date').val(),
				'remarks  '				: $('.TXT_remarks').val(),
				//<Detail> data type json
				't_buy_d' 				: _data,
				//<Total>
				'total_detail_amt'		: parseFloat(_total_detail_amt),
				'total_detail_tax'		: parseFloat(sum_total_detail_tax),
				'tax_amt'				: parseFloat(_tax_amt),
				'total_amt'				: parseFloat(_total_amt)
			};
		return STT_data;
	} catch(e) {
        console.log('getData' + e.message)
    }
}
/**
 * save data all - insert/update
 * 
 * @author : ANS806 - 2017/11/15 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function savePurchaseRequest() {
	try{
	    var data = getData();
	    $.ajax({
	        type        :   'POST',
	        url         :   '/purchase-request/purchase-request-detail/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd, function(ok) {
	            			if (ok) {
	            				itemErrorsE005(res.errors_item, res.error_list);
	            			}
	            		});
	            	} else {
	            		var msg = (mode == 'I') ? 'I001' : 'I003';
	            		jMessage(msg, function(r){
		                	if(r){
		                		mode	=	'U';
		                		var data = {
		                			buy_no 		: res.buy_no,
		                			mode 		: mode,
		                		}
		                		referPurchaseRequestDetail(data);
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
        console.log('postSave' + e.message)
    }
}
/**
 * refer pi infomation
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referPurchaseRequestDetail(data, callback) {
	try	{
		//clear all error
		_clearErrors();
		//
		$.ajax({
			type 		: 'GET',
			url 		: '/purchase-request/refer-purchase-request-detail',
			dataType	: 'json',
			data 		: data,
			success: function(res) {
				if (res.response) {
					//set refer data for item
					setItemBuyH(res.buy_h);
					var date = $('.TXT_buy_date').val();
					_getTaxRate(date)
					$('.heading-btn-group').html(res.button);
					$('#div-purchase-request').html(res.html_buy_d);
					if ($('#table-purchase tbody tr').length == 0) {
						//init 1 row table at mode add new (I)
						_initRowTable('table-purchase', 'table-row', 1);
					}
					$('.infor-created').html(res.header_html);
					//init table index
					_setTabIndex();
					//drap and drop row table
					var param = {
						'mode'		: mode,
						'from'		: 'PurchaseRequestDetail',
						'buy_no'	: data.buy_no,
					};
					_postParamToLink(from, 'PurchaseRequestDetail', '', param)
					_clearErrors();
					if(res.status == 'A'){
						_disabldedAllInput();
					}
					// $('.infor-created .heading-elements').removeClass('hidden');
				} else {
					if ($('.TXT_buy_no').val() !== '') {
						jMessage('W001', function(ok) {
							if (ok) {
								setItemBuyH('');
								//init 1 row table at mode add new (I)
								_initRowTable('table-purchase', 'table-row', 1);
								var date = $('.TXT_buy_date').val();
								_getTaxRate(date)
							}
						});
					}
				}
				if (typeof callback == 'function') {
					callback();
				}
			}
		});
	} catch (e) {
		alert('referPurchaseRequestDetail: ' + e.message);
	}
}
/**
 * refer product pi detail
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referParts(data, pos) {
	try	{
		$.ajax({
			type 		: 'GET',
			url 		: '/purchase-request/refer-parts',
			dataType	: 'json',
			data 		: data,
			success: function(res) {
				var data = '';
				if (res.response) {
					data 	=	res.data;
				}
				setItemReferParts(data, pos)
			}
		});
	} catch (e) {
		alert('referProduct: ' + e.message);
	}
}
/**
 * delete buy detail
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function deletePurchaseRequest() {
	try {
		var _buy_no = $('.TXT_buy_no').val();
		$.ajax({
	        type        :   'POST',
	        url         :   '/purchase-request/purchase-request-detail/delete',
	        dataType    :   'json',
	        data        :   {buy_no : _buy_no},
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		jMessage('I002', function(r){
		                	if(r){
		                		setItemBuyH('', true);
		                		//init 1 row table at mode add new (I)
								_initRowTable('table-purchase', 'table-row', 1);
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
        console.log('deletebuy' + e.message)
    }
}
/**
 * approve buy detail
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function approvePurchaseRequest() {
	try {
		var data = {
			buy_no 				: 	$('.TXT_buy_no').val(),
			supplier_cd 		: 	$('.TXT_supplier_cd').val(),
		}
		$.ajax({
	        type        :   'POST',
	        url         :   '/purchase-request/purchase-request-detail/approve',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd, function(ok) {
	            			if (ok) {
	            				itemErrorsE005(res.errors_item, '');
	            			}
	            		});
	            	} else {
	            		jMessage('I005', function(r){
		                	if(r){
		                		var data = {
		                			buy_no 		: res.buy_no,
		                			mode 		: mode,
		                		}
		                		referPurchaseRequestDetail(data);
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
        console.log('approvebuy' + e.message)
    }
}
/**
 * print buy detail
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function purchaseRequestExport() {
	try {
		var _data = [
			$('.TXT_buy_no').val()
		];
		var data = {
			buy_list 	: 	_data
		}
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/purchase-request-detail-export',
	        dataType    :   'json',
	        data        :   data,
	        loading     :   true,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
		            	location.href = res.fileName;
		            	jMessage('I004');
	            	}
	            } else {
	            	//catch DB error and display
	            	var msg_e999 = _text['E999'].replace('{0}', res.error);
	            	jMessage_str('E999', msg_e999, '', msg_e999);
	            }
	        },
	    });
	}  catch(e) {
        console.log('buyExport' + e.message)
    }
}
/**
 * cal buy detail amt
 * 
 * @author : ANS806 - 2018/02/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calBuyDetailAmt(parent_element) {
	try {
		var _buy_qty  			= 	parent.$('.'+parent_element).find('.TXT_buy_qty ').val().replace(/,/g, '');
		var _buy_unit_price 	=	parent.$('.'+parent_element).find('.TXT_buy_unit_price').val().replace(/,/g, '');
		var _buy_detail_amt 	=	parseFloat(_buy_qty)*parseFloat(_buy_unit_price);
			_buy_detail_amt 	= 	_roundNumeric(_buy_detail_amt.toFixed(3), purchase_detail_amt_round_div, 2);
		if (isNaN(_buy_detail_amt)) {
			_buy_detail_amt = 0;
		}
		parent.$('.'+parent_element).find('.TXT_buy_detail_amt').val(_convertMoneyToIntAndContra(_roundNumeric(_buy_detail_amt, purchase_detail_amt_round_div, 0)));
	} catch(e) {
        console.log('calBuyDetailAmt' + e.message)
    }
}
/**
 * cal buy detail tax
 * 
 * @author : ANS806 - 2018/02/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calBuyDetailTax(parent_element) {
	try {
		var _buy_detail_amt 	=	parent.$('.'+parent_element).find('.TXT_buy_detail_amt').val().replace(/,/g, '');
		var _tax 				=	$('.tax_rate').text().replace(/,/g, '');
		var _buy_detail_tax 	=	parseFloat(_buy_detail_amt) * parseFloat(_tax);
			_buy_detail_tax 	= 	_roundNumeric(_buy_detail_tax.toFixed(3), purchase_detail_tax_round_div);
		if (isNaN(_buy_detail_tax)) {
			_buy_detail_tax = 0;
		}
		parent.$('.'+parent_element).find('.TXT_buy_detail_tax').val(_convertMoneyToIntAndContra(_buy_detail_tax));
	} catch(e) {
        console.log('calBuyDetailTax' + e.message)
    }
}
/**
 * cal total detail amt
 * 
 * @author : ANS806 - 2018/02/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalDetailAmt() {
	try {
		var _total_detail_amt 	= 0;
		$('#table-purchase tbody tr').each(function() {
			var _buy_detail_amt = $(this).find('.TXT_buy_detail_amt').val();
				_buy_detail_amt = _buy_detail_amt.replace(/,/g, '');
			if (_buy_detail_amt != '') {
				_total_detail_amt = (parseFloat(_total_detail_amt) +  parseFloat(_buy_detail_amt));
			}
		});
		_total_detail_amt 	= 	_roundNumeric(_total_detail_amt.toFixed(3), purchase_summary_tax_round_div, 2);
		$('.DSP_total_detail_amt').text(_convertMoneyToIntAndContra(_total_detail_amt));
	} catch(e) {
        console.log('calTotalDetailAmt' + e.message)
    }
}
/**
 * cal total detail tax
 * 
 * @author : ANS806 - 2018/02/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalDetailTax() {
	try {
		var _total_detail_amt 	= 0;
		$('#table-purchase tbody tr').each(function() {
			var _buy_detail_amt = $(this).find('.TXT_buy_detail_amt').val();
				_buy_detail_amt = _buy_detail_amt.replace(/,/g, '');
			if (_buy_detail_amt != '') {
				_total_detail_amt = parseFloat(_total_detail_amt) +  parseFloat(_buy_detail_amt);
			}
		});
		var _tax 				=	parseFloat($('.tax_rate').text().replace(/,/g, ''));
		var _total_detail_tax 	=	parseFloat(_total_detail_amt) * parseFloat(_tax);
			_total_detail_tax 	= 	_roundNumeric(_total_detail_tax.toFixed(3), purchase_summary_tax_round_div);
		if (isNaN(_total_detail_tax)) {
			_total_detail_tax = 0;
		}
		$('.DSP_tax_amt').text(_convertMoneyToIntAndContra(_total_detail_tax));
	} catch(e) {
        console.log('calTotalDetailAmt' + e.message)
    }
}
/**
 * cal total amt
 * 
 * @author : ANS806 - 2018/02/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalAmt() {
	try {
		var _total_detail_amt 	=	$('.DSP_total_detail_amt').text().replace(/,/g, '');
		var _tax_amt 			=	$('.DSP_tax_amt').text().replace(/,/g, '');
		var _total_amt 			=	parseFloat(parseFloat(_total_detail_amt) + parseFloat(_tax_amt));
		if (isNaN(_total_amt)) {
			_total_amt = 0;
		}
		$('.DSP_total_amt').text(_convertMoneyToIntAndContra(_total_amt));
	} catch(e) {
        console.log('calTotalAmt' + e.message)
    }
}
/**
 * item Errors E005
 * 
 * @author : ANS806 - 2018/02/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function itemErrorsE005(errors_item, error_list) {
	try {
		if (errors_item.buy_no == error_key) {
			$('.TXT_buy_no').errorStyle(_text['E005']);
		}
		if (errors_item.supplier_cd == error_key) {
			$('.TXT_supplier_cd').errorStyle(_text['E005']);
		}
		// check e005 for table detail pi
		var detail 	= $('#table-purchase tbody tr');
		if (error_list.length > 0) {
			detail.each(function() {
				if ($(this).is(':visible')) {
					var parts 		=	$(this).find('.TXT_parts_cd');
					var parts_cd 	=	parts.val().trim();
					$.each(error_list, function(i, item) {
					    if (item.product_cd === parts_cd) {
					    	parts.errorStyle(_text[item.message_error]);
					    }
					});
				}
			});
		}
		if (errors_item.buy_no == error_key) {
			if (!$('input').hasClass('error-item')) {
				$('.TXT_buy_no').errorStyle(_text['E005']);
			}
		}
	} catch (e) {
		alert('itemErrorsE005: ' + e.message);
	}
}
/**
 * set data refer for in screen
 * 
 * @author : ANS806 - 2018/02/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setItemBuyH(data, is_del) {
	try {
		disableBuyNo();
		if (data != '') {
			$('.TXT_buy_no').val(data.buy_no);
			$('.TXT_buy_date').val(data.buy_date);
			$('.DSP_status').text(data.buy_status_nm);
			$('.DSP_buy_status_cre_datetime').text(data.status_cre_datetime);
			$('.TXT_buy_status').val(data.buy_status_div);
			$('.TXT_supplier_cd').val(data.supplier_cd);
			$('.DSP_supplier_nm').val(data.client_nm);
			$('.suppliers_nm').text(data.client_nm);
			$('.TXT_supplier_staff_nm').val(data.supplier_staff_nm);
			$('.TXT_subject_nm').val(data.subject_nm);
			$('.TXT_hope_delivery_date').val(data.hope_delivery_date);
			$('.TXT_remarks').val(data.remarks);
			$('.DSP_total_detail_amt').text(data.total_detail_amt);
			$('.DSP_tax_amt').text(data.total_tax);
			$('.DSP_total_amt').text(data.total_amt);
		} else {
			if (is_del) {
				$('.TXT_buy_no').val('');
			}
			$('.TXT_buy_date').val('');
			$('.DSP_status').text('');
			$('.DSP_buy_status_cre_datetime').text('');
			$('.TXT_buy_status').val('');
			$('.TXT_supplier_cd').val('');
			$('.suppliers_nm').text('');
			$('.DSP_supplier_nm').text('');
			$('.TXT_supplier_staff_nm').val('');
			$('.TXT_subject_nm').val('');
			$('.TXT_hope_delivery_date').val('');
			$('.TXT_remarks').val('');
			$('.DSP_total_detail_amt').text('');
			$('.DSP_tax_amt').text('');
			$('.DSP_total_amt').text('');

			$('.infor-created').html('');
		}
	} catch (e) {
		alert('setItemBuyH: ' + e.message);
	}
}
/**
 * set data refer for in screen
 * 
 * @author : ANS806 - 2018/02/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setItemReferParts(data, pos) {
	try {
		if (data != '') {
			parent.$('.'+pos).find('.DSP_unit').text(data.lib_val_nm_j);
			parent.$('.'+pos).find('.TXT_buy_unit_price').val(data.purchase_unit_price_JPY);

			parent.$('.'+pos).find('.DSP_item_nm_j .tooltip-overflow').attr('title', data.item_nm_j);
			parent.$('.'+pos).find('.DSP_item_nm_j .tooltip-overflow').text(data.item_nm_j);
			parent.$('.'+pos).find('.DSP_specification .tooltip-overflow').attr('title', data.specification);
			parent.$('.'+pos).find('.DSP_specification .tooltip-overflow').text(data.specification);
		} else {
			parent.$('.'+pos).find('.DSP_unit').text('');

			parent.$('.'+pos).find('.DSP_item_nm_j .tooltip-overflow').attr('title', '');
			parent.$('.'+pos).find('.DSP_item_nm_j .tooltip-overflow').text('');
			parent.$('.'+pos).find('.DSP_specification .tooltip-overflow').attr('title', '');
			parent.$('.'+pos).find('.DSP_specification .tooltip-overflow').text('');
		}
		//cal buy detail amt
		calBuyDetailAmt(pos);
		//cal buy detail tax
		calBuyDetailTax(pos);
		//remover class parent
		parent.$('.cal-refer-pos').removeClass(pos);
		//cal total detail amt
		calTotalDetailAmt();
		//cal total detail tax
		calTotalDetailTax()
		//cal total amount
		calTotalAmt();
	} catch (e) {
		alert('setItemReferParts: ' + e.message);
	}
}
/**
 * disable item key buy no by mode
 * 
 * @author : ANS806 - 2018/02/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function disableBuyNo() {
	try {
		if (mode == 'I') {
			$('.TXT_buy_no').attr('disabled', true);
			$('.TXT_buy_no').parent().addClass('popup-buy-search')
			$('.popup-buy-search').find('.btn-search').attr('disabled', true);
			parent.$('.popup-buy-search').removeClass('popup-buy-search');
		} else {
			$('.TXT_buy_no').attr('disabled', false);
			$('.TXT_buy_no').parent().addClass('popup-buy-search')
			$('.popup-buy-search').find('.btn-search').attr('disabled', false);
			parent.$('.popup-buy-search').removeClass('popup-buy-search');
			$(".TXT_buy_no").addClass("required");
		}
	} catch (e) {
		alert('disableBuyNo: ' + e.message);
	}
}
/**
 * validate key no
 * 
 * @author : ANS806 - 2018/02/22 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function validateBuyNo() {
	try {
		_clearErrors();
		var error 	= true;
		if ($('.TXT_buy_no').val() == '') {
			$('.TXT_buy_no').errorStyle(_MSG_E001);
			error 	= false;
		}
		return error;
	} catch (e) {
		alert('validateBuyNo: ' + e.message);
	}
}
/**
 * validate key no
 * 
 * @author : ANS806 - 2018/02/22 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function validateApprove() {
	try {
		_clearErrors();
		var error 	= true;
		if ($('.TXT_buy_no').val() == '') {
			$('.TXT_buy_no').errorStyle(_MSG_E001);
			error 	= false;
		}
		if ($('.TXT_supplier_cd').val() == '') {
			$('.TXT_supplier_cd').errorStyle(_MSG_E001);
			error 	= false;
		}
		return error;
	} catch (e) {
		alert('validateApprove: ' + e.message);
	}
}
/**
 * purchase request export
 * 
 * @author : ANS806 - 2018/02/23 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function purchaseRequestExport() {
	try {
		var data = {
			buy_no 			: 	$('.TXT_buy_no').val(),
			buy_status_div 	: 	$('.TXT_buy_status').val()
		}
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/purchase-request-detail-export',
	        dataType    :   'json',
	        loading     :   true,
	        data        :   {buy_list : data},
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
		            	location.href = res.fileName;
		            	jMessage('I004');
	            	}
	            } else {
	            	//catch DB error and display
	            	var msg_e999 = _text['E999'].replace('{0}', res.error);
	            	jMessage_str('E999', msg_e999, '', msg_e999);
	            }
	        },
	    });
	}  catch(e) {
        console.log('purchaseRequestExport' + e.message)
    }
}
function removeDuplicates(arr) {
    var obj = {};
    var ret_arr = [];
    for (var i = 0; i < arr.length; i++) {
        obj[arr[i]] = true;
    }
    for (var key in obj) {
        ret_arr.push(key);
    }
    return ret_arr;
}
function validateBuyAmtDetail(element) {
	try {
		var parent 				= element.parents('#table-purchase tbody tr');
		var buy_detail_amt 		= parent.find('.TXT_buy_detail_amt').val().replace(/,/g, '');
			buy_detail_amt 		= parseFloat(buy_detail_amt);

		var flag_buy_detail_amt 	=	false;
		if (buy_detail_amt < -9999999999999.99 || buy_detail_amt > 9999999999999.99) {
			parent.find('.TXT_buy_detail_amt').addClass('error-numeric');
			flag_buy_detail_amt	=	true;
		} else {
			parent.find('.TXT_buy_detail_amt').removeClass('error-numeric');
		}

		if (flag_buy_detail_amt) {
			parent.find('.TXT_buy_qty').addClass('error-numeric');
		} else {
			parent.find('.TXT_buy_qty').removeClass('error-numeric');
		}

		if (flag_buy_detail_amt) {
			parent.find('.TXT_buy_unit_price').addClass('error-numeric');
		} else {
			parent.find('.TXT_buy_unit_price').removeClass('error-numeric');
		}
	} catch (e) {
		alert('validateBuyAmtDetail: ' + e.message);
	}
}
function validateErrorNumericDetail() {
	try {
		_clearErrors();
		var detail 	= $('#table-purchase tbody tr');
		var error 	= 0;
		if (detail.find('.error-numeric').length > 0) {
			error ++;
		}
		if( error > 0 ) {
			return false;
		} else {
			return true;
		}
	} catch (e) {
		alert('validateErrorNumericDetail: ' + e.message);
	}
}
/**
 * getClientName
 * 
 * @author : ANS804 - 2017/12/26 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getClientName(client_cd, element, callback, deleteKey) {
    try {
    	if (deleteKey == undefined) {
			deleteKey = false;
		}

        $.ajax({
            type        :   'POST',
            url         :   '/purchase-request/purchase-request-detail/refer-supplier',
            dataType    :   'json',
            data 		: 	{
            					client_cd : client_cd
            				},
            success: function(res) {
        		//remove error
        		_removeErrorStyle(element.parents('.popup').find('.TXT_client_cd'));
                if (res.response) {
                	if (res.data != null) {
                		element.parents('.popup').find('.TXT_client_cd').val(res.data['client_cd']);
                		element.parents('.popup').find('.client_nm').text(res.data['client_nm']);
                		$('.TXT_supplier_staff_nm').val(res.data['client_staff_nm']);
                	} else {
                		if (deleteKey) {
	                		element.parents('.popup').find('.TXT_client_cd').val('');
	                	}
                		element.parents('.popup').find('.client_nm').text('');
                		$('.TXT_supplier_staff_nm').val('');
                	}
                } else {
                	if (deleteKey) {
                		element.parents('.popup').find('.TXT_client_cd').val('');
                	}
            		element.parents('.popup').find('.client_nm').text('');
            		$('.TXT_supplier_staff_nm').val('');
                }

                element.parents('.popup').find('.TXT_client_cd').focus();

                // check callback function
				if (typeof callback == 'function') {
					callback();
				}
            }
        });
    } catch (e) {
        console.log('getClientName' + e.message);
    }
}
