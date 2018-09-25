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
 * @package		:	TEST
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
	//_getComboboxData(name, 'outsourcing_div');
	//_getComboboxData(name, 'production_status_div');
	//_getComboboxData(name, 'done_div');
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
		$("#tbl-manufacturing-instruction").tablesorter({
			headers: { 
	            0: { 
	                sorter: false 
	            }
	        } 
	    }); 
		//init event check all for checkbox
		checkAll('check-all');
		//click line table-manufacturing-instruction
 		$(document).on('dblclick', '#tbl-manufacturing-instruction tbody tr.tr-class', function(){
 			var manufacture_no 		= $.trim($(this).find('td.DSP_manufacture_no').text());
 			parent.$('.popup-manufacturinginstruction').find('.manufacturinginstruction_cd').val(manufacture_no);
 			parent.$('.popup-manufacturinginstruction').find('.manufacturinginstruction_cd').trigger('change');
 			parent.$.colorbox.close();
 		});
		// button search
		$(document).on('click', '#btn-search-popup', function() {
			try {
				if (_checkDateFromTo('date-order') && _checkDateFromTo('date-manufacturing-completion')) {
					if(!_isBackScreen){
						_PAGE = 1;
					}					
					search();
				}
			} catch (e) {
				alert('#btn-search: ' + e.message);
			}
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
					_PAGE 	   = 1
					search();
				}
			} catch (e) {
				alert('#page-size: ' + e.message);
			}
		});
	} catch (e) {
		alert('initEvents: ' + e.message);
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
			type : 'POST',
			url : '/popup/search/manufacturinginstruction-search',
			dataType : 'json',
			data : data,
			loading		: true,
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
		});
	} catch(e) {
        alert('search' + e.message)
    }
}
