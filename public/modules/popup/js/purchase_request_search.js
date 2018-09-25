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
 * @package		:	Popup Search Purchase Request
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

 $(document).ready(function () {
 	initCombobox();
	initEvents();
});
function initCombobox() {
	//_getComboboxData('JP', 'buy_status_div');
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
		//click line table table-master-ml10
 		$(document).on('dblclick', '#table-puschase-request tbody tr', function() {
 			if (!$(this).find('td').hasClass('dataTables_empty')) {
	 			var purchaserequest_cd = $(this).find('td.purchaserequest_cd').text().trim();

	 			parent.$('.popup-purchaserequest').find('.purchaserequest_cd').val(purchaserequest_cd);
	 			
	 			parent.$('.popup-purchaserequest').find('.purchaserequest_cd').trigger('change');
	 			parent.$.colorbox.close();
 			}
 		});
 		// button search
		$(document).on('click', '#btn-search-popup', function() {
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
	} catch (e) {
		alert('initEvents: ' + e.message);
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
			url 		: '/popup/search/purchaserequest',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
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
		});
	} catch (e) {
         alert('search' + e.message);
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
				'buy_date_from'   	: $('.TXT_buy_date_from').val().trim(),
	            'buy_date_to'   	: $('.TXT_buy_date_to').val().trim(),
	            'buy_no_from'   	: $('.TXT_buy_no_from').val().trim(),
	            'buy_no_to'   		: $('.TXT_buy_no_to').val().trim(),
	            'supplier_nm'   	: $('.TXT_supplier_nm').val().trim(),
	            'parts_nm'   		: $('.TXT_parts_nm').val().trim(),
	            'buy_status_div'   	: $('.CMB_buy_status_div').val().trim(),
	            page 				: _PAGE,
				page_size 			: _PAGE_SIZE
	        };
        return data;
	} catch (e) {
         alert('getDataSearch' + e.message);
    }
}