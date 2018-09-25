/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/06/06
 * 作成者		:	ANS804
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	COMPONENT ORDER
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */
$(document).ready(function () {
    initEvents();
	_initRowTable('table-order', 'table-row', 1);

	disableMode(mode);

	if(mode !== 'I' && $('#TXT_parts_order_no').val() != ''){
		$('#TXT_parts_order_no').trigger('change');
	}
});
/**
 * init Events
 * @author  :   ANS804 - 2018/06/06 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		_dragLineTable('table-order', true);
		// remove row table
		$(document).on('click','.remove-row',function(e){
			try {
				var _this = $(this)
				jMessage('C002', function(r){
					if (r) {
						if ($('#table-order tbody tr.tr-table').length > 1) {
							_this.closest('tr').remove();
							_updateTable('table-order', true);
							autoNumberOrderTable();
						} else {
							_this.closest('tr').remove();
							var row = $("#table-row tr.tr-table").clone();
							$('.table-order tbody').append(row);
						}
					}
				});
			} catch (e) {
				console.log('click .remove-row: ' + e.message);
			}
		});
		//add row
		$(document).on('click', '#btn-add-row', function () {
			try {
				var row       = $("#table-row tr.tr-table").clone();				
				var col_index =  $('.table-order tbody tr').length + 1;

				if(col_index <= 30) {
					$('.table-order tbody').append(row);
					_updateTable('table-order', true);
					$('.table-order tbody tr:last .TXT_parts_cd').focus();
				}
			} catch (e) {
				console.log('add new row: ' + e.message);
			}
		});
		//init back
		$(document).on('click', '#btn-back', function () {
			try {
				sessionStorage.setItem('detail', true);
				location.href = '/component-order/order-search';
			} catch (e) {
				console.log('click #btn-back: ' + e.message);
			}
		});
 		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				_clearErrors();

				// get number rows
				var row_detail = $('#table-order tbody tr.tr-table').length;

				if (mode == 'I') {
					var msg = 'C001';
				} else {
					var msg = 'C003';
				}
				// validate
				if(validate($('body'))){
					if(row_detail == 0) {
						jMessage('E004');
						return false;
					} else if (!validateTable()) {
						if (validateLineEffect()) {// have line effect
							raiseErrorE004();
							$('.table-order .error-item:first').focus();
						} else { // not have line effect
							jMessage('E004', function(r) {
								raiseErrorE004();
								$('.table-order .error-item:first').focus();
							});
						}								
						return false;
					} else {
						jMessage(msg, function(r) {
							if (r) {
								saveOrderDetail();
							}
						});
					}
				}
			} catch (e) {
				console.log('#btn-save: ' + e.message);
			}
		});
		// button delete
		$(document).on('click', '#btn-delete', function() {
			try {
				if(validateOrder()){
					jMessage('C002', function(r) {
						if (r) {
							deleteOrderDetail();
						}
					});	
				}		
			} catch (e) {
				console.log('#btn-delete ' + e.message);
			}
		});
 		//btn-approve
 		$(document).on('click', '#btn-approve', function(){
			try {
 				if(validateOrder()){
					jMessage('C005', function(r) {
						if (r) {
	                        postApproved();
						}
					});
				}		   
			} catch (e) {
				console.log('#btn-approve: ' + e.message);
			}
 		});
 		//btn cancel approve
 		$(document).on('click', '#btn-cancel-approve', function(){
 			try {
 				if(validateOrder()){
 					jMessage('C006', function(r) {
						if (r) {
							postCancelApproved();
						}
					});
				}		   
			} catch (e) {
				console.log('#btn-cancel-approve ' + e.message);
			}
 		});
		// btn-issue
 		$(document).on('click', '#btn-issue', function(){
 			try {
				jMessage('C004', function(r) {
					if (r) {
						componentOrderExport();
					}
				});
			} catch (e) {
				console.log('#btn-issue ' + e.message);
			}
 		});
		// Change 注文番号
		$(document).on('change', '#TXT_parts_order_no', function() {
			try {
				if( $('#TXT_parts_order_no').val() != ""){
					referPartsOrder();
				} else {
					setItemReferPartsOrder({});
					_removeErrorStyle($('#TXT_parts_order_no'));
					_initRowTable('table-order', 'table-row', 1);
				}
			} catch (e) {
				console.log('change #TXT_parts_order_no: ' + e.message);
			}
		});
 		// change supplier cd
		$(document).on('change', '#TXT_supplier_cd', function() {
			try {
				getClientName($(this).val().trim(), $(this), '', false);
			} catch (e) {
				console.log('#page-size: ' + e.message);
			}
		});
 		//change item cd
		$(document).on('change', '#TXT_parts_order_date', function() {
			try {
				getTaxRate(true);
			} catch (e) {
				console.log('change .TXT_parts_cd: ' + e.message);
			}
		});
 		//change item cd
		$(document).on('change', '.TXT_parts_cd', function() {
			try {
				var parts_cd   = $(this).val().trim();
				var rowCurrent = $(this).closest('tr.tr-table');

				deleteValueRow(rowCurrent);

				if (parts_cd != '') {
					referComponent(parts_cd, $(this), '');
				} else {					
					rowCurrent.find(':input').val('');
					rowCurrent.find('.DSP_item_nm').text('');
					rowCurrent.find('.DSP_specification').text('');
					rowCurrent.find('.DSP_unit').text('');
					rowCurrent.find('.DSP_stock_available_qty').text('');
					rowCurrent.find('.TXT_parts_cd').focus();
				}
			} catch (e) {
				console.log('change .TXT_parts_cd: ' + e.message);
			}
		});
 		// calculate f1,f2,f3
		$(document).on('change', '.TXT_parts_order_qty, .TXT_parts_order_unit_price', function() {
			try {
				var row                           = $(this).parents('tr');
				
				var TXT_parts_order_unit_amt      = row.find('.TXT_parts_order_unit_amt').val().trim();
				
				var TXT_parts_order_qty           = row.find('.TXT_parts_order_qty').val().trim().replace(/,/g,'') != '' ? parseInt(row.find('.TXT_parts_order_qty').val().trim().replace(/,/g,'')) : '';
				var TXT_parts_order_unit_price    = row.find('.TXT_parts_order_unit_price').val().trim().replace(/,/g,'') != '' ? parseFloat(row.find('.TXT_parts_order_unit_price').val().trim().replace(/,/g,'')) : '';
				
				var purchase_detail_tax_round_div = $('#purchase_detail_tax_round_div').val().trim();
				var tax_rate                      = !isNaN(parseFloat($('#tax_rate').val().trim())) ? parseFloat($('#tax_rate').val().trim()) : 0;

				if (!!TXT_parts_order_qty && !!TXT_parts_order_unit_price) {
					var purchase_detail_amt_round_div = $('#purchase_detail_amt_round_div').val().trim();
					TXT_parts_order_unit_amt          = parseFloat((TXT_parts_order_qty*TXT_parts_order_unit_price).toFixed(2));

					tax_detail                        = _roundNumeric(TXT_parts_order_unit_amt * tax_rate / 100, purchase_detail_tax_round_div, 0);

			    	//refer tax detail
			    	row.find('.tax_detail').val(tax_detail);

					// refer total_amt detail
					row.find('.TXT_parts_order_unit_amt').val(formatNumber(_roundNumeric(TXT_parts_order_unit_amt, purchase_detail_amt_round_div, 0)));

					// clear error
					row.find('.TXT_parts_order_unit_amt').removeErrorStyle();
					row.find('.TXT_parts_order_unit_amt').removeClass('error-item');
				} else {
					row.find('.tax_detail').val(0);
					row.find('.TXT_parts_order_unit_amt').val(0);
				}
				checkAmtDetail($(this));
				referTotalAmt();
			} catch (e) {
				console.log('#cal sum, tax: ' + e.message);
			}
		});
 		// refer
		$(document).on('change', '.TXT_parts_order_unit_amt', function() {
			try {
				referTotalAmt();
			} catch (e) {
				console.log('#cal tax: ' + e.message);
			}
		});
 		//don't fill negative number
		$(document).on('keypress', '.TXT_parts_order_qty', function(e) {
			try {
				var key = e.which || e.keyCode || 0
				// check type -
				if (key == 45) {
					e.preventDefault();
				}
			} catch (e) {
				console.log('keypress .TXT_parts_order_qty: ' + e.message);
			}
		});
	} catch (e) {
		console.log('initEvents: ' + e.message);
	}
}
/**
 * validate
 *
 * @author      :   ANS804 - 2018/06/22 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function validate(element) {
	var error = 0;
	try {
		_clearErrors();
		element.find('.required:not(.TXT_parts_cd):not(.TXT_parts_order_qty)').each(function() {
			if ($(this).is(':visible')) {
				if(($(this).is("input") || $(this).is("textarea")) &&  $.trim($(this).val()) == '' ) {
					$(this).errorStyle(_MSG_E001);
					error ++;
				}
			}
		});

		$('input.error-item:first').focus();

		if( error > 0 ) {
			return false;
		} else {
			return true;
		}
	} catch(e) {
		console.log('validate: ' + e.toString());
	}
}

function checkAmtDetail(element) {
	try {
		var parent 			= element.parents('tr.tr-table');
		var amount 			= parseFloat(parent.find('.TXT_parts_order_unit_amt').val().trim().replace(/,/g, ''));

		if (amount > 9999999999999.99) {
			parent.find('.TXT_parts_order_unit_amt').addClass('error-numeric');
			parent.find('.TXT_parts_order_qty').addClass('error-numeric');
			parent.find('.TXT_parts_order_unit_price').addClass('error-numeric');
		} else {
			parent.find('.TXT_parts_order_unit_amt').removeClass('error-numeric');
			parent.find('.TXT_parts_order_qty').removeClass('error-numeric');
			parent.find('.TXT_parts_order_unit_price').removeClass('error-numeric');
		}
	} catch (e) {
		console.log('checkAmtDetail: ' + e.message);
	}
}
/**
 * validateTable
 *
 * @author      :   ANS804 - 2018/06/22 - create
 * @param       :  	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function validateTable(){
 	try {
		var error = 0;
		_clearErrors();

		$('.table-order tbody .tr-table').each(function() {
			$(this).find(':input.required').each(function(){
				if($.trim($(this).val()) == '' || parseInt($.trim($(this).val())) == 0) {
					error++;
				}
			});

			if($(this).find('.error-numeric').length > 0) {
				error++;
			}
		});

		return error > 0 ? false : true;
	} catch(e) {
		console.log('validateTable: ' + e.toString());
	}
}
/**
 * validate line effect
 *
 * @author      :   ANS804 - 2018/06/22 - create
 * @param       :  	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function validateLineEffect(){
 	try {
		var checkHaveLineEffect = true;

		$('.table-order tbody .tr-table').each(function() {
			var parts_cd        = $(this).find('.TXT_parts_cd').val();
			var parts_order_qty = $(this).find('.TXT_parts_order_qty').val();
			
			if($.trim(parts_cd) == '' || $.trim(parts_order_qty) == '' || parseInt(parts_order_qty) == 0) {
				checkHaveLineEffect = false;
			}
		});

		return checkHaveLineEffect;
	} catch(e) {
		console.log('validateLineEffect: ' + e.toString());
	}
}
/**
 * raiseErrorE004
 *
 * @author      :   ANS804 - 2018/06/22 - create
 * @param       :  	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function raiseErrorE004(){
 	try {
		_clearErrors();

		$('.table-order tbody .tr-table').each(function() {
			$(this).find(':input.required').each(function(){
				if($.trim($(this).val()) == '' || parseInt($.trim($(this).val())) == 0) {
					$(this).errorStyle(_MSG_E001);
				}
			});
		});
	} catch(e) {
		console.log('raiseErrorE004: ' + e.toString());
	}
}
/**
 * get data of input
 * 
 * @author      :   ANS804 - 2018/03/27 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 * @see :
 */
function getDataSave() {
	try {
		var parts_order_detail = [];
		var data_insert_update = {};

		$('.table-order tbody tr.tr-table').each(function() {
			var _this = $(this);
			// quantity request parts
			var parts_qty    = parseInt(_this.find('.TXT_parts_order_qty').val().trim().replace(/,/g,''));

			//Do not register records whose value is 0
			if(!isNaN(parts_qty) && parts_qty > 0) {
				var t_parts_order_d = {
					'parts_cd'      			: _this.find('.TXT_parts_cd').val(),
					'parts_order_qty' 			: parseInt(_this.find('.TXT_parts_order_qty').val().trim().replace(/,/g,'')),
					'parts_order_unit_price' 	: !isNaN(parseFloat(_this.find('.TXT_parts_order_unit_price').val().trim().replace(/,/g,''))) ? parseFloat(_this.find('.TXT_parts_order_unit_price').val().trim().replace(/,/g,'')) : 0,
					'parts_order_unit_amt' 		: !isNaN(parseFloat(_this.find('.TXT_parts_order_unit_amt').val().trim().replace(/,/g,''))) ? parseFloat(_this.find('.TXT_parts_order_unit_amt').val().trim().replace(/,/g,'')) : 0,
					'parts_order_detail_tax'	: _this.find('.tax_detail').val().trim().replace(/,/g,''),
					'detail_remarks'  			: _this.find('.TXT_detail_remarks').val(),
				};

				parts_order_detail.push(t_parts_order_d);
			}
		});

		var data_insert_update = {
			'mode'				 : mode, 
			//key
			'parts_order_no'	 : $('#TXT_parts_order_no').val(),
			//Basic
			'parts_order_date'	 : $('#TXT_parts_order_date').val(),
			'supplier_cd'		 : $('#TXT_supplier_cd').val(),
			'supplier_staff_nm'	 : $('#TXT_supplier_staff_nm').val(),
			'parts_order_subject': $('#TXT_subject_nm').val(),
			'expiration_date'	 : $('#TXT_expiration_date').val(),
			'hope_delivery_date' : $('#TXT_hope_delivery_date').val(),
			'total_detail_amt'	 : $('#DSP_total_detail_amt').text().trim().replace(/,/g,''),
			'total_detail_tax'	 : $('#total_detail_tax').val().trim().replace(/,/g,''),
			'total_header_tax'	 : $('#DSP_tax_amt').text().trim().replace(/,/g,''),
			'total_tax'	         : $('#DSP_tax_amt').text().trim().replace(/,/g,''),
			'total_amt'	         : $('#DSP_total_amt').text().trim().replace(/,/g,''),
			'remarks'			 : $('#TXT_remarks').val(),

			'parts_order_detail' : parts_order_detail.length == 0 ? '' : parts_order_detail,
		};

		return data_insert_update;
	} catch(e) {
        console.log('getDataSave: ' + e.message);
    }
}
/**
 * save order detail
 * 
 * @author      :   ANS804 -  2018/06/22 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function saveOrderDetail() {
	try{
	    var data = getDataSave();
	    $.ajax({
	        type        :   'POST',
	        url         :   '/component-order/order-detail/save-order-detail',
	        dataType    :   'json',
	        loading		: 	true,
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd, function(r){
		                	if(r){
		                		setErrorParts(res.header_error, res.list_parts_error);
		                	}
		                });
	            	} else {
	            		var msg = (mode == 'I') ? 'I001' : 'I003';
	            		jMessage(msg, function(r){
		                	if(r){
		                		mode = 'U';
		                		$('#lable_parts_order_no').addClass('required');
		                		$('#TXT_parts_order_no').val(res.parts_order_no).prop('disabled', false).addClass('required');
		                		referPartsOrder();
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
        console.log('saveOrderDetail: ' + e.message)
    }
}
/**
 * referPartsOrder
 *
 * @author      :   ANS804 - 2018/03/22 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function referPartsOrder(){
	try {
		var parts_order_no = $('#TXT_parts_order_no').val().trim();
		if (parts_order_no == '') return false;

		_clearErrors();

		var data    = {
	    	parts_order_no 		: parts_order_no,
	    	mode 				: mode
	    };

	    $.ajax({
	        type        :   'POST',
	        url         :   '/component-order/order-detail/refer-parts-order',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
				if (res.response) {
					// button
	        		$('.heading-btn-group').html(res.button);
					// Common
					$('#operator_info').html(res.header_html);
					// header
					setItemReferPartsOrder(res.parts_order_info_h);
					// table detail
					$('#table-refer').html(res.parts_order_table);

					// run again tooltip
					$(function () {
					  	$('[data-toggle="tooltip"]').tooltip();
					});

					_removeErrorStyle($('#TXT_parts_order_no'));
					_removeErrorStyle($('#TXT_parts_order_date'));

					if (res.parts_order_info_h['parts_order_status_div'] == '20') {
						_disabldedAllInput();
						$(".TXT_detail_remarks").prop('disabled', false);
						$("#TXT_remarks").prop('disabled', false);
					} else {
						abledAllInput();
					}
					if (res.parts_order_info_h.parts_order_status_div != '20') {
						_dragLineTable('table-order', true);
					}
				} else {
					jMessage(res.error,function(r){
						if(r){
							// Common
							$('#operator_info').html('');
							// header
							setItemReferPartsOrder({});

							_removeErrorStyle($('.TXT_parts_order_no'));							
							$('#TXT_parts_order_no').errorStyle(_text['E005']);	

							_initRowTable('table-order', 'table-row', 1);
							$('#TXT_parts_order_no').focus();
						}
					});
				}

				referTotalAmt();
	        },
	    });
	} catch(e) {
        console.log('referPartsOrder: ' + e.message)
    }
}
/**
 * set item refer Parts order
 * 
 * @author      :   ANS804 - 2018/03/27 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function setItemReferPartsOrder(parts_order_info_h){
	try {
		if (!!parts_order_info_h['parts_order_no']) {
			// Header
			$('#STT').removeClass('hide');
			$('#TXT_parts_order_date').val(parts_order_info_h['parts_order_date']);
			$('.DSP_parts_order_type_div').text(parts_order_info_h['parts_order_type_div_nm']);

			$('#DSP_status').text(parts_order_info_h['parts_order_status_div_nm']);
			$('#DSP_status_tm').text(parts_order_info_h['cre_datetime']);

			$('.DSP_buy_no').text(parts_order_info_h['buy_no']);
			$('.DSP_in_order_no').text(parts_order_info_h['in_order_no']);
			$('.DSP_manufacturing_instruction_number').text(parts_order_info_h['manufacturing_instruction_number']);
			
			$('#TXT_supplier_cd').val(parts_order_info_h['supplier_cd']);
			$('.client_nm').text(parts_order_info_h['supplier_nm']);
			$('#TXT_supplier_staff_nm').val(parts_order_info_h['supplier_staff_nm']);
			$('#TXT_subject_nm').val(parts_order_info_h['parts_order_subject']);
			$('#TXT_expiration_date').val(parts_order_info_h['expiration_date']);
			$('#TXT_hope_delivery_date').val(parts_order_info_h['hope_delivery_date']);

			$('#total_detail_tax').val(parts_order_info_h['total_detail_tax']);
			$('#DSP_total_detail_amt').text(parts_order_info_h['total_detail_amt']);
			$('#DSP_tax_amt').text(parts_order_info_h['total_tax']);
			$('#DSP_total_amt').text(parts_order_info_h['total_amt']);

			$('#TXT_remarks').val(parts_order_info_h['remarks']);

			// get tax
			getTaxRate(true);
		} else {
			$('#operator_info').html('');

			$('#STT').addClass('hide');
			$('#TXT_parts_order_date').val('');

			$('#DSP_status').text('');
			$('#DSP_status_tm').text('');

			$('.DSP_buy_no').text('');
			$('.DSP_in_order_no').text('');
			$('.DSP_manufacturing_instruction_number').text('');
			
			$('#TXT_supplier_cd').val('');
			$('.client_nm').text('');
			$('#TXT_supplier_staff_nm').val('');
			$('#TXT_subject_nm').val('');
			$('#TXT_expiration_date').val('');
			$('#TXT_hope_delivery_date').val('');

			$('#total_detail_tax').val('');
			$('#DSP_total_detail_amt').text('');
			$('#DSP_tax_amt').text('');
			$('#DSP_total_amt').text('');

			$('#TXT_remarks').val('');

			// footer
			$('#DSP_total_detail_amt').text('');
			$('#DSP_tax_amt').text('');
			$('#DSP_total_amt').text('');

			// refer date current
			referDate();
		}
	} catch (e) {
		console.log('setItemReferPartsOrder: ' + e.message);
	}
}
/**
 * get data is choosen
 * 
 * @author : ANS804 - 2018/06/06 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataExport() {
    try {
		var data_list      = [];
		
		var parts_order_no =  $('#TXT_parts_order_no').val().trim();
		var data           = {
            parts_order_no  : parts_order_no,
        };                      

        data_list.push(data);
        return data_list;
    } catch (e) {
        console.log('getDataExport: ' + e.message);
    }
}
/**
 * order export
 * 
 * @author : ANS804 - 2018/06/06 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function componentOrderExport() {
    try {
        var parts_order_no = getDataExport();
        $.ajax({
            type        :   'POST',
            url         :   '/export/component-order-search-export',
            dataType    :   'json',
            loading     :   true,
            data        :   {
            		parts_order_no 				: parts_order_no,
                    report_number_parts_order   : $('#report_number_parts_order').val().trim()
            },
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
        console.log('componentOrderExport: ' + e.message)
    }
}
/**
 * autoNumberOrderTable
 * 
 * @author : ANS804 - 2018/06/06 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function autoNumberOrderTable() {
    try {
        var i = 1;
        $("#table-order tbody tr.tr-table").each(function(){
        	$(this).find('.DSP_no').text(i++);
        });
    }  catch(e) {
        console.log('autoNumberOrderTable: ' + e.message)
    }
}
/**
 * refer component
 * 
 * @author : ANS804 - 2018/06/06 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referComponent(parts_cd, element, callback) {
	try {
		$.ajax({
			type 		: 'post',
			url 		: '/component-order/order-detail/refer-component',
			dataType	: 'json',
			data 		: {
							parts_cd 		: parts_cd,
							supplier_cd 	: $('#TXT_supplier_cd').val().trim()
						},
			success: function(res) {
            	_removeErrorStyle(element.parents('.popup').find('.TXT_parts_cd'));

				if (res.response) {
					element.closest('tr').find('.TXT_parts_cd').text(res.data.item_cd);
					element.closest('tr').find('.DSP_item_nm').text(res.data.item_nm);
					element.closest('tr').find('.DSP_item_nm').attr('title',res.data.item_nm);
					element.closest('tr').find('.DSP_specification').text(res.data.specification);
					element.closest('tr').find('.DSP_specification').attr('title',res.data.specification);
					element.closest('tr').find('.DSP_unit').text(res.data.unit);
					element.closest('tr').find('.TXT_parts_order_unit_price').val(res.data.purchase_unit_price_JPY);

					// run again tooltip
					$(function () {
					  $('[data-toggle="tooltip"]').tooltip();
					});
				} else {
					element.closest('tr').find('.DSP_item_nm').text('');
					element.closest('tr').find('.DSP_specification').text('');
					element.closest('tr').find('.DSP_unit').text('');
				}

				referTotalAmt();
				element.closest('tr').find('.TXT_parts_cd').focus();

				// check callback function
				if (typeof callback == 'function') {
					callback();
				}
			}
		});
	} catch(e) {
        console.log('referComponent: ' + e.message)
    }
}
/**
 * delete value of table detail
 * 
 * @author : ANS804 - 2017/12/26 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function deleteValueRow(element) {
	try {
		element.find(':input:not(.TXT_parts_cd)').val('');
		element.find('.DSP_item_nm').text('');
		element.find('.DSP_specification').text('');
		element.find('.TXT_parts_order_qty').val('');
		element.find('.DSP_unit').text('');

		// remove class error error-numeric
		element.find('.TXT_parts_order_qty').removeClass('error-numeric');
		element.find('.TXT_parts_order_unit_price').removeClass('error-numeric');
		element.find('.TXT_parts_order_unit_amt').removeClass('error-numeric');
	} catch (e) {
		console.log('deleteValueRow: ' + e.message)
	}
}
/**
 * format number to type #,###
 * 
 * @author : ANS804 - 2017/12/26 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function formatNumber(number) {
	try {
		number  = number + '';
		var rgx = /(\d+)(\d{3})/;

	    while (rgx.test(number)) {
	        number = number.replace(rgx, '$1' + ',' + '$2');
	    }

	    return number;
	} catch (e) {
		console.log('formatNumber: ' + e.message)
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
            url         :   '/component-order/order-detail/refer-supplier',
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
                		$('#TXT_supplier_staff_nm').val(res.data['client_staff_nm']);
                	} else {
                		if (deleteKey) {
	                		element.parents('.popup').find('.TXT_client_cd').val('');
	                	}
                		element.parents('.popup').find('.client_nm').text('');
                		$('#TXT_supplier_staff_nm').val('');
                	}
                } else {
                	if (deleteKey) {
                		element.parents('.popup').find('.TXT_client_cd').val('');
                	}
            		element.parents('.popup').find('.client_nm').text('');
            		$('#TXT_supplier_staff_nm').val('');
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
/**
 * refer Total Amt
 * 
 * @author : ANS804 - 2017/12/26 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referTotalAmt() {
    try {
		var sumDetail                      = 0;
		var sumTaxDetail                   = 0;
		
		var sumTaxTotal                    = 0;
		var purchase_summary_tax_round_div = $('#purchase_summary_tax_round_div').val().trim();
		var purchase_detail_amt_round_div  = $('#purchase_detail_amt_round_div').val().trim();
		var tax_rate                       = !isNaN(parseFloat($('#tax_rate').val().trim())) ? parseFloat($('#tax_rate').val().trim()) : 0;

    	$('#table-order tbody tr.tr-table').each(function(){
    		// detail
    		var tax_detail = parseFloat($(this).find('.tax_detail').val().trim().replace(/,/g,''));
    		if(!isNaN(tax_detail)) {
    			sumTaxDetail += tax_detail;
    		}

    		// total
    		var TXT_parts_order_unit_amt = parseFloat($(this).find('.TXT_parts_order_unit_amt').val().trim().replace(/,/g,''));
    		if(!isNaN(TXT_parts_order_unit_amt)) {
    			sumDetail += TXT_parts_order_unit_amt;
    		}
    	});

    	//////////////////////////////////////////////////////////////////////////////////////////////////////
    	// refer tax detail
    	var sumTaxDetail = _roundNumeric(sumTaxDetail, purchase_summary_tax_round_div, 0);
    	$('#total_detail_tax').val(sumTaxDetail);

    	//////////////////////////////////////////////////////////////////////////////////////////////////////
    	// round total
    	// refer total detail
		sumDetail = _roundNumeric(sumDetail, purchase_detail_amt_round_div, 2);
    	$('#DSP_total_detail_amt').text(formatNumber(sumDetail));

    	// refer tax total
    	var sumTaxTotal = _roundNumeric(sumDetail * tax_rate / 100, purchase_summary_tax_round_div, 0);
    	$('#DSP_tax_amt').text(formatNumber(sumTaxTotal));

    	// refer total
    	var sum = _roundNumeric((sumDetail*100 + sumTaxTotal*100)/100, purchase_detail_amt_round_div, 2);
    	$('#DSP_total_amt').text(formatNumber(sum));
    } catch (e) {
        console.log('referTotalAmt' + e.message);
    }
}
/**
 * getTaxRate
 * 
 * @author : ANS804 - 2017/12/26 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getTaxRate(referTotalAmt) {
    try {
		$.ajax({
			type 		: 'post',
			url 		: '/component-order/order-detail/refer-tax',
			dataType	: 'json',
			async 		: false,
			data 		: {
							parts_order_date 	: $('#TXT_parts_order_date').val().trim()
						},
			success: function(res) {
				if (res.response) {
					$('#tax_rate').val(res.tax_rate);
				} else {
					$('#tax_rate').val(0);
				}

				if (referTotalAmt) {
					$('#table-order tr.tr-table .TXT_parts_order_qty').trigger('change');
				}
			}
		});
	} catch(e) {
        console.log('getTaxRate: ' + e.message)
    }
}
/**
 * disableMode
 * 
 * @author : ANS804 - 2017/12/26 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function disableMode(mode) {
    try {
		if (mode == 'I') {
			$('.TXT_parts_order_no').attr('disabled',true);
			$("#TXT_parts_order_no").removeClass("required");
			$("#TXT_parts_order_no").val('');
		} else {
			$("#TXT_parts_order_no").addClass("required");
		}
	} catch(e) {
        console.log('disableMode: ' + e.message)
    }
}
/**
 * check part order no
 * 
 * @author      :   ANS804 - 2018/06/21 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function validateOrder() {
	try {
		_clearErrors();
		var error 	= true;
		if ($('#TXT_parts_order_no').val() == '') {
			$('#TXT_parts_order_no').errorStyle(_MSG_E001);
			error 	= false;
		}
		return error;
	} catch (e) {
		console.log('validateOrder: ' + e.message);
	}
}
/**
 * Update database for approved parts order
 * 
 * @author : ANS804 - 2018/06/01 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postApproved() {
    try {
        $.ajax({
            type        :   'POST',
            url         :   '/component-order/order-detail/approved',
            dataType    :   'json',
            data        :   {
            	parts_order_no : $('#TXT_parts_order_no').val().trim()
            },
            success: function(res) {
                if (res.response) {
                    if (res.error_cd != '') {
                        jMessage(res.error_cd);
                    } else {
                        jMessage('I005', function(r){
                            if(r) {
                                referPartsOrder();
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
    } catch (e) {
        console.log('postApproved: ' + e.message);
    }
}
/**
 * Update database for unapproved parts order
 * 
 * @author : ANS804 - 2018/06/01 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postCancelApproved() {
    try {
        $.ajax({
            type        :   'POST',
            url         :   '/component-order/order-detail/cancel-approved',
            dataType    :   'json',
            data        :   {
            	parts_order_no : $('#TXT_parts_order_no').val().trim()
            },
            success: function(res) {
                if (res.response) {
                    if (res.error_cd != '') {
                        jMessage(res.error_cd);
                    } else {
                        jMessage('I006', function(r){
                            if(r) {
                                referPartsOrder();
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
    } catch (e) {
        console.log('postApproved: ' + e.message);
    }
}
/**
 * abled all input
 * @author      :   ANS804 - 2018/06/01 - create
 * @param       :   
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function abledAllInput(callback) {
	try {
		$(":input").prop('disabled', false);
	} catch (e) {
		alert('abledAllInput: ' + e.message);
	}
}
/**
 * delete order detail
 * 
 * @author      :   ANS804 - 2018/06/26 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :  
 */
function deleteOrderDetail() {
	try {
		var parts_order_no = $('#TXT_parts_order_no').val().trim();

		$.ajax({
	        type        :   'POST',
	        url         :   '/component-order/order-detail/delete-order',
	        dataType    :   'json',
	        data        :   {parts_order_no : parts_order_no},
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		jMessage('I002', function(r){
		                	if(r){
		                		$(':input').val('').attr('disabled', false);
		      //           		$(":input").each(function (i) { 
								// 	$(this).attr('disabled', false);
								// });
		                		setItemReferPartsOrder({});
		                		
		                		_initRowTable('table-order', 'table-row', 1);
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
        console.log('deleteOrderDetail: ' + e.message)
    }
}
/**
 * refer date
 * 
 * @author      :   ANS804 - 2018/06/26 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :  
 */
function referDate() {
	try {
		var date  = new Date();
		var year  = date.getFullYear();
		var month = date.getMonth() + 1;
		var date  = date.getDate();

		if (month.toString().length == 1) {
			month = '0'+month.toString();
		}

	    $('#TXT_parts_order_date').val([year, month, date].join('/'));
	}  catch(e) {
        console.log('deleteOrderDetail: ' + e.message)
    }
}
/**
 * set error parts
 * 
 * @author : ANS804 - 2018/06/27 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setErrorParts(header_error, list_parts_error) {
 	try {
 		if (!!header_error) {
 			$.each(header_error,function(index, value){
 				if (value != '' && $('#' + value).val() != '') {
 					$('#' + value).errorStyle(_text['E005']);
 				}
 			});
 		}

 		if (!!list_parts_error) {
 			$.each(list_parts_error,function(index, value){
 				var position = parseInt(value['position']);
 				if (!isNaN(position)) {
 					$('#table-order tbody tr:nth-child(' + position + ')').find('.TXT_parts_cd').errorStyle(_text['E005']);
 				}
 			});
 		}
 	} catch (e) {
		console.log('setErrorParts: ' + e.message)
	}
}