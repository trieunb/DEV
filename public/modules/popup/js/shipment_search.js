/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/03/19
 * 作成者		:	Trieunb
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	SHIPMENT
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

$(document).ready(function () {
	initCombobox();
	initEvents();
});
function initCombobox(){
	var name = 'JP';
	//_getComboboxData(name, 'fwd_status_div');
}
/**
 * init Events
 * @author  :   Trieunb - 2017/03/19 - create
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
		//click line table pi
 		$(document).on('dblclick', '#table-shipment tbody tr', function(){
 			var shipment_id = $(this).find('td.shipment_cd').text().trim();
 			parent.$('.popup-shipment').find('.shipment_cd').val(shipment_id);

 			parent.$('.popup-shipment').find('.shipment_cd').trigger('change');
 			parent.$.colorbox.close();
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
 		// change TXT_country_cd
 		$(document).on('change', '.TXT_country_cd', function() {
 			var country_div 	=	$(this).val();
 			_referCountry(country_div, '', $(this), '', true);
 		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * search shipment search list detail
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
			url 		: '/popup/search/shipment',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
			success: function(res) {
				if (res.response) {
					$('#shipment-list').html(res.html);
					//sort clumn table
					$("#table-shipment").tablesorter({
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
 * get data for shipment search list search condition
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
				'cre_date_from'   	: $('.TXT_cre_date_from').val().trim(),
	            'cre_date_to'   	: $('.TXT_cre_date_to').val().trim(),
	            'fwd_no'   			: $('.TXT_fwd_no').val().trim(),
	            'client_nm'   		: $('.TXT_client_nm').val().trim(),
	            'country_cd'   		: $('.TXT_country_cd').val().trim(),
	            'fwd_status_div'   	: $('.CMB_status').val().trim(),
	            page 				: _PAGE,
				page_size 			: _PAGE_SIZE,
				is_jp               : $('#check-box-different-jp').is(':checked') ? 1 : 0
	        };
        return data;
	} catch (e) {
         alert('getDataSearch' + e.message);
    }
}