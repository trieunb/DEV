/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	DuyTP
 *
 * 更新日		: 	2018/02/05
 * 更新者		: 	DungNN - ANS810
 * 更新内容		: 	New Development
 *
 * @package		:	TEST
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

$(document).ready(function () {
	// initCombobox();
	initEvents();
});
// function initCombobox() {
// 	var name = 'JP';
	
// 	_getComboboxData(name, 'manufacture_kind_div');
// 	_getComboboxData(name, 'exists_div');
// }

/**
 * init Events
 * @author  :   DuyTP - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//sort clumn table
		$("#table-internal-order").tablesorter({
			headers: { 
	            0: { 
	                sorter: false 
	            }
	        } 
	    }); 
		//init event check all for checkbox
		checkAll('check-all');		

		// button search
		$(document).on('click', '#btn-search', function() {
			try {
				if (_checkDateFromTo('date-estimate')) {
					if(!_isBackScreen){
						_PAGE = 1;
					}					
					search();
				}
			} catch (e) {
				console.log('#btn-search: ' + e.message);
			}
		});	

		// button export
		$(document).on('click', '#btn-export', function() {
			try {
				jMessage('C007', function(r) {
					if (r) {
						outputExcel();
					}
				});
			} catch (e) {
				console.log('#btn-export: ' + e.message);
			}
		});		

		$(document).on('click', '#paginate li button', function() {
 			_PAGE = $(this).data('page');
 			search();
 		});

 		//change paging 
		$(document).on('change', '#page-size', function() {
			try {
				if ($('#table-result').find('td.w-popup-nodata').length == 0){		
					_PAGE_SIZE = ($('.nav-pagination').children('.pagi-fillter').length > 0) ? $('#page-size').val() : 10;
					_PAGE 	   = 1
					search();
				}
			} catch (e) {
				console.log('#page-size: ' + e.message);
			}
		});

		// btn-issue-instruction
 		$(document).on('click', '#btn-issue-instruction', function(){
 			try {
 				if ($('#table-internal-order').find('.check-all').is(':checked')) {					
					if(raiseErrorE281()){
						if(raiseErrorE282()){
							if(raiseErrorE001()){
								jMessage('C004', function(r) {
									if (r) {
										postPrint();
									}
								});
							}
						}
					}
				} else {
					jMessage('E003');
				}
 				
			} catch (e) {
				console.log('#btn-issue-instruction ' + e.message);
			}
 		});
 		//change 指示数量 
		$(document).on('change', '.TXT_manufacture_qty', function() {
			try {
				var manufacture_qty 	= ($(this).val().replace(/,/g,'') == '') ? '0' : $(this).val().replace(/,/g,'');
				var hdn_remaining_qty 	= $(this).closest('tr').find('.DSP_hdn_remaining_qty').val().replace(/,/g,'');
				var remaining_qty 		= (hdn_remaining_qty == '') ? '0' : hdn_remaining_qty;
				$(this).closest('tr').find('.DSP_remaining_qty').text(addCommas(parseInt(remaining_qty) - parseInt(manufacture_qty)));
			} catch (e) {
				console.log('#change 指示数量: ' + e.message);
			}
		});
	} catch (e) {
		console.log('initEvents: ' + e.message);
	}
}

/**
 * Search data manufacture report
 * 
 * @author : ANS810 - 2018/02/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try{
		var data = {
			TXT_internal_purchase_order_date_from   : $.trim($('.TXT_internal_purchase_order_date_from').val()),
			TXT_internal_purchase_order_date_to   	: $.trim($('.TXT_internal_purchase_order_date_to').val()),
			TXT_hope_delivery_date  				: $.trim($('.TXT_hope_delivery_date').val()),
			TXT_in_order_no 						: $.trim($('.TXT_in_order_no').val()),
			TXT_product_cd 							: $.trim($('.TXT_product_cd').val()),
			TXT_product_nm 							: $.trim($('.TXT_product_nm').val()),
			CMB_manufacture_kind_div 				: $.trim($('.CMB_manufacture_kind_div').val()),
			page 									: _PAGE,
			page_size 								: _PAGE_SIZE
		};
		$.ajax({
			type 		: 'POST',
			url 		: '/manufactureinstruction/manufacturing-instruction-report/search',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
			success : function(res) {
				// Do something here
				$('#div-manufactor-report-list').html(res.html);
				$("#table-internal-order").tablesorter({
					headers: { 
			            0: { 
			                sorter: false 
			            }
			        }
				});
				_setTabIndex();
			}
		}).done(function(res){
			_postSaveHtmlToSession();
		});
	} catch(e) {
        console.log('search' + e.message)
    }
}

/**
 * output excel
 * 
 * @author : ANS810 - 2018/01/17 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function outputExcel() {
	try {
		var data = {
			TXT_internal_purchase_order_date_from   : $.trim($('.TXT_internal_purchase_order_date_from').val()),
			TXT_internal_purchase_order_date_to   	: $.trim($('.TXT_internal_purchase_order_date_to').val()),
			TXT_hope_delivery_date  				: $.trim($('.TXT_hope_delivery_date').val()),
			TXT_in_order_no 						: $.trim($('.TXT_in_order_no').val()),
			TXT_product_cd 							: $.trim($('.TXT_product_cd').val()),
			TXT_product_nm 							: $.trim($('.TXT_product_nm').val()),
			CMB_manufacture_kind_div 				: $.trim($('.CMB_manufacture_kind_div').val()),
			page 									: 1,
			page_size 								: 0
		};
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/manufacturing-instruction-report/export-excel',
	        dataType    :   'json',
	        data        :   data,
			loading		:	true,
	        success: function(res) {
	            if (res.response) {
	            	jMessage('I008');
	            	location.href = res.filename;
	            }
	            else{
	            	jMessage('W001');
	            }
	        },
	    });
	}  catch(e) {
        console.log('outputExcel' + e.message)
    }
}

/**
 * Update database and print list
 * 
 * @author : ANS810 - 2018/02/06 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postPrint() {
	try {
		var data 	=	getDataUpdateDB();
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/manufacturing-instruction-report/export-excel-list',
	        dataType    :   'json',
	        data        :   data,
			loading		: 	true,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd,function(ok){
            			if(ok){            				
            				focusErrorE281(res.error_product);
            			}
            		});
	            	} else {
	            		//download excel
	            		location.href = res.fileName;
	            		jMessage('I004',function(r){
	            			if(r){
	            				search();
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
        console.log('postPrint' + e.message);
    }
}

/**
 * get data from view
 *
 * @author      :   ANS810 - 2018/02/06 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function getDataUpdateDB() {
	try {
		var update_list   = [];
		//
		$('#table-internal-order tbody tr').each(function() {
			var isCheck = $(this).find('.check-all').is(':checked');
			if (isCheck) {
				var _data = {
					in_order_no					: 	$(this).find('.DSP_in_order_no').text().trim(),
					in_order_detail_no			: 	$(this).find('.DSP_in_order_detail_no').text().trim(),
					product_cd					: 	$(this).find('.DSP_product_cd').text().trim(),
					manufacture_qty				: 	$(this).find('.TXT_manufacture_qty').val().replace(/,/g,''),
					remaining_qty				: 	$(this).find('.DSP_hdn_remaining_qty').val().trim().replace(/,/g,''),
					remarks						: 	$(this).find('.TXT_remarks').val()
				};
				update_list.push(_data);				
			}
		});
		var data = {
			update_list			: 	update_list
		};
		return data;
    } catch (e) {
        console.log('getDataUpdateDB: ' + e.message);
    }
}

/**
 * raiseErrorE281
 *
 * @author      :   ANS810 - 2018/02/08 - create
 * @param       : 	null
 * @return      :   boolean
 * @access      :   public
 * @see         :   init
 */
function raiseErrorE281() {
	try {
		var flag = true;		
		_removeErrorStyle($('#table-internal-order tbody tr').find('.TXT_manufacture_qty'));
		$('#table-internal-order tbody tr').each(function() {
			var isCheck 		= 	$(this).find('.check-all').is(':checked');
			var remaining_qty 	= 	$(this).find('.DSP_hdn_remaining_qty').val().replace(/,/g,'');
			var manufacture_qty = 	$(this).find('.TXT_manufacture_qty').val().replace(/,/g,'');
			var compare 		=	parseInt(manufacture_qty) > parseInt(remaining_qty);
						
			if(isCheck && compare){
				$(this).find('.TXT_manufacture_qty').errorStyle(_text['E281']);
				flag = false;
			}			
		});
		return flag;
    } catch (e) {
        console.log('raiseErrorE281: ' + e.message);
    }
}

/**
 * raiseErrorE282
 *
 * @author      :   ANS810 - 2018/02/12- create
 * @param       : 	null
 * @return      :   boolean
 * @access      :   public
 * @see         :   init
 */
function raiseErrorE282() {
	try {
		var flag = true;		
		_removeErrorStyle($('#table-internal-order tbody tr').find('.TXT_manufacture_qty'));
		$('#table-internal-order tbody tr').each(function() {
			var isCheck 		= 	$(this).find('.check-all').is(':checked');
			var last_serial_no 	= 	$(this).find('.DSP_last_serial_no').text().replace(/,/g,'');
			var manufacture_qty = 	$(this).find('.TXT_manufacture_qty').val().replace(/,/g,'');
			var total 			=	parseInt(manufacture_qty) + parseInt(last_serial_no);
			
			if(isCheck && total > 9999999){
				$(this).find('.TXT_manufacture_qty').errorStyle(_text['E282']);
				flag = false;
			}
		});
		return flag;
    } catch (e) {
        console.log('raiseErrorE282: ' + e.message);
    }
}

/**
 * focusErrorE281
 *
 * @author      :   ANS810 - 2018/03/06 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function focusErrorE281(arr_compare) {
	try {
		$('#table-internal-order tbody tr').each(function() {
			var in_order_no 		= $(this).find('.DSP_in_order_no').text().trim();
			var in_order_detail_no 	= $(this).find('.DSP_in_order_detail_no').text().trim();
			for (var i = 0; i < arr_compare.length; i++) {
			    if(in_order_no == arr_compare[i].in_order_no && in_order_detail_no == arr_compare[i].in_order_detail_no){
					$(this).find('.TXT_manufacture_qty').errorStyle(_text['E281']);
				}
			};
		});
    } catch (e) {
        console.log('focusErrorE281: ' + e.message);
    }
}

/**
 * raiseErrorE001
 *
 * @author      :   ANS810 - 2018/03/08 - create
 * @param       : 	null
 * @return      :   boolean
 * @access      :   public
 * @see         :   init
 */
function raiseErrorE001() {
	try {
		var flag = true;
		_removeErrorStyle($('#table-internal-order tbody tr').find('.TXT_manufacture_qty'));
		$('#table-internal-order tbody tr').each(function() {			
			var isCheck 		= 	$(this).find('.check-all').is(':checked');
			var manufacture_qty = 	$(this).find('.TXT_manufacture_qty').val().replace(/,/g,'');
			if(isCheck && (manufacture_qty == '' || manufacture_qty == '0')){
				$(this).find('.TXT_manufacture_qty').errorStyle(_text['E001']);
				flag = false;
			}
		});
		return flag;
    } catch (e) {
        console.log('raiseErrorE001: ' + e.message);
    }
}
