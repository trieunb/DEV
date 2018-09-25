/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/01/04
 * 作成者		:	Trieunb
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	Pi Order Confirm
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */
$(document).ready(function () {
	initEvents();
});
/**
 * init Events
 * @author  :   Trieunb - 2018/01/04 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//init event check all for checkbox
		checkAll('check-all');
		//screen moving
		$(document).on('dblclick', '#pi-order tbody tr', function(){
			if (!$(this).find('td').hasClass('dataTables_empty')) {
	 			var pi_no 	= $(this).find('.DSP_pi_no').text().trim();
	 			var param = {
	 				'mode'		: 'A',
	 				'from'		: 'OrderConfirmSearch',
	 				'pi_no'		: pi_no,
	 			};
	 			_postParamToLink('OrderConfirmSearch', 'PiDetail', '/pi/pi-detail', param);
 			}
		});
 		// button search
		$(document).on('click', '#btn-search', function() {
			try {
				if (_checkDateFromTo('order-confirm-date')) {
					if(!_isBackScreen){
						_PAGE = 1;
					}
					search();
				}
			} catch (e) {
				alert('#btn-search ' + e.message);
			}
		});
		// button btn-save
		$(document).on('click', '#btn-save', function() {
			try {
				if(_validate()){
					if ($('#pi-order').find('.check-all').is(':checked')) {
						jMessage('C001', function(r) {
							if (r) {
								savePiOrderConfirm();
							}
						});
					} else {
						jMessage('E003');
					}
				}
			} catch (e) {
				alert('#btn-save ' + e.message);
			}
		});
		//change paging 
		$(document).on('change', '#page-size', function() {
			try {
				_PAGE_SIZE = ($('.wrrap-pagi-fillter').children('.pagi-fillter').length > 0) ? $('#page-size').val() : 10
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
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * search pi order confirm
 * 
 * @author : ANS806 - 2018/01/04 - create
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
			url 		: '/order/order-confirm/search',
			dataType 	: 'json',
			data 		: data,
			loading 	: true,
			success: function(res) {
				if (res.response) {
					$('#order-confirm-list').html(res.html);
					//sort clumn table
					$("#pi-order").tablesorter({
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
 * get data for pi order confirm by condition
 * 
 * @author : ANS806 - 2018/01/04 - create
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
	            'cust_nm'   		: $('.TXT_cust_nm').val(),
	            'pi_no'   			: $('.TXT_pi_no').val(),
	            page 				: _PAGE,
				page_size 			: _PAGE_SIZE
	        };
        return data;
	} catch (e) {
         alert('getDataSearch' + e.message);
    }
}
/**
 * save pi order confirm
 * 
 * @author : ANS806 - 2017/12/14 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function savePiOrderConfirm() {
	try {
		var data = getDataPiList();
		$.ajax({
	        type        :   'POST',
	        url         :   '/order/order-confirm/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	var msg = '';
	            	if (res.error_cd != '') {
	            		msg = res.error_cd;
	            	} else {
	            		msg = 'I007';
	            	}
	            	jMessage(msg, function(r) {
            			if (r) {
            				_PAGE = 1;
            				search();
            			}
            		});
	            } else {
	            	//catch DB error and display
	            	var msg_e999 = _text['E999'].replace('{0}', res.error);
	            	jMessage_str('E999', msg_e999, '', msg_e999);
	            }
	        },
	    });  
	} catch (e) {
         alert('savePiOrderConfirm' + e.message);
    }
}
/**
 * get data save pi order confirm
 * 
 * @author : ANS806 - 2018/01/04 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataPiList() {
	try {
		var data_list = [];
		$('#pi-order tbody tr').each(function() {
			var isCheck = $(this).find('.check-all').is(':checked');
			if (isCheck) {
				var data = {
					pi_no 			: 	$(this).find('.DSP_pi_no').text().trim(),
				};
				data_list.push(data);
			}
		});
		var data = {
			pi_list 	: 	data_list,
			rcv_date 	: 	$('.TXT_rcv_date').val().trim()
		}
		return data;
	} catch (e) {
         alert('getDataPiList' + e.message);
    }
}