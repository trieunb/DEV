/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	Trieunb
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	Component list search
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

$(document).ready(function () {
	initEvents();
});

/**
 * init Events
 * @author  :   Trieunb - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		// button search
		$(document).on('click', '#btn-search', function() {
			try {
				if (!_isBackScreen) {
					_PAGE = 1;
				}
				search();
			} catch (e) {
				alert('#btn-search ' + e.message);
			}
		});	
		//init add new
		$(document).on('click', '#btn-add-new', function () {
			var param = {
				'mode'		: 'I',
				'from'		: 'ComponentListSearch',
				'is_new'	: true
			};
			_postParamToLink('ComponentListSearch', 'ComponentListDetail', '/master/component-list-detail', param)
		});

		//screen moving
		$(document).on('dblclick', '#table-component-list tbody tr', function(){
			if (!$(this).find('td').hasClass('dataTables_empty')) {
				var parent_item_cd = $(this).find('.parent_item_cd').text().trim();
				var child_item_cd = $(this).find('.child_item_cd').text().trim();
	 			var param = {
	 				'mode'					: 'U',
	 				'from'					: 'ComponentListSearch',
	 				'parent_item_cd'		: parent_item_cd,
	 				'child_item_cd'			: child_item_cd,
	 			};
	 			_postParamToLink('ComponentListSearch', 'ComponentListDetail', '/master/component-list-detail', param)
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
 		//change TXT_parent_item_cd 
		$(document).on('change', '.TXT_parent_item_cd', function() {
			var data = {
				'item_cd'		: 	$.mbTrim($('.TXT_parent_item_cd').val()),
			}
			referMItem(data, $(this), '', isSearch = true);
		});
		//change TXT_child_item_cd 
		$(document).on('change', '.TXT_child_item_cd', function() {
			var data = {
				'item_cd'		: 	$.mbTrim($('.TXT_child_item_cd').val()),
			}
			referMItem(data, $(this), '', isSearch = true);
		});
		//btn print
 		$(document).on('click', '#btn-export', function(){
 			if (_checkDateFromTo('date-from-to')) {
				jMessage('C007',  function(r) {
					if (r) {
						componentListOutput();
					}
				});
			}
		 });
		//btn upload
		$(document).on('click', '#btn-upload', function () {
			jMessage('C009', function (r) {
				if (r) {
					var input = $('#upload-excel');
					input.trigger('click'); // opening dialog

					document.body.onfocus = function () {
						setTimeout(function () {
							if (input.val().length > 0) {
								var url = "/master/component-list-search/upload";
								_ImportExcel(input, url, null, function(filePath) {
									if (filePath) {
										location.href = filePath;
									}
								});
							}
						}, 100);
						document.body.onfocus = null;
					};

				}
			});
		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * search component list detail
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
			url 		: '/master/component-list-search/search',
			dataType 	: 'json',
			data 		: data,
			loading		: true,
			success: function(res) {
				if (res.response) {
					$('#component-list').html(res.html);
					//sort clumn table
					$("#table-component-list").tablesorter();
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
 * get data for component list search condition
 * 
 * @author : ANS806 - 2017/12/20 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataSearch() {
	try {
		var data = {
				'parent_item_cd'   	: $.mbTrim($('.TXT_parent_item_cd').val()),
	            'child_item_cd'   	: $.mbTrim($('.TXT_child_item_cd').val()),
	            page 				: _PAGE,
				page_size 			: _PAGE_SIZE
	        };
        return data;
	} catch (e) {
         alert('getDataSearch' + e.message);
    }
}
/**
 * component List Output
 * 
 * @author : ANS342 - 2018/05/29 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function componentListOutput() {
	try{
		var data = {
				'parent_item_cd'   	: $.mbTrim($('.TXT_parent_item_cd').val()),
	            'child_item_cd'   	: $.mbTrim($('.TXT_child_item_cd').val()),
	            page 				: 1,
				page_size 			: 0
	        };
		$.ajax({
			type 	: 'POST',
			url 	: '/export/component-list-search/output',
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
        console.log('componentListOutput: ' + e.message)
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
