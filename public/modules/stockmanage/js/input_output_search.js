/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/01/16
 * 作成者		:	DuyTP
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	stock-manage
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
 * @author  :   Trieunb - 2018/01/16 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//sort clumn table
		$("#table-stock-manager").tablesorter(); 
		// button search
		$(document).on('click', '#btn-search', function() {
			try {
				if (_checkDateFromTo('input-output-date-to-from')) {
					if(!_isBackScreen){
						_PAGE = 1;
					}
					search();
				}
			} catch (e) {
				alert('#btn-search ' + e.message);
			}
		});
		//init add new
		$(document).on('click', '#btn-add-new', function () {
			var param = {
				'mode'		: 'I',
				'from'		: 'InputOutputSearch'
			};
			_postParamToLink('InputOutputSearch', 'InputOutputDetail', '/stock-manage/input-output-detail', param);
		});
 		//btn print
 		$(document).on('click', '#btn-export', function(){
			if (_checkDateFromTo('input-output-date-to-from')) {
				jMessage('C007',  function(r) {
					if (r) {
						StockInputOutputExport();
					}
				});
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
		//screen moving
		$(document).on('dblclick', '#table-stock-manager tbody tr', function(){
			if (!$(this).find('td').hasClass('dataTables_empty')) {
	 			var in_out_no 	= $(this).find('.DSP_in_out_no').text().trim();
	 			var param = {
	 				'mode'			: 'U',
	 				'from'			: 'InputOutputSearch',
	 				'in_out_no'		: in_out_no,
	 			};
	 			_postParamToLink('InputOutputSearch', 'InputOutputDetail', '/stock-manage/input-output-detail', param);
 			}
		});
		//change item cd  
		$(document).on('change', '.TXT_item_cd', function() {
			var data = {
				item_cd : $(this).val().trim()
			};
			_referMItem(data, $(this), '', true);
		});
		//change warehouse div  
		$(document).on('change', '.TXT_warehouse_div', function() {
			var warehouse_div =	$(this).val().trim();
			_referWarehouse(warehouse_div, $(this), '', true);
		});
		//btn upload
		$(document).on('click', '#btn-upload', function () {
			jMessage('C009', function (r) {
				if (r) {
					var input = $('#upload-excel');
					input.trigger('click'); // opening dialog

					document.body.onfocus = function () {
						setTimeout(function () {
							if (input.val().length > 0) {
								var url = "/stock-manage/input-output-search/upload";
								_ImportExcel(input, url, null, function(filePath) {
									if (filePath) {
										location.href = filePath;
									}
								});
							}
						}, 100);
						document.body.onfocus = null;
					};

				}
			});
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
			url 		: '/stock-manage/input-output/search',
			dataType 	: 'json',
			data 		: data,
			loading 	: true,
			success: function(res) {
				if (res.response) {
					$('#input-output-list').html(res.html);
					//sort clumn table
					$("#table-stock-manager").tablesorter();
					_setTabIndex();
				}
			}
		}).done(function(res){
			_postSaveHtmlToSession();
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
/**
 * Stock Input Output Export
 * 
 * @author : ANS806 - 2018/01/16 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function StockInputOutputExport() {
	try {
		var data = getDataSearch();
		$.ajax({
			type 		: 'POST',
			url 		: '/export/stock-manage/input-output-search/export',
			dataType 	: 'json',
			data 		: data,
			loading     : true,
			success: function(res) {
				if (res.response) {
					location.href = res.filename;
					jMessage('I008');
				} else {
	            	jMessage('E005');
	            }
			}
		});
	}  catch(e) {
        console.log('piExport' + e.message)
    }
}