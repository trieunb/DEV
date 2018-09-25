/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/11/10
 * 作成者		:	Trieunb
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	LIBRARY
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */
//Global variables
var _PAGE = 1;
$(document).ready(function () {
	initCombobox();
	initEvents();
});

function initCombobox() {
	var name = 'JP';
	//_getComboboxData(name, 'possible_div');
}
/**
 * init Events
 * @author  :   Trieunb - 2017/11/10 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//search
		$(document).on('click', '#btn-search', function () {
			try {
				_PAGE = 1;
				search();
			} catch (e) {
				console.log('#btn-search ' + e.message);
			}
		});
		
		//click paging
		$(document).on('click', '#paginate li button', function() {
 			_PAGE = $(this).data('page');
 			search();
 		});
 		//change paging 
		$(document).on('change', '#page-size', function() {
			try {
				if ($('#table-result').find('td.w-popup-nodata').length == 0){
					search(_PAGE);
				}
			} catch (e) {
				alert('#page-size' + e.message);
			}
		});
		//click line table pi
 		$(document).on('dblclick', '.table-library tbody tr', function() {
 			if (!$(this).find('td').hasClass('dataTables_empty')) {
 				var lib_cd = $(this).find('td:eq(0)').text().trim();
	 			var param = {
	 				'mode'			: 'U',
	 				'from'			: 'LibMasterSearch',
	 				'lib_cd'		: lib_cd,
	 			};
	 			_postParamToLink('LibMasterSearch', 'LibMasterDetail', '/system-management/library-master', param)
 			}
 		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * Search data Library
 * 
 * @author : ANS806 - 2017/11/09 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try{
		var data = getData();
		$.ajax({
			type 	 : 'POST',
			url  	 : '/system-management/library-master-search/search',
			dataType : 'json',
			data 	 : data,
			loading  : true,
			success  : function(res) {
				if (res.response) {
					$('#library-list').html(res.html);
					//sort clumn table
					$("#table-library").tablesorter();
					_setTabIndex();
				} else {
					$('#library-list').html('');
				}
			}
		}).done(function(res){
			_postSaveHtmlToSession();
		});
	} catch(e) {
        console.log('search' + e.message)
    }
}
/**
 * get data for condition search
 * 
 * @author : ANS806 - 2017/11/20 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getData() {
	try{
		var page_size = ($('.nav-pagination').children('.pagi-fillter').length > 0) ? $('#page-size').val() : 10
		var STT_data  = {
			lib_cd			: $('.TXT_lib_cd').val().trim(),
			lib_nm			: $('.TXT_lib_nm').val().trim(),
			lib_val_cd		: $('.TXT_lib_val_cd').val().trim(),
			lib_val_ab		: $('.TXT_lib_val_ab').val().trim(),
			change_perm_div	: $('.CMB_change_perm_div').val(),
			page 			: _PAGE,
			page_size 		: page_size
		};
		return STT_data;
	} catch(e) {
        console.log('getDataSearch' + e.message)
    }
}