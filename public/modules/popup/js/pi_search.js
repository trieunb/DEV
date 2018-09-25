/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/08/20
 * 作成者		:	Trieunb - ANS806 - trieunb@ans-asia.com
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	PI
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */
//Global variables
var _PAGE = 1;
$(document).ready(function () {
	initEvents();
	initCombobox();
});
function initCombobox() {
	//_getComboboxData('JP', 'pi_status_div');
}
/**
 * init Events
 * @author  :   Trieunb - 2017/08/20 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//init event check all for checkbox
		checkAll('check-all');
		// button search
		$(document).on('click', '#btn-search-popup', function() {
			try {
				if (_checkDateFromTo('date-estimate') && _checkDateFromTo('date-order')) {
					_PAGE = 1;
					search();
				}
			} catch (e) {
				alert('#btn-search ' + e.message);
			}
		});	
 		//click line table pi
 		$(document).on('dblclick', '#table-pi tbody tr', function(){
 			var pi_id = $(this).find('td.pi_cd').text().trim();
 			parent.$('.popup-pi').find('.pi_cd').val(pi_id);
 			parent.$('.popup-pi').find('.pi_cd').trigger('change');
 			parent.$.colorbox.close();
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
 * search pi detail
 * 
 * @author : ANS806 - 2017/12/14 - create
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
			url 		: '/popup/pi/search',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
			success: function(res) {
				if (res.response) {
					$('#pi-list').html(res.html);
					//sort clumn table
					$("#table-pi").tablesorter({
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
 * @author : ANS806 - 2017/12/14 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataSearch() {
	try {
		var page_size = ($('.nav-pagination').children('.pagi-fillter').length > 0) ? $('#page-size').val() : 10
		var data = {
	            'pi_date_from'   	: $('.TXT_pi_date_from').val(),
	            'pi_date_to'   		: $('.TXT_pi_date_to').val(),
	            'rcv_date_from'   	: $('.TXT_rcv_date_from').val(),
	            'rcv_date_to'   	: $('.TXT_rcv_date_to').val(),
	            'pi_no'   			: $('.TXT_pi_no').val(),
	            'rcv_no'   			: $('.TXT_rcv_no').val(),
	            'cust_nm'   		: $('.TXT_cust_nm').val(),
	            'pi_status_div'   	: $('.CMB_pi_status_div').val(),
	            page 				: _PAGE,
				page_size 			: page_size,
				'country_cd' 		: $('.TXT_country_cd').val().trim(),
				is_jp               : $('#check-box-different-jp').is(':checked') ? 1 : 0
	        };
        return data;
	} catch (e) {
         alert('getDataSearch' + e.message);
    }
}