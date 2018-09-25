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
 * @author  :   DaoNX - 2018/05/04 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		$(document).on('click', '#check-all', function(){
			try {
				if($(this).is(':checked')) {
					$('.check-all') .each(function(){
						$(this).prop('checked', true);
						$(this).parents('tr.tr-table').find('.parts_receipt_qty').val($(this).parents('tr.tr-table').find('.parts_not_yet_receipt_qty').text().trim());
						$(this).parents('tr.tr-table').find('.parts_receipt_qty').trigger('change');
					})
				} else {
					$('.check-all').prop('checked', false);
				}
			} catch(e) {
				console.log('click .check-all: ' + e);
			}
		});

		$(document).on('click', '.check-all', function(){
			try {
				var row = $(this).parents('tr.tr-table');
				if($(this).is(':checked')) {
					row.find('.parts_receipt_qty').val(row.find('.parts_not_yet_receipt_qty').text().trim());
					row.find('.parts_receipt_qty').trigger('change');
					var allRowIsChecked = true;
					$('.check-all:visible').each(function(){
						if (!$(this).is(":checked")) {
							allRowIsChecked = false;
						}
					});
					if (allRowIsChecked == true) {
						$('#check-all').prop('checked', true);
					}
				}else{
					$('#check-all').prop('checked', false);
				}
			} catch(e) {
				console.log('click .check-all: ' + e);
			}
		});

 		$(document).on('click', '#btn-search', function() {
 			try {
				if ( _checkDateFromTo('date-from-to') ) {
					if(!_isBackScreen){
						_PAGE = 1;
					}
					search();
				}
			} catch (e) {
				console.log('#btn-search: ' + e.message);
			}
 		});

		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				_clearErrors();

				if(validate($('body'))){
					if ($('#table-stocking').find('.check-all').is(':checked')) {
							if (checkQty()) {
								jMessage('C005', function(r) {
									if (r) {
										saveStockingDetail();
									}
								});
							} else {
								jMessage('E380', function(r){
									if(r) {
										raiseE380(checkQty('raiseError'));
									}
								});
							}
					} else {
						jMessage('E003');
					}
				}
			} catch (e) {
				console.log('#btn-save: ' + e.message);
			}
		});

 		// button export
		$(document).on('click', '#btn-export', function() {
			try {
				if (_checkDateFromTo('date-from-to') ) {
					jMessage('C007', function(r) {
						if (r) {
							outputExcel();
						}
					});
				}				
			} catch (e) {
				console.log('#btn-export: ' + e.message);
			}
		});

 		//change TXT_parts_cd 
		$(document).on('change', '.TXT_parts_cd', function() {
			try {
				var data = {
					'item_cd'		: 	$('.TXT_parts_cd').val(),
				}
				_referMItem(data, $(this), '', true);
			} catch (e) {
				console.log('.TXT_parts_cd: ' + e.message);
			}
		});

 		// calculate price
		$(document).on('change', '.parts_receipt_qty', function() {
			try {
				var row                          = $(this).parents('tr');
				
				var parts_purchase_actual_amount = row.find('.parts_purchase_actual_amount').val().trim();
				
				var parts_receipt_qty            = row.find('.parts_receipt_qty').val().trim().replace(/,/g,'') != '' ? parseInt(row.find('.parts_receipt_qty').val().trim().replace(/,/g,'')) : 0;
				var unit_price                   = row.find('.unit_price').text().trim().replace(/,/g,'') != '' ? parseFloat(row.find('.unit_price').text().trim().replace(/,/g,'')) : 0;

				if (!!parts_receipt_qty && !!unit_price) {
					var purchase_detail_amt_round_div = $('#purchase_detail_amt_round_div').val().trim();
					// var tax_rate                      = !isNaN(parseFloat(row.find('.tax_rate').val().trim())) ? parseFloat(row.find('.tax_rate').val().trim()) : 0;
					
					parts_purchase_actual_amount      = parts_receipt_qty*unit_price ;
					// parts_purchase_actual_amount      = parts_receipt_qty*unit_price*(100+tax_rate)/100 ;

					row.find('.parts_purchase_actual_amount').removeErrorStyle();
					row.find('.parts_purchase_actual_amount').removeClass('error-item').removeAttr('index');
					row.find('.parts_purchase_actual_amount').val(formatNumber(_roundNumeric(parts_purchase_actual_amount, purchase_detail_amt_round_div, 0)));
				} else {
					row.find('.parts_purchase_actual_amount').val(0);
				}

			} catch (e) {
				console.log('.parts_receipt_qty: ' + e.message);
			}
		});	

 		//don't fill negative number
		$(document).on('keypress', '.parts_receipt_qty', function(e) {
			try {
				var key = e.which || e.keyCode || 0
				// check type -
				if (key == 45) {
					e.preventDefault();
				}
			} catch (e) {
				console.log('keypress .parts_receipt_qty: ' + e.message);
			}
		});

		//change paging 
		$(document).on('click', '#paginate li button', function() {
			try {
	 			_PAGE = $(this).data('page');
	 			search();
			} catch (e) {
				console.log('#page-size: ' + e.message);
			}
 		});

 		//change paging 
		$(document).on('change', '#page-size', function() {
			try {
				if ($('#table-result').find('td.w-popup-nodata').length == 0){
					_PAGE_SIZE = ($('.nav-pagination').find('.pagi-fillter').length > 0) ? $('#page-size').val() : 10;
					_PAGE 	   = 1
					search();
				}
			} catch (e) {
				console.log('#page-size: ' + e.message);
			}
		});
	} catch (e) {
		console.log('initEvents: ' + e.message);
	}
}

/**
 * get data search
 * 
 * @author : ANS810 - 2018/05/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataSearch(action){
	try {
		return {
			parts_cd    			: $.trim($('.TXT_parts_cd').val()),
			supplier_nm  			: $.trim($('.TXT_supplier_nm').val()),
			parts_order_no 			: $.trim($('.TXT_parts_order_no').val()),
			manufacture_no 			: $.trim($('.TXT_manufacture_no').val()),
			parts_order_date_from  	: $.trim($('.TXT_parts_order_date_from').val()),
			parts_order_date_to  	: $.trim($('.TXT_parts_order_date_to').val()),
	        page 					: _PAGE,
			page_size 				: typeof action == 'undefined' ? _PAGE_SIZE : action
		}
	} catch (e) {
		console.log('getDataSearch: ' + e);
	}
}

/**
 * Search packing list
 * 
 * @author : ANS810 - 2018/03/28 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try{
		var data       = getDataSearch(_PAGE_SIZE);

		$.ajax({
			type 		: 'POST',
			url 		: '/stocking/stocking-detail/search',
			dataType 	: 'json',
			data 		: data,
			loading 	: true,
			success 	: function(res) {
				// Do something here
				$('#div-stock-list').html('');
				$('#div-stock-list').html(res.html);

				if(!!res.round[0].purchase_detail_amt_round_div) {
					$('#purchase_detail_amt_round_div').val(res.round[0].purchase_detail_amt_round_div);
				}
				// run again tooltip
				$(function () {
				  $('[data-toggle="tooltip"]').tooltip();
				});
				
				_setTabIndex();
				setBackgroundForTable();
			}
		}).done(function(res){
			initHoverTr();
			_postSaveHtmlToSession();
		});
	} catch(e) {
        console.log('search: ' + e.message)
    }
}

/**
 * output excel
 * 
 * @author : ANS810 - 2018/05/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function outputExcel() {
	try {
		var data = getDataSearch(0);

		$.ajax({
	        type        :   'POST',
	        url         :   '/export/stocking-detail/export-excel',
	        dataType    :   'json',
	        data        :   data,
	        loading     :   true,
	        success: function(res) {
	            if (res.response) {
	            	jMessage('I008');
	            	location.href = res.filename;
	            } else {
	            	jMessage('W001');
	            }
	        },
	    });
	} catch(e) {
        console.log('outputExcel: ' + e.message)
    }
}

/**
 * set background for row in table
 * 
 * @author : ANS810 - 2018/05/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setBackgroundForTable() {
	try {
		$('#table-stocking tbody tr.tr-table').each(function(){
			var index = parseInt($(this).attr('class').replace('tr-table index-',''));
			if (index % 2 == 0) {
				$(this).css('background-color','#FFF2CC');
			}
		});
	} catch(e) {
        console.log('setBackgroundForTable: ' + e.message)
    }
}

/**
 * get data of input
 * 
 * @author      :   ANS804 - 2018/05/09 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 * @see :
 */
function getDataSave() {
	try {
		var data_save   = [];

		$('#table-stocking tbody tr.tr-table.row1').each(function() {
			var isCheck           = $(this).find('.check-all').is(':checked');

			if (isCheck) {
				var data = {
					// line 1
					parts_order_detail_no			: 	$(this).find('.parts_order_detail_no').val().trim(),
					parts_cd						: 	$(this).find('.parts_cd').text().trim(),
					parts_receipt_qty				: 	$(this).find('.parts_receipt_qty').val().trim().replace(/,/g,''),
					unit_price 						: 	$(this).find('.unit_price').text().trim().replace(/,/g,''),
					parts_purchase_actual_amount 	: 	!isNaN(parseFloat($(this).find('.parts_purchase_actual_amount').val().trim().replace(/,/g,''))) ? $(this).find('.parts_purchase_actual_amount').val().trim().replace(/,/g,'') : 0,
					//line 2
					parts_order_no					: 	$(this).next('.tr-table').find('.parts_order_no').text().trim(),
					remarks							: 	$(this).next('.tr-table').find('.remarks').val().trim(),

				};
				data_save.push(data);
			}
		});

		var data = {
			data_save		: 	data_save
		};
			
		return data;
    } catch (e) {
        console.log('getDataSave: ' + e.message);
    }
}

/**
 * save stocking-detail
 * 
 * @author      :   ANS804 - 2018/05/09 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function saveStockingDetail() {
	try{
		var data               = getDataSave();
		data.in_warehouse_date = $('#input_warehouse_date').val();
		
	    $.ajax({
	        type        :   'POST',
	        url         :   '/stocking/stocking-detail/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		jMessage('I005', function(r){
		                	if(r){
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
	} catch(e) {
        console.log('saveStockingDetail: ' + e.message)
    }
}

/**
 * comparison
 * 
 * @author      :   ANS804 - 2018/05/09 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function checkQty(status) {
	try {
		var status    = typeof status != 'undefined' ? status : '';
		var error     = 0;
		var dataError = [];
		$(document).find('#table-stocking tbody tr').each(function(index){
			if($(this).find('.check-all').is(":checked")) {
				var parts_not_yet_receipt_qty = parseInt($(this).find('.parts_not_yet_receipt_qty').text().trim().replace(/,/g,''));
				var sum_purchase_qty          = parseInt($(this).find('.parts_receipt_qty').val().trim().replace(/,/g,''));

				if (sum_purchase_qty > parts_not_yet_receipt_qty) {
					error++;
					//return arr index error
					dataError.push(index);
				}
			}
		});

		if (status == 'raiseError') {
			return dataError;
		}

		if (error > 0) return false;

		return true;
	} catch(e) {
        console.log('checkQty: ' + e.message)
    }
}

/**
 * raiseE380
 * 
 * @author      :   ANS804 - 2018/05/09 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function raiseE380(arr) {
	try {
		$.each(arr,function(index, value){
			$(document).find('#table-stocking tbody tr:eq('+value+') .parts_receipt_qty').errorStyle(_text['E380']);
		});
	} catch(e) {
		console.log('raiseE380: ' + e.message)
	}
}

/**
 * validate
 *
 * @author      :   ANS804 - 2018/03/30 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function validate(element) {
	var error = 0;
	try {
		_clearErrors();
		element.find('.required').each(function() {
			var value = $.trim($(this).val());

			if($(this).is('#input_warehouse_date')) {
				if(value == '' ) {
					$(this).errorStyle(_MSG_E001);
					error ++;
				}
			} else {
				if ($(this).parents('tr').find('.check-all').is(':checked')){
					if(value == '' || value == 0) {
						$(this).errorStyle(_MSG_E001);
						error ++;
					}
				}
				
			}
				
		});

		$('input.error-item:first').focus();

		if( error > 0 ) {
			return false;
		} else {
			return true;
		}
	} catch(e) {
		console.log('validate: ' + e.toString());
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

/**
 * hover row
 * 
 * @author : ANS804 - 2018/05/25 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function initHoverTr() {
	try {
		$(document).find("table tbody tr").hover(function(){
				if($(this).is('.row1')) {
					$(this).addClass('background-choice');
					$(this).next('.row2').addClass('background-choice');
				} else {
					$(this).addClass('background-choice');
					$(this).prev('.row1').addClass('background-choice');
				}
		    }, function(){
		    	if($(this).is('.row1')) {
					$(this).removeClass('background-choice');
					$(this).next('.row2').removeClass('background-choice');
				} else {
					$(this).removeClass('background-choice');
					$(this).prev('.row1').removeClass('background-choice');
				}
		});
	} catch (e) {
		console.log('initHoverTr: ' + e.message)
	}
}

/**
 * set width when refer item
 * 
 * @author : ANS804 - 2018/06/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setWidthTextRefer(){
	try {
		$('.componentproduct_nm').width('auto');
		var arr = [];
		$('.componentproduct_nm').each(function(index, element){
			var data = {
				index 			: index,
				element 		: $(this),
				currentWidth 	: $(this)[0].getBoundingClientRect().width
			}
			arr.push(data);
		});

		var arrWidthMax = arr.reduce(function(accumulator, currentValue, index, arr) {
			if (currentValue.currentWidth > accumulator.currentWidth) {
				return currentValue
			} else {
				return accumulator
			}
		});

		$('.componentproduct_nm').width(arrWidthMax.currentWidth);
	} catch (e) {
		console.log('setWidthTextRefer' + e.message)
	}
}