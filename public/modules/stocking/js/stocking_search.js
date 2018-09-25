/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/02/13
 * 作成者		:	ANS804
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	COMPONENT ORDER
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

$(document).ready(function () {
    if (!sessionStorage.getItem('detail')) {
        sessionStorage.clear();
    }
	initEvents();
});
/**
 * init Events
 * @author  :   ANS804 - 2018/02/13 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		// button search
		$(document).on('click', '#btn-search', function(e) {
			try {
				if (_checkDateFromTo('date-order') && _checkDateFromTo('date-purchase')) {
					if(!_isBackScreen){
						_PAGE = 1;
					}
					search();
				}
			} catch (e) {
				console.log('#btn-search ' + e.message);
			}
		});
		//click line table stocking search
 		$(document).on('dblclick', '.table-stocking-search tbody tr', function(){
            try {
     			if (!$(this).find('td').hasClass('dataTables_empty')) {
    	 			var mode 	= 'U';
    	 			var parts_order_no 	    = $.mbTrim($(this).find('.DSP_parts_order_no').text());
                    var purchase_no         = $.mbTrim($(this).find('.DSP_purchase_no').text());
                    var purchase_detail_no  = $.mbTrim($(this).find('.DSP_purchase_detail_no').text());
    	 			var param 			= {
    	 				'mode'				: mode,
    	 				'from'				: 'StockingSearch',
    	 				'parts_order_no'	: parts_order_no,
                        'purchase_no'       : purchase_no,
                        'purchase_detail_no': purchase_detail_no
    	 			};

    	 			_postParamToLink('StockingSearch', 'StockingUpdate', '/stocking/stocking-update', param);
     			}
            } catch (e) {
                console.log('.table-stocking-search tbody tr: ' + e.message);
            }
 		});
		// button export
		$(document).on('click', '#btn-export', function() {
			try {
				if (_checkDateFromTo('date-order') && _checkDateFromTo('date-purchase')) {
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
 		//change paging 
		$(document).on('change', '#page-size', function() {
			try {
				_PAGE_SIZE = ($('.nav-pagination').children('.pagi-fillter').length > 0) ? $('#page-size').val() : 10
				_PAGE = 1;
				search();
			} catch (e) {
				console.log('#page-size' + e.message);
			}
		});
 		//click paging
		$(document).on('click', '#paginate li button', function() {
            try {
     			_PAGE = $(this).data('page');
     			search();
            } catch (e) {
                console.log('#paginate li button: ' + e.message);
            }
 		});
 		//change 仕入先コード 
		$(document).on('change', '.TXT_supplier_cd', function() {
			try {
				_getClientName($.mbTrim($(this).val()), $(this), '', true);
			} catch (e) {
				console.log('.TXT_supplier_cd: ' + e.message);
			}
		});
		//change TXT_parts_cd 
		$(document).on('change', '.TXT_parts_cd', function() {
			var data = {
				'item_cd'		: 	$.mbTrim($('.TXT_parts_cd').val()),
			}
			referMItem(data, $(this), '', isSearch = true);
		});
	} catch (e) {
		console.log('initEvents: ' + e.message);
	}
}
/**
 * search stocking list
 * 
 * @author : ANS796 - 2018/06/26 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
	try {
		var data = getDataSearch(_PAGE_SIZE);
		$.ajax({
			type 		: 'POST',
			url 		: '/stocking/stocking-search',
			dataType 	: 'json',
			data 		: data,
            loading     : true,
			success: function(res) {
				if (res.response) {
					$('#stocking-search-list').html(res.html);
					//sort clumn table
					$("#table-stocking-search").tablesorter();
					_setTabIndex();
				}
			}
		}).done(function(res){
			_postSaveHtmlToSession();
		});
	} catch (e) {
         console.log('search: ' + e.message);
    }
}
/**
 * get Data Search
 * 
 * @author : ANS796 - 2018/06/26 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataSearch(action) {
	try {
		var data = {
	            'parts_order_date_from'   	: $('.TXT_parts_order_date_from').val(),
	            'parts_order_date_to'   	: $('.TXT_parts_order_date_to').val(),
	            'supplier_cd'   			: $.mbTrim($('.TXT_supplier_cd').val()),
	            'parts_cd' 		  			: $.mbTrim($('.TXT_parts_cd').val()),
                'purchase_date_from'        : $('.TXT_purchase_date_from').val(),
                'purchase_date_to'          : $('.TXT_purchase_date_to').val(),
                'parts_order_no'            : $.mbTrim($('.TXT_parts_order_no').val()),
                'manufacture_no'            : $.mbTrim($('.TXT_manufacture_no').val()),
	            page 						: _PAGE,
				page_size 					: typeof action == 'undefined' ? _PAGE_SIZE : action
	        };
        return data;
	} catch (e) {
        console.log('getDataSearch: ' + e.message);
    }
}
/**
 * output Excel
 * 
 * @author : ANS796 - 2018/06/26 - create
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
            url         :   '/export/stocking-search-output',
            dataType    :   'json',
            data        :   data,
            loading     :   true,
            success: function(res) {
                if (res.response) {
                    jMessage('I008', function(r) {
                        if(r) {
                            location.href = res.filename;
                        }
                    });
                } else {
                    jMessage('W001');
                }
            },
        });
    }  catch(e) {
        console.log('outputExcel:' + e.message)
    }
}
/**
 *refer m item
 * 
 * @author : ANS806 - 2017/12/20 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referMItem(data, element, callback, isSearch) {
	try{
		if (isSearch == undefined) {
			isSearch = false;
		}
		$.ajax({
			type 		: 'GET',
			url 		: '/common/refer/refer-item',
			dataType	: 'json',
			data 		: data,
			success: function(res) {
				if (res.response) {
					//remove error
                	_removeErrorStyle(element.parents('.popup').find('.componentproduct_cd'));
					element.parents('.popup').find('.componentproduct_cd').val(res.data.item_cd);
					element.parents('.popup').find('.componentproduct_nm').text(res.data.item_nm);
					element.parents('.popup').find('.specification').text(res.data.specification);
					setWidthTextRefer();
				} else {
					if (!isSearch) {
						// element.parents('.popup').find('.componentproduct_cd').val('');
					}
					element.parents('.popup').find('.componentproduct_nm').text('');
					element.parents('.popup').find('.specification').text('');
				}
				//element.parents('.popup').find('.componentproduct_cd').focus();

				// check callback function
				if (typeof callback == 'function') {
					callback();
				}
			}
		});
		
	} catch(e) {
        console.log('referMItem' + e.message)
    }
}
