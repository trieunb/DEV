/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	DuyTP
 *
 * 更新日		: 	2017/12/18
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
 * @author  :   ANS817 - 2017/12/18 - create
 * @param
 * @return
 */
function removeBtnDelete() {
	if(mode=='U'){
        $('#btn-delete').show();
    }else{
    	$('#btn-delete').hide();
    }
}

function initCombobox() {
	//IF _getComboboxData completed THEN refer data from screen search to detail
	/*$.when(
		_getComboboxData('JP', 'unit_q_div'),
		_getComboboxData('JP', 'outsourcing_div'),
		_getComboboxData('JP', 'exists_div'),
		_getComboboxData('JP', 'unit_w_div'),
		_getComboboxData('JP', 'unit_m_div'),
	).done(function(){
		//refer data from screen search to detail
		if (mode == 'U') {
			$('#TXT_product_cd').trigger('change');
		}
	});*/
	/*_getComboboxData('JP', 'unit_q_div', function(){
		$('#CMB_unit').trigger('change');
	});*/
	// _getComboboxData('JP', 'outsourcing_div');
	// _getComboboxData('JP', 'exists_div');
	// _getComboboxData('JP', 'unit_w_div');
	// _getComboboxData('JP', 'unit_m_div', function(){
	$('#CMB_unit').trigger('change');
		//refer data from screen search to detail
	if (mode == 'U' && from == 'ProductMasterSearch' && $('#TXT_product_cd').val() != '') {
		$('#TXT_product_cd').trigger('change');
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

		//init back
		$(document).on('click', '#btn-back', function () {
			if (from == 'ProductMasterSearch') {
				sessionStorage.setItem('detail', true);
				location.href = '/master/product-master-search';
			}
		});

		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				//validate not ok
				if (!validate()) {
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
				console.log('#btn-save: ' + e.message);
			}
		});
 		
		// button delete
		$(document).on('click', '#btn-delete', function() {
			try {
				if($.trim($('#TXT_product_cd').val()) == '' ) {
					$('#TXT_product_cd').errorStyle(_MSG_E001);
				}else{
					jMessage('C002', function(r){
						if(r){
							postDelete();
						}
					});
				}
			} catch (e) {
				console.log('#btn-delete: ' + e.message);
			}
		});

 		//change 単位
 		$(document).on('change', '#CMB_unit', function(){
 			var mn_e = $("#CMB_unit option:selected").attr('data-nm-e');
 			if (mn_e === undefined) {
 				mn_e = '';
 			}
			$('#lbl_CMB_unit_e').text(mn_e);
 		});

 		// Change 製品コード
		$(document).on('change', '#TXT_product_cd', function() {
			try {
				refer();
			} catch (e) {
				console.log('change #TXT_product_cd: ' + e.message);
			}
		});
		$(document).on('keypress', '.weight, .measure', function(event) {
		   	var $this   = $(this);
		   	var negative  = (typeof $this.attr('negative') === 'undefined')?0:(1*$this.attr('negative'));
		   	var text   = $(this).val();
		   	if ($this.hasClass('weight') || $this.hasClass('measure')) {
		    if((negative!=1 || text.indexOf('-') > -1)&&(event.which == 45)) {
		    	event.preventDefault();
		    } else if(text.indexOf('-')==-1 && event.which==45 && negative==1) {
		            $this.val('-'+text);
		        }
		   	}
		});
	} catch (e) {
		console.log('initEvents: ' + e.message);
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

/**
 * save product - insert/update
 *
 * @author      :   ANS817 - 2017/12/18 - create
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
	        url         :   '/master/product-master-detail/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	if(res.error_cd != ''){
            		jMessage(res.error_cd);
            	}else if(res.response == true){
	            	var msg = (mode == 'I')?'I001':'I003';
	            	jMessage(msg,function(r){
						if(r){
							//change mode after save success
							mode = 'U';
							refer();
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
        console.log('save: ' + e.message);
    }
}


/**
 * get data from view
 *
 * @author      :   ANS817 - 2017/12/18 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function getDataFromView() {
	try {
		var net_weight   = $.mbTrim($('#TXT_net_weight').val());
		net_weight       = net_weight == '' ? 0 : net_weight.replace(/,/g, '');
		var gross_weight = $.mbTrim($('#TXT_gross_weight').val());
		gross_weight     = gross_weight == '' ? 0 : gross_weight.replace(/,/g, '');
		var measure      = $.mbTrim($('#TXT_measurement').val());
		measure          = measure == '' ? 0 : measure.replace(/,/g, '');

		var data = {
			mode					: 	mode,
			product_cd				: 	$.mbTrim($('#TXT_product_cd').val()),
			item_nm_j				: 	$.mbTrim($('#TXT_product_nm_j').val()),
			item_nm_e				: 	$.mbTrim($('#TXT_product_nm_e').val()),
			specification			: 	$.mbTrim($('#TXT_specification').val()),
			unit_qty_div			: 	$.mbTrim($('#CMB_unit').val()),
			outsourcing_div			: 	$.mbTrim($('#CMB_internal_manufacturing_outsource').val()),
			stock_management_div	: 	$.mbTrim($('#CMB_inventory_control').val()),
			serial_management_div	: 	$.mbTrim($('input[name="RDI_serial_management"]:checked').val()),
			last_serial_no			: 	'',
			jan_code				: 	$.mbTrim($('#TXT_jan_code').val()),
			net_weight				: 	parseFloat(net_weight),
			unit_net_weight_div		: 	$.mbTrim($('#CMB_nw_unit').val()),
			gross_weight			: 	parseFloat(gross_weight),
			unit_gross_weight_div	: 	$.mbTrim($('#CMB_gw_unit').val()),
			measure					: 	parseFloat(measure),
			unit_measure_div		: 	$.mbTrim($('#CMB_measurement_unit').val()),
			remarks					: 	$.mbTrim($('#TXT_remarks').val()),
		};

		return data;
    } catch (e) {
        console.log('getDataFromView: ' + e.message);
    }
}

/**
 * refer product
 *
 * @author      :   ANS817 - 2017/12/18 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function refer(){
	try{
		//clear all error
		_clearErrors();
		//get data
		var product_cd 	= $('#TXT_product_cd').val().trim();
	    var data = {
	    	product_cd 	: product_cd,
	    	mode 		: mode
	    };
	    $.ajax({
	        type        :   'POST',
	        url         :   '/master/product-master-detail/refer',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if(res.response == true) {
	            	setValueAllItem(res);
	            	$('#operator_info').html(res.info_header);
	            	if(res.data != undefined && res.data.is_product == '1'){
		            	mode = 'U';
		            }else{
		            	mode = 'I';
		            }
	            	$('.heading-btn-group').html(res.button_header);
		            removeBtnDelete();
		            _setTabIndex();
	            }else{
	            	if(product_cd == ''){
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
        console.log('referProduct: ' + e.message)
    }
}

/**
 * set value for all item when refer Part 
 *
 * @author      :   ANS817 - 2017/12/13 - create
 * @param       : 	res - Object
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function setValueAllItem(res) {
	try{
		if (res.data.length == 0) {
			return;
		}

		$('#TXT_product_cd').val(res.data.product_cd);
		$('#TXT_product_nm_j').val(res.data.item_nm_j);
		$('#TXT_product_nm_e').val(res.data.item_nm_e);
		$('#TXT_specification').val(res.data.specification);
		$('#CMB_unit').val(res.data.unit_qty_div).change();
		$('#CMB_internal_manufacturing_outsource').val(res.data.outsourcing_div);
		$('#CMB_inventory_control').val(res.data.stock_management_div);

		$('input[name="RDI_serial_management"][value="'+res.data.serial_management_div+'"]').prop('checked', true);
		
		/*if (res.data.serial_management_div == 1) {
			$('input[name="RDI_serial_management"][value="1"]').prop('checked', true);
		} else {
			$('input[name="RDI_serial_management"][value="0"]').prop('checked', true);
		}*/

		$('#DSP_last_serial_no').html(res.data.last_serial_no);
		$('#TXT_jan_code').val(res.data.jan_code);
		$('#TXT_net_weight').val(res.data.net_weight);
		$('#CMB_nw_unit').val(res.data.unit_net_weight_div);
		$('#TXT_gross_weight').val(res.data.gross_weight);
		$('#CMB_gw_unit').val(res.data.unit_gross_weight_div);
		$('#TXT_measurement').val(res.data.measure);
		$('#CMB_measurement_unit').val(res.data.unit_measure_div);
		$('#TXT_remarks').val(res.data.remarks);
	} catch(e) {
        console.log('setValueAllItem: ' + e.message)
    }
}

/**
 * clear all item (not product_cd)
 *
 * @author      :   ANS817 - 2017/12/18 - create
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
		//$('#TXT_product_cd').val('');
		$('#TXT_product_nm_j').val('');
		$('#TXT_product_nm_e').val('');
		$('#TXT_specification').val('');
		$('#CMB_unit').val('').change();
		$('#CMB_internal_manufacturing_outsource').val('');
		$('#CMB_inventory_control').val('');
		$('#DSP_last_serial_no').html('');
		$('#TXT_jan_code').val('');
		$('#TXT_net_weight').val('');
		$('#CMB_nw_unit').val('');
		$('#TXT_gross_weight').val('');
		$('#CMB_gw_unit').val('');
		$('#TXT_measurement').val('');
		$('#CMB_measurement_unit').val('');
		$('#TXT_remarks').val('');

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
		if($('#CMB_inventory_control').val() != '' && $('#CMB_inventory_control').val() != null){
			$('input[name="RDI_serial_management"][value="'+$('#CMB_inventory_control').val()+'"]').prop('checked', true);
		}else{
			$('input[name="RDI_serial_management"][value="1"]').prop('checked', true);
		}
	} catch(e) {
        console.log('clearAllItem: ' + e.message)
    }
}


/**
 * delete product
 * 
 * @author : ANS817 - 2017/12/20 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postDelete(){
	try{
	    //get data
		var product_cd 	= $('#TXT_product_cd').val().trim();
	    var data = {
	    	product_cd 	: product_cd
	    };
	    $.ajax({
	        type        :   'POST',
	        url         :   '/master/product-master-detail/delete',
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
							$('#TXT_product_cd').val('');
		            		var param = {
								'mode'		: mode,
								'from'		: from,
								'is_new'	: is_new
							};
							_postParamToLink(from, 'ProductMasterDetail', '/master/product-master-detail', param);
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
        console.log('postDelete: ' + e.message)
    }
}
