/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/03/22
 * 作成者		:	Daonx - ANS804
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	SHIFTING
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

$(document).ready(function () {
	initEvents();

	if (mode == 'I') {
		disableMoveNo();
		$("#TXT_move_no").removeClass("required");
		$("#TXT_move_no").val('');
	} else {
		$("#TXT_move_no").addClass("required");
	}

	_initRowTable('table-shifting-detail', 'table-row', 1);

	if(mode == 'U' && $('#TXT_move_no').val() != ''){
		$('#TXT_move_no').trigger('change');
	}
});

/**
 * init Events
 * @author  :   Daonx - ANS804 - 2018/03/22 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		_dragLineTable('table-shifting-detail', true);

		// remove row table
		$(document).on('click','.remove-row',function(e){
			try {
				var _this = $(this)
				jMessage('C002', function(r){
					if (r) {
						if ($('#table-shifting-detail tbody tr.tr-table').length > 1) {
							_this.closest('tr').remove();
							_updateTable('table-shifting-detail', true);
						} else {
							_this.closest('tr').remove();
							var row = $("#table-row tr.tr-table").clone();
							$('.table-shifting-detail tbody').append(row);
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
				if ($('.table-shifting-detail tbody').find('.tr-empty').length > 0) {
					$('.table-shifting-detail tbody .tr-empty').remove();
				}

				var row = $("#table-row tr.tr-table").clone();

				// var col_index =  $('.table-shifting-detail tbody tr').length;
				// if (col_index < 15) {
					$('.table-shifting-detail tbody').append(row);
				// }
				_updateTable('table-shifting-detail', true);

				$('.table-shifting-detail tbody tr:last .TXT_item_cd').focus();
			} catch (e) {
				console.log('add new row: ' + e.message);
			}
		});

		//init back
		$(document).on('click', '#btn-back', function () {
			try {
				sessionStorage.setItem('detail', true);
				location.href = '/shifting/shifting-request-search';
			} catch (e) {
				console.log('click #btn-back: ' + e.message);
			}
		});

		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				_clearErrors();

				// get number rows
				var row_detail = $('#table-shifting-detail tbody tr.tr-table').length;

				if (mode == 'I') {
					var msg = 'C001';
				} else {
					var msg = 'C003';
				}
				// validate
				if(validate($('body'))){
					if(validateSameWarehouse()) {
						if(row_detail == 0) {
							jMessage('E004');
							return false;
						} else if (!validateTable()) {
							if (validateLineEffect()) {// have line effect
								raiseErrorE004();
								$('.table-shifting-detail .error-item:first').focus();
							} else { // not have line effect
								jMessage('E004', function(r) {
									raiseErrorE004();
									$('.table-shifting-detail .error-item:first').focus();
								});
							}								
							return false;
						} else if (!validateDuplicateKey()) {
							jMessage('E411', function(r) {
								$('.table-shifting-detail .error-item:first').focus();
							});
							return false;
						} else {
							jMessage(msg, function(r) {
								if (r) {
									var arrNegative = arrayAvailableNegative();
									if (arrNegative.length > 0) {
										var messageC411 = _text['C411'].replace('{0}', arrNegative.length);
										jMessage_str('C411', messageC411, function(r) {
											if (r) {
												saveShiftingDetail();
											} else {
												raiseErrorC411(arrNegative);
											}
										}, messageC411);
									} else {
										saveShiftingDetail();
									}
								}
							});
						}
					}
				}
			} catch (e) {
				console.log('#btn-save: ' + e.message);
			}
		});

		// Change 移動依頼票番号
		$(document).on('change', '#TXT_move_no', function() {
			try {
				if( $('#TXT_move_no').val() != ""){
					referMove();
				} else {
					setItemReferMove({});
					_removeErrorStyle($('#TXT_move_no'));
					_initRowTable('table-shifting-detail', 'table-row', 1);
				}
			} catch (e) {
				console.log('change #TXT_move_no: ' + e.message);
			}
		});

		// button delete
		$(document).on('click', '#btn-delete', function() {
			try {
				if(validateShifting()){
					jMessage('C002', function(r) {
						if (r) {
							deleteShiftingDetail();
						}
					});	
				}		
			} catch (e) {
				console.log('#btn-delete ' + e.message);
			}
		});

		// btn-issue-instruction
 		$(document).on('click', '#btn-issue', function(){
 			try {
				jMessage('C004', function(r) {
					if (r) {
						postPrint();
					}
				});
			} catch (e) {
				console.log('#btn-issue ' + e.message);
			}
 		});

 		//btn-approve
 		$(document).on('click', '#btn-approve', function(){
			try {
 				if(validateShifting()){
					jMessage('C005', function(r) {
						if (r) {
							approveShifting(1);
						}
					});
				}		   
			} catch (e) {
				console.log('#btn-approve' + e.message);
			}
 		});

 		//change in warehouse div  
		$(document).on('change', '.TXT_manufacture_no', function() {
			try {
				var _this         	= $(this);
				var manufacture_no 	= $(this).val().trim();
				referManufacture(manufacture_no, _this);
			} catch (e) {
				console.log('change .TXT_manufacture_no: ' + e.message);
			}
		});

 		//change in warehouse div  
		$(document).on('change', '.TXT_in_warehouse_div', function() {
			try {
				var _this         = $(this);
				var warehouse_div =	$(this).val().trim();
				_referWarehouse(warehouse_div, _this, '', false);	   
			} catch (e) {
				console.log('change .TXT_in_warehouse_div: ' + e.message);
			}
		});

 		//change out warehouse div  
		$(document).on('change', '.TXT_out_warehouse_div', function() {
			try {
				var _this         = $(this);
				var warehouse_div =	_this.val().trim();
				_referWarehouse(warehouse_div, _this, function(){
					var warehouse = _this.val().trim();
					if (warehouse != '') {
						$('.TXT_item_cd').trigger('change');
					} else {
						$('.DSP_stock_current_qty').val('');
						$('.DSP_stock_available_qty_hidden').val('');
						//$('.TXT_move_qty_hidden').val('');
						//$('.TXT_move_qty').val('');
						$('.DSP_stock_available_qty').text('');
						$('.serial_list').val('');
						$('.TXT_detail_remarks').val('');
					}
				}, false);
			} catch (e) {
				console.log('change .TXT_out_warehouse_div: ' + e.message);
			}
		});

 		//change item cd
		$(document).on('change', '.TXT_item_cd', function() {
			try {
				var item_cd    = $(this).val().trim();
				var rowCurrent = $(this).closest('tr.tr-table');

				deleteValueRow(rowCurrent);

				if (item_cd != '') {
					referItem(item_cd, $(this), '');
				} else {					
					rowCurrent.find(':input').val('');
					rowCurrent.find('.DSP_item_nm').text('');
					rowCurrent.find('.DSP_specification').text('');
					rowCurrent.find('.DSP_unit').text('');
					rowCurrent.find('.DSP_stock_available_qty').text('');
				}
			} catch (e) {
				console.log('change .TXT_item_cd: ' + e.message);
			}
		});

		//remove event, add event
		$(document).off('focusout').on('focusout', '.required', function() {
	   		var val  = '';
		    if ($(this).is("input[type=checkbox]")) {
		       if ($(this).is(":checked")) {
		        	val = 'true';
			    } else {
			        val = '';
			    }
		    } else {
		       val = $(this).val().trim();
		    }

		    if ($(this).hasClass('TXT_move_qty')) {
		    	if (val !== '' && parseInt(val) !== 0) {
		    		_removeErrorStyle($(this));
		    	}
		    } else {
		    	if (val !== '' && $(this).attr('has-balloontip-message') == _MSG_E001) {
	   				_removeErrorStyle($(this));
		   		}
		    }
	   	});

 		//don't fill negative number
		$(document).on('keypress', '.TXT_move_qty', function(e) {
			try {
				var key = e.which || e.keyCode || 0
				// check type -
				if (key == 45) {
					e.preventDefault();
				}
			} catch (e) {
				console.log('keypress .TXT_move_qty: ' + e.message);
			}
		});
	} catch (e) {
		console.log('initEvents: ' + e.message);
	}
}

/**
 * validate
 *
 * @author      :   ANS804 - 2018/03/30 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function validate(element) {
	if(!element){
		element = $('body');
	}
	var error = 0;
	try {
		_clearErrors();
		element.find('.required:not(.TXT_item_cd):not(.TXT_move_qty)').each(function() {
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

/**
 * check warehouse same
 *
 * @author      :   ANS804 - 2018/03/30 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function validateSameWarehouse() {
	var error                 = 0;
	var TXT_out_warehouse_div = $.mbTrim($('.TXT_out_warehouse_div').val());
	var TXT_in_warehouse_div  = $.mbTrim($('.TXT_in_warehouse_div').val());
	try {
		_clearErrors();
		if(TXT_out_warehouse_div == TXT_in_warehouse_div) {
			$('.TXT_out_warehouse_div').errorStyle(_text['E412']);
			$('.TXT_in_warehouse_div').errorStyle(_text['E412']);
			error ++;
		}
		$('input.error-item:first').focus();

		if( error > 0 ) {
			return false;
		} else {
			return true;
		}
	} catch(e) {
		console.log('validateSameWarehouse: ' + e.toString());
	}
}

/**
 * validateTable
 *
 * @author      :   ANS804 - 2018/03/22 - create
 * @param       :  	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function validateTable(){
 	try {
		var error = 0;
		_clearErrors();

		$('.table-shifting-detail tbody .tr-table').each(function() {
			$(this).find(':input.required').each(function(){
				if($.trim($(this).val()) == '' || parseInt($.trim($(this).val())) == 0) {
					error++;
				}
			});
		});

		return error > 0 ? false : true;
	} catch(e) {
		console.log('validateTable: ' + e.toString());
	}
}

/**
 * validate line effect
 *
 * @author      :   ANS804 - 2018/03/22 - create
 * @param       :  	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function validateLineEffect(){
 	try {
		var checkHaveLineEffect = false;

		$('.table-shifting-detail tbody .tr-table').each(function() {
			var item_cd  = $(this).find('.TXT_item_cd').val();
			var move_qty = $(this).find('.TXT_move_qty').val();
			
			if($.trim(item_cd) !== '' && $.trim(move_qty) !== '' && parseInt(move_qty) !== 0) {
				checkHaveLineEffect = true;
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
 * @author      :   ANS804 - 2018/03/22 - create
 * @param       :  	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function raiseErrorE004(){
 	try {
		_clearErrors();

		$('.table-shifting-detail tbody .tr-table').each(function() {
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
 * validate table
 *
 * @author      :   ANS804 - 2018/03/22 - create
 * @param       :  	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function validateDuplicateKey(){
 	try {
		var error = 0;
		var arr   = [];

		_clearErrors();

		$('.table-shifting-detail').find('.TXT_item_cd:enabled:not([readonly])').each(function() {
			arr.push($(this).val().trim());
		});

		//get input have duplicate
		var arrayDuplicate = getInputDuplicate(arr);

		if (arrayDuplicate.length !== 0) {
			raiseErrorE411(arrayDuplicate);
			error++;
		}

		return error > 0 ? false : true;
	} catch(e) {
		console.log('validateDuplicateKey: ' + e.toString());
	}
}

/**
 * getInputDuplicate
 *
 * @author      :   ANS804 - 2018/03/22 - create
 * @param       :  	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function getInputDuplicate(arr){
	try {
		var objDuplicate = {};
		var arrayDuplicate = [];

		$.each(arr, function(index,val){
			if (arr.indexOf(val) !== arr.lastIndexOf(val)) {
				objDuplicate[val] = 0;
			}
	    });
	    for (var i in objDuplicate) {
	    	arrayDuplicate.push(i);
	    }

	    return arrayDuplicate;
	} catch(e) {
		console.log('getInputDuplicate: ' + e.toString());
	}
}

/**
 * raiseErrorE411
 *
 * @author      :   ANS804 - 2018/03/22 - create
 * @param       :  	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function raiseErrorE411(arr){
	try {
		$('.table-shifting-detail .TXT_item_cd').each(function(index,val){
			if ($.inArray($(this).val().trim(), arr) !== -1) {
				$(this).errorStyle(_text['E411']);
			}
		});
	} catch(e) {
		console.log('raiseErrorE411: ' + e.toString());
	}
}

/**
 * refer move No
 *
 * @author      :   ANS804 - 2018/03/22 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function referMove(){
	try{
		var move_no = $('#TXT_move_no').val().trim();
		if (move_no == '') return false;

		_clearErrors();

		var data    = {
	    	move_no 		: move_no,
	    	mode 			: mode
	    };

	    $.ajax({
	        type        :   'POST',
	        url         :   '/shifting/shifting-detail/refer-move',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
				if (res.response) {
					// button
	        		$('.heading-btn-group').html(res.button);
					// Common
					$('#operator_info').html(res.header_html);
					// header
					setItemReferMove(res.move_info_h);
					// table detail
					$('#table-refer').html(res.move_table);

					// run again tooltip
					$(function () {
					  $('[data-toggle="tooltip"]').tooltip();
					});

					_removeErrorStyle($('#TXT_move_no'));
					//disabled
					disabldedAfterSave();
					// format number
					$('.DSP_stock_available_qty').each(function(){
						var value = $(this).text().trim();
						$(this).text(formatNumber(value));
					});
				} else {
					jMessage(res.error,function(r){
						if(r){
							// Common
							$('#operator_info').html('');
							// header
							setItemReferMove({});

							_removeErrorStyle($('.TXT_move_no'));							
							$('#TXT_move_no').errorStyle(_text['E005']);							
							_initRowTable('table-shifting-detail', 'table-row', 1);
							$('#TXT_move_no').focus();
						}
					});
				}
	        },
	    });
	} catch(e) {
        console.log('referMove: ' + e.message)
    }
}

/**
 * set item refer Move
 * 
 * @author      :   ANS804 - 2018/03/27 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function setItemReferMove(move_info_h){
	try {
		if (!!move_info_h['move_no']) {
			// Header
			$('#STT').removeClass('hide');
			$('#DSP_status').text(move_info_h['move_status_div_nm']);
			$('#DSP_status_tm').text(move_info_h['cre_datetime']);
			
			$('.TXT_move_preferred_date').val(move_info_h['move_preferred_date']);
			$('.TXT_manufacture_no').val(move_info_h['manufacture_no']);
			$('.TXT_out_warehouse_div').val(move_info_h['out_warehouse_div']);
			$('.TXT_out_warehouse_div').closest('.popup').find('.warehouse_nm').text(move_info_h['out_warehouse_div_nm']);
			$('.TXT_in_warehouse_div').val(move_info_h['in_warehouse_div']);
			$('.TXT_in_warehouse_div').closest('.popup').find('.warehouse_nm').text(move_info_h['in_warehouse_div_nm']);
			$('.TXT_remarks').val(move_info_h['remarks']);
		} else {
			$('#operator_info').html('');

			$('#STT').addClass('hide');
			$('#DSP_status').text('');
			$('#DSP_status_tm').text('');
			
			$('.TXT_move_preferred_date').val('');
			$('.TXT_manufacture_no').val('');
			$('.TXT_out_warehouse_div').val('');
			$('.TXT_out_warehouse_div').closest('.popup').find('.warehouse_nm').text('');
			$('.TXT_in_warehouse_div').val('');
			$('.TXT_in_warehouse_div').closest('.popup').find('.warehouse_nm').text('');
			$('.TXT_remarks').val('');
		}
	} catch (e) {
		console.log('setItemReferMove: ' + e.message);
	}
}

/**
 * save shifting detail
 * 
 * @author      :   ANS804 - 2018/03/27 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function saveShiftingDetail() {
	try{
	    var data = getDataSave();
	    $.ajax({
	        type        :   'POST',
	        url         :   '/shifting/shifting-detail/save-shifting',
	        dataType    :   'json',
	        loading		: 	true,
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		var msg = (mode == 'I') ? 'I001' : 'I003';
	            		jMessage(msg, function(r){
		                	if(r){
		                		$('#TXT_move_no').val(res.move_no);
		                		// if(mode == 'I') {
		                		// 	$('#TXT_move_no').prop('readOnly', true);;
		                		// }
		                		mode = 'U';
		                		$('#TXT_move_no').prop('disabled', false);;
		                		//
		                		$('.DSP_move_no').addClass('required');
		                		$('#TXT_move_no').addClass('required');
		                		referMove();
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
        console.log('saveShiftingDetail: ' + e.message)
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
		var serial_list        = [];
		var move_detail        = [];
		var data_insert_update = {};

		$('.table-shifting-detail tbody tr.tr-table').each(function() {
			var _this = $(this);
			// quantity request move
			var move_qty    = parseInt(_this.find('.TXT_move_qty').val().trim().replace(/,/g,''));

			// get array from string
			var data_serial = _this.find('.serial_list').val().trim().split("; ");

			// get list serial
			data_serial.forEach(function(serial){
				if (serial) {
					var serial_list_no = {
						"item_cd" 	: _this.find('.TXT_item_cd').val(),
						"serial_no" : serial
					};
					serial_list.push(serial_list_no);
				}
			});

			//Do not register records whose value is 0
			if(move_qty > 0) {
				var t_move_d = {
					'item_cd'			: 	_this.find('.TXT_item_cd').val(),
					'move_qty' 			: 	parseInt(_this.find('.TXT_move_qty').val().trim().replace(/,/g,'')),
					'detail_remarks'	: 	_this.find('.TXT_detail_remarks').val(),
				};

				move_detail.push(t_move_d);
			}
		});

		var data_insert_update = {
			'mode'					: mode, 
			//key
			'move_no'				: $('#TXT_move_no').val(),
			//Basic
			'manufacture_no'		: $('.TXT_manufacture_no').val(),
			'out_warehouse_div'		: $('.TXT_out_warehouse_div').val(),
			'in_warehouse_div'		: $('.TXT_in_warehouse_div').val(),
			'move_preferred_date'	: $('.TXT_move_preferred_date').val(),
			'remarks'				: $('.TXT_remarks').val(),

			'move_detail' 			: move_detail.length == 0 ? '' : move_detail,
			'serial_list' 			: serial_list.length == 0 ? {"item_cd" : '', "serial_no" : '' } : serial_list
		};
		return data_insert_update;
	} catch(e) {
        console.log('getDataSave: ' + e.message);
    }
}

/**
 * refer item
 * 
 * @author : ANS804 - 2018/03/29 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referItem(item_cd, element, callback) {
	try {

		$.ajax({
			type 		: 'post',
			url 		: '/shifting/shifting-detail/refer-item',
			dataType	: 'json',
			data 		: {
							item_cd 		:item_cd,
							warehouse_div 	: $('.TXT_out_warehouse_div').val().trim()
						},
			success: function(res) {
				element.closest('tr').find('.TXT_item_cd').removeClass('error-item');
				$('.TXT_out_warehouse_div').removeClass('error-item');

				if (res.response) {
					//remove error
                	_removeErrorStyle(element.parents('.popup').find('.TXT_item_cd'));
                	
                	element.closest('tr').find('.TXT_item_cd').val(res.data.item_cd);
					element.closest('tr').find('.DSP_item_nm').text(res.data.item_nm);
					element.closest('tr').find('.DSP_item_nm').attr('title',res.data.item_nm);
					element.closest('tr').find('.DSP_specification').text(res.data.specification);
					element.closest('tr').find('.DSP_specification').attr('title',res.data.specification);
					element.closest('tr').find('.DSP_unit').text(res.data.unit);
					element.closest('tr').find('.DSP_stock_current_qty').val(res.data.stock_current_qty);
					element.closest('tr').find('.DSP_stock_available_qty').text(formatNumber(res.data.stock_available_qty));

					//element.closest('tr').find('.TXT_move_qty').val('');
					//element.closest('tr').find('.TXT_move_qty_hidden').val(0);
					element.closest('tr').find('.DSP_stock_available_qty_hidden').val(res.data.stock_available_qty);

					if (res.data.serial_management_div == 1) {
						element.closest('tr').find('.TXT_move_qty').prop('readOnly',true);
						element.closest('tr').find('.TXT_move_qty').closest('.popup').find('.btn-search').prop('disabled',false);
					} else {
						element.closest('tr').find('.TXT_move_qty').prop('readOnly',false);
						element.closest('tr').find('.TXT_move_qty').closest('.popup').find('.btn-search').prop('disabled',true);
					}

					// run again tooltip
					$(function () {
					  $('[data-toggle="tooltip"]').tooltip();
					});

				} else {
					if (res.clear_item == 1) {
						element.parents('.popup').find('.TXT_item_cd').val('');
						jMessage(res.error,function(r){
							if(r){
								element.closest('tr').find('.TXT_item_cd').addClass('error-item');

								if ($('.TXT_out_warehouse_div').val().trim() == '') {
									$('.TXT_out_warehouse_div').addClass('error-item');
								}
							}
						});
					}
					element.closest('tr').find('.DSP_item_nm').text('');
					element.closest('tr').find('.DSP_specification').text('');
					//element.closest('tr').find('.TXT_move_qty').val('');
					element.closest('tr').find('.serial_list').val('');
					element.closest('tr').find('.DSP_unit').text('');
					element.closest('tr').find('.DSP_stock_current_qty').val('');
					element.closest('tr').find('.DSP_stock_available_qty').text('');
					element.closest('tr').find('.DSP_stock_available_qty_hidden').val('');
					//element.closest('tr').find('.TXT_move_qty_hidden').val('');
				}

				_removeErrorStyle(element.closest('tr').find('.TXT_move_qty'));
				element.parents('.popup').find('.error-item:first').focus();

				// check callback function
				if (typeof callback == 'function') {
					callback();
				}
			}
		});
		
	} catch(e) {
        console.log('referItem: ' + e.message)
    }
}

/**
 * disable popup shifting search
 *
 * @author      :   ANS831 - 2018/01/18 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function disableMoveNo() {
	try {
		$('#TXT_move_no').attr('disabled',true);
		$('#TXT_move_no').parent().addClass('popup-shifting-search')
		$('.popup-shifting-search').find('.btn-search').attr('readonly', true);
		parent.$('.popup-shifting-search').removeClass('popup-shifting-search');
	} catch (e) {
		console.log('disableMoveNo: ' + e.message);
	}
}

/**
 * check move no
 * 
 * @author      :   ANS804 - 2018/03/30 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function validateShifting() {
	try {
		_clearErrors();
		var error 	= true;
		if ($('#TXT_move_no').val() == '') {
			$('#TXT_move_no').errorStyle(_MSG_E001);
			error 	= false;
		}
		return error;
	} catch (e) {
		console.log('validateShifting: ' + e.message);
	}
}

/**
 * delete shifting detail
 * 
 * @author      :   ANS804 - 2018/03/30 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :  
 */
function deleteShiftingDetail() {
	try {
		var _move_no = $('#TXT_move_no').val();
		$.ajax({
	        type        :   'POST',
	        url         :   '/shifting/shifting-detail/delete-shifting',
	        dataType    :   'json',
	        data        :   {_move_no : _move_no},
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		jMessage('I002', function(r){
		                	if(r){
		                		$(':input').val('');
		                		$(":input").each(function (i) { 
									$(this).attr('disabled', false);
								});
		                		setItemReferMove({});
		                		
		                		_initRowTable('table-shifting-detail', 'table-row', 1);
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
        console.log('deleteShiftingDetail: ' + e.message)
    }
}

/**
 * check available negative
 * 
 * @author      :   ANS804 - 2018/03/30 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function arrayAvailableNegative() {
	try {
		_clearErrors();
		var arrList     = [];
		var arrNegative = [];

		$('#table-shifting-detail tbody tr').each(function(index, element) {
			var stock_available_qty = parseInt($(this).find('.DSP_stock_available_qty').text().trim().replace(/,/g,''));
			var move_qty            = parseInt($(this).find('.TXT_move_qty').val().trim().replace(/,/g,''));
			var obj                 = {
				index  					: index + 1,
				stock_available_qty 	: stock_available_qty,
				move_qty				: move_qty
			};
			arrList.push(obj);
		});

		arrNegative = arrList.filter(function(value,index,arr){
			var result = value.stock_available_qty - value.move_qty
			return result < 0
		});

		return arrNegative;
		
	} catch (e) {
		console.log('arrayAvailableNegative: ' + e.message);
	}
}

/**
 * raiseErrorC411
 *
 * @author      :   ANS804 - 2018/03/22 - create
 * @param       :  	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function raiseErrorC411(arr){
	try {
		var len = arr.length;
		for (var i = 0; i < len; i++ ) {
			$('.table-shifting-detail tbody tr:nth-child('+ arr[i].index +') .TXT_move_qty').addClass('error-item');
		}
	} catch(e) {
		console.log('raiseErrorC411: ' + e.toString());
	}
}

/**
 * raiseErrorC412
 *
 * @author      :   ANS804 - 2018/03/22 - create
 * @param       :  	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function raiseErrorC412(arr){
	try {
		var len = arr.length;
		for (var i = 0; i < len; i++ ) {
			$('.table-shifting-detail tbody tr:nth-child('+ arr[i].row +') .TXT_move_qty').addClass('error-item');
		}
		$('.error-item:first').focus();
	} catch(e) {
		console.log('raiseErrorC412: ' + e.toString());
	}
}

/**
 * approve shifting detail
 * 
 * @author      :   ANS804 - 2018/03/22 - create
 * @param       :  	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function approveShifting(check_remaining_qty) {
	try {
		var dataApproved = getDataApproved();

		var data         = {
			move_no 			: $('#TXT_move_no').val(),
			out_warehouse_div 	: $('.TXT_out_warehouse_div').val(),
			in_warehouse_div 	: $('.TXT_in_warehouse_div').val(),
			move_detail			: dataApproved.move_detail,
			serial_list 		: dataApproved.serial_list,
			check_remaining_qty	: check_remaining_qty
		};

		$.ajax({
	        type        :   'POST',
	        url         :   '/shifting/shifting-detail/approve',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		if (res.error_cd == 'C412') {
	            			if (res.item_error.length > 0) {
								var messageC411 = _text['C412'].replace('{0}', res.item_error.length);
								jMessage_str('C412', messageC411, function(r) {
									if (r) {
										approveShifting(0);
									} else {
										raiseErrorC412(res.item_error);
									}
								}, messageC411);
							}
	            		} else {
	            			jMessage(res.error_cd);
	            		}
	            	} else {
	            		jMessage('I005', function(r){
		                	if(r){
		                		var data = {
		                			move_no 		: res.move_no,
		                			move_status 	: res.move_status,
		                			mode 			: mode,
		                		}
		                		referMove();
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
        console.log('approveShifting: ' + e.message)
    }
}

/**
 * get data item_cd/serial_no of input
 * 
 * @author      :   ANS804 - 2018/03/27 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 * @see :
 */
function getDataApproved() {
	try {
		var serial_list  = [];
		var move_detail  = [];
		var dataApproved = {};

		$('.table-shifting-detail tbody tr.tr-table').each(function() {
			var _this = $(this);

			// quantity request move
			if (_this.find('.TXT_item_cd').val() == '' || _this.find('.TXT_move_qty').val() == '') {
				var move_qty = '';
			} else {
				var move_qty    = isNaN(parseInt(_this.find('.TXT_move_qty').val().trim().replace(/,/g,''))) ? '' : parseInt(_this.find('.TXT_move_qty').val().trim().replace(/,/g,''));
			}
			

			// get serial_list
			if (_this.find('.serial_list').val() !== '') {
				// get array from string
				var data_serial = _this.find('.serial_list').val().trim().split("; ");;

				// get list serial
				data_serial.forEach(function(serial){
					if (serial) {
						var serial_list_no = {
							"item_cd" 			: _this.find('.TXT_item_cd').val(),
							"serial_no" 		: serial,
							"move_qty" 			: 1,
							"detail_remarks" 	: _this.find('.TXT_detail_remarks').val(),
						};
						serial_list.push(serial_list_no);
					}
				});
			} else {
				var serial_list_no = {
					"item_cd" 			: _this.find('.TXT_item_cd').val(),
					"serial_no" 		: '',
					"move_qty" 			: move_qty,
					"detail_remarks" 	: _this.find('.TXT_detail_remarks').val(),
				};
				serial_list.push(serial_list_no);
			}

			// get move_detail
            if(move_qty !== '') {
                var t_move_d = {
                    'item_cd'           :   _this.find('.TXT_item_cd').val(),
                    'move_qty'          :   parseInt(_this.find('.TXT_move_qty').val().trim().replace(/,/g,'')),
                    'detail_remarks'    :   _this.find('.TXT_detail_remarks').val(),
                };

                move_detail.push(t_move_d);
            }
		});
		
		var dataApproved = {
			'move_detail'	: move_detail.length == 0 ? {"item_cd" : '','move_qty':'','detail_remarks':'' } : move_detail,
            'serial_list'  	: serial_list.length == 0 ? {"item_cd" : '',"serial_no" : '','move_qty':'0','detail_remarks':'' } : serial_list
		};
		return dataApproved;
	} catch(e) {
        console.log('getDataApproved: ' + e.message);
    }
}

/**
 *refer data manufacture
 * 
 * @author : ANS804 - 2017/12/26 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referManufacture(data, element) {
	try {
		$.ajax({
			type 		: 'POST',
			url 		: '/shifting/shifting-detail/refer-manufacture',
			dataType	: 'json',
			data 		: {manufacture_no : data},
			success: function(res) {
				if (res.response) {
					//remove error
					//element.parents('.popup').find('.manufacturinginstruction_cd').val(res.data.manufacture_no);
					// table detail
					//$('#table-refer').html(res.move_table);
					$('.table-shifting-detail tbody').append(res.move_table);
					_updateTable('table-shifting-detail', true);
					$('.table-shifting-detail tbody tr:last .TXT_item_cd').focus();
				} else {
					element.parents('.popup').find('.manufacturinginstruction_cd').val('');
				}
				
			}
		});
		
	} catch(e) {
        console.log('referManufacture: ' + e.message)
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
 * Update database and print list
 * 
 * @author : ANS804 - 2017/12/26 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postPrint() {
	try {
		var data = {
			update_list : [{move_no : $('.TXT_move_no').val().trim()}]
		};

		$.ajax({
	        type        :   'POST',
	        url         :   '/export/shifting-request-detail/export-excel-list',
	        dataType    :   'json',
	        data        :   data,
	        loading     :   true,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
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
        console.log('postPrint' + e.message);
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
		element.find(':input:not(.TXT_item_cd,.TXT_move_qty)').val('');
		element.find('.DSP_item_nm').text('');
		element.find('.DSP_specification').text('');
		element.find('.DSP_unit').text('');
		element.find('.DSP_stock_available_qty').text('');
	} catch (e) {
		console.log('deleteValueRow: ' + e.message)
	}
}
/**
 * disabled item after register
 * 
 * @author : ANS804 - 2017/12/26 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function disabldedAfterSave(){
	try {
		_disabldedAllInput();
		$('.TXT_remarks').attr('disabled', false);
		$('.TXT_detail_remarks').attr('disabled', false);
	} catch (e) {
		console.log('disabldedAfterSave: ' + e.message)
	}
}
