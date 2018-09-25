/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	DungNN - ANS810
 *
 * 更新日		: 	2018/03/28
 * 更新者		: 	DaoNX - ANS804
 * 更新内容		: 	
 *
 * @package		:	SHIFTING-REQUEST-SEARCH
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

$(document).ready(function () {
	if (!sessionStorage.getItem('detail')) {
        sessionStorage.clear();
    }
	initCombobox();
	initEvents();
});

/**
 * initCombobox
 * @author  :   DaoNX - 2017/06/09 - create
 * @param
 * @return
 */
function initCombobox() {
	var name = 'JP';	
	//_getComboboxData(name, 'move_status_div');
}

/**
 * init Events
 * @author  :   DaoNX - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//sort clumn table
		$("#table-shifting").tablesorter({
			headers: { 
	            0: { 
	                sorter: false 
	            }
	        } 
	    });

		//init event check all for checkbox
		checkAll('check-all');

		// button search
		$(document).on('click', '#btn-search', function() {
			try {
				if (_checkDateFromTo('date-estimate') && _checkDateFromTo('date-from-to') ) {
					if(!_isBackScreen){
						_PAGE = 1;
					}
					search();
				}
			} catch (e) {
				console.log('#btn-search: ' + e.message);
			}
		});

		//init add new
		$(document).on('click', '#btn-add-new', function () {
			var param = {
				'mode'		: 'I',
				'from'		: 'ShiftingRequestSearch'
			};
			_postParamToLink('ShiftingRequestSearch', 'ShiftingRequestDetail', '/shifting/shifting-request-detail', param);
		});				

		//screen moving
		$(document).on('dblclick', '.table-shifting tbody tr', function() {
			if (!$(this).find('td').hasClass('dataTables_empty')) {
				var param = {
	 				'mode'				: 'U',
	 				'from'				: 'ShiftingRequest',
	 				'move_no'			: $(this).find('td.DSP_move_no').text().trim(),
	 			};	 			
	 			_postParamToLink('ShiftingRequestSearch', 'ShiftingRequestDetail', '/shifting/shifting-request-detail', param);
			}			
		});

 		//btn-approve
 		$(document).on('click', '#btn-approve', function(){
 			//
			if ($('#table-shifting').find('.check-all').is(':checked')) {					
				jMessage('C005', function(r) {
					if (r) {
						var arrNegative = arrayNegative();
						if (arrNegative.length > 0) {
							var messageC411 = _text['C411'].replace('{0}', arrNegative.length);
							jMessage_str('C411', messageC411, function(r) {
								if (r) {
									postApproved();
								}
							}, messageC411);
						} else {
							postApproved();
						}
					}
				});
			} else {
				jMessage('E003');
			}
 		});

 		// button export
		$(document).on('click', '#btn-export', function() {
			try {
				if (_checkDateFromTo('date-estimate') && _checkDateFromTo('date-from-to') ) {
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
		
		// btn-issue-instruction
 		$(document).on('click', '#btn-issue', function(){
 			try {
 				if ($('#table-shifting').find('.check-all').is(':checked')) {					
					jMessage('C004', function(r) {
						if (r) {
							postPrint();
						}
					});
				} else {
					jMessage('E003');
				}
 				
			} catch (e) {
				console.log('#btn-issue ' + e.message);
			}
 		});

		// Change 製造指示書番号
		$(document).on('change', '.TXT_manufacture_no', function() {
			try {
				var _this         	= $(this);
				var manufacture_no 	= $(this).val().trim();
				referManufacture(manufacture_no, _this);
			} catch (e) {
				console.log('change .TXT_manufacture_no: ' + e.message);
			}
		});

 		//change in warehouse div  
		$(document).on('change', '.TXT_out_warehouse_div', function() {
			try {
				var _this         = $(this);
				var warehouse_div =	$(this).val().trim();
				_referWarehouse(warehouse_div, _this, '', true);	   
			} catch (e) {
				console.log('change .TXT_out_warehouse_div: ' + e.message);
			}
		});

 		//change in warehouse div  
		$(document).on('change', '.TXT_in_warehouse_div', function() {
			try {
				var _this         = $(this);
				var warehouse_div =	$(this).val().trim();
				_referWarehouse(warehouse_div, _this, '', true);	   
			} catch (e) {
				console.log('change .TXT_in_warehouse_div: ' + e.message);
			}
		});

		//change paging 
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
				console.log('#page-size: ' + e.message);
			}
		});
	} catch (e) {
		console.log('initEvents: ' + e.message);
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
		var data = {
			item_cd    								: $.trim($('#item_cd').val()),
			manufacture_no  						: $.trim($('.TXT_manufacture_no').val()),
			move_no 								: $.trim($('#move_no').val()),
			register_date_from 						: $.trim($('.TXT_register_date_from').val()),
			register_date_to 						: $.trim($('.TXT_register_date_to').val()),
			desire_date_move_from  					: $.trim($('.TXT_desire_date_move_from').val()),
			desire_date_move_to  					: $.trim($('.TXT_desire_date_move_to').val()),
			out_warehouse_div 						: $.trim($('.TXT_out_warehouse_div').val()),
			in_warehouse_div 						: $.trim($('.TXT_in_warehouse_div').val()),
			CMB_move_status_div 					: $.trim($('.CMB_move_status_div').val()),
			page 									: _PAGE,
			page_size 								: _PAGE_SIZE
		};

		$.ajax({
			type 		: 'POST',
			url 		: '/shifting/shifting-request-search/search',
			dataType 	: 'json',
			data 		: data,
			loading 	: true,
			success 	: function(res) {
				// Do something here
				$('#div-shifting-list').html('');
				$('#div-shifting-list').html(res.html);
				formatNumberForMoveQty();
				$("#table-shifting").tablesorter({
					headers: { 
			            0: { 
			                sorter: false 
			            }
			        }
				});

				// run again tooltip
				$(function () {
				  $('[data-toggle="tooltip"]').tooltip()
				});

				//
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
 * output excel
 * 
 * @author : ANS810 - 2018/03/28 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function outputExcel() {
	try {
		var data = {
			item_cd    								: $.trim($('#item_cd').val()),
			manufacture_no  						: $.trim($('.TXT_manufacture_no').val()),
			move_no 								: $.trim($('#move_no').val()),
			register_date_from 						: $.trim($('.TXT_register_date_from').val()),
			register_date_to 						: $.trim($('.TXT_register_date_to').val()),
			desire_date_move_from  					: $.trim($('.TXT_desire_date_move_from').val()),
			desire_date_move_to  					: $.trim($('.TXT_desire_date_move_to').val()),
			out_warehouse_div 						: $.trim($('.TXT_out_warehouse_div').val()),
			in_warehouse_div 						: $.trim($('.TXT_in_warehouse_div').val()),
			CMB_move_status_div 					: $.trim($('.CMB_move_status_div').val()),
			page 									: 1,
			page_size 								: 0
		};
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/shifting-request-search/export-excel',
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
 * Update database and print list
 * 
 * @author : ANS810 - 2018/03/29 - create
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
	        url         :   '/export/shifting-request-search/export-excel-list',
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
        console.log('postPrint' + e.message);
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

		$('#table-shifting tbody tr').each(function() {
			var isCheck = $(this).find('.check-all').is(':checked');
			if (isCheck) {
				var _data = {
					move_no		: 	$(this).find('.DSP_move_no').text().trim()
				};
				update_list.push(_data);
			}
		});

		var data = {
			update_list		: 	update_list
		};
		return data;
    } catch (e) {
        console.log('getDataUpdateDB: ' + e.message);
    }
}

/**
 * Update database for approved shifting request list
 * 
 * @author : ANS810 - 2018/03/30 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postApproved() {
	try {
		var data 	=	getDataApproved();
		
		$.ajax({
	        type        :   'POST',
	        url         :   '/shifting/shifting-request-search/approved',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		//
	            		jMessage('I005', function(r){
	            			if(r) {
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
	} catch (e) {
        console.log('postApproved: ' + e.message);
    }
}

/**
 * get data approved
 *
 * @author      :   ANS804 - 2018/04/11 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function getDataApproved() {
	try {
		var data_approved   = [];

		$('#table-shifting tbody tr').each(function() {
			var isCheck = $(this).find('.check-all').is(':checked');
			if (isCheck) {
				var data = {
					move_no				: 	$(this).find('#move_no_hidden').val().trim(),
					out_warehouse_div 	: 	$(this).find('#out_warehouse_div_hidden').val().trim(),
					in_warehouse_div 	: 	$(this).find('#in_warehouse_div_hidden').val().trim(),
					move_detail_no 		: 	$(this).find('#move_detail_no_hidden').val().trim(),
					item_cd				: 	$(this).find('#item_cd_hidden').val().trim(),
					move_qty			: 	$(this).find('#move_qty_hidden').val().trim(),
					detail_remarks 		: 	$(this).find('#detail_remarks_hidden').val().trim(),
				};
				data_approved.push(data);
			}
		});

		var data = {
			data_approved		: 	data_approved
		};
			
		return data;
    } catch (e) {
        console.log('getDataApproved: ' + e.message);
    }
}

/**
 * check quatity negative
 * 
 * @author      :   ANS804 - 2018/03/30 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function arrayNegative() {
	try {
		var arrList     = [];
		var arrNegative = [];

		$('#table-shifting tbody tr').each(function(index, element) {
			var stock_available_qty = parseInt($(this).find('#stock_available_qty_hidden').text().trim().replace(',',''));
			var move_qty            = parseInt($(this).find('#move_qty_hidden').val().trim().replace(',',''));
			var obj                 = {
				index  					: index + 1,
				stock_available_qty 	: stock_available_qty,
				move_qty				: move_qty
			};
			arrList.push(obj);
		});

		arrNegative = arrList.filter(function(value,index,arr){
			var result = value.stock_available_qty - value.move_qty
			return result < 0
		});

		return arrNegative;
		
	} catch (e) {
		console.log('arrayNegative: ' + e.message);
	}
}

/**
 *refer data manufacture
 * 
 * @author : ANS804 - 2017/12/26 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referManufacture(data, element) {
	try {
		$.ajax({
			type 		: 'GET',
			url 		: '/common/refer/refer-manufacture',
			dataType	: 'json',
			data 		: {manufacture_no : data},
			success: function(res) {
				if (res.response) {
					//remove error
					element.parents('.popup').find('.manufacturinginstruction_cd').val(res.data.manufacture_no);
				}

				//element.parents('.popup').find('.manufacture_cd').focus();
			}
		});
		
	} catch(e) {
        console.log('referManufacture: ' + e.message)
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
 * format Number For Move Qty to type #,###
 * 
 * @author : ANS804 - 2017/12/26 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function formatNumberForMoveQty() {
	try {
		// format number
		$('.DSP_move_qty').each(function(){
			var value = $(this).text().trim();
			$(this).text(formatNumber(value));
		});
	} catch (e) {
		console.log('formatNumberForMoveQty: ' + e.message)
	}
}