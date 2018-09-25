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

		//init event check all for checkbox
		checkAll('check-all');

		//init add new
		$(document).on('click', '#btn-add-new', function () {
			location.href = '/test/detail';
		});

		//screen moving
		$(document).on('dblclick', '#table-area tbody tr', function(){
			location.href = '/test/detail';
		});
		
 		//btn print
 		$(document).on('click', '#btn-print', function(){
 			var cnt = $('#table-area tbody input:checked').length;
 			if (cnt > 0) {
 				jSuccess('印刷しました。');
 			} else {
 				jError('明細選択してください。');
 			}

 		});

 
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}

