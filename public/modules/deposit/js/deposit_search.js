/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/01/31
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
    // initCombobox();
	initEvents();
});
/**
 * init data combobox
 * @author  :   ANS342 - 2017/06/09 - create
 * @param
 * @return
 */
// function initCombobox() {
// 	var name = 'JP';
// 	//get combobox
// 	_getComboboxData(name, 'target_div');
// }
/**
 * init Events
 * @author  :   ANS804 - 2018/01/31 - create
 * @param 	:
 * @return 	:  	null
 * @access 	:  	public
 * @see 	:
 */
function initEvents() {
	try {
		// button search
		$(document).on('click', '#btn-search', function(e) {
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
				'from'		: 'DepositSearch'
			};
			_postParamToLink('DepositSearch', 'DepositDetail', '/deposit/deposit-detail', param);
		});
		//click line table deposit
 		$(document).on('dblclick', '.table-deposit tbody tr', function(){
 			if (!$(this).find('td').hasClass('dataTables_empty')) {              
	 			var mode 	= 'U';
	 			var deposit_no 	= $(this).find('.DSP_deposit_no').text().trim();
	 			var param 		= {
	 				'mode'		: mode,
	 				'from'		: 'DepositSearch',
	 				'deposit_no': deposit_no,
	 			};
	 			_postParamToLink('DepositSearch', 'DepositDetail', '/deposit/deposit-detail', param);
 			}
 		});
		// button export
		$(document).on('click', '#btn-export', function() {
			try {
				if (_checkDateFromTo('date-from-to')) {
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
 		//change 国コード
		$(document).on('change', '.TXT_country_div', function() {
			try {
				_referCountry($(this).val(), '', $(this), '', true);			   
			} catch (e) {
				console.log('change #.TXT_country_div: ' + e.message);
			}
		});
	} catch (e) {
		console.log('initEvents: ' + e.message);
	}
}
/**
 * search deposit detail
 * 
 * @author : ANS804 - 2018/01/31 - create
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
			url 		: '/deposit/search',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
			success: function(res) {
				if (res.response) {
					$('#deposit-list').html(res.html);
					//sort clumn table
					$("#table-deposit").tablesorter();
					$( document ).trigger( "stickyTable" );
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
 * get Data Search
 * 
 * @author : ANS804 - 2018/01/31 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataSearch(action) {
	try {
		var data = {
	            'deposit_date_from'   	: $('.TXT_deposit_date_from').val(),
	            'deposit_date_to'   	: $('.TXT_deposit_date_to').val(),
	            'deposit_no'   			: $.mbTrim($('.TXT_deposit_no').val()),
	            'rcv_no'   				: $.mbTrim($('.TXT_rcv_no').val()),
	            'cust_nm'   			: $.mbTrim($('.TXT_client_nm').val()),
	            'country_cd'   			: $.mbTrim($('.TXT_country_div').val()),
	            'split_deposit_div'   	: $.mbTrim($('.CMB_split_deposit_div').val()),
	            page 					: _PAGE,
				page_size 				: typeof action == 'undefined' ? _PAGE_SIZE : action,
				is_jp               	: $('#check-box-different-jp').is(':checked') ? 1 : 0
	        };
        return data;
	} catch (e) {
         console.log('getDataSearch: ' + e.message);
    }
}
/**
 * output Excel
 * 
 * @author : ANS804 - 2018/01/31 - create
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
            url         :   '/export/deposit-search',
            dataType    :   'json',
            data        :   data,
			loading		:	true,
            success: function(res) {
                if (res.response) {
                    jMessage('I008', function(r) {
                        if(r) {
                            location.href = res.fileName;
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