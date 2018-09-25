/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/03/06
 * 作成者		:	Trieunb
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	shipment
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
	
	//_getComboboxData(name, 'production_status_div');
	//_getComboboxData(name, 'serial_forward_div');
}

/**
 * init Events
 * @author  :   DuyTP - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		referSourceScreen();
		// getSerial(rcv_no, product_cd);
		//sort clumn table
		$("#table-shipment").tablesorter({
			headers: { 
	            0: { 
	                sorter: false 
	            }
	        } 
	    });

		//init event check all for checkbox
		checkAll('check-all');
		
		//click btn ok
 		$(document).on('click', '#btn-shipment-serial-ok', function() {
 			var fwd_status 	= parent.$('.popup-shipmentserial').parents('body').find('.STT_temp').text().trim();
 			//if fwd_status != 10 do not set fwd_qty (出荷指示数)
 			if(fwd_status != '10'){
 				parent.$.colorbox.close();
 			} else {
	 			var shipment_serial_id 	= $(".intruction_number").text().trim();
	 			var data 				= getDataShipmentSerialList();
	 			var serial_list 		= 	'';
	 			var serial_leng 		=	'';
	 			if (data !== '') {
	 				serial_list = 	data;
	 				serial_leng =	data.split(";").length;
	 				parent.$('.popup-shipmentserial').find('.shipment_serial_cd').val(serial_leng);
	 				parent.$('.popup-shipmentserial').parent().find('.list_serial').text(serial_list);
	                parent.$('.popup-shipmentserial').find('.shipment_serial_cd').trigger('change');

	 				
	 				parent.$.colorbox.close();
	 			}
 			}
 		});
 		//click btn cencel
 		$(document).on('click', '#btn-shipment-serial-cencel', function() {
 			parent.$.colorbox.close();
 		});
 		// button search
		$(document).on('click', '#BTN_search', function() {
			try {
				$(".DSP_fwd_qty").text('0');
				search();
			} catch (e) {
				console.log('#btn-search ' + e.message);
			}
		});
		// button search
		$(document).on('click', '.check-all, #check-all', function() {
			try {
				var sumcheck = ($('#table-shipment tbody').find('input[type="checkbox"]:checked').length);
				$(".DSP_fwd_qty").text(formatNumber(sumcheck));
			} catch (e) {
				console.log('.check-all, #check-all ' + e.message);
			}
		});
		
		var mode = parent.$('.popup-shipmentserial').parents('body').find('.TXT_mode').text().trim();
	    if(mode != 'I'){
		    searchIndex();
		}else{
			$('#BTN_search').trigger('click');
		}
	} catch (e) {
		console.log('initEvents: ' + e.message);
	}
}
/**
 * search purchase request list detail
 * 
 * @author : ANS806 - 2018/02/21 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function searchIndex() {
	try {
		var mode = parent.$('.popup-shipmentserial').parents('body').find('.TXT_mode').text().trim();
		var fwd_no = (mode == 'I') ? '' :  parent.$('.popup-shipmentserial').parents('body').find('#TXT_fwd_no').val().trim();
		var rcv_no 			= parent.$('.popup-shipmentserial').parents('body').find('#TXT_rcv_no').val().trim();
		var fwd_detail_no 	= parent.$('.popup-shipmentserial').parents('tr').find('.DSP_no').text().trim();
		
		var data = {
			fwd_no 			: fwd_no,
			rcv_no 			: rcv_no,
			fwd_detail_no 	: fwd_detail_no
		};
		$.ajax({
			type 		: 'POST',
			url 		: '/popup/search/shipment-serial/index',
			dataType 	: 'json',
			data 		: data,
			loading 	: true,
			success: function(res) {
				if (res.response) {
					$('#shipment-serial-list').html(res.html);
					$('#check-all').trigger('click');
					//sort clumn table
					$("#table-shipment").tablesorter({
						headers: { 
				            0: { 
				                sorter: false 
				            }
				        } 
				    });  
					_setTabIndex();
				}
			}
		});
	} catch (e) {
        console.log('search' + e.message);
    }
}
/**
 * search purchase request list detail
 * 
 * @author : ANS806 - 2018/02/21 - create
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
			url 		: '/popup/search/shipment-serial',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
			success: function(res) {
				if (res.response) {
					$('#shipment-serial-list').html(res.html);
					//sort clumn table
					$("#table-shipment").tablesorter({
						headers: { 
				            0: { 
				                sorter: false 
				            }
				        } 
				    });  
					_setTabIndex();
				}
			}
		});
	} catch (e) {
        console.log('search' + e.message);
    }
}
/**
 * get data for purchase request list search condition
 * 
 * @author : ANS806 - 2018/02/21 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataSearch() {
	var mode = parent.$('.popup-shipmentserial').parents('body').find('.TXT_mode').text().trim();
	var fwd_no = (mode == 'I') ? '' :  parent.$('.popup-shipmentserial').parents('body').find('#TXT_fwd_no').val().trim();
	try {
		var data = {
				'fwd_no'					: fwd_no,
				'product_cd'   				: $('.DSP_product_cd').text().trim(),
	            'count'   					: $('.TXT_count').val().trim().replace(/,/g,''),
	            'manufacture_no_from'   	: $('.TXT_manufacture_no_from').val().trim(),
	            'manufacture_no_to'   		: $('.TXT_manufacture_no_to').val().trim(),
	            'serial_no_from'   			: $('.TXT_serial_no_from').val().trim(),
	            'serial_no_to'   			: $('.TXT_serial_no_to').val().trim(),
	            'out_warehouse_div'			: parent.$('.popup-shipmentserial').parents('body').find('.out_warehouse_div').text().trim(),
	        };
        return data;
	} catch (e) {
        console.log('getDataSearch' + e.message);
    }
}
/**
 * get data get serial
 * 
 * @author : ANS806 - 2018/03/05
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataShipmentSerialList() {
	try {
		// var data_list = [];
		var data_list = '';
		$('#table-shipment tbody tr').each(function() {
			var isCheck = $(this).find('.check-all').is(':checked');
			if (isCheck) {
				var serial_no 	=	$(this).find('.DSP_serial_no').text().trim();
				var data = {
					serial_no 	: 	serial_no,
				};
				if (data_list == '') {
					data_list = serial_no;
				} else {
					data_list = data_list + '; ' + serial_no
				}
			}
		});
		return data_list;
	} catch (e) {
        console.log('getDataShipmentSerialList' + e.message);
    }
}
/**
 * refer data popup from source screen
 * 
 * @author : ANS831 - 2018/04/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
 function referSourceScreen(){
 	try {
		var mode = parent.$('.popup-shipmentserial').parents('body').find('.TXT_mode').text().trim();
 		var rcv_no 			= parent.$('.popup-shipmentserial').parents('body').find('.accept_cd').val().trim();
		var product_cd 		= parent.$('.popup-shipmentserial').parents('tr').find('.DSP_code').text().trim();
		var item_nm_j 		= parent.$('.popup-shipmentserial').parents('tr').find('.DSP_product_nm').text().trim();
		var qty 			= parent.$('.popup-shipmentserial').parents('tr').find('.DSP_rcv_amount').text().trim();
		var remaining_qty 	= parent.$('.popup-shipmentserial').parents('tr').find('.DSP_remain_amount').text().trim();
		var fwd_qty 		= parent.$('.popup-shipmentserial').parents('tr').find('.TXT_instructed_amount').val().trim();
		var remaining 		= parent.$('.popup-shipmentserial').parents('tr').find('.DSP_remaining').text().trim();
		// qty 				= qty.replace(/,/g,'');
		// remaining_qty 		= remaining_qty.replace(/,/g,'');
		// $("#TXT_count").val(qty-remaining_qty);
 		$('.DSP_product_cd').text(product_cd);
		$('.DSP_item_nm_j').text(item_nm_j);
		$('.DSP_qty').text(qty);
		$('.DSP_remaining_qty').text(remaining_qty);
		if(mode == 'I'){
			$("#TXT_count").val(remaining_qty);
		} else {
			$("#TXT_count").val(fwd_qty);
		}
		$('.DSP_fwd_qty').text(fwd_qty);
	} catch (e) {
     	console.log('referSourceScreen' + e.message);
    }
 }

/**
 * format number to type #,###
 * 
 * @author : ANS804 - 2017/12/26 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function formatNumber(number) {
	try {
		number  = number + '';
		var rgx = /(\d+)(\d{3})/;

	    while (rgx.test(number)) {
	        number = number.replace(rgx, '$1' + ',' + '$2');
	    }

	    return number;
	} catch (e) {
		console.log('formatNumber: ' + e.message)
	}
}
