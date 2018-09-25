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
});


/**
 * init Events
 * @author  :   DuyTP - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//click line table componentproduct
 		$(document).on('dblclick', '#table-component-product tbody tr.tr-table', function(){
 			var componentproduct_id = $(this).find('td.componentproduct_cd').text().trim();
 			var componentproduct_nm = $(this).find('td.componentproduct_nm').text().trim();
 			
 			parent.$('.popup-componentproduct').find('.componentproduct_cd').val(componentproduct_id);
 			parent.$('.popup-componentproduct').find('.componentproduct_nm').text(componentproduct_nm);

 			parent.$('.popup-componentproduct').find('.componentproduct_cd').trigger('change');

 			parent.$.colorbox.close();
 		});
		// button search
		$(document).on('click', '#btn-search-popup', function() {
			try {
				_PAGE = 1;
				search();
			} catch (e) {
				alert('#btn-search ' + e.message);
			}
		});	
 		//change paging 
		$(document).on('change', '#page-size', function() {
			try {
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
			url 		: '/popup/component-product-detail/search',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
			success: function(res) {
				if (res.response) {
					$('#component-product-list').html(res.html);
					//sort clumn table
					$("#table-component-product").tablesorter(); 
					_setTabIndex();
				} else {
					$('#table-component-product').html('');
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
	            item_nm   			: $('.TXT_item_nm').val(),
	            specification   	: $('.TXT_specification').val(),
	            item_cd   			: $('.TXT_item_cd').val(),
	            product   			: $('.product').is(':checked') ? 1 : '',
	            component   		: $('.component').is(':checked') ? 1 : '',
	            page 				: _PAGE,
				page_size 			: page_size
	        };
        return data;
	} catch (e) {
         alert('getDataSearch' + e.message);
    }
}