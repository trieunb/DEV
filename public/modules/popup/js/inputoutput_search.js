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
 	initCombobox();
	initEvents();
});
function initCombobox() {
	var name = 'JP';
	//_getComboboxData(name, 'in_out_div');
	//_getComboboxData(name, 'in_out_data_div');
}
/**
 * init Events
 * @author  :   DuyTP - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//sort clumn table
		$("#table-stock-manager").tablesorter(); 

		//click line table table-master-ml10
 		$(document).on('dblclick', '#table-stock-manager tbody tr', function(){

 			var stockmanage_id = $(this).find('td.inputoutput_cd').text().trim();
 			var stockmanager_nm = $(this).find('td.inputoutput_nm').text().trim();

 			parent.$('.popup-inputoutput').find('.inputoutput_cd').val(stockmanage_id);
 			parent.$('.popup-inputoutput').find('.inputoutput_nm').text(stockmanager_nm);

 			parent.$('.popup-inputoutput').find('.inputoutput_cd ').trigger('change');
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
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}


/**
 * search stock manage input output
 * 
 * @author : ANS806 - 2018/01/04 - create
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
			url 		: '/popup/search/input-output',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
			success: function(res) {
				if (res.response) {
					$('#input-output-list').html(res.html);
					//sort clumn table
					$("#table-stock-manager").tablesorter();
					_setTabIndex();
				}
			}
		});
	} catch (e) {
         alert('search' + e.message);
    }
}
/**
 * get data for pi order confirm by condition
 * 
 * @author : ANS806 - 2018/01/04 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataSearch() {
	try {
		var data = {
	            'in_out_date_from'  : $('.TXT_in_out_date_from').val(),
	            'in_out_date_to'   	: $('.TXT_in_out_date_to').val(),
	            'in_out_no'   		: $('.TXT_in_out_no').val(),
	            'item_cd'   		: $('.TXT_item_cd').val(),
	            'warehouse_div'   	: $('.TXT_warehouse_div').val(),
	            'in_out_div'   		: $('.CMB_in_out_div').val(),
	            'in_out_data_div'   : $('.CMB_in_out_data_div').val(),
	            page 				: _PAGE,
				page_size 			: _PAGE_SIZE
	        };
        return data;
	} catch (e) {
         alert('getDataSearch' + e.message);
    }
}