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
 //Global variables
$(document).ready(function () {
	initEvents();
});
/**
 * init Events
 * @author  :   Trieunb - 2017/08/20 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//init event check all for checkbox
		checkAll('check-all');
		// button search
		$(document).on('click', '#btn-search', function() {
			try {
				if (_checkDateFromTo('date-estimate') && _checkDateFromTo('date-order')) {
					if(!_isBackScreen){
						_PAGE = 1;
					}
					search();
				}
			} catch (e) {
				alert('#btn-search ' + e.message);
			}
		});	
		//init add new
		$(document).on('click', '#btn-add-new', function () {
			var param = {
				'mode'		: 'I',
				'from'		: 'PiSearch',
				'is_new'	: true
			};
			_postParamToLink('PiSearch', 'PiDetail', '/pi/pi-detail', param);
		});
 		//btn approve
 		$(document).on('click', '#btn-approve', function(){
 			try {
 				jMessage('C005', function(r) {
					if (r) {
						approvePiList();
					}
				});   
			} catch (e) {
				alert('#btn-approve ' + e.message);
			}
 		});
 		//btn print
 		$(document).on('click', '#btn-print', function(){
			jMessage('C004',  function(r) {
				if (r) {
					piExport();
				}
			});
		});
 		//click line table pi
 		$(document).on('dblclick', '.table-pi tbody tr', function(){
 			if (!$(this).find('td').hasClass('dataTables_empty')) {
	 			var approve = $(this).find('.DSP_pi_status_nm').text().trim();
	 			var mode 	= 'I';
	 			if (approve == '申請中') {
	 				mode	= 'R';
	 			}
	 			if (approve == '承認済') {
	 				mode	= 'A';
	 			}
	 			if (approve == '受注済') {
	 				mode	= 'O';
	 			}
	 			if (approve == '失注') {
	 				mode	= 'L';
	 			}
	 			var pi_no 	= $(this).find('.DSP_pi_no').text().trim();
	 			var param = {
	 				'mode'		: mode,
	 				'from'		: 'PiSearch',
	 				'pi_no'		: pi_no,
	 			};
	 			_postParamToLink('PiSearch', 'PiDetail', '/pi/pi-detail', param);
 			}
 		});
 		//change paging 
		$(document).on('change', '#page-size', function() {
			try {
				_PAGE_SIZE = ($('.nav-pagination').children('.pagi-fillter').length > 0) ? $('#page-size').val() : 10
				_PAGE = 1;
				search();
			} catch (e) {
				alert('#page-size' + e.message);
			}
		});
 		//click paging
		$(document).on('click', '#paginate li button', function() {
 			_PAGE = $(this).data('page');
 			search();
 		});
 		//change accept_cd  
		$(document).on('change', '.accept_cd', function() {
			var accept_cd =	$(this).val().trim();
			_referPiAccept(accept_cd, $(this), '', true);
		});
		//btn print
 		$(document).on('click', '#btn-export', function(){
 			if (_checkDateFromTo('date-from-to')) {
				jMessage('C007',  function(r) {
					if (r) {
						piExportOutput();
					}
				});
			}
 		});
 		//change TXT_country_div
 		$(document).on('change', '.TXT_country_cd', function() {
 			try {
				var country_div = $(this).val();
	 			_referCountry(country_div, '', $(this), '', true);
            } catch (e) {
                console.log('.TXT_country_cd: ' + e.message);
            }
 		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * search pi detail
 * 
 * @author : ANS806 - 2017/12/14 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try {
		var data = getDataSearch();
		$.ajax({
			type 		: 'POST',
			url 		: '/pi/search',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
			success: function(res) {
				if (res.response) {
					$('#pi-list').html(res.html);
					//sort clumn table
					$("#table-pi").tablesorter({
						headers: { 
				            0: { 
				                sorter: false 
				            }
				        } 
				    });
					_setTabIndex();
				}
			}
		}).done(function(res){
			_postSaveHtmlToSession();
		});
	} catch (e) {
         alert('search' + e.message);
    }
}
/**
 * get data for pi search condition
 * 
 * @author : ANS806 - 2017/12/14 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataSearch() {
	try {
		var data = {
	            'pi_date_from'   	: $('.TXT_pi_date_from').val(),
	            'pi_date_to'   		: $('.TXT_pi_date_to').val(),
	            'rcv_date_from'   	: $('.TXT_rcv_date_from').val(),
	            'rcv_date_to'   	: $('.TXT_rcv_date_to').val(),
	            'pi_no'   			: $('.TXT_pi_no').val(),
	            'rcv_no'   			: $('.TXT_rcv_no').val(),
	            'cust_nm'   		: $('.TXT_cust_nm').val(),
	            'pi_status_div'   	: $('.CMB_pi_status_div').val(),
	            page 				: _PAGE,
				page_size 			: _PAGE_SIZE,
				'country_cd' 		: $('.TXT_country_cd').val().trim(),
				is_jp               : $('#check-box-different-jp').is(':checked') ? 1 : 0
	        };
        return data;
	} catch (e) {
         alert('getDataSearch' + e.message);
    }
}
/**
 * approve pi detail list
 * 
 * @author : ANS806 - 2017/12/14 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function approvePiList() {
	try {
		var _pi_list = getDataPiList();
		if ($('#table-pi').find('.check-all').is(':checked')) {
			$.ajax({
		        type        :   'POST',
		        url         :   '/pi/pi-search/approve',
		        dataType    :   'json',
		        data        :   {pi_list : _pi_list},
		        success: function(res) {
		            if (res.response) {
		            	if (res.error_cd != '') {
		            		jMessage(res.error_cd);
		            	} else {
		            		jMessage('I005', function(r) {
		            			if (r) {
		            				_PAGE = 1;
		            				search();
		            			}
		            		});
		            	} 
		            } else {
		            	//catch DB error and display
		            	var msg_e999 = _text['E999'].replace('{0}', res.error);
		            	jMessage_str('E999', msg_e999, '', msg_e999);
		            }
		        },
		    });
		} else {
			jMessage('E003');
		}   
	} catch (e) {
         alert('approvePiList' + e.message);
    }
}
/**
 * print pi detail list
 * 
 * @author : ANS806 - 2017/12/14 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function printPiList() {
	try {
		var _pi_list = getDataPiList();
		if ($('#table-pi').find('.check-all').is(':checked')) {
			$.ajax({
		        type        :   'POST',
		        url         :   '/pi/pi-search/print',
		        dataType    :   'json',
		        data        :   {pi_list : _pi_list},
				loading		:	true,
		        success: function(res) {
		            if (res.response) {
		            	if (res.error_cd != '') {
		            		jMessage(res.error_cd);
		            	} else {
		            		jMessage('I004')
		            		//download excel
		            	} 
		            } else {
		            	//catch DB error and display
		            	var msg_e999 = _text['E999'].replace('{0}', res.error);
		            	jMessage_str('E999', msg_e999, '', msg_e999);
		            }
		        },
		    });
		} else {
			jMessage('E003');
		}   
	} catch (e) {
         alert('printPiList' + e.message);
    }
}
/**
 * get data approve pi list
 * 
 * @author : ANS806 - 2017/12/14 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataPiList() {
	try {
		var data_list = [];
		$('#table-pi tbody tr').each(function() {
			var isCheck = $(this).find('.check-all').is(':checked');
			if (isCheck) {
				var pi_status_div 	=	$(this).find('.DSP_pi_status_div').text().trim();
				var data = {
					pi_no 			: 	$(this).find('.DSP_pi_no').text().trim(),
					pi_status_div 	: 	pi_status_div,
				};
				data_list.push(data);
			}
		});
		return data_list;
	} catch (e) {
         alert('getDataPiList' + e.message);
    }
}
/**
 * print pi detail
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function piExport() {
	try {
		if ($('#table-pi').find('.check-all').is(':checked')) {
			var _pi_list = getDataPiList();
			$.ajax({
		        type        :   'POST',
		        url         :   '/export/pi-export',
		        dataType    :   'json',
		        data        :   {pi_list : _pi_list},
				loading		:	true,
		        success: function(res) {
		            if (res.response) {
		            	if (res.error_cd != '') {
		            		jMessage(res.error_cd);
		            	} else {
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
	    } else {
			jMessage('E003');
		}
	}  catch(e) {
        console.log('piExport' + e.message)
    }
}

function removeDuplicates(arr) {
    var obj = {};
    var ret_arr = [];
    for (var i = 0; i < arr.length; i++) {
        obj[arr[i]] = true;
    }
    for (var key in obj) {
        ret_arr.push(key);
    }
    return ret_arr;
}

/**
 * pi Export Output
 * 
 * @author : ANS342 - 2018/05/29 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function piExportOutput() {
	try{
		var data = {
	            'pi_date_from'   	: $('.TXT_pi_date_from').val(),
	            'pi_date_to'   		: $('.TXT_pi_date_to').val(),
	            'rcv_date_from'   	: $('.TXT_rcv_date_from').val(),
	            'rcv_date_to'   	: $('.TXT_rcv_date_to').val(),
	            'pi_no'   			: $('.TXT_pi_no').val(),
	            'rcv_no'   			: $('.TXT_rcv_no').val(),
	            'cust_nm'   		: $('.TXT_cust_nm').val(),
	            'pi_status_div'   	: $('.CMB_pi_status_div').val(),
	            page 				: 1,
				page_size 			: 0,
				'country_cd' 		: $('.TXT_country_cd').val().trim(),
				is_jp               : $('#check-box-different-jp').is(':checked') ? 1 : 0
	        };
		$.ajax({
			type 	: 'POST',
			url 	: '/export/pi-output',
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
        console.log('piExportOutput: ' + e.message)
    }
}