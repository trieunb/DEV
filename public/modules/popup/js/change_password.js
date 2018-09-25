/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/05/03
 * 作成者		:	ANS796
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	ChangePassword
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

$(document).ready(function () {
	initEvents();
});
/**
 * init Events
 * @author  :   ANS804 - 2018/03/01 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		$(document).on('click', '#btn-pass-ok', function(e) {
			try {                
				if(validate()){
					save();
				}
			} catch (e) {
				console.log('#btn-pass-ok ' + e.message);
			}
		});
        $(document).on('click', '#btn-pass-cancel', function(){
            try {
                parent.$.colorbox.close();
            } catch (e) {
                console.log('#btn-pass-cancel ' + e.message);
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
 * change password
 * 
 * @author : ANS796 - 2018/05/03 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function save(){
	try{
		var pass 		= $('.TXT_password').val().trim();
		var confirmPass = $('.TXT_confirm_password').val().trim();
		if(pass != confirmPass){
			$('.TXT_password').errorStyle(_text['E486']);
		}else{
			jMessage('C484',function(r){
				if(r){
				    var data = {
				    	pass 			: pass
				    };
				    $.ajax({
				        type        :   'POST',
				        url         :   '/popup/changePassword/save',
				        dataType    :   'json',
				        data        :   data,
				        success: function(res) {
				        	if(res.response == true){
				            	jMessage('I009',function(r){
									if(r){
										parent.$.colorbox.close();
									}
								});
				            }else{
				            	//catch DB error and display
				            	var msg_e999 = _text['E999'].replace('{0}', res.error);
				            	jMessage_str('E999', msg_e999, '', msg_e999);
				            }
				        },
				    });
				}
			});
		}
	} catch(e) {
        console.log('save' + e.message)
    }
}
