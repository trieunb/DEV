/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/02/01
 * 作成者		:	KhaDV
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

	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}