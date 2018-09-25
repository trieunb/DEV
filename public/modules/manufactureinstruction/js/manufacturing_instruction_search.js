/**
 * ****************************************************************************
 * 製造指示書一覧
 *
 * 処理概要		:	Manufacturing instruction search
 * 作成日		:	2018/04/09
 * 作成者		:	TuanNT
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	TEST
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */
$(document).ready(function () {
	if (!sessionStorage.getItem('detail')) {
        sessionStorage.clear();
    }
	// initCombobox();
	initEvents();
});
// function initCombobox() {
// 	var name = 'JP';
// 	_getComboboxData(name, 'outsourcing_div');
// 	_getComboboxData(name, 'production_status_div');
// 	_getComboboxData(name, 'done_div');
// }
/**
 * init Events
 * @author  :   TuanNT - 2018/04/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//sort clumn table
		$("#tbl-manufacturing-instruction").tablesorter({
			headers: {
	            0: {
	            	sorter: false
	            }
	        }
	    });
		//init event check all for checkbox
		checkAll('check-all');
		// button search
		$(document).on('click', '#btn-search', function() {
			try {
				var isCheckProductionDate = _checkDateFromTo('date-order');
				var isCheckManufacturingCompletionDate = _checkDateFromTo('date-manufacturing-completion');
				if (isCheckProductionDate && isCheckManufacturingCompletionDate) {
					if(!_isBackScreen){
						_PAGE = 1;
					}					
					search();
				}
			} catch (e) {
				console.log('#btn-search: ' + e.message);
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
					_PAGE 	   = 1
					search();
				}
			} catch (e) {
				console.log('#page-size: ' + e.message);
			}
		});
		//click row tbl-manufacturing-instruction
 		$(document).on('dblclick', '#tbl-manufacturing-instruction tbody tr.tr-class', function(){
 			try {
	 			var manufacture_no 		= $.trim($(this).find('td.DSP_manufacture_no').text());
	 			var param = {
	 				'mode'				: 'U',
	 				'from'				: 'ManufacturingInstructionSearch',
	 				'manufacture_no'	: manufacture_no,
	 			};
	 			_postParamToLink('ManufacturingInstructionSearch', 'ManufacturingCompletionProcess', '/manufacturing-completion-process', param);
 			} catch (e) {
				console.log('#tbl-manufacturing-instruction tbody tr.tr-class: ' + e.message);
			}
 		});
		//出力 button
 		$(document).on('click', '#btn-export', function(){
 			try {
				jMessage('C007',  function(r) {
					if (r) {
						outputExcel();
					}
				});
			} catch (e) {
				console.log('#btn-export: ' + e.message);
			}
 		});
 		// 製造指示書 button
 		$(document).on('click', '#btn-manufacturing-instruction', function(){
 			try {
 				if ($('#tbl-manufacturing-instruction').find('.check-all').is(':checked')){
					jMessage('C004', function(r) {
						if (r) {
							postPrint();
						}
					});
				} else {
					jMessage('E003');
				}
			} catch (e) {
				console.log('#btn-manufacturing-instruction: ' + e.message);
			}
 		});
 		// 出庫元作成 button
 		$(document).on('click', '#btn-good-issue-source', function(){
 			try {
 				if ($('#tbl-manufacturing-instruction').find('.check-all').is(':checked')){
					jMessage('C005', function(r) {
						if (r) {
							postCreateGoodsIssueSource();
						}
					});
				} else {
					jMessage('E003');
				}
			} catch (e) {
				console.log('#btn-good-issue-source: ' + e.message);
			}
 		});
	} catch (e) {
		console.log('initEvents: ' + e.message);
	}
}
/**
 * Search data manufacturing instruction search
 * 
 * @author : ANS796 - 2018/04/06 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try{
		var data = {
			TXT_manufacture_no 							: $.trim($('#TXT_manufacture_no').val()),
			TXT_in_order_no 							: $.trim($('#TXT_in_order_no').val()),
			TXT_mannufacturing_instruction_date_from   	: $.trim($('.TXT_mannufacturing_instruction_date_from').val()),
			TXT_mannufacturing_instruction_date_to   	: $.trim($('.TXT_mannufacturing_instruction_date_to').val()),
			TXT_orderer_nm  							: $.trim($('#TXT_orderer_nm').val()),
			CMB_manufacture_kind_div					: $.trim($('.CMB_manufacture_kind_div').val()),
			CMB_outsourcing_div							: $.trim($('.CMB_inhouse_outsourcing_manufacturing').val()),
			CMB_production_status 						: $.trim($('.CMB_production_status').val()),
			TXT_mannufacturing_completion_date_from   	: $.trim($('.TXT_mannufacturing_completion_date_from').val()),
			TXT_mannufacturing_completion_date_to   	: $.trim($('.TXT_mannufacturing_completion_date_to').val()),
			CMB_create_shipement_source_data 			: $.trim($('.CMB_create_shipement_source_data').val()),
			page 										: _PAGE,
			page_size 									: _PAGE_SIZE
		};
		$.ajax({
			type 		: 'POST',
			url 		: '/manufactureinstruction/manufacturing-instruction-search/search',
			dataType 	: 'json',
			data 		: data,
			loading 	: true,
			success : function(res) {
				$('#div-manufactor-search-list').html(res.html);
				$("#tbl-manufacturing-instruction").tablesorter({
					headers: { 
			            0: { 
			                sorter: false 
			            }
			        }
				});
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
 * output excel
 * 
 * @author : ANS796 - 2018/04/09 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function outputExcel() {
	try {
		var data = {
			TXT_manufacture_no 							: $.trim($('#TXT_manufacture_no').val()),
			TXT_in_order_no 							: $.trim($('#TXT_in_order_no').val()),
			TXT_mannufacturing_instruction_date_from   	: $.trim($('.TXT_mannufacturing_instruction_date_from').val()),
			TXT_mannufacturing_instruction_date_to   	: $.trim($('.TXT_mannufacturing_instruction_date_to').val()),
			TXT_orderer_nm  							: $.trim($('#TXT_orderer_nm').val()),
			CMB_manufacture_kind_div					: $.trim($('.CMB_manufacture_kind_div').val()),
			CMB_outsourcing_div							: $.trim($('.CMB_inhouse_outsourcing_manufacturing').val()),
			CMB_production_status 						: $.trim($('.CMB_production_status').val()),
			TXT_mannufacturing_completion_date_from   	: $.trim($('.TXT_mannufacturing_completion_date_from').val()),
			TXT_mannufacturing_completion_date_to   	: $.trim($('.TXT_mannufacturing_completion_date_to').val()),
			CMB_create_shipement_source_data 			: $.trim($('.CMB_create_shipement_source_data').val()),
			page 										: 1,
			page_size 									: 0
		};
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/manufacturing-instruction-search/export-excel',
	        dataType    :   'json',
	        data        :   data,
	        loading     :   true,
	        success: function(res) {
	            if (res.response) {
	            	jMessage('I008', function(r){
	            		if(r){
							location.href = res.filename;
	            		}
	            	});
	            } else {
	            	jMessage('W001');
	            }
	        },
	    });
	}  catch(e) {
        console.log('outputExcel' + e.message)
    }
}
/**
 * Update database and print list
 * 
 * @author : ANS796 - 2018/04/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postPrint() {
	try {
		var data 	=	getDataUpdateDB();
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/manufacturing-instruction-search/export-excel-list',
	        dataType    :   'json',
	        data        :   data,
	        loading     :   true,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		//download excel
	            		location.href = res.fileName;
	            		jMessage('I004');
	            	}
	            } else {
	            	//catch DB error and display
	            	var msg_e999 = _text['E999'].replace('{0}', res.error);
	            	jMessage_str('E999', msg_e999, '', msg_e999);
	            }
	        },
	    });
		   
	} catch (e) {
        console.log('postPrint' + e.message);
    }
}
/**
 * get data from view
 *
 * @author      :   ANS796 - 2018/04/11 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function getDataUpdateDB() {
	try {
		var update_list   = [];
		$('#tbl-manufacturing-instruction tbody tr').each(function() {
			var isCheck = $(this).find('.check-all').is(':checked');
			if (isCheck) {
				var _data = {
					manufacture_no 	: 	$.trim($(this).find('.DSP_manufacture_no').text())
				};
				update_list.push(_data);				
			}
		});
		var data = {
			update_list			: 	update_list
		};
		return data;
    } catch (e) {
        console.log('getDataUpdateDB: ' + e.message);
    }
}
/**
 * create soure goods issue
 * 
 * @author : ANS796 - 2018/04/13 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postCreateGoodsIssueSource() {
	try {
		var data 	=	getDataUpdateDB();
		$.ajax({
	        type        :   'POST',
	        url         :   '/manufactureinstruction/manufacturing-instruction-search/create-goods-issue-source',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		jMessage('I005', function(r){
	            			if(r){
	            				search();
	            			}
	            		});
	            	}
	            } else {
	            	//catch DB error and display
	            	var msg_e999 = _text['E999'].replace('{0}', res.error);
	            	jMessage_str('E999', msg_e999, '', msg_e999);
	            }
	        },
	    });
	} catch (e) {
        console.log('postCreateGoodsIssueSource' + e.message);
    }
}
