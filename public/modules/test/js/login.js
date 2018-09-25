/**
 * ****************************************************************************
 * LOGIN
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	DuyTP
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	TEST
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

$(document).ready(function() {	
	initialize();
	initEvents();
	//initItem();
});

/**
 * initialize
 *
 * @author		:	DuyTP – 2017/06/09 - create
 * @return		:	null
 * @access		:	public
 * @see			:	init
 */
function initialize() {
	$("#email").focus();
	$('#email').val("");
	//load message
	$.ajax({
        type        :   'POST',
        url         :   '/common/message/language-message',
        dataType    :   'json',
        success: function(res) {
            if(res.response == 'true'){
                //jMessage(1);
            }else{
                //jMessage(2);
            }   
        },
        // Ajax error
        error : function(res) {
           // closeWaiting();
        },
    });
}

/**
 * initEvents
 *
 * @author		:	DuyTP – 2017/06/09 - create
 * @authr		:
 * @return		:	null
 * @access		:	public
 * @see			:	init
 */
function initEvents() {
	//button login
	$(document).on('click','#btn_login',function(){
		login();
	});

	//enter key
	$(document).on('keydown','#email,#password,#btn_login',function (event) {
	   if(event.keyCode == 13){
		   $('#btn_login').trigger('click');
	   }
	});
}

/**
 * login
 * 
 * @author      :   DuyTP - 2017/05/09 - create
 * @params      :   null
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function login() {
	try {
		var data = {
			user_cd: $('#email').val(),
			password: $('#password').val()
		};
		$.ajax({
            type        :   'POST',
            url         :   '/login/do-login',
            dataType    :   'json',
            data        :   data,
            success: function(res) {
                //closeWaiting();
                if (typeof (res) != 'undefined') {
                    if (res['response'] == true) {
                        window.location.href = res['link'];
                    }else{
                        if (res['status'] == 'not_found') {
                            jMessage('E005');
                        };
                    }
                }  
            },
        });
	} catch (e) {
		alert('search' + e.message);
	}
}