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
		$("#table-stock-manager").tablesorter(); 

		//click line table table-master-ml10
 		$(document).on('dblclick', '#table-stock-manager tbody tr', function(){

 			var stockmanage_id = $(this).find('td.stockmanage_id').text().trim();
 			var stockmanager_nm = $(this).find('td.stockmanager_nm').text().trim();

 			parent.$('.popup-stockmanage').find('.stockmanage_id').val(stockmanage_id);
 			parent.$('.popup-stockmanage').find('.stockmanager_nm').text(stockmanager_nm);
 			parent.$.colorbox.close();
 		});

	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}