/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	DuyTP
 *
 * 更新日		: 	2017/12/12
 * 更新者		: 	HaVV - ANS817
 * 更新内容		: 	New Development
 *
 * @package		:	INVOICE
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

$(document).ready(function () {
	removeBtnDelete();
	initEvents();
 	initCombobox();
});
/**
 * remove button Delete
 * @author  :   ANS817 - 2017/12/13 - create
 * @param
 * @return
 */
function removeBtnDelete() {
	if(mode=='I'){
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
	//IF _getComboboxData completed THEN refer data from screen search to detail
	/*$.when(
		_getComboboxData(name, 'unit_q_div'),
		_getComboboxData(name, 'parts_kind_div'),
		_getComboboxData(name, 'exists_div'),
		_getComboboxData(name, 'parts_order_div'),
		_getComboboxData(name, 'order_level_div'),
	).done(function(){
		//refer data from screen search to detail
		if (mode == 'U') {
			$('#TXT_parts_cd').trigger('change');
		}
	});*/
	// _getComboboxData(name, 'unit_q_div');
	// _getComboboxData(name, 'parts_kind_div');
	// _getComboboxData(name, 'exists_div');
	// _getComboboxData(name, 'parts_order_div');
	// _getComboboxData(name, 'order_level_div', function(){
		//refer data from screen search to detail
	if (mode == 'U' && from == 'ComponentMasterSearch' && $('#TXT_parts_cd').val() != '') {
		$('#TXT_parts_cd').trigger('change');
	}
	// });
}
/**
 * init Events
 * @author  :   DuyTP - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		// remove row table
		$(document).on('click','.BTN_Delete_line',function(e){
			var obj   = $(this);
			_removeRowTable('table-purchase-price', obj, 'C002', false);
		});

		//add row
		$(document).on('click', '#BTN_Add_line', function () {
			try {
				// $('#table-purchase-price tbody #row-empty').remove();
				_addNewRowTable('table-purchase-price', 'table-row', null, updateTable);
				if($('#table-purchase-price tbody tr').length == 1){
					$('#table-purchase-price tbody tr:first input.styled').prop('checked', true);
				}
			} catch (e) {
				alert('add new row' + e.message);
			}
		});

		//init back
		$(document).on('click', '#btn-back', function () {
			if (from == 'ComponentMasterSearch') {
				sessionStorage.setItem('detail', true);
				location.href = '/master/component-master-search';
			}
		});

		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				//validate not ok
				var isCheck1 = validate();
				var isCheck2 = isExistRadioChecked();
				if (!isCheck1 || !isCheck2) {
					return;
				}

				//if table component hasn't detail row then show message error
				if (!isExistRow()) {
					jMessage('E004');
					return;
				}

				//if duplicate key
				var duplicateKey = isDuplicateKeyInTable();
				if (duplicateKey.flag) {
					var msgText = _text['E013'].replace('{1}', duplicateKey.val).replace('{2}', duplicateKey.index);
					jMessage('E013', function() {
						var TXT_purchaser_order_cd = $('#table-purchase-price tbody tr').eq(duplicateKey.index - 1).find('.TXT_purchaser_order_cd');
						$(TXT_purchaser_order_cd).focus();
					}, msgText);
					return;
				}

				//validate ok
				var msg = (mode == 'I')?'C001':'C003';
				jMessage(msg,function(r){
					if(r){
						save();
					}
				});
			} catch (e) {
				alert('#btn-save: ' + e.message);
			}
		});
 		

		// button delete
		$(document).on('click', '#btn-delete', function() {
			try {
				if($.trim($('#TXT_parts_cd').val()) == '' ) {
					$('#TXT_parts_cd').errorStyle(_MSG_E001);
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

 		//change 単位
 		$(document).on('change', '#CMB_unit', function(){
 			var mn_e = $("#CMB_unit option:selected").attr('data-hdn_nm');
 			if (mn_e === undefined) {
 				mn_e = '';
 			}
			$('#lbl_CMB_unit_e').text(mn_e);
 		});

 		// Change 部品コード
		$(document).on('change', '#TXT_parts_cd', function() {
			try {
				referPart();
			} catch (e) {
				alert('change #TXT_parts_cd: ' + e.message);
			}
		});

		// Change 発注先コード
		$(document).on('change', '.TXT_purchaser_order_cd', function() {
			try {
				referPurchasePrice(this);
			} catch (e) {
				alert('change #TXT_parts_cd: ' + e.message);
			}
		});

		// Change メイン
		$(document).on('change', '.RDI_main', function() {
			try {
				var checked = $(this)[0].checked;
				if (checked) {
					_removeErrorStyle($('#table-purchase-price tbody input.RDI_main'));
				}
			} catch (e) {
				alert('change #TXT_parts_cd: ' + e.message);
			}
		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}

/**
 * update table
 * using callback in function common _addNewRowTable
 *
 * @author		:	ANS817 - 2017/12/13 - create
 * @params		:	null
 * @return		:	null
 */
function updateTable() {
	_updateTable('table-purchase-price', false);
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
 * check exist detail row of table component
 *
 * @author      :   ANS817 - 2017/12/12 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function isExistRadioChecked() {
    try {
    	var isCheck = false;
    	$('#table-purchase-price tbody tr').each(function () {
    		if($(this).find('input.styled').is(':checked')){
    			isCheck = true;
    		}
    	});
    	if(!isCheck){
    		jMessage('E671', function(r) {
    			if (r) {
    				$('#table-purchase-price tbody input.RDI_main').errorStyle(_text['E001']);
    			}
    		});
    	}
    	return isCheck;
    } catch (e) {
        alert('isExistEmptyRow: ' + e.message);
    }
}

/**
 * check exist detail row of table component
 *
 * @author      :   ANS817 - 2017/12/12 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function isExistRow() {
	try {
    	var isEmpty = $('#table-purchase-price tbody tr').length > 0;
    	return isEmpty;
    } catch (e) {
        alert('isExistRow: ' + e.message);
    }
}

/**
 * check Duplicate key in table component
 *
 * @author      :   ANS817 - 2017/12/12 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function isDuplicateKeyInTable() {
    try {
    	var duplicateKey = {
    		flag	: 	false,
    		index	: 	null,
    		val		: 	null,
    	};

    	var arrSuppliersCd = [];
    	$('#table-purchase-price tbody tr').each(function (index, element) {
    		var obj = {
    			'index'	: 	index + 1,
    			'val' 	: 	$(element).find('.TXT_purchaser_order_cd').val()
    		};
    		arrSuppliersCd.push(obj);
    	});

    	var sorted_arr = arrSuppliersCd.sort(function (a, b) {
    		return (a.val > b.val) ? 1 : ((b.val > a.val) ? -1 : 0);
    	});

    	for (var i = 0; i < sorted_arr.length - 1; i++) {
		    if (sorted_arr[i + 1].val == sorted_arr[i].val) {
				duplicateKey.flag  = true;
				duplicateKey.index = sorted_arr[i + 1].index;
				duplicateKey.val   = sorted_arr[i + 1].val;
		        return duplicateKey;
		    }
		}

    	return duplicateKey;
    } catch (e) {
        alert('isDuplicateKeyInTable: ' + e.message);
    }
}

/**
 * add empty row when table empty row
 *
 * @author      :   ANS817 - 2017/12/12 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function addEmptyRow() {
	try {
		var countRow = $('#table-purchase-price tbody tr').length;
		if (countRow == 0) {
			var emptyRow = $('#table-row-empty #row-empty').clone();
			$('#table-purchase-price tbody').append(emptyRow);
		}
    } catch (e) {
        alert('addEmptyRow: ' + e.message);
    }
}

/**
 * save component - insert/update
 *
 * @author      :   ANS817 - 2017/12/12 - create
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
	        url         :   '/master/component-master-detail/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	//display E005 OR E670 in table
	        	if(res.data_err != null){
	        		var msg = res.data_err[0]['error_cd'];
	        		jMessage(msg, function(r){
	        			if(r){
	        				for(var i = 0; i < res.data_err.length; i++){
	        					if(res.data_err[i]['item_err'] == 'TXT_purchaser_order_cd'){
	        						$('#table-purchase-price tbody tr').eq(res.data_err[i]['item_idx']).find('.TXT_purchaser_order_cd').errorStyle(_text[res.data_err[i]['error_cd']]);
	        					}
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
							referPart();
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
 * @author      :   ANS817 - 2017/12/12 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function getDataFromView() {
	try {
		var m_purchase_price   = [];
		$('#table-purchase-price tbody tr').each(function() {
			if ($(this).attr('id') != 'row-empty') {
				var ini_target_div          = $(this).find('.RDI_main')[0].checked ? 1 : 0;
				var purchase_unit_price_JPY = $.trim($(this).find('.TXT_standard_unit_price_JPY').val());
				purchase_unit_price_JPY     = purchase_unit_price_JPY == '' ? 0 : purchase_unit_price_JPY.replace(/,/g, '');
				var purchase_unit_price_USD = $.trim($(this).find('.TXT_standard_unit_price_USD').val());
				purchase_unit_price_USD     = purchase_unit_price_USD == '' ? 0 : purchase_unit_price_USD.replace(/,/g, '');
				var purchase_unit_price_EUR = $.trim($(this).find('.TXT_standard_unit_price_EUR').val());
				purchase_unit_price_EUR     = purchase_unit_price_EUR == '' ? 0 : purchase_unit_price_EUR.replace(/,/g, '');
				var order_lot_qty           = $.trim($(this).find('.TXT_order_lot_size').val());
				order_lot_qty               = order_lot_qty == '' ? 0 : order_lot_qty.replace(/,/g, '');
				var lower_limit_lot_qty     = $.trim($(this).find('.TXT_lower_limit_lot_size').val());
				lower_limit_lot_qty         = lower_limit_lot_qty == '' ? 0 : lower_limit_lot_qty.replace(/,/g, '');
				var upper_limit_lot_qty     = $.trim($(this).find('.TXT_maximum_lot_size').val());
				upper_limit_lot_qty         = upper_limit_lot_qty == '' ? 0 : upper_limit_lot_qty.replace(/,/g, '');

				var _data = {
					row_idx					: 	$(this).index(),
					ini_target_div			: 	ini_target_div,
					supplier_cd				: 	$.trim($(this).find('.TXT_purchaser_order_cd').val()),
					purchase_unit_price_JPY	: 	parseFloat(purchase_unit_price_JPY),
					purchase_unit_price_USD	: 	parseFloat(purchase_unit_price_USD),
					purchase_unit_price_EUR	: 	parseFloat(purchase_unit_price_EUR),
					order_lot_qty			: 	parseInt(order_lot_qty),
					lower_limit_lot_qty		: 	parseInt(lower_limit_lot_qty),
					upper_limit_lot_qty		: 	parseInt(upper_limit_lot_qty)
				};
				m_purchase_price.push(_data);
			}
		});

		var contained_qty      = $.trim($('#TXT_contained_qty').val());
		contained_qty          = contained_qty == '' ? 0 : contained_qty.replace(/,/g, '');
		var order_point_qty    = $.trim($('#TXT_order_point_qty').val());
		order_point_qty        = order_point_qty == '' ? 0 : order_point_qty.replace(/,/g, '');
		var economic_order_qty = $.trim($('#TXT_economic_order_qty').val());
		economic_order_qty     = economic_order_qty == '' ? 0 : economic_order_qty.replace(/,/g, '');

		var data = {
			mode					: 	mode,
			parts_cd				: 	$.trim($('#TXT_parts_cd').val()),
			item_nm_j				: 	$.trim($('#TXT_part_nm_j').val()),
			item_nm_e				: 	$.trim($('#TXT_part_nm_e').val()),
			specification			: 	$.trim($('#TXT_specification').val()),
			unit_qty_div			: 	$.trim($('#CMB_unit').val()),
			contained_qty			: 	parseInt(contained_qty),
			parts_kind_div			: 	$.trim($('#CMB_classification').val()),
			stock_management_div	: 	$.trim($('#CMB_inventory_control').val()),
			parts_order_div			: 	$.trim($('#CMB_management_method').val()),
			order_point_qty			: 	parseInt(order_point_qty),
			economic_order_qty		: 	parseInt(economic_order_qty),
			order_level_div			: 	$.trim($('#CMB_order_level').val()),
			remarks					: 	$.trim($('#TXT_remarks').val()),
			m_purchase_price		: 	m_purchase_price
		};

		return data;
    } catch (e) {
        alert('save: ' + e.message);
    }
}

/**
 * refer Part
 *
 * @author      :   ANS817 - 2017/12/13 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function referPart(){
	try{
		//clear all error
		_clearErrors();
		//get data
		var parts_cd 	= $.trim($('#TXT_parts_cd').val());
	    var data = {
	    	parts_cd 	: parts_cd,
	    	mode		: mode,
	    };
	    $.ajax({
	        type        :   'POST',
	        url         :   '/master/component-master-detail/refer-part',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if(res.response == true) {
	            	setValueAllItem(res);
	            	$('#operator_info').html(res.info_header);

	            	if(res.component_data != undefined && res.component_data.is_parts == '1'){
		            	mode = 'U';
		            }else{
		            	mode = 'I';
		            }

	            	$('.heading-btn-group').html(res.button_header);

	            	!!res.lastestComponent['parts_cd'] ? $(document).find('#label_parts').html('最終登録部品：') : false;
	            	!!res.lastestComponent['parts_cd'] ? $(document).find('#lastest_parts_cd').html(res.lastestComponent['parts_cd']) : false;
	            	!!res.lastestComponent['item_nm_j'] ? $(document).find('#lastest_item_nm_j').html(res.lastestComponent['item_nm_j']) : false;

		            removeBtnDelete();
		            _setTabIndex();
	            } else {
	            	if(parts_cd == ''){
	            		if (mode == 'U') {
		            		clearAllItem();
		            	} else if (mode == 'I') {
		            		//clear operator_info
							$('#operator_info').html('');
		            	}
						mode = 'I';
	            		$('.heading-btn-group').html(res.button_header);
			            removeBtnDelete();
			            _setTabIndex();
		            }else{
		            	if (mode == 'U') {
		            		clearAllItem();
		            		/*jMessage('W001',function(r){
								if(r){
				            		clearAllItem();
				            		}
								});*/
		            	} else if (mode == 'I') {
		            		//clear operator_info
							$('#operator_info').html('');
		            	}
						mode = 'I';
						$('.heading-btn-group').html(res.button_header);
			            removeBtnDelete();
			            _setTabIndex();
		            }
	            }
	        },
	    });
	} catch(e) {
        alert('referPart: ' + e.message)
    }
}

/**
 * set value for all item when refer Part 
 *
 * @author      :   ANS817 - 2017/12/13 - create
 * @param       : 	data - Object
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function setValueAllItem(data) {
	try{
		$('#TXT_parts_cd').val(data.component_data.parts_cd);
		$('#TXT_part_nm_j').val(data.component_data.item_nm_j);
		$('#TXT_part_nm_e').val(data.component_data.item_nm_e);
		$('#TXT_specification').val(data.component_data.specification);
		$('#CMB_unit').val(data.component_data.unit_qty_div).change();
		$('#TXT_contained_qty').val(data.component_data.contained_qty);
		$('#CMB_classification').val(data.component_data.parts_kind_div);
		$('#CMB_inventory_control').val(data.component_data.stock_management_div);
		$('#CMB_management_method').val(data.component_data.parts_order_div);
		$('#TXT_order_point_qty').val(data.component_data.order_point_qty);
		$('#TXT_economic_order_qty').val(data.component_data.economic_order_qty);
		$('#CMB_order_level').val(data.component_data.order_level_div);
		$('#TXT_remarks').val(data.component_data.remarks);

		//set value for table
		$('#div-table-purchase-price').html(data.table_purchase_price);
	} catch(e) {
        alert('setValueAllItem: ' + e.message)
    }
}

/**
 * clear all item (not parts_cd)
 *
 * @author      :   ANS817 - 2017/12/13 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function clearAllItem() {
	try{
		//clear operator_info
		$('#operator_info').html('');

		//clear item
		//$('#TXT_parts_cd').val('');
		$('#TXT_part_nm_j').val('');
		$('#TXT_part_nm_e').val('');
		$('#TXT_specification').val('');
		$('#CMB_unit').val('').change();
		$('#TXT_contained_qty').val('');
		$('#CMB_classification').val('');
		$('#CMB_inventory_control').val('');
		$('#CMB_management_method').val('');
		$('#TXT_order_point_qty').val('');
		$('#TXT_economic_order_qty').val('');
		$('#CMB_order_level').val('');
		$('#TXT_remarks').val('');

		//remove all row in table
		$('#table-purchase-price tbody tr').remove();
		//add row new
		$('#BTN_Add_line').trigger('click');

		//set default value of select
    	$('select').each(function() {
    		if($(this).attr('data-ini-target') == 'true'){
	    		var objParent = $(this);
	    		objParent.find('option').each(function(){
	    			if($(this).attr('data-ini_target_div') == 1){
						objParent.val($(this).attr('value'));
					}
	    		});
	    	}
		});
	} catch(e) {
        alert('clearAllItem: ' + e.message)
    }
}

/**
 * delete part
 * 
 * @author : ANS817 - 2017/12/13 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postDelete(){
	try{
	    //get data
		var parts_cd 	= $.trim($('#TXT_parts_cd').val());
	    var data = {
	    	parts_cd 	: parts_cd
	    };
	    $.ajax({
	        type        :   'POST',
	        url         :   '/master/component-master-detail/delete',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	if(res.error_cd != ''){
            		jMessage(res.error_cd);
            	}else if(res.response == true){
            		//
            		if (is_new == 'true') {
						mode	=	'U';
					} else {
						mode	=	'I';
					}
	            	jMessage('I002',function(r){
						if(r){
							$('#TXT_parts_cd').val('');
							var param = {
								'mode'		: mode,
								'from'		: from,
								'is_new'	: is_new
							};
							_postParamToLink(from, 'ComponentMasterDetail', '/master/component-master-detail', param);
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
 * refer purchase price
 *
 * @author      :   ANS817 - 2017/12/13 - create
 * @param       : 	purchaser_order_cd_elm - DOM element
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function referPurchasePrice(purchaser_order_cd_elm){
	try{
		//get data
		var suppliers_cd 	= $.trim($(purchaser_order_cd_elm).val());
	    var data = {
	    	parts_cd 		: $.trim($('#TXT_parts_cd').val()),
	    	suppliers_cd 	: suppliers_cd
	    };
	    $.ajax({
	        type        :   'POST',
	        url         :   '/master/component-master-detail/refer-purchase-price',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if(res.response == true) {
	            	if (res.data['ini_target_div'] == 1) {
	            		$(purchaser_order_cd_elm).closest('tr').find('.RDI_main').prop('checked', true);
	            	}

	            	//$(purchaser_order_cd_elm).closest('tr').find('.RDI_main').prop('checked', checked);
	            	$(purchaser_order_cd_elm).val(res.data['supplier_cd']);
	            	$(purchaser_order_cd_elm).closest('tr').find('.DSP_purchaser_order_nm').html(res.data['client_nm']);
	            	$(purchaser_order_cd_elm).closest('tr').find('.TXT_standard_unit_price_JPY').val(res.data['purchase_unit_price_JPY']);
	            	$(purchaser_order_cd_elm).closest('tr').find('.TXT_standard_unit_price_USD').val(res.data['purchase_unit_price_USD']);
	            	$(purchaser_order_cd_elm).closest('tr').find('.TXT_standard_unit_price_EUR').val(res.data['purchase_unit_price_EUR']);
	            	$(purchaser_order_cd_elm).closest('tr').find('.TXT_order_lot_size').val(res.data['order_lot_qty']);
	            	$(purchaser_order_cd_elm).closest('tr').find('.TXT_lower_limit_lot_size').val(res.data['lower_limit_lot_qty']);
	            	$(purchaser_order_cd_elm).closest('tr').find('.TXT_maximum_lot_size').val(res.data['upper_limit_lot_qty']);
	            }else{
	            	if(suppliers_cd == ''){
	            		clearItemInRow($(purchaser_order_cd_elm).closest('tr'));
		            }else{
		            	jMessage('W001',function(r){
							if(r){
				            	clearItemInRow($(purchaser_order_cd_elm).closest('tr'));
				            	$(purchaser_order_cd_elm).focus();
							}
						});
		            }
	            }
				_removeErrorStyle($(purchaser_order_cd_elm));
	        },
	    });
	} catch(e) {
        alert('referPurchasePrice: ' + e.message)
    }
}

/**
 * clear item in row
 *
 * @author      :   ANS817 - 2017/12/14 - create
 * @param       : 	trElment - DOM element
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function clearItemInRow(trElment) {
	//$(trElment).find('.RDI_main').prop('checked', false);
	//$(trElment).find('.TXT_purchaser_order_cd').val('');
	$(trElment).find('.DSP_purchaser_order_nm').html('');
	$(trElment).find('.TXT_standard_unit_price_JPY').val('');
	$(trElment).find('.TXT_standard_unit_price_USD').val('');
	$(trElment).find('.TXT_standard_unit_price_EUR').val('');
	$(trElment).find('.TXT_order_lot_size').val('');
	$(trElment).find('.TXT_lower_limit_lot_size').val('');
	$(trElment).find('.TXT_maximum_lot_size').val('');
}