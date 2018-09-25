/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/02/28
 * 作成者		:	ANS804
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
	// initCombobox();
});
/**
 * initCombobox
 * @author  :   ANS804 - 2018/01/28 - create
 * @param 	:
 * @return 	:  	null
 * @access 	:  	public
 * @see 	:
 */
function initCombobox() {
	_getComboboxData('JP', 'inv_data_div');
}
/**
 * init Events
 * @author  :   ANS804 - 2018/02/13 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//init event check all for checkbox
		checkAll('check-all');
		// button search
		$(document).on('click', '#btn-search', function(e) {
			try {                
				if (_checkDateFromTo('date-order')) {
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
            try {
    			var param = {
    				'mode'		: 'I',
    				'from'		: 'InvoiceSearch'
    			};
    			_postParamToLink('InvoiceSearch', 'InvoiceDetail', '/invoice/invoice-detail', param);
            } catch (e) {
                console.log('#btn-add-new: ' + e.message);
            }
		});
		//click line table invoice
 		$(document).on('dblclick', '.table-invoice tbody tr', function(){
            try {
     			if (!$(this).find('td').hasClass('dataTables_empty')) {
    	 			var inv_no 	= $(this).find('.inv_no').text().trim();
    	 			var param 			= {
    	 				'mode'		: 'U',
    	 				'from'		: 'InvoiceSearch',
    	 				'inv_no' 	: inv_no,
    	 			};

    	 			_postParamToLink('InvoiceSearch', 'InvoiceDetail', '/invoice/invoice-detail', param);
     			}
            } catch (e) {
                console.log('.table-invoice tbody tr: ' + e.message);
            }
 		});
 		//change TXT_country_div
 		$(document).on('change', '.TXT_country_div', function() {
 			try {
				var country_div = $(this).val();
	 			_referCountry(country_div, '', $(this), '', true);
            } catch (e) {
                console.log('.TXT_country_div: ' + e.message);
            }
 		});
        //btn-invoice
        $(document).on('click', '#btn-invoice', function(){
            try {
                if ($('#table-invoice').find('.check-all').is(':checked')) {
                    jMessage('C004', function(r) {
                        if (r) {
                            var url = '/export/invoice-search/invoice-export';
                            invoiceExport("Invoice_","t_inv_print", url);
                        }
                    });
                } else {
                    jMessage('E003');
                }
            } catch (e) {
                console.log('#btn-invoice: ' + e.message);
            }
        });
        //btn-delivery_note
        $(document).on('click', '#btn-delivery-note', function(){
            try {
                if ($('#table-invoice').find('.check-all').is(':checked')) {
                    jMessage('C004', function(r) {
                        if (r) {
                            var url = '/export/invoice-search/delivery-note-export';
                            invoiceExport("納品書_","t_inv_delivery_print", url);
                        }
                    });
                } else {
                    jMessage('E003');
                }
            } catch (e) {
                console.log('#btn-delivery_note: ' + e.message);
            }
        });
		// button export
		$(document).on('click', '#btn-export', function() {
			try {
				if (_checkDateFromTo('date-order')) {
					jMessage('C007', function(r) {
						if (r) {
							outputExcel();
						}
					});
				}
			} catch (e) {
				console.log('#btn-export: ' + e.message);
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
            try {
     			_PAGE = $(this).data('page');
     			search();
            } catch (e) {
                console.log('#paginate li button: ' + e.message);
            }
 		});
	} catch (e) {
		console.log('initEvents: ' + e.message);
	}
}
/**
 * search component order list
 * 
 * @author : ANS804 - 2018/02/28 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try {
		var data = getDataSearch(_PAGE_SIZE);
		$.ajax({
			type 		: 'POST',
			url 		: '/invoice/invoice-search',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
			success: function(res) {
				if (res.response) {
					$('#invoice-list').html(res.html);
					//sort clumn table
					$("#table-invoice").tablesorter({
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
         console.log('search: ' + e.message);
    }
}
/**
 * get Data Search
 * 
 * @author : ANS804 - 2018/01/28 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataSearch(action) {
	try {
		var data = {
	            'inv_date_from'   	: $('.TXT_inv_date_from').val().trim(),
	            'inv_date_to'   	: $('.TXT_inv_date_to').val().trim(),
	            'inv_no'   			: $('.TXT_inv_no').val().trim(),
	            'rcv_no'   			: $('.TXT_rcv_no').val().trim(),
	            'pi_no'   			: $('.TXT_pi_no').val().trim(),
	            'client_nm' 		: $('.TXT_client_nm').val().trim(),
	            'country_div' 		: $('.TXT_country_div').val().trim(),
	            'inv_data_div'		: $('.inv_data_div').val().trim(),
	            page 				: _PAGE,
				page_size 			: typeof action == 'undefined' ? _PAGE_SIZE : action,
                is_jp               : $('#check-box-different-jp').is(':checked') ? 1 : 0
	        };
        return data;
	} catch (e) {
        console.log('getDataSearch: ' + e.message);
    }
}
/**
 * output Excel
 * 
 * @author : ANS804 - 2018/01/30 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function outputExcel() {
    try {
        var data = getDataSearch(0);
        $.ajax({
            type        :   'POST',
            url         :   '/export/invoice-search',
            dataType    :   'json',
            data        :   data,
			loading		: 	true,
            success: function(res) {
                if (res.response) {
                    jMessage('I008', function(r) {
                        if(r) {
                            location.href = res.filename;
                        }
                    });
                } else {
                    jMessage('W001');
                }
            },
        });
    }  catch(e) {
        console.log('outputExcel:' + e.message)
    }
}
/**
 * get data is choosen
 * 
 * @author : ANS804 - 2018/02/22 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataInvoiceExport() {
    try {
        var data_list = [];
        $('#table-invoice tbody tr').each(function() {
            var isCheck = $(this).find('.check-all').is(':checked');
            if (isCheck) {
                var inv_no  =  $(this).find('.inv_no').text().trim();
                var data = {
                    inv_no  : inv_no,
                };                           
                data_list.push(data);
            }
        });
        return data_list;
    } catch (e) {
        console.log('getDataInvoiceExport: ' + e.message);
    }
}
/**
 * invoice export
 * 
 * @author : ANS804 - 2018/03/02 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function invoiceExport(file_excel,t_insert,url) {
    try {
        var inv_no = getDataInvoiceExport();
        $.ajax({
            type        :   'POST',
            url         :   url,
            dataType    :   'json',
			loading		: 	true,
            data        :   {
                        inv_no      : inv_no,
                        t_insert    : t_insert,
                        file_excel  : file_excel
            },
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
        console.log('invoiceExport: ' + e.message)
    }
}