/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/01/20
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
// 初期化
$(document).ready(function () {
    if (!sessionStorage.getItem('detail')) {
        sessionStorage.clear();
    }
	initEvents();
	// initCombobox();
});
/**
 * initCombobox
 * @author  :   ANS804 - 2018/01/20 - create
 * @param 	:
 * @return 	:  	null
 * @access 	:  	public
 * @see 	:
 */
// function initCombobox() {
// 	_getComboboxData('JP', 'rcv_status_div');
// }
/**
 * init Events
 * @author  :   ANS804 - 2018/01/20 - create
 * @param 	:
 * @return 	:  	null
 * @access 	:  	public
 * @see 	:
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
			var param = {
				'mode'		: 'I',
				'from'		: 'AcceptSearch'
			};
			_postParamToLink('AcceptSearch', 'AcceptDetail', '/accept/accept-detail', param);
		});
		//click line table accept
 		$(document).on('dblclick', '.table-accept tbody tr', function(){
 			if (!$(this).find('td').hasClass('dataTables_empty')) {
	 			var approve = $(this).find('.DSP_rcv_status_div_nm').text().trim();
	 			var mode 	= 'I';

	 			if (approve == '申請中') {
	 				mode	= 'R';
	 			} else if (approve == '承認済') {
	 				mode	= 'A';
	 			} else if (approve == '失注') {
	 				mode	= 'L';
	 			}

	 			var accept_no 	= $(this).find('.DSP_rcv_no').text().trim();
	 			var param 		= {
	 				'mode'		: mode,
	 				'from'		: 'AcceptSearch',
	 				'accept_no'	: accept_no,
	 			};

	 			_postParamToLink('AcceptSearch', 'AcceptDetail', '/accept/accept-detail', param);
 			}
 		});
 		//btn approve
 		$(document).on('click', '#btn-approve', function(){
 			try {
                if ($('#table-accept').find('.check-all').is(':checked')) {
     				jMessage('C005', function(r) {
    					if (r) {
    						approveRcvList();
    					}
    				});
                } else {
                    jMessage('E003');
                }
			} catch (e) {
				console.log('#btn-approve ' + e.message);
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
 			_PAGE = $(this).data('page');
 			search();
 		});
 		//change TXT_country_div
 		$(document).on('change', '.TXT_country_cd', function() {
 			try {
				var country_div = $(this).val();
	 			_referCountry(country_div, '', $(this), '', true);
            } catch (e) {
                console.log('.TXT_country_cd: ' + e.message);
            }
 		});
	} catch (e) {
		console.log('initEvents: ' + e.message);
	}
}
/**
 * search rcv detail
 * 
 * @author : ANS804 - 2018/01/20 - create
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
			url 		: '/accept/search',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
			success: function(res) {
				if (res.response) {
					$('#rcv-list').html(res.html);
					//sort clumn table
					$("#table-accept").tablesorter({
						headers: { 
				            0: { 
				                sorter: false 
				            }
				        } 
				    });
					setTabIndex();
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
	            'rcv_date_from'   	: $('.TXT_rcv_date_from').val(),
	            'rcv_date_to'   	: $('.TXT_rcv_date_to').val(),
	            'rcv_no'   			: $('.TXT_rcv_no').val(),
	            'cust_nm'   		: $('.TXT_cust_nm').val(),
	            'rcv_status_div'   	: $('.CMB_rcv_status_div').val(),
	            page 				: _PAGE,
				page_size 			: typeof action == 'undefined' ? _PAGE_SIZE : action,
				'country_cd' 		: $('.TXT_country_cd').val().trim(),
				is_jp               : $('#check-box-different-jp').is(':checked') ? 1 : 0,
				isShipment 			: 0	//default value : check 未出荷数
	        };
        return data;
	} catch (e) {
         console.log('getDataSearch: ' + e.message);
    }
}
/**
 * approve rcv detail list
 * 
 * @author : ANS804 - 2018/01/20 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function approveRcvList() {
	try {
		var _rcv_list = getDataAcceptList();

		$.ajax({
	        type        :   'POST',
	        url         :   '/accept/accept-search/approve',
	        dataType    :   'json',
	        data        :   {rcv_list : _rcv_list},
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
        console.log('approveRcvList: ' + e.message);
    }
}
/**
 * get data approve accept list
 * 
 * @author : ANS804 - 2018/01/20 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataAcceptList() {
	try {
		var data_list = [];

		$('#table-accept tbody tr').each(function() {
			var isCheck = $(this).find('.check-all').is(':checked');
			if (isCheck) {
				var rcv_status_div 	=	$(this).find('.DSP_rcv_status_div').text().trim();
				var data = {
					rcv_no 			: 	$(this).find('.DSP_rcv_no').text().trim(),
					rcv_status_div 	: 	rcv_status_div,
				};
				data_list.push(data);
			}
		});
		return data_list;
	} catch (e) {
         console.log('getDataAcceptList: ' + e.message);
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
            url         :   '/export/accept-search',
            dataType    :   'json',
            data        :   data,
			loading		:	true,
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
 * setTabIndex
 * 
 * @author : ANS804 - 2018/01/31 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setTabIndex() {
    try {
        var index = 0;
        $(":input").each(function (i) {
            $(this).attr('tabindex', i + 1);
            if ($(this).hasClass('hasDatepicker') || $(this).hasClass('month')) {
                $(this).next('.ui-datepicker-trigger').attr('tabindex', i + 1);
            }
            index = i+1;
        });

        $('input[disabled], input[readonly], textarea[disabled], textarea[readonly], select[disabled], button[disabled]').attr('tabindex', '-1');

        if ($(':input.error-item').length > 0) {
            $(':input.error-item:first').focus();
        } else {
            $(':input:visible:not([disabled]):not([readonly]):first').focus();
        }
    }  catch(e) {
        console.log('setTabIndex:' + e.message)
    }
}
