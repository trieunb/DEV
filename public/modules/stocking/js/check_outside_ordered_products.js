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
		$("#table-stocking").tablesorter({
			headers: { 
	            0: { 
	                sorter: false 
	            },
	            5: { 
	                sorter: false 
	            },
	            8: { 
	                sorter: false 
	            },
	            9: { 
	                sorter: false 
	            }
	        } 
	    }); 
		//init event check all for checkbox
		checkAll('check-all');

		//screen moving
		$(document).on('dblclick', '#table-stocking tbody tr', function(){
			// location.href = '/invoice/detail';
		});
 		
 		//btn print
 		$(document).on('click', '#btn-print', function(){
 			var cnt = $('#table-stocking tbody input:checked').length;
 			if (cnt > 0) {
 				jSuccess('印刷しました。');
 			} else {
 				jError('明細選択してください。');
 			}

 		});

 		//btn-cancel-estimate
 		$(document).on('click', '#btn-cancel-estimate', function(){
 			var cnt = $('#table-stocking tbody input:checked').length;
 			if (cnt > 0) {
 				jConfirm('伝票取消してもよろしいですか？', 1, function(r){
					if(r){
						jSuccess('伝票取消しました。');
					}
				});
 			} else {
 				jError('明細選択してください。');
 			}
 		});

 		//btn-approve-estimate
 		$(document).on('click', '#btn-approve-estimate', function(){
 			var cnt = $('#table-stocking tbody input:checked').length;
 			if (cnt > 0) {
 				jConfirm('伝票承認してもよろしいですか？', 1, function(r){
					if(r){
						jSuccess('伝票承認しました。');
					}
				});
 			} else {
 				jError('明細選択してください。');
 			}
 		});

 		//btn-cancel-approve
 		$(document).on('click', '#btn-cancel-approve', function(){
 			var cnt = $('#table-stocking tbody input:checked').length;
 			if (cnt > 0) {
 				jConfirm('承認取消してもよろしいですか？', 1, function(r){
					if(r){
						jSuccess('承認取消しました。');
					}
				});
 			} else {
 				jError('明細選択してください。');
 			}
 		});

 		$(document).on('click', '#btn-search', function() {
 			checkDateFromTo('date-from-to');
 		});

 		$(document).on('click', '#paginate li button', function() {
 			alert($(this).data('page'));
 		});

	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}

