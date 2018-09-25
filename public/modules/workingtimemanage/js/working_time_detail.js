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
	initCombobox();

	// fix issue 164
	$('#TXA_remarks').attr('rows', 6);
});
function initCombobox() {
	var name = 'JP';
	/*_getComboboxData(name, 'work_hour_div');
	_getComboboxData(name, 'work_time_div', function(){
		//refer data from screen search to detail
		if (from == 'WorkingTimeSearch' && mode == 'U') {
			$('#TXT_work_report_no').trigger('change');
		}else{
			_referUser($.mbTrim($('#TXT_work_user_cd').val()), $('#TXT_work_user_cd'), function(){
				if(mode=='U'){
					$('#TXT_work_report_no').focus();
				}else{
					$('#TXT_work_date').focus();
				}
			}, false);
		}
	});*/
	//refer data from screen search to detail
	if (from == 'WorkingTimeSearch' && mode == 'U') {
		$('#TXT_work_report_no').trigger('change');
	}else{
		_referUser($.mbTrim($('#TXT_work_user_cd').val()), $('#TXT_work_user_cd'), function(){
			if(mode=='U'){
				$('#TXT_work_report_no').focus();
			}else{
				$('#TXT_work_date').focus();
			}
		}, false);
	}
	calcTotalWorkingTime();
}
/**
 * init Events
 * @author  :   DuyTP - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		_dragLineTable('table-working-time', true);
		// remove row table
		$(document).on('click','.remove-row',function(e){
			try {
				var obj = $(this);
				jMessage('C002', function(r) {
					if(r) {
						obj.closest('tr').remove();
						_updateTable('table-working-time', true);
						//calculate working time
						calcTotalWorkingTime();
						e.preventDefault();
					}
				});
			} catch (e) {
				console.log('remove-row' + e.message);
			}
		});
		//add row
		$(document).on('click', '#btn-add-row', function () {
			try {
				var row = $("#table-row tr").clone();
				$('.table-working-time tbody').append(row);
				_updateTable('table-working-time', true);
				$('.table-working-time tbody tr:last .manufacturinginstruction_cd').focus();
				calcTotalWorkingTime();
			} catch (e) {
				console.log('add new row' + e.message);
			}
		});
		//init back
		$(document).on('click', '#btn-back', function () {
			if (from == 'WorkingTimeSearch') {
				sessionStorage.setItem('detail', true);
				location.href = '/working-time-manage/working-time-search';
			}
		});
		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				//validate screen
				if(!validate()){
					return;
				}
				if(!checkWorkingTime()){
					return;
				}
				//if table hasn't detail row then show message error
				if (!isExistRow()) {
					jMessage('E004');
					return;
				}
				//
				var msg = (mode == 'I')?'C001':'C003';
				jMessage(msg,function(r){
					if(r){
						save();
					}
				});
			} catch (e) {
				console.log('#btn-save ' + e.message);
			}
		});
		// button delete
		$(document).on('click', '#btn-delete', function() {
			try {
				if($.trim($('#TXT_work_report_no').val()) == '' ) {
					$('#TXT_work_report_no').errorStyle(_MSG_E001);
				}else{
					jMessage('C002',function(r){
						if(r){
							postDelete();
						}
					});
				}
			} catch (e) {
				console.log('#btn-delete ' + e.message);
			}
		});
		// Change 作業日報番号
		$(document).on('change', '#TXT_work_report_no', function() {
			try {
				referWorkingTimeInfo();
			} catch (e) {
				console.log('change: #TXT_work_report_no ' + e.message);
			}
		});
		// Change 作業担当者
		$(document).on('change', '#TXT_work_user_cd', function() {
			try {
				_referUser($.mbTrim($(this).val()), $(this), '', true);
			} catch (e) {
				console.log('change: #TXT_work_user_cd ' + e.message);
			}
		});
		// Change 作業時間
		$(document).on('change', '.work_hour_div, .work_time_div', function() {
			try {
				calcTotalWorkingTime();
			} catch (e) {
				console.log('change: .work_hour_div, .work_time_div' + e.message);
			}
		});
		// Change 製造指示番号
		$(document).on('change', '.manufacturinginstruction_cd', function() {
			try {
				if ($.mbTrim($(this).val()) != '') {
					referProductManufacture(this,$.mbTrim($(this).val()));
				} else {
					$(this).closest('tr').find('.DSP_item_nm_j').text('');
					$(this).closest('tr').find('.DSP_item_nm_j').attr('title', '');
				}
			} catch (e) {
				alert('change #TXT_parts_cd: ' + e.message);
			}
		});
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * validate
 *
 * @author		:	DuyTP - 2017/06/15 - create
 * @params		:	null
 * @return		:	null
 */
function validate(){
	try{
		var _errors = 0;
		if(!_validate($('body'))){
			_errors++;
		}
		if(_errors>0)
			return false;
		return true;
	} catch(e) {
        console.log('validate ' + e.message)
    }
}
/**
 * validate working time
 *
 * @author		:	ANS796 - 2018/01/04 - create
 * @params		:	null
 * @return		:	null
 */
function checkWorkingTime(){
	try{
		var isCheck = true;
		$('#table-working-time tbody tr').each(function() {
			var work_hour_div =	$(this).find('.work_hour_div').val();
			if (work_hour_div == '00') { 
				work_hour_div  = '';
			}
			var work_time_div =	$(this).find('.work_time_div').val();
			if (work_time_div == '00') { 
				work_time_div  = '';
			}
			//if(work_hour_div == '00' && work_time_div == '00'){
			if(work_hour_div == '' && work_time_div == ''){
				$(this).find('.work_hour_div').errorStyle(_text['E002']);
				$(this).find('.work_time_div').errorStyle(_text['E002']);
				isCheck = false;
			}
		});
		return isCheck;
	} catch(e) {
        console.log('checkWorkingTime ' + e.message)
    }
}
/**
 * check exist detail row of table
 *
 * @author      :   ANS796 - 2018/01/04 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function isExistRow() {
	try {
    	var isEmpty = $('#table-working-time tbody tr').length > 0;
    	return isEmpty;
    } catch (e) {
        alert('isExistRow: ' + e.message);
    }
}
/**
 * save data all - insert/update
 * 
 * @author : ANS796 - 2018/01/03 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referWorkingTimeInfo(){
	try{
		//clear all error
		_clearErrors();
		//get data
		var work_report_no 	= $.mbTrim($('#TXT_work_report_no').val());
	    var data = {
	    	work_report_no 	: work_report_no,
	    	mode			: mode
	    };
	    $.ajax({
	        type        :   'POST',
	        url         :   '/working-time-manage/working-time-detail/refer',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if(res.response == true)
	            {
	            	$('#TXT_work_date').val(res.working['work_date']);
	            	$('#TXT_work_user_cd').val(res.working['work_user_cd']);
	            	_referUser($.mbTrim($('#TXT_work_user_cd').val()), $('#TXT_work_user_cd'), function(){
						if(mode=='U'){
							$('#TXT_work_report_no').focus();
						}else{
							$('#TXT_work_date').focus();
						}
					}, false);
	            	$('#TXA_remarks').val(res.working['remarks']);
	            	$('#operator_info').html(res.header);
	            	//$('.manufacturinginstruction_cd').trigger('change');
	            	//mode = 'U';
	            }else{
	            	if(work_report_no == ''){
	            		if (mode == 'U') {
		            		clearAllItem();
		            	} else if (mode == 'I') {
		            		//clear operator_info
							$('#operator_info').html('');
		            	}
		            }else{
		            	jMessage('W001',function(r){
							if(r){
				            	if (mode == 'U') {
				            		clearAllItem();
				            	} else if (mode == 'I') {
				            		//clear operator_info
									$('#operator_info').html('');
				            	}
							}
						});
		            }
					//mode = 'I';
	            }
	            $('#working_time_detail_id').html(res.table);
            	//_getComboboxData('JP', 'work_hour_div', setSelectCombobox);
				//_getComboboxData('JP', 'work_time_div', setSelectCombobox);
				setSelectCombobox();
	            _settingButtonDetele(mode);
	            $('.heading-btn-group').html(res.button);
	            autoTabindexButton(15, parentClass = '.navbar-nav', childClass = '.btn-link');
	            //drap and drop row table
				_dragLineTable('table-working-time', true);
				_setTabIndex();
				$('#TXT_work_report_no').focus();
	        },
	    });
	} catch(e) {
        console.log('referWorkingTimeInfo' + e.message)
    }
}
/**
 * setSelectCombobox
 * 
 * @author : ANS796 - 2018/01/03 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setSelectCombobox() {
	try {
		$('#table-working-time tbody tr').each(function() {
			var work_hour_div 		=	$(this).find('.work_hour_div').attr('data-selected');
			$(this).find('.work_hour_div option[value='+work_hour_div+']').prop('selected', true);
			var work_time_div 		=	$(this).find('.work_time_div').attr('data-selected');
			$(this).find('.work_time_div option[value='+work_time_div+']').prop('selected', true);
		});
		//calculate working time
	    calcTotalWorkingTime();
	} catch (e)  {
        console.log('setSelectCombobox:  ' + e.message);
    }
}
/**
 * calculate working time detail
 * 
 * @author : ANS796 - 2018/01/03 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calcTotalWorkingTime() {
	try {
		var arrHours = [];
		var arrMinutes = [];
		$('#table-working-time tbody tr').each(function() {
			var work_hour_div =	$(this).find('.work_hour_div').val() == ''? '00' : $(this).find('.work_hour_div').val() ;
			var work_time_div =	$(this).find('.work_time_div').val() == ''? '00' : $(this).find('.work_time_div').val() ;
			//if(work_hour_div != '' && work_time_div != ''){
				arrHours.push([work_hour_div]);
				arrMinutes.push([work_time_div]);
			//}
		});
		var countArray = arrHours.length;
		var hours_total = 0;
		var minutes_total = 0;
		var total_m = 0;
		var total_h = 0;
		for(var i = 0; i < countArray; i++){
			hours_total += parseInt(arrHours[i]);
			minutes_total += parseInt(arrMinutes[i]);
		}
		//calculate total hours and munites
		total_m = parseInt(minutes_total % 60);
		total_h = hours_total + parseInt(minutes_total / 60);
		//
		$('#DSP_hours_total').text((total_h<=99)?('0'+total_h).slice(-2):total_h);
		$('#DSP_minutes_total').text(('0'+total_m).slice(-2));
	} catch (e)  {
        console.log('calcTotalWorkingTime:  ' + e.message);
    }
}
/**
 * save data all - insert/update
 * 
 * @author : ANS796 - 2018/01/03 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function save(){
	try{
	    //get data from view
		var data = getDataFromView();
	    $.ajax({
	        type        :   'POST',
	        url         :   '/working-time-manage/working-time-detail/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	//display E005 error when 作業担当者コード and 製造指示番号 not exists
	        	if(res.data_err != null){
	        		jMessage('E005', function(r){
	        			if(r){
	        				for(var i = 0; i < res.data_err.length; i++){
	        					if(res.data_err[i]['item_err'] == 'work_report_no'){
	        						$('#TXT_work_report_no').errorStyle(_text['E005']);
	        					}
	        					if(res.data_err[i]['item_err'] == 'work_user_cd'){
	        						$('#TXT_work_user_cd').errorStyle(_text['E005']);
	        					}else if(res.data_err[i]['item_err'] == 'manufacture_no'){
	        						$('#table-working-time tbody tr').eq(res.data_err[i]['item_idx']).find('.manufacturinginstruction_cd').errorStyle(_text['E005']);
	        					}
	        				}
	        			}
	        		});
	        	}else if(res.error_cd != ''){
            		jMessage(res.error_cd, function(){
						$('#TXT_work_user_cd').focus();
            		});
            	}else if(res.response == true){
	            	var msg = (mode == 'I')?'I001':'I003';
	            	jMessage(msg,function(r){
						if(r){
							mode = 'U';
							$('#TXT_work_report_no').val(res.work_report_no);
							referWorkingTimeInfo();
						}
					});
	            }else{
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
 * delete User
 * 
 * @author : ANS796 - 2018/01/04 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postDelete(){
	try{
	    var data = {
	    	work_report_no 			: $.mbTrim($('#TXT_work_report_no').val())
	    };
	    $.ajax({
	        type        :   'POST',
	        url         :   '/working-time-manage/working-time-detail/delete',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	if(res.error_cd != ''){
            		jMessage(res.error_cd);
            	}else if(res.response == true){
	            	jMessage('I002',function(r){
						if(r){
							$('#TXT_work_report_no').val('');
		            		var param = {
								'mode'		: mode,
								'from'		: from
							};
							_postParamToLink(from, 'WorkingTimeDetail', '/working-time-manage/working-time-detail', param);
						}
					});
	            }else{
	            	//catch DB error and display
	            	var msg_e999 = _text['E999'].replace('{0}', res.error);
	            	jMessage_str('E999', msg_e999, '', msg_e999);
	            }
	        },
	    });
	} catch(e) {
        console.log('postDelete' + e.message)
    }
}
/**
 * get data from view
 *
 * @author      :   ANS796 - 2018/01/04 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function getDataFromView() {
	try {
		var workingtime_list   = [];
		$('#table-working-time tbody tr').each(function() {
			if ($(this).attr('id') != 'row-empty') {
				var work_report_detail_no   = ($(this).find('.DSP_work_report_detail_no').text() == "") ? 0 : $(this).find('.DSP_work_report_detail_no').text();
				var manufacture_no 			= $.mbTrim($(this).find('.manufacturinginstruction_cd').val());
				var work_hour_div 			= $(this).find('.work_hour_div').val();
				if (work_hour_div == '') {
				 	work_hour_div = '00' ;
				} 
				var work_time_div 			= $(this).find('.work_time_div').val().trim();
				if (work_time_div == '') {
				 	work_time_div = '00' ;
				} 
				var memo 					= $(this).find('.TXT_memo').val().trim();
				var _data = {
					work_report_detail_no	: work_report_detail_no,
					manufacture_no			: manufacture_no,
					work_hour_div			: work_hour_div,
					work_time_div			: work_time_div,
					memo					: memo
				};
				workingtime_list.push(_data);
			}
		});
		var data = {
			mode					: mode,
			work_report_no			: $.mbTrim($('#TXT_work_report_no').val()),
			work_user_cd			: $.mbTrim($('#TXT_work_user_cd').val()),
			work_date				: $('#TXT_work_date').val(),
			remarks					: $.mbTrim($('#TXA_remarks').val()),
			workingtime_list		: workingtime_list
		};
		return data;
    } catch (e) {
        console.log('getDataFromView: ' + e.message);
    }
}
/**
 * setting Button Delete
 *
 * @author      :   ANS796 - 2018/01/03 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function _settingButtonDetele(mode) {
    try {
        if(mode=='I'){
            $('#btn-delete').hide();
            $('#TXT_work_report_no').attr('readonly', true);
			$('#TXT_work_report_no').parent().addClass('popup-workingtime-search')
			$('.popup-workingtime-search').find('.btn-search').attr('disabled', true);
			parent.$('.popup-workingtime-search').removeClass('popup-workingtime-search');
			$("#TXT_work_report_no").removeClass("required");
        }
        if(mode=='U'){
            $('#btn-delete').show();
            $('#TXT_work_report_no').attr('readonly', false);
			$('#TXT_work_report_no').parent().addClass('popup-workingtime-search')
			$('.popup-workingtime-search').find('.btn-search').attr('disabled', false);
			parent.$('.popup-workingtime-search').removeClass('popup-workingtime-search');
			$('#TXT_work_report_no').addClass("required");
        }
    } catch (e) {
        console.log('_settingButtonDetele' + e.message);
    }
}
/**
 * clear all item screen
 *
 * @author      :   ANS796 - 2018/01/03 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function clearAllItem() {
    try {
    	$('#TXT_work_date').val(new Date().toJSON().slice(0,10).replace(/-/g,'/'));
    	$('#TXT_work_user_cd').val(userLogin);
    	_referUser($.mbTrim($('#TXT_work_user_cd').val()), $('#TXT_work_user_cd'), function(){
			if(mode=='U'){
				$('#TXT_work_report_no').focus();
			}
		}, false);
    	$('#TXA_remarks').val('');
    	$('#operator_info').html('');
    } catch (e) {
        console.log('clearAllItem' + e.message);
    }
}
/**
 * refer product base manufacture no
 * 
 * @author : ANS806 - 2017/12/12 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referProductManufacture(manufacture_no_elm, manufacture_no) {
	try	{
		$.ajax({
			type 		: 'GET',
			url 		: '/common/refer/refer-manufacture',
			dataType	: 'json',
			data 		: {
					manufacture_no : manufacture_no
			},
			success: function(res) {
				if(res.response == true) {
					$(manufacture_no_elm).closest('tr').find('.DSP_item_nm_j').text(res.data['item_nm_j']);
					$(manufacture_no_elm).closest('tr').find('.DSP_item_nm_j').attr('title', res.data['item_nm_j']);
				} else {
					jMessage('W001',function(r){
						if(r){
			            	$(manufacture_no_elm).closest('tr').find('.DSP_item_nm_j').text('');
			            	$(manufacture_no_elm).closest('tr').find('.DSP_item_nm_j').attr('title', '');
			            	$(manufacture_no_elm).focus();
						}
					});
				}
			}
		});
	} catch (e) {
		alert('referProductManufacture: ' + e.message);
	}
}
