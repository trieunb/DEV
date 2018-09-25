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

		//show/hide address to
		// $(document).on('click', '#show-address-to', function(){
		// 	if ($('.address-to').is(':visible') == true) {
		// 		$(this).text('住所表示');
		// 		$('.address-to').hide();	
		// 	} else {
		// 		$(this).text('住所非表示');
		// 		$('.address-to').show();
		// 	}
		// });

		// //show/hide address from
		// $(document).on('click', '#show-address-from', function(){
		// 	if ($('.address-from').is(':visible') == true) {
		// 		$(this).text('住所表示');
		// 		$('.address-from').hide();	
		// 	} else {
		// 		$(this).text('住所非表示');
		// 		$('.address-from').show();
		// 	}
		// });

		// remove row table
		$(document).on('click','.remove-row',function(e){
			$(this).closest('tr').next('tr').remove();
			$(this).closest('tr').remove();
			e.preventDefault();
		});

		//add row
		$(document).on('click', '#btn-add-row', function () {
			try {
				var row = $("#table-row tr").clone();

				$('#table-text tbody').append(row);

				// $("#table-area tbody tr:last select").focus();
			} catch (e) {
				alert('add new row' + e.message);
			}

		});

		//init back
		$(document).on('click', '#btn-back', function () {
			location.href = '/invoice';
		});

		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				jConfirm('保存してもよろしいですか？', 1, function(r){
					if(r){
						if(validate()){
							jSuccess('保存しました。');
						}
					}
				});
			   
			} catch (e) {
				alert('#btn-save ' + e.message);
			}
		});
 		

		// button delete
		$(document).on('click', '#btn-delete', function() {
			try {
				jConfirm('削除してもよろしいですか？', 1, function(r){
					if(r){
						jSuccess('削除しました。');
					}
				});
			   
			} catch (e) {
				alert('#btn-delete ' + e.message);
			}
		});

 		//btn print
 		$(document).on('click', '#btn-print', function(){
 			jSuccess('印刷しました。');

 		});

 		//btn-approve-estimate
 		$(document).on('click', '#btn-approve-estimate', function(){
			jConfirm('伝票承認してもよろしいですか？', 1, function(r){
				if(r){
					jSuccess('伝票承認しました。');
				}
			});
 		});

 		//btn-cancel-approve
 		$(document).on('click', '#btn-cancel-approve', function(){
			jConfirm('承認取消してもよろしいですか？', 1, function(r){
				if(r){
					jSuccess('承認取消しました。');
				}
			});
 		});

	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}



/**
 * validate
 *
 * @author		:	DuyTP - 2017/06/15 - create
 * @params		:	null
 * @return		:	null
 */
function validate(){
	var _errors = 0;
	if(!_validate($('body'))){
		_errors++;
	}

	if(_errors>0)
		return false;

	return true;
}