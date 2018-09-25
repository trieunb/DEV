/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/02/23
 * 作成者		:	Trieunb
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	Purchase Request
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

$(document).ready(function () {
	initCombobox();
	initEvents();
});

function initCombobox(){
	var name = 'JP';
	//_getComboboxData(name, 'buy_status_div');
}

/**
 * init Events
 * @author  :   Trieunb - 2018/02/23 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//sort clumn table
		$("#table-puschase-request").tablesorter({
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
				if (_checkDateFromTo('date-from-to')) {
					if(!_isBackScreen){
						_PAGE = 1;
					}
					search();
				}
			} catch (e) {
				console.log('#btn-search ' + e.message);
			}
		});	
		//init add new
		$(document).on('click', '#btn-add-new', function () {
			var param = {
				'mode'		: 'I',
				'from'		: 'PurchaseRequestSearch'
			};
			_postParamToLink('PurchaseRequestSearch', 'PurchaseRequestDetail', '/purchase-request/purchase-request-detail', param);
		});

		//screen moving
		$(document).on('dblclick', '#table-puschase-request tbody tr', function() {
			if (!$(this).find('td').hasClass('dataTables_empty')) {
				var param 	= {
	 				'mode'			: 'U',
	 				'from'			: 'PurchaseRequestSearch',
	 				'buy_no'		: $(this).find('.DSP_buy_no').text().trim()
	 			};
				_postParamToLink('PurchaseRequestSearch', 'PurchaseRequestDetail', '/purchase-request/purchase-request-detail', param);
			}
		});
 		//btn print
 		$(document).on('click', '#btn-issue', function(){
 			if ($('#table-puschase-request').find('.check-all').is(':checked')) {
	 			jMessage('C004',  function(r) {
					if (r) {
						purchaseRequestExport();
					}
				});
			} else {
				jMessage('E003');
			}   
 		});
 		//btn-export
 		$(document).on('click', '#btn-export', function(){
			if (_checkDateFromTo('date-from-to')) {
				jMessage('C007',  function(r) {
					if (r) {
						purchaseRequestOutput();
					}
				});
			}
 		});

 		//btn-approve-estimate
 		$(document).on('click', '#btn-approve', function(){
 			try {
 				if ($('#table-puschase-request').find('.check-all').is(':checked')) {
	 				jMessage('C005', function(r) {
						if (r) {
							approvePurchaseList();
						}
					});
				} else {
					jMessage('E003');
				}   
			} catch (e) {
				console.log('#btn-approve ' + e.message);
			}
 		});
 		//change paging 
		$(document).on('change', '#page-size', function() {
			try {
				_PAGE_SIZE = ($('.nav-pagination').children('.pagi-fillter').length > 0) ? $('#page-size').val() : 10
				_PAGE = 1;
				search();
			} catch (e) {
				console.log('#page-size' + e.message);
			}
		});
		//click paging
		$(document).on('click', '#paginate li button', function() {
 			_PAGE = $(this).data('page');
 			search();
 		});
	} catch (e) {
		console.log('initEvents: ' + e.message);
	}
}
/**
 * search purchase request list detail
 * 
 * @author : ANS806 - 2018/02/21 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try {
		var data = getDataSearch();
		$.ajax({
			type 		: 'POST',
			url 		: '/purchase-request/purchase-request-search/search',
			dataType 	: 'json',
			data 		: data,
			loading 	: true,
			success: function(res) {
				if (res.response) {
					$('#purchase-request-list').html(res.html);
					//sort clumn table
					$("#table-puschase-request").tablesorter({
						headers: { 
				            0: { 
				                sorter: false 
				            }
				        } 
				    }); 
					_setTabIndex();
				}
			}
		}).done(function(res){
			_postSaveHtmlToSession();
		});
	} catch (e) {
        console.log('search' + e.message);
    }
}
/**
 * get data for purchase request list search condition
 * 
 * @author : ANS806 - 2018/02/21 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataSearch() {
	try {
		var data = {
				'buy_date_from'   	: $.mbTrim($('.TXT_buy_date_from').val()),
	            'buy_date_to'   	: $.mbTrim($('.TXT_buy_date_to').val()),
	            'buy_no_from'   	: $.mbTrim($('.TXT_buy_no_from').val()),
	            'buy_no_to'   		: $.mbTrim($('.TXT_buy_no_to').val()),
	            'supplier_nm'   	: $.mbTrim($('.TXT_supplier_nm').val()),
	            'parts_nm'   		: $.mbTrim($('.TXT_parts_nm').val()),
	            'buy_status_div'   	: $.mbTrim($('.CMB_buy_status_div').val()),
	            page 				: _PAGE,
				page_size 			: _PAGE_SIZE
	        };
        return data;
	} catch (e) {
        console.log('getDataSearch' + e.message);
    }
}
/**
 * approve Purchase List detail list
 * 
 * @author : ANS806 - 2018/02/22 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function approvePurchaseList() {
	try {
		var _buy_list = getDataApprove();
		$.ajax({
	        type        :   'POST',
	        url         :   '/purchase-request/purchase-request-search/approve',
	        dataType    :   'json',
	        data        :   {buy_list : _buy_list},
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		jMessage('I005', function(r) {
	            			if (r) {
	            				_PAGE = 1;
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
        console.log('approvePurchaseList' + e.message);
    }
}
/**
 * get data approve buy list
 * 
 * @author : ANS806 - 2017/12/14 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataApprove() {
	try {
		var data_list = [];
		$('#table-puschase-request tbody tr').each(function() {
			var isCheck = $(this).find('.check-all').is(':checked');
			if (isCheck) {
				var buy_status_div 	=	$(this).find('.DSP_buy_status_div').text().trim();
				var data = {
					buy_no 			: 	$(this).find('.DSP_buy_no').text().trim(),
					buy_status_div 	: 	buy_status_div,
				};
				data_list.push(data);
			}
		});
		return data_list;
	} catch (e) {
        console.log('getDataApprove' + e.message);
    }
}
/**
 * Stock Input Output Export
 * 
 * @author : ANS806 - 2018/01/16 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function purchaseRequestOutput() {
	try {
		var data = getDataOutput();
		$.ajax({
			type 		: 'POST',
			url 		: '/export/purchase-request-search/output',
			dataType 	: 'json',
			data 		: data,
			loading     : true,
			success: function(res) {
				if (res.response) {
					location.href = res.filename;
					jMessage('I008');
				} else {
	            	jMessage('W001');
	            }
			}
		});
	}  catch(e) {
        console.log('purchaseRequestOutput' + e.message)
    }
}
/**
 * get data for purchase request list search condition
 * 
 * @author : ANS806 - 2018/02/21 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataOutput() {
	try {
		var data = {
				'buy_date_from'   	: $.mbTrim($('.TXT_buy_date_from').val()),
	            'buy_date_to'   	: $.mbTrim($('.TXT_buy_date_to').val()),
	            'buy_no_from'   	: $.mbTrim($('.TXT_buy_no_from').val()),
	            'buy_no_to'   		: $.mbTrim($('.TXT_buy_no_to').val()),
	            'supplier_nm'   	: $.mbTrim($('.TXT_supplier_nm').val()),
	            'parts_nm'   		: $.mbTrim($('.TXT_parts_nm').val()),
	            'buy_status_div'   	: $.mbTrim($('.CMB_buy_status_div').val()),
	        };
        return data;
	} catch (e) {
        console.log('getDataSearch' + e.message);
    }
}
/**
 * purchase request export
 * 
 * @author : ANS806 - 2018/02/23 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function purchaseRequestExport() {
	try {
		var _buy_list = getDataApprove();
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/purchase-request-export',
	        dataType    :   'json',
	        data        :   {buy_list : _buy_list},
	        loading     :   true,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
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
	}  catch(e) {
        console.log('purchaseRequestExport' + e.message)
    }
}
/**
 * get data approve buy list
 * 
 * @author : ANS806 - 2017/12/14 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataExport() {
	try {
		var data_list = [];
		$('#table-puschase-request tbody tr').each(function() {
			var isCheck = $(this).find('.check-all').is(':checked');
			if (isCheck) {
				var buy_status_div 	=	$.mbTrim($(this).find('.DSP_buy_status_div').text());
				var data = {
					buy_no 			: 	$.mbTrim($(this).find('.DSP_buy_no').text()),
					buy_status_div 	: 	buy_status_div,
				};
				data_list.push(data);
			}
		});
		return data_list;
	} catch (e) {
        console.log('getDataApprove' + e.message);
    }
}
// function removeDuplicates(arr) {
//     var obj = {};
//     var ret_arr = [];
//     for (var i = 0; i < arr.length; i++) {
//         obj[arr[i]] = true;
//     }
//     for (var key in obj) {
//         ret_arr.push(key);
//     }
//     return ret_arr;
// }