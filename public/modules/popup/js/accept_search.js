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
 * @author  :   DuyTP - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//sort clumn table
		$("#table-accept").tablesorter({
			headers: { 
	            0: { 
	                sorter: false 
	            }
	        } 
	    }); 

		checkAll('check-all');

		// button search
		$(document).on('click', '#btn-search-popup', function() {
			try {
				if (_checkDateFromTo('date-order')) {
					if(!_isBackScreen){
						_PAGE = 1;
					}
					search();
				}
			} catch (e) {
				alert('#btn-search ' + e.message);
			}
		});

 		// refer data from table-accept
        $(document).on('dblclick', '#table-accept tbody tr.tr-table', function(){
            try {
                var accept_cd = $(this).find('.accept_cd').text().trim();

                parent.$('.popup-accept').find('.accept_cd').val(accept_cd);
                parent.$('.popup-accept').find('.accept_cd').trigger('change');

                parent.$.colorbox.close();
            } catch (e) {
                console.log('refer data from table-accept: ' + e.message);
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
		alert('initEvents: ' + e.message);
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
		var data = getDataSearch();
		$.ajax({
			type 		: 'POST',
			url 		: '/popup/search/accept',
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
					_setTabIndex();
				}
			}
		});
	} catch (e) {
         alert('search' + e.message);
    }
}
/**
 * get data for pi search condition
 * 
 * @author : ANS804 - 2018/01/20 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataSearch() {
	try {
		var data = {
	            'rcv_date_from'   	: $('.TXT_rcv_date_from').val(),
	            'rcv_date_to'   	: $('.TXT_rcv_date_to').val(),
	            'rcv_no'   			: $('.TXT_rcv_no').val(),
	            'cust_nm'   		: $('.TXT_cust_nm').val(),
	            'rcv_status_div'   	: $('.CMB_rcv_status_div').val(),
	            page 				: _PAGE,
				page_size 			: _PAGE_SIZE,
				'country_cd' 		: $('.TXT_country_cd').val().trim(),
				is_jp               : $('#check-box-different-jp').is(':checked') ? 1 : 0,
				isShipment 			: (parent.isCheckAllShipment == undefined || parent.isCheckAllShipment == null) ? 0 : parent.isCheckAllShipment
	        };
	        console.log(data);
        return data;
	} catch (e) {
         alert('getDataSearch' + e.message);
    }
}