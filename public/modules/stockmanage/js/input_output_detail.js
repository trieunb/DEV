/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/03/19
 * 作成者		:	Trieunb
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	INPUT OUTPUT
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */
var error_key 		= 'E005';
var current_date 	= new Date().toJSON().slice(0,10).replace(/-/g,'/');
 $(document).ready(function () {
 	initCombobox();
	initEvents();
	if (mode == 'U' && $(".TXT_in_out_no").val() != '') {
		$(".TXT_in_out_no").trigger('change');
	}
	if (mode == 'I') {
		$(".TXT_in_out_no").val('');
	}
	disableInputOutput(mode);
});

function initCombobox() {
	var name = 'JP';
	/*_getComboboxData(name, 'in_out_div', function() {
		if (mode == 'U') {
			$('.CMB_in_out_div').attr('selected','selected');
			$('.CMB_in_out_div ').attr('disabled', true);
		}
	});
	_getComboboxData(name, 'in_out_data_div', function() {
		if (mode == 'I') {
			$('.CMB_in_out_data_div > option[value="00"]').attr('selected','selected');
			$('.CMB_in_out_data_div ').attr('disabled', true);
		}
	});*/
	if (mode == 'U') {
		$('.CMB_in_out_div').attr('selected','selected');
		$('.CMB_in_out_div ').attr('disabled', true);
	}
	if (mode == 'I') {
		$('.CMB_in_out_data_div > option[value="00"]').attr('selected','selected');
		$('.CMB_in_out_data_div ').attr('disabled', true);
	}
}

/**
 * init Events
 * @author  :   Trieunb - 2018/03/19 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//init 1 row table at mode add new (I)
		_initRowTable('table-stock-manage', 'table-row', 1);
		//drap and drop row table
		_dragLineTable('table-stock-manage', true);
		// remove row table
		$(document).on('click','.remove-row',function(e){
			var obj   = $(this);
			jMessage('C002', function(r) {
				if(r) {
					obj.closest('tr').remove();
					_updateTable('table-stock-manage', true);
					$('.table-stock-manage tbody tr:last :input:first').focus();
				}
			});
		});
		//add row
		$(document).on('click', '#btn-add-row', function () {
			try {
				var row = $("#table-row tr").clone();
				$('.table-stock-manage tbody').append(row);
				_updateTable('table-stock-manage', true);
				$('.table-stock-manage tbody tr:last :input:first').focus();
			} catch (e) {
				alert('add new row' + e.message);
			}

		});
		//init back
		$(document).on('click', '#btn-back', function () {
			sessionStorage.setItem('detail', true);
			location.href = '/stock-manage/input-output-search';
		});
		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				$(':input.item-error').removeClass('item-error');
				$(':input.warning-item').removeClass('warning-item');
				// checkSerialNotExist();
				if(validateHeader()) {
					if (mode !== 'I') {
						var msg = 'C003';
						jMessage(msg, function(r) {
							if (r) {
								saveInOutPut();
							}
						});
					} else {
						var date 		=	$('.TXT_in_out_date').val();
						if (date !== current_date) {
							jMessage('C482', function(ok) {
								if (ok) {
									saveInOutPutDetail();
								}
							});
						} else {
							saveInOutPutDetail();
						}
					}
				}
			} catch (e) {
				alert('#btn-save ' + e.message);
			}
		});
 		// warehouse_cd
 		$(document).on('change', '.warehouse_cd', function() {
 			_clearErrors();
 			$(this).parent().addClass('popup-warehouse');
			_referWarehouse($.mbTrim($(this).val()), $(this), '', true);
 		});
 		// table-stock-manage  
 		$(document).on('change', '.TXT_item_cd', function() {
 			var parent = $(this).parents('#table-stock-manage tbody tr');
 			parent.addClass('cal-refer-pos');
 			var data = {
 				'item_cd' 	: 	$(this).val(),
 			}
			referItemSerial(data, 'cal-refer-pos');
 		});
 		// TXT_in_out_no             
 		$(document).on('change', '.TXT_in_out_no', function() {
 			_clearErrors();
 			var data = {
 				in_out_no 	: 	$(this).val()
 			};
 			referInOut(data);
 		});
 		// TXT_serial_no             
 		$(document).on('change', '.TXT_serial_no', function() {
 			$(this).val(padZeroLeft($.mbTrim($(this).val()), 7));
 		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * disable item key in_out no by mode
 * 
 * @author : ANS806 - 2018/02/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function disableInputOutput(mode) {
	try {
		if (mode != 'I') {
			// console.log(mode);
			$('.TXT_in_out_no').attr('disabled', false);
			$('.TXT_in_out_no').parent().addClass('popup-inputoutput-search')
			$('.popup-inputoutput-search').find('.btn-search').attr('disabled', false);
			parent.$('.popup-inputoutput-search').removeClass('popup-inputoutput-search');
			$(".TXT_in_out_no").addClass("required");

			$('.TXT_in_out_date').attr('disabled', true);
			$('.TXT_in_out_date').parent().addClass('inputoutput-date')
			$('.inputoutput-date').find('.ui-datepicker-trigger').attr('disabled', true);
			parent.$('.inputoutput-date').removeClass('inputoutput-date');

			$('.TXT_warehouse_div').attr('disabled', true);
			$('.TXT_warehouse_div').parent().addClass('popup-warehouse-search')
			$('.popup-warehouse-search').find('.btn-search').attr('disabled', true);
			parent.$('.popup-warehouse-search').removeClass('popup-warehouse-search');
			
			$('.TXT_item_cd ').attr('disabled', true);
			$('.TXT_serial_no ').attr('disabled', true);

			$('.btn-add-row').attr('disabled', true);
			$('.remove-row').attr('disabled', true);

			$('.TXT_in_out_qty').attr('disabled', true);
			$('#table-stock-manage').find('.btn-search').attr('disabled', true);

			$('.CMB_in_out_div ').attr('disabled', true);
			$('.CMB_in_out_data_div').attr('disabled', true);
		} else {
			$('.TXT_in_out_no').attr('disabled', true);
			// $('.TXT_in_out_qty').attr('disabled', false);

			$('.TXT_in_out_no').parent().addClass('popup-inputoutput-search')
			$('.popup-inputoutput-search').find('.btn-search').attr('disabled', true);
			parent.$('.popup-inputoutput-search').removeClass('popup-inputoutput-search');

			$(".TXT_in_out_no").removeClass("required");
			// $(".TXT_in_out_no").val('');
			$('.CMB_in_out_div ').attr('disabled', false);
			$('.CMB_in_out_data_div').attr('disabled', true);
		}
	} catch (e) {
		alert('disableInputOutput: ' + e.message);
	}
}

/**
 * save data all - insert/update
 * 
 * @author : ANS806 - 2018/03/19 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function saveInOutPut() {
	try{
	    var data = getData();
	    $.ajax({
	        type        :   'POST',
	        url         :   '/stock-manage/input-output-detail/save',
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
		                			in_out_no 	: res.in_out_no
		                		}
		                		referInOut(data);
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
		$('#table-stock-manage tbody tr').each(function() {
			var _in_out_detail_no 	=	($(this).find('.DSP_in_out_detail_no').text() == "") ? 0 : $(this).find('.DSP_in_out_detail_no').text();
			var _in_out_qty			=	($(this).find('.TXT_in_out_qty').val() == "") ? 0 : $(this).find('.TXT_in_out_qty').val().replace(/,/g, '');
			//get data table in_out detail
			var _t_in_out_d = {
					'in_out_detail_no' 			: _in_out_detail_no,
					'item_cd' 					: $.mbTrim($(this).find('.TXT_item_cd ').val()),
					'serial_no' 				: $.mbTrim($(this).find('.TXT_serial_no ').val()),
					'in_out_qty' 				: parseInt(_in_out_qty),
					'detail_remarks' 			: $.mbTrim($(this).find('.TXT_detail_remarks').val()),
				};
			_data.push(_t_in_out_d);

		});
		var STT_data = {
				'mode'					: mode, 
				'in_out_no'				: $.mbTrim($('.TXT_in_out_no').val()),
				'in_out_div'			: $.mbTrim($('.CMB_in_out_div').val()),
				'in_out_date'			: $.mbTrim($('.TXT_in_out_date').val()),
				'in_out_data_div'		: $.mbTrim($('.CMB_in_out_data_div').val()),
				'warehouse_div'			: $.mbTrim($('.TXT_warehouse_div').val()),
				'remarks'				: $.mbTrim($('.TXT_remarks').val()),
				//<Detail> data type json
				't_in_out_d' 			: _data,
			};
		return STT_data;
	} catch(e) {
        console.log('getData' + e.message)
    }
}
/**
 * refer item serial
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referItemSerial(data, pos) {
	try	{
		_clearErrors();
		$(':input.warning-item').removeClass('warning-item');
		$.ajax({
			type 		: 'GET',
			url 		: '/stock-manage/input-output-detail/item',
			dataType	: 'json',
			data 		: data,
			success: function(res) {
				var data = '';
				if (res.response) {
					data 	=	res.data;
					parent.$('.'+pos).find('.DSP_item_nm').text(data.item_nm_j);
					parent.$('.'+pos).find('.DSP_item_nm').attr('title', data.item_nm_j);
					parent.$('.'+pos).find('.DSP_specification').text(data.specification);
					parent.$('.'+pos).find('.DSP_specification').attr('title', data.specification);
					// parent.$('.'+pos).find('.TXT_serial_no').val(data.serial_no);
					parent.$('.'+pos).find('.DSP_serial_management_div').text(data.serial_management_div);
					parent.$('.'+pos).find('.DSP_stock_management_div').text(data.stock_management_div);
				} else {
					parent.$('.'+pos).find('.DSP_item_nm').text('');
					parent.$('.'+pos).find('.DSP_item_nm').attr('title', '');
					parent.$('.'+pos).find('.DSP_specification').text('');
					parent.$('.'+pos).find('.DSP_specification').attr('title', '');
					// parent.$('.'+pos).find('.TXT_serial_no').val(data.serial_no);
					parent.$('.'+pos).find('.DSP_serial_management_div').text('');
					parent.$('.'+pos).find('.DSP_stock_management_div').text('');
				}
				if (mode == 'I' && data.serial_management_div == 1) {
					parent.$('.'+pos).find('.TXT_serial_no').prop('disabled', false);
					parent.$('.'+pos).find('.TXT_serial_no').addClass('required');
					parent.$('.'+pos).find('.TXT_in_out_qty').prop('disabled', true);
					parent.$('.'+pos).find('.TXT_in_out_qty').val('1');
				} else {
					parent.$('.'+pos).find('.TXT_serial_no').prop('disabled', true);
					parent.$('.'+pos).find('.TXT_serial_no').removeClass('required');
					parent.$('.'+pos).find('.TXT_in_out_qty').prop('disabled', false);
					parent.$('.'+pos).find('.TXT_in_out_qty').val('');
				}
				parent.$('.'+pos).find('.TXT_serial_no').val('');
				// setItemReferParts(data, pos)
				parent.$('.cal-refer-pos').removeClass(pos);
			}
		});
	} catch (e) {
		alert('referItemSerial: ' + e.message);
	}
}
/**
 * refer input output
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referInOut(data) {
	try	{
		$.ajax({
			type 		: 'GET',
			url 		: '/stock-manage/input-output-detail/refer-in-out',
			dataType	: 'json',
			data 		: data,
			success: function(res) {
				if (res.response) {
					//set refer data for item
					var data_result = res.in_out_h;
					// $('.heading-btn-group').html(res.button);
					setItemInOutH(data_result, false);
					$('.infor-created').html(res.header_html);
					$('#div-input-output').html(res.html_in_out_d);

					if ($('#table-stock-manage tbody tr').length == 0) {
						//init 1 row table at mode add new (I)
						_initRowTable('table-stock-manage', 'table-row', 1);
					}
					//init table index
					_setTabIndex();
					//drap and drop row table
					// var param = {
					// 	'mode'		: mode,
					// 	'from'		: 'InputOutputDetail',
					// 	'in_out_no'	: data.in_out_no,
					// };
					// _postParamToLink(from, 'InputOutputDetail', '', param);
					//disable button add row and remove row
					mode	=	'U';
					disableInputOutput(mode);
					_clearErrors();
				} else {
					jMessage('W001', function(ok) {
						setItemInOutH('', false);
						$('.infor-created').html(res.header_html);
						//init 1 row table at mode add new (I)
						_initRowTable('table-stock-manage', 'table-row', 1);
						//drap and drop row table
						_dragLineTable('table-stock-manage', true);
					})
				}
				if (typeof callback == 'function') {
					callback();
				}
			}
		});
	} catch (e) {
		alert('referInOut: ' + e.message);
	}
}
/**
 * set item inout header
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setItemInOutH(data, is_del) {
	$('.CMB_in_out_div option:first').prop('selected', true);
	$('.CMB_in_out_data_div option:first').prop('selected', true);
	if (data != '') {
		$('.TXT_in_out_no').val(data.in_out_no);
		$('.TXT_in_out_date').val(data.in_out_date);
		$('.TXT_warehouse_div').val(data.warehouse_div);
		$('.warehouse_nm').text(data.warehouse_nm);
		$('.TXT_remarks').val(data.remarks);
		if (data.in_out_div != '') {
			$('.CMB_in_out_div option[value='+data.in_out_div+']').prop('selected', true);
		}
		if (data.in_out_data_div != '') {
			$('.CMB_in_out_data_div option[value='+data.in_out_data_div+']').prop('selected', true);
		}
	} else {
		if (is_del) {
			$('.TXT_in_out_no').val('');
		}
		$('.TXT_in_out_date').val('');
		$('.TXT_warehouse_div').val('');
		$('.warehouse_nm').text('');
		$('.TXT_remarks').val('');
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
		var element = $('#table-stock-manage tbody tr');
		var error = 0;
		_clearErrors();
		var flag 	= false;
		if (is_e004) {
			element.each(function() {
				error 	= 0;
				$(this).find('.required').each(function() {
					if ($(this).is(':visible')) {
						if(($(this).is("input") || $(this).is("textarea")) &&  $.mbTrim($(this).val()) !== '' ) {
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
				if(($(this).is("input") || $(this).is("textarea")) &&  $.mbTrim($(this).val()) == '' ) {
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
 * check double item and serial
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function checkDoubleItemAndSerial(is_err) {
	try {
		$(':input.item-error').removeClass('item-error');
		var data 		= 	getData();
		var origData 	= 	data.t_in_out_d;
		var newData 	=	[];
		var flag		=	false;
		for (var i = 0; i < origData.length; i++ ) {
	        found = false;
	        for (var j = (i + 1); j < origData.length; j++ ) {
	            if ( origData[i]['item_cd'] === origData[j]['item_cd'] && 
	            	origData[i]['serial_no'] === origData[j]['serial_no']) 
	            {
	            	newData.push(origData[i])
	            }
	        }
	    }
	    if (newData.length > 0) {
	    	flag		=	true;
	    	if (is_err) {
	    		for (var i = 0; i < newData.length; i++) {
		    		var _item_cd 	= 	newData[i]['item_cd'];
		    		var _serial_no 	=	newData[i]['serial_no'];
		    		$('#table-stock-manage tbody tr').each(function() {
		    			var item_cd 	= 	$(this).find('.TXT_item_cd').val();
		    			var serial_no 	=	$(this).find('.TXT_serial_no').val();
		    			if (_item_cd == item_cd && _serial_no == serial_no) {
		    				// $(this).find('.TXT_item_cd').addClass('item-error');
		    				if(_serial_no != ''){
		    					$(this).find('.TXT_serial_no').errorStyle(_text['E489']);
		    				}else{
		    					$(this).find('.TXT_item_cd').errorStyle(_text['E481']);
		    				}
		    			}
		    		});
		    	}
	    	}
	    }
	    return flag;
	} catch (e) {
		alert('checkDoubleItemAndSerial: ' + e.message);
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
		if (errors_item.warehouse_div == error_key) {
			$('.TXT_warehouse_div').errorStyle(_text['E005']);
		}
		// check e005 for table detail pi
		var detail 	= $('#table-stock-manage tbody tr');
		if (error_list.length > 0) {
			detail.each(function() {
				if ($(this).is(':visible')) {
					var parts 		=	$(this).find('.TXT_item_cd');
					var item_cd 	=	$.mbTrim(parts.val());
					$.each(error_list, function(i, item) {
					    if (item.item_cd === item_cd) {
					    	parts.errorStyle(_text['E005']);
					    }
					});
				}
			});
		}
		if (errors_item.in_out_no == error_key) {
			if (!$('input').hasClass('error-item')) {
				$('.TXT_in_out_no').errorStyle(_text['E005']);
			}
		}
	} catch (e) {
		alert('itemErrorsE005: ' + e.message);
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
				if (!$(this).hasClass('TXT_item_cd') && !$(this).hasClass('TXT_serial_no') && !$(this).hasClass('TXT_in_out_qty')) {
					if(($(this).is("input") || $(this).is("textarea")) &&  $.mbTrim($(this).val()) == '' ) {
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
 * save in output detail
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function saveInOutPutDetail(is_C482) {
	try {
		_clearErrors();
		var msg = 'C001';
		var _row_detail = 	$('#table-stock-manage tbody tr').length;
		if(_row_detail > 0) {
			if (!validateDetail(is_e004 = true)) {
				jMessage('E004', function(ok) {
					validateDetail(is_e004 = false);
				});
			} else {
				var is_E482	=	validateStockManagementE482(is_err = true);
				if (!is_E482) {
					jMessage('E482', function(r) {
						if (r) {
							validateStockManagementE482(is_err = false);
						}
					});
				} else 
					if (!validateStockManagementE483(true)) {
						jMessage('E483', function(r) {
							validateStockManagementE483(false)
						});
					} else {
						if (validateDetail(is_e004 = false)) {
							if (checkDoubleItemAndSerial(false)) {
								//jMessage('E481', function(r) {
									checkDoubleItemAndSerial(true);
								//});
							} else {
								checkSerialNotExist(msg);
							}
						}
					}
				}
		} else {
			jMessage('E004');
		}
	} catch (e) {
		alert('saveInOutPutDetail: ' + e.message);
	}
}
/**
 * validate stock management e482
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function validateStockManagementE482(is_err) {
	try {
		var is_E482	=	true;
		if (is_err) {
			$('#table-stock-manage tbody tr').each(function() {
				if ($(this).find('.TXT_serial_no').val() == '' && 
					$(this).find('.DSP_serial_management_div').text() == '1') {
					is_E482	=	false;
				}
			});
		} else {
			$('#table-stock-manage tbody tr').each(function() {
				if ($(this).find('.TXT_serial_no').val() == '' && 
					$(this).find('.DSP_serial_management_div').text() == '1') {
					$(this).find('.TXT_serial_no').errorStyle(_text['E482']);
					is_E482	=	false;
				}
			});
		}
		return is_E482
	} catch (e) {
		alert('validateStockManagement: ' + e.message);
	}
}
/**
 * validate stock management e483
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function validateStockManagementE483(is_err) {
	try {
		var is_E483	=	true;
		if (is_err) {
			$('#table-stock-manage tbody tr').each(function() {
				if ($(this).find('.TXT_item_cd').val() !== '' && 
					($(this).find('.DSP_stock_management_div').text() == '0' || 
					$(this).find('.DSP_stock_management_div').text() == '')) {
						is_E483	=	false;
				}
			});
		} else {
			$('#table-stock-manage tbody tr').each(function() {
				if ($(this).find('.TXT_item_cd').val() !== '' && 
					($(this).find('.DSP_stock_management_div').text() == '0' || 
					$(this).find('.DSP_stock_management_div').text() == '')) {
						$(this).find('.TXT_item_cd').errorStyle(_text['E483']);
						is_E483	=	false;
				}
			});
		}
		return is_E483
	} catch (e) {
		alert('validateStockManagement: ' + e.message);
	}
}
/**
 * Check Serial not exist
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function checkSerialNotExist(msg) {
	try {
		var data = {
			t_in_out_d : getData()['t_in_out_d']
		}
		$.ajax({
			type 		: 'POST',
			url 		: '/stock-manage/input-output-detail/check-serial-exist',
			dataType	: 'json',
			data 		: data,
			success: function(res) {
				if (res.serial_exist_list.length > 0) {
					
	            	var msg_C481 = _text['C481'].replace('{0}', res.serial_exist_list[0]['in_out_detail_no']);
	            	jMessage_str('C481', msg_C481, function(ok) {
	            		if (ok) {
	            			saveInOutPut();
	            		} else {
	            			for (var i = 0; i < res.serial_exist_list.length; i++) {
								$('#table-stock-manage tbody tr:eq('+(res.serial_exist_list[i]['in_out_detail_no']-1)+')').find('.TXT_serial_no').addClass('warning-item');
					    		$('#table-stock-manage tbody tr:eq('+(res.serial_exist_list[i]['in_out_detail_no']-1)+')').find('.TXT_serial_no').errorStyle('指定されたシリアル番号がシリアル管理テーブルに存在しません。');
					    	}
	            		}
	            	}, msg_C481);
	            } else {
	            	jMessage(msg, function(r) {
						if (r) {
							saveInOutPut();
						}
					});
	            }
			}
		});
	} catch (e) {
		alert('checkSerialNotExist: ' + e.message);
	}
}