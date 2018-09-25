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
 * @package		:	SHIPMENT
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
	//_getComboboxData(name, 'fwd_status_div');
}

/**
 * init Events
 * @author  :   Trieunb - 2018/03/19 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//sort clumn table
		$("#table-shipment").tablesorter({
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
				alert('#btn-search ' + e.message);
			}
		});	
		//init add new
		$(document).on('click', '#btn-add-new', function () {
			var param = {
				'mode'		: 'I',
				'from'		: 'ShipmentSearch'
			};
			_postParamToLink('ShipmentSearch', 'ShipmentDetail', '/shipment/shipment-detail', param);
		});
 		
 		//btn-approve
 		$(document).on('click', '#btn-approve-estimate', function(){
 			try {
 				if ($('#table-shipment').find('.check-all').is(':checked')) {
	 				jMessage('C005', function(r) {
						if (r) {
							approveShipmenttList();
						}
					});
				} else {
					jMessage('E003');
				}   
			} catch (e) {
				alert('#btn-approve ' + e.message);
			}
 		});
 		//btn print
 		$(document).on('click', '#btn-export', function(){
 			if (_checkDateFromTo('date-from-to')) {
				jMessage('C007',  function(r) {
					if (r) {
						shipmentOutput();
					}
				});
			}
 		});
 		//screen moving
		$(document).on('dblclick', '#table-shipment tbody tr', function() {
			if (!$(this).find('td').hasClass('dataTables_empty')) {
				var param 	= {
	 				'mode'			: 'U',
	 				'from'			: 'ShipmentSearch',
	 				'shipment_no'	: $(this).find('.DSP_fwd_no').text().trim(),
	 			};
	 			console.log(param);
				_postParamToLink('ShipmentSearch', 'ShipmentDetail', '/shipment/shipment-detail', param);
			}
		});

 		//change paging 
		$(document).on('change', '#page-size', function() {
			try {
				_PAGE_SIZE = ($('.nav-pagination').children('.pagi-fillter').length > 0) ? $('#page-size').val() : 10
				_PAGE = 1;
				search();
			} catch (e) {
				alert('#page-size' + e.message);
			}
		});
		//click paging
		$(document).on('click', '#paginate li button', function() {
 			_PAGE = $(this).data('page');
 			search();
 		});
 		// change TXT_country_cd
 		$(document).on('change', '.TXT_country_cd', function() {
 			var country_div 	=	$(this).val();
 			_referCountry(country_div, '', $(this), '', true);
 		});
 		//btn print report
 		$(document).on('click', '#btn-issue', function() {
 			try {
 				if ($('#table-shipment').find('.check-all').is(':checked')) {
					jMessage('C004', function(r) {
						if (r) {
							postPrintList();
						}
					});
				} else {
					jMessage('E003');
				}   
 				
			} catch (e) {
				alert('#btn-print ' + e.message);
			}
 		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * search shipment search list detail
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
			url 		: '/shipment/shipment-search/search',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
			success: function(res) {
				if (res.response) {
					$('#shipment-list').html(res.html);
					//sort clumn table
					$("#table-shipment").tablesorter({
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
         alert('search' + e.message);
    }
}
/**
 * get data for shipment search list search condition
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
				'cre_date_from'   	: $.mbTrim($('.TXT_cre_date_from').val()),
	            'cre_date_to'   	: $.mbTrim($('.TXT_cre_date_to').val()),
	            'fwd_no'   			: $.mbTrim($('.TXT_fwd_no').val()),
	            'client_nm'   		: $.mbTrim($('.TXT_client_nm').val()),
	            'country_cd'   		: $.mbTrim($('.TXT_country_cd').val()),
	            'fwd_status_div'   	: $.mbTrim($('.CMB_status').val()),
	            page 				: _PAGE,
				page_size 			: _PAGE_SIZE,
				is_jp               : $('#check-box-different-jp').is(':checked') ? 1 : 0
	        };
        return data;
	} catch (e) {
         alert('getDataSearch' + e.message);
    }
}
/**
 * approve shipment List detail list
 * 
 * @author : ANS806 - 2018/02/22 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function approveShipmenttList() {
	try {
		var _fwd_list = getDataApprove();
		$.ajax({
	        type        :   'POST',
	        url         :   '/shipment/shipment-search/approve',
	        dataType    :   'json',
	        data        :   {fwd_list : _fwd_list},
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
         alert('approveShipmenttList' + e.message);
    }
}
/**
 * get data approve fwd list
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
		$('#table-shipment tbody tr').each(function() {
			var isCheck = $(this).find('.check-all').is(':checked');
			if (isCheck) {
				var data = {
					fwd_no 			: 	$(this).find('.DSP_fwd_no').text().trim(),
				};
				data_list.push(data);
			}
		});
		return data_list;
	} catch (e) {
         alert('getDataApprove' + e.message);
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
function shipmentOutput() {
	try {
		var data = getDataOutput();
		$.ajax({
			type 		: 'POST',
			url 		: '/export/shipment/output',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
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
	            'cre_date_from'   	: $.mbTrim($('.TXT_cre_date_from').val()),
	            'cre_date_to'   	: $.mbTrim($('.TXT_cre_date_to').val()),
	            'fwd_no'   			: $.mbTrim($('.TXT_fwd_no').val()),
	            'client_nm'   		: $.mbTrim($('.TXT_client_nm').val()),
	            'country_cd'   		: $.mbTrim($('.TXT_country_cd').val()),
	            'fwd_status_div'   	: $.mbTrim($('.CMB_status').val()),
	            is_jp               : $('#check-box-different-jp').is(':checked') ? 1 : 0,
	        };
        return data;
	} catch (e) {
         alert('getDataSearch' + e.message);
    }
}
/**
 * print provisional shipment search list
 * 
 * @author : ANS831 - 2018/03/19 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postPrintList() {
	try { 
	 	var _shipment_list = getDataFwd();
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/shipment-search/export-excel',
	        dataType    :   'json',
	        data        :   {fwd_list : _shipment_list},
			loading		: 	true,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
		            	jMessage('I004',function(){
		            		location.href = res.fileName;
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
         alert('postPrintList' + e.message);
    }
}
/**
 * get data fwd list
 * 
 * @author : ANS831 - 2018/03/20 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataFwd() {
	try {
		var data_list = [];
		$('#table-shipment tbody tr').each(function() {
			var isCheck = $(this).find('.check-all').is(':checked');
			if (isCheck) {
				var data = {
					fwd_no 			: 	$(this).find('.DSP_fwd_no').text().trim(),
				};
				data_list.push(data);
			}
		});
		return data_list;
	} catch (e) {
         alert('getDataApprove' + e.message);
    }
}