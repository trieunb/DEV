/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	DuyTP
 *
 * 更新日		: 	2018/01/09
 * 更新者		: 	DungNN - ANS810
 * 更新内容		: 	New Development
 *
 * @package		:	TEST
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

$(document).ready(function () {
	initEvents();
	// initCombobox();
});

// function initCombobox() {
// 	_getComboboxData('JP', 'manufacture_status_div');
// }

/**
 * init Events
 * @author  :   DuyTP - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {		
		//init event check all for checkbox
		checkAll('check-all');

		//sort clumn table
		$("#table-internal-order").tablesorter({
			headers: {
	            0: {
	                sorter: false
	            }
	        }
	    }); 

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
				alert('#btn-search: ' + e.message);
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
				alert('#btn-export: ' + e.message);
			}
		});		

		//init add new
		$(document).on('click', '#btn-add-new', function () {
			var param = {
				'mode'		: 'I',
				'from'		: 'InternalOrderSearch'
			};
			_postParamToLink('InternalOrderSearch', 'InternalOrderDetail', '/manufactureinstruction/internalorder-detail', param);
		});
		
		//screen moving
		$(document).on('dblclick', '#table-internal-order tbody tr', function() {
			if (!$(this).find('td').hasClass('dataTables_empty')) {
				var param = {
	 				'mode'				: 'U',
	 				'from'				: 'InternalOrderSearch',
	 				'internal_order_no'	: $(this).find('td.DSP_in_order_no').text().trim(),
	 			};
	 			_postParamToLink('InternalOrderSearch', 'InternalOrderDetail', '/manufactureinstruction/internalorder-detail', param);
			}			
		});
		
 		//btn issue
 		$(document).on('click', '#btn-issue', function(){
 			try {
 				if ($('#table-internal-order').find('.check-all').is(':checked')) {
					jMessage('C004', function(r) {
						if (r) {
							postPrint();
						}
					});
				} else {
					jMessage('E003');
				}   
 				
			} catch (e) {
				alert('#btn-print ' + e.message);
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
				alert('#page-size: ' + e.message);
			}
		});

	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}

/**
 * Search data internal order
 * 
 * @author : ANS810 - 2018/01/09 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try{
		var data = {
			TXT_in_order_no					: $.trim($('.TXT_in_order_no').val()),
			TXT_order_date_from   			: $.trim($('.TXT_order_date_from  ').val()),
			TXT_order_date_to  				: $.trim($('.TXT_order_date_to  ').val()),
			TXT_orderer_nm 					: $.trim($('.TXT_orderer_nm ').val()),
			TXT_product_nm 					: $.trim($('.TXT_product_nm ').val()),
			CMB_manufacture_status_div 		: $.trim($('.CMB_manufacture_status_div ').val()),
			page 							: _PAGE,
			page_size 						: _PAGE_SIZE
		};
		$.ajax({
			type 		: 'POST',
			url 		: '/manufactureinstruction/internalorder-search/search',
			dataType 	: 'json',
			data 		: data,
			loading 	: true,
			loading	: true,
			success : function(res) {
				// Do something here
				$('#div-internal-list').html(res.html);
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
        alert('search' + e.message)
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
			TXT_in_order_no					: $.trim($('.TXT_in_order_no').val()),
			TXT_order_date_from   			: $.trim($('.TXT_order_date_from  ').val()),
			TXT_order_date_to  				: $.trim($('.TXT_order_date_to  ').val()),
			TXT_orderer_nm 					: $.trim($('.TXT_orderer_nm ').val()),
			TXT_product_nm 					: $.trim($('.TXT_product_nm ').val()),
			CMB_manufacture_status_div 		: $.trim($('.CMB_manufacture_status_div ').val()),
			page 							: 1,
			page_size 						: 0
		};
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/internal-order-search',
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
		var _internal_list = getDataSavePrint();
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/internal-order-search/export-excel',
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
	            		jMessage('I004');
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
 * get data check print
 *
 * @author      :   ANS810 - 2018/01/08 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function getDataSavePrint() {
	try {
		var data_list = [];
		$('#table-internal-order tbody tr').each(function() {
			var isCheck = $(this).find('.check-all').is(':checked');
			if (isCheck) {
				var _data = {
					in_order_no			: 	$(this).find('.DSP_in_order_no').text().trim()
				};
				data_list.push(_data);
			}
		});
		return data_list;
    } catch (e) {
        alert('getDataSavePrint: ' + e.message);
    }
}

