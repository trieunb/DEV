/**
 * ****************************************************************************
 * Selling Unit Price By Client Search
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	TrieuNB
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
	//_getComboboxData(name, 'sales_unit_price_kind_div');
}
/**
 * init Events
 * @author  :   TrieuNB - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//sort clumn table
		$("#table-sales-price").tablesorter(); 
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
		//init add new
		$(document).on('click', '#btn-add-new', function () {
			var param = {
				'mode'		: 'I',
				'from'		: 'SellingUnitPriceByClientSearch',
				'is_new'	: true
			};
			_postParamToLink('SellingUnitPriceByClientSearch', 'SellingUnitPriceByClientDetail', '/master/selling-unit-price-by-client-detail', param);
		});
		//screen moving
		$(document).on('dblclick', '#table-sales-price tbody tr.tr-table', function(){
			var param = {
 				'mode'				: 'U',
 				'from'				: 'SellingUnitPriceByClientSearch',
 				'product_cd'		: $(this).find('td.product_cd').text().trim(),
 				'client_cd'			: $(this).find('td.client_cd').text().trim(),
 				'apply_st_date'		: $(this).find('td.apply_st_date').text().trim(),
 			};
 			_postParamToLink('SellingUnitPriceByClientSearch', 'SellingUnitPriceByClientDetail', '/master/selling-unit-price-by-client-detail', param);
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
					_PAGE = 1;
					search();
				}
			} catch (e) {
				alert('#page-size' + e.message);
			}
		});
		// Change 取引先コード
		$(document).on('change', '.TXT_client_cd', function() {
			try {
				_getClientName($(this).val(), $(this), '', true);
			} catch (e) {
				console.log('change: .TXT_client_cd ' + e.message);
			}
		});
		//change 国コード  
		$(document).on('change', '.country_cd', function() {
			$(this).parent().addClass('popup-country');
			_referCountry($(this).val().trim(), $(this), '', true);
		});
		//btn print
 		$(document).on('click', '#btn-export', function(){
 			if (_checkDateFromTo('date-from-to')) {
				jMessage('C007',  function(r) {
					if (r) {
						sellingUnitPriceByClientExportOutput();
					}
				});
			}
 		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * Search data Sales price
 * 
 * @author : ANS796 - 2017/12/13 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try{
		var data = {
			product_nm	: $('#TXT_product_nm').val().trim(),
			client_cd	: $('.TXT_client_cd').val().trim(),
			country_cd	: $('.TXT_country_cd').val().trim(),
			type		: $.mbTrim($('#TXT_type').val()),
			page 		: _PAGE,
			page_size 	: _PAGE_SIZE
		};
		$.ajax({
			type 		: 'POST',
			url 		: '/master/selling-unit-price-by-client-search/search',
			dataType 	: 'json',
			data 		: data,
			loading 	: true,
			success : function(res) {
				$('#sales_price_list').html(res.html);
				//sort clumn table
				$("#table-sales-price").tablesorter();
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
 * component Master Output
 * 
 * @author : ANS342 - 2018/05/29 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function sellingUnitPriceByClientExportOutput() {
	try{
		var data = {
			product_nm	: $('#TXT_product_nm').val().trim(),
			client_cd	: $('.TXT_client_cd').val().trim(),
			country_cd	: $('.TXT_country_cd').val().trim(),
			type		: $.mbTrim($('#TXT_type').val()),
			page 		: 1,
			page_size 	: 0
		};
		$.ajax({
			type 	: 'POST',
			url 	: '/export/selling-unit-price-by-client-search/output',
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
        console.log('sellingUnitPriceByClientExportOutput: ' + e.message)
    }
}