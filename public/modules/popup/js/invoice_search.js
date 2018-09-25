/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/03/01
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
	initEvents();
	initCombobox();
});
/**
 * initCombobox
 * @author  :   ANS804 - 2018/03/01 - create
 * @param 	:
 * @return 	:  	null
 * @access 	:  	public
 * @see 	:
 */
function initCombobox() {
	//_getComboboxData('JP', 'inv_data_div');
}
/**
 * init Events
 * @author  :   ANS804 - 2018/03/01 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//init event check all for checkbox
		checkAll('check-all');
		// button search
		$(document).on('click', '#btn-search-popup', function(e) {
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
 		//change TXT_country_div
 		$(document).on('change', '.TXT_country_div', function() {
 			try {
				var country_div = $(this).val();
	 			_referCountry(country_div, '', $(this), true);
            } catch (e) {
                console.log('.TXT_country_div: ' + e.message);
            }
 		});
 		// refer data from table-invoice
        $(document).on('dblclick', '#table-invoice tbody tr.tr-table', function(){
            try {
                var inv_no = $(this).find('.inv_no').text().trim();

                parent.$('.popup-invoice').find('.invoice_cd').val(inv_no);
                parent.$('.popup-invoice').find('.invoice_cd').trigger('change');
                
                parent.$.colorbox.close();
            } catch (e) {
                console.log('refer data from table-invoice: ' + e.message);
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
 * @author : ANS804 - 2018/03/01 - create
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
			url 		: '/popup/search/invoice',
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
		});
	} catch (e) {
         console.log('search: ' + e.message);
    }
}
/**
 * get Data Search
 * 
 * @author : ANS804 - 2018/03/01 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataSearch(action) {
	try {
		var data = {
	            'inv_date_from'   	: $('.TXT_inv_date_from').val(),
	            'inv_date_to'   	: $('.TXT_inv_date_to').val(),
	            'inv_no'   			: $('.TXT_inv_no').val(),
	            'rcv_no'   			: $('.TXT_rcv_no').val(),
	            'pi_no'   			: $('.TXT_pi_no').val(),
	            'client_nm' 		: $('.TXT_client_nm').val(),
	            'country_div' 		: $('.TXT_country_div').val(),
	            'inv_data_div'		: $('.inv_data_div').val(),
	            page 				: _PAGE,
				page_size 			: typeof action == 'undefined' ? _PAGE_SIZE : action,
				is_jp               : $('#check-box-different-jp').is(':checked') ? 1 : 0
	        };
        return data;
	} catch (e) {
        console.log('getDataSearch: ' + e.message);
    }
}
