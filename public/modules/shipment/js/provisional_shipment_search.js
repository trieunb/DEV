/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	DuyTP
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	INVOICE
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

$(document).ready(function () {
	if (!sessionStorage.getItem('detail')) {
        sessionStorage.clear();
    }
	initEvents();
});


/**
 * init Events
 * @author  :   DuyTP - 2017/06/09 - create
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
		
		//init add new
		$(document).on('click', '#btn-add-new', function () {
			var param = {
				'mode'		: 'I',
				'from'		: 'ProvisionalShipmentSearch'
			};
			_postParamToLink('ProvisionalShipmentSearch', 'ProvisionalShipmentDetail', '/shipment/provisional-shipment-detail', param);
		});
 		
 		//btn print
 		$(document).on('click', '#btn-export', function(){
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

 		//click line table pi
 		$(document).on('dblclick', '.table-shipment tbody tr.tr-class', function(){
 			var param = {
 				'mode'			: 'U',
 				'from'			: 'ProvisionalShipmentSearch',
 				'shipment_no'	: $(this).find('td.DSP_fwd_no').text().trim(),
 			}; 			
 			_postParamToLink('ProvisionalShipmentSearch', 'ProvisionalShipmentDetail', '/shipment/provisional-shipment-detail', param);
 		});

 		//paging
 		$(document).on('click', '#paginate li button', function() {
 			_PAGE = $(this).data('page');
 			search();
 		});

 		// button search
		$(document).on('click', '#btn-search', function() {
			try {
				if(!_isBackScreen){
					_PAGE = 1;
				}
				search();
			} catch (e) {
				alert('#btn-search: ' + e.message);
			}
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

		//change 国コード
		$(document).on('change', '.TXT_country_cd', function() {
			try {
				_referCountry($(this).val(), '', $(this), '', true);			   
			} catch (e) {
				console.log('change #.TXT_country_div: ' + e.message);
			}
		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}

/**
 * Search data provisional shipment detail
 * 
 * @author : ANS831 - 2018/01/26 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try{
		if (_checkDateFromTo('date-from-to')) {
			var data = {
				TXT_cre_date_from					: $.trim($('.TXT_cre_date_from').val()),
				TXT_cre_date_to    					: $.trim($('.TXT_cre_date_to').val()),
				TXT_fwd_no  						: $.trim($('.TXT_fwd_no').val()),
				TXT_client_nm 						: $.trim($('.TXT_client_nm').val()),
				TXT_country_cd  					: $.trim($('.TXT_country_cd').val()),
				page 								: _PAGE,
				page_size 							: _PAGE_SIZE,
				is_jp               				: $('#check-box-different-jp').is(':checked') ? 1 : 0
			};
			$.ajax({
				type : 'POST',
				url : '/shipment/provisional-shipment-search/search',
				dataType : 'json',
				data : data,
				loading	: true,
				success : function(res) {
					$('#div-shipment-list').html(res.html);
					$("#table-shipment").tablesorter({
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
		}
	} catch(e) {
        alert('search' + e.message)
    }
}


/**
 * output excel
 * 
 * @author : ANS810 - 2018/02/02 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function outputExcel() {
	try {
		if (_checkDateFromTo('date-from-to')) {
			var data = {
				TXT_cre_date_from				: $.trim($('.TXT_cre_date_from').val()),
				TXT_cre_date_to    				: $.trim($('.TXT_cre_date_to').val()),
				TXT_fwd_no  					: $.trim($('.TXT_fwd_no').val()),
				TXT_client_nm 					: $.trim($('.TXT_client_nm').val()),
				TXT_country_cd  				: $.trim($('.TXT_country_cd').val()),
				page 							: 1,
				page_size 						: 0,
				is_jp               			: $('#check-box-different-jp').is(':checked') ? 1 : 0
			};
			$.ajax({
		        type        :   'POST',
		        url         :   '/export/provisional-shipment-search',
		        dataType    :   'json',
		        data        :   data,
				loading		: true,
		        success: function(res) {
		            if (res.response) {
		            	jMessage('I008', function(r){
		            		if(r){
								location.href = res.filename;
		            		}
		            	});
		            }
		            else{
		            	jMessage('W001');
		            }
		        },
		    });
		}
	}  catch(e) {
        console.log('outputExcel' + e.message)
    }
}

/**
 * print provisional shipment search list
 * 
 * @author : ANS831 - 2018/02/06 - create
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
	        url         :   '/export/provisional-shipment-search/export-excel',
	        dataType    :   'json',
	        data        :   {fwd_list : _shipment_list},
			loading		: 	true,
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
	} catch (e) {
         alert('postPrintList' + e.message);
    }
}
/**
 * get data list provisional shipment
 * 
 * @author : ANS831 - 2018/02/06 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataList() {
	try {
		var data_list = [];
		$('#table-shipment tbody tr').each(function() {
			var isCheck = $(this).find('.check-all').is(':checked');
			if (isCheck) {
				var data = {
					fwd_no     : 	$(this).find('.DSP_fwd_no').text().trim(),					
				};
				data_list.push(data);
			}
		});
		return data_list;
	} catch (e) {
         alert('getDataList' + e.message);
    }
}
/**
 * get data list provisional shipment
 * 
 * @author : ANS831 - 2018/02/06 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function removeDuplicates(arr) {
    var obj = {};
    var ret_arr = [];
    for (var i = 0; i < arr.length; i++) {
        obj[arr[i]] = true;
    }
    for (var key in obj) {
        ret_arr.push(key);
    }
    return ret_arr;
}
/**
 * get data fwd list
 * 
 * @author : ANS806 - 2017/12/14 - create
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
