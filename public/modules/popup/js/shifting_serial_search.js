/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/03/28
 * 作成者		:	DaoNX
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	shifting
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

$(document).ready(function () {
	setHeader();
    getItemRequested();
	checkAll('check-all');
	initEvents();
});
/**
 * init Events
 * @author  :   DaoNX - 2018/03/27 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//sort column table
		$("#table-shifting").tablesorter({
			headers: { 
	            0: { 
	                sorter: false 
	            }
	        } 
	    }); 

		//click btn ok
 		$(document).on('click', '#btn-shifting-serial-ok', function() {
			var data        = getDataShiftingSerialList();
			var serial_list = 	'';
			var serial_leng =	'';
 			if (data !== '') {
 				serial_list = 	data;
 				serial_leng =	data.split(";").length;
 				parent.$('.popup-shiftingserial').find('.shifting_serial_cd').val(serial_leng);
 				parent.$('.popup-shiftingserial').next('.serial_list').val(serial_list);
 				// parent.$('.popup-shiftingserial').find('.TXT_move_qty').trigger('change');
 				_removeErrorStyle(parent.$('.popup-shiftingserial').find('.TXT_move_qty'));
 				 				
 				parent.$.colorbox.close();
 			} else {
 				parent.$('.popup-shiftingserial').find('.shifting_serial_cd').val('0');
 				parent.$('.popup-shiftingserial').next('.serial_list').val('');
 				parent.$.colorbox.close();
 				_removeErrorStyle(parent.$('.popup-shiftingserial').find('.TXT_move_qty'));
 			}
 		});

 		//click btn cancel
 		$(document).on('click', '#btn-shifting-serial-cancel', function() {
 			parent.$.colorbox.close();
 		});

 		// button search
		$(document).on('click', '#BTN_search', function() {
			try {
				$('.DSP_move_qty').text('0');
				search();
			} catch (e) {
				alert('#btn-search ' + e.message);
			}
		});

		//check, uncheck All
		$(document).on('click', '#check-all', function(){
			try {
				var numberChecked = 0;
				var isChecked     = $('#check-all').is(":checked");

				// process check
				$('.check-all').each(function(){
					if (isChecked) {
						$(this).prop('checked', true);
						numberChecked++;
					} else {
						$(this).prop('checked', false);
					}
				});

				// set qty
				$('.DSP_move_qty').text(numberChecked);
			} catch (e) {
				alert('#check-all: ' + e.message);
			}
		});

		//check, uncheck one -> all ?
		$(document).on('click', '.check-all', function(){
			try {
				var numberChecked = 0;
				var isChecked     = $(this).is(":checked");

				// process check
				if (isChecked) {
					var allRowIsChecked = true;
					$('.check-all:visible').each(function(){
						if (!$(this).is(":checked")) {
							allRowIsChecked = false;
						}
					});
					if (allRowIsChecked == true) {
						$('#check-all' ).prop('checked', true);
					}
					$(this).prop('checked', true);
				} else {
					$('#check-all' ).prop('checked', false);
				}

				// get qty
				$('.check-all').each(function(){
					if ($(this).is(":checked")) {
						$(this).prop('checked', true);
						numberChecked++;
					}
				});

				// set qty
				$('.DSP_move_qty').text(numberChecked);
			} catch (e) {
				alert('.check-all: ' + e.message);
			}
		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * search purchase request list detail
 * 
 * @author : ANS804 - 2018/02/21 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getItemRequested() {
	try {
		var move_no           = parent.$('.popup-shiftingserial').parents('body').find('#TXT_move_no').val().trim();
		var item_cd           = parent.$('.popup-shiftingserial').parents('tr').find('.TXT_item_cd').val().trim();
		var serial_list       = parent.$('.popup-shiftingserial').parents('tr').find('.serial_list').val().trim();
		var out_warehouse_div = parent.$('.popup-shiftingserial').parents('body').find('.TXT_out_warehouse_div').val().trim();
		
		var data              = {
			move_no 			: move_no,
			item_cd 			: item_cd,
			serial_list 		: serial_list,
			out_warehouse_div 	: out_warehouse_div,
		};

		$.ajax({
			type 		: 'POST',
			url 		: '/popup/search/shifting-serial/item-requested',
			dataType 	: 'json',
			data 		: data,
			loading 	: true,
			success: function(res) {
				if (res.response) {
					if (res.data.length > 0) {
						$('#TXT_count').val(res.count);
						$('#shifting-serial-list').html(res.html);
						$('#check-all').trigger('click');

						//sort clumn table
						$("#table-shifting").tablesorter({
							headers: { 
					            0: { 
					                sorter: false 
					            }
					        } 
					    });  
						_setTabIndex();
					}
				}

				$('#TXT_count').focus();
			}
		});
	} catch (e) {
         alert('search: ' + e.message);
    }
}
/**
 * search purchase request list detail
 * 
 * @author : ANS804 - 2018/02/21 - create
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
			url 		: '/popup/search/shifting-serial',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
			success: function(res) {
				if (res.response) {
					$('#shifting-serial-list').html(res.html);
					//sort clumn table
					$("#table-shifting").tablesorter({
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
         alert('search: ' + e.message);
    }
}
/**
 * get data for purchase request list search condition
 * 
 * @author : ANS804 - 2018/02/21 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataSearch() {
	try {
		var data = {
				'product_cd'   				: $('.DSP_product_cd').text().trim(),
	            'count'   					: ($('.TXT_count').val() != '') ? parseInt($('.TXT_count').val().trim().replace(',','')) : 0,
	            'manufacture_no_from'   	: $('.TXT_manufacture_no_from').val().trim(),
	            'manufacture_no_to'   		: $('.TXT_manufacture_no_to').val().trim(),
	            'serial_no_from'   			: $('.TXT_serial_no_from').val().trim(),
	            'serial_no_to'   			: $('.TXT_serial_no_to').val().trim(),
	            'out_warehouse_div'			: parent.$('.popup-shiftingserial').parents('body').find('.TXT_out_warehouse_div').val().trim(),
	        };
        return data;
	} catch (e) {
         alert('getDataSearch: ' + e.message);
    }
}
/**
 * get data get serial
 * 
 * @author : ANS804 - 2018/03/05
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataShiftingSerialList() {
	try {
		var data_list = '';
		$('#table-shifting tbody tr').each(function() {
			var isCheck = $(this).find('.check-all').is(':checked');
			if (isCheck) {
				var serial_no =	$(this).find('.DSP_serial_no').text().trim();
				
				if (data_list == '') {
					data_list = serial_no;
				} else {
					data_list = data_list + '; ' + serial_no
				}
			}
		});
		return data_list;
	} catch (e) {
         alert('getDataShiftingSerialList: ' + e.message);
    }
}
/**
 * get serial by product
 * 
 * @author : ANS804 - 2018/02/21 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setHeader() {
	try {
		var product_cd           = parent.$('.popup-shiftingserial').parents('tr').find('.TXT_item_cd').val().trim();
		var item_nm              = parent.$('.popup-shiftingserial').parents('tr').find('.DSP_item_nm').text().trim();
		var out_warehouse_div    = parent.$('.TXT_out_warehouse_div').val().trim();
		var out_warehouse_div_nm = parent.$('.TXT_out_warehouse_div').closest('.popup').find('.warehouse_nm').text().trim();
		var move_qty             = parent.$('.popup-shiftingserial').parents('tr').find('.TXT_move_qty').val().trim();

		$('.DSP_product_cd').text(product_cd);
		$('.DSP_item_nm_j').text(item_nm);
		$('.DSP_out_warehouse_div').text(out_warehouse_div);
		$('.DSP_out_warehouse_div_nm').text(out_warehouse_div_nm);
		$('.DSP_move_qty').text(move_qty);
	} catch (e) {
        alert('getSerial: ' + e.message);
    }
}

