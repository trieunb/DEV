/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/11/10
 * 作成者		:	Trieunb
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	LIBRARY MASTER
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

$(document).ready(function () {
	initEvents();
});
/**
 * init Events
 * @author  :   Trieunb - 2017/11/10 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		if (mode == 'U') {
			referLibrary();
			$("#btn-save").removeClass('disabled');
		} else {
			_disabldedAllInput();
			$("#btn-save").addClass('disabled');
		}
		//set tab index table
		setTabIndexTable('table-library');
		//init back
		$(document).on('click', '#btn-back', function () {
			sessionStorage.setItem('detail', true);
			location.href = '/system-management/library-master-search';
		});
		// remove row table
		$(document).on('click','.remove-row',function(e){
			var obj   = $(this);
			jMessage('C002', function(r) {
				if(r) {
					obj.closest('tr').remove();
				}
			});
			e.preventDefault();
		});
		//add row
		$(document).on('click', '#btn-add-row', function () {
			try {
				addNewRowTable('table-library');
				setMaxLenghLibCd();
			} catch (e) {
				alert('add new row' + e.message);
			}

		});
		//save
		$(document).on('click', '#btn-save', function () {
			try {
				var _row_detail = $('#table-library tbody tr').length;
				if(_row_detail > 0) {
					if (validate()) {
						jMessage('C001', function(r) {
							if(r) {
								save();
							}
						});
					}
				} else {
					jMessage('E004');
				}
			} catch (e) {
				alert('add new row' + e.message);
			}
		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * add new row table
 *
 * @author		:	Trieunb - 2017/08/28 - create
 * @params		:	null
 * @return		:	null
 */
function addNewRowTable(table) {
	try	{
		var row = $("#table-row tr").clone();	
		$('.'+ table + ' tbody').append(row);	
		setTabIndexTable(table)
		//set first forcus input in row
		$('.'+ table + ' tbody tr:last :input:first').focus();
	} catch (e) {
		alert('addNewRowTable: ' + e.message);
	}
}
/**
 * function set table index for table
 *
 * @author : Trieunb - 2017/11/10 - create
 * @author :
 * @return : true/false
 * @access : public
 * @see :
 */
function setTabIndexTable(table) {
	try	{
		var start 	= parseInt($('.'+table+' tr input:first').attr('tabindex'));
		if (isNaN(start)) {
			start = 1;
		}
	 	var start_2 = start + 1;
		$('.'+table+' tbody tr').each(function(i) {
				$(this).children().each(function(j) {
					$(this).find('.tab-top').attr('tabindex', i + start);
					$(this).find('.tab-top1').attr('tabindex',i + start_2 + 1);

					if ($(this).find('.tab-top').hasClass('datepicker') || $(this).find('.tab-top').hasClass('month')) {
						$(this).find('.tab-top').next().attr('tabindex', i + start);
					}

					$(this).find('.tab-bottom').attr('tabindex', i + start_2);
					$(this).find('.tab-bottom1').attr('tabindex', i + start_2 + 2);
					
				});
				
			start 		= 1 + start_2;
			start_2 	= 2 + start_2;
		});
	} catch (e) {
		alert('setTabIndexTable: ' + e.message);
	}
}
/**
 * refer data library
 * 
 * @author : ANS806 - 2017/11/15 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referLibrary() {
	try{
		var data = {
			lib_cd			: $('.DSP_lib_cd').text().trim(),
		};
		$.ajax({
			type : 'POST',
			url : '/system-management/library-master/refer-library-detail',
			dataType : 'json',
			data : data,
			success : function(res) {
				if (res.response) {
					$('#library-input').html(res.html_detail_input);
					$('.operator_info').html(res.header_html);
					$('.DSP_lib_nm').text(res.libraryDetail.lib_nm);
					$('.DSP_change_perm_div').text(res.libraryDetail.change_perm_div);
					$('.DSP_lib_val_cd_digit').text(res.libraryDetail.lib_val_cd_digit);
					//set tabindex
					setTabIndexTable('table-library');
					//drap and drop row table
					dragLineTable('table-library', false);
				}
			}
		}).done(function(res){
			setMaxLenghLibCd();
			changePermissionDiv();
		});
	} catch(e) {
        console.log('referLibrary' + e.message)
    }
}
/**
 * save data all - insert/update
 * 
 * @author : ANS806 - 2017/11/15 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function save() {
	try{
	    var data = getData();
    	$.ajax({
	        type        :   'POST',
	        url         :   '/system-management/library-master/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	if(res.error_cd != ''){
            		jMessage(res.error_cd);
            	} else if (res.response) {
	            	jMessage('I001', function(r){
	                	if(r){
	                		referLibrary();
	                	}
	                });
	            } else {
	            	//catch DB error and display
	            	var msg_e999 = _text['E999'].replace('{0}', res.error);
	            	jMessage_str('E999', msg_e999, '', msg_e999);
	            }
	        },
	    });
	} catch(e) {
        console.log('postSave' + e.message)
    }
}
/**
 * get data of input
 * 
 * @author : ANS806 - 2017/11/15 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getData() {
	try{
		var _data = [];
		var index = 1;
		$('#table-library tbody tr').each(function() {
			var _lib_val = {
						'lib_val_cd' 	: $(this).find('.TXT_lib_val_cd').val(),
						'lib_val_nm_j'  : $(this).find('.TXT_lib_val_nm_j').val(),
						'lib_val_nm_e'  : $(this).find('.TXT_lib_val_nm_e').val(),
						'lib_val_ab_j'  : $(this).find('.TXT_lib_val_ab_j').val(),
						'lib_val_ab_e'  : $(this).find('.TXT_lib_val_ab_e').val(),
						'lib_val_ctl1'  : $(this).find('.TXT_lib_val_ctl1').val(),
						'lib_val_ctl2'  : $(this).find('.TXT_lib_val_ctl2').val(),
						'lib_val_ctl3'  : $(this).find('.TXT_lib_val_ctl3').val(),
						'lib_val_ctl4'  : $(this).find('.TXT_lib_val_ctl4').val(),
						'lib_val_ctl5'  : $(this).find('.TXT_lib_val_ctl5').val(),
						'lib_val_ctl6'  : $(this).find('.TXT_lib_val_ctl6').val(),
						'lib_val_ctl7'  : $(this).find('.TXT_lib_val_ctl7').val(),
						'lib_val_ctl8'  : $(this).find('.TXT_lib_val_ctl8').val(),
						'lib_val_ctl9'  : $(this).find('.TXT_lib_val_ctl9').val(),
						'lib_val_ctl10' : $(this).find('.TXT_lib_val_ctl10').val(),
						'ini_target_div': $(this).find('.RDI_ini_target_div').is(':checked') ? '1' : '0',
						'disp_order'	: index
				};
			index = index + 1;
			_data.push(_lib_val);
		});
		var STT_data = {
				'lib_cd'	: $('.DSP_lib_cd').text().trim(),
				'lib_val' 	: _data
			};
		return STT_data;
	} catch(e) {
        console.log('getData' + e.message)
    }
}
/**
 * check double lib_val_cd
 * 
 * @author : ANS806 - 2017/11/15 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function checkDoubleLibCd(origData) {
	try{
	    var origLen = origData.length;
	    var flag 	= true;
	    var pos 	= [];
	    for (var i = 0; i < origLen; i++ ) {
	        found = false;
	        for (var j = (i + 1); j < origLen; j++ ) {
	            if ( origData[i]['lib_val_cd'] === origData[j]['lib_val_cd'] ) {
	            	pos.push({key : j, val : (i+1)})
	            }
	        }
	    }

	    if (pos.length > 0) {
	    	flag 	= false;
	    	for (var i = 0; i < pos.length; i++) {
	    		var msg_e013 = _text['E013'].replace('{1}', '区分');
				msg_e013 = msg_e013.replace('{2}', pos[i].val);
				$('#table-library tbody tr:eq('+pos[i].key+')').find('.TXT_lib_val_cd').errorStyle(msg_e013);
	    	}
	    }
	   return flag;
   } catch(e) {
        console.log('checkDoubleLibCd' + e.message)
    }
}
/**
 * validate
 *
 * @author		:	Trieunb - 2017/11/15 - create
 * @params		:	null
 * @return		:	null
 */
function validate(){
	try{
		var _errors = 0;
		_clearErrors();
		var msg_e001 = _text['E001'];
		$('#table-library tbody tr').each(function() {
			var lib_val_cd = $(this).find('.TXT_lib_val_cd').val().trim();
			if (lib_val_cd == '') {
				$(this).find('.TXT_lib_val_cd').errorStyle(msg_e001);
				_errors ++;
			}
		});
		var data = getData();
		if (!checkDoubleLibCd(data.lib_val)) {
			_errors ++;
		}
		if(_errors>0)
			return false;
		return true;
	} catch(e) {
        console.log('validate' + e.message)
    }
}
/**
 * drag Line Table
 *
 * @author		:	Trieunb - 2017/11/15 - create
 * @params		:	null
 * @return		:	null
 */
function dragLineTable(table, isNumberLine, callback) {
	try{
	  	Sortable.create(
	        $('.'+table+' tbody')[0],
	        {
	            animation: 150,
	            scroll: true,
	            handle: '.drag-handler',
	            onEnd: function (evt) {
	            	updateTable(table, isNumberLine);
	            	//set tabindex for element dragged
	            	$('.'+ table + ' tbody tr:eq(' + evt.newIndex + ') :input:first').focus();
	            }
	        }
	    );
    } catch(e) {
        console.log('dragLineTable' + e.message)
    }
}
/**
 * update Table
 *
 * @author		:	Trieunb - 2017/11/15 - create
 * @params		:	null
 * @return		:	null
 */
function updateTable(table, isNumberLine) {
	try{
		$('.'+table+' tbody tr').css('background-color','#FFFFFF');
	    $('.'+table+' tbody tr:odd').css('background-color','#FFF2CC');
	    if (isNumberLine) {
			$('.'+table+' tbody tr').each(function(i){
		    	$(this).find('.drag-handler').text(i+1);
		    });
		}
		_setTabIndex();
		setTabIndexTable(table);
	} catch(e) {
        console.log('updateTable' + e.message)
    }
}
/**
 * set MaxLengh Lib Cd
 *
 * @author		:	Trieunb - 2017/11/15 - create
 * @params		:	null
 * @return		:	null
 */
function setMaxLenghLibCd(maxlength) {
	try {
		var maxlength = $('.DSP_lib_val_cd_digit').text();
		$('.TXT_lib_val_cd').attr('maxlength', maxlength);
	} catch(e) {
        console.log('setMaxLenghLibCd' + e.message)
    }
}
/**
 * change Permission Div
 *
 * @author		:	Trieunb - 2017/11/15 - create
 * @params		:	null
 * @return		:	null
 */
function changePermissionDiv() {
	try {
		var perm = $('.DSP_change_perm_div').text();
		$("button").each(function (i) { 
			if (perm == '不可')  {
				$(this).attr('disabled', 'disabled');
			} else {
				$(this).attr('disabled', false);
			}
		});
	} catch(e) {
        console.log('changePermissionDiv' + e.message)
    }
}