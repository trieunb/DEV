/**
 * Souei Project
 *
 * @copyright    :    ANS
 * @author        :   DuyTP - 2017/06/15
 *
 */
//Global variables
var _PAGE = 1;
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
		$("#table-popup-user").tablesorter();
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
			try {
				_PAGE = 1;
				search();
			} catch (e) {
				console.log('#btn-search ' + e.message);
			}
		});
		//click line table pi
 		$(document).on('dblclick', '#table-popup-user tbody tr.tr-table', function(){
 			var user_id = $(this).find('td.user_cd').text().trim();
 			var user_nm = $(this).find('td.user_nm_j').text().trim();
 			parent.$('.popup-user').find('.user_cd').val(user_id);
 			parent.$('.popup-user').find('.user_nm').text(user_nm);
 			parent.$('.popup-user').find('.user_cd').trigger('change');
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
					_PAGE = 1;
					search();
				}
			} catch (e) {
				alert('#page-size' + e.message);
			}
		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * Search data User
 * 
 * @author : ANS796 - 2017/11/09 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try{
		var data = {
			user_cd		: $('#TXT_user_cd').val().trim(),
			user_nm_j	: $('#TXT_user_nm_j').val().trim(),
			user_nm_e	: $('#TXT_user_nm_e').val().trim(),
			page 		: _PAGE,
			page_size 	: ($('.nav-pagination').children('.pagi-fillter').length > 0) ? $('#page-size').val() : 10
		};
		$.ajax({
			type 		: 'POST',
			url 		: '/popup/search/user-search',
			dataType 	: 'json',
			data 		: data,
			loading 	: true,
			success : function(res) {
				$('#user_list').html(res.html);
				//sort clumn table
				$("#table-popup-user").tablesorter();
				_setTabIndex();
			}
		});
	} catch(e) {
        console.log('search' + e.message)
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