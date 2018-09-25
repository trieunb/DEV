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
		$("#table-stock-manager").tablesorter(); 
 		//btn print
 		$(document).on('click', '#btn-export', function(){
			jMessage('C007',  function(r) {
				if (r) {
					outputExcel();
				}
			});
 		});
 		// button search
		$(document).on('click', '#btn-search', function() {
			try {
				if(!_isBackScreen){
					_PAGE = 1;
				}
				search();
			} catch (e) {
				console.log('#btn-search ' + e.message);
			}
		});
		$(document).on('click', '#paginate li button', function() {
			try {
	 			_PAGE = $(this).data('page');
	 			search();
	 		} catch (e) {
				console.log('#paginate li button: ' + e.message);
			}
 		});
 		//change paging 
		$(document).on('change', '#page-size', function() {
			try {
				if ($('#table-result').find('td.w-popup-nodata').length == 0){		
					_PAGE_SIZE = ($('.nav-pagination').children('.pagi-fillter').length > 0) ? $('#page-size').val() : 10;
					_PAGE = 1;
					search();
				}
			} catch (e) {
				console.log('#page-size' + e.message);
			}
		});
		//change 品目コード
		$(document).on('change', '#TXT_item_cd', function() {
			var data = {
				'item_cd'		: 	$.mbTrim($('#TXT_item_cd').val()),
			}
			_referMItem(data, $(this), '', true);
		});
		//change 倉庫
		$(document).on('change', '#TXT_warehouse_cd', function() {
			$(this).parent().addClass('popup-warehouse');
			_referWarehouse($.mbTrim($(this).val()), $(this), '', true);
		});
	} catch (e) {
		console.log('initEvents: ' + e.message);
	}
}
/**
 * Search data
 * 
 * @author : ANS796 - 2018/01/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try{
		var data = {
			item_cd			: $.mbTrim($('#TXT_item_cd').val()),
			warehouse_cd	: $.mbTrim($('#TXT_warehouse_cd').val()),
			page 			: _PAGE,
			page_size 		: _PAGE_SIZE
		};
		$.ajax({
			type 		: 'POST',
			url 		: '/stock-manage/stock-search/search',
			dataType 	: 'json',
			data 		: data,
			loading 	: true,
			success : function(res) {
				$('#stock_list').html(res.html);
				//sort clumn table
				$("#table-stock-manager").tablesorter();
				_setTabIndex();
			}
		}).done(function(res){
			_postSaveHtmlToSession();
		});
	} catch(e) {
        console.log('search' + e.message)
    }
}
/**
 * output excel
 * 
 * @author : ANS796 - 2018/01/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function outputExcel() {
	try {
		var data = {
			item_cd			: $.mbTrim($('#TXT_item_cd').val()),
			warehouse_cd	: $.mbTrim($('#TXT_warehouse_cd').val()),
			page 			: 1,
			page_size 		: 0
		};
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/stock-search',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	jMessage('I008', function(r){
	            		if(r){
							location.href = res.fileName;
	            		}
	            	});
	            } else {
	            	jMessage('E005');
	            }
	        },
	    });
	}  catch(e) {
        console.log('outputExcel' + e.message)
    }
}