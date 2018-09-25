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
		$("#table-working-time").tablesorter();
		//click line table table-working-time
 		$(document).on('dblclick', '#table-working-time tbody tr.tr-table', function(){
 			var workingtime_cd = $(this).find('td.work_report_no').text().trim();
 			//var workingtime_nm = $(this).find('td.workingtime_nm').text().trim();
 			parent.$('.popup-workingtime').find('.workingtime_cd').val(workingtime_cd);
 			parent.$('.popup-workingtime').find('.workingtime_cd').trigger('change');
 			//parent.$('.popup-workingtime').find('.workingtime_nm').text(workingtime_nm);
 			parent.$.colorbox.close();
 		});
 		// button search
		$(document).on('click', '#btn-search-popup', function() {
			try {
				if(!_isBackScreen){
					_PAGE = 1;
				}
				search();
			} catch (e) {
				console.log('#btn-search-popup ' + e.message);
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
					search();
				}
			} catch (e) {
				alert('#page-size' + e.message);
			}
		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * Search data working time
 * 
 * @author : ANS796 - 2018/01/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try{
		if (_checkDateFromTo('date-from-to')) {
			var data = {
				work_report_no		: $.mbTrim($('#TXT_work_report_no').val()),
				work_user_cd		: $.mbTrim($('#TXT_work_user_cd').val()),
				user_nm_j			: $.mbTrim($('#TXT_user_nm_j').val()),
				working_date_from	: $.mbTrim($('#TXT_working_date_from').val()),
				working_date_to		: $.mbTrim($('#TXT_working_date_to').val()),
				manufacture_no		: $.mbTrim($('#TXT_manufacture_no').val()),
				page 				: _PAGE,
				page_size 			: _PAGE_SIZE
			};
			$.ajax({
				type 		: 'POST',
				url 		: '/popup/search/workingtime-search',
				dataType 	: 'json',
				data 		: data,
				loading 	: true,
				success : function(res) {
					$('#popup_workingtime_list').html(res.html);
					//sort clumn table
					$("#table-working-time").tablesorter();
					_setTabIndex();
				}
			});
		}
	} catch(e) {
        console.log('search' + e.message)
    }
}