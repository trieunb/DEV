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

$(document).ready(function () {
	initEvents();
	$('.TXT_supplier_cd').trigger('change');
});

/**
 * init Events
 * @author  :   DaoNX - 2018/05/04 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//init back
		$(document).on('click', '#btn-back', function () {
			sessionStorage.setItem('detail', true);
			location.href = '/stocking/stocking-search';
		});
		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				_clearErrors();
				if(validate()){
					jMessage('C003', function(r) {
						if (r) {
							saveStockingUpdate();
						}
					});
				}
			} catch (e) {
				console.log('#btn-save: ' + e.message);
			}
		});
		// button delete
		$(document).on('click', '#btn-delete', function() {
			try {
				jMessage('C002',function(r){
					if(r){
						deteleStockingUpdate();
					}
				});
			} catch (e) {
				alert('#btn-delete ' + e.message);
			}
		});
 		// calculate price
		$(document).on('change', '.TXT_parts_receipt_qty', function() {
			try {
				var qty 			= $.mbTrim($('.TXT_parts_receipt_qty').val()).replace(/,/g, '') != '' ? parseInt($.mbTrim($('.TXT_parts_receipt_qty').val()).replace(/,/g, '')) : 0;
				var unit_price  	= $.mbTrim($('.DSP_unit_price').text()).replace(/,/g, '') != '' ? parseFloat($.mbTrim($('.DSP_unit_price').text()).replace(/,/g, '')) : 0;
				var purchase_amount = qty * unit_price;
				var purchase_detail_amt_round_div = $.mbTrim($('#purchase_detail_amt_round_div').val());
				$('.TXT_detail_amt').val(_roundNumeric(purchase_amount, purchase_detail_amt_round_div, 0));
				$('.TXT_detail_amt').trigger('blur');
			} catch (e) {
				console.log('.TXT_parts_receipt_qty: ' + e.message);
			}
		});	

		//change 仕入先コード 
		$(document).on('change', '.TXT_supplier_cd', function() {
			try {
				_getClientName($.mbTrim($(this).val()), $(this), '', true);
			} catch (e) {
				console.log('.TXT_supplier_cd: ' + e.message);
			}
		});
	} catch (e) {
		console.log('initEvents: ' + e.message);
	}
}
/**
 * save stocking update
 * 
 * @author      :   ANS796 - 2018/06/27 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function saveStockingUpdate() {
	try{
		var data  = {
			'parts_order_no' 		: $.mbTrim($('.DSP_parts_order_no').text()),
			'purchase_no' 			: $.mbTrim($('.DSP_purchase_no').text()),
			'purchase_detail_no' 	: $.mbTrim($('.DSP_purchase_detail_no').text()),
			'purchase_date' 		: $.mbTrim($('#TXT_purchase_date').val()),
			'supplier_cd' 			: $.mbTrim($('.TXT_supplier_cd').val()),
			'parts_cd' 				: $.mbTrim($('.DSP_parts_cd').text()),
			'parts_receipt_qty' 	: $.mbTrim($('.TXT_parts_receipt_qty').val()).replace(/,/g, ''),
			'detail_amt' 			: ($.mbTrim($('.TXT_detail_amt').val()) == '') ? '0' : $.mbTrim($('.TXT_detail_amt').val()).replace(/,/g, ''),
			'detail_remarks' 		: $.mbTrim($('.TXT_detail_remarks').val()),
		};
	    $.ajax({
	        type        :   'POST',
	        url         :   '/stocking/stocking-update/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	if(res.data_err != null){
		        		jMessage('E005', function(r){
		        			if(r){
		        				for(var i = 0; i < res.data_err.length; i++){
		        					$('.'+res.data_err[i]['item_err']).errorStyle(_text[res.data_err[i]['msg_no']]);
		        				}
		        			}
		        		});
		        	} else {
	            		jMessage('I003', function(r){
		                	if(r){
		                		
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
        console.log('saveStockingUpdate: ' + e.message)
    }
}
/**
 * save stocking update
 * 
 * @author      :   ANS796 - 2018/06/27 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function deteleStockingUpdate() {
	try{
		var data  = {
			'parts_order_no' 		: $.mbTrim($('.DSP_parts_order_no').text()),
			'purchase_no' 			: $.mbTrim($('.DSP_purchase_no').text()),
			'purchase_detail_no' 	: $.mbTrim($('.DSP_purchase_detail_no').text()),
			'purchase_date' 		: $.mbTrim($('#TXT_purchase_date').val()),
			'supplier_cd' 			: $.mbTrim($('.TXT_supplier_cd').val()),
			'parts_cd' 				: $.mbTrim($('.DSP_parts_cd').text()),
			'parts_receipt_qty' 	: $.mbTrim($('.TXT_parts_receipt_qty').val()).replace(/,/g, ''),
			'detail_amt' 			: $.mbTrim($('.TXT_detail_amt').val()).replace(/,/g, ''),
			'detail_remarks' 		: $.mbTrim($('.TXT_detail_remarks').val()),
		};
	    $.ajax({
	        type        :   'POST',
	        url         :   '/stocking/stocking-update/delete',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if(res.error_cd != ''){
            		jMessage(res.error_cd);
            	}else if(res.response == true){
	            	jMessage('I002',function(r){
						if(r){
							$('#btn-back').trigger('click');
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
        console.log('saveStockingUpdate: ' + e.message)
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
