/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	DuyTP
 *
 * 更新日		: 	2018/05/14
 * 更新者		: 	HaVV - ANS817 - havv@ans-asia.com
 * 更新内容		: 	Development
 *
 * @package		:	INVOICE
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

$(document).ready(function () {
	initCombobox()
	initEvents();
});

function initCombobox() {
	var name = 'JP';
	//_getComboboxData(name, 'auth_role_div');
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
		sortTable();
		// //init event check all for checkbox
		checkAll('check-all-available');
		checkAll('check-all-not-available');
		checkAll('check-all-not-set');

		//click button search
		$(document).on('click', '#btn-search', function () {
			try {
				if(!_isBackScreen){
					_PAGE = 1;
				}
				search();
			} catch (e) {
				console.log('#btn-search: ' + e.message);
			}
		});

		//click button save
		$(document).on('click', '#btn-save', function () {
			try {
				var countRow = $('#table-authority tbody tr.tr-row').length;

				if (countRow == 0) {
					//not exist row
					jMessage('E004');
					return;
				}

				//exist row
				jMessage('C003',function(r){
					if(r){
						save();
					}
				});
			} catch (e) {
				console.log('#btn-save: ' + e.message);
			}
		});

		//click button btn-upload
		$(document).on('click', '#btn-upload', function () {
			try {
				jMessage('C009', function(r) {
					if (r) {
						var input = $('#upload-csv');
		                input.trigger('click'); // opening dialog

		                document.body.onfocus = function () { 
		                    setTimeout(function() {
		                    	if (input.val().length > 0) {
			                        var url = "/system-management/authority/upload";
			                        _ImportCSV(input, url);
			                    }
		                    }, 100); 
		                    document.body.onfocus = null;
		                };
		            }
				});
			} catch (e) {
				console.log('#btn-upload: ' + e.message);
			}
		});

		//click button btn-export
		$(document).on('click', '#btn-export', function () {
			try {
				jMessage('C007', function(r){
					if (r) {
						$.ajax({
			                type        :   'GET',
			                url         :   '/system-management/authority/export',
			                dataType    :   'json',
			                data        :   '',
			                loading     :   true,
			                success: function(res) {
			                	if (res.response == true) {
			                		jMessage('I008', function(r) {
			                			if (r) {
			                    			document.location.href = res.file;
			                			}
			                		});
			                	} else {
			                		jMessage('E750');
			                	}
			                }
			            });
					}
				});
			} catch (e) {
				console.log('#btn-export: ' + e.message);
			}
		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}

/**
 * sort table
 * 
 * @author : ANS817 - 2018/05/15 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function sortTable() {
	try {
		$("#table-authority").tablesorter({
			headers: { 
	            4: { 
	                sorter: false 
	            },
	            5: { 
	                sorter: false 
	            },
	            6: { 
	                sorter: false 
	            }
	        } 
	    }); 
	} catch (e) {
		alert('sortTable: ' + e.message);
	}
}

/**
 * Search data authority
 * 
 * @author : ANS817 - 2018/05/15 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try {
		if (!_validate()) {
			return;
		}

		var data = {
			auth_role_div	: $('#CMB_auth_role_div').val().trim(),
			prg_nm			: $('#TXT_prg_nm').val().trim(),
			fnc_nm			: $('#TXT_fnc_nm').val().trim(),
			fnc_use_div		: $('input[name=CHK_fnc_use_div_header]:checked').data('fnc_use_div'),
		};

		$.ajax({
			type 			: 'POST',
			url 			: '/system-management/authority/search',
			dataType 		: 'json',
			data 			: data,
			loading     	: true,
			success 		: function(res) {
				$('#div-authority-list').html(res.html);
				sortTable();
				_setTabIndex();

				//set check-all
				var check_available     = $('.check-all-available').is(':checked');
				var check_not_available = $('.check-all-not-available').is(':checked');
				var check_not_set       = $('.check-all-not-set').is(':checked');
				if (check_available && !check_not_available && !check_not_set) {
					//check all available
					$('#check-all-available').prop('checked', true);
				}
				if (!check_available && check_not_available && !check_not_set) {
					//check all not-available
					$('#check-all-not-available').prop('checked', true);
				}
				if (!check_available && !check_not_available && check_not_set) {
					//check all not-set
					$('#check-all-not-set').prop('checked', true);
				}
			}
		}).done(function(res){
			_postSaveHtmlToSession();
		});
	} catch(e) {
        alert('search' + e.message)
    }
}

/**
 * save data authority
 * 
 * @author : ANS817 - 2018/05/15 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function save() {
	try {
		var data = getDataFromView();

		$.ajax({
			type 			: 'POST',
			url 			: '/system-management/authority/save',
			dataType 		: 'json',
			data 			: data,
			success 		: function(res) {
				if(res.response == true){
					var msg = 'I003';
	            	jMessage(msg,function(r){
						if(r){
							search();
						}
					});
				} else {
					//catch DB error and display
	            	var msg_e999 = _text['E999'].replace('{0}', res.error);
	            	jMessage_str('E999', msg_e999, '', msg_e999);
				}
			}
		});
	} catch(e) {
        alert('save' + e.message)
    }
}

/**
 * get data from view
 *
 * @author      :   ANS817 - 2018/05/15 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function getDataFromView() {
	try {
		var m_auth = [];
		$('#table-authority tbody tr').each(function() {
			var auth_role_div = $(this).data('auth_role_div');
			var prg_cd        = $(this).data('prg_cd');
			var fnc_cd        = $(this).data('fnc_cd');
			var row_index     = $(this).data('row_index');
			var fnc_use_div   = $(this).find('input[name=CHK_fnc_use_div_'+row_index+']:checked').data('fnc_use_div');

			var _data = {
				auth_role_div 		: 	auth_role_div,
				prg_cd 				: 	prg_cd,
				fnc_cd 				: 	fnc_cd,
				fnc_use_div 		: 	fnc_use_div,
			};

			m_auth.push(_data);
		});

		var data = {
			m_auth					: 	m_auth
		};

		return data;
    } catch (e) {
        alert('getDataFromView: ' + e.message);
    }
}