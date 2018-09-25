/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	DuyTP
 *
 * 更新日		: 	2017/12/15
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
		$("#table-component").tablesorter(); 

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
				'from'		: 'ComponentMasterSearch',
				'is_new'	: true
			};
			_postParamToLink('ComponentMasterSearch', 'ComponentMasterDetail', '/master/component-master-detail', param);
		});

		//screen moving
		$(document).on('dblclick', '#table-component tbody tr.tr-table', function(){
 			var param = {
 				'mode'				: 'U',
 				'from'				: 'ComponentMasterSearch',
 				'component_id'		: $(this).find('td.DSP_parts_cd').text().trim(),
 			};
 			_postParamToLink('ComponentMasterSearch', 'ComponentMasterDetail', '/master/component-master-detail', param);
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
				console.log('#page-size: ' + e.message);
			}
		});

		//btn print
 		$(document).on('click', '#btn-export', function(){
 			if (_checkDateFromTo('date-from-to')) {
				jMessage('C007',  function(r) {
					if (r) {
						componentMasterOutput();
					}
				});
			}
 		});

	} catch (e) {
		console.log('initEvents: ' + e.message);
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
			url 		: '/master/component-master-search/search',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
			success : function(res) {
				$('#div-component-list').html(res.html);

				$("#table-component").tablesorter();

				$( document ).trigger( "stickyTable" );
				_setTabIndex();
			}
		}).done(function(res){
			_postSaveHtmlToSession();
		});
	} catch(e) {
        alert('search' + e.message)
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
function componentMasterOutput() {
	try{
		var data = {
			item_nm			: $('#TXT_part_nm').val().trim(),
			specification	: $('#TXT_specification').val().trim(),
			supplier_cd		: $('.TXT_purchaser_order_cd').val().trim(),
			parts_cd		: $('.TXT_parts_cd').val().trim(),
			page 			: 1,
			page_size 		: 0
		};
		$.ajax({
			type 	: 'POST',
			url 	: '/export/component-master-search/output',
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
        console.log('componentMasterOutput: ' + e.message)
    }
}

