/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	DuyTP
 *
 * 更新日		: 	2018/02/27
 * 更新者		: 	DungNN - ANS810
 * 更新内容		: 	New Development
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
		//sort table
		$("#table-packing").tablesorter({
			headers: { 
	            0: { 
	                sorter: false 
	            }
	        } 
	    }); 
		//init event check all for checkbox
		checkAll('check-all');		


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
		
		// button search
		$(document).on('click', '#btn-search', function() {
			try {
				if (_checkDateFromTo('date-estimate')) {
					if(!_isBackScreen){
						_PAGE = 1;
					}					
					search();
				}
			} catch (e) {
				alert('#btn-search: ' + e.message);
			}
		});

		// button export
		$(document).on('click', '#btn-export', function() {
			try {
				if (_checkDateFromTo('date-estimate')) {
					jMessage('C007', function(r) {
						if (r) {
							outputExcel();
						}
					});
				}				
			} catch (e) {
				alert('#btn-export: ' + e.message);
			}
		});		

		// btn-issue-instruction
 		$(document).on('click', '#btn-issue', function(){
 			try {
 				if ($('#table-packing').find('.check-all').is(':checked')) {					
					jMessage('C004', function(r) {
						if (r) {
							postPrint();
						}
					});
				} else {
					jMessage('E003');
				}
 				
			} catch (e) {
				alert('#btn-issue ' + e.message);
			}
 		});

 		// Change 国コード
		$(document).on('change', '.country_cd', function() {
			try {
				referCountry();
			} catch (e) {
				alert('change country: ' + e.message);
			}
		});

	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * Search packing list
 * 
 * @author : ANS810 - 2018/02/27 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try{
		var data = {
			TXT_inv_date_no_from    				: $.trim($('.TXT_inv_date_no_from').val()),
			TXT_inv_date_no_to  					: $.trim($('.TXT_inv_date_no_to').val()),
			TXT_rcv_no 								: $.trim($('.TXT_rcv_no').val()),
			TXT_cust_nm 							: $.trim($('.TXT_cust_nm').val()),
			TXT_country_cd 							: $.trim($('.TXT_country_cd').val()),
			CMB_status 								: $.trim($('.CMB_status').val()),
			page 									: _PAGE,
			page_size 								: _PAGE_SIZE,
			is_jp               					: $('#check-box-different-jp').is(':checked') ? 1 : 0
		};
		$.ajax({
			type 		: 'POST',
			url 		: '/oversea-document/packing-list/search',
			dataType 	: 'json',
			data 		: data,
			loading 	: true,
			success : function(res) {
				// Do something here
				$('#div-packing-list').html(res.html);
				$("#table-packing").tablesorter({
					headers: { 
			            0: { 
			                sorter: false 
			            }
			        }
				});

				// run again stickytable
				$( document ).trigger( "stickyTable" );

				_setTabIndex();
			}
		}).done(function(res){
			_postSaveHtmlToSession();
		});
	} catch(e) {
        alert('search' + e.message)
    }
}
/**
 * output excel
 * 
 * @author : ANS810 - 2018/01/17 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function outputExcel() {
	try {
		var data = {
			TXT_inv_date_no_from    				: $.trim($('.TXT_inv_date_no_from').val()),
			TXT_inv_date_no_to  					: $.trim($('.TXT_inv_date_no_to').val()),
			TXT_rcv_no 								: $.trim($('.TXT_rcv_no').val()),
			TXT_cust_nm 							: $.trim($('.TXT_cust_nm').val()),
			TXT_country_cd 							: $.trim($('.TXT_country_cd').val()),
			CMB_status 								: $.trim($('.CMB_status').val()),
			page 									: 1,
			page_size 								: 0,
			is_jp               					: $('#check-box-different-jp').is(':checked') ? 1 : 0
		};
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/packing-list-report/export-excel',
	        dataType    :   'json',
	        data        :   data,
	        loading     :   true,
	        success: function(res) {
	            if (res.response) {
	            	jMessage('I008');
	            	location.href = res.filename;
	            }
	            else{
	            	jMessage('W001');
	            }
	        },
	    });
	}  catch(e) {
        console.log('outputExcel' + e.message)
    }
}
/**
 * Update database and print list
 * 
 * @author : ANS810 - 2018/03/09 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postPrint() {
	try {
		var data 	=	getDataUpdateDB();
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/packing-list-report/export-excel-list',
	        dataType    :   'json',
	        data        :   data,
	        loading     :   true,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		//download excel
	            		location.href = res.fileName;
	            		jMessage('I004');
	            	}
	            } else {
	            	//catch DB error and display
	            	var msg_e999 = _text['E999'].replace('{0}', res.error);
	            	jMessage_str('E999', msg_e999, '', msg_e999);
	            }
	        },
	    });
		   
	} catch (e) {
         alert('postPrint' + e.message);
    }
}
/**
 * get data from view
 *
 * @author      :   ANS810 - 2018/03/09 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function getDataUpdateDB() {
	try {
		var update_list   = [];
		//
		$('#table-packing tbody tr').each(function() {
			var isCheck = $(this).find('.check-all').is(':checked');
			if (isCheck) {
				var _data = {
					inv_no					: 	$(this).find('.inv_no').text().trim()
				};
				update_list.push(_data);
			}
		});
		var data = {
			update_list			: 	update_list
		};
		return data;
    } catch (e) {
        alert('getDataUpdateDB: ' + e.message);
    }
}
/**
 * refer country
 *
 * @author      :   ANS810 - 2018/03/21 - create
 * @param       : 	
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function referCountry(){
	try{
		// Get data refer
		var country_cd 	=	$('.country_cd').val().trim();

	    $.ajax({
	        type        :   'GET',
	        url         :   '/common/refer/refer-country',
	        dataType    :   'json',
	        data        :   {
	        					country_cd : country_cd
	        				},
	        success: function(res) {
	        	//
	            if(res.response == true) {
	            	// Do something here
	            	$('.country_nm').text(res.data.country_nm);
	            }else{
	            	//$('.country_cd').focus();
	            	$('.country_nm').text('');
	            }
	        },
	    });
	} catch(e) {
        alert('referCountry: ' + e.message)
    }
}