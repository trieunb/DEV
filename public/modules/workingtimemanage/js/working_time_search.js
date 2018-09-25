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
	if (!sessionStorage.getItem('detail')) {
        sessionStorage.clear();
    }
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
		//init add new
		$(document).on('click', '#btn-add-new', function () {
			var param = {
				'mode'		: 'I',
				'from'		: 'WorkingTimeSearch'
			};
			_postParamToLink('WorkingTimeSearch', 'WorkingTimeDetail', '/working-time-manage/working-time-detail', param);
		});
		//screen moving
		$(document).on('dblclick', '#table-working-time tbody tr.tr-table', function(){
			var work_report_no = $(this).find('td.work_report_no').text().trim();
 			var mode 	= 'U';
			var param = {
 				'mode'				: mode,
 				'from'				: 'WorkingTimeSearch',
 				'work_report_no'	: work_report_no,
 			};
 			_postParamToLink('WorkingTimeSearch', 'WorkingTimeDetail', '/working-time-manage/working-time-detail', param);
		});
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
				url 		: '/working-time-manage/working-time-search/search',
				dataType 	: 'json',
				data 		: data,
				loading 	: true,
				success : function(res) {
					$('#workingtime_list').html(res.html);
					//sort clumn table
					$("#table-working-time").tablesorter();
					_setTabIndex();
				}
			}).done(function(res){
				_postSaveHtmlToSession();
			});
		}
	} catch(e) {
        console.log('search' + e.message)
    }
}
/**
 * output excel
 * 
 * @author : ANS796 - 2018/01/10 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function outputExcel() {
	try {
		var data = {
			work_report_no		: $.mbTrim($('#TXT_work_report_no').val()),
			work_user_cd		: $.mbTrim($('#TXT_work_user_cd').val()),
			user_nm_j			: $.mbTrim($('#TXT_user_nm_j').val()),
			working_date_from	: $.mbTrim($('#TXT_working_date_from').val()),
			working_date_to		: $.mbTrim($('#TXT_working_date_to').val()),
			manufacture_no		: $.mbTrim($('#TXT_manufacture_no').val()),
			page 				: 1,
			page_size 			: 0 	//search all record
		};
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/working-time',
	        dataType    :   'json',
	        data        :   data,
	        loading     :   true,
	        success: function(res) {
	            if (res.response) {
	            	jMessage('I008', function(r){
	            		if(r){
							location.href = res.fileName;
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