/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/08/20
 * 作成者		:	Trieunb - ANS806 - trieunb@ans-asia.com
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	PI
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */
$(document).ready(function () {
	initEvents();
	//remove msg empty when init screen 2018/05/03
	//$('.dataTables_empty').html('&nbsp;');
});
/**
 * init Events
 * @author  :   Trieunb - 2017/08/20 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//sort clumn table
		$("#table-user").tablesorter();
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

		//init add new
		$(document).on('click', '#btn-add-new', function () {
			var param = {
				'mode'		: 'I',
				'from'		: 'UserMasterSearch',
				'is_new'	: true
			};
			_postParamToLink('UserMasterSearch', 'UserMasterDetail', '/master/user-master-detail', param);
		});
 		//double click on line table-user
 		$(document).on('dblclick', '.table-user tbody tr.tr-table', function(){
 			var user_cd = $(this).find('td.user_cd').text().trim();
 			var mode 	= 'U';
 			var param = {
 				'mode'		: mode,
 				'from'		: 'UserMasterSearch',
 				'user_cd'	: user_cd,
 			};
 			_postParamToLink('UserMasterSearch', 'UserMasterDetail', '/master/user-master-detail', param)
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
		//btn print
 		$(document).on('click', '#btn-export', function(){
 			if (_checkDateFromTo('date-from-to')) {
				jMessage('C007',  function(r) {
					if (r) {
						userMasterExportOutput();
					}
				});
			}
 		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * Search data User
 * 
 * @author : ANS796 - 2017/11/09 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try{
		var data = {
			user_cd		: $('#TXT_user_cd').val().trim(),
			user_nm_j	: $('#TXT_user_nm_j').val().trim(),
			user_nm_e	: $('#TXT_user_nm_e').val().trim(),
			page 		: _PAGE,
			page_size 	: _PAGE_SIZE
		};
		$.ajax({
			type 		: 'POST',
			url 		: '/master/user-master-search/search',
			dataType 	: 'json',
			data 		: data,
			loading 	: true,
			success : function(res) {
				$('#user_list').html(res.html);
				//sort clumn table
				$("#table-user").tablesorter();
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
 * component Master Output
 * 
 * @author : ANS342 - 2018/05/29 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function userMasterExportOutput() {
	try{
		var data = {
			user_cd		: $('#TXT_user_cd').val().trim(),
			user_nm_j	: $('#TXT_user_nm_j').val().trim(),
			user_nm_e	: $('#TXT_user_nm_e').val().trim(),
			page 		: 1,
			page_size 	: 0
		};
		$.ajax({
			type 	: 'POST',
			url 	: '/export/user-master-search/output',
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
        console.log('productMasterOutput' + e.message)
    }
}