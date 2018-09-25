/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	DuyTP
 *
 * 更新日		:   2018/01/05
 * 更新者		:   DungNN - ANS810
 * 更新内容		:   New development
 *
 * @package		:	INVOICE
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

 $(document).ready(function () {
	initEvents();
	// initCombobox();
	initSettingMode(mode);
});

// function initCombobox() {
// 	_getComboboxData('JP', 'manufacture_kind_div');
// }

/**
 * init Events
 * @author  :   DuyTP - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//init 1 row table at mode add new (I)
		_initRowTable('table-internal-order', 'table-row', 1, updateTable);
		//drag and drop row table
		_dragLineTable('table-internal-order', true);

		//add row
		$(document).on('click', '#btn-add-row', function () {
			try {
				_addNewRowTable('table-internal-order', 'table-row', 30, updateTable);
				$('#table-internal-order tbody tr:last').find('.TXT_hope_delivery_date').prop('disabled', false);
			} catch (e) {
				alert('add new row' + e.message);
			}
		});

		// remove row table
		$(document).on('click','.remove-row',function(e){
			var obj   = $(this);
			_removeRowTable('table-internal-order', obj, 'C002', true);
		});

		// Change 製品コード
		$(document).on('change', '.TXT_product_cd', function() {
			try {
				referProduct(this);
			} catch (e) {
				alert('change #TXT_product_cd: ' + e.message);
			}
		});		

		//init back
		$(document).on('click', '#btn-back', function () {
			if (from == 'InternalOrderSearch') {
				sessionStorage.setItem('detail', true);
				location.href = '/manufactureinstruction/internalorder-search';
			}
		});

		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				var internal_order_no 	=	$('#TXT_internalorder_cd').val();
				if(!checkInvalidRow() && internal_order_no != ''){
					jMessage('E004',function(ok){
						if(ok){
							if (!validate()) {
								return;
							}
						}
					});
				}

				//validate not ok
				if (!validate()) {
					return;
				}

				//if table component hasn't detail row then show message error
				if (!isExistRow()) {
					jMessage('E004');
					return;
				}

				//validate ok
				var msg = (mode == 'I')?'C001':'C003';
				jMessage(msg,function(r){
					if(r){
						postSave();
					}
				});
			} catch (e) {
				alert('#btn-save: ' + e.message);
			}
		});

		// button delete
		$(document).on('click', '#btn-delete', function() {
			try {
				if($.trim($('#TXT_internalorder_cd').val()) == '' ) {
					$('#TXT_internalorder_cd').errorStyle(_MSG_E001);
				}else{
					jMessage('C002', function(r){
						if(r){
							postDelete();
						}
					});
				}
			} catch (e) {
				alert('#btn-delete: ' + e.message);
			}
		});

 		//btn-issue
 		$(document).on('click', '#btn-issue', function(){
 			if($.trim($('#TXT_internalorder_cd').val()) == '' ) {
				$('#TXT_internalorder_cd').errorStyle(_MSG_E001);
			}else{
	 			jMessage('C004', function(r){
					if(r){
						postPrint();
					}
				});
	 		}
 		});

 		//btn btn-set-date
 		$(document).on('click', '#btn-set-date', function(){
 			//
 			$('#table-internal-order tbody tr').each(function() {
				var _manufacture_status_div		=	$(this).closest('tr').find('.TXT_manufacture_status_div').text();
				if(_manufacture_status_div == 0){
					$(this).closest('tr').find('.datepicker').val($('#deliver_hope_date').val());					
				}
			});
			
 		});

 		// Change 社内発注書番号
		$(document).on('change', '.TXT_internalorder_cd', function() {
			try {
				var TXT_internalorder_cd = $('.TXT_internalorder_cd').val();
				referInternalOrderDetail(TXT_internalorder_cd);
			} catch (e) {
				alert('change .TXT_internalorder_cd: ' + e.message);
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

	if(_errors>0) {
		return false;
	}

	return true;
}

/**
 * update table
 * using callback in function common _addNewRowTable
 *
 * @author		:	ANS810 - 2018/01/05 - create
 * @params		:	null
 * @return		:	null
 */
function updateTable() {
	_formatDatepicker();
	_updateTable('table-internal-order', true);
	_autoFormattingDate("input.datepicker");
}

/**
 * check exist detail row of table component
 *
 * @author      :   ANS810 - 2018/01/05 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function isExistRow() {
	try {
    	var isEmpty = $('#table-internal-order tbody tr').length > 0;
    	return isEmpty;
    } catch (e) {
        alert('isExistRow: ' + e.message);
    }
}


/**
 * save component - insert/update
 *
 * @author      :   ANS810 - 2018/01/05 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function postSave() {
	try {
		//get data from view
		var data = getDataSave();

		$.ajax({
	        type        :   'POST',
	        url         :   '/manufactureinstruction/internalorder-detail/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	// Do something here	        	
	        	if(res.error_product != ''){	        		
	        		jMessage('E005',function(ok){
            			if(ok){
            				if(res.error_cd == 'E005'){
            					$('#TXT_internalorder_cd').errorStyle(_text['E005']);
            				}

            				raiseErrorE005(res.error_product);
            			}
            		});
	        	}else if(res.error_cd != ''){
            		jMessage(res.error_cd, function (r) {
            			if (r) {
            				if(res.error_cd == 'E005'){
            					$('#TXT_internalorder_cd').errorStyle(_text['E005']);
            				}
            			}
            		});
            	}else if(res.response == true){
	            	var msg = (mode == 'I')?'I001':'I003';
	            	jMessage(msg,function(r){
						if(r){
							mode = 'U'
							referInternalOrderDetail(res.in_order_no);
							$('#TXT_internalorder_cd').val(res.in_order_no);
							$('#TXT_internalorder_cd').removeAttr('disabled');
							$('#TXT_internalorder_cd').parent().find('button').removeAttr('disabled');
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
 * get data from view
 *
 * @author      :   ANS810 - 2018/01/08 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function getDataSave() {
	try {
		var t_in_order_d   = [];
		//
		$('#table-internal-order tbody tr').each(function() {
			var in_order_qty 					= $(this).find('.TXT_in_order_qty').val().trim();
			in_order_qty    		 			= in_order_qty == '' ? 0 : in_order_qty.replace(/,/g, '');

			var manufacture_status_div			= $(this).find('.TXT_manufacture_status_div').text().trim();
			if(manufacture_status_div == ''){
				manufacture_status_div	=	0
			}

			var product_cd 						= $(this).find('.TXT_product_cd').val().trim();
			
			if(product_cd != ''){
				var _data = {
					in_order_detail_no			: 	$(this).find('.DSP_in_order_detail_no').text().trim(),
					product_cd					: 	product_cd,
					manufacture_kind_div		: 	$(this).find('.CMB_manufacture_kind_div').val(),
					in_order_qty				: 	in_order_qty,
					hope_delivery_date			: 	$(this).find('.TXT_hope_delivery_date').val().trim(),
					detail_remarks				: 	$(this).find('.TXT_detail_remarks').val().trim(),
					disp_order					: 	$(this).find('.DSP_disp_order').text().trim(),
					manufacture_status_div		: 	manufacture_status_div
				};
				t_in_order_d.push(_data);
			}			
		});

		var data = {
			mode					: 	mode,
			TXT_internalorder_cd	: 	$('#TXT_internalorder_cd').val().trim(),
			t_in_order_d			: 	t_in_order_d
		};

		return data;
    } catch (e) {
        alert('save: ' + e.message);
    }
}

/**
 * refer product
 *
 * @author      :   ANS810 - 2018/01/05 - create
 * @param       : 	
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function referProduct(product_element){
	try{
		// Get data refer
		var product_cd 	=	$(product_element).val().trim();

	    $.ajax({
	        type        :   'POST',
	        url         :   '/manufactureinstruction/internalorder-detail/refer-product',
	        dataType    :   'json',
	        data        :   {
	        					product_cd : product_cd
	        				},
	        success: function(res) {
	        	//
	            if(res.response == true) {
	            	// Do something here
	            	//$(product_element).closest('tr').find('.DSP_product_nm').text(res.data.item_nm_j);
	            	$(product_element).closest('tr').find('.DSP_product_nm').text(res.data.item_nm_j);
	            	$(product_element).closest('tr').find('.DSP_product_nm').attr('title', res.data.item_nm_j);
	            	_removeErrorStyle($(product_element).closest('tr').find('.TXT_product_cd'));
	            }else{	            	
	            	$(product_element).closest('tr').find('.DSP_product_nm').text('');
	            	//$(product_element).closest('tr').find('.TXT_product_cd').focus();
	            }
	        },
	    });
	} catch(e) {
        alert('referProduct: ' + e.message)
    }
}

/**
 * init setting mode
 *
 * @author      :   ANS810 - 2018/01/05 - create
 * @param       : 	
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function initSettingMode(mode){
	try{
		//refer data from screen search to detail
		if (mode != 'I' && $(".TXT_internalorder_cd").val() != '') {
			$(".TXT_internalorder_cd").trigger("change");			
		}

		if (mode != 'I') {
			$(".TXT_internalorder_cd").addClass("required");
		} else {
			disableInternalOrderNo();
			$(".TXT_internalorder_cd").removeClass("required");
			$(".TXT_internalorder_cd").val('');
		}


	} catch(e) {
        alert('initSettingMode: ' + e.message)
    }
}

/**
 * refer internal order infomation
 * 
 * @author : ANS810 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referInternalOrderDetail(TXT_internalorder_cd, callback) {
	try	{
		$.ajax({
			type 		: 'POST',
			url 		: '/manufactureinstruction/internalorder-detail/refer-internal-order',
			dataType	: 'json',
			data 		: {
							TXT_internalorder_cd : TXT_internalorder_cd,
							mode				 : mode
						},
			success: function(res) {
				if (res.response == true) {
					setValueRefer(res);
					disableManufactureStatusDiv();
					//_getComboboxData('JP', 'manufacture_kind_div',setSelectCombobox);
					setSelectCombobox();
					_setTabIndex();
					_dragLineTable('table-internal-order', true);
					_autoFormattingDate("input.datepicker");
				} else{
	            	jMessage('W001',function(r){
						if(r){			            	
			            	$(document).find('.DSP_hope_delivery_date_header').val('');
			            	$(document).find('#TXT_internalorder_cd').focus();
			            	//init 1 row table at mode add new (I)
							_initRowTable('table-internal-order', 'table-row', 1, updateTable);
							// Clear header information
			            	$('#operator_info').html('');
						}
					});
	            }
				if (typeof callback == 'function') {
					callback();
				}
			}
		});
	} catch (e) {
		alert('referInternalOrderDetail: ' + e.message);
	}
}


/**
 * delete internal order
 * 
 * @author : ANS810 - 2018/01/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postDelete(){
	try{
	    //get data
		var internalorder_cd 	= $('#TXT_internalorder_cd').val().trim();
	    var data = {
	    	internalorder_cd 	: internalorder_cd
	    };
	    $.ajax({
	        type        :   'POST',
	        url         :   '/manufactureinstruction/internalorder-detail/delete',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	if(res.error_cd != ''){
            		jMessage(res.error_cd);
            	}else if(res.response == true){
	            	jMessage('I002',function(r){
						if(r){
							$('#TXT_internalorder_cd').val('');
							//init 1 row table at mode add new (I)
							_initRowTable('table-internal-order', 'table-row', 1, updateTable);
							// Clear header information
			            	$('#operator_info').html('');
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
 * set value refer when refer internal order 
 *
 * @author      :   ANS810 - 2018/01/09 - create
 * @param       : 	data - Object
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function setValueRefer(res) {
	try{
		//set button
	    $('.heading-btn-group').html(res.header_button);
		//set value for header
		$('#operator_info').html(res.header_html);
		//set value for table
		$('.table_content').html(res.table_html);
	} catch(e) {
        alert('setValueRefer: ' + e.message);
    }
}

/**
 * set select Combobox when refer internal order 
 *
 * @author      :   ANS810 - 2018/01/09 - create
 * @param       : 	data - Object
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */

function setSelectCombobox() {
	try {
		$('#table-internal-order tbody tr').each(function() {
			var _manufacture_kind_div 		=	$(this).find('.CMB_manufacture_kind_div').attr('data-selected');
			if(_manufacture_kind_div != ''){
				$(this).find('.CMB_manufacture_kind_div option[value='+_manufacture_kind_div+']').prop('selected', true);	
			}			
		});
	} catch (e)  {
        alert('setSelectCombobox:  ' + e.message);
    }
}

/**
 * disable with condition when refer internal order
 *
 * @author      :   ANS810 - 2018/01/09 - create
 * @param       : 	data - Object
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */

function disableManufactureStatusDiv() {
	try {
		var isCheckAllManufactured	= true;
		$('#table-internal-order tbody tr').each(function() {
			var _manufacture_status_div		=	$(this).closest('tr').find('.TXT_manufacture_status_div').text();
			if(_manufacture_status_div != 0){
				$('#btn-add-row').prop('disabled', true);
				$('.DSP_hope_delivery_date_header').val('');
				$('.DSP_hope_delivery_date_header').prop('disabled', true);
				$(this).closest('tr').find('.TXT_product_cd').prop('disabled', true);
				$(this).closest('tr').find('.btn-search').prop('disabled', true);
				$(this).closest('tr').find('.CMB_manufacture_kind_div').prop('disabled', true);
				$(this).closest('tr').find('.TXT_in_order_qty').prop('disabled', true);
				$(this).closest('tr').find('.TXT_hope_delivery_date').prop('disabled', true);
				$(this).closest('tr').find('.TXT_detail_remarks').prop('disabled', true);
				$(this).closest('tr').find('.remove-row').prop('disabled', true);
			}else{
				isCheckAllManufactured	= false;
				$(this).closest('tr').find('.TXT_product_cd').prop('disabled', false);
				$(this).closest('tr').find('.btn-search').prop('disabled', false);
				$(this).closest('tr').find('.CMB_manufacture_kind_div').prop('disabled', false);
				$(this).closest('tr').find('.TXT_in_order_qty').prop('disabled', false);
				$(this).closest('tr').find('.TXT_hope_delivery_date').prop('disabled', false);
				$(this).closest('tr').find('.TXT_detail_remarks').prop('disabled', false);
				$(this).closest('tr').find('.remove-row').prop('disabled', false);
			}
		});
		if(!isCheckAllManufactured){
			$('.DSP_hope_delivery_date_header').prop('disabled', false);
		}
	} catch (e)  {
        alert('disableManufactureStatusDiv:  ' + e.message);
    }
}

/**
 * disable mode
 *
 * @author      :   ANS810 - 2018/01/11 - create
 * @param       : 	data - Object
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */

function disableInternalOrderNo() {
	try {
		$('.TXT_internalorder_cd').attr('disabled', true);
		$('.TXT_internalorder_cd').parent().addClass('popup-internal-search')
		$('.popup-internal-search').find('.btn-search').attr('disabled', true);
		parent.$('.popup-internal-search').removeClass('popup-internal-search');
	} catch (e) {
		alert('disableInternalOrderNo: ' + e.message);
	}
}

/**
 * print internal-order detail
 * 
 * @author : ANS810 - 2018/01/18 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postPrint() {
	try {
		var data_list 	= [];
		var _data 		= {
							in_order_no			: 	$('.TXT_internalorder_cd').val()
						};
		data_list.push(_data);
		var _internal_list = 	data_list;
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/internal-order-detail/export-excel',
	        dataType    :   'json',
			loading		:	true,
	        data        :   {
	        					internal_list : _internal_list
	        				},
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		jMessage('I004')
	            		//download excel
	            		location.href = res.fileName;
	            	} 
	            } else {
	            	//catch DB error and display
	            	var msg_e999 = _text['E999'].replace('{0}', res.error);
	            	jMessage_str('E999', msg_e999, '', msg_e999);
	            }
	        },
	    });
		   
	} catch (e) {
         alert('postPrint' + e.message);
    }
}

/**
 * check invalid 1 row
 *
 * @author      :   ANS810 - 2018/01/18 - create
 * @param       : 	
 * @return      :   boolean
 * @access      :   public
 * @see         :   init
 */

function checkInvalidRow() {
	try {
		var check 	= 0;
		var flag 	= false;
		$('#table-internal-order tbody tr').each(function() {	
			check 	= 	0;
			var product_cd 		= 	$(this).closest('tr').find('.TXT_product_cd').val();
			var in_order_qty 	=	$(this).closest('tr').find('.TXT_in_order_qty').val();
			if(product_cd != ''){
				check++;
			}
			if(in_order_qty != ''){
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
 * raiseErrorE005
 *
 * @author      :   ANS810 - 2018/01/30 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function raiseErrorE005(arr_product_cd) {
	try {
		$('#table-internal-order tbody tr').each(function() {
			var product_cd = $(this).find('.TXT_product_cd').val().trim();
			for (var i = 0; i < arr_product_cd.length; i++) {
			    if(product_cd == arr_product_cd[i].product_cd){
					$(this).find('.TXT_product_cd').errorStyle(_text['E005']);
				}
			};
		});				
    } catch (e) {
        alert('raiseErrorE005: ' + e.message);
    }
}
