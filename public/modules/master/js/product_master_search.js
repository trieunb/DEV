/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	DuyTP
 *
 * 更新日		: 	2017/12/21
 * 更新者		: 	HaVV - ANS817
 * 更新内容		: 	New Development
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
		$(document).on('click', '#btn-search', function() {
			try {
				if(!_isBackScreen){
					_PAGE = 1;
				}
				search();
			} catch (e) {
				console.log('#btn-search: ' + e.message);
			}
		});	
		
		//init add new
		$(document).on('click', '#btn-add-new', function () {
			var param = {
				'mode'		: 'I',
				'from'		: 'ProductMasterSearch',
				'is_new'	: true
			};
			_postParamToLink('ProductMasterSearch', 'ProductMasterDetail', '/master/product-master-detail', param);
			//location.href = '/master/product-master-detail';
		});

		//screen moving
		$(document).on('dblclick', '#table-product tbody tr.tr-table', function(){
			var param = {
 				'mode'				: 'U',
 				'from'				: 'ProductMasterSearch',
 				'product_cd'		: $(this).find('td.DSP_product_cd').text().trim(),
 			};
 			_postParamToLink('ProductMasterSearch', 'ProductMasterDetail', '/master/product-master-detail', param);
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
				console.log('#page-size: ' + e.message);
			}
		});

		//btn print
 		$(document).on('click', '#btn-export', function(){
 			if (_checkDateFromTo('date-from-to')) {
				jMessage('C007',  function(r) {
					if (r) {
						productMasterOutput();
					}
				});
			}
 		});

	} catch (e) {
		console.log('initEvents: ' + e.message);
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
			url 	: '/master/product-master-search/search',
			dataType: 'json',
			data 	: data,
			loading	: true,
			success : function(res) {
				$('#div-product-list').html(res.html);

				$("#table-product").tablesorter();

				$( document ).trigger( "stickyTable" );
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
 * Search data Product
 * 
 * @author : ANS817 - 2017/12/21 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function productMasterOutput() {
	try{
		var data = {
			item_nm			: $('#TXT_product_nm').val().trim(),
			specification	: $('#TXT_specification').val().trim(),
			product_cd		: $('#TXT_product_cd').val().trim(),
			page 			: 1,
			page_size 		: 0
		};
		$.ajax({
			type 	: 'POST',
			url 	: '/export/product-master-search/output',
			dataType: 'json',
			data 	: data,
			loading	: true,
			success: function(res) {
				if (res.response) {
					location.href = res.filename;
					jMessage('I008');
				} else {
	            	jMessage('W001');
	            }
			}
		});
	}  catch(e) {
        console.log('productMasterOutput: ' + e.message)
    }
}
