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
		//sort clumn table
		$("#table-product").tablesorter(); 

		// button search
		$(document).on('click', '#btn-search-popup', function() {
			try {
				_PAGE = 1;
				search();
			} catch (e) {
				alert('#btn-search-popup: ' + e.message);
			}
		});

		//click line table table-master-ml10
 		$(document).on('dblclick', '#table-product tbody tr.tr-table', function(){
 			var product_id = $(this).find('td.product_cd').text().trim();
 			// var product_nm = $(this).find('td.product_nm').text().trim();

 			parent.$('.popup-product').find('.product_cd').val(product_id);
 			parent.$('.popup-product').find('.product_cd').trigger('change');
 			// parent.$('.popup-product').find('.product_nm').text(product_nm);
 			
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
					_PAGE      = 1;
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
 * Search data Product
 * 
 * @author : ANS817 - 2017/12/21 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try{
		var data = {
			item_nm			: $('#TXT_product_nm').val().trim(),
			specification	: $('#TXT_specification').val().trim(),
			product_cd		: $('#TXT_product_cd').val().trim(),
			page 			: _PAGE,
			page_size 		: _PAGE_SIZE
		};
		$.ajax({
			type 	: 'POST',
			url 	: '/popup/search/product-search',
			dataType: 'json',
			data 	: data,
			loading : true,
			success : function(res) {
				$('#div-product-list').html(res.html);

				$("#table-product").tablesorter();

				$( document ).trigger( "stickyTable" );
				_setTabIndex();
			}
		});
	} catch(e) {
        alert('search' + e.message)
    }
}