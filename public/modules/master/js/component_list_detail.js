/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/12/10
 * 作成者		:	Trieunb
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	COMPONENT LIST DETAIL
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */
 $(document).ready(function () {
 	// initCombobox();
	initEvents();
	// if($('.TXT_parent_item_cd').val() != '' && mode != 'I') {
	// 	$(this).parent().addClass('popup-componentproduct');
	// 	var data = {
	// 		'item_cd'		: 	$('.TXT_parent_item_cd').val(),
	// 	}
	// 	_referMItem(data);
	// }
	// if($('.TXT_child_item_cd').val() != '' && mode != 'I') {
	// 	$(this).parent().addClass('popup-componentproduct');
	// 	var data = {
	// 		'item_cd'		: 	$('.TXT_child_item_cd').val(),
	// 	}
	// 	_referMItem(data);
	// }
	if(from == 'ComponentListSearch' && mode == 'U' 
		&& $('.TXT_parent_item_cd').val() != '' 
		&& $('.TXT_child_item_cd').val() != '') {
	 		referBomDetail();
	}
	$('.infor-created').html('');
});
// function initCombobox() {
// 	var name = 'JP';
// 	_getComboboxData(name, 'unit_q_div');
// }
/**
 * init Events
 * @author  :   Trieunb - 2017/12/20 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//init back
		$(document).on('click', '#btn-back', function () {
			sessionStorage.setItem('detail', true);
			location.href = '/master/component-list-search';
		});
		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				if (mode == 'I') {
					var msg = 'C001';
				} else {
					var msg = 'C003';
				}
				if(validate()){
					if (checkParentAndChild()) {
						if (_checkDateFromTo('date-from-to')) {
							jMessage(msg, function(r) {
								if(r) {
									save();
								}
							});
						}
					}
					
				}
			} catch (e) {
				alert('#btn-save ' + e.message);
			}
		});
		// button delete
		$(document).on('click', '#btn-delete', function() {
			try {
				if(validateDelComponentProduct()){
					jMessage('C002', function(r) {
						if (r) {
							deleteBom();
						}
					});
				}
			} catch (e) {
				alert('#btn-delete ' + e.message);
			}
		});
		//change TXT_parent_item_cd 
		$(document).on('change', '.TXT_parent_item_cd', function() {
			var data = {
				'item_cd'		: 	$.mbTrim($('.TXT_parent_item_cd').val()),
			}
			referMItem(data, $(this), referBomDetail, true);
		});
		//change TXT_child_item_cd 
		$(document).on('change', '.TXT_child_item_cd', function() {
			var data = {
				'item_cd'		: 	$.mbTrim($('.TXT_child_item_cd').val()),
			}
			referMItem(data, $(this), referBomDetail, true);
		});
		//blur TXT_child_item_qty 
		$(document).on('blur', '.TXT_child_item_qty', function() {
			var val = $(this).val();
			if (val == 0) {
				$(this).val('');
			}
		});

	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * validate
 *
 * @author		:	Trieunb - 2017/12/18 - create
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
function checkParentAndChild() {
	var flag	=	true;
	var parent_item 	= $('.TXT_parent_item_cd').val();
	var child_item 		= $('.TXT_child_item_cd').val();
	if (parent_item == child_item) {
		jMessage('E752', function(r){
			if (r) {
				$('.TXT_parent_item_cd').errorStyle(_text['E752']);
				$('.TXT_child_item_cd').errorStyle(_text['E752']);
			}
		});
		flag	=	false;
	}
	return flag;
}
/**
 * save bom
 *
 * @author		:	Trieunb - 2017/12/20 - create
 * @params		:	null
 * @return		:	null
 */
function save() {
	try{
	    var data = getData();
	    $.ajax({
	        type        :   'POST',
	        url         :   '/master/component-list-detail/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	//display E005 error when 親品目コード and 子品目コード not exists
		        	if(res.data_err != null){
		        		jMessage('E005', function(r){
		        			if(r){
		        				for(var i = 0; i < res.data_err.length; i++){
		        					$('.'+res.data_err[i]['item_err']).errorStyle(_text[res.data_err[i]['msg_err']]);
		        				}
		        			}
		        		});
		        	}else if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		var msg = (mode == 'I') ? 'I001' : 'I003';
	            		jMessage(msg, function(r){
		                	if(r){
		                		referBomDetail();
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
 * get data for save bom
 *
 * @author		:	Trieunb - 2017/12/20 - create
 * @params		:	null
 * @return		:	null
 */
function getData() {
	try{
		var data = {};
		data = {
			'mode'					: 	mode,
			'parent_item_cd'		: 	$('.TXT_parent_item_cd').val(),
			'child_item_cd'			: 	$('.TXT_child_item_cd').val(),
			'child_item_qty'		: 	$('.TXT_child_item_qty').val().replace(/,/g, ''),
			'unit_q_div'			: 	$('.CMB_unit').val(),
			'apply_st_date'			: 	$('.TXT_application_period_from').val(),
			'apply_ed_date'			: 	$('.TXT_application_period_to').val(),
			'remarks'				: 	$('.TXT_remarks').val(),
		};
		return data;
	} catch(e) {
        console.log('getData' + e.message)
    }
}
/**
 * refer bom detail
 *
 * @author		:	Trieunb - 2017/12/20 - create
 * @params		:	null
 * @return		:	null
 */
function referBomDetail() {
	try{
		var data = {
			'parent_item_cd'		: 	$('.TXT_parent_item_cd').val(),
			'child_item_cd'			: 	$('.TXT_child_item_cd').val(),
			'mode' 					: 	mode
		}
		$.ajax({
			type 		: 'GET',
			url 		: '/master/component-list-detail/refer-bom',
			dataType	: 'json',
			data 		: data,
			success: function(res) {
				if (res.data === '' || res.data == null) {
					mode = 'I';
					// if (mode == 'U' && $('.TXT_parent_item_cd').val() != '' && $('.TXT_child_item_cd').val() != '') {
					// 	jMessage('W001');
					// }
				} else {
					mode = 'U';
				}
				$('.infor-created').html(res.header)
				$('.heading-btn-group').html(res.button)
				setItemComponentListDetail(res.data);
			}
		});
	} catch(e) {
        console.log('referBomDetail' + e.message)
    }
}
/**
 * set item for bom detail
 *
 * @author		:	Trieunb - 2017/12/20 - create
 * @params		:	null
 * @return		:	null
 */
function setItemComponentListDetail(data) {console.log(data);
	try{
		if (data != '') {
			// $('.TXT_parent_item_cd').val(data.parent_item_cd);
			// $('.TXT_child_item_cd').val(data.child_item_cd);
			$('.TXT_parent_item_cd').nextAll('#contain-name').find('.componentproduct_nm').text(data.parent_item_nm);
			$('.TXT_parent_item_cd').nextAll('#contain-name').find('.DSP_specification').text(data.specification_parent);
			$('.TXT_child_item_cd').nextAll('#contain-name').find('.componentproduct_nm').text(data.child_item_nm);
			$('.TXT_child_item_cd').nextAll('#contain-name').find('.DSP_specification').text(data.specification_child);
			$('.TXT_child_item_qty').val(data.child_item_qty.replace(/\.00$/,''));
			$('.CMB_unit option:first').prop('selected', true);
			if (data.unit_q_div != '') {
				$('.CMB_unit option[value='+data.unit_q_div+']').prop('selected', true);
			}
			$('.TXT_application_period_from').val(data.apply_st_date);
			$('.TXT_application_period_to').val(data.apply_ed_date);
			$('.TXT_remarks').val(data.remarks);
			setWidthTextRefer();
		} else {
			_clearErrors();
			// $('.TXT_parent_item_cd').val('');
			// $('.TXT_child_item_cd').val('');
			$('.TXT_child_item_qty').val('');
			$('.CMB_unit option:first').prop('selected', true);
			$('.TXT_application_period_from').val('');
			$('.TXT_application_period_to').val('');
			$('.TXT_remarks').val('');
			$('.infor-created').html('');
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
		}
	} catch(e) {
        console.log('setItemComponentListDetail' + e.message);
    }
}
/**
 * delete bom
 *
 * @author		:	Trieunb - 2017/12/20 - create
 * @params		:	null
 * @return		:	null
 */
function deleteBom() {
	try{
		var data = {};
		data = {
			parent_item_cd    : 	$('.TXT_parent_item_cd').val(),     
			child_item_cd     : 	$('.TXT_child_item_cd').val(),     
		};
		$.ajax({
	        type        :   'POST',
	        url         :   '/master/component-list-detail/delete',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		if (is_new == 'true') {
							mode	=	'U';
						} else {
							mode	=	'I';
						}
						var param = {
								'mode'		: mode,
								'from'		: from,
								'is_new'	: is_new
							};
	            		jMessage('I002', function(r){
		                	if(r){
		                		_postParamToLink(from, 'ComponentListDetail', '/master/component-list-detail', param);
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
        console.log('deleteBom' + e.message);
    }
}
function referBomDetailDelete() {
	try{
		$('.componentproduct_nm').text('');
		$('.TXT_parent_item_cd').val('');
		$('.TXT_child_item_cd').val('');
		$('.TXT_child_item_qty').val('');
		$('.CMB_unit option:first').prop('selected', true);
		$('.TXT_application_period_from').val('');
		$('.TXT_application_period_to').val('');
		$('.TXT_remarks').val('');
		$('input:first').focus();
		$('#DSP_cre_user_cd').text('');
		$('#DSP_cre_datetime').text('');
		$('#DSP_upd_user_cd').text('');
		$('#DSP_upd_datetime').text('');
		$('.infor-created').html('');
	} catch(e) {
        console.log('referBomDetailDelete' + e.message);
    }
}
function validateDelComponentProduct() {
	try {
		_clearErrors();
		var error 	= 0;
		if ($('.TXT_parent_item_cd ').val() == '') {
			$('.TXT_parent_item_cd').errorStyle(_MSG_E001);
			error 	++;
		}
		if ($('.TXT_child_item_cd  ').val() == '') {
			$('.TXT_child_item_cd ').errorStyle(_MSG_E001);
			error 	++;
		}
		if( error > 0 ) {
			return false;
		} else {
			return true;
		}
	} catch (e) {
		alert('validatePiNo: ' + e.message);
	}
}
/**
 *refer m item
 * 
 * @author : ANS806 - 2017/12/20 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referMItem(data, element, callback, isSearch) {
	try{
		if (isSearch == undefined) {
			isSearch = false;
		}
		$.ajax({
			type 		: 'GET',
			url 		: '/common/refer/refer-item',
			dataType	: 'json',
			data 		: data,
			success: function(res) {
				if (res.response) {
					//remove error
                	_removeErrorStyle(element.parents('.popup').find('.componentproduct_cd'));
					element.parents('.popup').find('.componentproduct_cd').val(res.data.item_cd);
					element.parents('.popup').find('.componentproduct_nm').text(res.data.item_nm);
					element.parents('.popup').find('.specification').text(res.data.specification);
					setWidthTextRefer();
				} else {
					if (!isSearch) {
						// element.parents('.popup').find('.componentproduct_cd').val('');
					}
					element.parents('.popup').find('.componentproduct_nm').text('');
					element.parents('.popup').find('.specification').text('');
				}
				//element.parents('.popup').find('.componentproduct_cd').focus();

				// check callback function
				if (typeof callback == 'function') {
					callback();
				}
			}
		});
		
	} catch(e) {
        console.log('referMItem' + e.message)
    }
}
/**
 * set width when refer item
 * 
 * @author : ANS804 - 2018/06/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setWidthTextRefer(){
	try {
		$('.componentproduct_nm').width('auto');
		var arr = [];
		$('.componentproduct_nm').each(function(index, element){
			var data = {
				index 			: index,
				element 		: $(this),
				currentWidth 	: $(this)[0].getBoundingClientRect().width
			}
			arr.push(data);
		});

		var arrWidthMax = arr.reduce(function(accumulator, currentValue, index, arr) {
			if (currentValue.currentWidth > accumulator.currentWidth) {
				return currentValue
			} else {
				return accumulator
			}
		});

		$('.componentproduct_nm').width(arrWidthMax.currentWidth);
	} catch (e) {
		console.log('setWidthTextRefer' + e.message)
	}
}