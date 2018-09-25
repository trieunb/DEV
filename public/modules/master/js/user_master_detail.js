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
	_settingButtonDetele(mode);
});

function initCombobox() {
	//IF _getComboboxData completed THEN refer data from screen search to detail
	var nameLibrary = 'JP';
	/*$.when(
		_getComboboxData(nameLibrary, 'auth_role_div'),
		_getComboboxData(nameLibrary, 'incumbent_div'),
		_getComboboxData(nameLibrary, 'belong_div'),
		_getComboboxData(nameLibrary, 'position_div'),
	).done(function(){
		//refer data from screen search to detail
		if (from == 'UserMasterSearch' && mode == 'U') {
			$('.user_cd').trigger('change');
		}
	});*/
	// _getComboboxData(nameLibrary, 'auth_role_div');
	// _getComboboxData(nameLibrary, 'incumbent_div');
	// _getComboboxData(nameLibrary, 'belong_div');
	// _getComboboxData(nameLibrary, 'position_div', function(){
		//refer data from screen search to detail
		if (from == 'UserMasterSearch' && mode == 'U' && $('.user_cd').val() != '') {
			$('.user_cd').trigger('change');
		}
	// });
}

/**
 * init Events
 * @author  :   DuyTP - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//init back
		$(document).on('click', '#btn-back', function () {
			if (from == 'UserMasterSearch') {
				sessionStorage.setItem('detail', true);
				location.href = '/master/user-master-search';
			}
		});
		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				if(validate()){
					var msg = (mode == 'I')?'C001':'C003';
					jMessage(msg,function(r){
						if(r){
							save();
						}
					});
			   	}
			} catch (e) {
				console.log('#btn-save ' + e.message);
			}
		});
		// button delete
		$(document).on('click', '#btn-delete', function() {
			try {
				if($.trim($('.user_cd').val()) == '' ) {
					$('.user_cd').errorStyle(_MSG_E001);
				}else{
					jMessage('C002',function(r){
						if(r){
							postDelete();
						}
					});
				}
			} catch (e) {
				alert('#btn-delete ' + e.message);
			}
		});
		// Change ユーザーコード
		$(document).on('change', '.user_cd', function() {
			try {
				referUserInfo();
			} catch (e) {
				console.log('change: .user_cd ' + e.message);
			}
		});
	} catch (e) {
		console.log('initEvents: ' + e.message);
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
	var _errors = 0;
	if(!_validate($('body'))){
		_errors++;
	}

	if(_errors>0)
		return false;

	return true;
}
/**
 * save data all - insert/update
 * 
 * @author : ANS796 - 2017/11/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referUserInfo(){
	try{
		//clear all error
		_clearErrors();
		//get data
		var user_cd 	= $('.user_cd').val().trim();
	    var data = {
	    	user_cd 	: user_cd,
	    	mode		: mode
	    };
	    $.ajax({
	        type        :   'POST',
	        url         :   '/master/user-master-detail/refer',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if(res.response == true)
	            {
	            	$('#TXT_user_nm_j').val(res.user['user_nm_j']);
	            	$('#TXT_user_ab_j').val(res.user['user_ab_j']);
	            	$('#TXT_user_nm_e').val(res.user['user_nm_e']);
	            	$('#TXT_user_ab_e').val(res.user['user_ab_e']);
	            	$('#TXT_pwd').val(res.user['pwd']);
	            	$('#CMB_belong_div').val(res.user['belong_div']);
	            	$('#CMB_position_div').val(res.user['position_div']);
	            	$('#CMB_auth_role_div').val(res.user['auth_role_div']);
	            	$('#CMB_incumbent_div').val(res.user['incumbent_div']);
	            	$('#TXT_pwd_upd_datetime').val(res.user['pwd_upd_datetime']);
	            	$('#TXT_login_datetime').val(res.user['login_datetime']);
	            	$('#TXA_memo').val(res.user['memo']);
	            	$('#operator_info').html(res.header);
	            	mode = 'U';
	            }else{
	            	if(user_cd == ''){
	            		if (mode == 'U') {
		            		clearAllItem();
		            	} else if (mode == 'I') {
		            		//clear operator_info
							$('#operator_info').html('');
		            	}
		            }else{
		            	if (mode == 'U') {
		            		clearAllItem();
		            		/*jMessage('W001',function(r){
								if(r){
					            		clearAllItem();
					            	}
				            	}
				            );*/
		            	} else if (mode == 'I') {
		            		//clear operator_info
							$('#operator_info').html('');
		            	}
		            }
					mode = 'I';
	            }
	            _settingButtonDetele(mode);
	            $('.heading-btn-group').html(res.button);
	            autoTabindexButton(15, parentClass = '.navbar-nav', childClass = '.btn-link');
	        },
	    });
	} catch(e) {
        console.log('referUserInfo' + e.message)
    }
}
/**
 * save data all - insert/update
 * 
 * @author : ANS796 - 2017/11/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function save(){
	try{
	    var data = {
	    	user_cd 			: $('.user_cd').val().trim(),
	    	user_nm_j 			: $('#TXT_user_nm_j').val().trim(),
	    	user_ab_j 			: $('#TXT_user_ab_j').val().trim(),
	    	user_nm_e 			: $('#TXT_user_nm_e').val().trim(),
	    	user_ab_e 			: $('#TXT_user_ab_e').val().trim(),
	    	pwd 				: $('#TXT_pwd').val().trim(),
	    	belong_div 			: $('#CMB_belong_div').val(),
	    	position_div 		: $('#CMB_position_div').val(),
	    	auth_role_div 		: $('#CMB_auth_role_div').val(),
	    	incumbent_div 		: $('#CMB_incumbent_div').val(),
	    	pwd_upd_datetime 	: $('#TXT_pwd_upd_datetime').val().trim(),
	    	login_datetime 		: $('#TXT_login_datetime').val().trim(),
	    	memo 				: $('#TXA_memo').val().trim(),
	    	mode 				: mode
	    };
	    $.ajax({
	        type        :   'POST',
	        url         :   '/master/user-master-detail/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	if(res.error_cd != ''){
            		jMessage(res.error_cd);
            	}else if(res.response == true){
	            	var msg = (mode == 'I')?'I001':'I003';
	            	jMessage(msg,function(r){
						if(r){
							mode = 'U';
							referUserInfo();
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
 * @author : ANS796 - 2017/11/08 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postDelete(){
	try{
	    var data = {
	    	user_cd 			: $('.user_cd').val().trim()
	    };
	    $.ajax({
	        type        :   'POST',
	        url         :   '/master/user-master-detail/delete',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	if(res.error_cd != ''){
            		jMessage(res.error_cd);
            	}else if(res.response == true){
            		if (is_new == 'true') {
						mode	=	'U';
					} else {
						mode	=	'I';
					}
	            	jMessage('I002',function(r){
						if(r){
							$('.user_cd').val('');
		            		var param = {
								'mode'		: mode,
								'from'		: from,
								'is_new'	: is_new
							};
							_postParamToLink(from, 'UserMasterDetail', '/master/user-master-detail', param);
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
 * setting Button Delete
 *
 * @author      :   ANS796 - 2017/11/08 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function _settingButtonDetele(mode) {
    try {
        if(mode=='I'){
            $('#btn-delete').hide();
        }
        if(mode=='U'){
            $('#btn-delete').show();
        }
    } catch (e) {
        console.log('_settingButtonDetele' + e.message);
    }
}
/**
 * clear all item screen
 *
 * @author      :   ANS796 - 2017/11/08 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function clearAllItem() {
    try {
       	$('#TXT_user_nm_j').val('');
    	$('#TXT_user_ab_j').val('');
    	$('#TXT_user_nm_e').val('');
    	$('#TXT_user_ab_e').val('');
    	$('#TXT_pwd').val('');
    	$('#CMB_belong_div').val('');
    	$('#CMB_position_div').val('');
    	$('#CMB_auth_role_div').val('');
    	$('#CMB_incumbent_div').val('');
    	$('#TXT_pwd_upd_datetime').val('');
    	$('#TXT_login_datetime').val('');
    	$('#TXA_memo').val('');
    	$('#operator_info').html('');
    	//set default value of select
    	$('select').each(function() {
    		if($(this).attr('data-ini-target') == 'true'){
	    		var objParent = $(this);
	    		objParent.find('option').each(function(){
	    			if($(this).attr('data-ini_target_div') == 1){
						objParent.val($(this).attr('value'));
					}
	    		});
	    	}
		});
    } catch (e) {
        console.log('clearAllItem' + e.message);
    }
}