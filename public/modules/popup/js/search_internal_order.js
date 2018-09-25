/**
 * Apel Project
 *
 * @copyright    :    ANS
 * @author        :   DuyTP - 2017/06/15
 *
 */

$(document).ready(function () {
	init();
	initEvents();
});

/**
 * init
 *
 * @author  :   DuyTP - 2017/01/03
 * @param
 * @return
 */
function init() {
	try {
		//sort clumn table
		$("#table-popup").tablesorter(); 
	} catch (e) {
		alert('init: ' + e.message);
	}
}
/**
 * init Events
 * @author  :   DuyTP - 2017/06/15
 * @param
 * @return
 */
function initEvents() {
	try {
		
		//init event search
		$(document).on('click', '#btn-search-popup', function () {
			search();
		});

		//click line table pi
 		$(document).on('dblclick', '#table-internal-order tbody tr', function(){
 			var internal_order_no	 = $(this).find('td.DSP_in_order_no').text().trim(); 			
 			parent.$('.popup-internalorder').find('.internalorder_cd').val(internal_order_no);
 			parent.$('.popup-internalorder').find('.internalorder_cd').trigger('change');
 			parent.$.colorbox.close();
 		});

 		$(document).on('click', '#paginate li button', function() {
 			_PAGE = $(this).data('page');
 			search();
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


	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}

/**
 * transfer
 * 
 * @author      :   DuyTP - 2017/06/15
 * @params      :   page
 * @return      :   null
 */
function transfer(element) {
	try {
		parent.$.colorbox.close();
	} catch (e) {
		alert('transfer' + e.message);
	}
}

/**
 * Search data internal order
 * 
 * @author : ANS810 - 2018/01/09 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try{
		var data = {
			TXT_in_order_no					: $.trim($('.TXT_in_order_no').val()),
			TXT_date_from 					: $.trim($('.TXT_date_from ').val()),
			TXT_date_to 					: $.trim($('.TXT_date_to ').val()),
			TXT_m_user_name 				: $.trim($('.TXT_m_user_name ').val()),
			TXT_product_name 				: $.trim($('.TXT_product_name ').val()),
			CMB_manufacture_status_div 		: $.trim($('.CMB_manufacture_status_div ').val()),
			page 							: _PAGE,
			page_size 						: _PAGE_SIZE
		};		
		$.ajax({
			type 		: 'POST',
			url 		: '/popup/search/internalorder/search',
			dataType 	: 'json',
			data 		: data,
			loading 	: true,
			success : function(res) {
				// Do something here
				$('#div-internal-list').html(res.html);
				$("#table-internal-order").tablesorter();
				_setTabIndex();
			}
		}).done(function(res){
			// _postSaveHtmlToSession();
		});
	} catch(e) {
        alert('search' + e.message)
    }
}