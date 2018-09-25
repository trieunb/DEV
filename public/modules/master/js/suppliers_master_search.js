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
		$("#table-master-suppliers").tablesorter({});

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

		//init back
		$(document).on('click', '#btn-add-new', function () {
			var param = {
 				'mode'		: 'I',
 				'from'		: 'SuppliersMasterSearch',
				'is_new'	: true
 			};
 			_postParamToLink('SuppliersMasterSearch', 'SuppliersMasterMaintenance', '/master/suppliers-master-maintenance', param);
		});

		//click line table suppliers
 		$(document).on('dblclick', '.table-master-suppliers tbody tr.tr-table', function(){
 			var id = $(this).attr('data-client-cd').trim();
 			var param = {
 				'mode'					: 'U',
 				'from'					: 'SuppliersMasterSearch',
 				'client_cd'				: id,
 			};
 			_postParamToLink('SuppliersMasterSearch', 'SuppliersMasterMaintenance', '/master/suppliers-master-maintenance', param)
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
					PAGE       = 1
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
			_referCountry($(this).val().trim(), '', $(this), '', true);
		});

		//btn print
 		$(document).on('click', '#btn-export', function(){
 			if (_checkDateFromTo('date-from-to')) {
				jMessage('C007',  function(r) {
					if (r) {
						suppliersOutput();
					}
				});
			}
 		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}

/**
 * Search client
 * 
 * @author : ANS804 - 2017/12/19 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try{
		var data = {
			client_cd			: $('#TXT_client_cd').val().trim(),
			client_nm			: $('#TXT_client_nm').val().trim(),
			parent_client_cd	: $('#TXT_parent_client_cd').val().trim(),
			client_cd_from		: $('#TXT_client_cd_from').val().trim(),
			client_cd_to		: $('#TXT_client_cd_to').val().trim(),
			client_country_div	: $('#TXT_country_cd').val().trim(),
			cust_div			: $('#CHK_customer').prop('checked') ? 1 : 0,
			supplier_div		: $('#CHK_suppliers').prop('checked') ? 1 : 0,
			outsourcer_div		: $('#CHK_outsourcer').prop('checked') ? 1 : 0,
			page 				: _PAGE,
			page_size 			: _PAGE_SIZE
		};
		
		$.ajax({
			type 		: 'POST',
			url 		: '/master/suppliers-master-search/search',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
			success : function(res) {
				$('#client_list').html(res.html);

				// run again tooltip
				$(function () {
				  $('[data-toggle="tooltip"]').tooltip()
				});

				// run again stickytable
				$( document ).trigger( "stickyTable" );

				$("#table-master-suppliers").tablesorter();

				_setTabIndex();
			}
		}).done(function(res){
			_postSaveHtmlToSession();
		});
	} catch(e) {
        console.log('search: ' + e.message)
    }
}

/**
 * Suppliers master Export
 * 
 * @author : ANS342-KhaDV - 2018/05/28 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function suppliersOutput() {
	try {
		var data = {
			client_cd			: $('#TXT_client_cd').val().trim(),
			client_nm			: $('#TXT_client_nm').val().trim(),
			parent_client_cd	: $('#TXT_parent_client_cd').val().trim(),
			client_cd_from		: $('#TXT_client_cd_from').val().trim(),
			client_cd_to		: $('#TXT_client_cd_to').val().trim(),
			client_country_div	: $('#TXT_country_cd').val().trim(),
			cust_div			: $('#CHK_customer').prop('checked') ? 1 : 0,
			supplier_div		: $('#CHK_suppliers').prop('checked') ? 1 : 0,
			outsourcer_div		: $('#CHK_outsourcer').prop('checked') ? 1 : 0,
			page 				: 1,
			page_size 			: 0
		};
		$.ajax({
			type 		: 'POST',
			url 		: '/export/suppliers-master-search/output',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
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
        console.log('suppliersOutput: ' + e.message)
    }
}