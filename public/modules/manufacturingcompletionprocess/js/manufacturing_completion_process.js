/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	DuyTP
 *
 * 更新日		: 	2018/04/19
 * 更新者		: 	HaVV - ANS817 - havv@ans-asia.com
 * 更新内容		: 	Development
 *
 * @package		:	INVOICE
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */
var obj_empty_header = {
	manufacture_no 				: 	'',
	production_instruction_date : 	'',
	production_status 			: 	'',
	in_order_no 				: 	'',
	internal_ordering_date 		: 	'',
	hope_delivery_date 			: 	'',
	product 					: 	'',
	manufacture_qty 			: 	'',
	complete_qty 				: 	'',
	remain_amount 				: 	'',
};

$(document).ready(function () {
	initEvents();
	
	if ($('#TXT_manufacture_no').val().trim() != '') {
		$('#TXT_manufacture_no').trigger('change');
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
		_dragLineTable('table-manufacturing-completion-process', true);

		//add row
		$(document).on('click', '#btn-add-row', function () {
			_addNewRowTable('table-manufacturing-completion-process', 'table-row', null, updateTable);
		});

		// remove row table
		$(document).on('click','.remove-row',function(e){
			_removeRowTable('table-manufacturing-completion-process', $(this), 'C002', true, function () {
				updateTable();
				calculateTotalCompleteQty();
			});
		});

		// click btn-save
		$(document).on('click','#btn-save',function(e){
			try {
				var isCheck1 = checkInvalidRow();
				var isCheck2 = _validate();
				var isCheck3 = validateCompleteDate();
				var isCheck4 = validateCompleteQty();

				if (!isCheck1 || !isCheck2) {
					if(!isCheck1){
						//check invalid row NOT OK
						jMessage('E004',function(r){
							if(r){
								//focus first item error
								$('.error-item:first').focus();
							}
						});
					}

					return;
				}

				if (!isCheck4) {
					//validate complete qty NOT OK
					jMessage('E472');
					return;
				}

				if (!isCheck3) {
					//validate complete date NOT OK
					jMessage('C470',function(r){
						if(r){
							//validate ok
							processSave();
						}
					});

					return;
				}

				//validate ok
				processSave();
			} catch (e) {
				alert('#btn-save ' + e.message);
			}
		});

		// click btn-back
		$(document).on('click','#btn-back',function(e){
			if (from == 'ManufacturingInstructionSearch') {
				sessionStorage.setItem('detail', true);
				location.href = '/manufactureinstruction/manufacturing-instruction-search';
			}
		});

		//change TXT_manufacture_no
		$(document).on('change', '#TXT_manufacture_no', function () {
			var val = $(this).val();
			referManufactureNo(val);
		});

		//change TXT_complete_qty
		$(document).on('change', '.TXT_complete_qty', function () {
			var val = parseInt($(this).val(), 10);
			if (val == 0) {
				$(this).val('');
			}
			calculateTotalCompleteQty();
		});

		//disable sign '-' for TXT_complete_qty
		$(document).on('keypress', '.TXT_complete_qty', function(e) {
		   	try {
		    	var key = e.which || e.keyCode || 0
		    	// check type -
		    	if (key == 45) {
		     		e.preventDefault();
		    	}
		   	} catch (e) {
		    	console.log('keypress .TXT_complete_qty: ' + e.message);
		   	}
		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}

/**
 * calculate total complete qty
 *
 * @author		:	ANS817 - 2018/04/24 - create
 * @params		:	null
 * @return		:	null
 */
function calculateTotalCompleteQty() {
	try {
		var manufacture_qty  = convertStringToNumeric($('#DSP_manufacture_qty').html());
		var sum_complete_qty = 0;
		var remain_amount    = 0;

		$('.TXT_complete_qty').each(function () {
			var complete_qty = convertStringToNumeric($(this).val());
			sum_complete_qty += complete_qty;
		});

		remain_amount = manufacture_qty - sum_complete_qty;

		$('#DSP_complete_qty').html(addCommas(sum_complete_qty));
		$('#DSP_remain_amount').html(addCommas(remain_amount));
	} catch (e) {
		alert('calculateTotalCompleteQty: ' + e.message);
	}
}

/**
 * update Table: format datepicker, update NO, set tabindex
 *
 * @author		:	ANS817 - 2018/04/19 - create
 * @params		:	null
 * @return		:	null
 */
function updateTable() {
	try {
		_formatDatepicker();
		_updateTable('table-manufacturing-completion-process', true);
		_setTabIndexTable('table-manufacturing-completion-process');
		_autoFormattingDate("input.datepicker");
	} catch (e) {
		alert('updateTable: ' + e.message);
	}
}

/**
 * refer manufacture no
 *
 * @author		:	ANS817 - 2018/04/19 - create
 * @params		:	null
 * @return		:	null
 */
function referManufactureNo(manufacture_no) {
	try {
		//clear all error
		_clearErrors();

	    $.ajax({
	        type        :   'POST',
	        url         :   '/manufacturing-completion-process/refer-manufacture-no',
	        dataType    :   'json',
	        data        :   {
	        	'manufacture_no'	: 	manufacture_no
	        },
	        success: function(res) {
	            if (res.response) {
	            	//set header info
	            	$('#operator_info').html(res.header);

	            	//set item in header
	            	setValueItemHeader(res.data, true);

	            	//set table
	            	$('#div-table').html(res.table);
	            	updateTable();
					calculateTotalCompleteQty();
					_setAttributeCommon();
					_dragLineTable('table-manufacturing-completion-process', true);
	            } else {
            		clearAllItem();
            		//focus first item
					$('#TXT_manufacture_no').focus();
	            }
	        },
	    });
	} catch (e) {
		alert('referManufactureNo: ' + e.message);
	}
}

/**
 * clear all item
 *
 * @author		:	ANS817 - 2018/04/19 - create
 * @params		:	null
 * @return		:	null
 */
function clearAllItem() {
	try {
		//clear operator_info
		$('#operator_info').html('');

		//clear item in header
		setValueItemHeader(obj_empty_header, false);

		//clear item in table
		$('#table-manufacturing-completion-process tbody tr').remove();
		//add new row in table
		$('#btn-add-row').trigger('click');
	} catch (e) {
		alert('clearAllItem: ' + e.message);
	}
}

/**
 * set value of item in header
 *
 * @author		:	ANS817 - 2018/04/19 - create
 * @params		:	obj - Object
 * @return		:	null
 */
function setValueItemHeader(obj, isRefer) {
	try {
		if (obj.length == 0) {
			obj = obj_empty_header;
		}

		if (isRefer) {
			$('#TXT_manufacture_no').val(obj.manufacture_no);
		}
		$('#DSP_production_instruction_date').html(obj.production_instruction_date);
		$('#DSP_production_status').html(obj.production_status);
		$('#DSP_in_order_no').html(obj.in_order_no);
		$('#DSP_internal_ordering_date').html(obj.internal_ordering_date);
		$('#DSP_hope_delivery_date').html(obj.hope_delivery_date);
		$('#DSP_product').html(obj.product);
		$('#DSP_manufacture_qty').html(obj.manufacture_qty);
		$('#DSP_complete_qty').html(obj.complete_qty);
		$('#DSP_remain_amount').html(obj.remain_amount);
	} catch (e) {
		alert('setValueItemHeader: ' + e.message);
	}
}

/**
 * check invalid 1 row
 *
 * @author      :   ANS817 - 2018/04/19 - create
 * @param       : 	
 * @return      :   boolean
 * @access      :   public
 * @see         :   init
 */
function checkInvalidRow() {
	try {
		var flag 	= false;
		$('#table-manufacturing-completion-process tbody tr').each(function() {	
			var check         = 	0;
			var complete_qty  = 	$(this).find('.TXT_complete_qty').val();
			var complete_date =	$(this).find('.TXT_complete_date').val();

			if(complete_qty != ''){
				check++;
			}

			if(complete_date != ''){
				check++;
			}

			if(check == 2){
				flag = true;
			}
		});
		
		return flag;
	} catch (e) {
		alert('checkInvalidRow: ' + e.message);
	}
}

/**
 * validate completion date
 * if 製造指示日　>　完了日 then false
 * else true
 *
 * @author      :   ANS817 - 2018/04/19 - create
 * @param       : 	
 * @return      :   boolean
 * @access      :   public
 * @see         :   init
 */
function validateCompleteDate() {
	try {
		var flag = true;
		var production_instruction_date = new Date($('#DSP_production_instruction_date').html());

		$('#table-manufacturing-completion-process tbody tr').each(function() {	
			var complete_date =	new Date($(this).find('.TXT_complete_date').val());

            if (production_instruction_date.getTime() > complete_date.getTime()) {
                flag = false;
            }
		});
		
		return flag;
	} catch (e) {
		alert('validateCompleteDate: ' + e.message);
	}
}

/**
 * validate complete qty
 * if h8.指示数>h9.完了数 then false
 * else true
 *
 * @author      :   ANS817 - 2018/04/19 - create
 * @param       : 	
 * @return      :   boolean
 * @access      :   public
 * @see         :   init
 */
function validateCompleteQty() {
	try {
		var flag = true;
		var manufacture_qty = parseInt($('#DSP_manufacture_qty').html(), 10);
		var complete_qty    = parseInt($('#DSP_complete_qty').html(), 10);

		if (manufacture_qty < complete_qty) {
			flag = false;
		}
		
		return flag;
	} catch (e) {
		alert('validateCompleteQty: ' + e.message);
	}
}

/**
 * process validateCompleteQty
 *
 * @author      :   ANS817 - 2018/04/20 - create
 * @param       : 	
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function processValidateCompleteQty() {
	try {
		jMessage('C471',function(r){
			if(r){
				//validate OK
				processSave();
			}
		});
	} catch (e) {
		alert('processValidateCompleteQty: ' + e.message);
	}
}

/**
 * process save
 *
 * @author      :   ANS817 - 2018/04/20 - create
 * @param       : 	
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function processSave() {
	try {
		jMessage('C005',function(r){
			if(r){
				postSave();
			}
		});
	} catch (e) {
		alert('processSave: ' + e.message);
	}
}

/**
 * save
 *
 * @author      :   ANS817 - 2018/04/19 - create
 * @param       : 	
 * @return      :   boolean
 * @access      :   public
 * @see         :   init
 */
function postSave() {
	try {
		//get data from view
		var data = getDataFromView();

		$.ajax({
	        type        :   'POST',
	        url         :   '/manufacturing-completion-process/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	if(res.error_cd != ''){
            		jMessage(res.error_cd, function(r) {
            			if (r) {
            				if (res.error_cd == 'E005') {
	            				$('#TXT_manufacture_no').errorStyle(_text['E005']);
            				}
            				$('#TXT_manufacture_no').focus();
            			}
            		});
            	}else if(res.response == true){
	            	var msg = 'I005';
	            	jMessage(msg,function(r){
						if(r){
							var manufacture_no = $('#TXT_manufacture_no').val().trim();
							referManufactureNo(manufacture_no);
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
		alert('postSave: ' + e.message);
	}
}


/**
 * get data from view
 *
 * @author      :   ANS817 - 2018/04/20 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function getDataFromView() {
	try {
		var t_complete = [];
		$('#table-manufacturing-completion-process tbody tr').each(function() {
			var complete_qty  = $(this).find('.TXT_complete_qty').val().trim();
			complete_qty      = complete_qty == '' ? 0 : complete_qty.replace(/,/g, '');
			
			var _data    = {
				complete_qty 		: 	complete_qty,
				complete_date 		: 	$(this).find('.TXT_complete_date').val().trim(),
				remarks 			: 	$(this).find('.TXT_remarks').val().trim(),
			};

			t_complete.push(_data);
		});

		var data = {
			manufacture_no			: 	$('#TXT_manufacture_no').val().trim(),
			t_complete				: 	t_complete,
		};

		return data;
    } catch (e) {
        alert('getDataFromView: ' + e.message);
    }
}