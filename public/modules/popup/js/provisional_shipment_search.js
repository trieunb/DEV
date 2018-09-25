/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/02/02
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
 * @author  :   KhaDV - 2018/02/02 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//sort clumn table
		$("#table-shipment").tablesorter({
			headers: { 
	            0: { 
	                sorter: false 
	            }
	        } 
	    }); 
		
		//init event check all for checkbox
		checkAll('check-all');
		
		//click line table provisional shipment
 		$(document).on('dblclick', '#table-shipment tbody tr.tr-class', function(){
 			
 			var fwd_no = $(this).find('td.DSP_fwd_no').text().trim();
 			parent.$('.popup-provisional-shipment').find('.provisional_shipment_cd').val(fwd_no);
 			parent.$('.popup-provisional-shipment').find('.provisional_shipment_cd').trigger('change');

 			parent.$.colorbox.close();
 		});

 		//button seach shipment
 		$(document).on('click', '#btn-search-popup', function() {
 			try {
				if(!_isBackScreen){
					_PAGE = 1;
				}
				search();
			} catch (e) {
				alert('#btn-search: ' + e.message);
			}
 		});

 		//paging
 		$(document).on('click', '#paginate li button', function() {
 			_PAGE = $(this).data('page');
 			search();
 		});
 		
 		//change paging 
		$(document).on('change', '#page-size', function() {
			try {
				if ($('#table-result').find('td.w-popup-nodata').length == 0){		
					_PAGE_SIZE = ($('.nav-pagination').children('.pagi-fillter').length > 0) ? $('#page-size').val() : 10;
					_PAGE 	   = 1
					search();
				}
			} catch (e) {
				alert('#page-size: ' + e.message);
			}
		});

		//change 国コード
		$(document).on('change', '.TXT_country_cd', function() {
			try {
				_referCountry($(this).val(), '', $(this), '', true);			   
			} catch (e) {
				console.log('change #.TXT_country_div: ' + e.message);
			}
		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * Search data provisional shipment detail
 * 
 * @author : ANS831 - 2018/02/02 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try{
		if (_checkDateFromTo('date-from-to')) {
			var data = {
				TXT_cre_date_from					: $.trim($('.TXT_cre_date_from').val()),
				TXT_cre_date_to    					: $.trim($('.TXT_cre_date_to').val()),
				TXT_fwd_no  						: $.trim($('.TXT_fwd_no').val()),
				TXT_client_nm 						: $.trim($('.TXT_client_nm').val()),
				TXT_country_cd  					: $.trim($('.TXT_country_cd').val()),
				page 								: _PAGE,
				page_size 							: _PAGE_SIZE,
				is_jp               				: $('#check-box-different-jp').is(':checked') ? 1 : 0
			};
			$.ajax({
				type 		: 'POST',
				url 		: '/popup/provisional-shipment-search/search',
				dataType 	: 'json',
				data 		: data,
				loading 	: true,
				success : function(res) {
					$('#div-shipment-list').html(res.html);
					$("#table-provisional-shipment-detail").tablesorter({
							headers: { 
					            0: { 
					                sorter: false 
					            }
					        } 
					    });
					_setTabIndex();
				}
			});
		}
	} catch(e) {
        alert('search' + e.message)
    }
}