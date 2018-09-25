/*********************************************************************
 *
 * 株式会社エイ・エヌ・エス Yusuke Kaneko
 * http://www.ans-net.co.jp/
 *
 *   [][]      []    []      [][]
 * []    []    [][]  []    []    []
 * []    []    [][]  []    []
 * []    []    []  [][]      [][]
 * [][][][]    []  [][]          []
 * []    []    []    []    []    []
 * []    [] [] []    [] []   [][]   []
 *
 * Copyright(C) 2013 A.N.S. corp. all rights reserved.
 *
 * restrictions :
 * May not be resold, sub-licensed, rented, transferred
 * or otherwise made available for use.
 *
 **********************************************************************/

/**
 * @name  jQuery
 * @class 詳細は jQuery を参照 (http://jquery.com/)
 * @see   http://jquery.com/
 */

/**
 * @name     fn
 * @class    詳細は jQuery を参照 (http://jquery.com/)
 * @memberOf jQuery
 * @see      http://jquery.com/
 */

/**
 * @name     event
 * @class    詳細は jQuery を参照 (http://jquery.com/)
 * @memberOf jQuery
 * @see      http://jquery.com/
 */

/**
 * @name     special
 * @class    詳細は jQuery を参照 (http://jquery.com/)
 * @memberOf jQuery.event
 * @see      http://jquery.com/
 */

/**
 * jQuery Plugin [Utility]
 *
 * @author Yusuke Kaneko
 * @version 2012.11.01.01
 * @requires jquery-X.X.X.js or jquery-X.X.X.min.js
 */
(function(jQuery) {
	/**
	 * デバッグモード
	 *
	 * @param {bool}
	 *            _debugFlag true:デバッグ表示あり / false:デバッグ表示なし
	 * @private
	 */
	var _debugFlag = true;

	/**
	 * PHP ファイル名
	 *
	 * @param {hash}
	 *            _phpFile PHP ファイルへのパス
	 * @param {string}
	 *            _phpFile.activereports PDF ダウンロード
	 * @param {string}
	 *            _phpFile.autocomplete オートコンプリート
	 * @param {string}
	 *            _phpFile.database SQL 発行
	 * @param {string}
	 *            _phpFile.dataOutput Excel / CSV / TSV 作成
	 * @param {string}
	 *            _phpFile.errorLog エラーログ
	 * @param {string}
	 *            _phpFile.fileCreateDownload 帳票作成 + 既存ファイルダウンロード
	 * @param {string}
	 *            _phpFile.fileDisplay ファイルのブラウザ表示
	 * @param {string}
	 *            _phpFile.fileDownload ファイルダウンロード
	 * @param {string}
	 *            _phpFile.fileExists ファイル存在確認
	 * @param {string}
	 *            _phpFile.ipAddress ユーザーの IP アドレス取得
	 * @param {string}
	 *            _phpFile.platform 環境依存文字の置換
	 * @param {string}
	 *            _phpFile.sendMail メール送信
	 * @param {string}
	 *            _phpFile.serverTime サーバー時刻取得
	 * @param {string}
	 *            _phpFile.urlHistory URL の遷移履歴取得
	 * @private
	 */
	var _phpFile = new Object();
	_phpFile['activereports'] = 'includes/activereports_service.php'; // PDF
	// ダウンロード
	_phpFile['csv'] = 'includes/csv_service.php'; // CSV ダウンロード
	_phpFile['autocomplete'] = 'common/autocomplete.php'; // オートコンプリート
	_phpFile['database'] = 'common/database.php'; // SQL 発行
	_phpFile['dataOutput'] = 'includes/data_output.php'; // Excel / CSV / TSV
	// 作成
	_phpFile['errorLog'] = 'common/error_log.php'; // エラーログ
	_phpFile['fileCreateDownload'] = 'common/file_create_download.php'; // 帳票作成
	// +
	// 既存ファイルダウンロード
	_phpFile['fileDisplay'] = 'common/file_display.php'; // ファイルのブラウザ表示
	_phpFile['fileDownload'] = 'common/file_download.php'; // ファイルダウンロード
	_phpFile['fileExists'] = 'common/get_file_exisits.php'; // ファイル存在確認
	_phpFile['ipAddress'] = 'common/get_ip_address.php'; // ユーザーの IP アドレス取得
	_phpFile['platform'] = 'common/platform_character.php'; // 環境依存文字の置換
	_phpFile['sendMail'] = 'common/send_mail.php'; // メール送信
	_phpFile['serverTime'] = 'common/get_server_time.php'; // サーバー時刻取得
	_phpFile['urlHistory'] = 'common/get_url_history.php'; // URL の遷移履歴取得

	// ----------+[ 判定関連 ]+----------
	/**
	 * 整数判定
	 *
	 * @param {string}
	 *            target 判定対象
	 * @return {bool} 判定結果 true:整数 / false:整数以外
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example if ($.isInteger(target)) { alert(target + ' is integer.'); };
	 */
	jQuery.isInteger = function(target) {
		try {
			target = jQuery.castString(target).replace(/,/g, '');
			//
			// 数値形式にマッチするか判定
			var match = target.match(/^[-+]?\d+$/);
			if (match !== null) {
				return (true);
			} else {
				return (false);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'isInteger');
			return (false);
		}
	};
	/**
	 * @author ANS_VN
	 * @discription: only input number into object
	 */
	jQuery.fn.ForceNumericOnly = function() {
		return this
				.each(function() {
					$(this)
							.keydown(
									function(e) {
										var key = e.charCode || e.keyCode || 0;
										// allow backspace, tab, delete, arrows,
										// numbers and keypad numbers ONLY
										return (key == 8 || key == 9
												|| key == 46
												|| (key >= 37 && key <= 40)
												|| (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
									});
				});
	};
	/**
	 * @author ANS_VN
	 * type: array
	 * sql : array
	 * file: array
	 * @discription: download pdf on popup
	 */
	jQuery.mydownloadfile = function(type,sql,file){
		location.href = "/phpexport/phppdf/download/type/" + JSON.stringify(type) + "/sql/" + JSON.stringify(sql) + "/file/" + JSON.stringify(file);
		return false;
	};
jQuery.savecsv = function(sql,file_name,params_json){

	$.colorbox({                            // initialize colorbox plugin for showing assignee moving popup
		iframe: true
	,   href:'/phpexport/phpcsv/index/file_name/'+file_name
	,   innerWidth:350
	,   innerHeight: 100
	,   opacity: 0.1
	,   title:  "Save as"
	,onClosed:function(){
		if($("#sys_out_csv_encode").length <=0){
			return false;
		}
		file_name = $("#sys_out_name").val();
		if($('#sys_out_csv_encode').val()==1){
			window.location.replace('/phpexport/phpcsv/exportcsvutf8/file_name/'+file_name+'/sql/'+sql+'/params/'+params_json);
			return false;
		}else if($('#sys_out_csv_encode').val()==2){
			window.location.replace('/phpexport/phpcsv/exportcsvansi/file_name/'+file_name+'/sql/'+sql+'/params/'+params_json);
			return false;
		}else if($('#sys_out_csv_encode').val()==3){
			window.location.replace('/phpexport/phpcsv/exportcsvunicode/file_name/'+file_name+'/sql/'+sql+'/params/'+params_json);
			return false;
		}
		$("#sys_out_name").remove();
		$("#sys_out_csv_encode").remove();
	}
	});
};
/**
 * @author ANS_VN
 * name: String
 * file: String
 * @discription: preview file on popup or download file if not preview
 */
jQuery.previewFile = function(name,fileUpload){
	var fileRoot = '/upload/';
	var fileExtension = jQuery.getExtension($('#'+name).val());
	var fileUploadExtension = jQuery.getExtension($('#'+fileUpload).val());
	var fileUrl = fileRoot;
	if(fileExtension==""){
		jAlert('表示するデータがありません。', '報告', function(){
		});
		return false;
	}
	var isUpload = false;
	if(fileExtension!="" && fileUploadExtension!=""){
		isUpload = true;
	}
	jQuery.uploadFile(name, fileUpload,function(data){
		fileUrl = fileRoot+data;
		$.post(
				'/phpexport/phppdf/checkfile',
				{   url     :   encodeURIComponent(fileUrl)
				},
				function(res) {
					if (res=="0"|| res==""){
						jAlert('表示するデータがありません。', '報告', function(){
						});
					}else{
						var extension = jQuery.getExtension($('#'+name).val());
						// File exit. Read and show file
						if (extension=='pdf' || extension=='png' ||extension=='jpg'||extension=='jpeg'||extension=='txt'||extension=='csv'){
							$.colorbox({                           // initialize colorbox plugin for showing assignee moving popup
									iframe: true
									,   href:'/phpexport/phppdf/previewfile/url/'+encodeURIComponent(fileUrl)
									,   innerWidth:1000
									,   innerHeight: 600
									,   opacity: 0.1
									,   cbox_load: function(){
											$('#cboxClose').remove();
											$("#cboxTitle").remove();
										}
									,onClosed:function(){
										if(isUpload){
											$.post(
													'/phpexport/phppdf/deletefilepreview',
													{   'file_url'  :   $("#sys_url_temp_file_download").val()},
													function(res) {
													}
												);
										}
										$("#sys_url_temp_file_download").remove();
									}
							});
						}else if(extension=='xlsx'){
							if(isUpload){
								window.location.replace('/phpexport/phppdf/getfileexelxlsxtemp/filename/'+encodeURIComponent(data));
							}else{
								window.location.replace('/phpexport/phppdf/getfileexelxlsx/filename/'+encodeURIComponent(data));
							}
						}else if(extension=='xls'){
							if(isUpload){
								window.location.replace('/phpexport/phppdf/getfileexelxlstemp/filename/'+encodeURIComponent(data));
							}else{
								window.location.replace('/phpexport/phppdf/getfileexelxls/filename/'+encodeURIComponent(data));
							}
						}else if(extension=='doc'||extension=='docx'){
							if(isUpload){
								window.location.replace('/phpexport/phppdf/getfiledoctemp/filename/'+encodeURIComponent(data));
							}else{
								window.location.replace('/phpexport/phppdf/getfiledoc/filename/'+encodeURIComponent(data));
							}
						}
					}
					return false;
				}
			);

	});
	return false;
	if(fileUploadExtension=="" && fileExtension!=""){
		fileUrl = fileRoot+$('#'+name).val();
		var extension = jQuery.getExtension($('#'+name).val());
		// Check file exists. If no exist, show alert information. Unless, show file.
		$.post(
			'/phpexport/phppdf/checkfile',
			{   url     :   encodeURIComponent(fileUrl)
			},
			function(res) {
				if (res=="0"|| res==""){
					jAlert('表示するデータがありません。', '報告', function(){
					});
				}else{
					// File exit. Read and show file
					if (extension=='pdf' || extension=='png' ||extension=='jpg'||extension=='jpeg'||extension=='txt'||extension=='csv'){
						$.colorbox({                           // initialize colorbox plugin for showing assignee moving popup
								iframe: true
								,   href:'/phpexport/phppdf/previewfile/url/'+encodeURIComponent(fileUrl)
								,   innerWidth:1000
								,   innerHeight: 600
								,   opacity: 0.1
								,   cbox_load: function(){
										$('#cboxClose').remove();
										$("#cboxTitle").remove();
									}
								,onClosed:function(){
									$("#sys_url_temp_file_download").remove();
								}
						});
					}else if(extension=='xlsx'){
						window.location.replace('/phpexport/phppdf/getfileexelxlsx/filename/'+encodeURIComponent($('#'+name).val()));
					}else if(extension=='xls'){
						window.location.replace('/phpexport/phppdf/getfileexelxls/filename/'+encodeURIComponent($('#'+name).val()));
					}else if(extension=='doc'||extension=='docx'){
						window.location.replace('/phpexport/phppdf/getfiledoc/filename/'+encodeURIComponent($('#'+name).val()));
					}
				}
				return false;
			}
		);
	}else{
		return false;
		$.ajaxFileUpload({
			url : '/phpexport/phppdf/savefile',
			secureuri : false,
			fileElementId : fileUpload,
			dataType : 'json',
		success: function (data)
		{
			if(typeof(data.error) != 'undefined')
			{
				if(data.error != '')
				{
					alert(data.error);
					return false;
				}else{

					return false;
					fileUrl = fileUrl +data.name;
					var extension = jQuery.getExtension(data.name);
					// alert(encodeURIComponent(fileUrl));
					if (extension=='pdf' || extension=='png' ||extension=='jpg'||extension=='jpeg'||extension=='txt'||extension=='csv'){
						$.colorbox({                           // initialize colorbox plugin for showing assignee moving popup
								iframe: true
								,   href:'/phpexport/phppdf/previewfile/url/'+encodeURIComponent(fileUrl)
								,   innerWidth:1000
								,   innerHeight: 600
								,   opacity: 0.1
								,   cbox_load: function(){
										$('#cboxClose').remove();
										$("#cboxTitle").remove();
									}
								,onClosed:function(){
										$.post(
												'/phpexport/phppdf/deletefilepreview',
												{   'file_url'  :   $("#sys_url_temp_file_download").val()},
												function(res) {
												}
											);
									$("#sys_url_temp_file_download").remove();
								}
						});
					}else if(extension=='xlsx'){
						window.location.replace('/phpexport/phppdf/getfileexelxlsxtemp/filename/'+encodeURIComponent(data.name));
					}else if(extension=='xls'){
						window.location.replace('/phpexport/phppdf/getfileexelxlstemp/filename/'+encodeURIComponent(data.name));
					}else if(extension=='doc'||extension=='docx'){
						window.location.replace('/phpexport/phppdf/getfiledoctemp/filename/'+encodeURIComponent(data.name));
					}
				}
			}
		},
		complete: function (data){
			var a = data.responseText;
			abc = a;

		},

		error: function (data, status, e)
		{
			alert(e);
			return false;
		}
		});

		alert(abc.valueOf('name'));
		console.log(abc);
	}
	//alert('abc2: ' + abc);
	return false;
};

jQuery.uploadFile = function(filename, fileUpload, callback){
	var newName ='';
	var fileExtension = jQuery.getExtension($('#'+filename).val());
	var fileUploadExtension = jQuery.getExtension($('#'+fileUpload).val());
	if(fileExtension==""){
		callback("");
		return false;
	}
	if(fileExtension!="" && fileUploadExtension==""){
		callback($('#'+filename).val());
		return false;
	}
	if(fileUploadExtension!=""){
		$.ajaxFileUpload({
			url : '/phpexport/phppdf/savefile',
			secureuri : false,
			fileElementId : fileUpload,
			dataType : 'json',
		success: function (data, status)
		{
			if(typeof(data.error) != 'undefined')
			{
				if(data.error != '')
				{
					alert(data.error);
					callback(false);
					return false;
				}else{
					newName = data.name;
					callback(newName);
					return false;
				}
			}
		},
		error: function (data, status, e)
		{
			alert(e);
			callback(false);
			return false;
		},

		});
	}
}
jQuery.uploadMultiFile = function(fileName, fileUpload, callback){
	var newName ='';
	var resultName = new Array();
	for(var i = 0; i<fileName.length;i++){
		var fileExtension = jQuery.getExtension($('#'+filename[i]).val());
		var fileUploadExtension = jQuery.getExtension($('#'+fileUpload[i]).val());
		if(fileExtension==""){
			resultName[i] = "";
			continue;
		}
		if(fileExtension!="" && fileUploadExtension==""){
			resultName[i]=$('#'+filename[i]).val();
			continue;
		}
		if(fileUploadExtension!=""){
			$.ajaxFileUpload({
				url : '/phpexport/phppdf/savefile',
				secureuri : false,
				fileElementId : fileUpload[i],
				dataType : 'json',
			success: function (data, status)
			{
				if(typeof(data.error) != 'undefined')
				{
					if(data.error != '')
					{
						alert(data.error);
						callback(false);
						return false;
					}else{
						newName = data.name;
						callback(newName);
						return false;
					}
				}
			},
			error: function (data, status, e)
			{
				alert(e);
				callback(false);
				return false;
			},

			});
		}
	}
	if(fileExtension!="" && fileUploadExtension==""){
		callback($('#'+filename).val());
		return false;
	}
	if(fileUploadExtension!=""){
		$.ajaxFileUpload({
			url : '/phpexport/phppdf/savefile',
			secureuri : false,
			fileElementId : fileUpload,
			dataType : 'json',
		success: function (data, status)
		{
			if(typeof(data.error) != 'undefined')
			{
				if(data.error != '')
				{
					alert(data.error);
					callback(false);
					return false;
				}else{
					newName = data.name;
					callback(newName);
					return false;
				}
			}
		},
		error: function (data, status, e)
		{
			alert(e);
			callback(false);
			return false;
		},

		});
	}
}
/**
 * @author ANS_VN
 * type: array
 * sql : array
 * file: array
 * @discription: show preview pdf on popup
 */
	jQuery.previewfilepdf = function(type,sql,file,callback){
		/*$.colorbox({                          // initialize colorbox plugin for showing assignee moving popup
			open: true
		,   href:'/phpexport/phppdf/preview'
		,   innerWidth:1000
		,   innerHeight: 540
		,   opacity: 0.1
		,   data : {
				type : JSON.stringify(type)
			,   sql  : JSON.stringify(sql)
			,   file : JSON.stringify(file)
			}
		,   cbox_load: function(){
				$('#cboxClose').remove();
				$("#cboxTitle").remove();
			}
		,onClosed:function(){
			$.post(
					'/phpexport/phppdf/deletefile',
					{   'file_url'  :   $("#sys_url_temp_file_download").val()},
					function(res) {
					}
				);
			$("#sys_url_temp_file_download").remove();
		}
		});*/
		$('#loading-image span').text(_text[1274]);
		$('#cboxOverlay').fadeIn('slow');
		$('#loading-image').fadeIn('slow');
		$.ajax({
			url : '/export/phppdf/preview'
		,   type : 'post'
		,   data : {
				type : JSON.stringify(type)
			,   sql  : JSON.stringify(sql)
			,   file : JSON.stringify(file)
			}
		,   success : function(res) {
				try{
					var data = $.parseJSON(res);
					if(data['status']==26)
					{
//						alert(26);
						jError(_text[61], _title[61]);
						//jMessage(26);
						//return false;
					} else if(data['status']==200)
					{
						var file = data['file'];
						var url = '/export/phppdf/pdfjs/url/' + file;
						openWindow(url);
//                      if(/^[^\\\/\:\*\?\"\<\>\|]+$/.file){
//                          var url = '/phpexport/phppdf/pdfjs/url/' + file;
//                          openWindow(url);
//                      }
//                      else
//                          jProhibit(res);
					}
					else
					{
						//jProhibit(res);
						alert(res);
					}
				}
				catch(e) {
					//jProhibit(res);
					alert(res);
				}
				//if(/^[^\\\/\:\*\?\"\<\>\|]+$/.test(res)){
				//  var url = '/phpexport/phppdf/pdfjs/url/' + res;
				//  openWindow(url);
				//}
				$('#cboxOverlay').fadeOut('slow');
				$('#loading-image').fadeOut('slow');
				$('#loading-image span').text('');
				//if(callback){
				//  callback();
				//}
			}
		});
	};
	function openWindow(url){
		window.open(url,'_blank');
		window.focus();
		return false;
	}
	/**
	 * Format: Hình thức hiển thị của số
	 *
	 * @param {string}
	 *            format Kiểu chuyển đổi #[,]##(#|0)[.][#…#|#…0] [Mặc định:#]
	 * @param {hash}
	 *            option Thiết định của user
	 * @param {float}
	 *            option.min Số nhỏ nhất Trường hợp null thì không thiết định số
	 *            nhỏ nhất.[Mặc định:null]
	 * @param {float}
	 *            option.max Số lớn nhất Trường hợp null thì không thiết định số
	 *            lớn nhất.[Mặc định:null]
	 * @return {object} jQuery object
	 * @public
	 */
	jQuery.fn.textNumberFormat = function(format, option) {
		try {
			var tag = '';
			var string = '';
			//
			return (this
					.each(function(i, dom) {
						try {
							tag = jQuery(this).get(0).tagName;
							if (tag === 'INPUT' || tag === 'TEXTAREA'
									|| tag === 'SELECT') {
								// value属性ありのタグ
								jQuery(this).unbind('focus',
										_textNumberFormatFocus).unbind('blur',
										_textNumberFormatBlur).bind('focus',
										_textNumberFormatFocus).bind('blur', {
									numberFormat : format,
									numberFormatOption : option
								}, _textNumberFormatBlur);
							} else {
								string = jQuery.textFormat(jQuery(this).text(),
										format);
								jQuery(this).text(string);
							}
						} catch (e) {
							jQuery.showErrorDetail(e, 'textNumberFormat each');
						}
					}));
		} catch (e) {
			jQuery.showErrorDetail(e, 'textNumberFormat');
			// エラー時は処理なしで jQuery オブジェクト自体を返却
			return (this.each(function(i, dom) {
			}));
		}
	};
	/**
	 * 小数判定
	 *
	 * @param {string}
	 *            target 判定対象
	 * @return {bool} 判定結果 true:小数 / false:小数以外
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example if ($.isFloat(target)) { alert(target + ' is float.'); };
	 */
	jQuery.isFloat = function(target) {
		try {
			var split = null;
			split = jQuery.castString(target).split('.');
			split[0] = split[0].replace(/,/g, '');
			target = split.join('.');
			//
			// 数値形式にマッチするか判定
			var match = target.match(/^[-+]?\d+\.?\d*$/);
			if (match !== null) {
				return (true);
			} else {
				return (false);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'isFloat');
			return (false);
		}
	};

	/**
	 * 日付判定
	 *
	 * @param {string}
	 *            target 判定対象
	 * @return {bool} 判定結果 true:日付 / false:日付以外
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.mbTrim
	 * @requires jQuery.isInteger
	 * @requires jQuery.castNumber
	 * @requires jQuery.showErrorDetail
	 * @example if ($.isDate(target)) { alert(target + ' is date.'); };
	 */
	jQuery.isDate = function(target) {
		try {
			target = jQuery.mbTrim(target).replace(/[\.\-]/g, '/');
			//
			var split = target.split('/');
			var year = 0;
			var month = 0;
			var day = 0;
			if (split.length === 3) {
				// "/"で 3 分割
				year = split[0];
				month = split[1];
				day = split[2];
			} else {
				if (target.length === 8) {
					// 8文字
					year = target.substr(0, 4);
					month = target.substr(4, 2);
					day = target.substr(6, 4);
				} else {
					// それ以外
					return (false);
				}
			}
			//
			if (!jQuery.isInteger(year) || !jQuery.isInteger(month)
					|| !jQuery.isInteger(day)) {
				// 年月日のいずれかが数値以外
				return (false);
			}
			//
			year = jQuery.castNumber(year, false);
			month = jQuery.castNumber(month, false);
			day = jQuery.castNumber(day, false);
			//
			// 日付作成 -> 月は 0 から 11 で処理されるので -1
			var dateCheck = new Date(year, (month - 1), day);
			// 日付:2000/01/32 = 2000/02/01となることを利用して正当性確認
			if (dateCheck.getFullYear() === year
					&& dateCheck.getMonth() === (month - 1)
					&& dateCheck.getDate() === day) {
				// 正しい日付
				return (true);
			} else {
				// 日付以外
				return (false);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'isDate');
			return (false);
		}
	};

	/**
	 * 時刻判定
	 *
	 * @param {string}
	 *            target 判定対象
	 * @return {bool} 判定結果 true:時刻 / false:時刻以外
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.mbTrim
	 * @requires jQuery.isInteger
	 * @requires jQuery.castNumber
	 * @requires jQuery.showErrorDetail
	 * @example if ($.isTime(target)) { alert(target + ' is time.'); };
	 */
	jQuery.isTime = function(target) {
		try {
			target = jQuery.mbTrim(target).replace(/[\.\']/g, ':');
			//
			var split = target.split(':');
			var hour = 0;
			var minute = 0;
			var second = 0;
			if (split.length === 3) {
				// ":"で 3 分割
				hour = split[0];
				minute = split[1];
				second = split[2];
			} else {
				// それ以外
				return (false);
			}
			//
			if (!jQuery.isInteger(hour) || !jQuery.isInteger(minute)
					|| !jQuery.isInteger(second)) {
				// 時分秒のいずれかが数値以外
				return (false);
			}
			//
			hour = jQuery.castNumber(hour, false);
			minute = jQuery.castNumber(minute, false);
			second = jQuery.castNumber(second, false);
			//
			if (hour > -1 && hour < 24 && minute > -1 && minute < 60
					&& second > -1 && second < 60) {
				// 00:00:00 から 23:59:59 の間にある
				return (true);
			} else {
				return (false);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'isTime');
			return (false);
		}
	};

	/**
	 * 日時判定
	 *
	 * @param {string}
	 *            target 判定対象
	 * @return {bool} 判定結果 true:時刻 / false:時刻以外
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.mbTrim
	 * @requires jQuery.isDate
	 * @requires jQuery.isTime
	 * @requires jQuery.showErrorDetail
	 * @example if ($.isDatetime(target)) { alert(target + ' is datetime.'); };
	 */
	jQuery.isDatetime = function(target) {
		try {
			target = jQuery.mbTrim(target);
			//
			var split = target.split(' ');
			if (split.length !== 2) {
				// 日付 + ' ' + 時刻形式以外
				return (false);
			}
			//
			if (!jQuery.isDate(split[0])) {
				// 日付以外
				return (false);
			}
			if (!jQuery.isTime(split[1])) {
				// 時刻以外
				return (false);
			}
			//
			return (true);
		} catch (e) {
			jQuery.showErrorDetail(e, 'isDatetime');
			return (false);
		}
	};

	/**
	 * 郵便番号判定 (日本形式 ※非存在郵便番号の判定は不可)
	 *
	 * @param {string}
	 *            target 判定対象
	 * @return {bool} 判定結果 true:整数 7 桁 / false:整数 7 桁以外 [デフォルト:false]
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.mbTrim
	 * @requires jQuery.showErrorDetail
	 * @example if ($.isPostcode(target)) { alert(target + 'is post code
	 *          form.'); };
	 */
	jQuery.isPostcode = function(target) {
		try {
			target = jQuery.castString(target).replace(/[\-]/g, '');
			//
			// 郵便番号形式にマッチするか判定
			var match = null;
			match = target.match(/^[0-9]{7}$/);
			//
			if (match !== null) {
				return (true);
			} else {
				return (false);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'isPostcode');
			return (false);
		}
	};

	/**
	 * データ型判定 (配列 / 連想配列について一部曖昧)
	 *
	 * @param {object}
	 *            object オブジェクト
	 * @return {string} データ型 (null / undefined / function / NaN / Infinity /
	 *         string / number / boolean / array / object / hash / regexp / date /
	 *         error / jquery)
	 * @public
	 * @requires _getTypeSub
	 * @requires jQuery.showErrorDetail
	 * @example if ($.getType(target) === 'string') { alert('datatype is
	 *          string.'); };
	 */
	jQuery.getType = function(object) {
		try {
			return (_getTypeSub(object, true));
		} catch (e) {
			jQuery.showErrorDetail(e, 'getType');
			return ('');
		}
	};

	/**
	 * データ型判定 (配列 / 連想配列について一部曖昧)
	 *
	 * @param {object}
	 *            object オブジェクト
	 * @param {bool}
	 *            check true:再帰呼出確認 / false:通常呼出確認 [デフォルト:false]
	 * @return {string} データ型 (null / undefined / function / NaN / Infinity /
	 *         string / number / boolean / array / object / hash / regexp / date /
	 *         error / jquery)
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example if (_getTypeSub(target) === 'string') { alert('datatype is
	 *          string.'); };
	 */
	function _getTypeSub(object, check) {
		try {
			var type = typeof (object);
			var key = null;
			//
			// NULL
			if (object == null) {
				return ('null');
			}
			// 未定義
			if (type === 'undefined') {
				return ('undefined');
			}
			// 関数
			if (type === 'function') {
				return ('function');
			}
			// 文字列
			if (type === 'string' || (object instanceof String)) {
				return ('string');
			}
			if (type === 'number' || (object instanceof Number)) {
				// NaN
				if (isNaN(object)) {
					return ('NaN');
				}
				// Infinity
				if (object === Infinity) {
					return ('Infinity');
				}
				return ('number');
			}
			// 真偽値
			if (type === 'boolean' || (object instanceof Boolean)) {
				return ('boolean');
			}
			// 配列 -> 配列と連想配列の混合は判定不可?
			if (object instanceof Array) {
				if (object.length === 0) {
					for (key in object) {
						return ('hash');
					}
				}
				return ('array');
			}
			// 正規表現オブジェクト
			if (object instanceof RegExp) {
				return ('regexp');
			}
			// 日付オブジェクト
			if (object instanceof Date) {
				return ('date');
			}
			// エラーオブジェクト
			if (object instanceof Error) {
				return ('error');
			}
			// XMLHttpRequest オブジェクト
			if (object instanceof XMLHttpRequest) {
				return ('XMLHttpRequest');
			}
			// DOM要素
			if (typeof (object.nodeType) === 'number') {
				if (object.nodeType === 1) {
					return ('dom');
				}
			}
			// 連想配列 -> それ以外はオブジェクト
			if (object instanceof Object) {
				for (key in object) {
					if (typeof (object.size) === 'function') {
						return ('jquery');
					} else {
						return ('hash');
					}
				}
				return ('object');
			}
			//
			if (type === 'object' && check === true) {
				// 親 / 子画面の関数に {} / [] を渡してタイプ判別すると正確に取れないため (evel
				// はセキュリティ面から利用しない)
				return (_getTypeSub(JSON.parse(JSON.stringify(object)), !check));
			} else {
				return (type);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'getTypeSub');
			return ('');
		}
	}

	// ----------+[ jQuery UI 関連 ]+----------
	/**
	 * メッセージダイアログ表示
	 *
	 * @param {string}
	 *            text 表示メッセージ
	 * @param {hash}
	 *            option パラメータ
	 * @param {string}
	 *            option.caption ダイアログボックスタイトル [デフォルト:'']
	 * @param {string}
	 *            option.icon 表示アイコン error / exclamation / information / none /
	 *            question / worning / maintenance [デフォルト:none]
	 * @param {integer}
	 *            option.button 表示ボタン 1:OK only / 2:OK & CANCEL [デフォルト:1]
	 * @param {integer}
	 *            option.default ボタンの初期フォーカス 1:OK only / CANCEL [デフォルト:2]
	 * @param {function}
	 *            option.function ダイアログクローズ時実行関数
	 * @param {object}
	 *            option.argument ダイアログクローズ時実行関数への引数
	 * @public
	 * @requires jQuery UI dialog
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.getPath
	 * @requires jQuery.showErrorDetail
	 * @example $.msgBox('Error Message', {icon : 'error'});
	 */
	jQuery.msgBox = function(text, option) {
		try {
			if (typeof (jQuery('#dummy').dialog) === 'undefined') {
				// jQuery UI dialog なし
				alert('This method depends on the jQuery UI [dialog].');
				return;
			}
			//
			// デフォルトパラメータ設定
			var parameter = new Object();
			parameter['caption'] = '';
			parameter['icon'] = 'none';
			parameter['button'] = 1;
			parameter['default'] = 2;
			parameter['function'] = null;
			parameter['argument'] = null;
			// 引数設定
			if (option != null) {
				var key = null;
				for (key in option) {
					if (key in parameter) {
						parameter[key] = option[key];
					}
				}
			}
			// 引数整形
			parameter['caption'] = jQuery.castString(parameter['caption']);
			parameter['icon'] = jQuery.castString(parameter['icon']);
			parameter['button'] = jQuery.castNumber(parameter['button'], false);
			parameter['default'] = jQuery.castNumber(parameter['default'],
					false);
			//
			// タグ -> HTML コード
			text = jQuery.castHtml(text);
			// 改行コード -> <br /> タグ
			text = text.replace(/\r\n/g, '<br />').replace(/\r/g, '<br />')
					.replace(/\n/g, '<br />');
			//
			// アイコン用 HTML
			var htmlImg = '';
			switch (parameter['icon']) {
			case 'error':
				htmlImg = jQuery.getPath('img') + 'msgbox_icon_error.png';
				break;
			case 'exclamation':
				htmlImg = jQuery.getPath('img') + 'msgbox_icon_exclamation.png';
				break;
			case 'information':
				htmlImg = jQuery.getPath('img') + 'msgbox_icon_information.png';
				break;
			case 'question':
				htmlImg = jQuery.getPath('img') + 'msgbox_icon_question.png';
				break;
			case 'worning':
				htmlImg = jQuery.getPath('img') + 'msgbox_icon_warning.png';
				break;
			case 'maintenance':
				htmlImg = jQuery.getPath('img') + 'msgbox_icon_maintenance.png';
				break;
			default:
				break;
			}
			if (htmlImg !== '') {
				htmlImg = '<img src="' + htmlImg
						+ '" style="width:32px; height:32px;"/>';
			}
			// ボタン用 HTML
			var htmlButton = '';
			htmlButton += '<button type="button" class="msgbox-button-ok" style="width: 90px; font-family: monospace; font-size: small; white-space: nowrap;">';
			htmlButton += '<img src="'
					+ jQuery.getPath('img')
					+ 'msgbox_button_ok.png" style="vertical-align: bottom;" />&nbsp;OK';
			htmlButton += '</button>';
			if (parameter['button'] !== 1) {
				// OK & CANCEL
				htmlButton += '&nbsp;';
				htmlButton += '<button type="button" class="msgbox-button-cencel" style="width: 90px; font-family: monospace; font-size: small; white-space: nowrap;">';
				htmlButton += '<img src="'
						+ jQuery.getPath('img')
						+ 'msgbox_button_cancel.png" style="vertical-align: bottom;" />&nbsp;CANCEL';
				htmlButton += '</button>';
			}
			//
			// msgBox 用の HTML
			var windowWidth = parseInt(jQuery(window).width() * 0.8, 10);
			var windowHeight = parseInt(jQuery(window).height() * 0.8, 10);
			var html = '';
			html += '<div class="jquery-message-box" title="'
					+ parameter['caption']
					+ '" style="font-size: small; font-weight: normal; min-width: 200px; max-width: '
					+ windowWidth
					+ 'px; max-height: '
					+ windowHeight
					+ 'px; position: absolute; top: 0px; left: 0px; overflow: hidden; visibility: hidden;">';
			html += '<table style="border-collapse: collapse;">';
			html += '<tbody>';
			html += '<tr>';
			html += '<td style="vertical-align: top;">';
			html += htmlImg;
			html += '</td>';
			html += '<td style="vertical-align: top; padding: 5px 0px 0px 10px;">';
			html += '<div style="max-width: ' + (windowWidth - 50)
					+ 'px; max-height: ' + (windowHeight - 70)
					+ 'px; overflow-x: auto; overflow-ｙ: auto;">';
			html += text + '<br /><br />';
			html += '</div>';
			html += '</td>';
			html += '</tr>';
			html += '</tbody>';
			html += '</table>';
			html += '<br />';
			html += '<table style="width: 100%; border-collapse: collapse;">';
			html += '<tbody>';
			html += '<tr>';
			html += '<td style="text-align: center;">';
			html += htmlButton;
			html += '</td>';
			html += '</tr>';
			html += '</tbody>';
			html += '</table>';
			html += '</div>';
			//
			// 非表示状態でbodyに追加
			jQuery('body').append(html);
			//
			// 要素取得
			var div = jQuery('.jquery-message-box:last');
			//
			// サイズ調整
			var width = div.width() + 30;
			div.css({
				'position' : 'static',
				'float' : ''
			});
			//
			// イベント追加
			var ok = jQuery('.msgbox-button-ok', div);
			var cancel = jQuery('.msgbox-button-cencel', div);
			ok.click(function(event) {
				try {
					if (typeof (parameter['function']) === 'function') {
						parameter['function'](true, parameter['argument']);
					}
					div.dialog('close');
				} catch (e) {
					jQuery.showErrorDetail(e, 'msgBox ok click');
				}
			}).keydown(function(event) {
				try {
					if (event.keyCode === 39) {
						cancel.focus();
					}
				} catch (e) {
					jQuery.showErrorDetail(e, 'msgBox ok keydown');
				}
			});
			cancel.click(function(event) {
				try {
					if (typeof (parameter['function']) === 'function') {
						parameter['function'](false, parameter['argument']);
					}
					div.dialog('close');
				} catch (e) {
					jQuery.showErrorDetail(e, 'msgBox cancel click');
				}
			}).keydown(function(event) {
				try {
					if (event.keyCode === 37) {
						ok.focus();
					}
				} catch (e) {
					jQuery.showErrorDetail(e, 'msgBox cancel keydown');
				}
			});
			//
			// ダイアログ設定
			div.dialog({
				autoOpen : false,
				width : width,
				modal : true,
				draggable : true,
				resizable : false,
				closeOnEscape : false,
				create : function(event, ui) {
					try {
						// 非表示設定 -> 表示設定
						jQuery(this).css({
							'visibility' : 'visible'
						});
						jQuery('.ui-dialog-titlebar').css({
							'font-size' : 'small',
							'font-family' : 'monospace',
							'font-weight' : 'normal',
							'overflow' : 'hidden'
						});
					} catch (e) {
						jQuery.showErrorDetail(e, 'msgBox dialog create');
					}
				},
				open : function(event, ui) {
					try {
						if (parameter['button'] === 2
								&& parameter['default'] === 2) {
							// Cancelボタンフォーカス
							cancel.focus();
						} else {
							ok.focus();
						}
					} catch (e) {
						jQuery.showErrorDetail(e, 'msgBox dialog open');
					}
				},
				close : function(event, ui) {
					try {
						div.dialog('destroy');
						div.remove();
					} catch (e) {
						jQuery.showErrorDetail(e, 'msgBox dialog close');
					}
				}
			});
			//
			// ダイアログ呼出
			div.dialog('open');
		} catch (e) {
			jQuery.showErrorDetail(e, 'msgBox');
		}
	};

	/**
	 * Date Picker の追加
	 *
	 * @param {object}
	 *            object 追加対象 jQuery オブジェクト
	 * @public
	 * @requires jQuery UI datepicker
	 * @requires jQuery.getJQuery
	 * @requires jQuery.getPath
	 * @requires jQuery.showErrorDetail
	 * @example $.appendDatepicker($('#date-input'));
	 */
	jQuery.appendDatepicker = function(object) {
		try {
			if (typeof (jQuery('#dummy').datepicker) === 'undefined') {
				// jQuery UI datepicker なし
				alert('This method depends on the jQuery UI [datepicker].');
				return;
			}
			//
			object = jQuery.getJQuery(object, false);
			if (object === null) {
				return;
			}
			//
			// 二重付加回避
			object.datepicker('destroy');
			//
			var now = new Date();
			var year = now.getFullYear();
			var minDate = new Date(1900, 0, 1);
			var maxDate = new Date(9999, 11, 31);
			object.datepicker({
				dateFormat : 'yy/mm/dd',
				showOn : 'button',
				buttonImage : jQuery.getPath('img')
						+ '1371570296_tear_off_calendar.png',
				buttonImageOnly : true,
				buttonText : '日付を選択してください',
				showButtonPanel : false,
				showMonthAfterYear : true,
				monthNames : [ '1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月',
						'9月', '10月', '11月', '12月' ],
				monthNamesShort : [ '1月', '2月', '3月', '4月', '5月', '6月', '7月',
						'8月', '9月', '10月', '11月', '12月' ],
				dayNamesMin : [ '日', '月', '火', '水', '木', '金', '土' ],
				dayNamesShort : [ '日', '月', '火', '水', '木', '金', '土' ],
				changeMonth : true,
				changeYear : true,
				minDate : minDate,
				maxDate : maxDate,
				onSelect : function(dateText, inst) {
					try {
						jQuery(this).trigger('change').blur().focusout()
								.focus();
						jQuery(this).datepicker('hide');
					} catch (e) {
						jQuery.showErrorDetail(e,
								'appendDatepicker datepicker onSelect');
					}
				},
				yearRange : (year - 100) + ':' +  (year + 100)
			});
			object.blur(function() {
				var oDate = new Date(object.val());
				if( oDate < minDate || oDate > maxDate ) {
					object.val('');
				}
			});
		} catch (e) {
			jQuery.showErrorDetail(e, 'appendDatepicker');
		}
	};

	/**
	 * Year-Month Picker の追加
	 *
	 * @param {object}
	 *            object 追加対象 jQuery オブジェクト
	 * @public
	 * @requires jQuery UI ympicker
	 * @requires jQuery.getJQuery
	 * @requires jQuery.getPath
	 * @requires jQuery.showErrorDetail
	 * @example $.appendDatepicker($('#date-input'));
	 */
	jQuery.appendYmpicker = function(object, minDate, maxDate, disabled) {
		try {
			var className = 'has-ym-picker';
			if(!minDate){
				minDate = new Date(1900, 0, 1);
			}
			if(!maxDate){
				maxDate = new Date(9999, 11, 31);
			}
			//
			if (typeof (jQuery('#dummy').ympicker) === 'undefined') {
				// jQuery UI ympicker なし
				alert('This method depends on the jQuery UI custom [ympicker].');
				return;
			}
			//
			object = jQuery.getJQuery(object, false);
			if (object === null) {
				return;
			}
			var now = new Date();
			var year = now.getFullYear();
			//
			object.each(function(index, dom) {
				try {
					var target = jQuery(this);
					//
					if (target.next('.' + className).size() === 0) {
						target.after('<input type="text" class="' + className
								+ '" style="display: none;" />');
					}
					var dummy = target.next('.' + className);
					//
					// 二重付加回避
					dummy.ympicker('destroy');
					//
					dummy.ympicker({
						dateFormat : 'yy/mm/dd',
						showOn : 'button',
						buttonImage : '/images/calendar-icon.ico',
						buttonImageOnly : true,
						buttonText : '年月を選択してください',
						showButtonPanel : true,
						showMonthAfterYear : true,
						monthNames : [ '&nbsp;1月', '2月', '3月', '4月', '5月',
								'6月', '7月', '8月', '9月', '10月', '11月', '12月' ],
						monthNamesShort : [ '&nbsp;1月', '2月', '3月', '4月', '5月',
								'6月', '7月', '8月', '9月', '10月', '11月', '12月' ],
						changeMonth : true,
						changeYear : true,
						minDate : minDate,
						maxDate : maxDate,
						disabled: disabled,
						altField : target,
						altFormat : 'yy/mm',
						beforeShow : function(input, inst) {
							try {
								jQuery(input).val(
										jQuery.textDateFormat(target.val(),
												'ym')
												+ '/01');
							} catch (e) {
								jQuery.showErrorDetail(e,
										'appendYmpicker ympicker beforeShow');
							}
						},
						onSelect : function(dateText, inst) {
							var input = $(this).prev();
							input.focus();
							if(input.hasClass('from-month')){
								input.val(dateText);
							}
							try {
								target.trigger('change').blur()
										.focusout().focus();
								target.datepicker('hide');
							} catch (e) {
								jQuery.showErrorDetail(e,
										'appendYmpicker ympicker onSelect');
							}
						},
						yearRange : (year - 100) + ':' +  (year + 100)
					});
				} catch (e) {
					jQuery.showErrorDetail(e, 'appendYmpicker each');
				}
			});
		} catch (e) {
			jQuery.showErrorDetail(e, 'appendYmpicker');
		}
	};


	jQuery.disableYmpicker = function(object) {
		object.prop("disabled", true);
		var img = object.next().next();
		img.unbind('click').css({opacity: '0.5', cursor: 'default'});
	};
	jQuery.enableYmpicker = function(object) {
		object.prop("disabled", false);
		$.appendYmpicker(object);
	};

	/**
	 * jQuery Object取得
	 *
	 * @param {object}
	 *            object jQuery オブジェクト / jQuery セレクター
	 * @param {bool}
	 *            one true:複数の場合最初の 1 つのみ / false:全て [デフォルト:false]
	 * @return {object} jQuery オブジェクト (取得出来ない場合は null)
	 * @public
	 * @requires jQuery.getType
	 * @requires jQuery.left
	 * @requires jQuery.showErrorDetail
	 * @example var object = $.getJQuery('element-id');
	 */
	jQuery.getJQuery = function(object, one) {
		try {
			var jquery = null;
			var type = jQuery.getType(object);
			var classId = '';
			if (type === 'string') {
				if (object === '') {
					jquery = null;
				} else {
					classId = jQuery.left(object, 1);
					if (classId === '#' || classId === '.') {
						// id または class を直接指定した場合
						if (jQuery(object).size() > 0) {
							// selector 文字列ダイレクト
							jquery = jQuery(object);
						} else {
							jquery = null;
						}
					} else {
						if (jQuery(object).size() > 0) {
							// selector 文字列ダイレクト
							jquery = jQuery(object);
						} else {
							if (jQuery('#' + object).size() > 0) {
								// id 名
								jquery = jQuery('#' + object);
							} else {
								// class 名
								if (jQuery('.' + object).size() > 0) {
									jquery = jQuery('.' + object);
								} else {
									// タグ名
									if (jQuery(object).size() > 0) {
										jquery = jQuery(object);
									} else {
										// 存在しない selector
										jquery = null;
									}
								}
							}
						}
					}
				}
			} else if (type === 'jquery') {
				// そもそも jQuery Object が渡された場合
				jquery = object;
			} else if (type === 'dom') {
				// DOM 要素
				jquery = jQuery(object);
			} else {
				jquery = null;
			}
			//
			if (jquery == null) {
				return (null);
			} else {
				if (one === true) {
					// 最初の 1 つのみ返却
					return (jquery.eq(0));
				} else {
					return (jquery);
				}
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'getJQuery');
			return (null);
		}
	};

	/**
	 * サジェスト(オートコンプリート)機能
	 *
	 * @param {object}
	 *            object サジェストを追加する jQuery オブジェクト
	 * @param {string}
	 *            target サジェストのデータを取得する view または テーブル名
	 * @param {string}
	 *            label 検索結果に表示する列名
	 * @param {string}
	 *            value 選択時にテキストボックスに書き込む値の列名
	 * @param {mixed}
	 *            where LIKE 検索する対象の列名 (配列 or 文字列)
	 * @param {object}
	 *            option オプション
	 * @param {string}
	 *            option.minLength 検索開始文字数 [デフォルト:1]
	 * @param {string}
	 *            option.joint true:label を "value : Label" で表示 [デフォルト:false]
	 * @param {integer}
	 *            option.maxRows 検索結果の最大数 (0:全件表示) [デフォルト:20]
	 * @param {string}
	 *            option.addWhere ユーザ追加WHERE区 [デフォルト:'']
	 * @see option['addWhere'] は 'WHERE' 自体は記述しない
	 * @see where / option['addWhere'] に insert / delete / update / execute
	 *      の文字が含まれる場合実行しない
	 * @public
	 * @requires _phpFile['autocomplete'] で定義された PHP ファイル
	 * @requires jQuery UI autocomplete
	 * @requires jQuery.getJQuery
	 * @requires jQuery.showErrorDetail
	 * @requires jQuery.castHtml
	 * @example $.appendAutocomplete( $('#sample'), 'table_view_name',
	 *          'column_label', 'column_value', ['column1', 'column2',
	 *          'column3'], { maxRows : 20, addWhere : 'column4 = 4' } );
	 */
	jQuery.appendAutocomplete = function(object, target, label, value, where,
			option) {
		try {
			if (typeof (jQuery('#dummy').autocomplete) === 'undefined') {
				// jQuery UI autocomplete なし
				alert('This method depends on the jQuery UI [autocomplete].');
				return;
			}
			//
			object = jQuery.getJQuery(object, false);
			if (object === null) {
				return;
			}
			//
			// 二重付加回避
			try {
				object.autocomplete('destroy');
			} catch (e) {
				//
			}
			//
			target = jQuery.mbTrim(target);
			label = jQuery.mbTrim(label);
			value = jQuery.mbTrim(value);
			var type = jQuery.getType(where);
			if (type === 'string') {
				var tmp = where;
				where = new Array();
				where[0] = tmp;
			} else if (type == 'array') {
				var i = 0;
				var len = where.length;
				for (i = 0; i < len; i++) {
					where[i] = jQuery.mbTrim(where[i]);
				}
			} else {
				where = new Array();
			}
			where[where.length] = label;
			if (target === '' || label === '' || value === ''
					|| where.length === 0) {
				return;
			}
			//
			// パラメータ初期値
			var parameter = new Object();
			parameter['minLength'] = 1;
			parameter['joint'] = false;
			parameter['maxRows'] = 20;
			parameter['addWhere'] = '';
			//
			// ユーザオプション取得
			if (option != null) {
				var key = null;
				for (key in option) {
					if (key in parameter) {
						parameter[key] = option[key];
					}
				}
			}
			parameter['minLength'] = jQuery.castNumber(parameter['minLength'],
					false);
			parameter['maxRows'] = jQuery.castNumber(parameter['maxRows'],
					false);
			parameter['addWhere'] = jQuery.castString(parameter['addWhere']);
			if (parameter['minLength'] < 1) {
				parameter['minLength'] = 1;
			}
			if (parameter['maxRows'] < 0) {
				parameter['maxRows'] = 20;
			}
			//
			object
					.autocomplete(
							{
								delay : 300, // 検索までの遅延時間(ミリ秒)
								// ※リモートデータの場合値が小さいとレスポンスがない内にロードが走る
								// [デフォルト:300]
								minLength : parameter['minLength'], // 検索実行を開始する文字数
								// ※リモートデータの場合値が小さいとヒットする数が膨大になる
								source : function(request, response) {
									try {
										var ajax = jQuery.data(object, 'ajax');
										if (ajax != null) {
											// 前回の通信が完了していない -> 中断
											ajax.abort();
										}
										//
										ajax = jQuery
												.ajax({
													url : jQuery.getPath('php')
															+ _phpFile['autocomplete'],
													dataType : 'text',
													type : 'post',
													timeout : 600000,
													cache : false,
													data : {
														target : target,
														label : label,
														value : value,
														where : where,
														joint : ((parameter['joint'] === true) ? 1
																: 0),
														maxRows : parameter['maxRows'],
														addWhere : parameter['addWhere'],
														request : request['term']
													},
													success : function(data,
															dataType) {
														try {
															var dataSet = null;
															try {
																// データ変換 (json
																// -> hash)
																dataSet = JSON
																		.parse(data);
																// 返却データなし
																if (dataSet == null) {
																	return;
																}
															} catch (e) {
																if (data != null
																		&& data !== '') {
																	// eval
																	// 実行時エラー
																	alert('An unexpected error occurred.\n'
																			+ data);
																}
																return;
															}
															//
															if ('error' in dataSet) {
																// エラーあり
																alert('----------+[ ERROR ]+----------\n'
																		+ dataSet['error']);
																return;
															}
															if ('data' in dataSet) {
																// 返却あり
																if (jQuery
																		.dataSetCheck(
																				dataSet['data'],
																				0)) {
																	// データあり ->
																	// 整形
																	response(jQuery
																			.map(
																					dataSet['data'][0],
																					function(
																							item,
																							index) {
																						return ({
																							'label' : jQuery
																									.castHtml(item['label']),
																							'value' : jQuery
																									.castHtml(item['value'])
																						});
																					}));
																}
															}
														} catch (e) {
															jQuery
																	.showErrorDetail(
																			e,
																			'appendAutocomplete ajax success');
														}
													},
													complete : function(
															xmlHttpRequest,
															textStatus) {
														try {
															// 通信が完了したら記憶 data
															// 削除
															jQuery.removeData(
																	object,
																	'ajax');
														} catch (e) {
															jQuery
																	.showErrorDetail(
																			e,
																			'appendAutocomplete ajax complete');
														}
													}
												});
										//
										jQuery.data(object, 'ajax', ajax);
									} catch (e) {
										jQuery.showErrorDetail(e,
												'appendAutocomplete source');
									}
								},
								search : function(event, ui) {
									try {
										if (event.keyCode === 229) {
											return (false);
										}
										return (true);
									} catch (e) {
										jQuery.showErrorDetail(e,
												'appendAutocomplete select');
										return (false);
									}
								},
								select : function(event, ui) {
									try {
										jQuery(this).autocomplete('close')
												.focus();
									} catch (e) {
										jQuery.showErrorDetail(e,
												'appendAutocomplete select');
									}
								}
							}).keyup(
							function(event) {
								try {
									if (event.keyCode === 13) {
										// 日本語対策
										jQuery(this).autocomplete('search');
									}
								} catch (e) {
									jQuery.showErrorDetail(e,
											'appendAutocomplete keyup');
								}
							});
		} catch (e) {
			jQuery.showErrorDetail(e, 'appendAutocomplete');
		}
	};

	// ----------+[ データベース関連 ]+----------
	/**
	 * SQL の発行
	 *
	 * @param {string}
	 *            sql SQL文
	 * @param {function}
	 *            userFunction 通信完了後に実行するコールバック関数
	 * @param {object}
	 *            userArgument コールバック関数への引数
	 * @param {bool}
	 *            async 通信方式 true:非同期 / false:同期 [デフォルト:false]
	 * @param {object}
	 *            request 参照渡 (Object インスタンスを渡す) -> XMLHttpRequest オブジェクトを格納
	 * @return {mixed} SQL 実行結果 ※非同期通信の場合は NULL 値 / エラー時は false
	 * @public
	 * @requires _phpFile['database'] で定義された PHP ファイル
	 * @requires jQuery.preloader
	 * @requires jQuery.showErrorDetail
	 * @example $.executeSql('EXECUTE SPC_SAMPLE');
	 */
	jQuery.executeSql = function(sql, userFunction, userArgument, async,
			request) {
		try {
			var timeStart = null;
			if (_debugFlag === true && typeof (console) === 'object') {
				// デバッグ時 -> 時間測開始時刻
				timeStart = new Date();
			}
			//
			// 変数型整形
			sql = jQuery.castString(sql);
			// プリローダー表示
			// jQuery.preloader(true);
			//
			// 通信形式確認
			if (async === true) {
				async = true;
			} else {
				async = false;
			}
			//
			var dataSet = null; // SQL 実行の結果セット
			var error = false; // 実行時エラーフラグ
			//
			if (_debugFlag === true && typeof (console) === 'object') {
				// デバッグ時 -> コンソールに SQL 表示 (FireBug等で確認可能)
				console.log(sql);
			}
			//
			// Ajax通信 -> SQL 実行 PHP 呼出
			request = new jQuery.ajax({
				async : async,
				url : jQuery.getPath('php') + _phpFile['database'],
				data : {
					'sql' : sql
				},
				type : 'post',
				dataType : 'text',
				timeout : 600000,
				cache : false,
				success : function(data, dataType) {
					try {
						// データ変換 (json -> hash)
						dataSet = JSON.parse(data);
						error = true;
					} catch (e) {
						if (data == null || data === '') {
							alert('no reply ...');
						} else {
							// json 以外
							alert(data);
							dataSet = false;
						}
					}
				},
				complete : function(xmlHttpRequest, textStatus) {
					try {
						// プリローダー非表示
						// jQuery.preloader(false);
						//
						if (textStatus === 'success') {
							// リクエスト成功
							if (error && typeof (userFunction) === 'function') {
								// 関数実行
								userFunction(dataSet, userArgument);
							}
						} else {
							alert('Ajax error : status [' + textStatus + ']');
						}
						if (_debugFlag === true
								&& typeof (console) === 'object') {
							// デバッグ時 -> 通信時間表示
							var timeEnd = new Date();
							console.log((timeEnd - timeStart) + 'micro sec');
						}
					} catch (e) {
						jQuery.showErrorDetail(e, 'executeSql complete');
						dataSet = false;
					}
				}
			});
			//
			return (dataSet);
		} catch (e) {
			// プリローダー非表示
			// jQuery.preloader(false);
			jQuery.showErrorDetail(e, 'executeSql');
			return (null);
		}
	};

	/**
	 * プリローダー
	 *
	 * @param {bool}
	 *            display true:表示 / false:非表示
	 * @public
	 * @example $.preloader(true);
	 */
	jQuery.preloader = function(display) {
		try {
			// プリローダー用 id
			var preloaderId = 'has-modal-preloader';
			var preloaderImage = 'preloader.gif';
			//
			if (display === true) {
				// 表示
				jQuery(document).bind('keydown', _preloaderKeyEvent).bind(
						'keyup', _preloaderKeyEvent).bind('keypress',
						_preloaderKeyEvent).bind('mousedown',
						_preloaderMouseEvent).bind('mouseup',
						_preloaderMouseEvent);
				//
				var body = jQuery('html, body');
				var top = jQuery.castNumber(body.scrollTop(), false);
				var left = jQuery.castNumber(body.scrollLeft(), false);
				//
				// <div> 用 CSS -> 画面いっぱいに <div> を広げてモーダルに見せかける
				var cssDiv = {
					'position' : 'absolute',
					'top' : top + 'px',
					'left' : left + 'px',
					'width' : '100%',
					'height' : '100%',
					'background-color' : '#000000',
					'filter' : 'alpha(opacity=50)',
					'-moz-opacity' : 0.5,
					'opacity' : 0.5,
					'z-index' : 999
				};
				// プリローダー画像
				var cssImg = {
					'position' : 'absolute',
					'top' : '50%',
					'left' : '50%',
					'width' : '64px',
					'height' : '64px',
					'margin-left' : '-32px', // widthの半分を指定
					'margin-top' : '-32px', // heightの半分を指定
					'z-index' : 10000
				};
				// body追加用のHTML文
				var htmlDiv = '<div id="' + preloaderId + '-div"></div>';
				var htmlImg = '<img id="' + preloaderId + '-img" src="'
						+ jQuery.getPath('img') + preloaderImage + '">';
				//
				var div = jQuery(htmlDiv);
				var img = jQuery(htmlImg);
				div.css(cssDiv);
				img.css(cssImg);
				//
				// 念の為削除
				jQuery('#' + preloaderId + '-div').remove();
				jQuery('#' + preloaderId + '-img').remove();
				// 追加
				jQuery('body').append(div).append(img);
			} else {
				// 非表示
				// イベント削除
				jQuery(document).unbind('keydown', _preloaderKeyEvent).unbind(
						'keyup', _preloaderKeyEvent).unbind('keypress',
						_preloaderKeyEvent).unbind('mousedown',
						_preloaderMouseEvent).unbind('mouseup',
						_preloaderMouseEvent);
				//
				// プリローダー削除
				jQuery('#' + preloaderId + '-div').remove();
				jQuery('#' + preloaderId + '-img').remove();
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'preloader');
		}
	};

	/**
	 * プリローダー時のキーイベント (focus unbind 特定用)
	 *
	 * @return {bool} true:F5キー / false:F5以外
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example jQuery(this).unbind('keydown', _preloaderKeyEvent);
	 */
	function _preloaderKeyEvent(event) {
		try {
			if (event.keyCode !== 116) {
				// F5 以外禁止
				return (false);
			} else {
				return (true);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'preloaderKeyEvent');
			return (false);

		}
	}

	/**
	 * プリローダー時のマウスイベント (focus unbind 特定用)
	 *
	 * @return {bool} 常に false
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example jQuery(this).unbind('keydown', _preloaderMouseEvent);
	 */
	function _preloaderMouseEvent(event) {
		try {
			return (false);
		} catch (e) {
			jQuery.showErrorDetail(e, 'preloaderMouseEvent');
			return (false);

		}
	}

	/**
	 * 結果セットレコードのNULL置換
	 *
	 * @param {string}
	 *            data 対象文字列
	 * @param {mixed}
	 *            replace 置換文字列 [デフォルト:'']
	 * @return {string} 置換文字列
	 * @public
	 * @requires jQuery.showErrorDetail
	 * @example $.convertSqlNull(dataSet[0][0]['column'], '');
	 */
	jQuery.convertSqlNull = function(data, replace) {
		try {
			if (typeof (replace) === 'undefined') {
				replace = '';
			}
			//
			if (data == null) {
				return (replace);
			} else {
				return (data);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'convertSqlNull');
			return ('');
		}
	};

	/**
	 * SQL インジェクション対策
	 *
	 * @param {string}
	 *            string 対象文字列
	 * @param {bool}
	 *            time 時間型フラグ true:時間 / falss:非時間 [デフォルト:false]
	 * @param {bool}
	 *            unicode Unicode対応 ture:対応 / false:非対応 [デフォルト:false]
	 * @return {string} SQLインジェクション対策済文字列
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example $.convertSqlValue(string, true);
	 */
	jQuery.convertSqlValue = function(string, time, unicode) {
		try {
			// NULLチェック
			if (time === true) {
				// 時間型
				if (string == null || string === '') {
					return ('NULL');
				}
			} else {
				// 非時間型
				if (string == null) {
					return ('NULL');
				}
			}
			//
			// インジェクション対策
			if (string == null) {
				return ('NULL');
			}
			string = jQuery.castString(string);
			string = string.replace(/\'/g, '\'\'');
			if (unicode === true) {
				// Unicode対応
				string = 'N\'' + string + '\'';
			} else {
				// Unicode非対応
				string = '\'' + string + '\'';
			}
			return (string);
		} catch (e) {
			jQuery.showErrorDetail(e, 'convertSqlValue');
			return ('');
		}
	};

	/**
	 * データセットのデータ有無確認
	 *
	 * @param {array}
	 *            dataSet データセット
	 * @param {integer}
	 *            index データセットインデックス
	 * @param {bool}
	 *            countFlag 行数確認フラグ true:確認 / false:非確認 [デフォルト:true]
	 * @return {bool} true:データ有り / false:データ無し
	 * @public
	 * @requires jQuery.showErrorDetail
	 * @example if (!$.dataSetCheck(dataSet, 0)) { alert('No data!'); }
	 */
	jQuery.dataSetCheck = function(dataSet, index, countFlag) {
		try {
			if (dataSet !== false) {
				if (dataSet != null) {
					if (index in dataSet) {
						if (dataSet[index] != null) {
							if (countFlag !== false) {
								if (dataSet[index].length > 0) {
									return (true);
								} else {
									// indexに対するデータがない
									return (false);
								}
							} else {
								// 行数非確認
								return (true);
							}
						} else {
							// indexに対するデータが空
							return (false);
						}
					} else {
						// indexが存在していない
						return (false);
					}
				} else {
					// データ自体が空
					return (false);
				}
			} else {
				// データベースでのエラー
				return (false);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'dataSetCheck');
			return (false);
		}
	};

	// ----------+[ パス関連 ]+----------
	/**
	 * 絶対パスの導出
	 *
	 * @param {string}
	 *            base 基準パス [デフォルト:location.pathname]
	 * @param {string}
	 *            relative 相対パス
	 * @return {string} 絶対パス
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var absPath = $.relativeToAbsolute('/sample/dir/img',
	 *          './../css/template.css');
	 */
	jQuery.relativeToAbsolute = function(base, relative) {
		try {
			var slash = '';
			//
			base = jQuery.castString(base);
			relative = jQuery.castString(relative);
			if (base === '') {
				// 基準パスがない場合 -> 自分自身を基準とする
				base = location.pathname;
			}
			// base の先頭が "/" か確認
			if (jQuery.left(base, 1) === '/') {
				slash = '/';
			}
			//
			// 初期絶対パス -> ベースと相対パスの単純結合
			var absolute = '/' + base + '/' + relative;
			// 文字列整形 -> "\" を "/" に置換
			absolute = absolute.replace(/\\/g, '/');
			// "/"で分割
			var path = absolute.split('/');
			// 空白要素 or 自分参照削除
			var i = 0;
			for (i = path.length - 1; i > -1; i--) {
				if (path[i] === '' || path[i] === '.') {
					path.splice(i, 1);
				}
			}
			// 解析
			var len = path.length;
			var j = 0;
			for (i = 1; i < len; i++) {
				// 上層参照の場合 -> 上層で空要素でない要素を空にする (ルートは削除しない)
				if (path[i] === '..') {
					for (j = i - 1; j > 0; j--) {
						if (path[j] !== '') {
							path[j] = '';
							break;
						}
					}
					// 自分を空にする
					path[i] = '';
				}
			}
			// 空白要素削除
			for (i = path.length - 1; i > -1; i--) {
				if (path[i] === '') {
					path.splice(i, 1);
				}
			}
			//
			return (slash + path.join('/'));
		} catch (e) {
			jQuery.showErrorDetail(e, 'relativeToAbsolute');
			return ('');
		}
	};

	/**
	 * ルートディレクトリの取得
	 *
	 * @return {string} ルートディレクトリのパス ("/"から開始し"/"で終了)
	 * @public
	 * @requires jQuery.showErrorDetail
	 * @example var root = $.getRoot();
	 */
	jQuery.getRoot = function() {
		try {
			var root = location.pathname.split('/')[1];
			// 文字列整形 -> 先頭・末尾に"/"追加 (※ルートディレクトリは"/"から開始)
			return ('/' + root + '/');
		} catch (e) {
			jQuery.showErrorDetail(e, 'getRoot');
			return ('');
		}
	};

	/**
	 * 各ディレクトリへのパス取得
	 *
	 * @param {string}
	 *            key キー
	 * @return {string} キーに対するルートディレクトリからの絶対パス ("/"で終了)
	 * @public
	 * @requires const.js (constPath)
	 * @requires jQuery.relativeToAbsolute
	 * @requires jQuery.showErrorDetail
	 * @example var path = $.getPath('img');
	 */
	jQuery.getPath = function(key) {
		try {
			// 定数確認 -> constPath の設定
			if (typeof (constPath) === 'undefined') {
				alert('\'constPath\' is not defined.');
				return ('');
			}
			// 定数確認 -> constPath[key] の設定
			if (jQuery.getType(constPath) !== 'hash') {
				alert('\'constPath\' is not a hash variable.');
				return ('');
			}
			if (!(key in constPath)) {
				alert('\'constPath.' + key + '\' is not defined.');
				return ('');
			}
			// ルートパスを基準に絶対パス導出 + "/"で終了
			var path = constPath[key] + '/';
			//
			return (path);
		} catch (e) {
			jQuery.showErrorDetail(e, 'getPath');
			return ('');
		}
	};

	// ----------+[ 数値操作関連 ]+----------
	/**
	 * 数値処理
	 *
	 * @param {float}
	 *            numeric 処理対象数値
	 * @param {integer}
	 *            mode モード 1:四捨五入 / 2:切り捨て / 3:切り上げ / 4:そのまま [デフォルト:4]
	 * @param {integer}
	 *            digit 小数点以下第何位まで表示するか [デフォルト:0] ※マイナスの場合、整数部分を処理
	 * @param {bool}
	 *            comma 金額用カンマ true:カンマ有り / false:カンマ無し [デフォルト:false]
	 * @return {string} 処理後の値
	 * @public
	 * @requires jQuery.castNumber
	 * @requires jQuery.showErrorDetail
	 * @example var numeric = $.numericalProcess('12345.678', 1, 2, true);
	 */
	jQuery.numericalProcess = function(numeric, mode, digit, comma) {
		try {
			numeric = jQuery.castNumber(numeric, true);
			mode = jQuery.castNumber(mode, false);
			digit = jQuery.castNumber(digit, true);
			if (mode < 1 || mode > 4) {
				mode = 4;
			}
			//
			// 正負判定
			var minusFlag = 0;
			if (numeric < 0) {
				minusFlag = 1;
				numeric *= -1;
			}
			// 桁移動
			numeric *= Math.pow(10, digit);
			// モード分岐
			switch (mode) {
			case 1:
				// 四捨五入
				numeric = Math.round(numeric);
				break;
			case 2:
				// 切り捨て
				numeric = Math.floor(numeric);
				break;
			case 3:
				// 切り上げ
				numeric = Math.ceil(numeric);
				break;
			case 4:
				// そのまま
				break;
			default:
				break;
			}
			// 桁戻し
			numeric /= Math.pow(10, digit);
			// 正負判定
			if (minusFlag === 1) {
				numeric *= -1;
			}
			// カンマ判定
			if (comma === true) {
				numeric += '';
				while (numeric != (numeric = numeric.replace(/^(-?\d+)(\d{3})/,
						'$1,$2')))
					;
			}
			//
			return (numeric + '');
		} catch (e) {
			jQuery.showErrorDetail(e, 'numericalProcess');
			return ('0');
		}
	};

	/**
	 * 乱数生成
	 *
	 * @param {integer}
	 *            min 乱数最小値
	 * @param {integer}
	 *            max 乱数最大値
	 * @return {integer} min から max までの乱数
	 * @public
	 * @requires jQuery.castNumber
	 * @requires jQuery.showErrorDetail
	 * @example var random = $.generateRandomNumber(-10, 10);
	 */
	jQuery.generateRandomNumber = function(min, max) {
		try {
			// 整数判定
			min = jQuery.castNumber(min, true);
			max = jQuery.castNumber(max, true);
			// min = max -> minを固定値で返却
			if (min === max) {
				return (max);
			}
			// minがmaxより大きい -> 入替え
			if (min > max) {
				var tmp = min;
				min = max;
				max = tmp;
			}
			return (Math.floor(Math.random() * (max - min + 1)) + min);
		} catch (e) {
			jQuery.showErrorDetail(e, 'generateRandomNumber');
			return (min);
		}
	};

	// ----------+[ 文字列操作関連 ]+----------
	/**
	 * マルチバイトtrim (全半角スペース + 改行 + タブ)
	 *
	 * @param {string}
	 *            target 対象文字列
	 * @return {string} trim 後文字列
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var trimString = $.mbTrim(string);
	 */
	jQuery.mbTrim = function(target) {
		try {
			var tmpString = jQuery.castString(target);
			tmpString = jQuery.trim(tmpString);
			tmpString = tmpString.replace(/^[\s\t\n\r　]+|[\s\t\n\r　]+$/g, '');
			return (tmpString);
		} catch (e) {
			jQuery.showErrorDetail(e, 'mbTrim');
			return ('');
		}
	};

	/**
	 * マルチバイト L trim (全半角スペース + 改行 + タブ)
	 *
	 * @param {string}
	 *            target 対象文字列
	 * @return {string} trim 後文字列
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var trimString = $.mbLTrim(string);
	 */
	jQuery.mbLTrim = function(target) {
		try {
			var tmpString = jQuery.castString(target);
			tmpString = jQuery.trim(tmpString);
			tmpString = tmpString.replace(/^[\s\t\n\r　]+/g, '');
			return (tmpString);
		} catch (e) {
			jQuery.showErrorDetail(e, 'mbLTrim');
			return ('');
		}
	};

	/**
	 * マルチバイト R trim (全半角スペース + 改行 + タブ)
	 *
	 * @param {string}
	 *            target 対象文字列
	 * @return {string} trim 後文字列
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var trimString = $.mbRTrim(string);
	 */
	jQuery.mbRTrim = function(target) {
		try {
			var tmpString = jQuery.castString(target);
			tmpString = jQuery.trim(tmpString);
			tmpString = tmpString.replace(/[\s\t\n\r　]+$/g, '');
			return (tmpString);
		} catch (e) {
			jQuery.showErrorDetail(e, 'mbRTrim');
			return ('');
		}
	};

	/**
	 * Left 関数
	 *
	 * @param {string}
	 *            target 対象文字列
	 * @param {integer}
	 *            number 切り取り数
	 * @return {string} 処理文字列
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.showErrorDetail
	 * @example var string = $.left(number + '00000', 5);
	 */
	jQuery.left = function(target, number) {
		try {
			target = jQuery.castString(target);
			number = jQuery.castNumber(number, false);
			if (number > target.length - 1 || number < 1) {
				// 切り取り数が文字列より長い or マイナス -> そのまま返す
				return (target);
			}
			//
			return (target.slice(0, number));
		} catch (e) {
			jQuery.showErrorDetail(e, 'left');
			return ('');
		}
	};

	/**
	 * Right 関数
	 *
	 * @param {string}
	 *            target 対象文字列
	 * @param {integer}
	 *            number 切り取り数
	 * @return {string} 処理文字列
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.showErrorDetail
	 * @example var string = $.right('00000' + number, 5);
	 */
	jQuery.right = function(target, number) {
		try {
			target = jQuery.castString(target);
			number = jQuery.castNumber(number, false);
			if (number > target.length - 1 || number < 1) {
				// 切り取り数が文字列より長い or マイナス -> そのまま返す
				return (target);
			}
			//
			return (target.slice(target.length - number));
		} catch (e) {
			jQuery.showErrorDetail(e, 'right');
			return ('');
		}
	};

	/**
	 * Left 関数 (バイト長)
	 *
	 * @param {string}
	 *            target 対象文字列
	 * @param {integer}
	 *            number 切り取り数
	 * @return {string} 処理文字列
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.showErrorDetail
	 * @example var string = $.mbLeft(number + '00000', 5);
	 */
	jQuery.mbLeft = function(target, number) {
		try {
			var result = ''; // 結果
			var count = 0; // バイト数カウンター
			var i = 0; // ループ用
			var len = 0; // ループ最大用
			var byteCount = 0; // バイト長
			var character = ''; // 1 文字
			//
			target = jQuery.castString(target);
			number = jQuery.castNumber(number, false);
			byteCount = jQuery.byteLength(target) - 1;
			//
			if (number < -1 || number > byteCount) {
				// 切り取り数が文字列より長い or マイナス -> そのまま返す
				return (target);
			}
			//
			len = target.length;
			for (i = 0; i < len; i++) {
				character = target.charAt(i);
				byteCount = jQuery.byteLength(character);
				//
				if (count + byteCount > number) {
					// 最大文字数を越えたら終了
					break;
				} else {
					// 結果と結合
					result += character;
					// カウンターインクリメント
					count += byteCount;
				}
			}
			//
			if (jQuery.byteLength(result) !== number) {
				// バイト長と必要文字数が異なる -> 区切り部分がが全角
				result += ' ';
			}
			//
			return (result);
		} catch (e) {
			jQuery.showErrorDetail(e, 'mbLeft');
			return ('');
		}
	};

	/**
	 * Right 関数 (バイト長)
	 *
	 * @param {string}
	 *            target 対象文字列
	 * @param {integer}
	 *            number 切り取り数
	 * @return {string} 処理文字列
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.showErrorDetail
	 * @example var string = $.mbLeft(number + '00000', 5);
	 */
	jQuery.mbRight = function(target, number) {
		try {
			var result = ''; // 結果
			var count = 0; // バイト数カウンター
			var i = 0; // ループ用
			var len = 0; // ループ最大用
			var byteCount = 0; // バイト長
			var character = ''; // 1 文字
			//
			target = jQuery.castString(target);
			number = jQuery.castNumber(number, false);
			byteCount = jQuery.byteLength(target) - 1;
			//
			if (number < -1 || number > byteCount) {
				// 切り取り数が文字列より長い or マイナス -> そのまま返す
				return (target);
			}
			//
			len = target.length;
			for (i = len - 1; i > -1; i--) {
				character = target.charAt(i);
				byteCount = jQuery.byteLength(character);
				//
				if (count + byteCount > number) {
					// 最大文字数を越えたら終了
					break;
				} else {
					// 結果と結合
					result = character + result;
					// カウンターインクリメント
					count += byteCount;
				}
			}
			//
			if (jQuery.byteLength(result) !== number) {
				// バイト長と必要文字数が異なる -> 区切り部分がが全角
				result = ' ' + result;
			}
			//
			return (result);
		} catch (e) {
			jQuery.showErrorDetail(e, 'mbLeft');
			return ('');
		}
	};

	/**
	 * バイト換算の文字列長取得
	 *
	 * @param {string}
	 *            target 対象文字列
	 * @return {integer} 文字列長のバイト数
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var length = $.byteLength('abc012あいうアイウｱｲｳ');
	 */
	jQuery.byteLength = function(target) {
		try {
			var i = 0;
			var length = 0;
			var count = 0;
			var character = '';
			//
			target = jQuery.castString(target);
			length = target.length;
			//
			for (i = 0; i < length; i++) {
				// 1 文字を切り出し Unicode に変換
				character = target.charCodeAt(i);
				//
				// Unicode の半角 : 0x0 - 0x80, 0xf8f0, 0xff61 - 0xff9f, 0xf8f1 -
				// 0xf8f3
				if ((character >= 0x0 && character < 0x81)
						|| (character == 0xf8f0)
						|| (character > 0xff60 && character < 0xffa0)
						|| (character > 0xf8f0 && character < 0xf8f4)) {
					// 1 バイト文字
					count += 1;
				} else {
					// 2 バイト文字
					count += 2;
				}
			}
			//
			return (count);
		} catch (e) {
			jQuery.showErrorDetail(e, 'byteLength');
			return (0);
		}
	};

	/**
	 * 指定バイト数まで文字列で埋める
	 *
	 * @param {string}
	 *            target 対象文字列
	 * @param {integer}
	 *            maxLength バイト数
	 * @param {string}
	 *            fillString 詰め文字 [デフォルト:半角スペース]
	 * @param {bool}
	 *            fillFlag 詰め方 true:文字列の前に詰め文字挿入 / false:文字列の後ろに詰め文字挿入
	 *            [デフォルト:false]
	 * @return {string} 処理文字列
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.byteLength
	 * @requires jQuery.showErrorDetail
	 * @example var string = $.fillByByte('abcde', 10, '0', false);
	 */
	jQuery.fillByByte = function(target, maxLength, fillString, fillFlag) {
		try {
			var tmpString = ''; // 一時文字列
			var count = 0; // バイト数カウンター
			var fillLength = 0; // 詰め文字のバイト数
			var i = 0; // ループ用
			var length = 0; // ループ最大用
			var byteCount = 0; // バイト長
			var character = ''; // 1 文字
			//
			target = jQuery.castString(target);
			maxLength = jQuery.castNumber(maxLength, false);
			fillString = jQuery.castString(fillString);
			//
			if (fillString === '') {
				// 詰め文字の指定無し -> 半角スペース
				fillString = ' ';
				fillLength = 1;
			} else {
				fillLength = jQuery.byteLength(fillString);
			}
			//
			// 現在の文字が最大文字数を越えていないか確認
			length = target.length;
			for (i = 0; i < length; i++) {
				character = target.charAt(i);
				byteCount = jQuery.byteLength(character);
				//
				if (count + byteCount > maxLength) {
					// 最大文字数を越えたら終了
					break;
				} else {
					// 一時文字列に格納
					tmpString += character;
					// カウンターインクリメント
					count += byteCount;
				}
			}
			//
			// 最大文字数まで詰め文字で埋める
			for (i = count + 1; i < maxLength + 1; i += fillLength) {
				if (count + fillLength > maxLength) {
					break;
				} else {
					if (fillFlag === true) {
						// 前詰め
						tmpString = fillString + tmpString;
					} else {
						// 後ろ詰め
						tmpString += fillString;
					}
					count += fillLength;
				}
			}
			//
			// 残り分を半角スペースで埋める
			for (i = count + 1; i < maxLength + 1; i++) {
				if (fillFlag === true) {
					tmpString = ' ' + tmpString;
				} else {
					tmpString += ' ';
				}
			}
			//
			return (tmpString);
		} catch (e) {
			jQuery.showErrorDetail(e, 'fillByByte');
			return ('');
		}
	};

	/**
	 * 指定文字数まで文字列で埋める
	 *
	 * @param {string}
	 *            target 対象文字列
	 * @param {integer}
	 *            maxLength 文字数
	 * @param {string}
	 *            fillString 詰め文字 [デフォルト:半角スペース]
	 * @param {bool}
	 *            fillFlag 詰め方 true:文字列の前に詰め文字挿入 / false:文字列の後ろに詰め文字挿入
	 *            [デフォルト:false]
	 * @return {string} 処理文字列
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.showErrorDetail
	 * @example var string = $.fillByLength(number, 10, '0', false);
	 */
	jQuery.fillByLength = function(target, maxLength, fillString, fillFlag) {
		try {
			// 内部変数
			var tmpString = ''; // 一時文字列
			var count = 0; // バイト数カウンター
			var fillLength = 0; // 詰め文字のバイト数
			var i = 0; // ループ用
			var length = 0; // ループ最大用
			//
			target = jQuery.castString(target);
			maxLength = jQuery.castNumber(maxLength, false);
			fillString = jQuery.castString(fillString);
			//
			if (fillString === '') {
				// 詰め文字の指定無し -> 半角スペース
				fillString = ' ';
				fillLength = 1;
			} else {
				// 詰め文字の長さ取得
				fillLength = fillString.length;
			}
			//
			// 現在の文字が最大文字数を越えていないか確認
			length = target.length;
			for (i = 0; i < length; i++) {
				if (count + 1 > maxLength) {
					// 最大文字数を越えたら終了
					break;
				} else {
					// 一時文字列に格納
					tmpString += target.charAt(i);
					// カウンターインクリメント
					count += 1;
				}
			}
			//
			// 最大文字数まで詰め文字で埋める
			for (i = count + 1; i < maxLength + 1; i += fillLength) {
				if (count + fillLength > maxLength) {
					break;
				} else {
					if (fillFlag === true) {
						// 前詰め
						tmpString = fillString + tmpString;
					} else {
						// 後ろ詰め
						tmpString += fillString;
					}
					count += fillLength;
				}
			}
			//
			// 残り分を半角スペースで埋める
			for (i = count + 1; i < maxLength + 1; i++) {
				if (fillFlag === true) {
					tmpString = ' ' + tmpString;
				} else {
					tmpString += ' ';
				}
			}
			//
			return (tmpString);
		} catch (e) {
			jQuery.showErrorDetail(e, 'fillByLength');
			return ('');
		}
	};

	/**
	 * 区切り数ごとに区切り文字挿入
	 *
	 * @param {string}
	 *            target 対象文字列
	 * @param {integer}
	 *            number 区切り数
	 * @param {string}
	 *            delimiter 区切り文字
	 * @return {string} 処理文字列
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.showErrorDetail
	 * @example var str = $.wordwrap('123456789', 3, '-');
	 */
	jQuery.wordwrap = function(target, number, delimiter) {
		try {
			var result = ''; // 結果
			var i = 0; // ループ用
			var len = 0; // ループ最大用
			//
			target = jQuery.castString(target);
			delimiter = jQuery.castString(delimiter);
			number = jQuery.castNumber(number, false);
			//
			if (number < 1) {
				return (target);
			}
			//
			len = target.length;
			for (i = 0; i < len; i += number) {
				result += target.slice(i, i + number) + delimiter;
			}
			//
			return (result);
		} catch (e) {
			jQuery.showErrorDetail(e, 'wordwrap');
			return ('');
		}
	};

	/**
	 * 区切り数ごとに区切り文字挿入 (マルチバイト対応)
	 *
	 * @param {string}
	 *            target 入力文字列
	 * @param {integer}
	 *            number 文字列を分割するときの文字数
	 * @param {string}
	 *            delimiter 区切り文字
	 * @return {string} 処理文字列
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.showErrorDetail
	 * @example var str = $.wordwrap('あいうえお', 3, '-');
	 */
	jQuery.mbWordwrap = function(target, number, delimiter) {
		try {
			var result = ''; // 結果
			var count = 0; // バイト数カウンター
			var i = 0; // ループ用
			var len = 0; // ループ最大用
			var byteCount = 0; // バイト長
			var character = ''; // 1 文字
			//
			target = jQuery.castString(target);
			delimiter = jQuery.castString(delimiter);
			number = jQuery.castNumber(number, false);
			if (number < 1) {
				return (target);
			}
			//
			len = target.length;
			for (i = 0; i < len; i++) {
				character = target.charAt(i);
				byteCount = jQuery.byteLength(character);
				//
				if (count + byteCount > number) {
					// 最大文字数を越えたら区切り文字挿入
					result += delimiter + character;
					count = byteCount;
				} else {
					// 結果と結合
					result += character;
					count += byteCount;
				}
			}
			//
			return (result);
		} catch (e) {
			jQuery.showErrorDetail(e, 'mbWordwrap');
			return ('');
		}
	};

	/**
	 * 文字列から拡張子取得
	 *
	 * @param {string}
	 *            file 対象文字列
	 * @return {string} 拡張子 (ピリオドを含まない / 全て小文字で返却)
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var extension = $.getExtension('sample.php');
	 */
	jQuery.getExtension = function(file) {
		try {
			file = jQuery.castString(file);
			//
			var split = file.split('.');
			if (split.length < 2) {
				// 拡張子なし
				return ('');
			} else {
				return (split[split.length - 1].toLowerCase());
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'getExtension');
			return ('');
		}
	};

	/**
	 * ランダム文字列作成 (パスワード生成などに利用)
	 *
	 * @param {integer}
	 *            length 作成する文字列長
	 * @param {string}
	 *            option 利用文字 A:英字大文字追加 / 9:数字追加 /
	 * @:記号追加 (!#$%&.@_) [デフォルト:英字小文字]
	 * @param {string}
	 *            add 上記以外で追加したい文字
	 * @param {string}
	 *            del 上記から除外したい文字
	 * @return {string} ランダム文字列
	 * @public
	 * @requires jQuery.castNumber
	 * @requires jQuery.castString
	 * @requires jQuery.generateRandomNumber
	 * @requires jQuery.showErrorDetail
	 * @example var random = $.generateRandomString(15, 'A9', '', 'aA');
	 */
	jQuery.generateRandomString = function(length, option, add, del) {
		try {
			length = jQuery.castNumber(length, false);
			option = jQuery.castString(option);
			add = jQuery.castString(add);
			del = jQuery.castString(del);
			//
			var character = '';
			character += 'abcdefghijklmnopqrstuvwxyz';
			//
			if (option.indexOf('A', 0) !== -1) {
				character += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			}
			if (option.indexOf('9', 0) !== -1) {
				character += '0123456789';
			}
			if (option.indexOf('@', 0) !== -1) {
				character += '!#$%&.@_';
			}
			character += add;
			//
			var i = 0;
			var len = del.length;
			var tmp = '';
			for (i = 0; i < len; i++) {
				tmp = del.charAt(i);
				character = character.split(tmp).join('');
			}
			if (character === '') {
				// 全て del された場合
				return ('');
			}
			//
			var max = character.length - 1;
			var random = '';
			for (i = 0; i < length; i++) {
				random += character.charAt(jQuery.generateRandomNumber(0, max));
			}
			return (random);
		} catch (e) {
			jQuery.showErrorDetail(e, 'generateRandomString');
			return ('');
		}
	};

	/**
	 * 前方一致
	 *
	 * @param {string}
	 *            haystack 検索を行う文字列
	 * @param {string}
	 *            needle 検索文字列
	 * @return {bool} true:前方一致 / false:前方一致しない
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var str1 = 'abcde'; var str2 = 'ab'; if ($.leftMatch(str1,
	 *          str2)) { alert('前方一致'); }
	 */
	jQuery.leftMatch = function(haystack, needle) {
		try {
			haystack = jQuery.castString(haystack);
			needle = jQuery.castString(needle);
			//
			return (haystack.indexOf(needle, 0) === 0);
		} catch (e) {
			jQuery.showErrorDetail(e, 'leftMatch');
			return (false);
		}
	};

	/**
	 * 後方一致
	 *
	 * @param {string}
	 *            haystack 検索を行う文字列
	 * @param {string}
	 *            needle 検索文字列
	 * @return {bool} true:前方一致 / false:前方一致しない
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var str1 = 'abcde'; var str2 = 'de'; if ($.rightMatch(str1,
	 *          str2)) { alert('後方一致'); }
	 */
	jQuery.rightMatch = function(haystack, needle) {
		try {
			haystack = jQuery.castString(haystack);
			needle = jQuery.castString(needle);
			var reg = new RegExp(needle + '$');
			//
			return (haystack.match(reg));
		} catch (e) {
			jQuery.showErrorDetail(e, 'rightMatch');
			return (false);
		}
	};

	/**
	 * 部分一致
	 *
	 * @param {string}
	 *            haystack 検索を行う文字列
	 * @param {string}
	 *            needle 検索文字列
	 * @return {bool} true:前方一致 / false:前方一致しない
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var str1 = 'abcde'; var str2 = 'cd'; if ($.partialMatch(str1,
	 *          str2)) { alert('部分一致'); }
	 */
	jQuery.partialMatch = function(haystack, needle) {
		try {
			haystack = jQuery.castString(haystack);
			needle = jQuery.castString(needle);
			//
			return (haystack.indexOf(needle, 0) !== -1);
		} catch (e) {
			jQuery.showErrorDetail(e, 'partialMatch');
			return (false);
		}
	};

	// ----------+[ 日付操作関連 ]+----------
	/**
	 * 指定年月の末尾計算
	 *
	 * @param {integer}
	 *            year 年
	 * @param {integer}
	 *            month 月
	 * @return {string} 末日 (yyyy/MM/dd 形式)
	 * @public
	 * @requires jQuery.castNumber
	 * @requires jQuery.showErrorDetail
	 * @example var date = $.getMonthEndDay(2012, 2);
	 */
	jQuery.getMonthEndDay = function(year, month) {
		try {
			year = jQuery.castNumber(year, false);
			month = jQuery.castNumber(month, false);
			if (year < 1) {
				year = 1;
			} else if (year > 9999) {
				year = 9999;
			}
			if (month < 1 || month > 12) {
				month = 12;
			}
			//
			var date = new Date(year, month, 0);
			if (date === 'Invalid Date') {
				alert('日付が扱える範囲を越えました');
				return ('');
			}
			//
			var result = '';
			result += date.getFullYear() + '/';
			result += jQuery.right('00' + (date.getMonth() + 1), 2) + '/';
			result += jQuery.right('00' + date.getDate(), 2);
			return (result);
		} catch (e) {
			jQuery.showErrorDetail(e, 'getMonthEndDay');
			return ('');
		}
	};

	/**
	 * 日付の加算
	 *
	 * @param {string}
	 *            type 計算タイプ y:年 / m:月 / d:日 / w:週
	 * @param {integer}
	 *            diff 差分 (マイナス値も可能)
	 * @param {string}
	 *            date 日付 (yyyy/MM/dd 形式)
	 * @return {string} 計算結果 (yyyy/MM/dd 形式)
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.getMonthEndDay
	 * @requires jQuery.textDateFormat
	 * @requires jQuery.showErrorDetail
	 * @example var date = $.dateAdd('d', -10, '2012/02/29');
	 */
	jQuery.dateAdd = function(type, diff, date) {
		try {
			type = jQuery.castString(type).toLowerCase();
			diff = jQuery.castNumber(diff, false);
			date = jQuery.textDateFormat(date, 'ymd');
			//
			if (date === '') {
				// 日付形式以外
				return ('');
			}
			//
			if (type === 'w') {
				// 週 -> 7 x 週の日計算に変換
				type = 'd';
				diff *= 7;
			}
			//
			// 日付分割
			var split = date.split('/');
			var year = jQuery.castNumber(split[0], false);
			var month = jQuery.castNumber(split[1], false);
			var day = jQuery.castNumber(split[2], false);
			//
			var addDate = null;
			var result = '';
			//
			// タイプ別処理
			switch (type) {
			case 'y':
				// 年加算
				addDate = new Date(year + diff, month - 1, day);
				if (addDate === 'Invalid Date') {
					alert('日付が扱える範囲を越えました');
				} else {
					if (addDate.getMonth() + 1 === month) {
						result += addDate.getFullYear() + '/';
						result += jQuery.right('00' + (addDate.getMonth() + 1),
								2)
								+ '/';
						result += jQuery.right('00' + addDate.getDate(), 2);
					} else {
						// 加算前と月が異なる (2012/02/29 [Year + 1] -> 2013/03/01) ->
						// 先月末日に変更
						result = jQuery.getMonthEndDay(addDate.getFullYear(),
								addDate.getMonth());
					}
				}
				break;
			case 'm':
				// 月加算
				addDate = new Date(year, month - 1 + diff, day);
				if (addDate === 'Invalid Date') {
					alert('日付が扱える範囲を越えました');
				} else {
					if (addDate.getDate() === day) {
						result += addDate.getFullYear() + '/';
						result += jQuery.right('00' + (addDate.getMonth() + 1),
								2)
								+ '/';
						result += jQuery.right('00' + addDate.getDate(), 2);
					} else {
						// 加算前と日が異なる (2012/03/31 [Month + 1] -> 2012/05/01) ->
						// 先月末日に変更
						result = jQuery.getMonthEndDay(addDate.getFullYear(),
								addDate.getMonth());
					}
				}
				break;
			case 'd':
				// 日加算
				addDate = new Date(year, month - 1, day + diff);
				if (addDate === 'Invalid Date') {
					alert('日付が扱える範囲を越えました');
				} else {
					result += addDate.getFullYear() + '/';
					result += jQuery.right('00' + (addDate.getMonth() + 1), 2)
							+ '/';
					result += jQuery.right('00' + addDate.getDate(), 2);
				}
				break;
			default:
				// 指定不明
				result = date;
				break;
			}
			//
			return (result);
		} catch (e) {
			jQuery.showErrorDetail(e, 'dateAdd');
			return ('');
		}
	};

	/**
	 * 時間の加算
	 *
	 * @param {string}
	 *            type 計算タイプ h:時 / i:分 / s:秒
	 * @param {integer}
	 *            diff 差分 (マイナス値も可能)
	 * @param {string}
	 *            time 時刻 (hh:ii:ss 24時間形式)
	 * @param {object}
	 *            date 参照渡で日付の繰り上げを格納する [デフォルト：null]
	 * @param {integer}
	 *            date.day 日付の繰り上げ日数
	 * @return {string} 計算結果 (hh:ii:ss 24時間形式)
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.getType
	 * @requires jQuery.numericalProcess
	 * @requires jQuery.getMonthEndDay
	 * @requires jQuery.textDateFormat
	 * @requires jQuery.showErrorDetail
	 * @example var time = $.timeAdd('h', -10, '01:23:45');
	 */
	jQuery.timeAdd = function(type, diff, time, date) {
		try {
			type = jQuery.castString(type).toLowerCase();
			diff = jQuery.castNumber(diff, false);
			time = jQuery.textTimeFormat(time, '24h', 'his');
			if (jQuery.getType(date) !== 'hash'
					&& jQuery.getType(date) !== 'object') {
				date = new Object();
			}
			date['day'] = 0;
			//
			if (time === '') {
				// 時刻形式以外
				return ('');
			}
			//
			// 時刻分割
			var split = time.split(':');
			var hour = jQuery.castNumber(split[0], false);
			var minute = jQuery.castNumber(split[1], false);
			var second = jQuery.castNumber(split[2], false);
			//
			var digit = 0;
			var result = '';
			//
			// タイプ別処理
			if (type === 's') {
				// 秒加算
				second += diff;
			}
			// 桁上げ
			if (second < 0) {
				digit = parseInt((second - 60) / 60, 10);
				second = 60 - ((-1 * second) % 60);
			} else {
				digit = parseInt(second / 60, 10);
				second = second % 60;
			}
			minute += digit;
			//
			if (type === 'i') {
				// 分加算
				minute += diff;
			}
			// 桁上げ
			if (minute < 0) {
				digit = parseInt((minute - 60) / 60, 10);
				minute = 60 - ((-1 * minute) % 60);
			} else {
				digit = parseInt(minute / 60, 10);
				minute = minute % 60;
			}
			hour += digit;
			//
			if (type === 'h') {
				// 時加算
				hour += diff;
			}
			// 桁上げ
			if (hour < 0) {
				digit = parseInt((hour - 24) / 24, 10);
				hour = 24 - ((-1 * hour) % 24);
			} else {
				digit = parseInt(hour / 24, 10);
				hour = hour % 24;
			}
			date['day'] = digit;
			//
			// 結果
			result += jQuery.right('00' + (hour), 2) + ':';
			result += jQuery.right('00' + (minute), 2) + ':';
			result += jQuery.right('00' + (second), 2);
			//
			return (result);
		} catch (e) {
			jQuery.showErrorDetail(e, 'timeAdd');
			return ('');
		}
	};

	/**
	 * 日時の加算
	 *
	 * @param {string}
	 *            type 計算タイプ y:年 / m:月 / d:日 / w:週 / h:時 / i:分 / s:秒
	 * @param {integer}
	 *            diff 差分 (マイナス値も可能)
	 * @param {string}
	 *            datetime 時刻 (yyyy/mm/dd hh:ii:ss 24時間形式)
	 * @return {string} 計算結果 (yyyy/mm/dd hh:ii:ss 24時間形式)
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.timeAdd
	 * @requires jQuery.dateAdd
	 * @requires jQuery.textDateFormat
	 * @requires jQuery.showErrorDetail
	 * @example var datetimeAdd = $.datetimeAdd('h', -10, '2012/12/31
	 *          01:23:45');
	 */
	jQuery.datetimeAdd = function(type, diff, datetime) {
		try {
			type = jQuery.castString(type).toLowerCase();
			datetime = jQuery.textDatetimeFormat(datetime, {
				date : 'ymd',
				time : 'his',
				hour : '24h'
			});
			//
			if (datetime === '') {
				// 日時形式以外
				return ('');
			}
			//
			// 日時分割
			var split = datetime.split(' ');
			var date = split[0];
			var time = split[1];
			var digit = new Object();
			digit['day'] = 0;
			//
			if (type === 'h' || type === 'i' || type === 's') {
				// 時刻加算
				time = jQuery.timeAdd(type, diff, time, digit);
				//
				if (digit['day'] !== 0) {
					// 日付の繰り上げあり
					date = jQuery.dateAdd('d', digit['day'], date);
				}
			}
			if (time === '' || date === '') {
				return ('');
			}
			//
			if (type === 'y' || type === 'm' || type === 'd' || type === 'w') {
				// 日付加算
				date = jQuery.dateAdd(type, diff, date);
			}
			if (date === '') {
				return ('');
			}
			//
			return (date + ' ' + time);
		} catch (e) {
			jQuery.showErrorDetail(e, 'datetimeAdd');
			return ('');
		}
	};

	/*
	 * +============================================+ | 年 |
	 * +---+----------------------------------------+ | y | 2 桁表示 : 01 - 99 | |
	 * Y | 4 桁表示 : 1900 - 9999 | +===+========================================+ |
	 * 月 | +---+----------------------------------------+ | m | 1 桁表示 : 1 - 12 | |
	 * M | 2 桁表示 : 01 - 12 | | f | 略称 (3文字) 形式 : Jan - Dec | | F | フルスペル形式 :
	 * January - December | +===+========================================+ | 日 |
	 * +---+----------------------------------------+ | d | 1 桁表示 : 1 - 31 | | D |
	 * 2 桁表示 : 01 - 31 | +===+========================================+ | 曜日 |
	 * +---+----------------------------------------+ | w | 略称 (3文字) 形式 : Sun -
	 * Sat | | W | フルスペル形式 : Sunday - Saturday |
	 * +===+========================================+ | 時 |
	 * +---+----------------------------------------+ | g | 1 桁表示 / 12時間単 1 - 11 | |
	 * G | 2 桁表示 / 12時間単 01 - 11 | | h | 1 桁表示 / 24時間単 1 - 23 | | H | 2 桁表示 /
	 * 24時間単 01 - 23 | | a | 小文字表示 : 午前 / 午後 am / pm | | A | 大文字表示 : 午前 / 午後 AM /
	 * PM | +===+========================================+ | 分 |
	 * +---+----------------------------------------+ | i | 1 桁表示 : 1 - 59 | | I |
	 * 2 桁表示 : 01 - 59 | +===+========================================+ | 秒 |
	 * +---+----------------------------------------+ | s | 1 桁表示 : 1 - 59 | | S |
	 * 2 桁表示 : 01 - 59 | +===+========================================+
	 */
	/**
	 * ローカル時刻の取得
	 *
	 * @param {string}
	 *            format フォーマット ※対象表参照
	 * @return {string} フォーマットに対するローカル時刻
	 * @public
	 * @requires jQuery.right
	 * @requires jQuery.showErrorDetail
	 * @example alert($.getLocalTime('現在 : Y-M-D (w) a G:I:S'));
	 */
	jQuery.getLocalTime = function(format) {
		try {
			var week = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday',
					'Thursday', 'Friday', 'Saturday');
			var weekShort = new Array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri',
					'Sat');
			var month = new Array('January', 'February', 'March', 'April',
					'May', 'June', 'July', 'August', 'September', 'October',
					'November', 'December');
			var monthShort = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May',
					'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
			//
			var date = new Date();
			var replace = new Object();
			replace['Y'] = date.getFullYear();
			replace['y'] = jQuery.right(replace['Y'], 2);
			replace['m'] = date.getMonth() + 1;
			replace['M'] = jQuery.right('00' + replace['m'], 2);
			replace['f'] = monthShort[date.getMonth()];
			replace['F'] = month[date.getMonth()];
			replace['d'] = date.getDate();
			replace['D'] = jQuery.right('00' + replace['d'], 2);
			replace['w'] = weekShort[date.getDay()];
			replace['W'] = week[date.getDay()];
			replace['h'] = date.getHours();
			replace['H'] = jQuery.right('00' + replace['h'], 2);
			replace['g'] = replace['h'] % 12;
			replace['G'] = jQuery.right('00' + replace['g'], 2);
			replace['a'] = (replace['h'] < 12) ? 'am' : 'pm';
			replace['A'] = replace['a'].toUpperCase();
			replace['i'] = date.getMinutes();
			replace['I'] = jQuery.right('00' + replace['i'], 2);
			replace['s'] = date.getSeconds();
			replace['S'] = jQuery.right('00' + replace['s'], 2);
			//
			// format を 1 文字ずつ検査
			format = jQuery.castString(format);
			var i = 0;
			var len = format.length;
			var tmp = '';
			var time = '';
			for (i = 0; i < len; i++) {
				tmp = format.charAt(i);
				time += (tmp in replace) ? replace[tmp] : tmp;
			}
			//
			return (time);
		} catch (e) {
			jQuery.showErrorDetail(e, 'getLocalTime');
			return ('');
		}
	};

	// ----------+[ 画面操作関連 ]+----------
	/**
	 * 画像リロード (キャッシュに保存されている画像への対処 / img タグ以外は変更不可)
	 *
	 * @param {string}
	 *            url img の src 属性に設定する値 (省略可)
	 * @param {hash}
	 *            option オプション
	 * @param {string}
	 *            option.imagePath url ファイル非存在時の代替画像 [デフォルト:getPath('img') +
	 *            'image_no_link.png']
	 * @param {integer}
	 *            option.width url ファイル非存在時の代替画像幅 [デフォルト:160]
	 * @param {integer}
	 *            option.height url ファイル非存在時の代替画像t高さ [デフォルト:160]
	 * @return {object} 自身の jQuery オブジェクト
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.getPath
	 * @requires jQuery.showErrorDetail
	 * @see src="imagepath?timestamp" とクエリパラメータを付けキャッシュを無視する操作を実行
	 * @see 画像パス自体は指定したものと完全一致しないで注意
	 * @example $('#sample').changeImage('./img/sample.jpg');
	 */
	jQuery.fn.reloadImage = function(url, option) {
		try {
			var date = new Date();
			var query = '?' + date.getTime();
			//
			var parameter = jQuery.extend({
				imagePath : 'image_no_link.png',
				width : 0,
				height : 0
			}, option);
			//
			url = jQuery.castString(url);
			parameter['imagePath'] = jQuery.castString(parameter['imagePath']);
			parameter['width'] = jQuery.castNumber(parameter['width'], false);
			parameter['height'] = jQuery.castNumber(parameter['height'], false);
			//
			if (parameter['imagePath'] === '') {
				parameter['imagePath'] = jQuery.getPath('img')
						+ 'image_no_link.png';
			}
			//
			// img タグ抽出
			var image = this.find('img').add(this.filter('img'));
			//
			// 画像変更
			if (url !== '') {
				var object = null;
				image
						.each(function(index, dom) {
							try {
								object = jQuery(this);
								object
										.attr('src', url + query)
										.error(
												function(event) {
													try {
														// 画像がない場合のエラーがキャッチできるブラウザ
														// -> 画像変更
														jQuery(this)
																.attr(
																		'src',
																		parameter['imagePath']
																				+ query);
														// 正常終了したこととする
														return (true);
													} catch (e) {
														jQuery
																.showErrorDetail(
																		e,
																		'reloadImage each error');
														return (false);
													}
												});
								// サイズ指定がある場合は変更
								if (parameter['width'] > 0) {
									object.css('width', parameter['width']
											+ 'px');
								}
								if (parameter['height'] > 0) {
									object.css('height', parameter['height']
											+ 'px');
								}
							} catch (e) {
								jQuery.showErrorDetail(e, 'reloadImage each');
								return (false);
							}
						});
			}
			//
			return (this.each(function(index, dom) {
			}));
		} catch (e) {
			jQuery.showErrorDetail(e, 'reloadImage');
			// エラー時は処理なしで jQuery オブジェクト自体を返却
			return (this.each(function(index, dom) {
			}));
		}
	};

	/**
	 * 画像差し替え (img タグ以外は変更不可)
	 *
	 * @param {string}
	 *            url img の src 属性に設定する値 (省略可)
	 * @param {hash}
	 *            option オプション
	 * @param {string}
	 *            option.imagePath url ファイル非存在時の代替画像
	 * @param {integer}
	 *            option.imageWidth url ファイル非存在時の代替画像幅 [デフォルト:160]
	 * @param {integer}
	 *            option.imageHeight url ファイル非存在時の代替画像t高さ [デフォルト:160]
	 * @param {integer}
	 *            option.maxWidth 画像の最大幅 (0:考慮しない) [デフォルト:0]
	 * @param {integer}
	 *            option.maxHeight 画像の最大高さ (0:考慮しない) [デフォルト:0]
	 * @return {object} 自身の jQuery オブジェクト
	 * @see url の画像を option.maxWidth / option.maxHeight の比に合わせる
	 * @see 最初の img タグにのみ有効
	 * @see IE では不具合がおきる場合あり
	 * @public
	 * @requires jQuery.preloader
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.getFileExists
	 * @requires jQuery.getPath
	 * @requires jQuery.showErrorDetail
	 * @example $('#sample').changeImage('./img/sample.pdf');
	 */
	jQuery.fn.changeImage = function(url, option) {
		try {
			jQuery.preloader(true);
			//
			var date = new Date();
			var query = '?' + date.getTime();
			var information = new Object();
			var width = 0;
			var height = 0;
			//
			var parameter = jQuery.extend({
				imagePath : 'image_no_link.png',
				imageWidth : 160,
				imageHeight : 160,
				maxWidth : 0,
				maxHeight : 0
			}, option);
			//
			url = jQuery.castString(url);
			parameter['imagePath'] = jQuery.castString(parameter['imagePath']);
			parameter['imageWidth'] = jQuery.castNumber(
					parameter['imageWidth'], false);
			parameter['imageHeight'] = jQuery.castNumber(
					parameter['imageHeight'], false);
			parameter['maxWidth'] = jQuery.castNumber(parameter['maxWidth'],
					false);
			parameter['maxHeight'] = jQuery.castNumber(parameter['maxHeight'],
					false);
			//
			if (parameter['imagePath'] === '') {
				parameter['imagePath'] = 'image_no_link.png';
			}
			if (parameter['imageWidth'] < 1) {
				parameter['imageWidth'] = 160;
			}
			if (parameter['imageHeight'] < 1) {
				parameter['imageHeight'] = 160;
			}
			if (parameter['maxWidth'] < 0) {
				parameter['maxWidth'] = 0;
			}
			if (parameter['maxHeight'] < 0) {
				parameter['maxHeight'] = 0;
			}
			//
			// img タグ抽出
			var image = this.find('img').add(this.filter('img'));
			//
			// 画像確認
			if (url !== '') {
				if (!jQuery.getFileExists(url, information)) {
					// 画像非存在
					url = jQuery.getPath('img') + parameter['imagePath'];
					width = parameter['imageWidth'];
					height = parameter['imageHeight'];
				} else {
					if ('image' in information && information['image'] !== '') {
						if ('width' in information) {
							width = jQuery.castNumber(information['width'],
									false);
						}
						if ('height' in information) {
							height = jQuery.castNumber(information['height'],
									false);
						}
					} else {
						// 画像以外のファイル
						url = jQuery.getPath('img') + parameter['imagePath'];
						width = parameter['imageWidth'];
						height = parameter['imageHeight'];
					}
				}
				//
				var object = null;
				image.each(function(index, dom) {
					try {
						object = jQuery(this);
						object.css({
							'display' : 'none'
						}).attr('src', url + query);
						//
						// 画像差し替え
						parameter['width'] = width;
						parameter['height'] = height;
						_changeImageCallback(object, parameter);
					} catch (e) {
						jQuery.showErrorDetail(e, 'changeImage each');
						return (false);
					}
				});
			}
			//
			return (this.each(function(index, dom) {
			}));
		} catch (e) {
			jQuery.preloader(false);
			jQuery.showErrorDetail(e, 'changeImage');
			// エラー時は処理なしで jQuery オブジェクト自体を返却
			return (this.each(function(index, dom) {
			}));
		}
	};

	/**
	 * 画像差し替え (差し替え完了後実行関数)
	 *
	 * @param {object}
	 *            object img の src 属性に設定する値 (省略可)
	 * @param {hash}
	 *            option オプション
	 * @param {integer}
	 *            option.maxWidth 画像の最大幅 (0:考慮しない) [デフォルト:0]
	 * @param {integer}
	 *            option.maxHeight 画像の最大高さ (0:考慮しない) [デフォルト:0]
	 * @param {integer}
	 *            option.width 画像の幅
	 * @param {integer}
	 *            option.height 画像の高さ
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example _changeImageCallback(jQuery(this));
	 */
	function _changeImageCallback(object, parameter) {
		try {
			var width = parameter['width'];
			var height = parameter['height'];
			//
			if (width < 1) {
				width = object.width();
			}
			if (height < 1) {
				height = object.height();
			}
			//
			var callback = function() {
				try {
					var rateWidth = 1.0;
					var rateHeight = 1.0;
					var css = {
						'width' : '',
						'height' : '',
						'display' : ''
					};
					//
					if (parameter['maxWidth'] > 0 && parameter['maxHeight'] > 0) {
						// 縦横比
						if (width > parameter['maxWidth']
								|| height > parameter['maxHeight']) {
							if (width > parameter['maxWidth']) {
								rateWidth = parameter['maxWidth'] / width;
							}
							if (height > parameter['maxHeight']) {
								rateHeight = parameter['maxHeight'] / height;
							}
							if (rateWidth > rateHeight) {
								// 縦合わせ
								css['width'] = parseInt(width * rateHeight, 10)
										+ 'px';
								css['height'] = parseInt(height * rateHeight,
										10)
										+ 'px';
							} else {
								// 横合わせ
								css['width'] = parseInt(width * rateWidth, 10)
										+ 'px';
								css['height'] = parseInt(height * rateWidth, 10)
										+ 'px';
							}
						}
					} else if (parameter['maxWidth'] > 0) {
						// 横比
						if (width > parameter['maxWidth']) {
							rateWidth = parameter['maxWidth'] / width;
							css['width'] = parameter['maxWidth'] + 'px';
							css['height'] = parseInt(height * rateWidth, 10)
									+ 'px';
						}
					} else if (parameter['maxHeight'] > 0) {
						// 縦比
						if (height > parameter['maxHeight']) {
							rateHeight = parameter['maxHeight'] / height;
							css['width'] = parseInt(width * rateHeight, 10)
									+ 'px';
							css['height'] = parameter['maxHeight'] + 'px';
						}
					} else {
						css['width'] = object.css('width');
						css['height'] = object.css('height');
					}
					//
					object.css(css);
					jQuery.preloader(false);
				} catch (e) {
					jQuery.preloader(false);
					jQuery.showErrorDetail(e, 'changeImage callback');
				}
			};
			//
			// 画像ロード後のコールバック (IE で不具合がある)
			var tmpImg = new Image();
			tmpImg.onload = callback;
			tmpImg.src = object.attr('src');
		} catch (e) {
			jQuery.preloader(false);
			jQuery.showErrorDetail(e, 'changeImageCallback');
		}
	}

	/**
	 * 入力のクリア (textbox クラスの入力クリア / combobox クラスの選択クリア / panel クラス以下の radio と
	 * checkbox の選択クリア)
	 *
	 * @public
	 * @requires jQuery.showErrorDetail
	 * @requires removeErrorStyle
	 * @see class に protected を指定した場合はクリアの対象から除外する
	 * @example $.inputClear();
	 */
	jQuery.inputClear = function() {
		try {
			var textClass = 'textbox';
			var comboboxClass = 'combobox';
			var panelClass = 'panel';
			var displayClass = 'display';
			//
			var tmp = null;
			var object = null;
			var target = null;
			var tag = '';
			//
			// テキストクリア
			object = jQuery('.' + textClass);
			target = object.filter('input[type=text]').add(
					object.filter('input[type=password]')).add(
					object.filter('textarea'));
			target.removeClass(textClass + '-focus').removeClass(
					textClass + '-error')
					.removeErrorStyle(textClass + '-error');
			target.not('.protected').val('');
			target.filter('[disabled!=disabled]')
					.filter('[readonly!=readonly]').removeClass(
							textClass + '-disabled');
			//
			// コンボクリア
			object = jQuery('.' + comboboxClass);
			target = object.filter('select');
			target.removeClass(comboboxClass + '-focus').removeClass(
					comboboxClass + '-error').removeErrorStyle(
					comboboxClass + '-error');
			jQuery('option', target.not('.protected')).removeAttr('selected');
			jQuery('option:eq(0)', target.not('.protected')).attr('selected',
					'selected');
			target.filter('[disabled!=disabled]').removeClass(
					comboboxClass + '-disabled');
			//
			// チェッククリア
			target = jQuery('.' + panelClass);
			jQuery('input[type=radio], input[type=checkbox]',
					target.not('.protected')).removeAttr('checked');
			target.removeClass(panelClass + '-focus').removeClass(
					panelClass + '-error').removeErrorStyle(
					panelClass + '-error');
			target
					.each(function(index, dom) {
						try {
							object = jQuery(this);
							if (jQuery(
									'input[type=radio], input[type=checkbox]',
									object).size() !== jQuery(
									'input[type=radio]:disabled, input[type=checkbox]:disabled',
									object).size()) {
								// panel 内の要素が1つでも disabled でなければ disabled クラス削除
								object.removeClass(panelClass + '-disabled');
							}
						} catch (e) {
							jQuery.showErrorDetail(e, 'inputClear panel each');
							return (false);
						}
					});
			//
			// 表示ラベルクリア
			target = jQuery('.' + displayClass);
			target.not('.protected').text('');
		} catch (e) {
			jQuery.showErrorDetail(e, 'inputClear');
		}
	};

	/**
	 * @param {object}
	 *            _keyPreview タブ移動時に利用
	 * @private
	 */
	var _keyPreview = null;

	/**
	 * Tab / Enter (+ Shift) でのフォーカス移動 (末端要素どうしのフォーカス移動も可能)
	 *
	 * @param {bool}
	 *            arrow true:left / right キーで移動する / false:通常 [デフォルト:false]
	 * @public
	 * @requires _keyPreview
	 * @requires :_visible
	 * @requires jQuery.showErrorDetail
	 * @see document から keydown を unbind するとこのイベントは消える
	 * @see tabindex 順には移動しなくなる / チェックがついた場合の input type=radio の挙動が変わる
	 * @see class に passed を指定した場合はフォーカスの対象から除外する
	 * @example $.moveFocus();
	 */
	jQuery.moveFocus = function(arrow, tab) {
		try {
			jQuery(document)
					.keydown(
							function(event) {
								// キーが押下された要素取得
								var target = jQuery(event.target);
								//
								var tag = target.get(0).tagName;
								var type = target.attr('type');
								//
								if (event.keyCode === 9
										|| (event.keyCode === 13 && (tag !== 'TEXTAREA'
												&& tag !== 'BUTTON'
												&& tag !== 'A'
												&& type !== 'button'
												&& type !== 'file'
												&& type !== 'reset'
												&& type !== 'submit' && type !== 'image'))
										|| (arrow === true
												&& (event.keyCode === 38 || event.keyCode === 40)
												&& (tag !== 'TEXTAREA' && tag !== 'SELECT') && (!event.shiftKey
												&& !event.altKey && !event.ctrlKey))) {
									// Tab or Enter 押下でかつフォーカスの移動対象要素
									_keyPreview = jQuery(
											'input:_visible, select:_visible, textarea:_visible, button:_visible, a:_visible[href]')
											.not('[tabindex=-1]') // tabindex="-1"
											// を除外
											.not(':disabled') // disabled="disabled"
											// を除外
											.not('[readonly=readonly]') // readonly="readonly"
											// を除外
											.not('.passed'); // 特定クラスを除外
									// 現在フォーカスを持った要素のインデックス取得
									var index = _keyPreview.index(target);
									var shift = event.shiftKey;
									if (event.keyCode === 38) {
										shift = true;
									} else if (event.keyCode === 40) {
										shift = false;
									}
									if (index > -1) {
										// インデックスが取れる場合 -> 次のフォーカス対象を検索
										if (!shift) {
											// Shift なし -> 通常
											if (index + 2 > _keyPreview.size()) {
												// 最後の要素の場合は最初のインデックスを設定
												index = 0;
											} else {
												// 次のインデックスを設定
												index++;
											}
										} else {
											// Shift あり -> 戻る
											if (index === 0) {
												// 最初の要素の場合は最後ののインデックスを設定
												index = _keyPreview.size() - 1;
											} else {
												// 前のインデックスを設定
												index--;
											}
										}
										// 該当インデックスの要素にフォーカス
										_keyPreview
												.filter(':eq(' + index + ')')
												.focus();
										// キーイベント終了
										return (false);
									}
								}
							});
		} catch (e) {
			jQuery.showErrorDetail(e, 'moveFocus');
		}
	};

	/**
	 * 特殊ショートカットキーの割り当て
	 *
	 * @public
	 * @requires _ctrlAltOptionKeydown
	 * @requires _ctrlAltOptionKeyup
	 * @requires jQuery.showErrorDetail
	 * @see Ctrl + Alt + 数字キーで "クリック" イベント発生
	 * @see 数字キーと組み合わせる為 0 から 9 までの 10 個までしか登録できない
	 * @example $.appendShortcutKey();
	 */
	jQuery.appendShortcutKey = function() {
		try {
			jQuery(document).bind('keydown', _ctrlAltOptionKeydown);
			jQuery(document).keyup('keyup', _ctrlAltOptionKeyup);
		} catch (e) {
			jQuery.showErrorDetail(e, 'appendShortcutKey');
		}
	};

	/**
	 * @param {string}
	 *            _ctrlAltOptionClassname 特殊ショートカットキー用のクラス名
	 * @private
	 */
	var _ctrlAltOptionClassname = 'ctrl-alt-option';

	/**
	 * 特殊ショートカットキー keydown 時
	 *
	 * @param {object}
	 *            event イベント
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example jQuery('#id').bind('keydown', _ctrlAltOptionKeydown);
	 */
	function _ctrlAltOptionKeydown(event) {
		try {
			if (event.ctrlKey && event.altKey) {
				var object = null;
				if (event.keyCode === 17 || event.keyCode === 18) {
					// Ctrl + Alt のみ
					var offset = null;
					var html = '<div class="'
							+ _ctrlAltOptionClassname
							+ '-display-number" style="width:17px; border: 1px #808080 solid; text-align: center; position: absolute; background-color: #ffffff;"></div>';
					var css = new Object();
					css['top'] = 0;
					css['left'] = 0;
					jQuery('.' + _ctrlAltOptionClassname).each(
							function(index, dom) {
								try {
									if (jQuery(this).is(':_visible')) {
										if (index > 9) {
											return (false);
										}
										offset = jQuery(this).offset();
										css['top'] = offset['top'];
										css['left'] = offset['left'];
										//
										object = jQuery(html);
										object.css(css);
										object.text((index + 1) % 10);
										jQuery('body').append(object);
									}
								} catch (e) {
									jQuery.showErrorDetail(e,
											'ctrlAltOptionKeydown each');
									return (false);
								}
							});
				} else if ((event.keyCode > 47 && event.keyCode < 58)
						|| (event.keyCode > 95 && event.keyCode < 106)) {
					// 数字キー (テンキーも考慮)
					var index = null;
					switch (event.keyCode) {
					case 48:
					case 96:
						// 0 キー
						index = 9;
						break;
					case 49:
					case 97:
						// 1 キー
						index = 0;
						break;
					case 50:
					case 98:
						// 2 キー
						index = 1;
						break;
					case 51:
					case 99:
						// 3 キー
						index = 2;
						break;
					case 52:
					case 100:
						// 4 キー
						index = 3;
						break;
					case 53:
					case 101:
						// 5 キー
						index = 4;
						break;
					case 54:
					case 102:
						// 6 キー
						index = 5;
						break;
					case 55:
					case 103:
						// 7 キー
						index = 6;
						break;
					case 56:
					case 104:
						// 8 キー
						index = 7;
						break;
					case 57:
					case 105:
						// 9 キー
						index = 8;
						break;
					default:
						break;
					}
					if (index !== null) {
						object = jQuery('.' + _ctrlAltOptionClassname + ':eq('
								+ index + ')');
						if (object.is(':_visible')) {
							object.click();
						}
					}
					//
					jQuery('.' + _ctrlAltOptionClassname + '-display-number')
							.remove();
					// キー入力無効
					return (false);
				}
			} else {
				jQuery('.' + _ctrlAltOptionClassname + '-display-number')
						.remove();
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'ctrlAltOptionKeydown');
		}
	}

	/**
	 * 特殊ショートカットキー keyup 時
	 *
	 * @param {object}
	 *            event イベント
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example jQuery('#id').bind('keydown', _ctrlAltOptionKeydown);
	 */
	function _ctrlAltOptionKeyup(event) {
		try {
			if (!event.ctrlKey || !event.altKey) {
				jQuery('.' + _ctrlAltOptionClassname + '-display-number')
						.remove();
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'ctrlAltOptionKeyup');
		}
	}

	/**
	 * テーブル行へのスタイル割当 (td 基準)
	 *
	 * @param {object}
	 *            object カラーリングするテーブルの jQuery Object
	 * @param {integer}
	 *            rowBlock 行の塊数 [デフォルト:1]
	 * @param {object}
	 *            option オプション
	 * @param {string}
	 *            option.odd 奇数行 tr 内の td スタイル名 [デフォルト:sheet-row-odd]
	 * @param {string}
	 *            option.even 偶数行 tr 内の td スタイル名 [デフォルト:sheet-row-even]
	 * @param {string}
	 *            option.hover オンマウス行tr内のtdスタイル名 [デフォルト:sheet-row-focus]
	 * @public
	 * @requires jQuery.getJQuery
	 * @requires jQuery.castNumber
	 * @requires jQuery.castString
	 * @requires jQuery.mbTrim
	 * @requires jQuery.showErrorDetail
	 * @example $.tableRowsColoring($('#table'), 2, {odd : 'row-odd', even :
	 *          'row-even', hover : 'row-fhover'});
	 */
	jQuery.tableRowsColoring = function(object, rowBlock, option) {
		try {
			object = jQuery.getJQuery(object, false);
			if (object === null) {
				return;
			}
			rowBlock = jQuery.castNumber(rowBlock, false);
			if (rowBlock < 1) {
				rowBlock = 1;
			}
			//
			var parameter = new Object();
			parameter['odd'] = 'sheet-row-odd';
			parameter['even'] = 'sheet-row-even';
			parameter['hover'] = 'sheet-row-focus';
			// ユーザオプション取得
			var key = null;
			for (key in option) {
				if (key in parameter) {
					parameter[key] = jQuery.mbTrim(option[key]);
				}
			}
			//
			// スタイル / イベントの初期化
			var tbody = '';
			if (jQuery('> tbody', object).size() > 0) {
				tbody = '> tbody ';
			}
			jQuery(tbody + '> tr > td', object).removeClass(parameter['odd'])
					.removeClass(parameter['even']).removeClass(
							parameter['hover']);
			jQuery(tbody + '> tr', object).unbind('mouseover').unbind(
					'mouseout');
			//
			// スタイル割当
			var times = rowBlock * 2; // 複数行を1ブロックとするため
			var i = 0;
			for (i = 1; i < times + 1; i++) {
				if (i < rowBlock + 1) {
					// oddスタイル
					jQuery(
							'td',
							jQuery(tbody + '> tr:nth-child(' + times + 'n+' + i
									+ ')', object)).addClass(parameter['odd']);
				} else {
					// evenスタイル
					jQuery(
							'td',
							jQuery(tbody + '> tr:nth-child(' + times + 'n+' + i
									+ ')', object)).addClass(parameter['even']);
				}
			}
			//
			var appendStyle = function(object, selector, parameter) {
				try {
					jQuery(selector, object)
							.mouseover(
									function(event) {
										try {
											// mouse orver
											jQuery('> td',
													jQuery(selector, object))
													.addClass(
															parameter['hover']);
										} catch (e) {
											jQuery
													.showErrorDetail(e,
															'tableRowsColoring appendStyle mouseover');
											return;
										}
									})
							.mouseout(
									function(event) {
										try {
											// mouse out
											jQuery('> td',
													jQuery(selector, object))
													.removeClass(
															parameter['hover']);
										} catch (e) {
											jQuery
													.showErrorDetail(e,
															'tableRowsColoring appendStyle mouseout');
											return;
										}
									});
				} catch (e) {
					jQuery.showErrorDetail(e, 'tableRowsColoring appendStyle');
					return;
				}
			};
			//
			// mouse hover時にスタイルを割当
			var selector = '';
			var j = 0;
			var len = jQuery(tbody + '> tr', object).length;
			for (i = 1; i < len + rowBlock - 1; i += rowBlock) {
				selector = '';
				for (j = i; j < i + rowBlock; j++) {
					selector += ((selector !== '') ? ',' : '') + tbody
							+ '> tr:nth-child(' + j + ')';
				}
				// スタイルの割当
				appendStyle(object, selector, parameter);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'tableRowsColoring');
			return;
		}
	};

	/**
	 * ウィンドウ枠固定
	 *
	 * @param {object}
	 *            object 対象 jQuery オブジェクト
	 * @param {hash}
	 *            option オプション
	 * @param {integer}
	 *            option.height Body 高さ [デフォルト:350]
	 * @param {function}
	 *            option.function ダブルクリック時実行関数 [デフォルト:null] (第一引数:クリックされた行の
	 *            jQuery オブジェクト, 第二引数:option.argument)
	 * @param {mixed}
	 *            option.argument ダブルクリック時実行関数への引数 [デフォルト:null]
	 * @public
	 * @requires jQuery.getJQuery
	 * @requires jQuery.getType
	 * @requires jQuery.castNumber
	 * @requires jQuery.showErrorDetail
	 * @example $.freezePanes( $('#sample'), { function : function(trObject,
	 *          arg) { alert($('td:eq(0)', trObject).text()); }, argument : null } );
	 */
	jQuery.freezePanes = function(object, option) {
		try {
			object = jQuery.getJQuery(object, false);
			if (object === null) {
				return;
			}
			//
			// 速度を速めるために一度非表示
			object.css('visibility', 'hidden');
			//
			var parameter = new Object();
			parameter['height'] = 350; // 高さ指定
			parameter['function'] = null;
			parameter['argument'] = null;
			//
			var key = null;
			if (jQuery.getType(option) === 'hash') {
				for (key in option) {
					if (key in parameter) {
						parameter[key] = option[key];
					}
				}
			}
			//
			// 引き数調整
			var height = parseInt(
					jQuery.castNumber(parameter['height'], false), 10);
			//
			var widthObject = object.outerWidth(true);
			var widthBody = 0;
			var heightBody = 0;
			if (height < 1) {
				heightBody = 350;
			} else {
				heightBody = height;
			}
			//
			// HTML 解析
			var analyzeTable = jQuery('table:eq(0)', jQuery('table:eq(0)',
					object));
			var analyzeTr = jQuery('> tbody > tr:eq(0)', analyzeTable);
			//
			var scrollbar = jQuery.getScrollbarWidth();
			var heightHead = parseInt(jQuery.castNumber(jQuery('> thead',
					analyzeTable).outerHeight(true), false), 10);
			var widthHead = 0;
			var widthTmp = 0;
			jQuery('.multirow-header', analyzeTr).each(
					function(index, dom) {
						// 列ヘッダを指定するクラス名(multirow-header)を持った要素の最右端を検索
						try {
							widthTmp = parseInt(jQuery(this).position().left,
									10)
									+ parseInt(jQuery(this).outerWidth(true),
											10);
							if (widthHead < widthTmp) {
								widthHead = widthTmp;
							}
						} catch (e) {
							jQuery.showErrorDetail(e,
									'freezePanes multirow-header each');
							return (false);
						}
					});
			//
			widthBody = widthObject - widthHead - scrollbar;
			//
			var scrollbarHeight = scrollbar;
			var borderVertical = 1;
			var scrollbarWidth = scrollbar;
			var borderHorizontal = 1;
			var realWidth = analyzeTable.outerWidth(true);
			var realHeight = analyzeTable.outerHeight(true) - heightHead;
			var heightBodyAdjust = scrollbarWidth;
			if (realWidth < widthObject - scrollbar) {
				// <table> 要素の幅が画面幅より小さい
				borderVertical = 0;
				widthBody = realWidth - widthHead;
				scrollbarHeight = 1;
			}
			if (realHeight < heightBody) {
				// <table> 要素の高さが指定高より小さい -> 実寸を指定高とする
				heightBody = realHeight;
				borderHorizontal = 0;
			}
			if (realHeight > heightBody) {
				// <table> 要素の高さが指定高より小さい -> 縦スクロールバーが出るパターン
				heightBodyAdjust = 0;
			}
			//
			// CSS 調整
			var headHead = jQuery('.multirow-hh', object).css({
				'width' : widthHead + 'px',
				'height' : heightHead + 'px'
			});
			jQuery('> div', headHead).css({
				'top' : 0 + 'px',
				'left' : 0 + 'px'
			});
			//
			var headBody = jQuery('.multirow-hb', object).css({
				'width' : (widthBody + heightBodyAdjust) + 'px',
				'height' : heightHead + 'px'
			});
			var bodyHead = jQuery('.multirow-bh', object).css({
				'width' : widthHead + 'px',
				'height' : (heightBody + 1) + 'px'
			});
			var bodyBody = jQuery('.multirow-bb', object).css({
				'width' : (widthBody + scrollbarWidth) + 'px',
				'height' : (heightBody + scrollbarHeight) + 'px'
			});
			var headBodyDiv = jQuery('> div', headBody).css({
				'top' : 0 + 'px',
				'left' : (-1 * widthHead) + 'px'
			});
			var bodyHeadDiv = jQuery('> div', bodyHead).css({
				'top' : (-1 * heightHead) + 'px',
				'left' : 0 + 'px'
			});
			var bodyBodyDiv = jQuery('> div', bodyBody).css({
				'top' : (-1 * heightHead) + 'px',
				'left' : (-1 * widthHead) + 'px'
			});
			if (borderVertical === 1) {
				if (borderHorizontal === 1) {
					headBody.parent('td')
							.css('border-width', '0px 0px 0px 1px');
					bodyHead.parent('td')
							.css('border-width', '1px 0px 0px 0px');
					bodyBody.parent('td')
							.css('border-width', '1px 0px 0px 1px');
				} else {
					headBody.parent('td')
							.css('border-width', '0px 0px 0px 1px');
					bodyHead.parent('td')
							.css('border-width', '0px 0px 0px 0px');
					bodyBody.parent('td')
							.css('border-width', '0px 0px 0px 1px');
				}
			} else {
				if (borderHorizontal === 1) {
					headBody.parent('td')
							.css('border-width', '0px 0px 0px 0px');
					bodyHead.parent('td')
							.css('border-width', '1px 0px 0px 0px');
					bodyBody.parent('td')
							.css('border-width', '1px 0px 0px 0px');
				} else {
					headBody.parent('td')
							.css('border-width', '0px 0px 0px 0px');
					bodyHead.parent('td')
							.css('border-width', '0px 0px 0px 0px');
					bodyBody.parent('td')
							.css('border-width', '0px 0px 0px 0px');
				}
			}
			//
			// スクロールイベント付加
			bodyBody.scrollTop(0);
			bodyBody
					.scroll(function() {
						try {
							var position = bodyBodyDiv.position();
							headBodyDiv.css('left', position.left);
							bodyHeadDiv.css('top', position.top);
						} catch (e) {
							jQuery.showErrorDetail(e,
									'setMultiRow multirow-bb scroll');
						}
					});
			//
			// カラーリングイベント付加
			var bodyHeadTable = jQuery('> table', bodyHeadDiv);
			var bodyHeadTr = jQuery('> tbody > tr', bodyHeadTable);
			var bodyBodyTable = jQuery('> table', bodyBodyDiv);
			var bodyBodyTr = jQuery('> tbody > tr', bodyBodyTable);
			var index = -1;
			//
			bodyHeadTr.hover(
					function(event) {
						try {
							index = bodyHeadTr.index(jQuery(this));
							jQuery('> td', jQuery(this)).addClass(
									'sheet-row-focus');
							jQuery('> tbody > tr:eq(' + index + ') > td',
									bodyBodyTable).addClass('sheet-row-focus');
						} catch (e) {
							jQuery.showErrorDetail(e,
									'multiRow bodyHeadTr mouseover');
						}
					}, function(event) {
						try {
							var index = bodyHeadTr.index(jQuery(this));
							jQuery('> td', jQuery(this)).removeClass(
									'sheet-row-focus');
							jQuery('> tbody > tr:eq(' + index + ') > td',
									bodyBodyTable).removeClass(
									'sheet-row-focus');
						} catch (e) {
							jQuery.showErrorDetail(e,
									'multiRow bodyHeadTr mouseout');
						}
					});
			//
			bodyBodyTr.hover(
					function(event) {
						try {
							index = bodyBodyTr.index(jQuery(this));
							jQuery('> td', jQuery(this)).addClass(
									'sheet-row-focus');
							jQuery('> tbody > tr:eq(' + index + ') > td',
									bodyHeadTable).addClass('sheet-row-focus');
						} catch (e) {
							jQuery.showErrorDetail(e,
									'multiRow bodyBodyTr mouseover');
						}
					}, function(event) {
						try {
							index = bodyBodyTr.index(jQuery(this));
							jQuery('> td', jQuery(this)).removeClass(
									'sheet-row-focus');
							jQuery('> tbody > tr:eq(' + index + ') > td',
									bodyHeadTable).removeClass(
									'sheet-row-focus');
						} catch (e) {
							jQuery.showErrorDetail(e,
									'multiRow bodyBodyTr mouseout');
						}
					});
			//
			// ダブルクリックイベント付加
			var jQuerytd = jQuery('> tbody > tr > td', jQuery('table:eq(0)',
					jQuery('.multirow-bh, .multirow-bb', object)));
			jQuerytd.dblclick(function(event) {
				try {
					if (typeof (parameter['function']) === 'function') {
						parameter['function'](jQuery(this),
								parameter['argument']);
					}
				} catch (e) {
					jQuery.showErrorDetail(e, 'multiRow bhTr dblclick');
				}
			});
			//
			// 表示
			object.css('visibility', '');
		} catch (e) {
			jQuery.showErrorDetail(e, 'freezePanes');
		}
		/*
		 * ----------+[ freezePanes 利用方法 ]+---------- 以下に示す HTML
		 * コード以外は解釈しないので注意して下さい。 // object として渡す要素は、以下の構造を持っていることが前提です。 ※下記の例の場合
		 * id = "sample" の <div> 要素が object となります。 <div id="sample"> <table
		 * style="border-collapse: collapse;"> <tbody> <tr>
		 * <td style="border-width: 0px 0px 0px 0px; border-color: #000000; border-style: solid; vertical-align: top;">
		 * <div class="multirow-hh" style="position: relative; overflow:
		 * hidden;" tabindex="-1"> <div style="position: absolute;"
		 * tabindex="-1"> [<table> 要素] </div> </div> </td>
		 * <td style="border-width: 0px 0px 0px 0px; border-color: #000000; border-style: solid; vertical-align: top;">
		 * <div class="multirow-hb" style="position: relative; overflow:
		 * hidden;" tabindex="-1"> <div style="position: absolute;"
		 * tabindex="-1"> [<table> 要素] </div> </div> </td> </tr> <tr>
		 * <td style="border-width: 0px 0px 0px 0px; border-color: #000000; border-style: solid; vertical-align: top;">
		 * <div class="multirow-bh" style="position: relative; overflow:
		 * hidden;" tabindex="-1"> <div style="position: relative; overflow:
		 * hidden;" tabindex="-1"> [<table> 要素] </div> </div> </td>
		 * <td style="border-width: 0px 0px 0px 1px; border-color: #000000; border-style: solid; vertical-align: top;">
		 * <div class="multirow-bb" style="position: relative; overflow-x: auto;
		 * overflow-y: auto;" tabindex="-1"> <div style="position: absolute;"
		 * tabindex="-1"> [<table> 要素] </div> </div> </td> </tr> </tbody>
		 * </table> <div> // [<table> 要素] には全て同じ HTML
		 * コードが入り、以下の構造を持っていることが前提です。 <table> <!-- 列のヘダー部分 --> <thead> <tr>
		 * <td class="multirow-header">行のヘダー部分</td> <td>通常部分</td> </tr>
		 * </thead> <!-- 通常部分 --> <tbody> <tr> <td class="multirow-header">行のヘダー部分</td>
		 * <td>通常部分</td> </tr> </tbody> </table> // 列のヘダーを作成したい場合は、<thead>
		 * を用いて下さい。 行のヘダーを作成したい場合は、<td> に class = "multirow-header" を用いて下さい。
		 * ※最右端にある multirow-header クラスで行ヘダーの幅が決まるので、行毎に multirow-header
		 * の数が異なると正常に動作しません。
		 */
	};

	/**
	 * スクロール位置変更
	 *
	 * @param {object}
	 *            outerElement スクロールバーが表示されている jQuery オブジェクト
	 * @param {object}
	 *            baseElement スクロールバーの基準位置となる jQuery オブジェクト
	 * @param {object}
	 *            scrollElement スクロールバーで移動させたい jQuery オブジェクト
	 * @public
	 * @requires jQuery.getJQuery
	 * @requires jQuery.showErrorDetail
	 * @example $.setScrollBarPosition($('#outer'), $('#base'), $('#scroll'));
	 */
	jQuery.setScrollBarPosition = function(outerElement, baseElement,
			scrollElement) {
		try {
			outerElement = jQuery.getJQuery(outerElement, true);
			baseElement = jQuery.getJQuery(baseElement, true);
			scrollElement = jQuery.getJQuery(scrollElement, true);
			//
			if (outerElement === null || baseElement === null
					|| scrollElement === null) {
				// jQuery オブジェクトが確認できない場合
				return;
			}
			//
			var outerElementOffset = outerElement.offset();
			var baseElementOffset = baseElement.offset();
			var scrollElementOffset = scrollElement.offset();
			if (outerElementOffset == null || baseElementOffset == null
					|| scrollElementOffset == null) {
				// jQuery から ofset を取れない
				return;
			}
			//
			var diffBase = parseInt(baseElementOffset['top'], 10)
					- parseInt(outerElementOffset['top'], 10)
					+ outerElement.scrollTop();
			var diffScroll = parseInt(scrollElementOffset['top'], 10)
					- parseInt(baseElementOffset['top'], 10);
			//
			var position = diffScroll + diffBase;
			outerElement.scrollTop(position);
		} catch (e) {
			jQuery.showErrorDetail(e, 'setScrollBarPosition');
		}
	};

	// ----------+[ キャスト関連 ]+----------
	/**
	 * 文字列へのキャスト
	 *
	 * @param {object}
	 *            target 対象
	 * @return {string} 文字列 (null / undefined の場合は空文字を返す)
	 * @public
	 * @requires jQuery.showErrorDetail
	 * @example var string = $.castString(text);
	 */
	jQuery.castString = function(target) {
		try {
			if (target == null) {
				return ('');
			} else {
				return (target.toString());
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'castString');
			return ('');
		}
	};

	/**
	 * 数字へのキャスト
	 *
	 * @param {object}
	 *            target 対象
	 * @param {bool}
	 *            type キャストタイプ true:小数 / false:整数 [デフォルト:true]
	 * @return {number} 数値 (null / undefined / 数値以外の場合は 0 を返す)
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var integer = $.castNumber(text, false);
	 */
	jQuery.castNumber = function(target, type) {
		try {
			target = jQuery.mbTrim(target);
			//
			var half = new Array('0', '1', '2', '3', '4', '5', '6', '7', '8',
					'9', '.', ',', '+', '-');
			var full = new Array('０', '１', '２', '３', '４', '５', '６', '７', '８',
					'９', '．', '，', '＋', '－');
			var object = null;
			var i = 0;
			var len = half.length;
			for (i = 0; i < len; i++) {
				object = new RegExp(full[i], 'gm');
				target = target.replace(object, half[i]);
			}
			//
			// 数値形式にマッチするか判定
			var match = target.match(/^[-+]?\d+[\,0-9]*\.?\d*$/);
			var number = 0;
			if (match !== null) {
				match[0] = match[0].replace(/\,/g, '');
				if (type !== false) {
					// 小数
					number = match[0] - 0;
				} else {
					// 整数
					number = parseInt(match[0], 10);
				}
			}
			return (number);
		} catch (e) {
			jQuery.showErrorDetail(e, 'castNumber');
			return (0);
		}
	};

	/**
	 * HTML文へのキャスト
	 *
	 * @param {string}
	 *            target 対象
	 * @return {string} 処理文字列 (null / undefined の場合は空文字を返す)
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var string = $.castHtml(text);
	 */
	jQuery.castHtml = function(target) {
		try {
			target = jQuery.castString(target);
			// HTML コードに変換
			target = target.replace(/\&/g, '\&amp;');
			target = target.replace(/\</g, '\&lt;').replace(/\>/g, '\&gt;')
					.replace(/\"/g, '\&quot;');
			return (target);
		} catch (e) {
			jQuery.showErrorDetail(e, 'castHtml');
			return ('');
		}
	};

	// ----------+[ ブラウザ情報関連 ]+----------
	/**
	 * ブラウザの種類取得
	 *
	 * @return {string} ブラウザ名 [Chrome / Safari / Netscape / IE / Firefox /
	 *         Opera] (ユーザーエージェントから確認)
	 * @public
	 * @requires jQuery.showErrorDetail
	 * @example var browser = $.getBrowserName();
	 */
	jQuery.getBrowserName = function() {
		try {
			var browserName = '';
			// ユーザーエージェント情報取得
			var userAgent = navigator.userAgent;
			//
			// ブラウザ情報にブラウザ名が入っているか確認
			if (userAgent.indexOf('Chrome') !== -1) {
				// ※ChromeにはSafariの文字列があるので最初に判定
				browserName = 'Chrome';
			} else if (userAgent.indexOf('Safari') !== -1) {
				browserName = 'Safari';
			} else if (userAgent.indexOf('Netscape') !== -1) {
				browserName = 'Netscape';
			} else if (userAgent.indexOf('MSIE') !== -1 || userAgent.indexOf('NET') !== -1) {
				browserName = 'IE';
			} else if (userAgent.indexOf('Firefox') !== -1) {
				browserName = 'Firefox';
			} else if (userAgent.indexOf('Opera') !== -1) {
				browserName = 'Opera';
			}
			return (browserName);
		} catch (e) {
			jQuery.showErrorDetail(e, 'getBrowserName');
			return ('');
		}
	};

	/**
	 * タブレット PC / スマートフォン
	 *
	 * @return {bool} true:タブレット PC / スマートフォン / false:それ以外 (ユーザーエージェントから確認)
	 * @public
	 * @requires jQuery.showErrorDetail
	 * @example var browser = $.getBrowserName();
	 */
	jQuery.isTablet = function() {
		try {
			// ユーザーエージェント情報取得
			var userAgent = navigator.userAgent;
			//
			if (userAgent.indexOf('iPhone') > 0
					|| userAgent.indexOf('iPad') > 0
					|| userAgent.indexOf('iPod') > 0
					|| userAgent.indexOf('Android') > 0) {
				return (true);
			} else {
				return (false);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'isTablet');
			return (false);
		}
	};

	/**
	 * スクロールバーの幅取得
	 *
	 * @return {integer} スクロールバーの幅 (ブラウザの表示倍率によって正確な値が取れない場合有り)
	 * @public
	 * @requires jQuery.showErrorDetail
	 * @example var width = $.getScrollbarWidth();
	 */
	jQuery.getScrollbarWidth = function() {
		try {
			// divによるbox入れ子作成
			var div = jQuery('<div style="height:50px;width:50px;overflow:scroll;visibility:hidden;"><div style="height:100px;"></div></div>');
			// 実際にスクリーン出力 (style="visibility:hidden"で見えない)
			jQuery('body').append(div);
			// 内側と外側の差分を求める
			var difference = div.width() - jQuery('div', div).width();
			// スクリーン出力を削除
			jQuery(div).remove();
			//
			return (parseInt(difference, 10));
		} catch (e) {
			jQuery.showErrorDetail(e, 'getScrollbarWidth');
			return (0);
		}
	};

	/**
	 * cookieの有無判定
	 *
	 * @return {bool} true:cookie有効 / false:cookie無効
	 * @public
	 * @requires jQuery.showErrorDetail
	 * @example if (!$.checkCookie()) { alert('Cookie が無効です'); }
	 */
	jQuery.checkCookie = function() {
		try {
			if (navigator.cookieEnabled === true) {
				return (true);
			} else {
				return (false);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'checkCookie');
			return (false);
		}
	};

	/**
	 * プロキシの有無判定
	 *
	 * @return {bool} true:プロキシ利用 / false:プロキシ非利用(判定不可)
	 * @public
	 * @requires _createXMLHttp
	 * @requires jQuery.showErrorDetail
	 * @example if ($.checkProxy()) { alert('Proxy 経由でアクセスしています'); }
	 */
	jQuery.checkProxy = function() {
		try {
			var xmlHttp = _createXMLHttp();
			var timetemp = new Date;
			if (xmlHttp) {
				// 存在しない名前のファイルをGETして404を取得する
				xmlHttp.open('GET', '/dummy.' + timetemp.getTime(), false);
				xmlHttp.send(null);
				if (xmlHttp.getAllResponseHeaders().indexOf('Proxy-Connection',
						0) !== -1) {
					// HTTPヘッダにプロキシ情報有
					return (true);
				} else {
					return (false);
				}
			} else {
				return (false);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'checkProxy');
			return (false);
		}
	};

	/**
	 * HTTP request を送信するための XMLHttps オブジェクト作成
	 *
	 * @return {object} XMLHttpRequest
	 * @private
	 * @example var xmlHttp = _createXMLHttp();
	 */
	function _createXMLHttp() {
		try {
			return (new ActiveXObject('MSXML2.XMLHTTP'));
		} catch (e) {
			try {
				return (new ActiveXObject('Microsoft.XMLHTTP'));
			} catch (e) {
				try {
					return (new XMLHttpRequest());
				} catch (e) {
					return (null);
				}
			}
		}
		return (null);
	}

	// ----------+[ サーバー関連 ]+----------
	/**
	 * form タグ内の Enter 送信禁止
	 *
	 * @param {object}
	 *            object form タグの jQuery オブジェクト
	 * @public
	 * @requires jQuery.getJQuery
	 * @requires jQuery.showErrorDetail
	 * @example $.formSendCancel($('#form'));
	 */
	jQuery.formSendCancel = function(object) {
		try {
			object = jQuery.getJQuery(object, false);
			if (object === null) {
				return;
			}
			jQuery('input', object).keypress(
					function(event) {
						if ((event.which && event.which === 13)
								|| (event.keyCode && event.keyCode === 13)) {
							// Enter -> キャンセル
							return (false);
						} else {
							return (true);
						}
					});
		} catch (e) {
			jQuery.showErrorDetail(e, 'formSendCancel');
		}
	};

	/**
	 * パラメータの POST 送信
	 *
	 * @param {string}
	 *            url POST 先 URL
	 * @param {mixed}
	 *            parameter POST 送信パラメータ
	 * @param {bool}
	 *            target 送信ターゲット true:_blank / false:通常 [デフォルト:false]
	 * @public
	 * @requires _createSendData
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var param = new Array('A', 'B', 'C'); $.sendPost('./sample.php',
	 *          param);
	 */
	jQuery.sendPost = function(url, parameter, target) {
		try {
			url = jQuery.castString(url);
			//
			var html = '';
			var key = null;
			html += '<form class="jquery-send-post" action="' + url
					+ '" method="post" style="display: none;"'
					+ ((target !== true) ? '' : ' target="_blank"') + '>';
			for (key in parameter) {
				html += _createSendData(key, parameter[key]);
			}
			html += '</form>';
			//
			jQuery('.jquery-send-post').remove();
			jQuery('body').append(html);
			//
			// submit
			jQuery('.jquery-send-post:last').submit();
		} catch (e) {
			jQuery.showErrorDetail(e, 'sendPost');
			return;
		}
	};

	/**
	 * パラメータの GET 送信
	 *
	 * @param {string}
	 *            url GET 先 URL
	 * @param {mixed}
	 *            parameter GET 送信パラメータ
	 * @param {bool}
	 *            target 送信ターゲット true:_blank / false:通常 [デフォルト:false]
	 * @public
	 * @requires _createSendData
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var param = new Array('A', 'B', 'C'); $.sendPost('./sample.php',
	 *          param);
	 */
	jQuery.sendGet = function(url, parameter, target) {
		try {
			url = jQuery.castString(url);
			//
			var html = '';
			var key = null;
			html += '<form class="jquery-send-get" action="' + url
					+ '" method="get" style="display: none;"'
					+ ((target !== true) ? '' : ' target="_blank"') + '>';
			for (key in parameter) {
				html += _createSendData(key, parameter[key]);
			}
			html += '</form>';
			//
			jQuery('.jquery-send-post').remove();
			jQuery('body').append(html);
			//
			// submit
			jQuery('.jquery-send-get:last').submit();
		} catch (e) {
			jQuery.showErrorDetail(e, 'sendGet');
			return;
		}
	};

	/**
	 * データ送信用の form 作成 (null は送信しない)
	 *
	 * @param {string}
	 *            name POST キー名
	 * @param {object}
	 *            object POST データ
	 * @param {string}
	 *            bracket 階層 [デフォルト:'']
	 * @private
	 * @requires jQuery.castString
	 * @requires jQuery.castHtml
	 * @requires jQuery.getType
	 * @requires jQuery.showErrorDetail
	 * @example var html = ''; var param = new Array('A', 'B', 'C'); for (var
	 *          key in param) { html += _createSendData(key, param[key]); }
	 */
	function _createSendData(name, object, bracket) {
		try {
			bracket = jQuery.castString(bracket);
			var type = jQuery.getType(object);
			var html = '';
			if (type === 'string' || type === 'number' || type === 'boolean') {
				html += '<input type="hidden" name="' + jQuery.castHtml(name)
						+ bracket + '" value="' + jQuery.castHtml(object)
						+ '" />';
			} else if (type === 'array') {
				var i = 0;
				var length = type.length;
				for (i = 0; i < length; i++) {
					html += _createSendData(name, object[i], bracket + '[' + i
							+ ']');
				}
			} else if (type === 'hash') {
				var key = null;
				for (key in object) {
					html += _createSendData(name, object[key], bracket + '['
							+ key + ']');
				}
			}
			//
			return (html);
		} catch (e) {
			jQuery.showErrorDetail(e, 'createPostData');
			return ('');
		}
	}

	/**
	 * ASP.NET Web サービス連携 (複数の場合は zip ファイルをダウンロード)
	 *
	 * @param {array}
	 *            type 帳票種別 (WEB サービスで帳票設定をしている箇所でレポートファイルを決める為の ID)
	 * @param {array}
	 *            sql 帳票用データ取得 SQL 文
	 * @param {array}
	 *            file ファイル名
	 * @param {string}
	 *            unzip ダウンロード時のファイル名 [デフォルト:ダウンロードファイル]
	 * @public
	 * @requires _phpFile['activereports'] で定義された PHP ファイル
	 * @requires jQuery.castString
	 * @requires jQuery.getType
	 * @requires jQuery.getPath
	 * @requires jQuery.showErrorDetail
	 * @example $.downloadPdf( ['sample', 'text'], ['EXECUTE SPC_SAPME',
	 *          'EXECUTE SPC_TEST'], ['sample_file.pdf', 'test_file.pdf'] );
	 */
	jQuery.downloadPdf = function(type, sql, file, unzip) {
		try {
			var i = 0;
			var len = 0;
			//
			unzip = jQuery.mbTrim(unzip);
			if (unzip === '') {
				unzip = 'ダウンロードファイル';
			}
			//
			if (jQuery.getType(type) === 'string') {
				type = new Array(jQuery.castString(type));
			} else {
				if (jQuery.getType(type) === 'array') {
					len = type.length;
					for (i = 0; i < len; i++) {
						type[i] = jQuery.castString(type[i]);
					}
				} else {
					type = new Array();
				}
			}
			//
			if (jQuery.getType(sql) === 'string') {
				sql = new Array(jQuery.castString(sql));
			} else {
				if (jQuery.getType(sql) === 'array') {
					len = sql.length;
					for (i = 0; i < len; i++) {
						sql[i] = jQuery.castString(sql[i]);
					}
				} else {
					sql = new Array();
				}
			}
			//
			if (jQuery.getType(file) === 'string') {
				file = new Array(jQuery.castString(file));
			} else {
				if (jQuery.getType(file) === 'array') {
					len = file.length;
					for (i = 0; i < len; i++) {
						file[i] = jQuery.castString(file[i]);
					}
				} else {
					file = new Array();
				}
			}
			//
			if (type.length !== sql.length || sql.length !== file.length) {
				alert('Web サービスの引数に指定された配列の要素数が一致しません。\n全ての配列の要素数が同じになる必要があります。');
				return;
			}
			//
			// Ajax 通信設定
			var inputHtml = '';
			len = type.length;
			for (i = 0; i < len; i++) {
				inputHtml += '<input type="hidden" name="type[' + i
						+ ']" value="' + type[i] + '" />';
				inputHtml += '<input type="hidden" name="sql[' + i
						+ ']" value="' + sql[i] + '" />';
				inputHtml += '<input type="hidden" name="file[' + i
						+ ']" value="' + file[i] + '" />';
			}
			//
			jQuery('.activereports-pdf-download').remove();
			var html = '';
			html += '<div class="activereports-pdf-download" style="display: none;">';
			html += '<form action="'
					+ jQuery.getPath('php')
					+ _phpFile['activereports']
					+ '" method="post" target="activereports-pdf-download-target">';
			html += inputHtml;
			html += '<input type="hidden" name="unzip" value="' + unzip
					+ '" />';
			html += '</form>';
			html += '<iframe name="activereports-pdf-download-target" style="width: 0px; height: 0px; border: 0px transparent solid;"></iframe>';
			html += '</div>';
			jQuery('body').append(html);
			//
			var div = jQuery('.activereports-pdf-download').last();
			// 送信
			jQuery('form', div).submit();
		} catch (e) {
			jQuery.showErrorDetail(e, 'downloadPdf');
		}
	};
	/**
	 * Function viet rieng cho FreeSale Project
	 *
	 */
	jQuery.FS_downloadPdf = function(type, sql, file, report_type) {
		try {
			location.href = '/phpexport/phppdf/download/type/'+JSON.stringify(type)+'/sql/'+JSON.stringify(sql)+'/file/'+JSON.stringify(file)+'/reportType/'+JSON.stringify(report_type);
		} catch (e) {
			jQuery.showErrorDetail(e, 'downloadPdf');
		}
	};
	/**
	 * ASP.NET Web サービス連携 (複数の場合は zip ファイルをダウンロード)
	 *
	 * @param {array}
	 *            type 帳票種別 (WEB サービスで帳票設定をしている箇所でレポートファイルを決める為の ID)
	 * @param {array}
	 *            sql 帳票用データ取得 SQL 文
	 * @param {array}
	 *            file ファイル名
	 * @param {string}
	 *            unzip ダウンロード時のファイル名 [デフォルト:ダウンロードファイル]
	 * @public
	 * @requires _phpFile['csv'] で定義された PHP ファイル
	 * @requires jQuery.castString
	 * @requires jQuery.getType
	 * @requires jQuery.getPath
	 * @requires jQuery.showErrorDetail
	 * @example $.downloadCsv( ['sample', 'text'], ['EXECUTE SPC_SAPME',
	 *          'EXECUTE SPC_TEST'], ['sample_file.csv', 'test_file.csv'] );
	 */
	jQuery.report = function(typeExport, type, sql, file, unzip) {
		try {
			var i = 0;
			var len = 0;
			//
			unzip = jQuery.mbTrim(unzip);
			if (unzip === '') {
				unzip = 'ダウンロードファイル';
			}
			if (jQuery.getType(typeExport) === 'string') {
				typeExport = new Array(jQuery.castString(typeExport));
			} else {
				if (jQuery.getType(typeExport) === 'array') {
					len = typeExport.length;
					for (i = 0; i < len; i++) {
						typeExport[i] = jQuery.castString(typeExport[i]);
					}
				} else {
					typeExport = new Array();
				}
			}
			//
			if (jQuery.getType(type) === 'string') {
				type = new Array(jQuery.castString(type));
			} else {
				if (jQuery.getType(type) === 'array') {
					len = type.length;
					for (i = 0; i < len; i++) {
						type[i] = jQuery.castString(type[i]);
					}
				} else {
					type = new Array();
				}
			}
			//
			if (jQuery.getType(sql) === 'string') {
				sql = new Array(jQuery.castString(sql));
			} else {
				if (jQuery.getType(sql) === 'array') {
					len = sql.length;
					for (i = 0; i < len; i++) {
						sql[i] = jQuery.castString(sql[i]);
					}
				} else {
					sql = new Array();
				}
			}
			//
			if (jQuery.getType(file) === 'string') {
				file = new Array(jQuery.castString(file));
			} else {
				if (jQuery.getType(file) === 'array') {
					len = file.length;
					for (i = 0; i < len; i++) {
						file[i] = jQuery.castString(file[i]);
					}
				} else {
					file = new Array();
				}
			}
			//
			if (type.length !== sql.length || sql.length !== file.length) {
				alert('Web サービスの引数に指定された配列の要素数が一致しません。\n全ての配列の要素数が同じになる必要があります。');
				return;
			}
			//
			// Ajax 通信設定
			var inputHtml = '';
			len = type.length;
			for (i = 0; i < len; i++) {
				inputHtml += '<input type="hidden" name="typeExport[' + i
						+ ']" value="' + typeExport[i] + '" />';
				inputHtml += '<input type="hidden" name="type[' + i
						+ ']" value="' + type[i] + '" />';
				inputHtml += '<input type="hidden" name="sql[' + i
						+ ']" value="' + sql[i] + '" />';
				inputHtml += '<input type="hidden" name="file[' + i
						+ ']" value="' + file[i] + '" />';
			}
			//
			jQuery('.activereports-csv-download').remove();
			var html = '';
			html += '<div class="activereports-csv-download" style="display: none;">';
			html += '<form action="'
					+ jQuery.getPath('php')
					+ _phpFile['csv']
					+ '" method="post" target="activereports-csv-download-target">';
			html += inputHtml;
			html += '<input type="hidden" name="unzip" value="' + unzip
					+ '" />';
			html += '</form>';
			html += '<iframe name="activereports-csv-download-target" style="width: 0px; height: 0px; border: 0px transparent solid;"></iframe>';
			html += '</div>';
			jQuery('body').append(html);
			//
			var div = jQuery('.activereports-csv-download').last();
			// 送信
			jQuery('form', div).submit();
		} catch (e) {
			jQuery.showErrorDetail(e, 'downloadCsv');
		}
	};

	/**
	 * ASP.NET Web サービス連携コールバック
	 *
	 * @param {string}
	 *            message メッセージ
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.msgBox
	 * @requires jQuery.showErrorDetail
	 * @example $.downloadPdfCallback(message);
	 */
	jQuery.downloadPdfCallback = function(message) {
		try {
			message = jQuery.castString(message);
			if (message !== '') {
				// エラーあり
				var param = new Object();
				param['caption'] = 'エラー';
				param['icon'] = 'error';
				//
				jQuery.msgBox(message, param);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'downloadPdfCallback');
		}
	};

	/**
	 * ファイルダウンロード (複数の場合は zip ファイルをダウンロード)
	 *
	 * @param {array}
	 *            filePath ファイルのパス (const.php ベース)
	 * @param {array}
	 *            fileName ダウンロード時のファイル名
	 * @param {string}
	 *            unzip ダウンロード時のファイル名 [デフォルト:ダウンロードファイル]
	 * @public
	 * @requires _phpFile['fileDownload'] で定義された PHP ファイル
	 * @requires jQuery.castString
	 * @requires jQuery.getType
	 * @requires jQuery.getPath
	 * @requires jQuery.showErrorDetail
	 * @example $.downloadFile( ['sample.pdf', 'test.doc'], ['サンプル.pdf',
	 *          'テスト.doc'] );
	 */
	jQuery.downloadFile = function(filePath, fileName, unzip) {
		try {
			var i = 0;
			var len = 0;
			//
			unzip = jQuery.mbTrim(unzip);
			if (unzip === '') {
				unzip = 'ダウンロードファイル';
			}
			//
			if (jQuery.getType(filePath) === 'string') {
				filePath = new Array(jQuery.castString(filePath));
			} else {
				if (jQuery.getType(filePath) === 'array') {
					len = filePath.length;
					for (i = 0; i < len; i++) {
						filePath[i] = jQuery.castString(filePath[i]);
					}
				} else {
					filePath = new Array();
				}
			}
			//
			if (jQuery.getType(fileName) === 'string') {
				fileName = new Array(jQuery.castString(fileName));
			} else {
				if (jQuery.getType(fileName) === 'array') {
					len = fileName.length;
					for (i = 0; i < len; i++) {
						fileName[i] = jQuery.castString(fileName[i]);
					}
				} else {
					if (fileName == null) {
						fileName = new Array();
						len = filePath.length;
						for (i = 0; i < len; i++) {
							fileName[i] = '';
						}
					} else {
						fileName = new Array();
					}
				}
			}
			//
			if (filePath.length !== fileName.length) {
				alert('引数に指定された配列の要素数が一致しません。\n全ての配列の要素数が同じになる必要があります。');
				return;
			}
			//
			// Ajax 通信設定
			var inputHtml = '';
			len = filePath.length;
			for (i = 0; i < len; i++) {
				inputHtml += '<input type="hidden" name="file-path[' + i
						+ ']" value="' + filePath[i] + '" />';
				inputHtml += '<input type="hidden" name="file-name[' + i
						+ ']" value="' + fileName[i] + '" />';
			}
			//
			jQuery('.php-file-download').remove();
			var html = '';
			html += '<div class="php-file-download" style="display: none;">';
			html += '<form action="' + jQuery.getPath('php')
					+ _phpFile['fileDownload']
					+ '" method="post" target="php-file-download-target">';
			html += inputHtml;
			html += '<input type="hidden" name="unzip" value="' + unzip
					+ '" />';
			html += '</form>';
			html += '<iframe name="php-file-download-target" style="width: 0px; height: 0px; border: 0px transparent solid;"></iframe>';
			html += '</div>';
			jQuery('body').append(html);
			//
			var div = jQuery('.php-file-download').last();
			// 送信
			jQuery('form', div).submit();
		} catch (e) {
			jQuery.showErrorDetail(e, 'downloadFile');
		}
	};

	/**
	 * ファイルダウンロードコールバック
	 *
	 * @param {string}
	 *            message メッセージ
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.msgBox
	 * @requires jQuery.showErrorDetail
	 * @example $.downloadFileCallback(message);
	 */
	jQuery.downloadFileCallback = function(message) {
		try {
			message = jQuery.castString(message);
			//
			if (message !== '') {
				// エラーあり
				var param = new Object();
				param['caption'] = 'エラー';
				param['icon'] = 'error';
				//
				jQuery.msgBox(message, param);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'downloadFileCallback');
		}
	};

	/**
	 * データ出力
	 *
	 * @param {string}
	 *            type ダウンロード形式 xls:Excel2003 / xlsx:Excel2007 / csv:CSV /
	 *            tsv:TSV
	 * @param {string}
	 *            sql データ取得用 SQL 文 (結果セットの 1 番目のみ) (INPUT / UPDATE / DELETE /
	 *            DROOP/ TRUNCATE を含むものは実行不可)
	 * @param {string}
	 *            fileName ダウンロード時のファイル名 (拡張子は自動判別)
	 * @param {bool}
	 *            header true:列名あり / false:列名なし [デフォルト:true]
	 * @param {bool}
	 *            quotation true:CSV,TSV 時にデータを"で囲む / false:CSV,TSV 時にデータを"で囲まない
	 *            [デフォルト:false]
	 * @public
	 * @requires _phpFile['dataOutput'] で定義された PHP ファイル
	 * @requires jQuery.castString
	 * @requires jQuery.mbTrim
	 * @requires jQuery.getPath
	 * @requires jQuery.showErrorDetail
	 * @example $.dataOutput('xls', 'EXECUTE SPC_DATAOUTPUT', 'sample.xls',
	 *          false);
	 */
	jQuery.dataOutput = function(type, sql, fileName, header, quotation) {
		try {
			type = jQuery.mbTrim(type);
			sql = jQuery.mbTrim(sql).replace(/\n/g, ' ').replace(/\r/g, ' ');
			fileName = jQuery.castString(fileName);
			if (header !== false) {
				header = 'true';
			} else {
				header = 'false';
			}
			if (quotation !== true) {
				quotation = 'false';
			} else {
				quotation = 'true';
			}
			//
			// Ajax 通信設定
			jQuery('.php-data-output').remove();
			var html = '';
			html += '<div class="php-data-output" style="display: none;">';
			html += '<form action="' + jQuery.getPath('php')
					+ _phpFile['dataOutput']
					+ '" method="post" target="php-data-output-target">';
			html += '<input type="hidden" name="type"      value="' + type
					+ '" />';
			html += '<input type="hidden" name="sql"       value="' + sql
					+ '" />';
			html += '<input type="hidden" name="file-name" value="' + fileName
					+ '" />';
			html += '<input type="hidden" name="header"    value="' + header
					+ '" />';
			html += '<input type="hidden" name="quotation" value="' + quotation
					+ '" />';
			html += '</form>';
			html += '<iframe name="php-data-output-target" style="width: 0px; height: 0px; border: 0px transparent solid;"></iframe>';
			html += '</div>';
			jQuery('body').append(html);
			//
			var div = jQuery('.php-data-output').last();
			// 送信
			jQuery('form', div).submit();
		} catch (e) {
			jQuery.showErrorDetail(e, 'dataOutput');
		}
	};

	/**
	 * データ出力コールバック
	 *
	 * @param {string}
	 *            message メッセージ
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.msgBox
	 * @requires jQuery.showErrorDetail
	 * @example $.dataOutputCallback(message);
	 */
	jQuery.dataOutputCallback = function(message) {
		try {
			message = jQuery.castString(message);
			//
			if (message !== '') {
				// エラーあり
				var param = new Object();
				param['caption'] = 'エラー';
				param['icon'] = 'error';
				//
				jQuery.msgBox(message, param);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'dataOutputCallback');
		}
	};

	/**
	 * 帳票作成 + 既存ファイルダウンロード (複数の場合は zip ファイルをダウンロード)
	 *
	 * @param {array}
	 *            type 帳票種別
	 * @param {array}
	 *            sql 帳票用データ取得 SQL 文
	 * @param {array}
	 *            file ファイル名
	 * @param {array}
	 *            filePath ファイルのパス (const.php ベース)
	 * @param {array}
	 *            fileName ダウンロード時のファイル名
	 * @param {string}
	 *            unzip ダウンロード時のファイル名 [デフォルト:ダウンロードファイル]
	 * @public
	 * @requires _phpFile['fileCreateDownload'] で定義された PHP ファイル
	 * @requires jQuery.castString
	 * @requires jQuery.getType
	 * @requires jQuery.getPath
	 * @requires jQuery.showErrorDetail
	 * @example $.createDownloadFile( ['sample.pdf', 'test.doc'], ['サンプル.pdf',
	 *          'テスト.doc'], ['sample', 'text'], ['EXECUTE SPC_SAPME', 'EXECUTE
	 *          SPC_TEST'], ['sample_file.pdf', 'test_file.pdf'] );
	 */
	jQuery.createDownloadFile = function(type, sql, file, filePath, fileName,
			unzip) {
		try {
			var i = 0;
			var len = 0;
			//
			unzip = jQuery.mbTrim(unzip);
			if (unzip === '') {
				unzip = 'ダウンロードファイル';
			}
			//
			// PDF 作成
			if (jQuery.getType(type) === 'string') {
				type = new Array(jQuery.castString(type));
			} else {
				if (jQuery.getType(type) === 'array') {
					len = type.length;
					for (i = 0; i < len; i++) {
						type[i] = jQuery.castString(type[i]);
					}
				} else {
					type = new Array();
				}
			}
			//
			if (jQuery.getType(sql) === 'string') {
				sql = new Array(jQuery.castString(sql));
			} else {
				if (jQuery.getType(sql) === 'array') {
					len = sql.length;
					for (i = 0; i < len; i++) {
						sql[i] = jQuery.castString(sql[i]);
					}
				} else {
					sql = new Array();
				}
			}
			//
			if (jQuery.getType(file) === 'string') {
				file = new Array(jQuery.castString(file));
			} else {
				if (jQuery.getType(file) === 'array') {
					len = file.length;
					for (i = 0; i < len; i++) {
						file[i] = jQuery.castString(file[i]);
					}
				} else {
					file = new Array();
				}
			}
			//
			if (type.length !== sql.length || sql.length !== file.length) {
				alert('Web サービスの引数に指定された配列の要素数が一致しません。\n全ての配列の要素数が同じになる必要があります。');
				return;
			}
			//
			// 既存ファイルダウンロード
			if (jQuery.getType(filePath) === 'string') {
				filePath = new Array(jQuery.castString(filePath));
			} else {
				if (jQuery.getType(filePath) === 'array') {
					len = filePath.length;
					for (i = 0; i < len; i++) {
						filePath[i] = jQuery.castString(filePath[i]);
					}
				} else {
					filePath = new Array();
				}
			}
			//
			if (jQuery.getType(fileName) === 'string') {
				fileName = new Array(jQuery.castString(fileName));
			} else {
				if (jQuery.getType(fileName) === 'array') {
					len = fileName.length;
					for (i = 0; i < len; i++) {
						fileName[i] = jQuery.castString(fileName[i]);
					}
				} else {
					if (fileName == null) {
						fileName = new Array();
						len = filePath.length;
						for (i = 0; i < len; i++) {
							fileName[i] = '';
						}
					} else {
						fileName = new Array();
					}
				}
			}
			//
			if (filePath.length !== fileName.length) {
				alert('引数に指定された配列の要素数が一致しません。\n全ての配列の要素数が同じになる必要があります。');
				return;
			}
			//
			// Ajax 通信設定
			jQuery('.create-download-file').remove();
			var inputHtml = '';
			var html = '';
			//
			len = type.length;
			for (i = 0; i < len; i++) {
				inputHtml += '<input type="hidden" name="type[' + i
						+ ']" value="' + type[i] + '" />';
				inputHtml += '<input type="hidden" name="sql[' + i
						+ ']" value="' + sql[i] + '" />';
				inputHtml += '<input type="hidden" name="file[' + i
						+ ']" value="' + file[i] + '" />';
			}
			len = filePath.length;
			for (i = 0; i < len; i++) {
				inputHtml += '<input type="hidden" name="file-path[' + i
						+ ']" value="' + filePath[i] + '" />';
				inputHtml += '<input type="hidden" name="file-name[' + i
						+ ']" value="' + fileName[i] + '" />';
			}
			html += '<div class="create-download-file" style="display: none;">';
			html += '<form action="' + jQuery.getPath('php')
					+ _phpFile['fileCreateDownload']
					+ '" method="post" target="create-download-file-target">';
			html += inputHtml;
			html += '<input type="hidden" name="unzip" value="' + unzip
					+ '" />';
			html += '</form>';
			html += '<iframe name="create-download-file-target" style="width: 0px; height: 0px; border: 0px transparent solid;"></iframe>';
			html += '</div>';
			jQuery('body').append(html);
			//
			var div = jQuery('.create-download-file').last();
			// 送信
			jQuery('form', div).submit();
		} catch (e) {
			jQuery.showErrorDetail(e, 'createDownloadFile');
		}
	};

	/**
	 * 帳票作成 + 既存ファイルダウンロードコールバック
	 *
	 * @param {string}
	 *            message メッセージ
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.msgBox
	 * @requires jQuery.showErrorDetail
	 * @example $.createDownloadFileCallback(message);
	 */
	jQuery.createDownloadFileCallback = function(message) {
		try {
			message = jQuery.castString(message);
			//
			if (message !== '') {
				// エラーあり
				var param = new Object();
				param['caption'] = 'エラー';
				param['icon'] = 'error';
				//
				jQuery.msgBox(message, param);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'createDownloadFileCallback');
		}
	};

	/**
	 * メール送信
	 *
	 * @param {hash}
	 *            option オプション
	 * @param {hash}
	 *            option.from 送信元
	 * @param {string}
	 *            option.from.mail メールアドレス (必須)
	 * @param {string}
	 *            option.from.name 表示名
	 * @param {array}
	 *            option.to 送信先
	 * @param {string}
	 *            option.to.0.mail メールアドレス (1件は必須)
	 * @param {string}
	 *            option.to.0.name 表示名
	 * @param {array}
	 *            option.cc カーボンコピー
	 * @param {string}
	 *            option.cc.0.mail メールアドレス (1件は必須)
	 * @param {string}
	 *            option.cc.0.name 表示名
	 * @param {array}
	 *            option.bcc ブラインドカーボンコピー
	 * @param {string}
	 *            option.bcc.0.mail メールアドレス (1件は必須)
	 * @param {string}
	 *            option.bcc.0.name 表示名
	 * @param {hash}
	 *            option.reply 返信先
	 * @param {string}
	 *            option.reply.mail メールアドレス (必須)
	 * @param {string}
	 *            option.reply.name 表示名
	 * @param {array}
	 *            option.attach 添付ファイル
	 * @param {string}
	 *            option.attach.0.path ファイルパス (const.php 基準)
	 * @param {string}
	 *            option.attach.0.name 添付時のファイル名
	 * @param {string}
	 *            subject 件名
	 * @param {string}
	 *            body 本文
	 * @param {bool}
	 *            htmlMail true:HTML メール / false:通常メール [デフォルト:false]
	 * @param {string}
	 *            altBody HTML メール時の代替本文
	 * @return {mix} true:送信成功 / 文字列:エラーメッセージ
	 * @public
	 * @requires _phpFile['sendMail'] で定義された PHP ファイル
	 * @requires jQuery.showErrorDetail
	 * @example var from = {mail : 'sample_from@test.co.jp', name : 'Mr.
	 *          Sample_From'}; var to = [ {mail : 'sample_to1@test.co.jp', name :
	 *          'Mr. Sample_To_1'}, {mail : 'sample_to2@test.co.jp', name : 'Mr.
	 *          Sample_To_2'} ]; var cc = [ {mail : 'sample_cc1@test.co.jp',
	 *          name : 'Mr. Sample_Cc_1'}, {mail : 'sample_cc2@test.co.jp', name :
	 *          'Mr. Sample_Cc_2'} ]; var bcc = [ {mail :
	 *          'sample_bcc1@test.co.jp', name : 'Mr. Sample_Bcc_1'}, {mail :
	 *          'sample_bcc2@test.co.jp', name : 'Mr. Sample_Bcc_2'} ]; var
	 *          reply = {mail : 'sample_reply@test.co.jp', name : 'Mr.
	 *          Sample_Reply'}; var subject = 'Subject'; var body = 'mail
	 *          body.\n'; $.createDownloadFileCallback({ from : from, to : to,
	 *          cc : cc, bcc : bcc, reply : reply, subject : subject, body :
	 *          body });
	 */
	jQuery.sendMail = function(option) {
		try {
			var parameter = jQuery.extend({
				from : new Object(), // 送信元
				to : new Array(), // 送信先
				cc : new Array(), // カーボンコピー
				bcc : new Array(), // ブラインドカーボンコピー
				reply : new Object(), // 返信先
				attach : new Object(), // 添付ファイル
				subject : '', // 件名
				body : '', // 本文
				htmlMail : false, // true:HTML メール / false:通常メール
				altBody : '' // HTML メール時の代替本文
			}, option);
			//
			var dataSet = '';
			new jQuery.ajax({
				async : false,
				url : jQuery.getPath('php') + _phpFile['sendMail'],
				data : {
					'from' : parameter['from'],
					'to' : parameter['to'],
					'cc' : parameter['cc'],
					'bcc' : parameter['bcc'],
					'reply' : parameter['reply'],
					'attach' : parameter['attach'],
					'subject' : parameter['subject'],
					'body' : parameter['body'],
					'htmlMail' : ((parameter['htmlMail'] === true) ? 'true'
							: 'false'),
					'altBody' : parameter['altBody']
				},
				type : 'post',
				dataType : 'text',
				timeout : 600000,
				cache : false,
				success : function(data, dataType) {
					try {
						dataSet = data;
					} catch (e) {
						jQuery.showErrorDetail(e, 'sendMail ajax success');
					}
				},
				complete : function(XMLHttpRequest, textStatus) {
					try {
						if (textStatus !== 'success') {
							// エラー
							alert('Ajax error : status [' + textStatus + ']');
						}
					} catch (e) {
						jQuery.showErrorDetail(e, 'sendMail ajax complete');
					}
				}
			});
			//
			if (dataSet === 'true') {
				return (true);
			} else {
				return (dataSet);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'sendMail');
			return ('予期しないエラーが発生しました');
		}
	};

	/**
	 * ファイル表示
	 *
	 * @param {string}
	 *            fileName ファイル名
	 * @public
	 * @requires _phpFile['fileDisplay'] で定義された PHP ファイル
	 * @requires jQuery.castHtml
	 * @requires jQuery.getPath
	 * @requires jQuery.showErrorDetail
	 * @example $.fileDisplay('sample.pdf');
	 */
	jQuery.fileDisplay = function(fileName) {
		try {
			fileName = jQuery.castHtml(fileName);
			//
			var html = '';
			html += '<form class="jquery-file-open" action="'
					+ jQuery.getPath('php') + _phpFile['fileDisplay']
					+ '" method="post" style="display: none;" target="_blank">';
			html += '<input type="hidden" name="file-name" value="' + fileName
					+ '" />';
			html += '</form>';
			//
			jQuery('body').append(html);
			//
			// submit
			jQuery('.jquery-file-open:last').submit().remove();
		} catch (e) {
			jQuery.showErrorDetail(e, 'fileDisplay');
			return;
		}
	};

	/*
	 * +--------------------------------------------+ | 年 |
	 * +---+----------------------------------------+ | y | 2 桁表示 : 01 - 99 | |
	 * Y | 4 桁表示 : 1900 - 9999 | +===+========================================+ |
	 * 月 | +---+----------------------------------------+ | m | 1 桁表示 : 1 - 12 | |
	 * M | 2 桁表示 : 01 - 12 | | f | 略称 (3文字) 形式 : Jan - Dec | | F | フルスペル形式 :
	 * January - December | +===+========================================+ | 日 |
	 * +---+----------------------------------------+ | d | 1 桁表示 : 1 - 31 | | D |
	 * 2 桁表示 : 01 - 31 | +===+========================================+ | 曜日 |
	 * +---+----------------------------------------+ | w | 略称 (3文字) 形式 : Sun -
	 * Sat | | W | フルスペル形式 : Sunday - Saturday |
	 * +===+========================================+ | 時 |
	 * +---+----------------------------------------+ | g | 1 桁表示 / 12時間単 1 - 11 | |
	 * G | 2 桁表示 / 12時間単 01 - 11 | | h | 1 桁表示 / 24時間単 1 - 23 | | H | 2 桁表示 /
	 * 24時間単 01 - 23 | | a | 小文字表示 : 午前 / 午後 am / pm | | A | 大文字表示 : 午前 / 午後 AM /
	 * PM | +===+========================================+ | 分 |
	 * +---+----------------------------------------+ | i | 1 桁表示 : 1 - 59 | | I |
	 * 2 桁表示 : 01 - 59 | +===+========================================+ | 秒 |
	 * +---+----------------------------------------+ | s | 1 桁表示 : 1 - 59 | | S |
	 * 2 桁表示 : 01 - 59 | +---+----------------------------------------+
	 */
	/**
	 * サーバー時刻の取得
	 *
	 * @param {string}
	 *            format フォーマット ※対象表参照
	 * @param {string}
	 *            timeZone タイムゾーン [デフォルト:Asia/Tokyo] (PHP で認められるタイムゾーンのみ有効)
	 * @return {string} フォーマットに対するローカル時刻
	 * @public
	 * @requires jQuery.right
	 * @requires jQuery.showErrorDetail
	 * @example alert($.getServerTime('現在 : Y-M-D (w) a G:I:S'));
	 */
	jQuery.getServerTime = function(format, timeZone) {
		try {
			// 引数確認
			format = jQuery.castString(format);
			if (format === '') {
				return ('');
			}
			timeZone = jQuery.castString(timeZone);
			if (timeZone === '') {
				timeZone = 'Asia/Tokyo';
			}
			// サーバーに対して Ajax 通信 (同期)
			var serverDatetime = '';
			new jQuery.ajax(
					{
						async : false,
						url : jQuery.getPath('php') + _phpFile['serverTime'],
						data : {
							'time-zone' : timeZone
						},
						type : 'post',
						dataType : 'text',
						timeout : 600000,
						cache : false,
						success : function(data, dataType) {
							try {
								serverDatetime = jQuery.castHtml(data);
							} catch (e) {
								jQuery.showErrorDetail(e,
										'getServerTime ajax success');
							}
						},
						complete : function(XMLHttpRequest, textStatus) {
							try {
								if (textStatus !== 'success') {
									// エラー
									alert('Ajax error : status [' + textStatus
											+ ']');
								}
							} catch (e) {
								jQuery.showErrorDetail(e,
										'getServerTime ajax complete');
							}
						}
					});
			//
			var split = serverDatetime.split(' ');
			var serverDate = split[0].split('/');
			var serverTime = split[1].split(':');
			//
			var week = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday',
					'Thursday', 'Friday', 'Saturday');
			var weekShort = new Array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri',
					'Sat');
			var month = new Array('January', 'February', 'March', 'April',
					'May', 'June', 'July', 'August', 'September', 'October',
					'November', 'December');
			var monthShort = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May',
					'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
			//
			var date = new Date(serverDate[0], serverDate[1] - 1,
					serverDate[2], serverTime[0], serverTime[1], serverTime[2]);
			var replace = new Object();
			replace['Y'] = date.getFullYear();
			replace['y'] = jQuery.right(replace['Y'], 2);
			replace['m'] = date.getMonth() + 1;
			replace['M'] = jQuery.right('00' + replace['m'], 2);
			replace['f'] = monthShort[date.getMonth()];
			replace['F'] = month[date.getMonth()];
			replace['d'] = date.getDate();
			replace['D'] = jQuery.right('00' + replace['d'], 2);
			replace['w'] = weekShort[date.getDay()];
			replace['W'] = week[date.getDay()];
			replace['h'] = date.getHours();
			replace['H'] = jQuery.right('00' + replace['h'], 2);
			replace['g'] = replace['h'] % 12;
			replace['G'] = jQuery.right('00' + replace['g'], 2);
			replace['a'] = (replace['h'] < 12) ? 'am' : 'pm';
			replace['A'] = replace['a'].toUpperCase();
			replace['i'] = date.getMinutes();
			replace['I'] = jQuery.right('00' + replace['i'], 2);
			replace['s'] = date.getSeconds();
			replace['S'] = jQuery.right('00' + replace['s'], 2);
			//
			// format を 1 文字ずつ検査
			format = jQuery.castString(format);
			var i = 0;
			var len = format.length;
			var tmp = '';
			var time = '';
			for (i = 0; i < len; i++) {
				tmp = format.charAt(i);
				time += (tmp in replace) ? replace[tmp] : tmp;
			}
			//
			return (time);
		} catch (e) {
			jQuery.showErrorDetail(e, 'getServerTime');
			return ('');
		}
	};

	/**
	 * アクセスユーザーの IP アドレス取得
	 *
	 * @return {string} アクセスユーザーの IP アドレス
	 * @public
	 * @requires _phpFile['ipAddress'] で定義された PHP ファイル
	 * @requires jQuery.getPath
	 * @requires jQuery.showErrorDetail
	 * @example var ip = $.getIpAddress();
	 */
	jQuery.getIpAddress = function() {
		try {
			// サーバーに対して Ajax 通信 (同期)
			var ipAddress = '';
			new jQuery.ajax(
					{
						async : false,
						url : jQuery.getPath('php') + _phpFile['ipAddress'],
						type : 'post',
						dataType : 'text',
						timeout : 600000,
						cache : false,
						success : function(data, dataType) {
							try {
								ipAddress = jQuery.castHtml(data);
							} catch (e) {
								jQuery.showErrorDetail(e,
										'getIpAddress ajax success');
							}
						},
						complete : function(XMLHttpRequest, textStatus) {
							try {
								if (textStatus !== 'success') {
									// エラー
									alert('Ajax error : status [' + textStatus
											+ ']');
								}
							} catch (e) {
								jQuery.showErrorDetail(e,
										'getIpAddress ajax complete');
							}
						}
					});
			//
			return (ipAddress);
		} catch (e) {
			jQuery.showErrorDetail(e, 'getIpAddress');
			return ('');
		}
	};

	/**
	 * URL の履歴取得
	 *
	 * @param {integer}
	 *            index 履歴のインデックス (インデックス 0 = 自分自身の URL) [デフォルト:0]
	 * @return {string} インデックスに対応した URL (インデックスが非存在の場合は空文字)
	 * @public
	 * @requires _phpFile['urlHistory'] で定義された PHP ファイル
	 * @requires jQuery.getPath
	 * @requires jQuery.showErrorDetail
	 * @example var ip = $.getIpAddress();
	 */
	jQuery.getUrlHistory = function(index) {
		try {
			index = jQuery.castNumber(index, false);
			//
			// サーバーに対して Ajax 通信 (同期)
			var object = '';
			new jQuery.ajax({
				async : false,
				url : jQuery.getPath('php') + _phpFile['urlHistory'],
				data : {
					'index' : index
				},
				type : 'post',
				dataType : 'text',
				timeout : 600000,
				cache : false,
				success : function(data, dataType) {
					object = jQuery.castString(data);
					// ルート部分は削除
					object = object.replace(jQuery.getPath('php'), '');
				},
				complete : function(XMLHttpRequest, textStatus) {
					try {
						if (textStatus !== 'success') {
							// エラー
							alert('Ajax error : status [' + textStatus + ']');
						}
					} catch (e) {
						jQuery
								.showErrorDetail(e,
										'getUrlHistory ajax complete');
					}
				}
			});
			//
			return (object);
		} catch (e) {
			jQuery.showErrorDetail(e, 'getUrlHistory');
			return ('');
		}
	};

	/**
	 * 住所取得
	 *
	 * @param {string}
	 *            postcode 郵便番号
	 * @param {hash}
	 *            option オプション
	 * @param {function}
	 *            option.function Ajax 通信のコールバック関数 (第一引数:住所,
	 *            第二引数:option.argument)
	 * @param {mixed}
	 *            option.argument コールバック関数への引数
	 * @param {object}
	 *            target 住所を書き出す jQuery オブジェクト
	 * @param {integer}
	 *            type 返却のタイプ 0:文字列 / 1:配列 -> 都道府県 + 市区町村 + 番地 [デフォルト:0]
	 * @public
	 * @requires http://ajaxzip3.googlecode.com/svn/trunk/ajaxzip3/ajaxzip3.js
	 * @requires https://ajaxzip3.googlecode.com/svn/trunk/ajaxzip3/ajaxzip3-https.js
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.generateRandomNumber
	 * @requires jQuery.getJQuery
	 * @requires jQuery.showErrorDetail
	 * @example var ip = $.getAddress( '012-3456', { function :
	 *          function(address, arg) { $('#adress1').val(address[0]);
	 *          $('#adress2').val(address[1]); $('#adress3').val(address[2]); },
	 *          argument : null }, $('#adress1'), 1 );
	 */
	jQuery.getAddress = function(postcode, option, target, type) {
		try {
			if (typeof (AjaxZip3) !== 'function') {
				// AjaxZip3 の読み込みがない
				alert('郵便番号取得モジュールが見つかりません。');
				return;
			}
			var interval = 200; // 通信完了の監視インターバル(ミリ秒)
			var countMax = 7; // 通信の監視回数
			var deleteTime = 300; // オブジェクト削除までのインターバル(ミリ秒)
			//
			// オプション
			var parameter = new Object();
			parameter['function'] = null; // Ajax 通信のコールバック関数
			parameter['argument'] = null; // コールバック関数への引数
			//
			var key = null;
			if (jQuery.getType(option) === 'hash') {
				for (key in option) {
					if (key in parameter) {
						parameter[key] = option[key];
					}
				}
			}
			//
			postcode = jQuery.castString(postcode).replace(/[^0-9]/g, '');
			if (postcode.length !== 7) {
				// 郵便番号が数字 7 桁以外
				return;
			}
			if (typeof (parameter['function']) !== 'function') {
				// コールバックに実行するものがない
				return;
			}
			type = jQuery.castNumber(type, false);
			//
			// HTML 作成
			var id = 'ajax-postcode-' + jQuery.generateRandomNumber(1000, 9999);
			var html = '';
			target = jQuery.getJQuery(target, false);
			if (target === null) {
				html += '<div id="' + id + '" style="display: none;">';
				html += '<input type="hidden" name="postcode-' + id + '" />';
				html += '<input type="hidden" name="prefecture-' + id + '" />';
				html += '<input type="hidden" name="address-' + id + '" />';
				html += '<input type="hidden" name="street-' + id + '" />';
				html += '</div>';
				// 一時追加
				jQuery('body').append(html);
			} else {
				html += '<div id="'
						+ id
						+ '" style="width: 0px; height: 0px; overflow: hidden; float: left;">';
				html += '<input type="text" name="postcode-' + id + '" />';
				html += '<input type="text" name="prefecture-' + id + '" />';
				html += '<input type="text" name="address-' + id + '" />';
				html += '<input type="text" name="street-' + id + '" />';
				html += '</div>';
				// 一時追加
				target.after(html);
			}
			//
			var div = jQuery('#' + id);
			var inputPostcde = jQuery('input:eq(0)', div);
			var inputPrefecture = jQuery('input:eq(1)', div);
			//
			// 郵便番号から住所取得
			inputPostcde.val(postcode);
			try {
				var A = AjaxZip3;
				A.zip2addr(inputPostcde.get(0), '', 'prefecture-' + id,
						'address-' + id, ' street-' + id);
			} catch (e) {
				alert(e);
			}
			//
			var count = 0;
			var recursive = function() {
				try {
					if (inputPrefecture.val() === '' && count < countMax) {
						// 都道府県に値なし + 確認回数が上限未満 -> 再帰呼び出し
						count++;
						setTimeout(function() {
							try {
								recursive();
							} catch (e) {
								jQuery.showErrorDetail(e,
										'getAddress recursive setTimeout');
							}
						}, interval);
						return;
					}
					//
					var address = null;
					if (type === 1) {
						address = new Array(jQuery('input:eq(1)', div).val(),
								jQuery('input:eq(2)', div).val(), jQuery(
										'input:eq(3)', div).val());
					} else {
						address = jQuery('input:eq(1)', div).val()
								+ jQuery('input:eq(2)', div).val()
								+ jQuery('input:eq(3)', div).val();
					}
					//
					if (inputPrefecture.val() !== ''
							&& typeof (parameter['function']) === 'function') {
						parameter['function'](address, parameter['argument']);
					}
					// 時間をおいてから処理 (IE エラー対応)
					setTimeout(
							function() {
								try {
									target.focus();
									div.remove();
									// 郵便番号検索用に呼び出された外部 JavaScript ファイル削除
									jQuery(
											'script[src^="http://ajaxzip3.googlecode.com/svn/trunk/ajaxzip3/zipdata/zip"]',
											jQuery('head')).remove();
								} catch (e) {
								}
							}, deleteTime);
				} catch (e) {
					jQuery.showErrorDetail(e, 'getAddress recursive');
				}
			};
			//
			recursive();
		} catch (e) {
			jQuery.showErrorDetail(e, 'getAddress');
		}
	};

	/**
	 * 郵便番号検索 (補助)
	 *
	 * @param {object}
	 *            postcode1 郵便番号 3 桁部分の jQuery オブジェクト
	 * @param {object}
	 *            postcode2 郵便番号 4 桁部分の jQuery オブジェクト
	 * @param {object}
	 *            address1 住所部分の jQuery オブジェクト
	 * @public
	 * @requires tag
	 * @requires jQuery.getJQuery
	 * @requires jQuery.isPostcode
	 * @requires jQuery.getAddress
	 * @requires jQuery.showErrorDetail
	 */
	jQuery.appendPostcodeSearch = function(postcode1, postcode2, address1) {
		try {
			var postcode = '';
			var callback = function(address, argument) {
				try {
					if (address1.attr('disabled') !== 'disabled') {
						address1.val(address).caretPosition('l');
					}
				} catch (e) {
					jQuery.showErrorDetail(e, 'appendSearchPostcode callback');
				}
			};
			//
			postcode1 = jQuery.getJQuery(postcode1);
			postcode2 = jQuery.getJQuery(postcode2);
			address1 = jQuery.getJQuery(address1);
			if (postcode1 === null || postcode2 === null || address1 === null) {
				return;
			}
			//
			// 郵便番号 3 桁
			postcode1
					.blur(function(event) {
						try {
							if (postcode1.val() !== ''
									&& postcode2.val() !== ''
									&& (postcode1.val() !== postcode1.tag() || postcode2
											.val() !== postcode2.tag())) {
								// 郵便番号 3 桁 か 4 桁 が空でなく前回値と異なる場合 -> 郵便番号検索
								postcode = postcode1.val() + postcode2.val();
								if (jQuery.isPostcode(postcode)) {
									// 郵便番号形式
									jQuery.getAddress(postcode, {
										'function' : callback
									}, address1);
								}
								// tag 保存
								postcode1.tag(postcode1.val());
								postcode2.tag(postcode2.val());
							}
						} catch (e) {
							jQuery.showErrorDetail(e,
									'appendPostcodeSearch postcode1 blur');
						}
					});
			//
			// 郵便番号 4 桁
			postcode2
					.blur(function(event) {
						try {
							if (postcode1.val() !== ''
									&& postcode2.val() !== ''
									&& (postcode1.val() !== postcode1.tag() || postcode2
											.val() !== postcode2.tag())) {
								// 郵便番号 3 桁 か 4 桁 が空でなく前回値と異なる場合 -> 郵便番号検索
								postcode = postcode1.val() + postcode2.val();
								if (jQuery.isPostcode(postcode)) {
									// 郵便番号形式
									jQuery.getAddress(postcode, {
										'function' : callback
									}, address1);
								}
								// tag 保存
								postcode1.tag(postcode1.val());
								postcode2.tag(postcode2.val());
							}
						} catch (e) {
							jQuery.showErrorDetail(e,
									'appendPostcodeSearch postcode2 blur');
						}
					});
		} catch (e) {
			jQuery.showErrorDetail(e, 'appendPostcodeSearch');
		}
	};

	/**
	 * 同じドメイン内のファイル存在確認
	 *
	 * @param {string}
	 *            url ファイルの絶対URL
	 * @param {object}
	 *            information ファイル情報 (参照渡) [デフォルト:null]
	 * @return {bool} true:存在 / false:非存在
	 * @public
	 * @requires _phpFile['fileExists'] で定義された PHP ファイル
	 * @requires jQuery.castString
	 * @requires jQuery.relativeToAbsolute
	 * @requires jQuery.getPath
	 * @requires jQuery.showErrorDetail
	 * @example if (!$.getFileExists('sample.pdf')) { alert('File does not
	 *          exists.'); }
	 */
	jQuery.getFileExists = function(url, information) {
		try {
			// 引数確認
			url = jQuery.castString(url);
			// url 解析
			var split = url.split('?');
			url = split[0];
			//
			// サーバーに対して Ajax 通信 (同期)
			var fileExists = false;
			new jQuery.ajax(
					{
						async : false,
						url : jQuery.getPath('php') + _phpFile['fileExists'],
						data : {
							'filePath' : url
						},
						type : 'post',
						dataType : 'text',
						timeout : 600000,
						cache : false,
						success : function(data, dataType) {
							try {
								try {
									// データ変換 (json -> hash)
									var dataSet = JSON.parse(data);
									//
									if ('status' in dataSet) {
										switch (dataSet['status']) {
										case 'true':
											fileExists = true;
											//
											if (jQuery
													.isPlainObject(information)) {
												if ('image' in dataSet) {
													information['image'] = jQuery
															.castNumber(
																	dataSet['image'],
																	false);
												}
												if ('width' in dataSet) {
													information['width'] = jQuery
															.castNumber(
																	dataSet['width'],
																	false);
												}
												if ('height' in dataSet) {
													information['height'] = jQuery
															.castNumber(
																	dataSet['height'],
																	false);
												}
											}
											break;
										case 'false':
											break;
										case 'error':
											if ('message' in dataSet) {
												alert('error\n'
														+ dataSet['message']);
											} else {
												alert('error');
											}
											break;
										default:
											alert('irregular reply ...\n'
													+ data);
											break;
										}
									}
								} catch (e) {
									fileExists = false;
									//
									if (data == null || data === '') {
										alert('no reply ...');
									} else {
										// json 以外
										alert('error\n' + e['message'] + '\n'
												+ data);
									}
								}
							} catch (e) {
								jQuery.showErrorDetail(e,
										'getFileExists ajax success');
							}
						},
						complete : function(XMLHttpRequest, textStatus) {
							try {
								if (textStatus !== 'success') {
									// エラー
									alert('Ajax error : status [' + textStatus
											+ ']');
								}
							} catch (e) {
								jQuery.showErrorDetail(e,
										'getFileExists ajax complete');
							}
						}
					});
			//
			return (fileExists);
		} catch (e) {
			jQuery.showErrorDetail(e, 'getFileExists');
			return (false);
		}
	};

	/**
	 * 環境依存文字削除
	 *
	 * @param {string}
	 *            target 確認対象文字列
	 * @param {object}
	 *            information 使用されている環境依存文字 (参照渡) [デフォルト:null]
	 * @return {string} 環境依存文字削除後の文字列
	 * @public
	 * @requires _phpFile['platform'] で定義された PHP ファイル
	 * @requires jQuery.castString
	 * @requires jQuery.getPath
	 * @requires jQuery.showErrorDetail
	 * @example //環境依存文字を含むかの確認 var str = '①TEST②SAMPLE'; var info = new
	 *          Object(); if (str !== $.platformCharacter(str, info)) { var txt =
	 *          ''; var key = null; for (key in info) { txt += info[key] + '\n'; }
	 *          alert(str + '\n環境依存文字が含まれています。\n----------\n' + txt); }
	 */
	jQuery.platformCharacter = function(target, information) {
		try {
			target = jQuery.castString(target);
			//
			var result = '';
			var flag = false;
			new jQuery.ajax(
					{
						async : false,
						url : jQuery.getPath('php') + _phpFile['platform'],
						data : {
							'target' : target
						},
						type : 'post',
						dataType : 'text',
						timeout : 600000,
						cache : false,
						success : function(data, dataType) {
							try {
								try {
									// データ変換 (json -> hash)
									var dataSet = JSON.parse(data);
									//
									if ('status' in dataSet) {
										switch (dataSet['status']) {
										case 'true':
											if ('replace' in dataSet) {
												// 環境依存文字置換
												result = dataSet['replace'];
												flag = true;
											}
											//
											if (jQuery
													.isPlainObject(information)) {
												if ('char' in dataSet) {
													if (jQuery
															.getType(dataSet['char']) === 'array') {
														var i = 0;
														var len = dataSet['char'].length;
														for (i = 0; i < len; i++) {
															information[i + ''] = dataSet['char'][i];
														}
													}
												}
											}
											break;
										case 'false':
											break;
										case 'error':
											if ('message' in dataSet) {
												alert('error\n'
														+ dataSet['message']);
											} else {
												alert('error');
											}
											break;
										default:
											alert('irregular reply ...\n'
													+ data);
											break;
										}
									}
								} catch (e) {
									if (data == null || data === '') {
										alert('no reply ...');
									} else {
										// json 以外
										alert('error\n' + e['message'] + '\n'
												+ data);
									}
								}
							} catch (e) {
								jQuery.showErrorDetail(e,
										'getFileExists ajax success');
							}
						},
						complete : function(XMLHttpRequest, textStatus) {
							try {
								if (textStatus !== 'success') {
									// エラー
									alert('Ajax error : status [' + textStatus
											+ ']');
								}
							} catch (e) {
								jQuery.showErrorDetail(e,
										'getFileExists ajax complete');
							}
						}
					});
			//
			return ((flag) ? result : target);
		} catch (e) {
			jQuery.showErrorDetail(e, 'getFileExists');
			return (target);
		}
	};

	// ----------+[ デバッグ関連 ]+----------
	/**
	 * 関数解析
	 *
	 * @param {function}
	 *            object 関数オブジェクト (arguments.callee)
	 * @return {hash} {name : 関数名 (関数変数の場合はfunction value), arg: 引数群 (カンマ区切り)}
	 * @public
	 * @requires jQuery.showErrorDetail
	 * @example var object = $.analyzeFunction(arguments.callee);
	 */
	jQuery.analyzeFunction = function(object) {
		try {
			if (typeof (object) !== 'function') {
				// 引数が関数以外
				return ({
					name : '',
					arg : ''
				});
			}
			//
			var functionName = '';
			var argumentName = '';
			//
			functionName = object.toString().split('(');
			if (functionName.length > 1) {
				functionName = functionName[0];
				functionName = functionName.replace(/function/g, '').replace(
						/\s/g, '');
				if (functionName === '') {
					functionName = 'anonymous function';
				}
			} else {
				functionName = '';
			}
			//
			argumentName = object.toString().split('(');
			if (argumentName.length > 2) {
				argumentName = argumentName[1].split(')');
				if (argumentName.length > 1) {
					argumentName = argumentName[0].replace(/\s/g, '');
				} else {
					argumentName = '';
				}
			} else {
				argumentName = '';
			}
			//
			return ({
				name : functionName,
				arg : argumentName
			});
		} catch (e) {
			jQuery.showErrorDetail(e, 'analyzeFunction');
			return ({
				name : '',
				arg : ''
			});
		}
	};

	/**
	 * オブジェクトの展開
	 *
	 * @param {object}
	 *            object 展開したい変数
	 * @param {integer}
	 *            clear 削除フラグ 0:削除しない / 1:削除のみ / 2:削除後オブジェクトの展開 [デフォルト:0]
	 * @public
	 * @requires jQuery.castNumber
	 * @requires jQuery.showErrorDetail
	 * @requires _var_dump
	 * @example var object = {a : 'This is a.', b : 'This is b.'};
	 *          $.var_dump(object);
	 */
	jQuery.var_dump = function(object, clear) {
		try {
			var className = 'jquery-expansion-class-var-dump';
			clear = jQuery.castNumber(clear, false);
			//
			if (clear === 1 || clear === 2) {
				jQuery('.' + className).remove();
				if (clear === 1) {
					return;
				}
			}
			//
			var content = '';
			content += '<pre class="' + className + '">';
			content += '<div style="font-size: small; font-family: monospace;">';
			content += _var_dump(object, 0);
			content += '</div>';
			content += '</pre>';
			//
			jQuery('body').append(content);
		} catch (e) {
			jQuery.showErrorDetail(e, 'var_dump');
		}
	};

	/**
	 * オブジェクトの展開(サブ関数)
	 *
	 * @param {object}
	 *            object 展開したいオブジェクト
	 * @param {integer}
	 *            depth 配列の階層 [デフォルト:0]
	 * @param {bool}
	 *            indent 字下げ有無 true:有り / false:無し [デフォルト:true]
	 * @return {string} 展開内容
	 * @private
	 * @requires jQuery.analyzeFunction
	 * @requires jQuery.castHtml
	 * @requires jQuery.showErrorDetail
	 * @example var html = _var_dump(object, 0);
	 */
	function _var_dump(object, depth, indent) {
		try {
			if (depth > 9) {
				// 深度10以上は非表示
				return ('<font color="#ff0000"> and more ...</font><br />');
			}
			var space = '';
			var arraySpace = '';
			var i = 0;
			//
			// 階層判定
			if (indent !== false) {
				for (i = 0; i < depth; i++) {
					space += '&nbsp;&nbsp;';
				}
			}
			for (i = 0; i < depth + 1; i++) {
				arraySpace += '&nbsp;&nbsp;';
			}
			//
			var content = '';
			var key = null;
			var len = 0;
			switch (jQuery.getType(object)) {
			case 'undefined':
				content += space
						+ '<font color="#f57900">[ undefined ]</font><br />';
				break;
			case 'null':
				content += space + '<font color="#3465a4">null</font><br />';
				break;
			case 'function':
				// 関数内容文字列化
				var tmpFunction = jQuery.analyzeFunction(object);
				content += space
						+ '<font color="#f57900">[function]</font><br />';
				content += arraySpace
						+ ' <font color="#3465a4">name    </font> <font color="#888a85">=&gt;</font> '
						+ jQuery.castHtml(tmpFunction['name']) + '<br / >';
				content += arraySpace
						+ ' <font color="#3465a4">argument</font> <font color="#888a85">=&gt;</font> '
						+ jQuery.castHtml(tmpFunction['arg']) + '<br / >';
				break;
			case 'string':
				content += space
						+ '<small>string</small> <font color="#cc0000">\''
						+ jQuery.castHtml(object) + '\'</font> <i>(length = '
						+ object.length + ')</i><br />';
				break;
			case 'number':
				content += space
						+ '<small>number</small> <font color="#4e9a06">'
						+ jQuery.castHtml(object) + '</font><br />';
				break;
			case 'NaN':
				content += space
						+ '<font color="#f57900">[ NaN (Not a Number) ]</font><br />';
				break;
			case 'Infinity':
				content += space
						+ '<font color="#f57900">[ Infinity ]</font><br />';
				break;
			case 'boolean':
				content += space
						+ '<small>boolean</small> <font color="#75507b">'
						+ ((object === true) ? 'true' : 'false')
						+ '</font><br />';
				break;
			case 'array':
				depth++;
				content += space + '<b>array</b><br />';
				if (object.length === 0) {
					content += arraySpace
							+ '<font color="#888a85">empty</font><br />';
				} else {
					len = object.length;
					for (i = 0; i < len; i++) {
						content += arraySpace + i
								+ ' <font color="#888a85">=&gt;</font> '
								+ _var_dump(object[i], depth, false);
					}
				}
				break;
			case 'object':
				content += space
						+ '<font color="#f57900">[ object ]</font> <font color="#888a85">empty</font><br />';
				break;
			case 'hash':
				depth++;
				content += space + '<b>hash</b><br />';
				for (key in object) {
					content += arraySpace + '\'' + jQuery.castHtml(key)
							+ '\' <font color="#888a85">=&gt;</font> '
							+ _var_dump(object[key], depth, false);
				}
				break;
			case 'regexp':
				content += space
						+ '<font color="#f57900">[ RegExp ]</font><br />';
				content += arraySpace
						+ ' <font color="#3465a4">source</font> <font color="#888a85">=&gt;</font> /'
						+ object.source + '/<br / >';
				break;
			case 'date':
				var date = object.getFullYear() + '/'
						+ ((object.getMonth() + 1 < 10) ? '0' : '')
						+ (object.getMonth() + 1) + '/'
						+ ((object.getDate() < 10) ? '0' : '')
						+ object.getDate();
				var time = ((object.getHours() < 10) ? '0' : '')
						+ (object.getHours()) + ':'
						+ ((object.getMinutes() < 10) ? '0' : '')
						+ (object.getMinutes()) + ':'
						+ ((object.getSeconds() < 10) ? '0' : '')
						+ object.getSeconds();
				content += space
						+ '<font color="#f57900">[ Date ]</font><br />';
				content += arraySpace
						+ ' <font color="#3465a4">Date</font> <font color="#888a85">=&gt;</font> '
						+ date + '<br / >';
				content += arraySpace
						+ ' <font color="#3465a4">Time</font> <font color="#888a85">=&gt;</font> '
						+ time + '<br / >';
				break;
			case 'error':
				depth++;
				content += space + '<b>error</b><br />';
				for (key in object) {
					content += arraySpace + '\'' + jQuery.castHtml(key)
							+ '\' <font color="#888a85">=&gt;</font> '
							+ _var_dump(object[key], depth, false);
				}
				break;
			case 'dom':
				content += space + '<font color="#f57900">[ DOM ]</font><br />';
				break;
			case 'jquery':
				content += space
						+ '<font color="#f57900">[ jQuery ]</font><br />';
				content += arraySpace
						+ ' <font color="#3465a4">size</font> <font color="#888a85">=&gt;</font> '
						+ object.size() + '<br / >';
				content += (object.size() === 1) ? arraySpace
						+ ' <font color="#3465a4">tag </font> <font color="#888a85">=&gt;</font> '
						+ object.get(0).tagName.toLowerCase() + '<br / >'
						: '';
				break;
			case 'XMLHttpRequest':
				content += space
						+ '<font color="#f57900">[ XMLHttpRequest ]</font><br />';
				break;
			default:
				content += space + '<font color="#f57900">[???]</font><br />';
				break;
			}
			//
			return (content);
		} catch (e) {
			jQuery.showErrorDetail(e, '_var_dump');
			return ('');
		}
	}

	/**
	 * デバッグ文字列表示
	 *
	 * @param {string}
	 *            log 表示文字列 null:クリア / 未指定:コンソール削除
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example $.console_log('log contents');
	 */
	jQuery.console_log = function(log) {
		try {
			var className = 'jquery-expansion-class-console-log';
			var widthRate = 0.95;
			var heightRate = 0.20;
			var windowResize = function() {
				try {
					var width = parseInt(jQuery(window).width() * widthRate, 10);
					var height = parseInt(jQuery(window).height() * heightRate,
							10);
					jQuery('.' + className).width(width).height(height);
				} catch (e) {
					jQuery.showErrorDetail(e, 'console_log windowResize');
				}
			};
			var windowScroll = function() {
				try {
					var scroll = 0 - parseInt(jQuery(window).scrollTop(), 10);
					jQuery('.' + className).css({
						'bottom' : scroll + 'px'
					});
				} catch (e) {
					jQuery.showErrorDetail(e, 'console_log windowResize');
				}
			};
			//
			if (log === undefined) {
				// 引数なし -> 削除
				jQuery('.' + className).remove();
				jQuery(window).unbind('resize', windowResize).unbind('scroll',
						windowScroll);
				return;
			}
			//
			var object = jQuery.getJQuery('.' + className, false);
			//
			// 初期設定
			if (object === null) {
				var width = parseInt(jQuery(window).width() * widthRate, 10);
				var height = parseInt(jQuery(window).height() * heightRate, 10);
				var scroll = 0 - parseInt(jQuery(window).scrollTop(), 10);
				var html = '';
				html += '<textarea class="' + className
						+ '" readonly="readonly" ';
				html += 'style="resize: none; position: absolute; left: 0px; bottom: '
						+ scroll + 'px; ';
				html += 'width: ' + width + 'px; ';
				html += 'height: ' + height + 'px;';
				html += '">';
				html += '</textarea>';
				jQuery('body').append(html);
				jQuery('.' + className + ':last').bind(
						'contextmenu',
						function(event) {
							try {
								jQuery(this).remove();
								return (false);
							} catch (e) {
								jQuery.showErrorDetail(e,
										'console_log contextmenu');
								return (false);
							}
						}).keypress(function(event) {
					try {
						if (event.keyCode === 27) {
							jQuery(this).remove();
							return (false);
						}
					} catch (e) {
						jQuery.showErrorDetail(e, 'console_log keypress');
						return (false);
					}
				});
				jQuery(window).bind('resize', windowResize).bind('scroll',
						windowScroll);
			}
			//
			object = jQuery('.' + className);
			if (typeof (log) === 'undefined') {
				object.remove();
			} else if (log === null) {
				// 引数 = null -> クリア
				object.val('');
			} else {
				object.val(jQuery.castString(log) + '\r\n' + object.val());
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'console_log');
		}
	};

	/**
	 * 未実装機能呼び出し
	 *
	 * @public
	 * @requires jQuery.msgBox
	 * @example $.maintenance();
	 */
	jQuery.maintenance = function() {
		try {
			var param = new Object();
			param['caption'] = 'お詫び';
			param['icon'] = 'maintenance';
			jQuery.msgBox('今後、リリース予定の機能です。\n完成までもうしばらくお待ちください。', param);
		} catch (e) {
			jQuery.showErrorDetail(e, 'maintenance');
		}
	};

	// ----------+[ エラー関連 ]+----------
	/**
	 * 強制エラー発生
	 *
	 * @param {string}
	 *            message エラーメッセージ
	 * @public
	 * @requires jQuery.mbTrim
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example $.throwException('error');
	 */
	jQuery.throwException = function(message) {
		try {
			message = jQuery.castString(message);
			if (jQuery.mbTrim(message) === '') {
				return;
			}
			//
			var error = new Error();
			error['message'] = message;
			throw error;
		} catch (e) {
			jQuery.showErrorDetail(e, '__throwException', true);
		}
	};

	/**
	 * エラー内容詳細表示
	 *
	 * @param {object}
	 *            errorObject エラーオブジェクト
	 * @param {mixed}
	 *            functionObject 関数オブジェクト (arguments.callee) / 文字列の場合はそのまま表示
	 * @param {bool}
	 *            flag true:意図した Exception
	 * @public
	 * @requires jQuery.analyzeFunction
	 * @requires jQuery.castString
	 * @requires jQuery.writeErrorLog
	 * @example function sample() { try { //Code } catch(e) {
	 *          jQuery.showErrorDetail(e, 'sample'); } }
	 */
	jQuery.showErrorDetail = function(errorObject, functionObject, flag) {
		try {
			var functionName = '';
			var tmpFunction = null;
			if (typeof (functionObject) === 'function') {
				tmpFunction = jQuery.analyzeFunction(functionObject);
				functionName += 'function : ' + tmpFunction['name'] + '\n';
				functionName += 'argument : ' + tmpFunction['arg'] + '\n\n';
			} else {
				tmpFunction = jQuery.castString(functionObject);
				if (tmpFunction !== '' && tmpFunction !== '__throwException') {
					functionName = 'function : ' + tmpFunction + '\n';
				} else {
					// 呼び出し元関数解析
					if (typeof (arguments.callee.caller) === 'function') {
						var parentFunction = arguments.callee.caller;
						while (parentFunction) {
							tmpFunction = jQuery
									.analyzeFunction(parentFunction);
							if (tmpFunction['name'] === ''
									|| tmpFunction['name'] === 'anonymous function') {
								// 無名関数 -> 更に親を解析
								parentFunction = parentFunction.caller;
							} else {
								functionName = 'function : '
										+ tmpFunction['name'] + '\n';
								break;
							}
						}
						if (functionName === '') {
							functionName = 'function : (anonymous function)\n';
						}
					}
				}
			}
			//
			var fileName = '';
			if ('fileName' in errorObject) {
				fileName = 'file : ' + errorObject['fileName'].split('?')[0]
						+ '\n';
			}
			var url = location.href;
			url = url.split('?');
			url = url[0];
			//
			var text = '';
			if (flag === true) {
				text += '----------+[ THROW EXCEPTION ]+----------\n';
			} else {
				text += '----------+[ ERROR ]+----------\n';
			}
			text += 'User Agent : ' + navigator.userAgent + '\n';
			text += 'Browser : ' + navigator.appName + '\n';
			text += 'Platform : ' + navigator.platform + '\n';
			text += 'Location : ' + url + '\n\n';
			text += functionName;
			text += fileName;
			text += ('lineNumber' in errorObject) ? 'line : '
					+ errorObject['lineNumber'] + '\n' : '';
			text += ('name' in errorObject) ? 'type : ' + errorObject['name']
					+ '\n' : '';
			text += ('message' in errorObject) ? 'message : '
					+ errorObject['message'] + '\n' : '';
			// サーバーへエラー内容送信
			jQuery.writeErrorLog(text);
			// エラー表示
			alert(text);
		} catch (e) {
			alert('回復不可能なエラーが発生しました\n' + e['message']);
		}
	};


	// ----------+[ jQuery 拡張機能 ]+----------
	/**
	 * :visible / :hidden の機能拡張
	 *
	 * @public
	 * @example var obj = $('#id:_visible');
	 */
	jQuery.extend(jQuery.expr[':'], {
		_visible : function(elemnt) {
			if (jQuery.expr[':'].hidden(elemnt)) {
				return (false);
			}
			if (jQuery.css(elemnt, 'visibility') === 'hidden') {
				return (false);
			} else if (jQuery.css(elemnt, 'visibility') === 'collapse') {
				return (false);
			}
			return true;
		},
		_hidden : function(elemnt) {
			if (jQuery.expr[':'].hidden(elemnt)) {
				return (true);
			}
			if (jQuery.css(elemnt, 'visibility') === 'hidden') {
				return (true);
			} else if (jQuery.css(elemnt, 'visibility') === 'collapse') {
				return (false);
			}
			return (false);
		}
	});

	/**
	 * jQuery拡張 TAG 機能
	 *
	 * @param {object}
	 *            object object:SET / 指定なし:GET / null:クリア
	 * @return {object} null (jQuery のメソッドチェーン不可)
	 * @public
	 * @requires jQuery.showErrorDetail
	 * @example var object = $('#sample').tag();
	 */
	jQuery.fn.tag = function(object) {
		try {
			if (typeof (object) === 'undefined') {
				// GET
				return (jQuery(this).data('extendFunctionTag'));
			} else if (object === null) {
				// CLER
				jQuery(this).removeData('extendFunctionTag');
			} else {
				// SET
				jQuery(this).data('extendFunctionTag', object);
			}
			return (null);
		} catch (e) {
			jQuery.showErrorDetail(e, 'extend tag');
			return (null);
		}
	};

	/**
	 * jQuery拡張 フォーカス機能
	 *
	 * @param {string}
	 *            style スタイル名 [デフォルト:textbox-focus]
	 * @return {object} 自身の jQuery オブジェクト
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example $('input').focusStyle('textbox-focus');
	 */
	jQuery.fn.focusStyle = function(style) {
		try {
			return (this.each(function(index, dom) {
				try {
					style = jQuery.castString(style);
					if (style === '') {
						style = 'textbox-focus';
					}
					//
					jQuery(this).focusin(function(event) {
						try {
							jQuery(this).addClass(style);
						} catch (e) {
							jQuery.showErrorDetail(e, 'focusStyle focusin');
						}
					}).focusout(function(event) {
						try {
							jQuery(this).removeClass(style);
						} catch (e) {
							jQuery.showErrorDetail(e, 'focusStyle focusout');
						}
					});
				} catch (e) {
					jQuery.showErrorDetail(e, 'focusStyle');
					return (false);
				}
			}));
		} catch (e) {
			jQuery.showErrorDetail(e, 'focusStyle');
			return (this.each(function(index, dom) {
			}));
		}
	};

	/**
	 * jQuery拡張 利用可否変更機能
	 *
	 * @param {bool}
	 *            flag 利用可否 true:利用可能 / false:利用不可 [デフォルト:true]
	 * @param {string}
	 *            style スタイル名 [デフォルト:textbox-disabled]
	 * @param {bool}
	 *            clear 内容削除 true:削除 / false:そのまま [デフォルト:false]
	 * @return {object} 自身の jQuery オブジェクト
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example $('input').enabledChange(true, 'textbox-disabled');
	 */
	jQuery.fn.enabledChange = function(flag, style, clear) {
		try {
			var tag = '';
			var type = '';
			var panel = null;
			return (this
					.each(function(index, dom) {
						try {
							type = jQuery.castString(jQuery(this).attr('type'))
									.toLowerCase();
							//
							style = jQuery.castString(style);
							if (style === '') {
								style = 'textbox-disabled';
							}
							//
							if (flag === false) {
								// 利用不可
								jQuery(this).blur().tag(null);
								if (type === 'checkbox' || type === 'radio') {
									panel = jQuery(this).parent('.panel');
									panel.addClass(style);
									jQuery('input', panel).attr('disabled',
											'disabled');
								} else {
									jQuery(this).addClass(style).attr(
											'disabled', 'disabled');
								}
								//
								if (clear === true) {
									tag = jQuery
											.castString(jQuery(this).get(0).tagName);
									if (tag === 'SELECT') {
										jQuery('> option:eq(0)', jQuery(this))
												.attr('selected', 'selected');
									} else {
										jQuery(this).val('');
									}
								}
							} else {
								// 利用可能
								if (type === 'checkbox' || type === 'radio') {
									panel = jQuery(this).parent('.panel');
									panel.removeClass(style);
									jQuery('input', panel).removeAttr(
											'disabled');
								} else {
									jQuery(this).removeClass(style).removeAttr(
											'disabled');
								}
							}
						} catch (e) {
							jQuery.showErrorDetail(e, 'enabledChange each');
							return (false);
						}
					}));
		} catch (e) {
			jQuery.showErrorDetail(e, 'enabledChange');
			return (this.each(function(index, dom) {
			}));
		}
	};

	/**
	 * jQuery拡張 エラースタイル
	 *
	 * @param {string}
	 *            message エラーメッセージ
	 * @param {string}
	 *            style スタイル名 [デフォルト:textbox-error]
	 * @return {object} 自身の jQuery オブジェクト
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example $('input').errorStyle('エラーがあります', 'textbox-error');
	 */

	jQuery.fn.errorStyle = function(message, style) {
		try {
			if(typeof message == 'undefined')
				message = 'メッセージは存在しません。システム管理者に連絡してください。';
			return (this.each(function(index, dom) {
				try {
					message = jQuery.castString(message);
					if (message !== '') {
						style = jQuery.castString(style);
						if (style === '') {
							style = 'error-item';
						}
						//
						if (!jQuery(this).hasClass(style)) {
							// エラースタイルを持っていない場合
							jQuery(this).addClass(style);
							if(style == 'textbox-error'){
								jQuery(this).balloontip(message);
							}else{
								jQuery(this).balloontip(message, 'focus');
							}
						}
					}
					var element = '.'+ style + ':first';
					$(document).find(element).focus();
				} catch (e) {
					jQuery.showErrorDetail(e, 'errorStyle each');
					return (false);
				}
			}));
		} catch (e) {
			jQuery.showErrorDetail(e, 'errorStyle');
			return (this.each(function(index, dom) {
			}));
		}
	};

	/**
	 * jQuery拡張 エラースタイル削除
	 *
	 * @param {string}
	 *            style スタイル名 [デフォルト:textbox-error]
	 * @return {object} 自身の jQuery オブジェクト
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example $('input').removeErrorStyle('textbox-error');
	 */
	jQuery.fn.removeErrorStyle = function(style) {
		try {
			return (this.each(function(index, dom) {
				try {
					style = jQuery.castString(style);
					if (style === '') {
						style = 'textbox-error';
					}
					//
					jQuery(this).removeClass(style).removeBalloontip();
				} catch (e) {
					jQuery.showErrorDetail(e, 'removeErrorStyle each');
					return (false);
				}
			}));
		} catch (e) {
			jQuery.showErrorDetail(e, 'removeErrorStyle');
			return (this.each(function(index, dom) {
			}));
		}
	};

	// ----------▽[ アウタークリック ]▽----------
	/**
	 * jQuery拡張 アウタークリック用
	 *
	 * @param {array}
	 *            _outerClickTarget outerClick 対象要素格納用
	 * @private
	 */
	var _outerClickTarget = new Array();

	// 自分以外の要素か確認
	/**
	 * jQuery拡張 アウタークリック用:要素判定
	 *
	 * @param {object}
	 *            event イベント
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example
	 */
	function _outerClickElementCheck(event) {
		try {
			var i = 0;
			var len = _outerClickTarget.length;
			var target = event.target;
			var elm = null;
			// 全要素確認
			for (i = 0; i < len; i++) {
				elm = _outerClickTarget[i];
				// チェック対象要素が outerClick 対象以外で且つ outerClick 対象の子要素以外
				if (elm !== target
						&& !(elm.contains ? elm.contains(target)
								: (elm.compareDocumentPosition ? (elm
										.compareDocumentPosition(target) & DOCUMENT_POSITION_CONTAINED_BY)
										: 1))) {
					// イベント発生
					jQuery.event.trigger('outerClick', event, elm);
				}
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'outerClickElementCheck');
		}
	}

	/**
	 * jQuery拡張 アウタークリック用:イベント追加 / 削除
	 *
	 * @private
	 * @requires jQuery.showErrorDetail
	 */
	jQuery.event.special.outerClick = {
		setup : function() {
			try {
				var i = _outerClickTarget.length;
				if (i === 0) {
					// 複数にイベントを付ける場合は最初のみ document に click イベント付加
					jQuery.event
							.add(document, 'click', _outerClickElementCheck);
				}
				// 自分自身を _outerClickTarget に記憶
				if (jQuery.inArray(this, _outerClickTarget) < 0) {
					_outerClickTarget[i] = this;
				}
			} catch (e) {
				jQuery.showErrorDetail(e, 'outerClick setup');
			}
		},
		teardown : function() {
			try {
				var i = jQuery.inArray(this, _outerClickTarget);
				if (i > -1) {
					// unbind した要素を _outerClickTarget から削除
					_outerClickTarget.splice(i, 1);
					// _outerClickTarget から全ての要素を削除 -> document の click イベント削除
					if (_outerClickTarget.length === 0) {
						jQuery(document).unbind('click',
								_outerClickElementCheck);
					}
				}
			} catch (e) {
				jQuery.showErrorDetail(e, 'outerClick teardown');
			}
		}
	};

	/**
	 * jQuery拡張 アウタークリック
	 *
	 * @param {function}
	 *            method イベント発生時実行関数 (null の場合はイベント発生)
	 * @return {object} 自身の jQuery オブジェクト
	 * @public
	 * @requires jQuery.showErrorDetail
	 * @example $('#sample').outerClick(function(event) { alert('outer click);
	 *          });
	 */
	jQuery.fn.outerClick = function(method) {
		try {
			return (method ? this.bind('outerClick', method) : this
					.trigger('outerClick'));
		} catch (e) {
			jQuery.showErrorDetail(e, 'outerClick');
		}
	};
	// ----------△[ アウタークリック ]△----------

	// ----------▽[ マウスホイール ]▽----------
	/**
	 * jQuery拡張 マウスホイール用
	 *
	 * @param {bool}
	 *            _mousewheelToFix ブラウザごとのホイールイベント名
	 * @private
	 */
	var _mousewheelToFix = [ 'wheel', 'mousewheel', 'DOMMouseScroll',
			'MozMousePixelScroll' ];

	/**
	 * jQuery拡張 マウスホイール用
	 *
	 * @param {array}
	 *            _mousewheelToBind ブラウザごとのホイールイベント
	 * @private
	 */
	var _mousewheelToBind = ('onwheel' in document || document.documentMode >= 9) ? [ 'wheel' ]
			: [ 'mousewheel', 'DomMouseScroll', 'MozMousePixelScroll' ];

	/**
	 * jQuery拡張 マウスホイール用
	 *
	 * @param {integer}
	 *            _mousewheelLowestDelta
	 * @private
	 */
	var _mousewheelLowestDelta = 0;

	/**
	 * jQuery拡張 マウスホイール用
	 *
	 * @param {integer}
	 *            _mousewheelLowestDeltaXY
	 * @private
	 */
	var _mousewheelLowestDeltaXY = 0;

	/**
	 * jQuery拡張 マウスホイール用:Event オブジェクトにプロパティに追加
	 *
	 * @private
	 */
	if (jQuery.event.fixHooks) {
		// fixHooks : jQuery 1.7 で導入
		var i = 0;
		var len = _mousewheelToFix.length;
		for (i = 0; i < len; i++) {
			jQuery.event.fixHooks[_mousewheelToFix[i]] = jQuery.event.mouseHooks;
		}
	}

	/**
	 * jQuery拡張 マウスホイール用:イベント追加 / 削除
	 *
	 * @private
	 * @requires jQuery.showErrorDetail
	 */
	jQuery.event.special.mousewheel = {
		setup : function() {
			try {
				if (this.addEventListener) {
					var i = 0;
					var len = _mousewheelToBind.length;
					for (i = 0; i < len; i++) {
						this.addEventListener(_mousewheelToBind[i],
								_mousewheelHandler, false);
					}
				} else {
					this.onmousewheel = _mousewheelHandler;
				}
			} catch (e) {
				// jQuery.showErrorDetail(e, 'outerClick');
			}
		},
		teardown : function() {
			try {
				if (this.removeEventListener) {
					var i = 0;
					var len = _mousewheelToBind.length;
					for (i = 0; i < len; i++) {
						this.removeEventListener(_mousewheelToBind[i],
								_mousewheelHandler, false);
					}
				} else {
					this.onmousewheel = null;
				}
			} catch (e) {
				// jQuery.showErrorDetail(e, 'outerClick');
			}
		}
	};

	/**
	 * jQuery拡張 マウスホイール
	 *
	 * @param {function}
	 *            method イベント発生時実行関数 (null の場合はイベント発生)
	 * @return {object} 自身の jQuery オブジェクト
	 * @public
	 * @requires jQuery.showErrorDetail
	 * @example $('#sample').mousewheel(function(event) { alert('mouse wheel');
	 *          });
	 */
	jQuery.fn.mousewheel = function(method) {
		try {
			return (method ? this.bind('mousewheel', method) : this
					.trigger('mousewheel'));
		} catch (e) {
			// jQuery.showErrorDetail(e, 'outerClick');
		}
	};

	/**
	 * jQuery拡張 マウスホイール
	 *
	 * @param {object}
	 *            event イベント
	 * @return {object} イベントハンドラ
	 * @private
	 * @requires jQuery.showErrorDetail
	 */
	function _mousewheelHandler(event) {
		var orgEvent = event || window.event;
		var args = [].slice.call(arguments, 1);
		var delta = 0;
		var deltaX = 0;
		var deltaY = 0;
		var absDelta = 0;
		var absDeltaXY = 0;
		var math = '';
		//
		event = jQuery.event.fix(orgEvent);
		event.type = 'mousewheel';
		//
		// Old school scrollwheel delta
		if (orgEvent.wheelDelta) {
			delta = orgEvent.wheelDelta;
		}
		if (orgEvent.detail) {
			delta = orgEvent.detail * -1;
		}
		//
		// New school wheel delta (wheel event)
		if (orgEvent.deltaY) {
			deltaY = orgEvent.deltaY * -1;
			delta = deltaY;
		}
		if (orgEvent.deltaX) {
			deltaX = orgEvent.deltaX;
			delta = deltaX * -1;
		}
		//
		// Webkit
		if (orgEvent.wheelDeltaY !== undefined) {
			deltaY = orgEvent.wheelDeltaY;
		}
		if (orgEvent.wheelDeltaX !== undefined) {
			deltaX = orgEvent.wheelDeltaX * -1;
		}
		//
		// Look for lowest delta to normalize the delta values
		absDelta = Math.abs(delta);
		if (!_mousewheelLowestDelta || absDelta < _mousewheelLowestDelta) {
			_mousewheelLowestDelta = absDelta;
		}
		absDeltaXY = Math.max(Math.abs(deltaY), Math.abs(deltaX));
		if (!_mousewheelLowestDeltaXY || absDeltaXY < _mousewheelLowestDeltaXY) {
			_mousewheelLowestDeltaXY = absDeltaXY;
		}
		//
		// Get a whole value for the deltas
		math = (delta > 0) ? 'floor' : 'ceil';
		delta = Math[math](delta / _mousewheelLowestDelta);
		deltaX = Math[math](deltaX / _mousewheelLowestDeltaXY);
		deltaY = Math[math](deltaY / _mousewheelLowestDeltaXY);
		//
		// Add event and delta to the front of the arguments
		args.unshift(event, delta, deltaX, deltaY);
		//
		return ((jQuery.event.dispatch || jQuery.event.handle)
				.apply(this, args));
	}
	// ----------△[ マウスホイール ]△----------

	// ----------▽[ ツールチップ ]▽----------
	/**
	 * jQuery拡張 ツールチップ用
	 *
	 * @param {integer}
	 *            _xOffset マウスからの x 軸距離
	 * @private
	 */
	var _xOffset = 10;

	/**
	 * jQuery拡張 ツールチップ用
	 *
	 * @param {integer}
	 *            _yOffset マウスからの y 軸距離
	 * @private
	 */
	var _yOffset = 0;

	/**
	 * jQuery拡張 ツールチップ用
	 *
	 * @param {string}
	 *            _balloontipId balloontip 本体のID名 (変更時は component.css も変更する)
	 * @private
	 */
	var _balloontipId = 'has-balloontip-class';

	/**
	 * jQuery拡張 ツールチップ用
	 *
	 * @param {string}
	 *            _balloontipId balloontip 内容記憶属性
	 * @private
	 */
	var _toottipAttr = 'has-balloontip-message';


	function _balloontipMousefocus(event, object, callback) {
		try {
			// 表示
			if (jQuery('#' + _balloontipId).size() > 0) {
				// 他で表示した balloontip があれば削除
				jQuery('#' + _balloontipId).remove();
			}
			var balloontipMessage = jQuery
					.castString(object.attr(_toottipAttr));
			if (balloontipMessage !== '') {
				// css強制指定 iframe 内のコンポーネントに対しても対処
				var parent = object.parent();
				//parent.css({'position' : 'relative'});
				var position  = object.position();
				jQuery('body').append(
						'<p id="' + _balloontipId + '"><span class="arrow"></span>' + balloontipMessage
						+ '</p>');

				var erroutHeight = jQuery('#' + _balloontipId).outerHeight();
				var errHeight = jQuery('#' + _balloontipId).height();
				var errlineHeight = jQuery('#' + _balloontipId).css('line-height');
				var errorgHeight = parseInt(errlineHeight)  + erroutHeight - errHeight;
				var widowWidth = $(window).width();
				var left = object.offset().left + event['target'].offsetWidth - 40;
				var ballmsgWidth =  jQuery('#' + _balloontipId).outerWidth();
				if(widowWidth < (left + ballmsgWidth)) {
					var right = widowWidth - object.offset().left - event['target'].offsetWidth;
					var css = {
						'top' : (object.offset().top-errorgHeight - 7)+ 'px',
						'right' : right + 'px',
						'position': 'absolute',
						'z-index' : '9999999999'
					};
					jQuery('#' + _balloontipId).find('span.arrow').removeClass('arrow').addClass('arrow-right');
				} else {
					var css = {
						'top' : (object.offset().top-errorgHeight - 7)+ 'px',
						'left' : left + 'px',
						'position': 'absolute',
						'z-index' : '9999999999'
					};
				}


				jQuery('#' + _balloontipId).css(css);

				if(callback){
					callback(jQuery('#' + _balloontipId));
				}
				jQuery('#' + _balloontipId).fadeIn(300, null);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'balloontipMouseover');
		}
	}


	/**
	 * jQuery拡張 ツールチップ用:マウスホバー
	 *
	 * @param {object}
	 *            event イベント
	 * @param {object}
	 *            object jQuery オブジェクト
	 * @private
	 * @requires jQuery.castString
	 * @requires _balloontipId
	 * @requires jQuery.showErrorDetail
	 * @example _balloontipMouseover(event, jQuery(this));
	 */

	function _balloontipMouseover(event, object, callback) {
		try {
			// 表示
			if (jQuery('#' + _balloontipId).size() > 0) {
				// 他で表示した balloontip があれば削除
				jQuery('#' + _balloontipId).remove();
			}
			var balloontipMessage = jQuery
					.castString(object.attr(_toottipAttr));
			if (balloontipMessage !== '') {
				// css強制指定 iframe 内のコンポーネントに対しても対処
				var parent = object.parent();
				//parent.css({'position' : 'relative'});
				var position  = object.position();
				/*parent.append(
						'<p id="' + _balloontipId + '"><span class="arrow"></span>' + balloontipMessage
								+ '</p>');*/

				//nguyen van bien 2014/10/30
				jQuery('body').append(
						'<p id="' + _balloontipId + '"><span class="arrow"></span>' + balloontipMessage
								+ '</p>');

				var erroutHeight = jQuery('#' + _balloontipId).outerHeight();
				var errHeight = jQuery('#' + _balloontipId).height();
				var errlineHeight = jQuery('#' + _balloontipId).css('line-height');
				var errorgHeight = parseInt(errlineHeight)  + erroutHeight - errHeight;

				/*var css = {
					'top' : (position.top - errorgHeight - 5) + 'px',
				};*/
				//nguyen van bien 2014/10/30
				var css = {
					'top' : (object.offset().top-errorgHeight-7)+ 'px',
					'left' : (parseInt(event['pageX'])-10)+ 'px',
					'position': 'absolute',
					'z-index' : '999'
				};

				jQuery('#' + _balloontipId).css(css);

				if(callback){
					callback(jQuery('#' + _balloontipId, parent));
				}
				jQuery('#' + _balloontipId, parent).fadeIn(300,
						null);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'balloontipMouseover');
		}
	}


	/**
	 * jQuery拡張 ツールチップ用:マウスホバー
	 *
	 * @param {object}
	 *            event イベント
	 * @param {object}
	 *            object jQuery オブジェクト
	 * @private
	 * @requires _balloontipId
	 * @requires jQuery.showErrorDetail
	 * @example _balloontipMouseout(event, jQuery(this));
	 */
	function _balloontipMouseout(event, object) {
		try {
			// 非表示 iframe 内のコンポーネントに対しても対処
			jQuery('#' + _balloontipId, object.parents('body')).remove();
		} catch (e) {
			jQuery.showErrorDetail(e, 'balloontipMouseout');
		}
	}

	/**
	 * jQuery拡張 ツールチップ用:マウスホバー
	 *
	 * @param {object}
	 *            event jQuery Event オブジェクト
	 * @param {object}
	 *            object jQuery オブジェクト
	 * @private
	 * @requires _yOffset
	 * @requires _xOffset
	 * @requires jQuery.showErrorDetail
	 * @example _balloontipMousemove(event, jQuery(this));
	 */
	function _balloontipMousemove(event, object, callback) {
		try {
			var parent = object.parent();

			var balloontip = jQuery('#' + _balloontipId, parent);
			var width = balloontip.outerWidth(true);
			var height = balloontip.outerHeight(true);
			var x = parseInt(event['pageX'], 10) + _xOffset;
			var y = parseInt(event['pageY'], 10) + _yOffset;
			var windowWidth = jQuery(window).width();
			var windowHeight = jQuery(window).height();
			var windowLeft = jQuery(window).scrollLeft();
			var windowTop = jQuery(window).scrollTop();
			var xOffset = 0;
			var yOffset = 0;
			if (x + width > windowWidth + windowLeft) {
				x = parseInt(windowWidth + windowLeft - width - _xOffset, 10);
				yOffset = -1 * height - 10;
			}
			if (y + height > windowHeight + windowTop) {
				y = parseInt(windowHeight + windowTop - height - _yOffset, 10);
			}
			//parent.css({'position' : 'relative'});
			// マウスムーブ -> balloontip をマウスに追従
			var objectOffset = object.offset();
			var position  = object.position();
			var css = {
				'left' : (x - objectOffset.left - 25 + position.left) + 'px',
			};
			// iframe 内のコンポーネントに対しても対処
			//jQuery('#' + _balloontipId, parent).css(css);
			//nguyen van bien 2014/10/30
			var erroutHeight = jQuery('#' + _balloontipId).outerHeight();
			var errHeight = jQuery('#' + _balloontipId).height();
			var errlineHeight = jQuery('#' + _balloontipId).css('line-height');
			var errorgHeight = parseInt(errlineHeight)  + erroutHeight - errHeight;
			var css = {
				'top' : (object.offset().top-errorgHeight-7)+ 'px',
				'left' : (parseInt(event['pageX'])-10)+ 'px',
				'position': 'absolute',
				'z-index' : '999'
			};

			jQuery('#' + _balloontipId).css(css);
			if(callback){
				callback(jQuery('#' + _balloontipId, parent));
			}

		} catch (e) {
			jQuery.showErrorDetail(e, 'balloontipMousemove');
		}
	}

	/**
	 * jQuery拡張 ツールチップ
	 *
	 * @param {string}
	 *            message メッセージ ※null の場合ツールチップ削除
	 * @return {object} 自身の jQuery オブジェクト
	 * @public
	 * @requires jQuery.castString
	 * @requires _toottipAttr
	 * @requires _balloontipMouseover
	 * @requires _balloontipMouseout
	 * @requires _balloontipMousemove
	 * @requires jQuery.showErrorDetail
	 * @example $('#sample').balloontip('ポップアップ表示');
	 */
	jQuery.fn.balloontip = function(message, option) {
		try {
			// message = jQuery.castHtml(message);
			// message = message.replace(/\r\n/g, '<br />').replace(/\r/g, '<br />').replace(/\n/g, '<br />');
			//
			return (this.each(function(index, dom) {
				try {
					if(option == 'focus'){
						jQuery(this).attr(_toottipAttr, message).focus(function(event) {
							if(jQuery(this).parent().find('i.fa').length > 0) {
								_balloontipMousefocus(event, jQuery(this), function(el){
									var left  = el.position().left + 17;
									el.css({'left' : left});
								});
							} else {
								_balloontipMousefocus(event, jQuery(this));
							}
						}).focusout(function(event) {
							_balloontipMouseout(event, jQuery(this));
						});

					} else {
						jQuery(this).attr(_toottipAttr, message).mouseover(function(event) {
							_balloontipMouseover(event, jQuery(this));
						}).mouseout(function(event) {
							_balloontipMouseout(event, jQuery(this));
						}).mousemove(function(event) {
							_balloontipMousemove(event, jQuery(this));
						});
					}

				} catch (e) {
					jQuery.showErrorDetail(e, 'balloontip each');
					return (false);
				}
			}));
		} catch (e) {
			jQuery.showErrorDetail(e, 'balloontip');
			return (this.each(function(index, dom) {
			}));
		}
	};


	/**
	 * jQuery拡張 ツールチップ削除
	 *
	 * @return {object} 自身の jQuery オブジェクト
	 * @public
	 * @requires _toottipAttr
	 * @requires _balloontipMouseover
	 * @requires _balloontipMouseout
	 * @requires _balloontipMousemove
	 * @requires _balloontipId
	 * @requires jQuery.showErrorDetail
	 * @example $('#sample').removeBalloontip();
	 */
	jQuery.fn.removeBalloontip = function() {
		try {
			return (this.each(function(index, dom) {
				try {
					jQuery(this).removeAttr(_toottipAttr).unbind('mouseover',
							_balloontipMouseover).unbind('mouseout',
							_balloontipMouseout).unbind('mousemove',
							_balloontipMousemove);
					jQuery('#' + _balloontipId).remove();
				} catch (e) {
					jQuery.showErrorDetail(e, 'removeBalloontip each');
					return (false);
				}
			}));
		} catch (e) {
			jQuery.showErrorDetail(e, 'removeBalloontip');
			return (this.each(function(index, dom) {
			}));
		}
	};
	// ----------△[ ツールチップ ]△----------

	/**
	 * ページャ作成
	 *
	 * @param {object}
	 *            target ページャの追加場所を特定する jQuery オブジェクト
	 * @param {integer}
	 *            pageMax 最大ページ数
	 * @param {integer}
	 *            displayPage 表示ページ数
	 * @param {hash}
	 *            option ユーザーオプション
	 * @param {integer}
	 *            option.message ページャの左に表示する文字列 [デフォルト:ページ：]
	 * @param {integer}
	 *            option.mainCount 現在表示ページを中心に何ページ分表示するか [デフォルト:3]
	 * @param {integer}
	 *            option.sideCount 最初と最後に何ページ表示するか [デフォルト:0]
	 * @param {bool}
	 *            option.prevNext 次と前へのリンク true:表示 / false:非表示 [デフォルト:true]
	 * @param {bool}
	 *            option.firstLast 最初と最後への直リンク true:表示 / false:非表示 [デフォルト:true]
	 * @param {string}
	 *            option.style ページボタンの基本スタイル [デフォルト:pager-style]
	 * @param {string}
	 *            option.link ページボタンのスタイル [デフォルト:pager-link]
	 * @param {string}
	 *            option.mouseOrver ページボタンにマウスオーバーしたときのスタイル
	 *            [デフォルト:pager-link-mouse-orver]
	 * @param {string}
	 *            option.selected 選択中のページボタンのスタイル [デフォルト:pager-link-selected]
	 * @param {string}
	 *            option.nonLink 選択できない部分のページボタンのスタイル [デフォルト:pager-link-non]
	 * @param {string}
	 *            option.displayMessage ページャの左に表示する文字列部分のスタイル
	 *            [デフォルト:pager-message]
	 * @param {function}
	 *            option.function ページボタンクリック時に実行する関数 [デフォルト:null]
	 *            (第一引数:移動先のページ番号, 第二引数:option.argument)
	 * @param {mixed}
	 *            option.argument クリック時に実行する関数への引数 [デフォルト:null]
	 * @return {object} 自身の jQuery オブジェクト
	 * @public
	 * @requires jQuery.getJQuery
	 * @requires jQuery.castNumber
	 * @requires jQuery.castString
	 * @requires jQuery.mbTrim
	 * @requires jQuery.showErrorDetail
	 * @example $.createPager( $('#pager'), 20, 1, { mainCount : 1, sideCount :
	 *          1, firstLast : true } );
	 */
	jQuery.fn.createPager = function(pageMax, displayPage, option) {
		try {
			pageMax = jQuery.castNumber(pageMax, false);
			if (pageMax < 1) {
				// pageMax がなければ何もしない
				return (this.each(function(index, dom) {
				}));
			}
			//
			var parameter = new Object();
			parameter['message'] = 'ページ：';
			parameter['mainCount'] = 3;
			parameter['sideCount'] = 0;
			parameter['prevNext'] = true;
			parameter['firstLast'] = true;
			parameter['style'] = 'pager-style';
			parameter['link'] = 'pager-link';
			parameter['mouseOrver'] = 'pager-link-mouse-orver';
			parameter['selected'] = 'pager-link-selected';
			parameter['nonLink'] = 'pager-link-non';
			parameter['displayMessage'] = 'pager-message';
			parameter['function'] = null;
			parameter['argument'] = null;
			//
			// ユーザーオプション取得
			var key = null;
			for (key in option) {
				if (key in parameter) {
					parameter[key] = option[key];
				}
			}
			//
			// 引数整形
			displayPage = jQuery.castNumber(displayPage, false);
			parameter['message'] = jQuery.castString(parameter['message']);
			parameter['mainCount'] = jQuery.castNumber(parameter['mainCount'],
					false);
			parameter['sideCount'] = jQuery.castNumber(parameter['sideCount'],
					false);
			if (parameter['prevNext'] !== false) {
				parameter['prevNext'] = true;
			}
			if (parameter['firstLast'] !== false) {
				parameter['firstLast'] = true;
			}
			parameter['link'] = jQuery.mbTrim(parameter['link']);
			parameter['mouseOrver'] = jQuery.mbTrim(parameter['mouseOrver']);
			parameter['selected'] = jQuery.mbTrim(parameter['selected']);
			parameter['nonLink'] = jQuery.mbTrim(parameter['nonLink']);
			//
			// 引数確認
			if (displayPage < 1) {
				displayPage = 1;
			} else if (displayPage > pageMax) {
				displayPage = pageMax;
			}
			if (parameter['mainCount'] < 1) {
				parameter['mainCount'] = 3;
			}
			if (parameter['sideCount'] < 0) {
				parameter['sideCount'] = 0;
			}
			if (parameter['style'] === '') {
				parameter['style'] = 'pager-style';
			}
			if (parameter['link'] === '') {
				parameter['link'] = 'pager-link';
			}
			if (parameter['mouseOrver'] === '') {
				parameter['mouseOrver'] = 'pager-link-mouse-orver';
			}
			if (parameter['selected'] === '') {
				parameter['selected'] = 'pager-link-selected';
			}
			if (parameter['nonLink'] === '') {
				parameter['nonLink'] = 'pager-link-non';
			}
			if (parameter['displayMessage'] === '') {
				parameter['displayMessage'] = 'pager-message';
			}
			//
			// ページャ作成準備
			var activePage = new Object(); // 表示ページ番号
			var i = 0;
			var len = 0;
			// 左サイド
			len = parameter['sideCount'] + 1;
			for (i = 1; i < len; i++) {
				if (i > pageMax) {
					activePage[pageMax] = pageMax;
				} else {
					activePage[i] = i;
				}
			}
			// 右サイド
			len = pageMax - parameter['sideCount'];
			for (i = pageMax; i > len; i--) {
				if (i < 1) {
					activePage[1] = 1;
				} else {
					activePage[i] = i;
				}
			}
			// メイン
			if (parameter['mainCount'] % 2 === 0) {
				// 偶数
				len = displayPage + parameter['mainCount'] / 2 + 1;
				for (i = (displayPage - (parameter['mainCount'] / 2 - 1)); i < len; i++) {
					if (i < 1) {
						activePage[1] = 1;
					} else if (i > pageMax) {
						activePage[pageMax] = pageMax;
					} else {
						activePage[i] = i;
					}
				}
			} else {
				// 奇数
				len = displayPage + (parameter['mainCount'] - 1) / 2 + 1;
				for (i = (displayPage - (parameter['mainCount'] - 1) / 2); i < len; i++) {
					if (i < 1) {
						activePage[1] = 1;
					} else if (i > pageMax) {
						activePage[pageMax] = pageMax;
					} else {
						activePage[i] = i;
					}
				}
			}
			//
			// ソート準備
			var tmp = new Array();
			for (key in activePage) {
				tmp[tmp.length] = {
					'key' : key,
					'value' : activePage[key]
				};
			}
			//
			// keyソート
			var sortByValue = function(a, b) {
				return ((a.value > b.value) ? 1 : -1);
			};
			tmp.sort(sortByValue);
			//
			// 返却用配列作成
			activePage = new Array(); // 初期化
			if (tmp[0]['value'] > 1) {
				activePage[activePage.length] = '...';
			}
			len = tmp.length;
			for (i = 0; i < len; i++) {
				activePage[activePage.length] = tmp[i]['value'];
				if (i + 1 < len) {
					// 次の数字との差が1以上 -> 補完文字挿入
					if (tmp[i + 1]['value'] - tmp[i]['value'] > 1) {
						activePage[activePage.length] = '...';
					}
				}
			}
			if (tmp[tmp.length - 1]['value'] < pageMax) {
				activePage[activePage.length] = '...';
			}
			//
			// HTML作成
			var html = '';
			if (parameter['message'] !== '') {
				html += '<span class="' + parameter['displayMessage'] + '">'
						+ parameter['message'] + '&nbsp;</span>';
			}
			if (parameter['firstLast'] === true) {
				if (displayPage === 1) {
					// 最初ページリンク無し
					html += '<span class="' + parameter['style'] + ' '
							+ parameter['nonLink'] + '">&lt;&lt;</span>';
				} else {
					// 最初ページリンク
					html += '<span class="' + parameter['style'] + ' '
							+ parameter['link']
							+ ' pager-first-move">&lt;&lt;</span>';
				}
			}
			if (parameter['prevNext'] === true) {
				if (displayPage === 1) {
					// 前ページリンク無し
					html += '<span class="' + parameter['style'] + ' '
							+ parameter['nonLink'] + '">&lt;</span>';
				} else {
					// 前ページリンク
					html += '<span class="' + parameter['style'] + ' '
							+ parameter['link']
							+ ' pager-previous-move">&lt;</span>';
				}
			}
			//
			len = activePage.length;
			for (i = 0; i < len; i++) {
				if (activePage[i] === displayPage) {
					// 現在表示ページ
					html += '<span class="' + parameter['style'] + ' '
							+ parameter['selected'] + '">' + activePage[i]
							+ '</span>';
				} else if (activePage[i] === '...') {
					// 補完表示部分
					html += '<span class="' + parameter['style'] + ' '
							+ parameter['nonLink'] + '">' + activePage[i]
							+ '</span>';
				} else {
					// リンク部分
					html += '<span class="' + parameter['style'] + ' '
							+ parameter['link'] + '">' + activePage[i]
							+ '</span>';
				}
			}
			//
			if (parameter['prevNext'] === true) {
				if (displayPage === pageMax) {
					// 次ページリンク無し
					html += '<span class="' + parameter['style'] + ' '
							+ parameter['nonLink'] + '">&gt;</span>';
				} else {
					// 次ページリンク
					html += '<span class="' + parameter['style'] + ' '
							+ parameter['link']
							+ ' pager-next-move">&gt;</span>';
				}
			}
			if (parameter['firstLast'] === true) {
				if (displayPage === pageMax) {
					// 最後ページリンク無し
					html += '<span class="' + parameter['style'] + ' '
							+ parameter['nonLink'] + '">&gt;&gt;</span>';
				} else {
					// 最後ページリンク
					html += '<span class="' + parameter['style'] + ' '
							+ parameter['link']
							+ ' pager-last-move">&gt;&gt;</span>';
				}
			}
			//
			return (this
					.each(function(index, dom) {
						try {
							// HTML反映
							jQuery(this).html(html);
							//
							// イベント追加
							jQuery('.' + parameter['link'], jQuery(this))
									.mouseover(
											function(event) {
												try {
													jQuery(this)
															.addClass(
																	parameter['mouseOrver']);
												} catch (e) {
													jQuery
															.showErrorDetail(e,
																	'createPager mouseover');
												}
											})
									.mouseout(
											function(event) {
												try {
													jQuery(this)
															.removeClass(
																	parameter['mouseOrver']);
												} catch (e) {
													jQuery
															.showErrorDetail(e,
																	'createPager mouseout');
												}
											})
									.click(
											function(event) {
												var jumpPage = 1;
												if (typeof (parameter['function']) === 'function') {
													if (jQuery(this).hasClass(
															'pager-first-move')) {
														// 最初ページクリック
														jumpPage = 1;
													} else if (jQuery(this)
															.hasClass(
																	'pager-previous-move')) {
														// 前ページクリック
														if (displayPage - 1 < 0) {
															jumpPage = 1;
														} else {
															jumpPage = displayPage - 1;
														}
													} else if (jQuery(this)
															.hasClass(
																	'pager-next-move')) {
														// 次ページクリック
														if (displayPage + 1 > pageMax) {
															jumpPage = pageMax;
														} else {
															jumpPage = displayPage + 1;
														}
													} else if (jQuery(this)
															.hasClass(
																	'pager-last-move')) {
														// 最後ページクリック
														jumpPage = pageMax;
													} else {
														jumpPage = jQuery
																.castNumber(
																		jQuery(
																				this)
																				.text(),
																		false);
														if (jumpPage < 1) {
															jumpPage = 1;
														} else if (jumpPage > pageMax) {
															jumpPage = pageMax;
														}
													}
													//
													if (jumpPage !== displayPage) {
														// ページ変更時 -> ユーザー定義関数実行
														parameter['function']
																(
																		jumpPage,
																		parameter['argument']);
													}
												}
											});
						} catch (e) {
							jQuery.showErrorDetail(e, 'createPager each');
							return (false);
						}
					}));
		} catch (e) {
			jQuery.showErrorDetail(e, 'createPager');
			return (this.each(function(index, dom) {
			}));
		}
	};

	/**
	 * 画面位置固定
	 *
	 * @param {object}
	 *            option オプション
	 * @param {integer}
	 *            option.top ブラウザの上からの距離 [デフォルト:0]
	 * @param {integer}
	 *            option.left ブラウザの左からの距離 [デフォルト:0]
	 * @param {bool}
	 *            option.topReverse true:下からの距離 / false:上からの距離 [デフォルト:false]
	 * @param {bool}
	 *            option.leftReverse true:左からの距離 / false:右からの距離 [デフォルト:false]
	 * @return {object} 自身の jQuery オブジェクト
	 * @public
	 * @requires jQuery.castNumber
	 * @requires jQuery.showErrorDetail
	 * @example $.floatingBox()
	 */
	jQuery.fn.floatingBox = function(option) {
		try {
			var parameter = jQuery.extend({
				top : 0,
				left : 0,
				topReverse : false,
				leftReverse : false
			}, option);
			//
			// 引数整形
			parameter['top'] = jQuery.castNumber(parameter['top'], false);
			parameter['left'] = jQuery.castNumber(parameter['left'], false);
			//
			// 画面スクロール時のイベント
			var windowScroll = function(event) {
				try {
					scrollTop = screen.scrollTop();
					scrollLeft = screen.scrollLeft();
					css = new Object();
					if (parameter['topReverse'] === true) {
						css['bottom'] = (parameter['top'] - scrollTop) + 'px';
					} else {
						css['top'] = (parameter['top'] + scrollTop) + 'px';
					}
					if (parameter['leftReverse'] === true) {
						css['right'] = (parameter['left'] - scrollLeft) + 'px';
					} else {
						css['left'] = (parameter['left'] + scrollLeft) + 'px';
					}
					event['data']['element'].css(css);
				} catch (e) {
					jQuery.showErrorDetail(e, 'floatingBox windowScroll');
				}
			};
			// 画面リサイズ時のイベント
			var windowResize = function(event) {
				try {
					scrollTop = screen.scrollTop();
					scrollLeft = screen.scrollLeft();
					css = new Object();
					if (parameter['topReverse'] === true) {
						css['bottom'] = (parameter['top'] + scrollTop) + 'px';
					}
					if (parameter['leftReverse'] === true) {
						css['right'] = (parameter['left'] + scrollLeft) + 'px';
					}
					event['data']['element'].css(css);
				} catch (e) {
					jQuery.showErrorDetail(e, 'floatingBox windowResize');
				}
			};
			//
			// window のイベントにバインド
			var screen = jQuery(window);
			var scrollTop = screen.scrollTop();
			var scrollLeft = screen.scrollLeft();
			var css = new Object();
			var object = null;
			return (this.each(function(index, dom) {
				try {
					// CSS 設定
					css = new Object();
					css['position'] = 'absolute';
					if (parameter['topReverse'] === true) {
						css['bottom'] = (parameter['top'] + scrollTop) + 'px';
					} else {
						css['top'] = (parameter['top'] + scrollTop) + 'px';
					}
					if (parameter['leftReverse'] === true) {
						css['right'] = (parameter['left'] + scrollLeft) + 'px';
					} else {
						css['left'] = (parameter['left'] + scrollLeft) + 'px';
					}
					//
					object = jQuery(this);
					object.css(css);
					//
					if (parameter['topReverse'] !== true
							|| parameter['leftReverse'] !== true) {
						// top / left の場合は画面のスクロールで追従
						screen.bind('scroll', {
							element : object
						}, windowScroll);
					}
					if (parameter['topReverse'] === true
							|| parameter['leftReverse'] === true) {
						// bottom / right の場合は画面のリサイズで追従
						screen.bind('resize', {
							element : object
						}, windowResize);
					}
				} catch (e) {
					jQuery.showErrorDetail(e, 'floatingBox each');
					return (false);
				}
			}));
		} catch (e) {
			jQuery.showErrorDetail(e, 'floatingBox');
			return (this.each(function(index, dom) {
			}));
		}
	};

	/**
	 * jQuery拡張 カウンター
	 *
	 * @param {hash}
	 *            options オプション
	 * @param {integer}
	 *            options.size ボタンのサイズ [デフォルト:8]
	 * @param {string}
	 *            options.color ボタンの色 [デフォルト:#9ca5b5]
	 * @param {string}
	 *            options.focus ボタンのフォーカス色 [デフォルト:#008cdd]
	 * @param {integer}
	 *            options.min 最小値 (null:無制限) [デフォルト:null]
	 * @param {integer}
	 *            options.max 最大値 (null:無制限) [デフォルト:null]
	 * @param {integer}
	 *            options.interval 増加減数 [デフォルト:1]
	 * @param {integer}
	 *            options.intervalTime マウスダウン時の増加減時間 (ミリ秒) [デフォルト:50]
	 * @return {object} 自身の jQuery オブジェクト
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.showErrorDetail
	 * @example $('.class').numericUpDown({ 'size' : 10, 'color' : '#cccccc',
	 *          'focus' : '#008000', 'min' : 500, 'max' : 0, 'interval' : 1,
	 *          'intervalTime': 100, });
	 */
	jQuery.fn.numericUpDown = function(option) {
		try {
			// オートインクリメント / デクリメントまでのインターバル
			var initialInterval = 500;
			//
			// オプション設定
			var parameter = jQuery.extend({
				'size' : 8,
				'color' : '#9ca5b5',
				'focus' : '#008cdd',
				'min' : null,
				'max' : null,
				'interval' : 1,
				'intervalTime' : 50
			}, option);
			//
			// 引数調整
			parameter['size'] = jQuery.castNumber(parameter['size'], false);
			if (parameter['size'] < 1) {
				parameter['size'] = 10;
			}
			parameter['color'] = jQuery.castString(parameter['color']);
			parameter['focus'] = jQuery.castString(parameter['focus']);
			parameter['click'] = jQuery.castString(parameter['click']);
			if (parameter['min'] !== null) {
				// 数値チェック
				if (!jQuery.isFloat(parameter['min'])) {
					parameter['min'] = null;
				} else {
					parameter['min'] = jQuery.castNumber(parameter['min'],
							false);
				}
			}
			if (parameter['max'] !== null) {
				// 数値チェック
				if (!jQuery.isFloat(parameter['min'])) {
					parameter['max'] = null;
				} else {
					parameter['max'] = jQuery.castNumber(parameter['max'],
							false);
				}
			}
			if (parameter['min'] !== null && parameter['max'] !== null
					&& parameter['min'] > parameter['max']) {
				// 大小チェック
				var tmp = parameter['min'];
				parameter['min'] = parameter['max'];
				parameter['max'] = tmp;
			}
			parameter['interval'] = jQuery.castNumber(parameter['interval'],
					false);
			parameter['intervalTime'] = jQuery.castNumber(
					parameter['intervalTime'], false);
			if (parameter['size'] < 1) {
				parameter['size'] = 250;
			}
			//
			// 共通 CSS 部分
			var halfSize = Math.floor(parameter['size'] / 2) + 1;
			var style = {
				'height' : '0px',
				'width' : '0px',
				'margin-left' : '2px',
				'border-width' : halfSize + 'px ' + parameter['size'] + 'px '
						+ halfSize + 'px ' + parameter['size'] + 'px',
				'border-color' : 'transparent',
				'border-style' : 'solid',
				'cursor' : 'pointer'
			};
			//
			var _numericUpDownIntervalId = null;
			var _numericUpDownTimeoutId = null;
			//
			return (this
					.each(function(index, dom) {
						try {
							var input = jQuery(this);
							//
							if (!input.is('input[type=text]')) {
								// input type=text 以外は除外
								return (true);
							}
							//
							input
									.wrap('<table class="numeric-up-down-box" style="display: inline;"><tbody><tr><td rowspan="2"></td></tr></tbody></table>');
							//
							var table = input.closest('table');
							input.addClass('numeric-up-down-input');
							jQuery('tr', table)
									.append(
											'<td><div class="numeric-up-down-up-button"   style="margin-bottom: 1px;"></div></td>');
							jQuery('tbody', table)
									.append(
											'<tr><td><div class="numeric-up-down-down-button" style="margin-top:    1px;"></div></td></tr>');
							//
							var up = jQuery('.numeric-up-down-up-button', table);
							var down = jQuery('.numeric-up-down-down-button',
									table);
							//
							// スタイル / イベント付加
							up.css(style).css('border-bottom-color',
									parameter['color']);
							down.css(style).css('border-top-color',
									parameter['color']);
							//
							// 上下限制限チェック
							function limitCheck(number) {
								try {
									if (parameter['min'] !== null
											&& number < parameter['min']) {
										number = parameter['min'];
									}
									if (parameter['max'] !== null
											&& number > parameter['max']) {
										number = parameter['max'];
									}
									return (number);
								} catch (e) {
									jQuery.showErrorDetail(e,
											'numericUpDown limitCheck');
									return (0);
								}
							}
							// カウントアップ
							function countUp() {
								try {
									var number = jQuery.castNumber(input.val(),
											false);
									number += parameter['interval'];
									number = limitCheck(number);
									//
									// change 発火
									input.val(number).change();
								} catch (e) {
									jQuery.showErrorDetail(e,
											'numericUpDown countUp');
									return (0);
								}
							}
							// カウントダウン
							function countDown() {
								try {
									var number = jQuery.castNumber(input.val(),
											false);
									number -= parameter['interval'];
									number = limitCheck(number);
									//
									// change 発火
									input.val(number).change();
								} catch (e) {
									jQuery.showErrorDetail(e,
											'numericUpDown countDown');
									return (0);
								}
							}
							// ボタンマウスオーバー
							function numericUpDownMouseOver(event) {
								try {
									var button = jQuery(event['target']);
									if (button
											.hasClass('numeric-up-down-up-button')) {
										// UP
										button.css('border-bottom-color',
												parameter['focus']);
									} else {
										// DOWN
										button.css('border-top-color',
												parameter['focus']);
									}
								} catch (e) {
									jQuery
											.showErrorDetail(e,
													'numericUpDown numericUpDownMouseOver');
								}
							}
							// ボタンマウスアウト
							function numericUpDownMouseOut(event) {
								try {
									var button = jQuery(event['target']);
									if (button
											.hasClass('numeric-up-down-up-button')) {
										// UP
										button.css('border-bottom-color',
												parameter['color']);
									} else {
										// DOWN
										button.css('border-top-color',
												parameter['color']);
									}
								} catch (e) {
									jQuery
											.showErrorDetail(e,
													'numericUpDown numericUpDownMouseOut');
								}
							}
							// ボタンからフォーカスが離れてもクリックし続ける処理
							jQuery.fn.numericUpDownMouseUp = function(event) {
								try {
									(function(jQuery) {
										jQuery(document)
												.one(
														'mouseup',
														function(event) {
															try {
																clearTimeout(_numericUpDownTimeoutId);
																try {
																	// setInterval
																	// 未実行の場合の対処
																	clearInterval(_numericUpDownIntervalId);
																} catch (e) {
																}
															} catch (e) {
																jQuery
																		.showErrorDetail(
																				e,
																				'numericUpDown numericUpDownMouseUp one');
																return (false);
															}
														});
									})(jQuery);
									return (false);
								} catch (e) {
									jQuery
											.showErrorDetail(e,
													'numericUpDown numericUpDownMouseUp');
									return (false);
								}
							};
							// UP ボタンイベント
							up
									.hover(numericUpDownMouseOver,
											numericUpDownMouseOut)
									.mousedown(
											function(event) {
												try {
													countUp();
													jQuery(this)
															.numericUpDownMouseUp();
													_numericUpDownTimeoutId = setTimeout(
															function() {
																_numericUpDownIntervalId = setInterval(
																		countUp,
																		parameter['intervalTime']);
															}, initialInterval);
												} catch (e) {
													jQuery
															.showErrorDetail(e,
																	'numericUpDown up mousedown');
													return (false);
												}
											});
							// DOWN ボタンイベント
							down
									.hover(numericUpDownMouseOver,
											numericUpDownMouseOut)
									.mousedown(
											function() {
												try {
													countDown();
													jQuery(this)
															.numericUpDownMouseUp();
													_numericUpDownTimeoutId = setTimeout(
															function() {
																_numericUpDownIntervalId = setInterval(
																		countDown,
																		parameter['intervalTime']);
															}, initialInterval);
												} catch (e) {
													jQuery
															.showErrorDetail(e,
																	'numericUpDown down mousedown');
													return (false);
												}
											});
							// INPUT イベント
							input.keydown(function(event) {
								try {
									if (event.keyCode === 38) {
										// UP
										countUp();
									} else if (event.keyCode === 40) {
										// DOWN
										countDown();
									}
								} catch (e) {
									jQuery.showErrorDetail(e,
											'numericUpDown input keydown');
									return (false);
								}
							});
						} catch (e) {
							jQuery.showErrorDetail(e, 'numericUpDown');
							return (false);
						}
					}));
		} catch (e) {
			jQuery.showErrorDetail(e, 'numericUpDown');
			return (this.each(function(index, dom) {
			}));
		}
	};

})(jQuery);

/*-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+*/

/**
 * jQuery Plugin [Format]
 *
 * @author Yusuke Kaneko
 * @version 2012.11.01.01
 * @requires jquery-X.X.X.js or jquery-X.X.X.min.js
 */
(function(jQuery) {
	/*
	 * ----------+[ 変換表 ]+---------- +------------+--------+--------+ | -対象-
	 * |-半角化-|-全角化-| +------------+--------+--------+ | 英字小文字 | a | ａ |
	 * +------------+--------+--------+ | 英字大文字 | A | Ａ |
	 * +------------+--------+--------+ | 数字 | 9 | ９ |
	 * +------------+--------+--------+ | 数字関連 | # | ＃ |
	 * +------------+--------+--------+ | 記号 | @ | ＠ |
	 * +------------+--------+--------+ | カタカナ | K | Ｋ |
	 * +------------+--------+--------+ | スペース | S | Ｓ |
	 * +------------+--------+--------+ | 全て | H | Ｚ |
	 * +------------+--------+--------+ | -対象- |-小文字-|-大文字-|
	 * +------------+--------+--------+ | 英字 | C | Ｃ |
	 * +------------+--------+--------+ | -対象- |-カナ化-|-かな化-|
	 * +------------+--------+--------+ | かな/カナ | W | Ｗ |
	 * +------------+--------+--------+ | -対象- | -削除- |
	 * +------------+-----------------+ | 指定以外 | - (ﾏｲﾅｽ記号) |
	 * +------------+-----------------+
	 */
	/**
	 * @param {string}
	 *            _deleteStack 削除対象確保用
	 * @private
	 */
	var _deleteStack = '';

	/**
	 * @param {integer}
	 *            _yearBoud 年フォーマット時の境界
	 * @private
	 */
	var _yearBoud = 50;

	/**
	 * jQuery拡張 フォーマット
	 *
	 * @param {string}
	 *            format フォーマット ※変換表参照
	 * @return {object} jQuery オブジェクト
	 * @public
	 * @requires _textFormatBlur
	 * @requires jQuery.showErrorDetail
	 * @example $('#sample').textFormat('aA9');
	 */
	jQuery.fn.textFormat = function(format) {
		try {
			// 入力系タグ抽出
			var input = this.find('input[type=text]').add(
					this.filter('input[type=text]')).add(this.find('textarea'))
					.add(this.filter('textarea'));
			//
			input.each(function(index, dom) {
				try {
					jQuery(this).unbind('blur', _textFormatBlur).bind('blur', {
						normalFormat : format
					}, _textFormatBlur);
				} catch (e) {
					jQuery.showErrorDetail(e, 'textFormat each');
				}
			});
			//
			return (this.each(function(index, dom) {
			}));
		} catch (e) {
			jQuery.showErrorDetail(e, 'textFormat');
			return (this.each(function(index, dom) {
			}));
		}
	};

	/**
	 * フォーマット用イベント (unbind 特定用)
	 *
	 * @param {object}
	 *            event イベント
	 * @private
	 * @requires jQuery.textFormat
	 * @requires jQuery.showErrorDetail
	 * @example jQuery(this).unbind('blur', _textFormatBlur)
	 */
	function _textFormatBlur(event) {
		try {
			var object = jQuery(event['target']);
			var string = jQuery.textFormat(object.val(),
					event['data']['normalFormat']);
			object.val(string);
		} catch (e) {
			jQuery.showErrorDetail(e, 'textFormatBlur');
		}
	}

	/**
	 * フォーマット
	 *
	 * @param {string}
	 *            target フォーマット対象文字列
	 * @param {string}
	 *            format フォーマット ※変換表参照
	 * @return {string} フォーマット後文字列
	 * @public
	 * @requires jQuery.textFormat
	 * @requires jQuery.showErrorDetail
	 * @example var string = $.textFormat('abcABC012ａｂｃＡＢＣ０１２', 'aA9');
	 */
	jQuery.textFormat = function(target, format) {
		try {
			target = jQuery.castString(target);
			format = jQuery.castString(format);
			//
			if (target === '') {
				return (target);
			}
			//
			var flag = false;
			if (format.indexOf('-', 0) !== -1) {
				// "-"あり -> 指定以外削除
				flag = true;
			}
			// 全対象置換
			format = format.replace(/H/g, 'aA9@SK').replace(/Ｚ/g, 'ａＡ９＃＠ＳＫ');
			// 不要項目削除
			format = format.replace(/[^aａAＡ9９#＃@＠SＳKＫCＣWＷ]/g, '');
			//
			// 解析
			var memory = new Array();
			var stack = new Array();
			var H = new Array('a', 'A', '9', '#', '@', 'S', 'K', 'C', 'W');
			var Z = new Array('ａ', 'Ａ', '９', '＃', '＠', 'Ｓ', 'Ｋ', 'Ｃ', 'Ｗ');
			var indexH = -1;
			var indexZ = -1;
			var i = 0;
			var len = H.length;
			for (i = 0; i < len; i++) {
				// パターンを後ろから検索
				indexH = format.lastIndexOf(H[i]);
				indexZ = format.lastIndexOf(Z[i]);
				//
				if (indexH !== indexZ) {
					// インデックスが大きい方が最終的な変換対象
					if (indexH > indexZ) {
						memory[indexH] = H[i];
					} else {
						memory[indexZ] = Z[i];
					}
				}
			}
			//
			// 抜け番を詰める
			len = memory.length;
			for (i = 0; i < len; i++) {
				if (typeof (memory[i]) !== 'undefined') {
					stack[stack.length] = memory[i];
				}
			}
			//
			// 削除対象初期化
			_deleteStack = '';
			//
			len = stack.length;
			if (len === 0) {
				// フォーマット対象なし -> そのまま返却
				return (target);
			}
			//
			// 各フォーマット実行
			for (i = 0; i < len; i++) {
				switch (stack[i]) {
				case 'a':
					target = _formatConvertAlphabetLower(target, 'h');
					break;
				case 'ａ':
					target = _formatConvertAlphabetLower(target, 'f');
					break;
				case 'A':
					target = _formatConvertAlphabetUpper(target, 'h');
					break;
				case 'Ａ':
					target = _formatConvertAlphabetUpper(target, 'f');
					break;
				case '9':
					target = _formatConvertNumberOnly(target, 'h');
					break;
				case '９':
					target = _formatConvertNumberOnly(target, 'f');
					break;
				case '#':
					target = _formatConvertNumber(target, 'h');
					break;
				case '＃':
					target = _formatConvertNumber(target, 'f');
					break;
				case '@':
					target = _formatConvertSymbol(target, 'h');
					break;
				case '＠':
					target = _formatConvertSymbol(target, 'f');
					break;
				case 'S':
					target = _formatConvertSpace(target, 'h');
					break;
				case 'Ｓ':
					target = _formatConvertSpace(target, 'f');
					break;
				case 'K':
					target = _formatConvertKatakana(target, 'h');
					break;
				case 'Ｋ':
					target = _formatConvertKatakana(target, 'f');
					break;
				case 'C':
					target = _formatConvertAlphabetCase(target, 'h');
					break;
				case 'Ｃ':
					target = _formatConvertAlphabetCase(target, 'f');
					break;
				case 'W':
					target = _formatConvertKana(target, 'h');
					break;
				case 'Ｗ':
					target = _formatConvertKana(target, 'f');
					break;
				default:
					break;
				}
			}
			//
			// 不要文字列削除
			if (flag) {
				var object = '[^' + _deleteStack + ']';
				var regularExpression = new RegExp(object, 'gm');
				target = target.replace(regularExpression, '');
			}
			//
			return (target);
		} catch (e) {
			jQuery.showErrorDetail(e, 'textFormat');
			return ('');
		}
	};

	/**
	 * フォーマット:英字小文字の全角半角変換
	 *
	 * @param {string}
	 *            target フォーマット対象の文字列
	 * @param {string}
	 *            type 変換タイプ h:半角 / f:全角
	 * @return {string} フォーマット後の文字列
	 * @private
	 * @requires _deleteStack
	 * @requires _formatConvert
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var string = _formatConvertAlphabetLower('string', 'h');
	 */
	function _formatConvertAlphabetLower(target, type) {
		try {
			var half = new Array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i',
					'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u',
					'v', 'w', 'x', 'y', 'z');
			var full = new Array('ａ', 'ｂ', 'ｃ', 'ｄ', 'ｅ', 'ｆ', 'ｇ', 'ｈ', 'ｉ',
					'ｊ', 'ｋ', 'ｌ', 'ｍ', 'ｎ', 'ｏ', 'ｐ', 'ｑ', 'ｒ', 'ｓ', 'ｔ', 'ｕ',
					'ｖ', 'ｗ', 'ｘ', 'ｙ', 'ｚ');
			//
			if (type === 'h') {
				target = _formatConvert(target, full, half);
				_deleteStack += half.join('');
			} else if (type === 'f') {
				target = _formatConvert(target, half, full);
				_deleteStack += full.join('');
			}
			return (target);
		} catch (e) {
			jQuery.showErrorDetail(e, 'formatConvertAlphabetLower');
			return ('');
		}
	}

	/**
	 * フォーマット:英字大文字の全角半角変換
	 *
	 * @param {string}
	 *            target フォーマット対象の文字列
	 * @param {string}
	 *            type 変換タイプ h:半角 / f:全角
	 * @return {string} フォーマット後の文字列
	 * @private
	 * @requires _deleteStack
	 * @requires _formatConvert
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var string = _formatConvertAlphabetUpper('string', 'h');
	 */
	function _formatConvertAlphabetUpper(target, type) {
		try {
			var half = new Array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
					'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U',
					'V', 'W', 'X', 'Y', 'Z');
			var full = new Array('Ａ', 'Ｂ', 'Ｃ', 'Ｄ', 'Ｅ', 'Ｆ', 'Ｇ', 'Ｈ', 'Ｉ',
					'Ｊ', 'Ｋ', 'Ｌ', 'Ｍ', 'Ｎ', 'Ｏ', 'Ｐ', 'Ｑ', 'Ｒ', 'Ｓ', 'Ｔ', 'Ｕ',
					'Ｖ', 'Ｗ', 'Ｘ', 'Ｙ', 'Ｚ');
			//
			if (type === 'h') {
				target = _formatConvert(target, full, half);
				_deleteStack += half.join('');
			} else if (type === 'f') {
				target = _formatConvert(target, half, full);
				_deleteStack += full.join('');
			}
			return (target);
		} catch (e) {
			jQuery.showErrorDetail(e, 'formatConvertAlphabetUpper');
			return ('');
		}
	}

	/**
	 * フォーマット:数字のみの全角半角変換
	 *
	 * @param {string}
	 *            target フォーマット対象の文字列
	 * @param {string}
	 *            type 変換タイプ h:半角 / f:全角
	 * @return {string} フォーマット後の文字列
	 * @private
	 * @requires _deleteStack
	 * @requires _formatConvert
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var string = _formatConvertNumberOnly('string', 'h');
	 */
	function _formatConvertNumberOnly(target, type) {
		try {
			var half = new Array('0', '1', '2', '3', '4', '5', '6', '7', '8',
					'9');
			var full = new Array('０', '１', '２', '３', '４', '５', '６', '７', '８',
					'９');
			//
			if (type === 'h') {
				target = _formatConvert(target, full, half);
				_deleteStack += half.join('');
			} else if (type === 'f') {
				target = _formatConvert(target, half, full, true);
				_deleteStack += full.join('');
			}
			return (target);
		} catch (e) {
			jQuery.showErrorDetail(e, 'formatConvertNumberOnly');
			return ('');
		}
	}

	/**
	 * フォーマット:数字の全角半角変換
	 *
	 * @param {string}
	 *            target フォーマット対象の文字列
	 * @param {string}
	 *            type 変換タイプ h:半角 / f:全角
	 * @return {string} フォーマット後の文字列
	 * @private
	 * @requires _deleteStack
	 * @requires _formatConvert
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var string = _formatConvertNumber('string', 'h');
	 */
	function _formatConvertNumber(target, type) {
		try {
			var half = new Array('0', '1', '2', '3', '4', '5', '6', '7', '8',
					'9', '%', '.', ',', '+', '-');
			var full = new Array('０', '１', '２', '３', '４', '５', '６', '７', '８',
					'９', '％', '．', '，', '＋', '－');
			//
			if (type === 'h') {
				target = _formatConvert(target, full, half);
				_deleteStack += '0123456789\%\\\.\,\\\+\\\-';
			} else if (type === 'f') {
				target = _formatConvert(target, half, full, true);
				_deleteStack += full.join('');
			}
			return (target);
		} catch (e) {
			jQuery.showErrorDetail(e, 'formatConvertNumber');
			return ('');
		}
	}

	/**
	 * フォーマット:記号の全角半角変換
	 *
	 * @param {string}
	 *            target フォーマット対象の文字列
	 * @param {string}
	 *            type 変換タイプ h:半角 / f:全角
	 * @return {string} フォーマット後の文字列
	 * @private
	 * @requires _deleteStack
	 * @requires _formatConvert
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var string = _formatConvertSymbol('string', 'h');
	 */
	function _formatConvertSymbol(target, type) {
		try {
			var half = new Array('!'
					, '"', '#'
					, '$', '%'
					, '\'', '&'
					, '(', ')'
					,'-', '='
					, '^', '~'
					, '\\', '|'
					, '@', '`'
					, '[', '{'
					, ';','+'
					, ':', '*'
					, ']', '}'
					, ',', '<'
					, '.', '>'
					, '/', '?'
					, '_','-'
					, '-', '-'
					, ',', '.'
					, '/');
			var full = new Array('！'
					, '”', '＃'
					, '＄', '％'
					, '’', '＆'
					, '（', '）',
					'－', '＝'
					, '＾', '～'
					, '￥', '｜'
					, '＠', '‘'
					, '［', '｛'
					, '；', '＋'
					,'：', '＊'
					, '］', '｝'
					, '，', '＜'
					, '．', '＞'
					, '／', '？'
					, '＿', 'ー'
					,'―', '‐'
					, '、', '。'
					, '・');
			//
			if (type === 'h') {
				target = _formatConvert(target, full, half);
				_deleteStack += '\\' + half.join('\\');
			} else if (type === 'f') {
				target = _formatConvert(target, half, full, true);
				_deleteStack += full.join('');
			}
			return (target);
		} catch (e) {
			jQuery.showErrorDetail(e, 'formatConvertSymbol');
			return ('');
		}
	}

	/**
	 * フォーマット:スペースの全角半角変換
	 *
	 * @param {string}
	 *            target フォーマット対象の文字列
	 * @param {string}
	 *            type 変換タイプ h:半角 / f:全角
	 * @return {string} フォーマット後の文字列
	 * @private
	 * @requires _deleteStack
	 * @requires _formatConvert
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var string = _formatConvertSpace('string', 'h');
	 */
	function _formatConvertSpace(target, type) {
		try {
			var half = new Array(' ');
			var full = new Array('　');
			//
			if (type === 'h') {
				target = _formatConvert(target, full, half);
				_deleteStack += half.join('');
			} else if (type === 'f') {
				target = _formatConvert(target, half, full);
				_deleteStack += full.join('');
			}
			return (target);
		} catch (e) {
			jQuery.showErrorDetail(e, 'formatConvertSpace');
			return ('');
		}
	}

	/**
	 * フォーマット:カタカナの全角半角変換
	 *
	 * @param {string}
	 *            target フォーマット対象の文字列
	 * @param {string}
	 *            type 変換タイプ h:半角 / f:全角
	 * @return {string} フォーマット後の文字列
	 * @private
	 * @requires _deleteStack
	 * @requires _formatConvert
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var string = _formatConvertKatakana('string', 'h');
	 */
	function _formatConvertKatakana(target, type) {
		try {
			// ※濁点・半濁点から先に置換する
			var half = new Array('ｶﾞ', 'ｷﾞ', 'ｸﾞ', 'ｹﾞ', 'ｺﾞ', 'ｻﾞ', 'ｼﾞ',
					'ｽﾞ', 'ｾﾞ', 'ｿﾞ', 'ﾀﾞ', 'ﾁﾞ', 'ﾂﾞ', 'ﾃﾞ', 'ﾄﾞ', 'ﾊﾞ', 'ﾋﾞ',
					'ﾌﾞ', 'ﾍﾞ', 'ﾎﾞ', 'ﾊﾟ', 'ﾋﾟ', 'ﾌﾟ', 'ﾍﾟ', 'ﾎﾟ', 'ｧ', 'ｨ',
					'ｩ', 'ｪ', 'ｫ', 'ｬ', 'ｭ', 'ｮ', 'ｯ', 'ｳﾞ', 'ｱ', 'ｲ', 'ｳ',
					'ｴ', 'ｵ', 'ｶ', 'ｷ', 'ｸ', 'ｹ', 'ｺ', 'ｻ', 'ｼ', 'ｽ', 'ｾ', 'ｿ',
					'ﾀ', 'ﾁ', 'ﾂ', 'ﾃ', 'ﾄ', 'ﾅ', 'ﾆ', 'ﾇ', 'ﾈ', 'ﾉ', 'ﾊ', 'ﾋ',
					'ﾌ', 'ﾍ', 'ﾎ', 'ﾏ', 'ﾐ', 'ﾑ', 'ﾒ', 'ﾓ', 'ﾔ', 'ﾕ', 'ﾖ', 'ﾗ',
					'ﾘ', 'ﾙ', 'ﾚ', 'ﾛ', 'ﾜ', 'ｦ', 'ﾝ', 'ｰ');
			var full = new Array('ガ', 'ギ', 'グ', 'ゲ', 'ゴ', 'ザ', 'ジ', 'ズ', 'ゼ',
					'ゾ', 'ダ', 'ヂ', 'ヅ', 'デ', 'ド', 'バ', 'ビ', 'ブ', 'ベ', 'ボ', 'パ',
					'ピ', 'プ', 'ペ', 'ポ', 'ァ', 'ィ', 'ゥ', 'ェ', 'ォ', 'ャ', 'ュ', 'ョ',
					'ッ', 'ヴ', 'ア', 'イ', 'ウ', 'エ', 'オ', 'カ', 'キ', 'ク', 'ケ', 'コ',
					'サ', 'シ', 'ス', 'セ', 'ソ', 'タ', 'チ', 'ツ', 'テ', 'ト', 'ナ', 'ニ',
					'ヌ', 'ネ', 'ノ', 'ハ', 'ヒ', 'フ', 'ヘ', 'ホ', 'マ', 'ミ', 'ム', 'メ',
					'モ', 'ヤ', 'ユ', 'ヨ', 'ラ', 'リ', 'ル', 'レ', 'ロ', 'ワ', 'ヲ', 'ン',
					'ー');
			//
			if (type === 'h') {
				target = _formatConvert(target, full, half);
				_deleteStack += half.join('');
			} else if (type === 'f') {
				target = _formatConvert(target, half, full);
				_deleteStack += full.join('');
			}
			return (target);
		} catch (e) {
			jQuery.showErrorDetail(e, 'formatConvertKatakana');
			return ('');
		}
	}

	/**
	 * フォーマット:英字の大文字小文字変換
	 *
	 * @param {string}
	 *            target フォーマット対象の文字列
	 * @param {string}
	 *            type 変換タイプ h:小文字 / f:大文字
	 * @return {string} フォーマット後の文字列
	 * @private
	 * @requires _deleteStack
	 * @requires _formatConvert
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var string = _formatConvertAlphabetCase('string', 'h');
	 */
	function _formatConvertAlphabetCase(target, type) {
		try {
			var half = new Array('ａ', 'ｂ', 'ｃ', 'ｄ', 'ｅ', 'ｆ', 'ｇ', 'ｈ', 'ｉ',
					'ｊ', 'ｋ', 'ｌ', 'ｍ', 'ｎ', 'ｏ', 'ｐ', 'ｑ', 'ｒ', 'ｓ', 'ｔ', 'ｕ',
					'ｖ', 'ｗ', 'ｘ', 'ｙ', 'ｚ');
			var full = new Array('Ａ', 'Ｂ', 'Ｃ', 'Ｄ', 'Ｅ', 'Ｆ', 'Ｇ', 'Ｈ', 'Ｉ',
					'Ｊ', 'Ｋ', 'Ｌ', 'Ｍ', 'Ｎ', 'Ｏ', 'Ｐ', 'Ｑ', 'Ｒ', 'Ｓ', 'Ｔ', 'Ｕ',
					'Ｖ', 'Ｗ', 'Ｘ', 'Ｙ', 'Ｚ');
			//
			if (type === 'h') {
				target = target.toLowerCase();
				target = _formatConvert(target, full, half);
				_deleteStack += 'abcdefghijklmnopqrstuvwxyz' + half.join('');
			} else if (type === 'f') {
				target = target.toUpperCase();
				target = _formatConvert(target, half, full);
				_deleteStack += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' + full.join('');
			}
			return (target);
		} catch (e) {
			jQuery.showErrorDetail(e, 'formatConvertAlphabetCase');
			return ('');
		}
	}

	/**
	 * フォーマット:ひらがな <-> カタカナ
	 *
	 * @param {string}
	 *            target フォーマット対象の文字列
	 * @param {string}
	 *            type 変換タイプ h:カタカナ / f:ひらがな
	 * @return {string} フォーマット後の文字列
	 * @private
	 * @requires _deleteStack
	 * @requires _formatConvert
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example var string = _formatConvertKana('string', 'h');
	 */
	function _formatConvertKana(target, type) {
		try {
			var katakana = new Array('ア', 'イ', 'ウ', 'エ', 'オ', 'カ', 'キ', 'ク',
					'ケ', 'コ', 'サ', 'シ', 'ス', 'セ', 'ソ', 'タ', 'チ', 'ツ', 'テ', 'ト',
					'ナ', 'ニ', 'ヌ', 'ネ', 'ノ', 'ハ', 'ヒ', 'フ', 'ヘ', 'ホ', 'マ', 'ミ',
					'ム', 'メ', 'モ', 'ヤ', 'ヰ', 'ユ', 'ヱ', 'ヨ', 'ラ', 'リ', 'ル', 'レ',
					'ロ', 'ワ', 'ヲ', 'ン', 'ガ', 'ギ', 'グ', 'ゲ', 'ゴ', 'ザ', 'ジ', 'ズ',
					'ゼ', 'ゾ', 'ダ', 'ヂ', 'ヅ', 'デ', 'ド', 'バ', 'ビ', 'ブ', 'ベ', 'ボ',
					'パ', 'ピ', 'プ', 'ペ', 'ポ', 'ァ', 'ィ', 'ゥ', 'ェ', 'ォ', 'ャ', 'ュ',
					'ョ', 'ッ', 'ヮ', 'ー');
			var hiragana = new Array('あ', 'い', 'う', 'え', 'お', 'か', 'き', 'く',
					'け', 'こ', 'さ', 'し', 'す', 'せ', 'そ', 'た', 'ち', 'つ', 'て', 'と',
					'な', 'に', 'ぬ', 'ね', 'の', 'は', 'ひ', 'ふ', 'へ', 'ほ', 'ま', 'み',
					'む', 'め', 'も', 'や', 'ゐ', 'ゆ', 'ゑ', 'よ', 'ら', 'り', 'る', 'れ',
					'ろ', 'わ', 'を', 'ん', 'が', 'ぎ', 'ぐ', 'げ', 'ご', 'ざ', 'じ', 'ず',
					'ぜ', 'ぞ', 'だ', 'ぢ', 'づ', 'で', 'ど', 'ば', 'び', 'ぶ', 'べ', 'ぼ',
					'ぱ', 'ぴ', 'ぷ', 'ぺ', 'ぽ', 'ぁ', 'ぃ', 'ぅ', 'ぇ', 'ぉ', 'ゃ', 'ゅ',
					'ょ', 'っ', 'ゎ', 'ー');
			//
			if (type === 'h') {
				target = _formatConvert(target, hiragana, katakana);
				_deleteStack += katakana.join('');
			} else if (type === 'f') {
				target = _formatConvert(target, katakana, hiragana);
				_deleteStack += hiragana.join('');
			}
			return (target);
		} catch (e) {
			jQuery.showErrorDetail(e, 'formatConvertKana');
			return ('');
		}
	}

	/**
	 * フォーマット:変換
	 *
	 * @param {string}
	 *            target フォーマット対象の文字列
	 * @param {array}
	 *            original 変換元
	 * @param {array}
	 *            format 変換先
	 * @param {bool}
	 *            escape true:エスケープ処理実行 / false:エスケープ処理不実行 [デフォルト:false]
	 * @return {string} フォーマット後の文字列
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example var string = _formatConvert('string', full, half, true);
	 */
	function _formatConvert(target, original, format, escape) {
		try {
			var object = null;
			var i = 0;
			var len = original.length;
			//
			if (escape === true) {
				for (i = 0; i < len; i++) {
					object = new RegExp(_formatConvertEscapeCheck(original[i]),
							'gm');
					target = target.replace(object, format[i]);
				}
			} else {
				for (i = 0; i < len; i++) {
					object = new RegExp(original[i], 'gm');
					target = target.replace(object, format[i]);
				}
			}
			delete (object);
			return (target);
		} catch (e) {
			jQuery.showErrorDetail(e, 'formatConvert');
			return ('');
		}
	}

	/**
	 * 半角記号でエスケープ処理が必要なもの判定
	 *
	 * @param {string}
	 *            character 文字
	 * @return {srting} エスケープ処理文字
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example var string = _formatConvertEscapeCheck(character);
	 */
	function _formatConvertEscapeCheck(character) {
		try {
			var escape = '\\/^$*+-?{|}[].()';
			var i = 0;
			var len = escape.length;
			for (i = 0; i < len; i++) {
				if (character.indexOf(escape[i], 0) !== -1) {
					return ('\\' + character);
				}
			}
			return (character);
		} catch (e) {
			jQuery.showErrorDetail(e, 'formatConvertEscapeCheck');
			return ('');
		}
	}

	/**
	 * jQuery拡張 フォーマット:数値型
	 *
	 * @param {string}
	 *            format フォーマット #[,]##(#|0)[.][#…#|#…0]
	 * @param {hash}
	 *            option ユーザーオプション
	 * @param {float}
	 *            option.min 最小値 nullの場合最小値指定なし [デフォルト:null]
	 * @param {float}
	 *            option.max 最大値 nullの場合最大値指定なし [デフォルト:null]
	 * @return {object} jQuery オブジェクト
	 * @public
	 * @requires _textNumberFormatFocus
	 * @requires _textNumberFormatBlur
	 * @requires jQuery.textNumberFormat
	 * @requires jQuery.showErrorDetail
	 * @example $('#sample').textNumberFormat('#,##0', {min : 0, max : 200});
	 */
	jQuery.fn.textNumberFormat = function(format, option) {
		try {
			// 入力系タグ抽出
			var input = this.find('input[type=text]').add(
					this.filter('input[type=text]')).add(this.find('textarea'))
					.add(this.filter('textarea'));
			//
			input.each(function(index, dom) {
				try {
					jQuery(this).unbind('focus', _textNumberFormatFocus)
							.unbind('blur', _textNumberFormatBlur).bind(
									'focus', _textNumberFormatFocus).bind(
									'blur', {
										numberFormat : format,
										numberFormatOption : option
									}, _textNumberFormatBlur);
				} catch (e) {
					jQuery.showErrorDetail(e, 'textNumberFormat each');
				}
			});
			//
			return (this.each(function(index, dom) {
			}));
		} catch (e) {
			jQuery.showErrorDetail(e, 'textNumberFormat');
			// エラー時は処理なしで jQuery オブジェクト自体を返却
			return (this.each(function(index, dom) {
			}));
		}
	};

	/**
	 * 数値型フォーマット用イベント (focus unbind 特定用)
	 *
	 * @param {object}
	 *            event イベント
	 * @private
	 * @requires _formatConvertNumber
	 * @requires jQuery.showErrorDetail
	 * @example jQuery(this).unbind('focus', _textNumberFormatFocus);
	 */
	function _textNumberFormatFocus(event) {
		try {
			var object = jQuery(event['target']);
			var string = _formatConvertNumber(jQuery.mbTrim(object.val()), 'h')
					.replace(/,/g, '');
			object.val(string);
		} catch (e) {
			jQuery.showErrorDetail(e, 'textNumberFormatFocus');
		}
	}

	/**
	 * 数値型フォーマット用イベント (blur unbind 特定用)
	 *
	 * @param {object}
	 *            event イベント
	 * @private
	 * @requires jQuery.textNumberFormat
	 * @requires jQuery.showErrorDetail
	 * @example jQuery(this).bind('blur', {numberFormat : '#,##0',
	 *          numberFormatOption : {min : 0, max : 200}},
	 *          _textNumberFormatBlur);
	 */
	function _textNumberFormatBlur(event) {
		try {
			var object = jQuery(event['target']);
			var string = jQuery.textNumberFormat(object.val(),
					event['data']['numberFormat'],
					event['data']['numberFormatOption']);
			object.val(string);
		} catch (e) {
			jQuery.showErrorDetail(e, 'textNumberFormatBlur');
		}
	}

	/**
	 * フォーマット:数値型
	 *
	 * @param {string}
	 *            target フォーマット対象文字列
	 * @param {string}
	 *            format フォーマット #[,]##(#|0)[.][#…#|#…0]
	 * @param {hash}
	 *            option ユーザーオプション
	 * @param {float}
	 *            option.min 最小値 nullの場合最小値指定なし [デフォルト:null]
	 * @param {float}
	 *            option.max 最大値 nullの場合最大値指定なし [デフォルト:null]
	 * @return {string} フォーマット後文字列
	 * @public
	 * @requires _formatConvertNumeric
	 * @requires jQuery.showErrorDetail
	 * @example var string = $.textNumberFormat('12345', '#,##0', {min : 0, max :
	 *          200});
	 */
	jQuery.textNumberFormat = function(target, format, option) {
		try {
			return (_formatConvertNumeric(target, format, option));
		} catch (e) {
			jQuery.showErrorDetail(e, 'textNumberFormat');
			return ('');
		}
	};

	/**
	 * フォーマット:数値形式
	 *
	 * @param {string}
	 *            target フォーマット対象の文字列
	 * @param {string}
	 *            format 変換タイプ #[,]##(#|0)[.][#…#|#…0] [デフォルト:#]
	 * @param {hash}
	 *            option ユーザーオプション
	 * @param {float}
	 *            option.min 最小値 nullの場合最小値指定なし [デフォルト:null]
	 * @param {float}
	 *            option.max 最大値 nullの場合最大値指定なし [デフォルト:null]
	 * @return {string} フォーマット後の文字列
	 * @private
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.mbTrim
	 * @requires jQuery.getType
	 * @requires jQuery.showErrorDetail
	 * @example var string = _formatConvertNumeric('12345', '#,##0', {min : 0,
	 *          max : 200});
	 */
	function _formatConvertNumeric(target, format, option) {
		try {
			format = jQuery.mbTrim(format);
			if (format === '') {
				format = '#';
			}
			var min = null; // 最小値
			var max = null; // 最大値
			var tmp = null; // 一時利用
			if (jQuery.getType(option) === 'hash') {
				min = (('min' in option) ? option['min'] : null);
				max = (('max' in option) ? option['max'] : null);
			}
			min = ((jQuery.isFloat(min)) ? jQuery.castNumber(min, true) : null);
			max = ((jQuery.isFloat(max)) ? jQuery.castNumber(max, true) : null);
			if (min !== null && max !== null && min > max) {
				// min / max の指定あり -> 値の大小が逆
				tmp = min;
				min = max;
				max = tmp;
			}
			//
			var comma = false; // カンマ true:あり / false:なし
			var zeroInteger = false; // true:整数が空でも0入力 / false:通常
			var zeroDecimal = 0; // 小数部の表示桁数
			var zeroFill = ''; // 小数部分の 0
			var decimalFlag = true; // true:小数あり / false:小数なし
			var result = ''; // 結果
			var i = 0; // ループ用
			//
			// type 解析
			var match = format.match(/^([0#]+)([,0#]*)(\.?)([0#]*)$/);
			if (match === null) {
				// type の形式を解析できない
				jQuery.throwException('illegal format');
				return ('');
			}
			if (match[1].indexOf('0') > -1 || match[2].indexOf('0') > -1) {
				// 整数部に 0 あり
				zeroInteger = true;
			}
			if (match[2] !== '') {
				// カンマあり
				comma = true;
			}
			if (match[3] === '') {
				decimalFlag = false;
			} else {
				zeroDecimal = match[4].length;
				if (match[4].indexOf('0') > -1) {
					for (i = 0; i < zeroDecimal; i++) {
						zeroFill += '0';
					}
				}
			}
			//
			// 対象整形
			target = _formatConvertNumber(jQuery.mbTrim(target), 'h').replace(
					/,/g, '');
			// 最小 / 最大確認
			if (target !== '') {
				tmp = 0;
				tmp = (jQuery.isFloat(target) ? jQuery.castNumber(target, true)
						: null);
				if (tmp !== null) {
					if (min !== null) {
						if (tmp < min) {
							target = jQuery.castString(min);
						}
					}
					if (max !== null) {
						if (tmp > max) {
							target = jQuery.castString(max);
						}
					}
				}
			}
			//
			match = target.match(/^([+-]?)(\d+)\.?(\d*)$/);
			//
			if (match === null || match[2] === '') {
				// 数値以外 or 空
				if (zeroInteger) {
					result = '0' + ((zeroFill !== '') ? '.' + zeroFill : '');
				} else {
					result = (zeroFill !== '') ? '0.' + zeroFill : '';
				}
				return (result);
			}
			//
			// 符号
			if (match[1] === '-') {
				result += '-';
			}
			// 整数部
			result += jQuery.castNumber(match[2], true);
			// カンマ区切り
			if (comma) {
				while (result != (result = result.replace(/^(-?\d+)(\d{3})/,
						'$1,$2')))
					;
			}
			// 小数部
			if (decimalFlag) {
				match[3] = jQuery.left(match[3] + zeroFill, zeroDecimal);
				result += ((match[3] !== '') ? '.' + match[3] : '');
			}
			//
			return (result);
		} catch (e) {
			jQuery.showErrorDetail(e, 'formatConvertNumeric');
			return ('');
		}
	}

	/**
	 * jQuery拡張 フォーマット:日付型
	 *
	 * @param {string}
	 *            format フォーマット ymd:yyyy/mm/dd / ym:yyyy/mm / md:mm/dd
	 *            [デフォルト:ymd]
	 * @public
	 * @requires _textDateFormatBlur
	 * @requires jQuery.showErrorDetail
	 * @example $('#sample').textDateFormat('ymd');
	 */
	jQuery.fn.textDateFormat = function(format) {
		try {
			// 入力系タグ抽出
			var input = this.find('input[type=text]').add(
					this.filter('input[type=text]')).add(this.find('textarea'))
					.add(this.filter('textarea'));
			//
			input.each(function(index, dom) {
				try {
					jQuery(this).unbind('blur', _textDateFormatBlur).unbind(
							'keypress', _textDateFormatKeypress).bind('blur', {
						dateFormat : format
					}, _textDateFormatBlur).bind('keypress',
							_textDateFormatKeypress);
				} catch (e) {
					jQuery.showErrorDetail(e, 'textDateFormat each');
				}
			});
			//
			return (this.each(function(index, dom) {
			}));
		} catch (e) {
			jQuery.showErrorDetail(e, 'textDateFormat');
			// エラー時は処理なしで jQuery オブジェクト自体を返却
			return (this.each(function(index, dom) {
			}));
		}
	};

	/**
	 * 日付型フォーマット用イベント (unbind 特定用)
	 *
	 * @param {object}
	 *            event イベント
	 * @private
	 * @requires jQuery.textDateFormat
	 * @requires jQuery.showErrorDetail
	 * @example $(this).bind('blur', {dateFormat : 'ymd'}, _textDateFormatBlur);
	 */
	function _textDateFormatBlur(event) {
		try {
			var object = jQuery(event['target']);
			var string = jQuery.textDateFormat(object.val(),
					event['data']['dateFormat']);
			object.val(string);
		} catch (e) {
			jQuery.showErrorDetail(e, 'textDateFormatBlur');
		}
	}

	/**
	 * 日付型フォーマット用イベント (unbind 特定用)
	 *
	 * @param {object}
	 *            event イベント
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example $(this).bind('keypress', _textDateFormatKeypress);
	 */
	function _textDateFormatKeypress(event) {
		try {
			if (event.altKey === true && event.which === 59) {
				var date = new Date();
				var object = jQuery(event['target']);
				object.val(date.getFullYear() + '/'
						+ jQuery.right('00' + (date.getMonth() + 1), 2) + '/'
						+ jQuery.right('00' + date.getDate(), 2));
				return (false);
			}
			return (true);
		} catch (e) {
			jQuery.showErrorDetail(e, 'textDateFormatKeypress');
			return (false);
		}
	}

	/**
	 * フォーマット:日付型
	 *
	 * @param {string}
	 *            target フォーマット対象文字列
	 * @param {string}
	 *            format フォーマット ymd:yyyy/mm/dd / ym:yyyy/mm / md:mm/dd
	 *            [デフォルト:ymd]
	 * @return {string} フォーマット後文字列
	 * @public
	 * @requires _formatConvertDate
	 * @requires jQuery.showErrorDetail
	 * @example var string = $.textDateFormat('20121231', 'ymd');
	 */
	jQuery.textDateFormat = function(target, format) {
		try {
			return (_formatConvertDate(target, format));
		} catch (e) {
			jQuery.showErrorDetail(e, 'textDateFormat');
			return ('');
		}
	};

	/**
	 * フォーマット:日付形式
	 *
	 * @param {string}
	 *            target フォーマット対象の文字列
	 * @param {string}
	 *            type 変換タイプ ymd:[yyyy/mm/dd]形式 / ym:[yyyy/mm]形式 / md:[mm/dd]形式
	 *            [デフォルト:ymd]
	 * @return {string} フォーマット後の文字列
	 * @private
	 * @requires _formatConvertNumber
	 * @requires _formatConvertSymbol
	 * @requires jQuery._formatConvertNumber
	 * @requires jQuery.mbTrim
	 * @requires jQuery.right
	 * @requires jQuery.showErrorDetail
	 * @example var string = _formatConvertDate('20121231', 'ymd');
	 */
	function _formatConvertDate(target, type) {
		try {
			var result = ''; // 結果
			//
			target = jQuery.mbTrim(target);
			target = _formatConvertNumber(target, 'h');
			target = _formatConvertSymbol(target, 'h');
			target = target.replace(/\s*[\.\-\/\_\'\,\･]\s*|\s+/g, '/');
			type = jQuery.mbTrim(type).toLowerCase();
			//
			var split = target.split('/');
			var year = 0;
			var month = 0;
			var day = 0;
			var tmp = 0;
			var flag = true;
			//
			var lenSplit = split.length;
			var lenTarget = target.length;
			//
			switch (type) {
			case 'ym':
				// 年月形式
				if (lenSplit === 1) {
					// "/"で 1 分割
					year = target.substr(0, 4);
					day = 1; // ダミー
					//
					if (lenTarget === 5) {
						// 5 文字
						month = target.substr(4, 1);
					} else if (lenTarget === 6 || lenTarget === 7) {
						// 6 文字 or 7 文字
						tmp = jQuery.castNumber(target.substr(4, 2), false);
						if (tmp > 0 && tmp < 13) {
							// 月が 1 ～ 12 の範囲
							month = target.substr(4, 2);
						} else {
							month = target.substr(4, 1);
						}
					} else if (lenTarget === 8) {
						// 8 文字
						month = target.substr(4, 2);
						day = target.substr(6, 4);
					} else {
						// 4 文字以下 or 9 文字以上
						flag = false;
					}
				} else if (lenSplit === 2) {
					// "/"で 2 分割
					year = split[0];
					month = split[1];
					day = 1; // ダミー
				} else if (lenSplit === 3) {
					// "/"で 3 分割
					year = split[0];
					month = split[1];
					day = split[2];
				} else {
					// 4 分割以上
					flag = false;
				}
				break;
			case 'md':
				// 月日形式
				if (lenSplit === 1) {
					// "/"で 1 分割
					if (lenTarget === 2) {
						// 2 文字 -> Md
						year = 2000; // ダミー
						month = target.substr(0, 1);
						day = target.substr(1, 1);
					} else if (lenTarget === 3) {
						// 3 文字
						year = 2000; // ダミー
						//
						tmp = jQuery.castNumber(target.substr(0, 2), false);
						if (tmp > 0 && tmp < 13 && target.substr(2, 1) !== '0') {
							// 月が 1 ～ 12 の範囲 and 日が 0 以外 -> MMd
							month = target.substr(0, 2);
							day = target.substr(2, 1);
						} else {
							// Mdd
							month = target.substr(0, 1);
							day = target.substr(1, 2);
						}
					} else if (lenTarget === 4) {
						// 4 文字 -> MMdd
						year = 2000; // ダミー
						month = target.substr(0, 2);
						day = target.substr(2, 2);
					} else if (lenTarget === 8) {
						// 8 文字
						year = target.substr(0, 4);
						month = target.substr(4, 2);
						day = target.substr(6, 2);
					} else {
						flag = false;
					}
				} else if (lenSplit === 2) {
					// "/"で 2 分割
					year = 2000; // ダミー
					month = split[0];
					day = split[1];
				} else if (lenSplit === 3) {
					// "/"で 3 分割
					year = split[0];
					month = split[1];
					day = split[2];
				} else {
					// 4 分割以上
					flag = false;
				}
				break;
			default:
				// 年月日形式
				if (lenSplit === 1) {
					// "/"で 1 分割
					if (lenTarget === 6) {
						// 6 文字 -> yyyyMd
						year = target.substr(0, 4);
						month = target.substr(4, 1);
						day = target.substr(5, 1);
					} else if (lenTarget === 7) {
						// 7 文字 -> yyyyMMd or yyyyMdd
						year = target.substr(0, 4);
						//
						tmp = jQuery.castNumber(target.substr(4, 2), false);
						if (tmp > 0 && tmp < 13 && target.substr(6, 1) !== '0') {
							// 月が 1 ～ 12 の範囲 and 日が 0 以外 -> yyyy/MMd
							month = target.substr(4, 2);
							day = target.substr(6, 1);
						} else {
							// yyyyMdd
							month = target.substr(4, 1);
							day = target.substr(5, 2);
						}
					} else if (lenTarget === 8) {
						// 8 文字 -> yyyyMMdd
						year = target.substr(0, 4);
						month = target.substr(4, 2);
						day = target.substr(6, 2);
					} else {
						// 5 文字以下 or 9 文字以上
						flag = false;
					}
				} else if (lenSplit === 2) {
					// "/"で 2 分割
					if (split[0].length === 4) {
						// 1 分割目が 4 文字
						year = split[0];
						//
						if (split[1].length == 2) {
							// 2 分割目が 2 文字 -> yyyy/Md 形式
							month = split[1].substr(0, 1);
							day = split[1].substr(1, 1);
						} else if (split[1].length == 3) {
							// 2 分割目が 3 文字 -> yyyy/MMd or yyyy/M/dd 形式
							tmp = jQuery.castNumber(split[1].substr(0, 2),
									false);
							if (tmp > 0 && tmp < 13
									&& split[1].substr(2, 1) !== '0') {
								// 月が 1 ～ 12 の範囲 and 日が 0 以外 -> yyyy/MMd
								month = split[1].substr(0, 2);
								day = split[1].substr(2, 1);
							} else {
								// yyyy/Mdd
								month = split[1].substr(0, 1);
								day = split[1].substr(1, 2);
							}
						} else if (split[1].length == 4) {
							// yyyy/MMdd 形式
							month = split[1].substr(0, 2);
							day = split[1].substr(2, 2);
						} else {
							// 2 分割目が 5 文字以上
							flag = false;
						}
					} else if (split[0].length === 5 || split[0].length === 6) {
						// 1 分割目が 5 文字 or 6 文字 -> yyyyM/d or yyyyM/dd 形式
						year = split[0].substr(0, 4);
						month = split[0].substr(4, 2);
						day = split[1];
					} else {
						// 1 分割目が 3 文字以下 or 7 文字以上
						flag = false;
					}
				} else if (lenSplit === 3) {
					// "/"で 3 分割
					year = split[0];
					month = split[1];
					day = split[2];
				} else {
					// 3分割以上
					flag = false;
				}
				break;
			}
			//
			if (!flag || !jQuery.isInteger(year) || !jQuery.isInteger(month)
					|| !jQuery.isInteger(day)) {
				// フォーマットエラー or 年月日のいずれかが数値以外
				return ('');
			}
			//
			if (year.length === 2) {
				// 年が 2 桁 -> 補正
				if (jQuery.castNumber(year, false) < _yearBoud + 1) {
					year = '20' + year;
				} else {
					year = '19' + year;
				}
			}
			//
			year = jQuery.castNumber(year, false);
			month = jQuery.castNumber(month, false);
			day = jQuery.castNumber(day, false);
			//
			// 日付作成 -> 月は 0 から 11 で処理されるので -1
			var dateCheck = new Date(year, (month - 1), day);
			// 日付:2000/01/32 = 2000/02/01となることを利用して正当性確認
			if (dateCheck.getFullYear() !== year
					|| dateCheck.getMonth() !== (month - 1)
					|| dateCheck.getDate() !== day) {
				// 日付以外
				return ('');
			}
			//
			// 0 詰め
			month = jQuery.right('00' + month, 2);
			day = jQuery.right('00' + day, 2);
			//
			switch (type) {
			case 'ym':
				// 年月形式
				result = year + '/' + month;
				break;
			case 'md':
				// 月日形式
				result = month + '/' + day;
				break;
			default:
				// 年月日形式
				result = year + '/' + month + '/' + day;
				break;
			}
			//
			return (result);
		} catch (e) {
			jQuery.showErrorDetail(e, 'formatConvertDate');
			return ('');
		}
	}

	/**
	 * jQuery拡張 フォーマット:時刻型
	 *
	 * @param {string}
	 *            format フォーマット 12h:12時間表記 : 24h:24時間表記 [デフォルト:24h]
	 * @param {string}
	 *            option 変換オプション his:[hh:ii:ss]形式 / hi:[hh:ii]形式 / is:[ii:ss]形式
	 *            [デフォルト:his]
	 * @return {object} jQuery オブジェクト
	 * @public
	 * @requires _textTimeFormatBlur
	 * @requires _textTimeFormatKeypress
	 * @requires jQuery.showErrorDetail
	 * @example $('#sample').textTimeFormat('24h', 'his');
	 */
	jQuery.fn.textTimeFormat = function(format, option) {
		try {
			// 入力系タグ抽出
			var input = this.find('input[type=text]').add(
					this.filter('input[type=text]')).add(this.find('textarea'))
					.add(this.filter('textarea'));
			//
			input.each(function(index, dom) {
				try {
					jQuery(this).unbind('blur', _textTimeFormatBlur).unbind(
							'keypress', _textTimeFormatKeypress).bind('blur', {
						timeFormat : format,
						timeOption : option
					}, _textTimeFormatBlur).bind('keypress',
							_textTimeFormatKeypress);
				} catch (e) {
					jQuery.showErrorDetail(e, 'textTimeFormat each');
				}
			});
			//
			return (this.each(function(index, dom) {
			}));
		} catch (e) {
			jQuery.showErrorDetail(e, 'textTimeFormat');
			// エラー時は処理なしで jQuery オブジェクト自体を返却
			return (this.each(function(index, dom) {
			}));
		}
	};

	/**
	 * 時刻型フォーマット用イベント (unbind 特定用)
	 *
	 * @param {object}
	 *            event イベント
	 * @private
	 * @requires jQuery.textTimeFormat
	 * @requires jQuery.showErrorDetail
	 * @example $(this).bind('blur', {timeFormat : '24h', timeOption : 'his'},
	 *          _textTimeFormatBlur);
	 */
	function _textTimeFormatBlur(event) {
		try {
			var object = jQuery(event['target']);
			var string = jQuery.textTimeFormat(object.val(),
					event['data']['timeFormat'], event['data']['timeOption']);
			object.val(string);
		} catch (e) {
			jQuery.showErrorDetail(e, 'textTimeFormatBlur');
		}
	}

	/**
	 * 時刻型フォーマット用イベント (unbind 特定用)
	 *
	 * @param {object}
	 *            event イベント
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example $(this).bind('keypress', _textTimeFormatKeypress);
	 */
	function _textTimeFormatKeypress(event) {
		try {
			if (event.altKey === true && event.which === 58) {
				var date = new Date();
				var object = jQuery(event['target']);
				object.val(jQuery.right('00' + date.getHours(), 2) + ':'
						+ jQuery.right('00' + date.getMinutes(), 2) + ':'
						+ jQuery.right('00' + date.getSeconds(), 2));
				return (false);
			}
			return (true);
		} catch (e) {
			jQuery.showErrorDetail(e, 'textTimeFormatKeypress');
			return (false);
		}
	}

	/**
	 * フォーマット:時刻型
	 *
	 * @param {string}
	 *            target フォーマット対象文字列
	 * @param {string}
	 *            format フォーマット 12h:12時間表記 : 24h:24時間表記 [デフォルト:24h]
	 * @param {string}
	 *            option 変換オプション his:[hh:ii:ss]形式 / hi:[hh:ii]形式 / is:[ii:ss]形式
	 *            [デフォルト:his]
	 * @return {string} フォーマット後文字列
	 * @public
	 * @requires jQuery.textTimeFormat
	 * @requires jQuery.showErrorDetail
	 * @example var string = $.textTimeFormat('125959', '24h', 'his');
	 */
	jQuery.textTimeFormat = function(target, format, option) {
		try {
			return (_formatConvertTime(target, format, option));
		} catch (e) {
			jQuery.showErrorDetail(e, 'textTimeFormat');
			return ('');
		}
	};

	/**
	 * フォーマット:時刻形式
	 *
	 * @param {string}
	 *            target フォーマット対象の文字列
	 * @param {string}
	 *            type 変換タイプ 12h:12時間表記 : 24h:24時間表記 [デフォルト:24h]
	 * @param {string}
	 *            option 変換オプション his:[hh:ii:ss]形式 / hi:[hh:ii]形式 / is:[ii:ss]形式
	 *            [デフォルト:his]
	 * @return {string} フォーマット後の文字列
	 * @private
	 * @requires _formatConvertNumber
	 * @requires _formatConvertSymbol
	 * @requires jQuery.castNumber
	 * @requires jQuery.mbTrim
	 * @requires jQuery.right
	 * @requires jQuery.showErrorDetail
	 * @example var string = _formatConvertTime('125959', '24h', 'his');
	 */
	function _formatConvertTime(target, type, option) {
		try {
			// 時刻形式判定用
			var timeFormatCheck = function(hour, minute, second) {
				try {
					hour = jQuery.mbTrim(hour);
					minute = jQuery.mbTrim(minute);
					second = jQuery.mbTrim(second);
					if (!jQuery.isInteger(hour) || !jQuery.isInteger(hour)
							|| !jQuery.isInteger(hour)) {
						// 時刻以外
						return (false);
					}
					hour = jQuery.castNumber(hour, false);
					minute = jQuery.castNumber(minute, false);
					second = jQuery.castNumber(second, false);
					if ((hour < 0 || hour > 23) || (minute < 0 || minute > 59)
							|| (second < 0 || second > 59)) {
						// 時刻以外
						return (false);
					}
					return (true);
				} catch (e) {
					jQuery.showErrorDetail(e, 'timeFormatCheck');
					return (false);
				}
			};
			//
			var result = ''; // 結果
			//
			target = jQuery.mbTrim(target);
			target = _formatConvertNumber(target, 'h');
			target = _formatConvertSymbol(target, 'h');
			target = target.replace(/\s*[\.\-\:\_\'\,]\s*|\s+/g, ':');
			type = jQuery.mbTrim(type).toLowerCase();
			option = jQuery.mbTrim(option).toLowerCase();
			//
			var split = target.split(':');
			var hour = 0;
			var minute = 0;
			var second = 0;
			var tmp = '';
			//
			var lenSplit = split.length;
			var lenTarget = target.length;
			//
			switch (option) {
			case 'hi':
				// 時分形式
				if (lenSplit === 1) {
					// ":"で 1 分割
					if (lenTarget === 2) {
						// 2文字 hi
						hour = target.substr(0, 1);
						minute = target.substr(1, 1);
						second = 0; // ダミー
					} else if (lenTarget === 3) {
						// 3文字
						second = 0; // ダミー
						// hhi
						hour = target.substr(0, 2);
						second = target.substr(2, 1);
						if (timeFormatCheck(hour, minute, second)) {
							break;
						}
						// hii
						hour = target.substr(0, 1);
						second = target.substr(1, 2);
					} else if (lenTarget === 4) {
						// 4文字 hhii
						hour = target.substr(0, 2);
						minute = target.substr(2, 2);
						second = 0; // ダミー
					} else if (lenTarget === 6) {
						// 6文字 hhiiss
						hour = target.substr(0, 2);
						minute = target.substr(2, 2);
						second = target.substr(4, 2);
					} else {
						// 1文字以下 or 5文字以上 (6文字を除く)
						return ('');
					}
				} else if (lenSplit === 2) {
					// ":"で 2 分割
					hour = split[0];
					minute = split[1];
					second = 0; // ダミー
				} else if (lenSplit === 3) {
					// ":"で 3 分割
					hour = split[0];
					minute = split[1];
					second = split[2];
				} else {
					// ":"で 3 分割以上
					return ('');
				}
				break;
			case 'is':
				// 分秒形式
				if (lenSplit === 1) {
					// ":"で 1 分割
					if (lenTarget === 2) {
						// 2文字 is
						hour = 0; // ダミー
						minute = target.substr(0, 1);
						second = target.substr(1, 1);
					} else if (lenTarget === 3) {
						// 3文字
						hour = 0; // ダミー
						// iis
						minute = target.substr(0, 2);
						second = target.substr(2, 1);
						if (timeFormatCheck(hour, minute, second)) {
							break;
						}
						// iss
						minute = target.substr(0, 1);
						second = target.substr(1, 2);
					} else if (lenTarget === 4) {
						// 4文字 iiss
						hour = 0; // ダミー
						minute = target.substr(0, 2);
						second = target.substr(2, 2);
					} else if (lenTarget === 6) {
						// 6文字 hhiiss
						hour = target.substr(0, 2);
						minute = target.substr(2, 2);
						second = target.substr(4, 2);
					} else {
						// 1文字以下 or 5文字以上 (6文字を除く)
						return ('');
					}
				} else if (lenSplit === 2) {
					// ":"で 2 分割
					hour = 0; // ダミー
					minute = split[0];
					second = split[1];
				} else if (lenSplit === 3) {
					// ":"で 3 分割
					hour = split[0];
					minute = split[1];
					second = split[2];
				} else {
					// ":"で 3 分割以上
					return ('');
				}
				break;
			default:
				// 時分秒形式
				if (lenSplit === 1) {
					// ":"で 1 分割
					if (lenTarget === 3) {
						// 3文字
						hour = target.substr(0, 1);
						minute = target.substr(1, 1);
						second = target.substr(2, 1);
					} else if (lenTarget === 4) {
						// 4文字 -> どこを2桁とるか
						// hhii00
						hour = target.substr(0, 2);
						minute = target.substr(2, 2);
						second = 0; // ダミー
						if (timeFormatCheck(hour, minute, second)) {
							break;
						}
						// hhis
						hour = target.substr(0, 2);
						minute = target.substr(2, 1);
						second = target.substr(3, 1);
						if (timeFormatCheck(hour, minute, second)) {
							break;
						}
						// hiis
						hour = target.substr(0, 1);
						minute = target.substr(1, 2);
						second = target.substr(3, 1);
						if (timeFormatCheck(hour, minute, second)) {
							break;
						}
						// hiss
						hour = target.substr(0, 1);
						minute = target.substr(1, 1);
						second = target.substr(2, 2);
					} else if (lenTarget === 5) {
						// 5文字 -> どこを1桁とるか
						// hh:ii:s
						hour = target.substr(0, 2);
						minute = target.substr(2, 2);
						second = target.substr(4, 1);
						if (timeFormatCheck(hour, minute, second)) {
							break;
						}
						// hhiss
						hour = target.substr(0, 2);
						minute = target.substr(2, 1);
						second = target.substr(3, 2);
						if (timeFormatCheck(hour, minute, second)) {
							break;
						}
						// hiiss
						hour = target.substr(0, 1);
						minute = target.substr(1, 2);
						second = target.substr(3, 2);
					} else if (lenTarget === 6) {
						// 6文字
						hour = target.substr(0, 2);
						minute = target.substr(2, 2);
						second = target.substr(4, 2);
					} else {
						// 2文字以下 or 7文字以上
						return ('');
					}
				} else if (lenSplit === 2) {
					// ":"で 2 分割 -> hh:ii とする
					hour = split[0]; // 決打
					minute = split[1]; // 決打
					second = 0; // ダミー
				} else if (lenSplit === 3) {
					// ":"で 3 分割
					hour = split[0];
					minute = split[1];
					second = split[2];
				} else {
					// ":"で 3 分割以上
					return ('');
				}
				break;
			}
			//
			if (!timeFormatCheck(hour, minute, second)) {
				// 時刻以外
				return ('');
			}
			//
			if (type === '12h') {
				// 12時間表記 -> 12時を越えている場合は変換
				hour = jQuery.castNumber(hour, false) % 12;
			}
			//
			// 0 詰め
			switch (option) {
			case 'hi':
				// 時分形式
				result += jQuery.right('00' + hour, 2) + ':';
				result += jQuery.right('00' + minute, 2);
				break;
			case 'is':
				// 分秒形式
				result += jQuery.right('00' + minute, 2) + ':';
				result += jQuery.right('00' + second, 2);
				break;
			default:
				// 時分秒形式
				result += jQuery.right('00' + hour, 2) + ':';
				result += jQuery.right('00' + minute, 2) + ':';
				result += jQuery.right('00' + second, 2);
				break;
			}
			//
			return (result);
		} catch (e) {
			jQuery.showErrorDetail(e, 'formatConvertTime');
			return ('');
		}
	}

	/**
	 * jQuery拡張 フォーマット:日時型
	 *
	 * @param {object}
	 *            option オプション
	 * @param {string}
	 *            option.date 日付変換タイプ ymd:[yyyy/mm/dd]形式 / ym:[yyyy/mm]形式 /
	 *            md:[mm/dd]形式 [デフォルト:ymd]
	 * @param {string}
	 *            option.time 時刻変換タイプ his:[hh:ii:ss]形式 / hi:[hh:ii]形式 /
	 *            is:[ii:ss]形式 [デフォルト:his]
	 * @param {string}
	 *            option.hour フォーマット 12h:12時間表記 : 24h:24時間表記 [デフォルト:24h]
	 * @return {object} jQuery オブジェクト
	 * @public
	 * @requires _textDatetimeFormatBlur
	 * @requires _textDatetimeFormatKeypress
	 * @requires jQuery.showErrorDetail
	 * @example $('#sample').textTimeFormat({date : 'ymd', time : 'hi'});
	 */
	jQuery.fn.textDatetimeFormat = function(option) {
		try {
			// 入力系タグ抽出
			var input = this.find('input[type=text]').add(
					this.filter('input[type=text]')).add(this.find('textarea'))
					.add(this.filter('textarea'));
			//
			input.each(function(index, dom) {
				try {
					jQuery(this).unbind('blur', _textDatetimeFormatBlur)
							.unbind('keypress', _textDatetimeFormatKeypress)
							.bind('blur', {
								textDatetimeOption : option
							}, _textDatetimeFormatBlur).bind('keypress',
									_textDatetimeFormatKeypress);
				} catch (e) {
					jQuery.showErrorDetail(e, 'textDatetimeFormat each');
				}
			});
			//
			return (this.each(function(index, dom) {
			}));
		} catch (e) {
			jQuery.showErrorDetail(e, 'textDatetimeFormat');
			// エラー時は処理なしで jQuery オブジェクト自体を返却
			return (this.each(function(index, dom) {
			}));
		}
	};

	/**
	 * 日時型フォーマット用イベント (unbind 特定用)
	 *
	 * @param {object}
	 *            event イベント
	 * @private
	 * @requires jQuery.textTimeFormat
	 * @requires jQuery.showErrorDetail
	 * @example $(this).bind('blur', {textDatetimeOption : {date : 'ymd', time :
	 *          'hi'}}, _textTimeFormatBlur);
	 */
	function _textDatetimeFormatBlur(event) {
		try {
			var object = jQuery(event['target']);
			var string = jQuery.textDatetimeFormat(object.val(),
					event['data']['textDatetimeOption']);
			object.val(string);
		} catch (e) {
			jQuery.showErrorDetail(e, 'textDatetimeFormatBlur');
		}
	}

	/**
	 * 日時型フォーマット用イベント (unbind 特定用)
	 *
	 * @param {object}
	 *            event イベント
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example $(this).bind('keypress', _textTimeFormatKeypress);
	 */
	function _textDatetimeFormatKeypress(event) {
		try {
			var object = jQuery(event['target']);
			var date = new Date();
			if (event.altKey === true) {
				if (event.which === 59) {
					object.val(object.val() + date.getFullYear() + '/'
							+ jQuery.right('00' + (date.getMonth() + 1), 2)
							+ '/' + jQuery.right('00' + date.getDate(), 2));
					return (false);
				} else if (event.which === 58) {
					object.val(object.val()
							+ jQuery.right('00' + date.getHours(), 2) + ':'
							+ jQuery.right('00' + date.getMinutes(), 2) + ':'
							+ jQuery.right('00' + date.getSeconds(), 2));
					return (false);
				}
			}
			return (true);
		} catch (e) {
			jQuery.showErrorDetail(e, 'textDatetimeFormatKeypress');
			return (false);
		}
	}

	/**
	 * フォーマット:日時型
	 *
	 * @param {string}
	 *            target フォーマット対象文字列
	 * @param {object}
	 *            option オプション
	 * @param {string}
	 *            option.date 日付変換タイプ ymd:[yyyy/mm/dd]形式 / ym:[yyyy/mm]形式 /
	 *            md:[mm/dd]形式 [デフォルト:ymd]
	 * @param {string}
	 *            option.time 時刻変換タイプ his:[hh:ii:ss]形式 / hi:[hh:ii]形式 /
	 *            is:[ii:ss]形式 [デフォルト:his]
	 * @param {string}
	 *            option.hour フォーマット 12h:12時間表記 : 24h:24時間表記 [デフォルト:24h]
	 * @return {string} フォーマット後文字列
	 * @public
	 * @requires jQuery.textTimeFormat
	 * @requires jQuery.showErrorDetail
	 * @example var string = $.textTimeFormat('20121231 245959', {date : 'ymd',
	 *          time : 'hi'});
	 */
	jQuery.textDatetimeFormat = function(target, option) {
		try {
			return (_formatConvertDatetime(target, option));
		} catch (e) {
			jQuery.showErrorDetail(e, 'textDatetimeFormat');
			return ('');
		}
	};

	/**
	 * フォーマット:日時形式
	 *
	 * @param {string}
	 *            target フォーマット対象の文字列
	 * @param {object}
	 *            option オプション
	 * @param {string}
	 *            option.date 日付変換タイプ ymd:[yyyy/mm/dd]形式 / ym:[yyyy/mm]形式 /
	 *            md:[mm/dd]形式 [デフォルト:ymd]
	 * @param {string}
	 *            option.time 時刻変換タイプ his:[hh:ii:ss]形式 / hi:[hh:ii]形式 /
	 *            is:[ii:ss]形式 [デフォルト:his]
	 * @param {string}
	 *            option.hour フォーマット 12h:12時間表記 : 24h:24時間表記 [デフォルト:24h]
	 * @return {string} フォーマット後の文字列
	 * @private
	 * @requires _formatConvertDate
	 * @requires _formatConvertTime
	 * @requires jQuery.mbTrim
	 * @requires jQuery.showErrorDetail
	 * @example var string = _formatConvertTime('20121231 245959', {date :
	 *          'ymd', time : 'hi'});
	 */
	function _formatConvertDatetime(target, option) {
		try {
			var param = new Object();
			var key = null;
			param['date'] = 'ymd';
			param['time'] = 'his';
			param['hour'] = '24h';
			for (key in option) {
				if (key in param) {
					param[key] = option[key];
				}
			}
			//
			target = jQuery.mbTrim(target);
			target = target.replace(/　/g, ' ');
			//
			var split = target.split(' ');
			var i = 0;
			var j = 0;
			var len = split.length;
			var date = '';
			var time = '';
			//
			for (i = 0; i < len; i++) {
				if (split[i] !== '') {
					date = split[i];
					for (j = i + 1; j < len; j++) {
						if (split[j] !== '') {
							time = split[j];
							break;
						}
					}
					break;
				}
			}
			if (time === '') {
				time = '00:00:00';
			}
			//
			date = _formatConvertDate(date, param['date']);
			if (date === '') {
				return ('');
			}
			time = _formatConvertTime(time, param['hour'], param['time']);
			if (time === '') {
				return ('');
			}
			//
			return (date + ' ' + time);
		} catch (e) {
			jQuery.showErrorDetail(e, 'formatConvertDatetime');
			return ('');
		}
	}

	/**
	 * jQuery拡張 フォーマット:バイト長
	 *
	 * @param {integer}
	 *            maxLength 最大長 ※指定されない場合は maxlength 属性
	 * @return {object} jQuery オブジェクト
	 * @public
	 * @requires _textLengthFormatBlur
	 * @requires jQuery.showErrorDetail
	 * @example $('#sample').textLengthFormat(10);
	 */
	jQuery.fn.textLengthFormat = function(maxLength) {
		try {
			// 入力系タグ抽出
			var input = this.find('input[type=text]').add(
					this.filter('input[type=text]')).add(this.find('textarea'))
					.add(this.filter('textarea'));
			//
			input.each(function(index, dom) {
				try {
					jQuery(this).unbind('blur', _textLengthFormatBlur).bind(
							'blur', {
								maxLength : maxLength
							}, _textLengthFormatBlur);
				} catch (e) {
					jQuery.showErrorDetail(e, 'textLengthFormat each');
				}
			});
			//
			return (this.each(function(index, dom) {
			}));
		} catch (e) {
			jQuery.showErrorDetail(e, 'textLengthFormat');
			// エラー時は処理なしで jQuery オブジェクト自体を返却
			return (this.each(function(index, dom) {
			}));
		}
	};

	/**
	 * バイト長フォーマット用イベント (unbind 特定用)
	 *
	 * @param {object}
	 *            event イベント
	 * @public
	 * @requires jQuery.textLengthFormat
	 * @requires jQuery.showErrorDetail
	 * @example $(this).bind('blur', {maxLength : 10}, _textLengthFormatBlur);
	 */
	function _textLengthFormatBlur(event) {
		try {
			var object = jQuery(event['target']);
			var maxlength = event['data']['maxLength'];
			if (maxlength == null) {
				maxlength = object.attr('maxlength');
			}
			var string = jQuery.textLengthFormat(object.val(), maxlength);
			object.val(string);
		} catch (e) {
			jQuery.showErrorDetail(e, 'textLengthFormatBlur');
		}
	}

	/**
	 * フォーマット:バイト長
	 *
	 * @param {string}
	 *            target フォーマット対象文字列
	 * @param {integer}
	 *            maxLength バイト長 (0 以下の場合チェックしない)
	 * @return {string} フォーマット後文字列
	 * @public
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.byteLength
	 * @requires jQuery.mbRTrim
	 * @requires jQuery.fillByByte
	 * @requires jQuery.showErrorDetail
	 * @example var string = $.textLengthFormat('string', 10);
	 */
	jQuery.textLengthFormat = function(target, maxLength) {
		try {
			target = jQuery.castString(target);
			maxLength = jQuery.castNumber(maxLength, false);
			//
			if (maxLength < 1) {
				return (target);
			}
			//
			// バイト長取得
			var length = jQuery.byteLength(target);
			if (length > maxLength) {
				// バイト長が maxLength を越えている場合 -> カット
				target = jQuery.mbRTrim(jQuery.fillByByte(target, maxLength,
						' ', false));
			}
			return (target);
		} catch (e) {
			jQuery.showErrorDetail(e, 'textLengthFormat');
			return ('');
		}
	};

})(jQuery);

/*-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+*/

/**
 * jQuery Plugin [Caret Position]
 *
 * @author Yusuke Kaneko
 * @version 2012.11.01.01
 * @requires jquery-X.X.X.js or jquery-X.X.X.min.js
 */
(function(jQuery) {
	/**
	 * キャレット位置取得
	 *
	 * @param {object}
	 *            dom DOM 要素
	 * @return {integer} キャレット位置
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example var number = _caretPositionGet($(this).get(0));
	 */
	function _caretPositionGet(dom) {
		try {
			var posotion = 0;
			//
			if (typeof (document.selection) !== 'undefined') {
				// document.selection が利用可能なブラウザ
				var range = document.selection.createRange();
				range.moveStart('character', -1 * dom.value.length);
				posotion = range.text.length;
			} else if (typeof (dom.selectionStart) !== 'undefined') {
				// dom.selectionStart が利用可能なブラウザ
				posotion = dom.selectionStart;
			}
			return (posotion);
		} catch (e) {
			jQuery.showErrorDetail(e, 'caretPositionGet');
			return (0);
		}
	}

	/**
	 * キャレット位置設定
	 *
	 * @param {object}
	 *            dom DOM 要素
	 * @param {integer}
	 *            position 設定位置
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example _caretPositionSet($(this).get(0), 0);
	 */
	function _caretPositionSet(dom, position) {
		try {
			dom.focus();
			if (typeof (dom.createTextRange) !== 'undefined') {
				// dom.createTextRange が利用可能なブラウザ
				var range = dom.createTextRange();
				range.collapse(true);
				range.moveEnd('character', position);
				range.moveStart('character', position);
				range.select();
			} else if (typeof (dom.setSelectionRange) !== 'undefined') {
				// dom.setSelectionRange が利用可能なブラウザ
				dom.setSelectionRange(position, position);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'caretPositionSet');
		}
	}

	/**
	 * テキスト全選択
	 *
	 * @param {object}
	 *            dom DOM 要素
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example _caretAllSelect($(this).get(0));
	 */
	function _caretAllSelect(dom) {
		try {
			dom.focus();
			if (typeof (dom.select) !== 'undefined') {
				dom.select();
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'caretAllSelect');
		}
	}

	/**
	 * jQuery拡張 キャレット位置操作
	 *
	 * @param {string}
	 *            method 操作種別 (指定なし:GET / 'f':最初に移動 / 'l':最後に移動 / 'p':前に移動 /
	 *            'n':後ろ移動 / 整数:指定位置に移動)
	 * @return {integer} キャレット位置 (GET 以外の場合は null) (jQuery のメソッドチェーン不可)
	 * @public
	 * @requires jQuery.showErrorDetail
	 * @example $('#sample').caretPosition('n');
	 */
	jQuery.fn.caretPosition = function(method) {
		try {
			var dom = this.get(0);
			//
			if (typeof (method) === 'undefined') {
				// GET
				return (_caretPositionGet(dom));
			} else {
				method = jQuery.castString(method).toLowerCase();
				if (method === 'f') {
					// 先頭
					_caretPositionSet(dom, 0);
				} else if (method === 'l') {
					// 末尾
					_caretPositionSet(dom, dom.value.length);
				} else if (method === 'p') {
					// 前
					_caretPositionSet(dom, _caretPositionGet(dom) - 1);
				} else if (method === 'n') {
					// 次
					_caretPositionSet(dom, _caretPositionGet(dom) + 1);
				} else if (method === 'a') {
					// 全選択
					_caretAllSelect(dom);
				} else {
					if (jQuery.isInteger(method)) {
						_caretPositionSet(dom, jQuery.castString(method, false));
					}
				}
				return (null);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'extend caretPosition');
			return (null);
		}
	};

})(jQuery);

/*-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+*/

/**
 * jQuery Plugin [Text Chenging]
 *
 * @author Yusuke Kaneko
 * @version 2012.11.01.01
 * @requires jquery-X.X.X.js or jquery-X.X.X.min.js
 */
(function(jQuery) {
	/**
	 * Changing の設定
	 *
	 * @param {object}
	 *            object jQuery オブジェクト
	 * @param {mixed}
	 *            event 'set':Changing しないで値変更 / 'destroy':Changing 削除 /
	 *            function:Changing 時に実行する関数
	 * @param {mixed}
	 *            parameter パラメータ 'set':設定する値 / function:Changing 時に実行する関数への引数
	 * @private
	 * @requires _isStarted
	 * @requires _setValue
	 * @requires _startChanging
	 * @requires jQuery.castString
	 * @requires jQuery.showErrorDetail
	 * @example _setChanging($(this), 'set', 'string');
	 */
	function _setChanging(object, event, parameter) {
		try {
			// 入力系タグ抽出
			var input = object.find('input[type=text]').add(
					object.filter('input[type=text]')).add(
					object.find('textarea')).add(object.filter('textarea'));
			//
			if (!_isStarted) {
				return;
			}
			//
			if (event === 'set') {
				// changing イベントを発生させずに値変更
				parameter = jQuery.castString(parameter);
				_setValue(input, parameter);
			} else if (event === 'destroy') {
				// changing イベント登録解除
				_destroy(input);
			} else if (typeof (event) === 'function') {
				input.bind('changing', event);
				input.data('changingLastValue', input.val());
				_startChanging(input, parameter);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'changing setChanging');
		}
	}

	/**
	 * Changing が開始しているかの判定
	 *
	 * @param {object}
	 *            object jQuery オブジェクト
	 * @return {bool} true:開始 / false:未開始
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example if (!_isStarted) { alert('Text changing event has been
	 *          started.'); }
	 */
	function _isStarted(object) {
		try {
			if (typeof (object.data('changingTimerId')) !== 'undefined') {
				// 既に設定済み or changingTimerId が使用済み
				return (true);
			} else {
				return (false);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'changing isStarted');
			return (false);
		}
	}

	/**
	 * Changing させずに値をセット
	 *
	 * @param {object}
	 *            object jQuery オブジェクト
	 * @param {string}
	 *            parameter 設定値
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example _setValue($(this), 'string');
	 */
	function _setValue(object, parameter) {
		try {
			// 先に記憶状態を更新してから値を設定する
			object.data('changingLastValue', parameter).val(parameter);
		} catch (e) {
			jQuery.showErrorDetail(e, 'changing setValue');
		}
	}

	/**
	 * Changing の削除
	 *
	 * @param {object}
	 *            object jQuery オブジェクト
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example _destroy($(this));
	 */
	function _destroy(object) {
		try {
			// タイマーストップ
			clearTimeout(object.data('changingTimerId'));
			// 利用データ削除
			object.removeData('changingTimerId');
			object.removeData('changingLastValue');
			// イベント削除
			object.unbind('changing');
		} catch (e) {
			jQuery.showErrorDetail(e, 'changing destroy');
		}
	}

	/**
	 * Changing の監視
	 *
	 * @param {object}
	 *            object jQuery オブジェクト
	 * @param {mixed}
	 *            parameter パラメータ Changing 時に実行する関数への引数
	 * @private
	 * @requires _isChanged
	 * @requires _execute
	 * @requires _isStarted
	 * @requires _startChanging
	 * @requires jQuery.showErrorDetail
	 * @example _startChanging($(this), 'set');
	 */
	function _startChanging(object, parameter) {
		try {
			object.data('changingTimerId', setTimeout(function() {
				try {
					if (_isChanged(object)) {
						// 変更
						_execute(object, parameter);
					}
					// 再帰呼出
					if (_isStarted(object)) {
						_startChanging(object, parameter);
					}
				} catch (e) {
					jQuery.showErrorDetail(e,
							'changing startChanging setTimeout');
				}
			}, 200));
		} catch (e) {
			jQuery.showErrorDetail(e, 'changing startChanging');
		}
	}

	/**
	 * Changing イベント実行
	 *
	 * @param {object}
	 *            object jQuery オブジェクト
	 * @param {mixed}
	 *            parameter パラメータ Changing 時に実行する関数への引数
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example _execute($(this), arg);
	 */
	function _execute(object, parameter) {
		try {
			// Changing イベント実行
			object.trigger('changing', [ parameter,
					object.data('changingLastValue') ]);
			// 最終値更新
			object.data('changingLastValue', object.val());
		} catch (e) {
			jQuery.showErrorDetail(e, 'changing execute');
		}
	}

	/**
	 * 変更判定
	 *
	 * @param {object}
	 *            object jQuery オブジェクト
	 * @return {bool} true:変更あり / false:変更なし
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example _isChanged($(this));
	 */
	function _isChanged(object) {
		try {
			if (object.data('changingLastValue') !== object.val()) {
				// 変更あり
				return (true);
			} else {
				// 変更なし
				return (false);
			}
		} catch (e) {
			jQuery.showErrorDetail(e, 'changing isChanged');
			return (false);
		}
	}

	/**
	 * Changing の設定
	 *
	 * @param {mixed}
	 *            event 'set':Changing しないで値変更 / 'destroy':Changing 削除 /
	 *            function:Changing 時に実行する関数
	 * @param {mixed}
	 *            parameter パラメータ 'set':設定する値 / function:Changing 時に実行する関数への引数
	 * @return {object} 自身の jQuery オブジェクト
	 * @public
	 * @requires _setChanging
	 * @requires jQuery.showErrorDetail
	 * @example $('#sample').changing('set', 'string');
	 */
	jQuery.fn.changing = function(event, parameter) {
		try {
			return this.each(function(index, dom) {
				try {
					_setChanging(jQuery(this), event, parameter);
				} catch (e) {
					jQuery.showErrorDetail(e, 'changing return');
				}
			});
		} catch (e) {
			jQuery.showErrorDetail(e, 'changing');
			return (this.each(function(index, dom) {
			}));
		}
	};

})(jQuery);

/*-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+*/

/**
 * jQuery Plugin [Cookie]
 *
 * @author Yusuke Kaneko
 * @version 2012.11.01.01
 * @requires jquery-X.X.X.js or jquery-X.X.X.min.js
 */
(function(jQuery) {

	/**
	 * @param {string}
	 *            _cookieNameValidate Cookie NAME 利用不可時のエラーメッセージ
	 * @private
	 */
	var _cookieNameValidate = 'Cookie NAME に利用できない文字が含まれています。';

	/**
	 * Cookie NAME の利用可否確認
	 *
	 * @param {string}
	 *            name クッキー名
	 * @return {bool} true:利用可能 / false:利用不可能
	 * @see RFC2109 に従い、nameには、英数字、いくつかの号 !#$%&'-^~|`*._のみが利用できる
	 *      (英字の大文字小文字は区別される)
	 * @see PHP で Cookie 名に配列を利用する場合を考慮して、[]は利用可能とする
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example var value = _cookieGet('sample');
	 */
	function _cookieNameCheck(name) {
		try {
			// 許可文字を空文字に置換
			var tmp = name.replace(/[\w\!\#\$\%\&\'\~\|\-\^\`\+\*\.\[\]]/g, '');
			if (tmp !== '') {
				alert(_cookieNameValidate + '\n' + tmp);
				return (false);
			}
			return (true);
		} catch (e) {
			jQuery.showErrorDetail(e, 'cookieNameCheck');
			return (false);
		}
	}

	/**
	 * Cookie 取得
	 *
	 * @param {string}
	 *            name クッキー名
	 * @return {string} Cookieの値(name 非存在時は空文字)
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example var value = _cookieGet('sample');
	 */
	function _cookieGet(name) {
		try {
			var object = null;
			var regExp = null;
			var match = null;
			var index = null;
			var cookie = null;
			var i = 0;
			var lenI = 0;
			var j = 0;
			var lenJ = 0;
			var tmp = '';
			//
			cookie = document['cookie'].replace(/\+/g, '%20').split('; ');
			lenI = cookie.length;
			for (i = 0; i < lenI; i++) {
				if (cookie[i].indexOf(name + '=', 0) === 0) {
					// name の完全一致
					try {
						return (eval('('
								+ decodeURIComponent(cookie[i].split('=')[1])
								+ ')'));
					} catch (e) {
						return ('');
					}
				}
				//
				// Cookie の解析 (Cookie 名を配列で登録している場合の対処)
				regExp = new RegExp(name + '(\\[.+\\])=(.*)$');
				match = cookie[i].match(regExp);
				if (match !== null && match.length > 2) {
					if (object === null) {
						object = new Object();
					}
					//
					// ']' で分割して 1 ずつ object に追加
					index = match[1].split(']');
					lenJ = index.length;
					tmp = '';
					for (j = 0; j < lenJ; j++) {
						if (index[j] !== '') {
							index[j] = index[j].replace('[', '');
							if (!(index[j] in object)) {
								// object に key 非存在
								tmp += '[\'' + index[j] + '\']';
								eval('(object' + tmp + ' = new Object())');
							}
						}
					}
					try {
						eval('(object' + tmp + '='
								+ decodeURIComponent(match[2]) + ')');
					} catch (e) {
						eval('(object' + tmp + '=\''
								+ decodeURIComponent(match[2]) + '\')');
					}
				}
			}
			// PHP データとの互換用
			return ((object !== null) ? object : '');
		} catch (e) {
			jQuery.showErrorDetail(e, 'cookieGet');
			return ('');
		}
	}

	/**
	 * Cookie 設定
	 *
	 * @param {string}
	 *            name クッキー名
	 * @param {mixed}
	 *            value クッキーの値
	 * @param {hash}
	 *            option オプション [デフォルト:null]
	 * @param {intger}
	 *            option.expires Cookie の有効期限 現在時刻からの秒数 (マイナス:Cookie 削除 / null
	 *            or 0:セッションが切れるまで) [デフォルト:null]
	 * @param {string}
	 *            option.path 指定したパス以下に対して Cookie が保存される (null:現在のパス)
	 *            [デフォルト:null]
	 * @param {string}
	 *            option.domain Cookie を発行する WEB サーバーの名前 (null:現在のドメイン)
	 *            [デフォルト:null]
	 * @param {bool}
	 *            option.secure サーバー接続がセキュアの時のみ Cookie 情報の取得が可能 true:設定 /
	 *            false:設定しない [デフォルト:false] (※非対応のブラウザあり)
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example _cookieSet('sample', 'sample value', {expires : 10 * 60});
	 */
	function _cookieSet(name, value, option) {
		try {
			value = JSON.stringify(value);
			var parameter = new Array(name + '=' + encodeURIComponent(value),
					((option['expires'] !== '') ? ';expires='
							+ option['expires'] : ''),
					((option['path'] !== '') ? ';path=' + option['path'] : ''),
					((option['domain'] !== '') ? ';domain=' + option['domain']
							: ''), ((option['secure'] === true) ? ';secure'
							: ''));
			document['cookie'] = parameter.join('');
		} catch (e) {
			jQuery.showErrorDetail(e, 'cookieGet');
		}
	}

	/**
	 * Cookie 操作
	 *
	 * @param {string}
	 *            name クッキー名
	 * @param {mixed}
	 *            value クッキーの値 null:クッキーの削除
	 * @param {hash}
	 *            option オプション [デフォルト:null]
	 * @param {intger}
	 *            option.expires Cookie の有効期限 現在時刻からの秒数 (マイナス:Cookie 削除 / null
	 *            or 0:セッションが切れるまで) [デフォルト:null]
	 * @param {string}
	 *            option.path 指定したパス以下に対して Cookie が保存される (null:現在のパス)
	 *            [デフォルト:null]
	 * @param {string}
	 *            option.domain Cookie を発行する WEB サーバーの名前 (null:現在のドメイン)
	 *            [デフォルト:null]
	 * @param {bool}
	 *            option.secure サーバー接続がセキュアの時のみ Cookie 情報の取得が可能 true:設定 /
	 *            false:設定しない [デフォルト:false] (※非対応のブラウザあり)
	 * @return {mixed} GET:Cookieの値(name 非存在時は空文字) / SET:null / CLERA:null
	 * @see RFC2109 に従い、nameには、英数字、いくつかの号 !#$%&'-^~|`*._のみが利用できる
	 *      (英字の大文字小文字は区別される)
	 * @see option.path、option.domain を設定している場合、同じ値を設定しないとクッキーの削除は行なわれない
	 * @public
	 * @requires jQuery.checkCookie
	 * @requires jQuery.castString
	 * @requires jQuery.castNumber
	 * @requires jQuery.showErrorDetail
	 * @example $.cookie('sample', 'sample value', {expires : 10 * 60});
	 */
	jQuery.cookie = function(name, value, option) {
		try {
			if (!jQuery.checkCookie()) {
				alert('Cookie が有効になっていません。');
				return (null);
			}
			//
			name = jQuery.castString(name);
			if (!_cookieNameCheck(name)) {
				return (null);
			}
			//
			// モード判定
			var mode = '';
			if (typeof (value) === 'undefined') {
				mode = 'get';
			} else {
				if (value === null) {
					mode = 'clear';
				} else {
					mode = 'set';
				}
			}
			//
			// 値取得
			if (mode === 'get') {
				return (_cookieGet(name));
			}
			//
			// 引数取得
			var parameter = new Object();
			parameter['expires'] = '';
			parameter['path'] = '';
			parameter['domain'] = '';
			parameter['secure'] = false;
			if (option != null) {
				var key = null;
				for (key in option) {
					if (key in parameter) {
						parameter[key] = option[key];
					}
				}
			}
			// 引数整形
			var datetime = new Date();
			parameter['expires'] = jQuery.castNumber(parameter['expires'],
					false);
			if (mode === 'set') {
				if (parameter['expires'] !== 0) {
					datetime.setTime(datetime.getTime()
							+ (parseInt(parameter['expires'], 10) * 1000));
					parameter['expires'] = datetime.toGMTString();
				} else {
					parameter['expires'] = '';
				}
			} else if (mode === 'clear') {
				datetime.setTime(0);
				parameter['expires'] = datetime.toGMTString();
			}
			parameter['path'] = jQuery.castString(parameter['path']);
			parameter['domain'] = jQuery.castString(parameter['domain']);
			//
			_cookieSet(name, value, parameter);
			//
			return (null);
		} catch (e) {
			jQuery.showErrorDetail(e, 'cookie');
			return (null);
		}
	};

})(jQuery);

/**
 * jQuery Plugin [stopwatch] ※デバッグ時の実行時間計測等で利用
 *
 * @author Yusuke Kaneko
 * @version 2012.11.01.01
 * @requires jquery-X.X.X.js or jquery-X.X.X.min.js
 */
(function(jQuery) {

	/**
	 * @param {object}
	 *            _startTime 開始時刻
	 * @private
	 */
	var _startTime = null;

	/**
	 * @param {object}
	 *            _lastLap 最終ラップ時刻
	 * @private
	 */
	var _lastLap = null;

	/**
	 * @param {integer}
	 *            _stopTime ストップ時のミリ秒
	 * @private
	 */
	var _lastStop = 0;

	/**
	 * リセット
	 *
	 * @public
	 * @example $.stopwatchReset();
	 */
	jQuery.stopwatchReset = function() {
		try {
			_startTime = null;
			_lastLap = null;
			_lastStop = 0;
		} catch (e) {
			jQuery.showErrorDetail(e, 'stopwatchReset');
			return;
		}
	};

	/**
	 * スタート
	 *
	 * @public
	 * @example $.stopwatchStart();
	 */
	jQuery.stopwatchStart = function() {
		try {
			_startTime = new Date();
			_lastLap = _startTime;
		} catch (e) {
			jQuery.showErrorDetail(e, 'stopwatchStart');
			return;
		}
	};

	/**
	 * ラップ
	 *
	 * @param {string}
	 *            type タイプ ms:ミリ秒 / s:秒 / m:分 / h:時 / d:日 [デフォルト:ms]
	 * @return {integer} ラップ時間
	 * @public
	 * @requires jQuery.showErrorDetail
	 * @example alert($.stopwatchLap());
	 */
	jQuery.stopwatchLap = function(type) {
		try {
			if (_startTime === null) {
				// スタートしていない
				return (0);
			}
			var lapTime = new Date();
			var time = lapTime - _lastLap;
			// 最終ラップ時刻更新
			_lastLap = lapTime;
			//
			return (_changeType(time, type));
		} catch (e) {
			jQuery.showErrorDetail(e, 'stopwatchLap');
			return (0);
		}
	};

	/**
	 * ストップ
	 *
	 * @param {string}
	 *            type タイプ ms:ミリ秒 / s:秒 / m:分 / h:時 / d:日 [デフォルト:ms]
	 * @return {integer} ラップ時間
	 * @public
	 * @requires jQuery.showErrorDetail
	 * @example alert($.stopwatchStop('s'));
	 */
	jQuery.stopwatchStop = function(type) {
		try {
			if (_startTime === null) {
				// スタートしていない
				return (0);
			}
			var stopTime = new Date();
			var time = stopTime - _startTime;
			time += _lastStop;
			// ストップ時のミリ秒更新
			_lastStop = time;
			_startTime = null;
			_lastLap = null;
			//
			return (_changeType(time, type));
		} catch (e) {
			jQuery.showErrorDetail(e, 'stopwatchStop');
			return (0);
		}
	};

	/**
	 * ミリ秒の単位変換
	 *
	 * @param {integer}
	 *            time 時間 (ミリ秒)
	 * @param {string}
	 *            type タイプ ms:ミリ秒 / s:秒 / m:分 / h:時 / d:日 [デフォルト:ms]
	 * @return {integer} 時間
	 * @private
	 * @requires jQuery.showErrorDetail
	 * @example _changeType(1234, 's');
	 */
	function _changeType(time, type) {
		try {
			time = parseInt(time, 10);
			//
			switch (type) {
			case 's':
				time = parseInt(time / 1000, 10);
				break;
			case 'm':
				time = parseInt(time / (1000 * 60), 10);
				break;
			case 'h':
				time = parseInt(time / (1000 * 60 * 60), 10);
				break;
			case 'd':
				time = parseInt(time / (1000 * 60 * 60 * 24), 10);
				break;
			default:
				break;
			}
			//
			return (time);
		} catch (e) {
			jQuery.showErrorDetail(e, 'changeType');
			return (0);
		}
	}

	/**
	 * フォーマット
	 *
	 * @param {integer}
	 *            time 時間 (ミリ秒)
	 * @return {hash} {day: 日, hour: 時, min: 分, sec: 秒, msec: ミリ秒, time: 時刻}
	 * @private
	 * @requires jQuery.castNumber
	 * @requires jQuery.right
	 * @requires jQuery.showErrorDetail
	 * @example _changeType(0, true);
	 */
	jQuery.stopwatchFormat = function(time) {
		try {
			var format = new Object;
			format['time'] = '';
			format['msec'] = 0;
			format['sec'] = 0;
			format['min'] = 0;
			format['hour'] = 0;
			format['day'] = 0;
			//
			time = jQuery.castNumber(time, false);
			//
			format['msec'] = time % 1000;
			time -= format['msec'];
			time = parseInt(time / 1000, 10);
			//
			format['sec'] = time % 60;
			time -= format['sec'];
			time = parseInt(time / 60, 10);
			//
			format['min'] = time % 60;
			time -= format['min'];
			time = parseInt(time / 60, 10);
			//
			format['hour'] = time % 24;
			time -= format['hour'];
			time = parseInt(time / 24, 10);
			//
			format['day'] = time;
			//
			format['time'] = 24 * format['day'] + format['hour'] + ':';
			format['time'] += jQuery.right('00' + format['min'], 2) + ':';
			format['time'] += jQuery.right('00' + format['sec'], 2) + '.';
			format['time'] += jQuery.right('000' + format['msec'], 3);
			//
			return (format);
		} catch (e) {
			jQuery.showErrorDetail(e, 'changeType');
			return ({
				day : 0,
				hour : 0,
				min : 0,
				sec : 0,
				msec : 0,
				time : ''
			});
		}
	};

})(jQuery);
