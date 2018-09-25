/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/03/14
 * 作成者		:	ANS806 - Trieunb
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
var current_date 	= new Date().toJSON().slice(0,10).replace(/-/g,'/');
var error_key		=	'E005';
$(document).ready(function () {
 	initCombobox()
	initEvents();
	init();
	eventCalTotalCarton();
	$('.CMB_payment_conditions_div').trigger('change');		

});
function initCombobox() {
	var name = 'JP';
	// _getComboboxData(name, 'port_city_div');
	// _getComboboxData(name, 'port_country_div');
	// _getComboboxData(name, 'shipment_div');
	// _getComboboxData(name, 'currency_div');
	// _getComboboxData(name, 'trade_terms_div');
	// _getComboboxData(name, 'payment_conditions_div');
	// _getComboboxData(name, 'sales_detail_div');
	// _getComboboxData(name, 'unit_q_div');
	// _getComboboxData(name, 'unit_w_div');
	// _getComboboxData(name, 'unit_m_div');
	// _getComboboxData(name, 'storage_manager_div');

	var nameTax = $('.country_id').val();
	//_addCountryTradeTableTotal(nameTax);
}
function changeNmCombobox(name) {
	_changeNmCombobox(name, 'port_city_div');
	_changeNmCombobox(name, 'port_country_div');
	_changeNmCombobox(name, 'shipment_div');
	_changeNmCombobox(name, 'currency_div');
	_changeNmCombobox(name, 'trade_terms_div');
	_changeNmCombobox(name, 'payment_conditions_div');
	_changeNmCombobox(name, 'unit_q_div');
	_changeNmCombobox(name, 'unit_w_div');
	_changeNmCombobox(name, 'unit_m_div');
	_changeNmCombobox(name, 'sales_detail_div');
	_changeNmCombobox(name, 'bank_div');
}
function init() {
	var date = $('.TXT_sales_date').val();
	_getTaxRate(date)
	if (mode != 'I') {
		$(".TXT_inv_no").addClass("required");
		if ($(".TXT_inv_no").val() != '') {
			$(".TXT_inv_no").trigger("change");
		}
	} else {
		disableInvoiceNo();
		$(".TXT_inv_no").removeClass("required");
		$(".TXT_inv_no").val('');
	}
	if ((mode == 'I' || mode == 'U') && $(".TXT_inv_no").val() == '') {
		$('.infor-created .heading-elements').addClass('hidden');
	}

	$(".TXT_sign_cd").val(cre_user_cd);
	$(".DSP_sign_nm").text(cre_user_nm);
	var _inv_data_div = $('input[name="RDI_inv_data_div"]:checked').val();
 	checkInvDataDiv(_inv_data_div, false);
}
/**
 * init Events
 * @author  :   Trieunb - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//init 1 row table at mode add new (I)
		_initRowTable('table-invoice', 'table-row', 1);
		_initRowTable('table-carton', 'table-row-carton', 1);
		_setTabIndexTable('table-invoice');
		// _dragLineTable('table-invoice', true);
		// _dragLineTable('table-carton', true);
		disableItemInvoice();
		disableItemCarton();
		//change TXT_invoice_date  
		$(document).on('change', '.TXT_sales_date  ', function(e) {
			var date = $(this).val();
			_getTaxRate(date, calTotalTaxAmt);
		});

		// show/hide address to
		$(document).on('click', '#show-address-to', function(){
			$(".address-to").toggleClass("hidden");
			if ($(this).text() == '住所非表示') {
				$(this).text('住所表示');
			} else {
				$(this).text('住所非表示');
			}
		});

		// show/hide address from
		$(document).on('click', '#show-address-from', function() {
			$(".address-from").toggleClass("hidden");
			if ($(this).text() == '住所非表示') {
				$(this).text('住所表示');
			} else {
				$(this).text('住所非表示');
			}
		});

		$(document).on('click', '#show-table-carton', function(){
			$(".table-carton-hidden").toggleClass("hidden");
				_formatDatepicker();
			if ($(this).text() == 'カートン非表示') {
				$(this).text('カートン表示')
			} else {
				$(this).text('カートン非表示')
			}
		});
		// add row carton
		$(document).on('click', '#btn-add-row-second', function () {
			try {
				var row = $("#table-row-carton tr").clone();

				var col_index =  $('.table-carton tbody tr').length;
				if (col_index < 100) {
					$('.table-carton tbody').append(row);
				}
				disableItemCarton();
				_updateTable('table-carton', true);
				_setTabIndexTable('table-invoice');

				$(".table-carton tbody tr:last :input:first").focus();
			} catch (e) {
				alert('add new row' + e.message);
			}
		});

		$(document).on('click', '#btn-add-blank-row', function () {
			try {
				var count_row = $('#count-add-blank-row').val();
				addNewRowEmplyTable('table-carton', 'table-row-carton', count_row);
			} catch (e) {
				alert('add new row' + e.message);
			}
		});

		//init back
		$(document).on('click', '#btn-back', function () {
			sessionStorage.setItem('detail', true);
			location.href = '/invoice/invoice-search';
		});

		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				if (mode != 'I') {
					var msg = 'C003';
				} else {
					var msg = 'C001';
				}
				if(validate()){
					var _row_detail = $('#table-invoice tbody tr').length;
					if(_row_detail > 0) {
						if (validateCarton()) {
							jMessage(msg, function(r) {
								if (r) {
									// if (mode == 'U' && $('.TXT_deposit_no').val() != '') {
									// 	var deposit_no = $('.TXT_deposit_no').val();
									// 	var msg_C147 = _text['C147'].replace('{0}', deposit_no);
		       //      					jMessage_str('C147', msg_C147, function() {
		       //      						validateCartonInvoice();
		       //      					}, msg_C147);
									// } else {
									// 	validateCartonInvoice();
									// }
									if (mode == 'U') {
										checkDeposit();
									} else {
										validateCartonInvoice();
									}
								}
							});
						} else {
							jMessage('E143', function (r) {
								if (r) {
									showMsgCarton();
								}
							});
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
				if(validateInvoice()){
					jMessage('C002', function(r) {
						if (r) {
							deleteInvoice();
						}
					});	
				}		   
			} catch (e) {
				alert('#btn-delete ' + e.message);
			}
		});
		//btn-invoice
        $(document).on('click', '#btn-invoice', function(){
            try {
            	if(validateInvoice()){
	                jMessage('C004', function(r) {
	                    if (r) {
	                    	var url = '/export/invoice-detail/invoice-export';
	                        invoiceExport("Invoice_","t_inv_print", url);
	                    }
	                });
	            }
            } catch (e) {
                console.log('#btn-invoice: ' + e.message);
            }
        });
        //btn-delivery_note
        $(document).on('click', '#btn-delivery-note', function(){
            try {
            	if(validateInvoice()){
	                jMessage('C004', function(r) {
	                    if (r) {
	                    	var url = '/export/invoice-detail/delivery-note-export';
	                        invoiceExport("納品書_","t_inv_delivery_print", url);
	                    }
	                });
               	}
            } catch (e) {
                console.log('#btn-delivery_note: ' + e.message);
            }
        });
        // btn-issue-instruction
 		$(document).on('click', '#btn-print-packing', function(){
 			try {
 				if(validateInvoice()){
	 				jMessage('C004', function(r) {
						if (r) {
							postPackingList();
						}
					});
	 			}
			} catch (e) {
				alert('#btn-issue ' + e.message);
			}
 		});
 		// button export
		$(document).on('click', '#btn-print-mark', function() {
			try {
				if(validateInvoice()){
					jMessage('C004', function(r) {
						if (r) {
							var	inv_no = $('.invoice_cd').val();
							if(inv_no !== '') {
								printMark(inv_no);
							}
						}
					});
				}
			} catch (e) {
				console.log('#btn-export: ' + e.message);
			}
		});	
		// Change 仮出荷指示No
		$(document).on('change', '.TXT_inv_no', function() {
			try {
				//get data
				var inv_no 	= $(this).val().trim();
			    var data = {
			    	inv_no 		: inv_no,
			    	mode 		: mode
			    };
				referInvoice(data);
			} catch (e) {
				alert('change #TXT_inv_no: ' + e.message);
			}
		});
 		//btn-popup-carton-item-set
		$(document).on('click', '.btn-popup-carton-item-set', function() {
			var data = {};
			var parent = $(this).parents('.popup');
			var input = parent.find('input');
			// var btn = input.attr('tabindex');

			// data.id = parent.data('id');
			data.search = $(this).data('search');
			// data.istable = parent.data('istable');
			// data.multi = parent.data('multi');
			// data.btnid = btn;
			parent.addClass('popup-'+ data.search);
			showPopup('/popup/search/cartonitemset?' + _setGetPrams(data), function(){
				input.focus();
				parent.removeClass('popup-'+ data.search);
			}, '70%', '70%');			
		});
		//click radio 仮／確定
 		$(document).on('click', '#provisional_type', function(){
			$('.provisional_shipment_cd').removeAttr('disabled', 'disabled');
			$('.provisional_shipment_cd').next().find('.btn-search').removeAttr('disabled', 'disabled');
			$('#TXT_provisonal_order_no').removeAttr('disabled', 'disabled');
			$('#TXT_provisonal_order_no').next().find('.btn-search').removeAttr('disabled', 'disabled');
			//add disabled attribute
			$('#TXT_confirm_order_no').val('');
			$('#TXT_confirm_order_no').attr('disabled', 'disabled');
			$('#TXT_confirm_order_no').next().find('.btn-search').attr('disabled', 'disabled');
			$('.shipment_cd').val('');
			$('.shipment_cd').attr('disabled', 'disabled');
			$('.shipment_cd').next().find('.btn-search').attr('disabled', 'disabled');
 		});
 		$(document).on('click', '#confirm_type', function(){
 			$('.provisional_shipment_cd').val('');
			$('.provisional_shipment_cd').attr('disabled', 'disabled');
			$('.provisional_shipment_cd').next().find('.btn-search').attr('disabled', 'disabled');
			$('#TXT_provisonal_order_no').val('');
			$('#TXT_provisonal_order_no').attr('disabled', 'disabled');
			$('#TXT_provisonal_order_no').next().find('.btn-search').attr('disabled', 'disabled');
			//
			$('.shipment_cd').removeAttr('disabled', 'disabled');
			$('.shipment_cd').next().find('.btn-search').removeAttr('disabled', 'disabled');
			$('#TXT_confirm_order_no').removeAttr('disabled', 'disabled');
			$('#TXT_confirm_order_no').next().find('.btn-search').removeAttr('disabled', 'disabled');
 		});
 		//click radio 仮／確定
 		$(document).on('click', 'input[name="RDI_inv_data_div"]', function() {
 			var _inv_data_div = $(this).val();
 			if (_inv_data_div == '1') {
 				$('.TXT_fwd_no').trigger('change');
 			} else {
 				$('.TXT_p_fwd_no').trigger('change');
 			}
 			checkInvDataDiv(_inv_data_div, false);
 		});
 		//change TXT_fwd_no 
		$(document).on('change', '.TXT_fwd_no, .TXT_p_fwd_no', function(e) {
			try {
				var fwd_data_div = '';
				if ($(this).hasClass('TXT_p_fwd_no')) {
					fwd_data_div = '0';
				} else {
					fwd_data_div = '1';
				}
				var data = {
					inv_no 			: 	$('.TXT_inv_no').val().trim(),
					mode 			:   mode,
					fwd_no 			: 	$(this).val().trim(),
					fwd_data_div 	: 	fwd_data_div
				};

				if (e.isTrigger) {
					referFwdDetail(data, fwd_data_div, function() {
							var lib_val_ctl5 = $('.CMB_trade_terms_div > option:selected').attr('data-ctl5');
					 		var lib_val_ctl6 = $('.CMB_trade_terms_div > option:selected').attr('data-ctl6');
					 		
					 		changeTrade(lib_val_ctl5, lib_val_ctl6);
						});
				} else {
					referFwdDetail(data, fwd_data_div);
				}
			} catch (e) {
				console.log('TXT_fwd_no: ' + e.message);
			}
		});
 		//change TXT_cust_cd 
		$(document).on('change', '.TXT_cust_cd ', function() {
			referSuppliers(true);
		});
		//change TXT_consignee_cd 
		$(document).on('change', '.TXT_consignee_cd ', function() {
			referSuppliers(false);
		});
		//change TXT_cust_city_div 
		$(document).on('change', '.TXT_cust_city_div ', function() {
			var city_div 	=	$(this).val();
			_referCity(city_div, $(this), $('.TXT_cust_country_div'), function() {
				var country_div =	$('.TXT_cust_country_div').val();
				changeNmCombobox(country_div);
				setItemCustCountryDiv(country_div);
				calTotalTaxAmt();
				calTotalAmt();
				_clearValidateMsg();
				$('.CMB_payment_conditions_div').trigger('change');
			}, true);
		});
		// change TXT_cust_country_div
 		$(document).on('change', '.TXT_cust_country_div', function() {
 			var country_div 	=	$(this).val();
 			_referCountry(country_div, $('.TXT_cust_city_div'), $(this), function() {
 				var country_div =	$('.TXT_cust_country_div').val();
 				changeNmCombobox(country_div);
 				setItemCustCountryDiv(country_div);
 				calTotalTaxAmt();
 				calTotalAmt();
 				_clearValidateMsg();
 				$('.CMB_payment_conditions_div').trigger('change');
 			}, true);
 		});
 		//change TXT_consignee_city_div 
		$(document).on('change', '.TXT_consignee_city_div ', function() {
			var city_div 	=	$(this).val();
			_referCity(city_div, $(this), $('.TXT_consignee_country_div'), function() {
				_clearValidateMsg();
			}, true);
		});
		// change TXT_consignee_country_div
 		$(document).on('change', '.TXT_consignee_country_div', function() {
 			var country_div 	=	$(this).val();
 			_referCountry(country_div, $('.TXT_consignee_city_div'), $(this), function() {
 				_clearValidateMsg();
 			}, true);
 		});
		//combobox trade terms
 		$(document).on('change', '.CMB_trade_terms_div', function(){
 			var lib_val_ctl5 = $('option:selected', this).attr('data-ctl5');
 			var lib_val_ctl6 = $('option:selected', this).attr('data-ctl6');
 			changeTrade(lib_val_ctl5, lib_val_ctl6);
 		});
 		// change TXT_dest_city_div
 		$(document).on('change', '.TXT_dest_city_div', function() {
 			var city_div 	=	$(this).val();
 			_referCity(city_div, $(this), $('.TXT_dest_country_div'), function() {
 				_clearValidateMsg();
 			}, true);
 		});
 		// change TXT_dest_country_div
 		$(document).on('change', '.TXT_dest_country_div', function() {
 			var country_div 	=	$(this).val();
 			_referCountry(country_div, $('.TXT_dest_city_div'), $(this), '', true);
 		});
 		//change TXT_freight_amt and TXT_insurance_amt
 		$(document).on('change', '.TXT_freight_amt, .TXT_insurance_amt', function() {
 			calTotalAmt();
 		});
 		//change TXT_sign_cd
 		$(document).on('change', '.TXT_sign_cd', function() {
 			var user_cd 	=	$(this).val();
 			_referUser(user_cd, $(this), '', true);
 		});

 		//CMB_payment_conditions_div
	    $(document).on('change','.CMB_payment_conditions_div',function (e) {
	        try {
	           payment_e = $('.CMB_payment_conditions_div').find('option:selected').data('nm-e');
	           $('.TXT_payment_notes').val($.trim($('.CMB_payment_conditions_div option:selected').text()));
	        } catch (e){
	            console.log('CMB_payment_conditions_div: '+e.message);
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
	if(!_validate($('body'))){
		_errors++;
	}

	if(_errors>0)
		return false;

	return true;
}
function disableItemInvoice() {
	$('#table-invoice tbody tr').each(function () { 
		$(this).find(':input').attr('disabled', 'disabled');
		$(this).find('.TXT_outside_remarks').attr('disabled', false);
		$(this).find('.btn-popup-carton-item-set').attr('disabled', false);
	});
}
function disableItemCarton() {
	$('#table-carton tbody tr').each(function () { 
		$(this).find(':input:not(.TXT_carton_number):not(.TXT_qty_table_carton):not(.remove-row)').attr('disabled', 'disabled');
	});
}

/**
 * disable Inv no
 *
 * @author		:	Trieunb - 2018/04/05 - create
 * @params		:	
 * @return		:	null
 */
function disableInvoiceNo() {
	try {
		if (mode == 'I') {
			$('.TXT_inv_no').attr('disabled', true);
			$('.TXT_inv_no').parent().addClass('popup-inv-search')
			$('.popup-inv-search').find('.btn-search').attr('disabled', true);
			parent.$('.popup-inv-search').removeClass('popup-inv-search');
		} else {
			$('.TXT_inv_no').attr('disabled', false);
			$('.TXT_inv_no').parent().addClass('popup-inv-search')
			$('.popup-inv-search').find('.btn-search').attr('disabled', false);
			parent.$('.popup-inv-search').removeClass('popup-inv-search');
			$(".TXT_inv_no").addClass("required");
		}
	} catch (e) {
		alert('disablePiNo: ' + e.message);
	}
}
/**
 * add new row emply table
 *
 * @author		:	Trieunb - 2018/04/05 - create
 * @params		:	obj talbe, obj line add new, total row need add
 * @return		:	null
 */
function addNewRowEmplyTable(table, objLineNew, numLine, callback) {
	try	{
		var row 		= 	$("#" + objLineNew).html();
		var htmlString 	= 	''; 
		for (var i = 0; i < parseInt(numLine); i++) {
			htmlString += row;
		}
		$('.'+ table + ' tbody').append(htmlString);
		
		if (typeof callback == 'function') {
			callback();
		}
		disableItemCarton();
		_updateTable('table-carton', true);
		_setTabIndex();
		_setTabIndexTable('table-invoice');
		//set first forcus input in row
		$('.'+ table + ' tbody tr:last :input:first').focus();
	} catch (e) {
		alert('addNewRowEmplyTable: ' + e.message);
	}
}
/**
 * inv data div
 * 
 * @author : ANS806 - 2018/04/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function checkInvDataDiv(div, is_refer) {
	try {
		_removeErrorStyle($('.TXT_p_fwd_no '));
		_removeErrorStyle($('.TXT_fwd_no '));
		// $('.TXT_p_fwd_no ').val('');
		// $('.TXT_fwd_no ').val('');
		// $('.DSP_p_rcv_no').text('');
		// $('.DSP_rcv_no').text('');

		if (is_refer && div == '1') {
			$('input[name="RDI_inv_data_div"]').prop("disabled", true);
		} else {
			$('input[name="RDI_inv_data_div"]').prop("disabled", false);
		}

		$('.TXT_p_fwd_no').attr('disabled', false);
        $('.TXT_fwd_no').attr('disabled', false);

		$('.TXT_fwd_no').addClass('required');
		$('.TXT_p_fwd_no').addClass('required');

		$('.TXT_fwd_no').parent().addClass('popup-shipment')
		$('.popup-shipment').find('.btn-search').attr('disabled', false);
		parent.$('.popup-shipment').removeClass('popup-shipment');

		$('.TXT_p_fwd_no').parent().addClass('popup-shipment')
		$('.popup-shipment').find('.btn-search').attr('disabled', false);
		parent.$('.popup-shipment').removeClass('popup-shipment');

		if (div == '0') {
			$('.TXT_fwd_no').attr('disabled', true);
			$('.TXT_fwd_no').removeClass('required');
			$('.TXT_fwd_no').parent().addClass('popup-shipment')
			$('.popup-shipment').find('.btn-search').attr('disabled', true);
			parent.$('.popup-shipment').removeClass('popup-shipment');
		} else {
			$('.TXT_p_fwd_no ').attr('disabled', true);
			$('.TXT_p_fwd_no ').removeClass('required');
			$('.TXT_p_fwd_no ').parent().addClass('popup-shipment')
			$('.popup-shipment').find('.btn-search').attr('disabled', true);
			parent.$('.popup-shipment').removeClass('popup-shipment');
		}
    }  catch(e) {
        console.log('checkInvDataDiv:' + e.message)
    }
}
/**
 * refer cust m client
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
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
			url 		: '/pi/refer-suppliers',
			dataType	: 'json',
			data 		: data,
			success: function(res) {
				var data = '';
				if (res.response) {
					data 	=	res.data;
				}
				setItemReferSuppliers(data, flag)
			}
		});
	} catch (e) {
		alert('referSuppliers: ' + e.message);
	}
}

/**
 * set item refer suppliers Cust and Consignee
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setItemReferSuppliers(data, flag) {
	try	{
		
		if (flag) {
			if (data != '') {
				// <得意先>
				$('.TXT_cust_nm').val(data.client_nm);
				$('.TXT_cust_adr1').val(data.client_adr1);
				$('.TXT_cust_adr2').val(data.client_adr2);
				$('.TXT_cust_zip').val(data.client_zip);
				$('.TXT_cust_city_div').val(data.client_city_div);
				$('.DSP_cust_city_nm').text(!jQuery.isEmptyObject(data) ? data.client_city_nm : '');
				$('.TXT_cust_country_div').val(data.client_country_div);
				$('.DSP_cust_country_nm').text(!jQuery.isEmptyObject(data) ? data.client_country_nm : '');
				$('.TXT_cust_tel').val(data.client_tel);
				$('.TXT_cust_fax').val(data.client_fax);
				// <Consignee>
				$('.TXT_consignee_cd').val('');
				$('.TXT_consignee_nm').val(data.consignee_nm);
				$('.TXT_consignee_adr1').val(data.consignee_adr1);
				$('.TXT_consignee_adr2').val(data.consignee_adr2);
				$('.TXT_consignee_zip').val(data.consignee_zip);
				$('.TXT_consignee_city_div').val(data.consignee_city_div);
				$('.DSP_consignee_city_nm').text(!jQuery.isEmptyObject(data) ? data.consignee_city_nm : '');
				$('.TXT_consignee_country_div').val(data.consignee_country_div);
				$('.DSP_consignee_country_nm').text(!jQuery.isEmptyObject(data) ? data.consignee_country_nm : '');
				$('.TXT_consignee_tel').val(data.consignee_tel);
				$('.TXT_consignee_fax').val(data.consignee_fax);
				// <他>
				$('.TXT_shipping_mark1').val(data.mark1);
				$('.TXT_shipping_mark2').val(data.mark2);
				$('.TXT_shipping_mark3').val(data.mark3);
				$('.TXT_shipping_mark4').val(data.mark4);
				$('.TXT_dest_city_div').val(data.delivery_city_div);
				$('.DSP_dest_city_nm').text(!jQuery.isEmptyObject(data) ? data.deliverye_city_nm : '');
				$('.TXT_dest_country_div').val(data.delivery_country_div);
				$('.DSP_dest_country_nm').text(!jQuery.isEmptyObject(data) ? data.deliverye_country_nm : '');

	 			changeNmCombobox(data.client_country_div);
	 			// calTotalTaxAmt();
	 			calTotalAmt();
	 			setItemCustCountryDiv(data.client_country_div);

	 			$('.CMB_currency_div option:first').prop('selected', true);
				$('.CMB_payment_conditions_div option:first').prop('selected', true);
				if (!jQuery.isEmptyObject(data)) {
					if (data.sales_currency_div != '') {
						$('.CMB_currency_div option[value='+data.sales_currency_div+']').prop('selected', true);
						$('.CMB_payment_conditions_div').trigger('change');
					}
					if (data.payment_conditions_div != '') {
						$('.CMB_payment_conditions_div option[value='+data.payment_conditions_div+']').prop('selected', true);
						$('.CMB_payment_conditions_div').trigger('change');
					}
				} else {
					// $('.TXT_cust_cd').val('');
					$('.TXT_consignee_cd').val('');
					$('.CMB_payment_conditions_div').trigger('change');
				}
			} else {
				$('.TXT_cust_nm').val('');
			}
			
		} else {
			if (data != '') {
				// <Consignee>
				$('.TXT_consignee_nm').val(data.client_nm);
				$('.TXT_consignee_adr1').val(data.client_adr1);
				$('.TXT_consignee_adr2').val(data.client_adr2);
				$('.TXT_consignee_zip').val(data.client_zip);
				$('.TXT_consignee_city_div').val(data.client_city_div);
				$('.DSP_consignee_city_nm').text(!jQuery.isEmptyObject(data) ? data.client_city_nm : '');
				$('.TXT_consignee_country_div').val(data.client_country_div);
				$('.DSP_consignee_country_nm').text(!jQuery.isEmptyObject(data) ? data.client_country_nm : '');
				$('.TXT_consignee_tel').val(data.client_tel);
				$('.TXT_consignee_fax').val(data.client_fax);
				if (jQuery.isEmptyObject(data)) {
					$('.TXT_consignee_cd').val('');
				}
			} else {
				$('.TXT_consignee_nm').val('');
			}
		}
		_clearValidateMsg();
	} catch (e) {
		alert('setItemReferSuppliers: ' + e.message);
	}
}
/**
 * set Item When change Cust Country Div
 *
 * @author      :   ANS806 - 2017/07/05
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   saveFunction
 */
function setItemCustCountryDiv(country_div) {
	try {
		if (country_div == 'JP' || country_div =='jp' || country_div =='jP' || country_div =='Jp') {
			$('.TXT_packing').val(_constVal1['pi_packing'])
			$('.TXT_time_of_shipment').val(_constVal1['pi_time_of_shipment'])
			$('.TXT_country_of_origin').val(_constVal1['pi_country_of_origin'])
			$('.TXT_manufacture').val(_constVal1['pi_manufacture'])
		} else {
			$('.TXT_packing').val(_constVal2['pi_packing'])
			$('.TXT_time_of_shipment').val(_constVal2['pi_time_of_shipment'])
			$('.TXT_country_of_origin').val(_constVal2['pi_country_of_origin'])
			$('.TXT_manufacture').val(_constVal2['pi_manufacture'])
		}
	} catch (e)  {
        alert('setItemCustCountryDiv:  ' + e.message);
    }
}
function changeTrade(lib_val_ctl5, lib_val_ctl6) {
	try {
		_addFreigtAndInsurance(lib_val_ctl5, lib_val_ctl6);
		calTotalAmt();
	} catch (e) {
		alert('changeTrade: ' + e.message);
	}
}
/**
 * refer accept infomation
 * 
 * @author : ANS806 - 2018/04/09 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referFwdDetail(data, fwd_data_div, callback) {
	try	{
		$.ajax({
			type 		: 'GET',
			url 		: '/invoice/refer-fwd-detail',
			dataType	: 'json',
			data 		: data,
			success: function(res) {
				if (res.response) {
					if (res.fwd_h.fwd_data_div == '1' && res.fwd_h.rcv_status_div != '20') {
						jMessage('E140', function(r) {
							if (r) {
								emptyInputAfterRefer(false);
							}
						});
					} else {
						// $('.heading-btn-group').html(res.button);
						$('#div-table-invoice').html(res.html_fwd_d);
						// set item for header
						setItemFwdHeader(res.fwd_h);

						// get and set tax date
						var date = $('.TXT_sales_date').val();
						_getTaxRate(date);
						
						var name =	$('.TXT_cust_country_div').val();

						setSelectCombobox();
						// _getComboboxData(name, 'unit_q_div',setSelectCombobox);
						// _getComboboxData(name, 'unit_w_div',setSelectCombobox);
						// _getComboboxData(name, 'unit_m_div',setSelectCombobox);
						// _getComboboxData(name, 'sales_detail_div', setSelectCombobox);

						changeNmCombobox(name);

						_setTabIndex();
						
						_setTabIndexTable('table-invoice');

						//drap and drop row table
						// _dragLineTable('table-invoice', true, setClassUnitCombobox);
						_dragLineTable('table-invoice', true);

						// var param = {
						// 	'mode'		: mode,
						// 	'from'		: 'InvoiceDetail',
						// 	'fwd_no'	: data.fwd_no,
						// };

						// if (from == 'InvoiceDSearch') {
						// 	_postParamToLink('InvoiceDSearch', 'InvoiceDetail', '', param)
						// } else {
						// 	_postParamToLink('InvoiceDetail', 'InvoiceDetail', '', param)
						// }					
					
					}

					_clearErrors();
				} else {
					if (data.fwd_no != '') {
						jMessage('E005', function(r) {
							if (r) {
								// _clearErrors();
								emptyInputAfterRefer(false);
								if (fwd_data_div == '1') {
									$('.TXT_fwd_no').errorStyle(_text['E005']);
									$('.TXT_fwd_no').focus();
								} else {
									$('.TXT_p_fwd_no').errorStyle(_text['E005']);
									$('.TXT_p_fwd_no').focus();
								}
							}
						});
					} else {
						emptyInputAfterRefer(false);
					}
				}
				if (typeof callback == 'function') {
					callback();
				}
				_initRowTable('table-carton', 'table-row-carton', 1);
				disableItemInvoice();
				disableItemCarton();
			}
		});
	} catch (e) {
		console.log('refer Fwd Detail: ' + e.message);
	}
}
/**
 * set Item fwdH
 * 
 * @author : ANS806 - 2018/04/09 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setItemFwdHeader(data) {
	try {
		if ($('input[name="RDI_inv_data_div"]:checked').val() == '0') {
			$('.TXT_p_fwd_no').val(data.fwd_no);
			$('.DSP_p_rcv_no').text(data.rcv_no);
			// $('.TXT_fwd_no').val('');
			// $('.DSP_rcv_no').text('');
			// $('.TXT_warehouse_div').val('');
		} else {
			$('.TXT_fwd_no').val(data.fwd_no);
			$('.DSP_rcv_no').text(data.rcv_no);
			// $('.TXT_p_fwd_no').val('');
			// $('.DSP_p_rcv_no').text("");
			$('.TXT_warehouse_div').val(data.warehouse_div);
		}
		$('.TXT_fwd_status').val(data.fwd_status_div);
		$('.TXT_rcv_status').val(data.rcv_status_div);

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

		// console.log(data.payment_conditions_div);
		if (data.payment_conditions_div != null) {
			$('.CMB_payment_conditions_div option[value='+data.payment_conditions_div+']').prop('selected', true);
		} else {
			$('.CMB_payment_conditions_div option:first').prop('selected', true);
		}
		
		$('.TXT_dest_city_div').val(data.dest_city_div);
		$('.DSP_dest_city_nm').text(data.dest_city_nm);
		$('.TXT_dest_country_div').val(data.dest_country_div);
		$('.DSP_dest_country_nm').text(data.dest_country_nm);
		
		$('.TXT_payment_notes').val(data.payment_notes);
		$('.TXT_freight_amt').val(data.freigt_amt);
		$('.TXT_insurance_amt').val(data.insurance_amt);

		// detail sum of amount
		calTotalQty();
		calTotalGrossWeight();
		calTotalNetWeight();
		calTotalMeasure();

		calTotalDetailAmt();
		calTotalTaxAmt();
		calTotalAmt();
		// set item footer
		$('.TXT_country_of_origin').val(data.country_of_origin);
		$('.TXT_manufacture').val(data.manufacture);
		$('.TXT_sign_cd').val(data.sign_user_cd);
		$('.DSP_sign_nm').text(data.sign_user_nm);
		$('.TXA_inside_remarks').val(data.inside_remarks);
		//
		var currency_div = $('.currency_div').find('option:selected').val();

		if (currency_div == 'JPY') {
			$('#table-invoice tbody tr').find('.price').addClass('currency_JPY');
		} else {
			$('#table-invoice tbody tr').find('.price').removeClass('currency_JPY');
		}
	} catch (e) {
		console.log('setItemFwdHeader: ' + e.message);
	}
}
/**
 * empty input after refer not exists
 * 
 * @author : ANS806 - 2018/04/09 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function emptyInputAfterRefer(is_inv) {
	try {
		// not empty code of "destination_city", "destination_country", "署名者"(signer)
		if (is_inv) {
			$(':input:not(.TXT_inv_no):not(.TXT_invoice_date):not(.TXT_sales_date):not([type="radio"]):not(.TXT_sign_cd)').val('');
		} else {
			$(':input:not(.TXT_inv_no):not(.TXT_fwd_no):not(.TXT_p_fwd_no):not(.TXT_invoice_date):not(.TXT_sales_date):not([type="radio"]):not(.TXT_sign_cd)').val('');
		}

		$('.TXT_invoice_date').val(current_date);
		$('.TXT_sales_date').val(current_date);

		$('.DSP_p_rcv_no').text('');
		$('.DSP_rcv_no').text('');

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

		$('.DSP_carton_total_qty').text('');
		$('.DSP_carton_total_net_weight').text('');
		$('.DSP_carton_total_gross_weight').text('');
		$('.DSP_carton_total_measure').text('');

		$('.DSP_unit_total_gross_weight_nm').text('');
		$('.DSP_unit_total_gross_weight_div').text('');
		$('.DSP_unit_total_net_weight_nm').text('');
		$('.DSP_unit_total_net_weight_div').text('');
		$('.DSP_unit_total_measure_nm').text('');
		$('.DSP_unit_total_measure_div').text('');
		
		calNumberCarton();
		// $('.DSP_status').text('');
		// $('.DSP_fwd_status_cre_datetime').text('');

		_addFreigtAndInsurance();

		$('.DSP_tax_amt').addClass('hidden');
		$('.title-jp').addClass('hidden');

		$('.TXT_fwd_date').val($('.TXT_fwd_date').attr('data-init'));

		$('.TXT_packing ').val(_constVal1['pi_packing']);
		$(".TXT_sign_cd").val(cre_user_cd);
		$(".DSP_sign_nm").text(cre_user_nm);

		//init 1 row table at mode add new (I)
		// _initRowTable('table-invoice', 'table-row', 1, setClassUnitCombobox);
		_initRowTable('table-invoice', 'table-row', 1);
		_initRowTable('table-carton', 'table-row-carton', 1);
		disableItemInvoice();
		disableItemCarton();
		$('.infor-created .heading-elements').addClass('hidden');
	} catch (e) {
		console.log('emptyInputAfterRefer: ' + e.message)
	}
}
/**
 * set Select Combobox
 * 
 * @author : ANS806 - 2018/04/09- create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setSelectCombobox() {
	try {
		$('#table-invoice tbody tr').each(function() {
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
		var unit_total_gross_weight_nm 	= $('#table-invoice tbody tr:first').find('.CMB_unit_net_weight_div option:selected').text();
		var unit_total_gross_weight_div = $('#table-invoice tbody tr:first').find('.CMB_unit_net_weight_div option:selected').val();
		$('.DSP_unit_total_gross_weight_nm').text(unit_total_gross_weight_nm);
		$('.DSP_unit_total_gross_weight_div').text(unit_total_gross_weight_div);
		$('.DSP_unit_total_net_weight_nm').text(unit_total_gross_weight_nm);
		$('.DSP_unit_total_net_weight_div').text(unit_total_gross_weight_div);
		var unit_total_measure_nm = $('#table-invoice tbody tr:first').find('.CMB_unit_measure_price option:selected').text();
		var unit_total_measure_div = $('#table-invoice tbody tr:first').find('.CMB_unit_measure_price option:selected').val();
		$('.DSP_unit_total_measure_nm').text(unit_total_measure_nm);
		$('.DSP_unit_total_measure_div').text(unit_total_measure_div);

		$('#table-carton tbody tr').each(function() {
			var _unit_of_m_div = $(this).find('.CMB_unit_net_weight_div_table_carton').attr('data-selected');
			if (_unit_of_m_div != '') {
				$(this).find('.CMB_unit_net_weight_div_table_carton option[value='+_unit_of_m_div+']').prop('selected', true);
			} else {
				$(this).find('.CMB_unit_net_weight_div_table_carton option:first').prop('selected', true);
			}

			var _unit_net_weight_div = $(this).find('.CMB_unit_measure_table_carton').attr('data-selected');
			if (_unit_net_weight_div != '') {
				$(this).find('.CMB_unit_measure_table_carton option[value='+_unit_net_weight_div+']').prop('selected', true);
			} else {
				$(this).find('.CMB_unit_measure_table_carton option:first').prop('selected', true);
			}
		});

	} catch (e)  {
        console.log('setSelectCombobox: ' + e.message);
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
		/* =============================== INVOICE TABLE =============================== */
		var _data_invoice = [];
		$('#table-invoice tbody tr').each(function() {
			var _inv_detail_no 		= ($(this).find('.DSP_inv_detail_no').text() == "") ? 0 : $(this).find('.DSP_inv_detail_no').text();
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
			
			//get data table fwd detail
			var _t_inv_d = {
					'inv_detail_no' 		: parseInt(_inv_detail_no),
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
			_data_invoice.push(_t_inv_d);
		});

		var _total_qty          = ($('.DSP_total_qty').text() == "") ? 0 : $('.DSP_total_qty').text().replace(/,/g, '');
		var _total_gross_weight = ($('.DSP_total_gross_weight').text() == "") ? 0 : $('.DSP_total_gross_weight').text().replace(/,/g, '');
		var _total_net_weight   = ($('.DSP_total_net_weight').text()  == "") ? 0 : $('.DSP_total_net_weight').text().replace(/,/g, '');
		var _total_measure      = ($('.DSP_total_measure').text()  == "") ? 0 : $('.DSP_total_measure').text().replace(/,/g, '');
	 
		var _total_detail_amt   = ($('.DSP_total_detail_amt').text()  == "") ? 0 : $('.DSP_total_detail_amt').text().replace(/,/g, '');
		var _freight_amt         = $('.TXT_freight_amt').hasClass('hidden') ? 0 : $('.TXT_freight_amt').val().replace(/,/g, '');
		var _insurance_amt      = $('.TXT_insurance_amt').hasClass('hidden') ? 0 : $('.TXT_insurance_amt').val().replace(/,/g, '');
		var _tax_amt            = ($('.DSP_tax_amt').text()  == "") ? 0 : $('.DSP_tax_amt').text().replace(/,/g, '');
	 
		var _total_amt          = ($('.DSP_total_amt').text()  == "" )? 0 : $('.DSP_total_amt').text().replace(/,/g, '')

		/* =============================== CARTON TABLE =============================== */
		var _data_carton 		= 	[];
		var _t_invoice_carton_d	=	[];
		// var _t_invoice_carton_d	=	{};
		$('#table-carton tbody tr').each(function() {
			if ($(this).find('.DSP_fwd_detail_no_table_carton').text() != '' && $(this).find('.DSP_fwd_detail_no_table_carton').text() != null) {
				if (($(this).find('.TXT_carton_number').val() != '')) {
					var _inv_carton_detail_no 		= ($(this).find('.DSP_inv_carton_detail_no').text() == "") ? 0 : $(this).find('.DSP_inv_carton_detail_no').text();
					var _inv_fwd_detail_no 			= ($(this).find('.DSP_fwd_detail_no_table_carton').text() == "") ? 0 : $(this).find('.DSP_fwd_detail_no_table_carton').text();
					var _carton_qty 				= ($(this).find('.TXT_qty_table_carton').val() == "") ? 0 : $(this).find('.TXT_qty_table_carton').val().replace(/,/g, '');
					var _carton_unit_net_weight 	= ($(this).find('.TXT_unit_net_weight_table_carton').val() == "") ? 0 : $(this).find('.TXT_unit_net_weight_table_carton').val().replace(/,/g, '');
					var _carton_net_weight 			= ($(this).find('.DSP_total_net_weight_table_carton').text() == "") ? 0 : $(this).find('.DSP_total_net_weight_table_carton').text().replace(/,/g, '');
					var _carton_unit_gross_weight 	= ($(this).find('.TXT_unit_gross_weight_table_carton').val() == "") ? 0 : $(this).find('.TXT_unit_gross_weight_table_carton').val().replace(/,/g, '');
					var _carton_gross_weight 		= ($(this).find('.DSP_total_gross_weight_table_carton').text() == "") ? 0 : $(this).find('.DSP_total_gross_weight_table_carton').text().replace(/,/g, '');
					var _carton_unit_measure 		= ($(this).find('.TXT_unit_measure_table_carton').val() == "") ? 0 : $(this).find('.TXT_unit_measure_table_carton').val().replace(/,/g, '');
					var _carton_measure 			= ($(this).find('.DSP_total_measure_table_carton').text() == "") ? 0 : $(this).find('.DSP_total_measure_table_carton').text().replace(/,/g, '');
					//get data table fwd detail
				_t_invoice_carton_d = {
						'inv_carton_detail_no' 			: parseInt(_inv_carton_detail_no),
						'inv_fwd_detail_no' 			: parseInt(_inv_fwd_detail_no),
						'carton_number' 				: $(this).find('.TXT_carton_number').val(),
						'carton_product_cd' 			: $(this).find('.TXT_product_cd_table_carton').val(),
						'carton_qty' 					: parseInt(_carton_qty),
						'carton_unit_net_weight_div'	: $(this).find('.CMB_unit_net_weight_div_table_carton').val(),
						'carton_unit_net_weight'		: parseFloat(_carton_unit_net_weight),
						'carton_net_weight'				: parseFloat(_carton_net_weight),
						'carton_unit_gross_weight'		: parseFloat(_carton_unit_gross_weight),
						'carton_gross_weight'			: parseFloat(_carton_gross_weight),
						'carton_measure_div'			: $(this).find('.CMB_unit_measure_table_carton').val(),
						'carton_unit_measure'			: parseFloat(_carton_unit_measure),
						'carton_measure'				: parseFloat(_carton_measure)
					};
				_data_carton.push(_t_invoice_carton_d);
				}
			}
		});


		var carton_total_qty          	= ($('.DSP_carton_total_qty').text() == "") ? 0 : $('.DSP_carton_total_qty').text().replace(/,/g, '');
		var carton_total_net_weight 	= ($('.DSP_carton_total_net_weight').text() == "") ? 0 : $('.DSP_carton_total_net_weight').text().replace(/,/g, '');
		var carton_total_gross_weight   = ($('.DSP_carton_total_gross_weight').text()  == "") ? 0 : $('.DSP_carton_total_gross_weight').text().replace(/,/g, '');
		var carton_total_measure      	= ($('.DSP_carton_total_measure').text()  == "") ? 0 : $('.DSP_carton_total_measure').text().replace(/,/g, '');
		var total_carton_num       		= ($('.DSP_total_carton_num').text()  == "") ? 0 : $('.DSP_total_carton_num').text().replace(/,/g, '');

		var inv_data_div = '';
		if ($('input[name="RDI_inv_data_div"]:checked').val() == '0') {
			inv_data_div = '0';
		} else {
			inv_data_div = '1';
		}
		var fwd_no 		=	'';
		var p_fwd_no 	=	'';
		var rcv_no 		=	'';
		var p_rcv_no 	=	'';
		if (inv_data_div == '1') {
			fwd_no 	=	$('.TXT_fwd_no').val();
			rcv_no 	=	$('.DSP_rcv_no').text();
		} else {
			p_fwd_no 	=	$('.TXT_p_fwd_no').val();
			p_rcv_no 	=	$('.DSP_p_rcv_no').text();
		}

		var STT_data = {
				'mode'							: mode,
				'inv_no'						: $('.TXT_inv_no').val(),
				'inv_data_div'					: inv_data_div,
				'p_fwd_no'						: p_fwd_no,
				'fwd_no'						: fwd_no,
				'p_rcv_no'						: p_rcv_no,
				'rcv_no'						: rcv_no,
				'rcv_status'					: $('.TXT_rcv_status').val(),
				'fwd_status'					: $('.TXT_fwd_status').val(),
				'invoice_date'					: $('.TXT_invoice_date').val(),
				'sales_date'					: $('.TXT_sales_date').val(),
				'lc_number'						: $('.TXT_lc_no').val(),
				'po_number'						: $('.TXT_po_no').val(),
				'date_of_shipment'				: $('.TXT_date_of_shipment').val(),
				'warehouse_div'					: $('.TXT_warehouse_div').val(),
				// <E-②:得意先>		
				'cust_cd'						: $('.TXT_cust_cd ').val(),
				'cust_nm'						: $('.TXT_cust_nm ').val(),
				'cust_adr1'						: $('.TXT_cust_adr1 ').val(),
				'cust_adr2'						: $('.TXT_cust_adr2 ').val(),
				'cust_zip'						: $('.TXT_cust_zip ').val(),
				'cust_city_div'					: $('.TXT_cust_city_div ').val(),
				'cust_country_div'				: $('.TXT_cust_country_div ').val(),
				'cust_tel'						: $('.TXT_cust_tel ').val(),
				'cust_fax'						: $('.TXT_cust_fax ').val(),
				// <E-③:Consignee>	
				'consignee_cd'					: $('.TXT_consignee_cd ').val(),
				'consignee_nm'					: $('.TXT_consignee_nm ').val(),
				'consignee_adr1'				: $('.TXT_consignee_adr1 ').val(),
				'consignee_adr2'				: $('.TXT_consignee_adr2 ').val(),
				'consignee_zip'					: $('.TXT_consignee_zip ').val(),
				'consignee_city_div'			: $('.TXT_consignee_city_div ').val(),
				'consignee_country_div'			: $('.TXT_consignee_country_div ').val(),
				'consignee_tel'					: $('.TXT_consignee_tel ').val(),
				'consignee_fax'					: $('.TXT_consignee_fax ').val(),
				// <E-④:他>	
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
				'payment_conditions_div'		: $('.CMB_payment_conditions_div').val(),
				'payment_notes'					: $('.TXT_payment_notes').val(),
				//<F:Invoice明細_Detail Invoice>
				't_invoice_d' 					: _data_invoice,
				//<F-⑤:Common>
				//<G:数量合計明細_Detail tổng số lượng>
				'total_qty'						: parseInt(_total_qty),
				'unit_total_qty_div'			: $('#table-invoice tbody tr:first').find('.CMB_unit_of_m_div option:selected').val(),
				'total_gross_weight'			: parseFloat(_total_gross_weight),
				'unit_total_gross_weight_div'	: $('.DSP_unit_total_gross_weight_div').text(),
				'total_net_weight'				: parseFloat(_total_net_weight),
				'unit_total_net_weight_div'		: $('.DSP_unit_total_net_weight_div').text(),
				'total_measure'					: parseFloat(_total_measure),
				'unit_total_measure_div'		: $('.DSP_unit_total_measure_div').text(),
				//<H:金額合計_Tổng số tiền>
				'total_detail_amt'				: parseFloat(_total_detail_amt),
				'freight_amt'					: (_freight_amt == '') ? 0 : parseFloat(_freight_amt),
				'insurance_amt'					: (_insurance_amt == '') ? 0 : parseFloat(_insurance_amt),
				'tax_amt'						: parseFloat(_tax_amt),
				'total_amt'						: parseFloat(_total_amt),
				// <J:Carton detail>
				't_carton_d' 					: (_t_invoice_carton_d.length == 0) ? '' : _data_carton,
				// <K:カートン明細合計_Tổng chi tiết carton>
				'carton_total_qty'				: parseInt(carton_total_qty),
				'carton_total_net_weight'		: parseFloat(carton_total_net_weight),
				'carton_total_gross_weight'		: parseFloat(carton_total_gross_weight),
				'carton_total_measure'			: parseFloat(carton_total_measure),
				'total_carton_num'				: parseInt(total_carton_num),
				//<N:フッタ>	
				'storage_user_cd '				: $('.CMB_storage_user_cd').val(),
				'country_of_origin '			: $('.TXT_country_of_origin').val(),
				'manufacture'					: $('.TXT_manufacture ').val(),
				'sign_cd'						: $('.TXT_sign_cd').val(),
				'inside_remarks'				: $('.TXA_inside_remarks').val(),
			};
			
		return STT_data;
	} catch(e) {
        console.log('getData' + e.message)
    }
}
/**
 * save invoice detail
 *
 * @author      :   ANS806 - 2018/04/12 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function saveInvoice() {
	try{
	    var data = getData();
	    $.ajax({
	        type        :   'POST',
	        url         :   '/invoice/invoice-detail/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	if (checkErrors(res)) {
	            		var msg = (mode == 'I') ? 'I001' : 'I003';
	            		jMessage(msg, function(r){
		                	if(r){
								mode 	 = 'U';
		                		var data = {
		                			inv_no 		: res.inv_no,
		                			mode		: mode
		                		};
		                		referInvoice(data);
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
        console.log('saveInvoice: ' + e.message)
    }
}
/**
 * check Deposit
 *
 * @author      :   ANS806 - 2018/05/02 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function checkDeposit() {
	try{
		var inv_no = $('.TXT_inv_no').val();
		$.ajax({
	        type        :   'POST',
	        url         :   '/invoice/invoice-detail/check-deposit',
	        dataType    :   'json',
	        data        :   {inv_no : inv_no},
	        success: function(res) {
	            if (res.response) {
	            	if (res.warning != '') {
	            		//catch DB error and display
		            	var msg_deposit_no = _text[res.warning].replace('{0}', res.deposit_no);
		            	jMessage_str(res.warning, msg_deposit_no, function(r) {
		            		if(r){
			            		validateCartonInvoice();
			            	}
		            	}, msg_deposit_no);
	     //        		jMessage(res.warning, function() {
						// 	validateCartonInvoice();
						// }); 
	            	} else {
	            		validateCartonInvoice();
	            	}
	            }
	        },
	    });
		
	} catch(e) {
        console.log('checkDeposit: ' + e.message)
    }
}
/**
 * check errors sever
 *
 * @author      :   ANS806 - 2018/04/12 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function checkErrors(res) {
	if (res.error_rcv_status != '') {
		msg_err	=	res.error_rcv_status;
		jMessage(msg_err);
		return false;
	} 
	if(res.error_fwd != '') {
		var error_fwd = _text[res.error_fwd].replace('{0}', res.inv_no);
		jMessage_str(res.error_fwd, error_fwd, '', error_fwd);
		return false;
	}
	if(res.error_fwd_status != '') {
		msg_err	=	res.error_fwd_status;
		jMessage(msg_err);
		return false;
	}
	if (!fillItemErrorsE005(res.errors_item, false)) {
		jMessage('E005', function(r) {
			fillItemErrorsE005(res.errors_item, true);
		});
		return false;
	}
	return true;
}
function fillItemErrorsE005(errors_item, is_msg) {
	try {
		var error_key = 'E005'
		var is_error = true;
		if (errors_item.fwd_no == error_key) {
			if (is_msg) {
				$('.TXT_fwd_no').errorStyle(_text['E005']);
			}
			is_error = false;
		}
		if (errors_item.p_fwd_no == error_key) {
			if (is_msg) {
				$('.TXT_p_fwd_no').errorStyle(_text['E005']);
			}
			is_error = false;
		}
		if (errors_item.sign_cd == error_key) {
			if (is_msg) {
				$('.TXT_sign_cd').errorStyle(_text['E005']);
			}
			is_error = false;
		}
		if (errors_item.cust_cd == error_key) {
			if (is_msg) {
				$('.TXT_cust_cd').errorStyle(_text['E005']);
			}
			is_error = false;
		}
		if (errors_item.cust_city_div == error_key) {
			if (is_msg) {
				$('.TXT_cust_city_div').errorStyle(_text['E005']);
			}
			is_error = false;
		}
		if (errors_item.cust_country_div == error_key) {
			if (is_msg) {
				$('.TXT_cust_country_div').errorStyle(_text['E005']);
			}
			is_error = false;
		}
		if (errors_item.consignee_cd == error_key) {
			if (is_msg) {
				$('.TXT_consignee_cd').errorStyle(_text['E005']);
			}
			is_error = false;
		}
		if (errors_item.consignee_city_div == error_key) {
			if (is_msg) {
				$('.TXT_consignee_city_div').errorStyle(_text['E005']);
			}
			is_error = false;
		}
		if (errors_item.consignee_country_div == error_key) {
			if (is_msg) {
				$('.TXT_consignee_country_div').errorStyle(_text['E005']);
			}
			is_error = false;
		}
		if (errors_item.dest_city_div == error_key) {
			if (is_msg) {
				$('.TXT_dest_city_div').errorStyle(_text['E005']);
			}
			is_error = false;
		}
		if (errors_item.dest_country_div == error_key) {
			if (is_msg) {
				$('.TXT_dest_country_div').errorStyle(_text['E005']);
			}
			is_error = false;
		}
		return is_error;
	} catch (e) {
		alert('fillItemErrorsE005: ' + e.message);
	}
}
/**
 * validate table carton
 * 
 * @author : ANS806 - 2018/04/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function validateCarton() {
	try {
		var leng_invoice 	= $('#table-invoice tbody tr').length;
		var dataCarton 		=	[];
		var fwd_detail_no	=	'';
		var is_e143			=	true;
		$('#table-carton tbody tr').each(function() {
			var carton_no = $(this).find('.DSP_fwd_detail_no_table_carton').text();
			if (carton_no == '') {
				if (($(this).find('.TXT_carton_number').val() !== '')) {
					is_e143	= false;
				}
				// dataCarton.push(carton_no);
				
			}
		});
		// var unique_array 	= []
		// var leng_carton 	= 0;
		// if (dataCarton.length > 0) {
		// 	for(var j = 0; j < dataCarton.length; j++) {
		//         if(unique_array.indexOf(dataCarton[j]) == -1) {
		//             unique_array.push(dataCarton[j]);
		//         }
		//     }
		//     leng_carton	=	unique_array.length;
		// }
		// if (leng_invoice == leng_carton) {
		// 	return true;
		// } else {
		// 	return false;
		// }

		// if (dataCarton.length > 0) {
		// 	is_e143	= true;
		// } else {
		// 	is_e143	= false;
		// }
		return is_e143;
	} catch(e) {
        console.log('validateCarton' + e.message)
    }
}
/**
 * show msg table carton
 * 
 * @author : ANS806 - 2018/04/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function showMsgCarton() {
	try {
		$('#table-carton tbody tr').each(function() {
			var carton_no = $(this).find('.DSP_fwd_detail_no_table_carton').text();
			_removeErrorStyle($(this).find('.TXT_carton_number'));
			if (carton_no == '') {
				if (($(this).find('.TXT_carton_number').val() !== '')) {
					$(this).find('.TXT_carton_number').errorStyle(_text['E143']);
				}
			}
		});
	} catch(e) {
        console.log('validateCarton' + e.message)
    }
}
/**
 * validate total carton vs invoice
 * 
 * @author : ANS806 - 2018/04/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function validateTotalNetWeight() {
	try {
		var _total_net_weight_invoice 	= parseFloat($('.DSP_total_net_weight').text().replace(/,/g, ''));
		var _total_net_weight_carton 	= parseFloat($('.DSP_total_net_weight_carton').text().replace(/,/g, ''));

		var is_flag = true;
		if (_total_net_weight_invoice != _total_net_weight_carton) {
			is_flag = false;
		}
		return is_flag;
	} catch(e) {
        console.log('validateTotalCartonInvoice' + e.message)
    }
}
/**
 * validate total carton vs invoice
 * 
 * @author : ANS806 - 2018/04/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function validateTotalGrossWeight() {
	try {
		var _total_gross_weight_invoice = parseFloat($('.DSP_total_gross_weight').text().replace(/,/g, ''));

		var _total_gross_weight_carton 	= parseFloat($('.DSP_total_gross_weight_carton').text().replace(/,/g, ''));

		var is_flag = true;
		if (_total_gross_weight_invoice != _total_gross_weight_carton) {
			is_flag = false;
		}
		return is_flag;
	} catch(e) {
        console.log('validateTotalCartonInvoice' + e.message)
    }
}
/**
 * validate total measuret carton vs invoice
 * 
 * @author : ANS806 - 2018/04/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function validateTotalMeasurement() {
	try {
		var _total_measure_invoice 		= parseFloat($('.DSP_total_measure').text().replace(/,/g, ''));
		_total_measure_invoice   		= !isNaN(_total_measure_invoice) ? _total_measure_invoice : 0;
		var _total_measure_carton 		= parseFloat($('.DSP_total_measure_carton').text().replace(/,/g, ''));
		_total_measure_carton   		= !isNaN(_total_measure_carton) ? _total_measure_carton : 0;
		//
		var is_flag = true;
		if (_total_measure_invoice != _total_measure_carton) {
			is_flag = false;
		}
		return is_flag;
	} catch(e) {
        console.log('validateTotalCartonInvoice' + e.message)
    }
}
/**
 * validate carton vs invoice
 * 
 * @author : ANS806 - 2018/04/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function validateCartonInvoice() {
	try {
		// if (checkCarton()) {
			if (!validateTotalNetWeight()) {
				jMessage('C144', function(r) {
					if (r) {
						if (!validateTotalGrossWeight()) {
							jMessage('C145', function(k) {
								if (k) {
									if (!validateTotalMeasurement()) {
										jMessage('C146', function(q) {
											if(q){
												saveInvoice();
											}
										});
									}else{
										saveInvoice();
									}
								}
							})
						}else{
							saveInvoice();
						}
					}
				});
			} else if (!validateTotalGrossWeight()) {
					jMessage('C145', function(n) {
						if (n) {
							if (!validateTotalMeasurement()) {
								jMessage('C146', function(w) {
									if(w){
										saveInvoice();
									}
								});
							}else{
								saveInvoice();
							}
						}
					})
				} else if (!validateTotalMeasurement()) {
						jMessage('C146', function(z) {
							if(z){
								saveInvoice();
							}
						});
					}
				else {
					saveInvoice();
				}
			// } else {
			// 	if ($('#table-carton-hidden').hasClass('hidden')) {
			// 		$('#show-table-carton').trigger('click');
			// 	}
			// 	jMessage('E004', function(r) {
			// 		if (r) {
			// 			showMsgDetail();
			// 		}
			// 	});
			// }
	} catch(e) {
        console.log('validateCartonInvoice' + e.message)
    }
}
/**
 * refer shipment detail 
 *
 * @author      :   ANS831 - 2018/02/28 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function referInvoice(data, callback){
	try{
		//clear all error
		_clearErrors();
	    $.ajax({
	        type        :   'GET',
	        url         :   '/invoice/refer-invoice-detail',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	if (res.response) {
					$('.heading-btn-group').html(res.button);
					$('#div-table-invoice').html(res.html_inv_d);
	        		$('#table-carton-hidden').html(res.html_carton_d);
					// set item for header
					setItemInvoiceHeader(res.inv_h);

					// get and set tax date
					var date = $('.TXT_sales_date').val();
					_getTaxRate(date);
					
					var name =	$('.TXT_cust_country_div').val();

					setSelectCombobox();
					// _getComboboxData(name, 'unit_q_div',setSelectCombobox);
					// _getComboboxData(name, 'unit_w_div',setSelectCombobox);
					// _getComboboxData(name, 'unit_m_div',setSelectCombobox);
					// _getComboboxData(name, 'sales_detail_div', setSelectCombobox);

					changeNmCombobox(name);

					calTotalQtyCarton();
					calTotalNetWeightCarton();
					calTotalGrossWeightCarton();
					calTotalMeasureCarton();
					calNumberCarton();

					_setTabIndex();
					_setTabIndexTable('table-invoice');
					_setTabIndexTable('table-carton');

					// var param = {
					// 	'mode'		: mode,
					// 	'from'		: 'InvoiceDetail',
					// 	'inv_no'	: data.inv_no,
					// };

					// _postParamToLink(from, 'InputOutputDetail', '', param);

					_clearErrors();
				} else {
					if (data.inv_no != '') {
						jMessage('E005', function(r) {
							if (r) {
								emptyInputAfterRefer(true);
							}
						});
					} else {
						emptyInputAfterRefer(true);
					}
				}
				if (typeof callback == 'function') {
					callback();
				}
				disableItemInvoice();
				disableItemCarton();
	        },
	    });
	} catch(e) {
        alert('refer Invoice: ' + e.message)
    }
}
/**
 * set Item Invoice header
 * 
 * @author : ANS806 - 2018/04/09 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setItemInvoiceHeader(data) {
	try {
		disableInvoiceNo();
		// if (mode == 'I') {
		// 	$('.TXT_fwd_no').val('');
		// 	$('.TXT_fwd_no').attr('disabled', true);
		// 	$('.TXT_fwd_no').parent().addClass('popup-fwd-search');

		// 	$('.popup-fwd-search').find('.btn-search').attr('disabled', true);
		// 	parent.$('.popup-fwd-search').removeClass('popup-fwd-search');
		// 	$(".TXT_fwd_no").removeClass("required");
		// } else {
		// 	$('.TXT_fwd_no').attr('disabled', false);
		// 	$('.TXT_fwd_no').parent().addClass('popup-fwd-search');

		// 	$('.popup-fwd-search').find('.btn-search').attr('disabled', false);
		// 	parent.$('.popup-fwd-search').removeClass('popup-fwd-search');
		// 	$(".TXT_fwd_no").addClass("required");
		// }
		$('.TXT_inv_no').val(data.inv_no);
		$('.TXT_deposit_no').val(data.deposit_no);
		$('.TXT_warehouse_div').val(data.warehouse_div);

		$('.infor-created .heading-elements').removeClass('hidden');
		// Common
		$('#DSP_cre_user_cd').text(data.cre_user_cd +' '+ data.cre_user_nm);
		$('#DSP_cre_datetime').text(data.cre_datetime);
		$('#DSP_upd_user_cd').text(data.upd_user_cd +' '+ data.upd_user_nm);
		$('#DSP_upd_datetime').text(data.upd_datetime);
		
		if (data.inv_data_div == '1') {
			$('input[name="RDI_inv_data_div"][value="1"]').prop('checked', true);
		} else {
			$('input[name="RDI_inv_data_div"][value="0"]').prop('checked', true);
		}

		var _inv_data_div = $('input[name="RDI_inv_data_div"]:checked').val();
 		checkInvDataDiv(_inv_data_div, true);

 		$('.TXT_p_fwd_no').val(data.p_fwd_no);
 		$('.DSP_p_rcv_no').text(data.p_rcv_no);

		$('.TXT_fwd_no ').val(data.fwd_no);
		$('.DSP_rcv_no').text(data.rcv_no);

		$('.TXT_fwd_status').val(data.fwd_status_div);
		$('.TXT_rcv_status').val(data.rcv_status_div);

		// if ($('input[name="RDI_inv_data_div"]:checked').val() == '0') {
		// 	$('.TXT_p_fwd_no').val(data.p_fwd_no);
		// 	$('.DSP_p_rcv_no').text(data.p_rcv_no);
		// 	$('.TXT_fwd_no').val('');
		// 	$('.DSP_rcv_no').text('');
		// 	$('.TXT_warehouse_div').val('');
		// } else {
		// 	$('.TXT_fwd_no').val(data.fwd_no);
		// 	$('.DSP_rcv_no').text(data.rcv_no);
		// 	$('.TXT_p_fwd_no').val('');
		// 	$('.DSP_p_rcv_no').text("");
		// 	$('.TXT_warehouse_div').val(data.warehouse_div);
		// }

		// $('.TXT_fwd_date').val(data.fwd_date);
		// $('.DSP_status').text(data.fwd_status_nm);
		// $('.TXT_fwd_status').val(data.fwd_status_div);
		// $('.DSP_fwd_status_cre_datetime').text(data.fwd_status_cre_datetime);

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

		$('.TXT_lc_no').val(data.lc_number);
		$('.TXT_po_no').val(data.po_number);
		$('.TXT_date_of_shipment').val(data.shipment_date);

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
		// console.log(111);
		if (data.payment_conditions_div !== '') {
			$('.CMB_payment_conditions_div option[value='+data.payment_conditions_div+']').prop('selected', true);
		} else {
			$('.CMB_payment_conditions_div option:first').prop('selected', true);
		}
		
		$('.TXT_dest_city_div').val(data.dest_city_div);
		$('.DSP_dest_city_nm').text(data.dest_city_nm);
		$('.TXT_dest_country_div').val(data.dest_country_div);
		$('.DSP_dest_country_nm').text(data.dest_country_nm);
		
		$('.TXT_payment_notes').val(data.payment_notes);
		// $('.DSP_currency_div').text(data.currency_div);
		// $('.TXT_our_freight_amt').val(data.our_freight_amt);
		$('.TXT_freight_amt').val(data.freigt_amt);
		$('.TXT_insurance_amt').val(data.insurance_amt);
		// detail sum of amount
		// $('.DSP_total_qty').text(data.total_qty.replace(/\.00$/,''));
		calTotalQty();
		// $('.DSP_total_gross_weight').text(data.total_gross_weight.replace(/\.00$/,''));
		calTotalGrossWeight();
		// $('.DSP_total_net_weight').text(data.total_net_weight.replace(/\.00$/,''));
		calTotalNetWeight();
		// $('.DSP_total_measure').text(data.total_measure.replace(/\.00$/,''));
		calTotalMeasure();

		calTotalDetailAmt();
		calTotalTaxAmt();
		calTotalAmt();
		// $('.DSP_unit_total_gross_weight_nm').text(data.unit_total_gross_weight_nm);
		// $('.DSP_unit_total_gross_weight_div').text(data.unit_total_gross_weight_div);

		// $('.DSP_unit_total_net_weight_nm').text(data.unit_total_gross_weight_nm);
		// $('.DSP_unit_total_net_weight_div').text(data.unit_total_net_weight_div);
		
		// $('.DSP_unit_total_measure_nm').text(data.unit_total_measure_nm);
		// $('.DSP_unit_total_measure_div').text(data.unit_total_measure_div);

		// sum of money
		// $('.DSP_total_detail_amt').text(data.total_detail_amt.replace(/\.00$/,''));
		//$('.TXT_freight_amt').val(data.freigt_amt);
		//$('.TXT_insurance_amt').val(data.insurance_amt);
		// $('.TXT_freight_amt').val(data.freigt_amt.replace(/\.00$/,''));
		// $('.DSP_tax_amt').text(data.total_detail_tax.replace(/\.00$/,''));
		// $('.DSP_total_amt').text(data.total_amt.replace(/\.00$/,''));
		$('.DSP_carton_total_qty').text(data.total_carton_qty);
		$('.DSP_carton_total_net_weight').text(data.total_carton_net_weight);
		$('.DSP_carton_total_gross_weight').text(data.total_carton_gross_weight);
		$('.DSP_carton_total_measure').text(data.total_carton_measure_weight);
		// set item footer
		if (data.storage_user_cd != '') {
			$('.CMB_storage_user_cd option[value='+data.storage_user_cd+']').prop('selected', true);
		} else {
			$('.CMB_storage_user_cd option:first').prop('selected', true);
		}
		$('.TXT_country_of_origin').val(data.country_of_origin);
		$('.TXT_manufacture').val(data.manufacture);
		$('.TXT_sign_cd').val(data.sign_user_cd);
		$('.DSP_sign_nm').text(data.sign_user_nm);
		$('.TXA_inside_remarks').val(data.inside_remarks);
		//
		var currency_div = $('.currency_div').find('option:selected').val();

		if (currency_div == 'JPY') {
			$('#table-invoice tbody tr').find('.price').addClass('currency_JPY');
		} else {
			$('#table-invoice tbody tr').find('.price').removeClass('currency_JPY');
		}
		//
		$('.TXT_invoice_date').val(data.inv_date);
		$('.TXT_sales_date').val(data.sales_date);
	} catch (e) {
		console.log('setItemFwdHeader: ' + e.message);
	}
}
/**
 * validate Accept No
 * 
 * @author : ANS806 - 2018/04/19- create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function validateInvoice() {
	try {
		_clearErrors();
		var error 	= true;
		if ($('.invoice_cd').val() == '') {
			$('.invoice_cd').errorStyle(_MSG_E001);
			error 	= false;
		}
		return error;
	} catch (e) {
		console.log('validateInvoice: ' + e.message);
	}
}
/**
 * delete accept detail
 * 
 * @author : ANS806 - 2018/04/19 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function deleteInvoice() {
	try {
		var _inv_no = $('.TXT_inv_no').val();
		$.ajax({
	        type        :   'POST',
	        url         :   '/invoice/invoice-detail/delete',
	        dataType    :   'json',
	        data        :   {inv_no : _inv_no},
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		if (res.deposit_no == '') {
	            			jMessage(res.error_cd, function(r) {
	                        	$('.TXT_inv_no').errorStyle(_text['E005']);
	                        });
	            		} else {
	            			//catch DB error and display
			            	var msg_E485 = _text[res.error_cd].replace('{0}', res.deposit_no);
			            	jMessage_str(res.error_cd, msg_E485, '', msg_E485);
	            		}
	            		
	            	} else {
	            		jMessage('I002', function(r) {
		                	if(r){
		                		$('.TXT_inv_no').val('');
		                		$('input[name="RDI_inv_data_div"]').prop("disabled", false);
		                		emptyInputAfterRefer(true);
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
        console.log('deleteInvoice: ' + e.message)
    }
}
/**
 * invoice export
 * 
 * @author : ANS806 - 2018/03/02 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function invoiceExport(file_excel, t_insert, url) {
    try {
        var inv_no = {
        	inv_no 	: $('.TXT_inv_no').val()
        };
        $.ajax({
            type        :   'POST',
            url         :   url,
            dataType    :   'json',
			loading		:	true,
            data        :   {
                        inv_no      : inv_no,
                        t_insert    : t_insert,
                        file_excel  : file_excel
            },
            success: function(res) {
                if (res.response) {
                    if (res.error_cd != '') {
                        jMessage(res.error_cd, function(r) {
                        	$('.TXT_inv_no').errorStyle(_text['E005']);
                        });
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
        console.log('invoiceExport: ' + e.message)
    }
}
/**
 * Update database and print list
 * 
 * @author : ANS806- 2018/04/20 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postPackingList() {
	try {
		var data = {
        	inv_no 	: $('.TXT_inv_no').val()
        };
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/invoice-detail/export-excel-list',
	        dataType    :   'json',
	        data        :   {update_list: data},
			loading		:	true,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd, function(r) {
                        	$('.TXT_inv_no').errorStyle(_text['E005']);
                        });
	            	} else {
	            		//download excel
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
		   
	} catch (e) {
         alert('postPackingList' + e.message);
    }
}
/**
 * output Excel
 * 
 * @author : ANS804 - 2018/03/13 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function printMark(inv_no) {
    try {
        $.ajax({
            type        :   'POST',
            url         :   '/export/invoice-detail/print-mark',
            dataType    :   'json',
            data        :   {inv_no: inv_no},
			loading		:	true,
            success: function(res) {
                if (res.response) {
                	if (res.error_cd != '') {
	            		jMessage(res.error_cd, function(r) {
                        	$('.TXT_inv_no').errorStyle(_text['E005']);
                        });
	            	} else {
	                    jMessage('I004', function(r) {
	                        if(r) {
	                            location.href = res.fileName;
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
        console.log('outputExcel:' + e.message)
    }
}

function checkCarton() {
	var flag = false
	$('#table-carton tbody tr').each(function() {
		if ($(this).find('.DSP_fwd_detail_no_table_carton').text() !== '' && 
			$(this).find('.DSP_fwd_detail_no_table_carton').text() !== '0') {
			if (($(this).find('.TXT_carton_number').val() !== '') && 
				($(this).find('.TXT_qty_table_carton').val() !== '' && 
					$(this).find('.TXT_qty_table_carton').val() !== '0')) {
				flag = true;
			}
		}
	});
	return flag;
}
function showMsgDetail() {
	try {
		var detail 	= $('#table-carton tbody tr');
		var error 	= 0;
		detail.find('.required_carton:enabled:not([readonly])').each(function() {
			if ($(this).is(':visible')) {
				if ($(this).find('.DSP_fwd_detail_no_table_carton').text() !== '' || 
					$(this).find('.DSP_fwd_detail_no_table_carton').text() !== '0') {
					if(($(this).is("input") || $(this).is("textarea")) &&  ($.trim($(this).val()) == '' || $.trim($(this).val()) == '0')) {
						$(this).errorStyle(_MSG_E001);
					}else if( $(this).is("select") &&  ($(this).val() == '' || $(this).val() == undefined) ) {
						$(this).errorStyle(_MSG_E001);
					}else if($(this).is("input[type=checkbox]") && !$(this).is(":checked")){
	                    $(this).errorStyle(_MSG_E001);
	                }
	            }
			}
		});
	} catch (e) {
		alert('showMsgDetail: ' + e.message);
	}
}