/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/02/13
 * 作成者		:	ANS804
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	COMPONENT ORDER
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
    				'from'		: 'ComponentOrderSearch'
    			};
    			_postParamToLink('ComponentOrderSearch', 'ComponentOrderDetail', '/component-order/order-detail', param);
            } catch (e) {
                console.log('#btn-add-new: ' + e.message);
            }
		});
		//click line table component order
 		$(document).on('dblclick', '.table-component-order tbody tr', function(){
            try {
     			if (!$(this).find('td').hasClass('dataTables_empty')) {
    	 			var mode 	= 'U';
    	 			var parts_order_no 	= $(this).find('.parts_order_no').text().trim();
    	 			var param 			= {
    	 				'mode'				: mode,
    	 				'from'				: 'ComponentOrderSearch',
    	 				'parts_order_no'	: parts_order_no,
    	 			};

    	 			_postParamToLink('ComponentOrderSearch', 'ComponentOrderDetail', '/component-order/order-detail', param);
     			}
            } catch (e) {
                console.log('.table-component-order tbody tr: ' + e.message);
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
        //btn-issue
        $(document).on('click', '#btn-issue', function(){
            try {
                if ($('#table-component-order').find('.check-all').is(':checked')) {
                    jMessage('C004', function(r) {
                        if (r) {
                            componentOrderExport();
                        }
                    });
                } else {
                    jMessage('E003');
                }
            } catch (e) {
                console.log('#btn-issue: ' + e.message);
            }
        });
        //btn-approve
        $(document).on('click', '#btn-approve', function(){
            if ($('#table-component-order').find('.check-all').is(':checked')) {                   
                jMessage('C005', function(r) {
                    if(r) {
                        postApproved();
                    }
                });
            } else {
                jMessage('E003');
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
 * @author : ANS804 - 2018/02/13 - create
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
			url 		: '/component-order/order-search',
			dataType 	: 'json',
			data 		: data,
            loading     : true,
			success: function(res) {
				if (res.response) {
					$('#component-order-list').html(res.html);
					//sort clumn table
					$("#table-component-order").tablesorter({
						headers: { 
				            0: { 
				                sorter: false 
				            }
				        } 
				    });
                    $( document ).trigger( "stickyTable" );
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
 * @author : ANS804 - 2018/01/20 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataSearch(action) {
	try {
		var data = {
	            'parts_order_date_from'   	: $('.TXT_parts_order_date_from').val(),
	            'parts_order_date_to'   	: $('.TXT_parts_order_date_to').val(),
	            'parts_order_no_from'   	: $('.TXT_parts_order_no_from').val(),
	            'parts_order_no_to'   		: $('.TXT_parts_order_no_to').val(),
	            'supplier_nm'   			: $.mbTrim($('.TXT_supplier_nm').val()),
	            'part_nm' 		  			: $.mbTrim($('.TXT_part_nm').val()),
                'buy_no'                    : $.mbTrim($('.TXT_buy_no').val()),
                'in_order_no'               : $.mbTrim($('.TXT_internalorder_cd').val()),
                'manufacture_no'            : $.mbTrim($('.TXT_manufacture_no').val()),
	            'buy_status_div'		    : $('.CMB_status').val(),
	            page 						: _PAGE,
				page_size 					: typeof action == 'undefined' ? _PAGE_SIZE : action
	        };
        return data;
	} catch (e) {
         console.log('getDataSearch: ' + e.message);
    }
}
/**
 * output Excel
 * 
 * @author : ANS804 - 2018/02/13 - create
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
            url         :   '/export/component-order-search-output',
            dataType    :   'json',
            data        :   data,
            loading     :   true,
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
function getDataExport() {
    try {
        var data_list = [];
        $('#table-component-order tbody tr').each(function() {
            var isCheck = $(this).find('.check-all').is(':checked');
            if (isCheck) {
                var parts_order_no  =  $(this).find('.parts_order_no').text().trim();
                var data = {
                    parts_order_no  : parts_order_no,
                };                           
                data_list.push(data);
            }
        });
        return data_list;
    } catch (e) {
        console.log('getDataExport: ' + e.message);
    }
}
/**
 * order export
 * 
 * @author : ANS804 - 2018/02/22 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function componentOrderExport() {
    try {
        var parts_order_no = getDataExport();
        
        $.ajax({
            type        :   'POST',
            url         :   '/export/component-order-search-export',
            dataType    :   'json',
            loading     :   true,
            data        :   {
                    parts_order_no              : parts_order_no,
                    report_number_parts_order   : $('#report_number_parts_order').val().trim()
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
        console.log('componentOrderExport: ' + e.message)
    }
}
/**
 * get data approved
 *
 * @author      :   ANS804 - 2018/06/01 - create
 * @param       :   null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function getDataApproved() {
    try {
        var data_approved   = [];

        $('#table-component-order tbody tr').each(function() {
            var isCheck = $(this).find('.check-all').is(':checked');
            if (isCheck) {
                var data = {
                    parts_order_no  :   $(this).find('.parts_order_no').text().trim(),
                };
                data_approved.push(data);
            }
        });

        var data = {
            data_approved       :   data_approved
        };
            
        return data;
    } catch (e) {
        console.log('getDataApproved: ' + e.message);
    }
}
/**
 * Update database for approved order-search
 * 
 * @author : ANS804 - 2018/06/01 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postApproved() {
    try {
        var data    =   getDataApproved();
        
        $.ajax({
            type        :   'POST',
            url         :   '/component-order/approved',
            dataType    :   'json',
            data        :   data,
            success: function(res) {
                if (res.response) {
                    if (res.error_cd != '') {
                        jMessage(res.error_cd);
                    } else {
                        jMessage('I005', function(r){
                            if(r) {
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
        console.log('postApproved: ' + e.message);
    }
}