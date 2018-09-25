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
	if(parent.screenID == 'purchase-request-detail'){
		$('.TXT_purchaser_order_cd').val(parent.$('.suppliers_cd').val());
		_getClientName($('.TXT_purchaser_order_cd').val().trim(), $('.TXT_purchaser_order_cd'), function(){
			$('#TXT_part_nm').focus();
		}, true);
	}
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
		$("#table-component").tablesorter(); 

		// button search
		$(document).on('click', '#btn-search-popup', function() {
			try {
				_PAGE = 1;
				search();
			} catch (e) {
				alert('#btn-search-popup: ' + e.message);
			}
		});
		
		//click line table component
 		$(document).on('dblclick', '#table-component tbody tr.tr-table', function(){
 			var component_id = $(this).find('td.parts_cd').text().trim();
 			parent.$('.popup-component').find('.component_cd').val(component_id);
 			parent.$('.popup-component').find('.component_cd').trigger('change');
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

		$(document).on('change', '.TXT_purchaser_order_cd', function() {
			try {
				_getClientName($(this).val().trim(), $(this), '', true);
			} catch (e) {
				alert('#page-size: ' + e.message);
			}
		});

	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}


/**
 * Search data Component
 * 
 * @author : ANS817 - 2017/12/15 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try{
		var data = {
			item_nm			: $('#TXT_part_nm').val().trim(),
			specification	: $('#TXT_specification').val().trim(),
			supplier_cd		: $('.TXT_purchaser_order_cd').val().trim(),
			parts_cd		: $('.TXT_parts_cd').val().trim(),
			page 			: _PAGE,
			page_size 		: _PAGE_SIZE
		};
		$.ajax({
			type 		: 'POST',
			url 		: '/popup/search/component-search',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
			success : function(res) {
				$('#div-component-list').html(res.html);

				$("#table-component").tablesorter();

				$( document ).trigger( "stickyTable" );
				_setTabIndex();
			}
		});
	} catch(e) {
        alert('search' + e.message)
    }
}