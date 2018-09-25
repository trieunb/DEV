/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/06/05
 * 作成者		:	DaoNX
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
	initEvents();
});

/**
 * initEvents 
 * 
 * @author : ANS804 - 2018/06/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function initEvents() {
	try {
		//sort clumn table
		$("#table-component-order").tablesorter({
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

 		// refer data from table-component-order
        $(document).on('dblclick', '#table-component-order tbody tr.tr-table', function(){
            try {
                var parts_order_no = $(this).find('.parts_order_no').text().trim();

                parent.$('.popup-order').find('.order_cd').val(parts_order_no);
                parent.$('.popup-order').find('.order_cd').trigger('change');

                parent.$.colorbox.close();
            } catch (e) {
                console.log('refer data from table-component-order: ' + e.message);
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
		alert('initEvents: ' + e.message);
	}
}
/**
 * search 
 * 
 * @author : ANS804 - 2018/06/05 - create
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
			url 		: '/popup/search/order',
			dataType 	: 'json',
			data 		: data,
            loading     : true,
			success: function(res) {
				if (res.response) {
					$('#component-order-list').html(res.html);
					//sort column table
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
		});
	} catch (e) {
         alert('search' + e.message);
    }
}
/**
 * get data for pi search condition
 * 
 * @author : ANS804 - 2018/06/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataSearch() {
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