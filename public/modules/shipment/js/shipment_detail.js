/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/06/09
 * 作成者		:	DuyTP
 *
 * 更新日		: 	2018/02/28
 * 更新者		: 	KhaDV - ANS831
 * 更新内容		: 	New development
 *
 * @package		:	INVOICE
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

 $(document).ready(function () {
	initEvents();
	initCombobox();
	if (mode != 'I') {
		$("#TXT_fwd_no").addClass("required");
	} else {
		disableFwdNo();
		$("#TXT_fwd_no").removeClass("required");
		$("#TXT_fwd_no").val('');
	}
	_initRowTable('table-shipment-detail', 'table-row', 1);
	
	$('.out_warehouse_div').text(_constVal1['out_warehouse_div']);
	$('.TXT_mode').text(mode);
});

function initCombobox() {
	// _getComboboxData('JP', 'forwarding_div');
	// _getComboboxData('JP', 'forwarding_way_div');
	// _getComboboxData('JP', 'forwarding_warehouse_div');
	// _getComboboxData('JP', 'forwarder_div');
	// _getComboboxData('JP', 'packing_method_div', function(){
		if(mode == 'U' && $('#TXT_fwd_no').val() != ''){
			$('#TXT_fwd_no').trigger('change');
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
		disableInputMode();
		//drap and drop row table
		_dragLineTable('table-shipment', true);
		//init back
		$(document).on('click', '#btn-back', function () {
			if (from == 'ShipmentSearch') {
				sessionStorage.setItem('detail', true);
				location.href = '/shipment/shipment-search';
			}
		});

		// clear row table
		$(document).on('click','.BTN_clear',function(e){
			var status = $('.STT_temp').text().trim();
			if(status == 10){
				// var obj   = $(this);
				// obj.parent().find('.TXT_instructed_amount').val(0);
				// obj.parent().find('.list_serial').text('');
				// calculateRemaining();
				var obj   = $(this);
				jMessage('C002', function(r) {
					if(r) {
						obj.closest('tr').remove();
						_updateTable('table-shipment-detail', true);
					}
				});
			} 
		});

		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				_clearErrors();
				if (mode != 'I') {
					var msg = 'C003';
				} else {
					var msg = 'C001';
				}
				if(!_validate()){
					return;
				}
				if (!validateShipmentDetail()) {
					jMessage('E004',function(r){
						if(r){
							raiseErrorE004();
						}
					});
					return;
				}
				var _row_detail = $('#table-shipment-detail tbody tr').length;
				if(_row_detail = 0) {
					jMessage('E004');
					return;
				}
				if (!checkTheSameSerial()) {
					jMessage('E411',function(r){
						if(r){
							$('#table-shipment-detail .error-item:first').focus();
						}
					});
					return;
				}
				if (mode != 'U'){
					if (validateRemaining(true)) {
						jMessage(msg, function(r) {
							if (r) {
								saveShipmentDetail();
							}
						});
					} 
					else {
						var msg_e016= _text['E016'].replace('{0}', '出荷指示数');
							msg_e016= msg_e016.replace('{1}', '残数');
		            	jMessage_str('E016', msg_e016, function(r){
		            		if(r){
		            			validateRemaining(false)
		            			$('.error-item:first').focus();
		            		}
		            	}, msg_e016);
					}
				} else {
					jMessage(msg, function(r) {
						if (r) {
							saveShipmentDetail();
						}
					});
				}
			} catch (e) {
				alert('#btn-save ' + e.message);
			}
		});

		// button delete
		$(document).on('click', '#btn-delete', function() {
			try {
				if(validateShipment()){
					jMessage('C002', function(r) {
						if (r) {
							deleteShipmentDetail();
						}
					});	
				}		   
			} catch (e) {
				console.log('#btn-delete: ' + e.message);
			}
		});

 		// Change PiNo
		$(document).on('change', '.TXT_pi_no', function() {
			try {
				if( $(".TXT_pi_no").val() != ""){
					referPiNo();
				} else {
					setItemReferReceive({},false);
					_removeErrorStyle($('.TXT_pi_no'));
					_initRowTable('table-shipment-detail', 'table-row', 1);
					$('#TXT_rcv_no').val('');
				}
			} catch (e) {
				alert('change .TXT_pi_no: ' + e.message);
			}
		});

 		//btn-approve
 		$(document).on('click', '#btn-approve', function(){
			try {
 				if(validateShipment()){
					jMessage('C005', function(r) {
						if (r) {
							approveShipment();
						}
					});
				}		   
			} catch (e) {
				console.log('#btn-approve' + e.message);
			}
 		});

 		//btn-cancel-approve
 		$(document).on('click', '#btn-cancel-approve', function(){
			try {
 				if(validateShipment()){
 					jMessage('C006', function(r) {
						if (r) {
							cancelApproveShipment();
						}
					});
				}		   
			} catch (e) {
				console.log('#btn-cancel-approve' + e.message);
			}
 		});

 		// Change 受注No
		$(document).on('change', '#TXT_rcv_no', function() {
			try {
				if( $('#TXT_rcv_no').val() != ""){
					$('.TXT_pi_no').val('');
					referReceive();
				} else {
					setItemReferReceive({},false);
					_removeErrorStyle($('#TXT_rcv_no'));
					_initRowTable('table-shipment-detail', 'table-row', 1);
				}
			} catch (e) {
				alert('change #TXT_rcv_no: ' + e.message);
			}
		});

		// Change 仮出荷指示No
		$(document).on('change', '#TXT_fwd_no', function() {
			try {
				referShipment();
			} catch (e) {
				alert('change #TXT_fwd_no: ' + e.message);
			}
		});

		// Change forwarding way div
		$(document).on('change', '#CMB_forwarding_way_div', function() {
			try {
				var item 	= 	$('option:selected', this);
				var div 	= 	'data-ctl1';
				var val 	= 	$('#TXT_forwarding_way_remarks');
				controlOther(item,div,val);
			} catch (e) {
				alert('change #CMB_forwarding_way_div: ' + e.message);
			}
		});

		// Change forwarder div
		$(document).on('change', '#CMB_forwarder_div', function() {
			try {
				var item 	= 	$('option:selected', this);
				var div 	= 	'data-ctl1';
				var val 	= 	$('#TXT_forwarder_remarks');
				controlOther(item,div,val);
			} catch (e) {
				alert('change #CMB_forwarder_div: ' + e.message);
			}
		});

		// Change packing method div
		$(document).on('change', '#CMB_packing_method_div', function() {
			try {
				var item 	= 	$('option:selected', this);
				var div 	= 	'data-ctl1';
				var val 	= 	$('#TXT_packing_method_remarks');
				controlOther(item,div,val);
			} catch (e) {
				alert('change #CMB_packing_method_div: ' + e.message);
			}
		});

		// Change packing method div
		$(document).on('change', '.TXT_instructed_amount', function() {
			try {
				var tr_element = $(this).closest('tr');
				calculateRemaining(tr_element);
			} catch (e) {
				alert('change #TXT_instructed_amount: ' + e.message);
			}
		});
		// Change packing method div
		$(document).on('click', '#CHK_confirmation_div', function() {
			try {
				if($(this).is(':checked')){
					$(this).removeClass('error-item');
					$(this).removeAttr('has-balloontip-message');
				}
			} catch (e) {
				alert('change #CHK_confirmation_div: ' + e.message);
			}
		});

		//btn-issue
 		$(document).on('click', '#btn-issue', function(){
 			try {
 				if(validateShipment()){
					jMessage('C004', function(r) {
						if (r) {
							postPrintList();
						}
					});
				}  	
			} catch (e) {
				alert('#btn-print ' + e.message);
			}
 		});
 		//popup-checklist
 		$(document).on('click', '.popup-checklist', function(){
 			try {
 				$('#CHK_confirmation_div').prop('checked', true);
			} catch (e) {
				alert('popup-checklist' + e.message);
			}
 		});

	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}

/**
 * disabled input flow mode
 *
 * @author		:	Trieunb - 2017/08/28 - create
 * @params		:	null
 * @return		:	null
 */
function disableInputMode() {
	if (mode == 'A' || mode == 'O') {
		_disabldedAllInput();
		$('.remark').attr('disabled', false);
	}
}

/**
 * refer receive No
 *
 * @author      :   ANS831 - 2018/02/28 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function referReceive(){
	try{
		var rcv_cd 	= $('#TXT_rcv_no').val().trim();
	    var data = {
	    	rcv_cd 		: rcv_cd,
	    };
	    $.ajax({
	        type        :   'POST',
	        url         :   '/shipment/shipment-detail/refer-receive',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	var data = {};
				if (res.response) {
					data 	=	res.received_info;
					setItemReferReceive(data,true);
					$('.table-shipment-detail').html(res.received_html);
					_removeErrorStyle($('#TXT_rcv_no'));
				}
				else{
					if(res.error != ''){
						jMessage(res.error,function(r){
							if(r){
								setItemReferReceive(data,false);
								$('#TXT_rcv_no').errorStyle(_text['E005']);
								_removeErrorStyle($('.TXT_pi_no'));
								_initRowTable('table-shipment-detail', 'table-row', 1);
								$('#TXT_rcv_no').focus();
							}
						});
					}else{
						setItemReferReceive(data,false);
						$('#TXT_rcv_no').errorStyle(_text['E005']);
						_removeErrorStyle($('.TXT_pi_no'));
						_initRowTable('table-shipment-detail', 'table-row', 1);
						$('#TXT_rcv_no').focus();
					}
				}
	        },
	    });
	} catch(e) {
        alert('referReceive: ' + e.message)
    }
}

/**
 * refer Pi No
 *
 * @author      :   ANS831 - 2018/02/28 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function referPiNo(){
	try{
		//clear all error
		_clearErrors();
		//get data
		var pi_no 	= $('.TXT_pi_no').val().trim();
	    var data 	= {
	    	pi_no 		: pi_no,
	    };
	    $.ajax({
	        type        :   'POST',
	        url         :   '/shipment/shipment-detail/refer-pi-no',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	var data 	= 	{};
				if (res.response) {
					data 	=	res.received_info;
					$('#TXT_rcv_no').val(data.rcv_no);
					setItemReferReceive(data,true);
					$('.table-responsive').html(res.received_html);
					$('.TXT_pi_no').val(pi_no);
				}
				else{
					if(res.error != ''){
						jMessage(res.error,function(r){
							if(r){
								$('#TXT_rcv_no').val('');
								setItemReferReceive(data,false);
								_initRowTable('table-shipment-detail', 'table-row', 1);
								$('.TXT_pi_no').errorStyle(_text['E005']);
							}
						});
					}else{
						$('#TXT_rcv_no').val('');
						setItemReferReceive(data,false);
						_initRowTable('table-shipment-detail', 'table-row', 1);
						$('.TXT_pi_no').errorStyle(_text['E005']);
					}
				}
	        },
	    });
	} catch(e) {
        alert('referPiNo: ' + e.message)
    }
}

/**
 * disable popup shipment search
 *
 * @author      :   ANS831 - 2018/01/18 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function disableFwdNo()
{ 
	try {
		$('#TXT_fwd_no').attr('disabled',true);
		$('#TXT_fwd_no').parent().addClass('popup-shipment-search')
		$('.popup-shipment-search').find('.btn-search').attr('disabled', true);
		parent.$('.popup-shipment-search').removeClass('popup-shipment-search');
	} catch (e) {
		console.log('disableFwdNo: ' + e.message);
	}
}

/**
 * set item refer Receive
 * 
 * @author : ANS831 - 2017/02/28 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setItemReferReceive(data, flag) {
	try {
		if(flag){
			$('#DSP_cust_nm').html(data.cust_cd + ' ' + data.cust_nm);
			$('#DSP_dest_country_nm').html(data.dest_country_div + ' ' + data.lib_val_nm_j_country);
			$('#DSP_dest_city_nm').html(data.dest_city_div + ' ' + (data.lib_val_nm_j_city != null? data.lib_val_nm_j_city:''));
			if (data.shipment_div != '') {
				$('#CMB_forwarding_way_div option[value='+data.shipment_div+']').prop('selected', true);
			} else {
				$('#CMB_forwarding_way_div option:first').prop('selected', true);
			}
			$('.TXT_pi_no').val(data.pi_no);
			$('.TXT_inside_remarks').val(data.inside_remarks);
			_removeErrorStyle($('.TXT_pi_no'));
		} else {
			$('#CMB_forwarding_way_div option:first').prop('selected', true);
			$('#DSP_cust_nm').html('');
			$('#DSP_dest_country_nm').html('');
			$('#DSP_dest_city_nm').html('');
			$('.TXT_pi_no').val('');
			$('.TXT_inside_remarks').val('');
		}
	} catch (e) {
		alert('setItemReferReceive: ' + e.message);
	}
}
/**
 * refer library country
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function _referCountry(country_div, element_city, element_country, callback, isSearch) {
	try	{
		if (isSearch == undefined) {
			isSearch = false;
		}
		$.ajax({
			type 		: 'GET',
			url 		: '/common/refer/refer-country',
			dataType	: 'json',
			data 		: {country_div : country_div},
			success: function(res) {
				var data = {};
				if (res.response) {
					data 	=	res.data;
					//remove error
                	_removeErrorStyle($(element_country).parents('.popup').find('.country_cd'));
					$(element_country).parents('.popup').find('.country_cd').val(data.country_div);
					$(element_country).parents('.popup').find('.country_nm').text(data.country_nm);
				} else {
					if (!isSearch) {
						$(element_country).parents('.popup').find('.country_cd').val('');
					}
					$(element_country).parents('.popup').find('.country_nm').text('');
				}
				// set blank for city cd and city nm
				$(element_city).parents('.popup').find('.city_cd').val('');
				$(element_city).parents('.popup').find('.city_nm').text('');
				// check callback function
				if (typeof callback == 'function') {
					callback();
				}
			}
		});
	} catch (e) {
		console.log('referCountry: ' + e.message);
	}
}

/**
 * control Other Item 
 *
 * @author      :   ANS831 - 2018/02/28 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function controlOther(item,div,val) {
	try {
		if (item.attr(div) == 1) {
			val.removeAttr('readonly');
		} else {
			val.attr('readonly','true');
			val.val("");
		}
	} catch(e) {
        alert('controlOther: ' + e.message)
    }
}

/**
 * refer shipment detail 
 *
 * @author      :   ANS831 - 2018/02/28 - create
 * @param       : 	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function referShipment(){
	try{
		//clear all error
		_clearErrors();
		//get data
		var fwd_no 	= $('#TXT_fwd_no').val().trim();
	    var data = {
	    	fwd_no 		: fwd_no,
	    	mode 		: mode
	    };
	    $.ajax({
	        type        :   'POST',
	        url         :   '/shipment/shipment-detail/refer-shipment',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	        	$('.heading-btn-group').html(res.button);
	        	var data_rcv = {};
	        	var data_fwd = {};
				if (res.response) {
					$('#operator_info').html(res.header_html);
					data_rcv 	=	res.received_info;
					data_fwd 	=	res.shipment_info;
					setItemReferReceive(data_rcv,true);
					setAllItemShipmentDetail(data_fwd,true);
					if(mode == 'U'){
						$('.table-shipment-detail').html(res.received_html);
						if(data_fwd.fwd_status_div != 10){
							$('.table-shipment-detail').find('.TXT_instructed_amount').attr("readonly",true);
						} 
					}
				} else {
					if ($('#TXT_fwd_no').val() != ''){	
						jMessage('W001',function(r){
							if(r){
								setItemReferReceive(data_rcv,false);
								setAllItemShipmentDetail(data_fwd,false);
								//set status,header = blank
								$('#operator_info').html('');
								_initRowTable('table-shipment-detail', 'table-row', 1);
							}
						});
					} else {
						setItemReferReceive(data_rcv,false);
						setAllItemShipmentDetail(data_fwd,false);
						//set status,header = blank
						$('#operator_info').html('');
						_initRowTable('table-shipment-detail', 'table-row', 1);
					}
				}
	        },
	    });
	} catch(e) {
        alert('referShipment: ' + e.message)
    }
}

/**
 * set All Item Shipment Detail
 * 
 * @author : ANS831 - 2018/02/28 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setAllItemShipmentDetail(data,flag){
	try {
		if(flag){
			// $('#TXT_split_source_shipment').val(data.split_foward_no);
			$('#TXT_deliver_date').val(data.deliver_date);
			$('#TXT_rcv_no').val(data.rcv_no);
			$('.TXT_pi_no').val(data.pi_no);
			$('#TXT_shipping_complete_date').val(data.shipping_complete_date);
			$('#TXT_inside_remarks').val(data.inside_remarks);
			$('#TXT_forwarding_way_remarks').val(data.forwarding_way_remarks);
			$('#TXT_forwarder_remarks').val(data.forwarder_remarks);
			$('#TXT_packing_method_remarks').val(data.packing_method_remarks);
			$('#STT').removeClass('hide');
			$('#DSP_status').text(data.lib_val_nm_j);
			$('#DSP_status_tm').text(data.cre_datetime);
			if (data.confirmation_div == "1"){
				$('#CHK_confirmation_div').prop('checked', true);
			} else {
				$('#CHK_confirmation_div').prop('checked', false);
			}
			// get status to popup
			$('.STT_temp').text(data.fwd_status_div);

			if(data.fwd_status_div != 10){
				disableAllItem(true);
			} else {
				disableAllItem(false);
			}
		} else {
			disableAllItem(false);
			$('#TXT_deliver_date').val('');
			$('#TXT_rcv_no').val('');
			$('.TXT_pi_no').val('');
			$('#TXT_shipping_complete_date').val('');
			$('#TXT_inside_remarks').val('');	
			$('#TXT_forwarding_way_remarks').val('');
			$('#TXT_forwarder_remarks').val('');
			$('#TXT_packing_method_remarks').val('');
			$('#CMB_forwarding_div option:first').prop('selected', true);
			$('#CMB_forwarding_way_div option:first').prop('selected', true);
			$('#CMB_forwarder_div  option:first').prop('selected', true);
			$('#CMB_forwarding_dest_div option:first').prop('selected', true);
			$('#CMB_packing_method_div option:first').prop('selected', true);
			$('#CHK_confirmation_div').prop('checked', false);
			$('#CMB_user_training_check_div option:first').prop('selected', true);
			$('#STT').addClass('hide');
			$('#DSP_status').text('');
			$('#DSP_status_tm').text('');
		}
		if (data.forwarding_div != '') {
			$('#CMB_forwarding_div option[value='+data.forwarding_div+']').prop('selected', true);
		} else {
			$('#CMB_forwarding_div option:first').prop('selected', true);
		}
		if (data.forwarding_way_div != '') {
			$('#CMB_forwarding_way_div option[value='+data.forwarding_way_div+']').prop('selected', true);
			var item 	=	$('#CMB_forwarding_way_div option:selected');
			var div 	= 	'data-ctl1';
			var val 	= 	$('#TXT_forwarding_way_remarks');
			controlOther(item,div,val);
		} else {
			$('#CMB_forwarding_way_div option:first').prop('selected', true);
		}
		if (data.forwarder_div != '') {
			$('#CMB_forwarder_div  option[value='+data.forwarder_div+']').prop('selected', true);
			var item 	=	$('#CMB_forwarder_div option:selected');
			var div 	= 	'data-ctl1';
			var val 	= 	$('#TXT_forwarder_remarks');
			controlOther(item,div,val);
		} else {
			$('#CMB_forwarder_div  option:first').prop('selected', true);
		}
		if (data.forwarding_dest_div != '') {
			$('#CMB_forwarding_dest_div option[value='+data.forwarding_dest_div+']').prop('selected', true);
		} else {
			$('#CMB_forwarding_dest_div option:first').prop('selected', true);
		}
		if (data.packing_method_div != '') {
			$('#CMB_packing_method_div option[value='+data.packing_method_div+']').prop('selected', true);
			var item 	=	$('#CMB_packing_method_div option:selected');
			var div 	= 	'data-ctl1';
			var val 	= 	$('#TXT_packing_method_remarks');
			controlOther(item,div,val);
		} else {
			$('#CMB_packing_method_div option:first').prop('selected', true);
		}
		if (data.user_training_check_div != '') {
			$('#CMB_user_training_check_div option[value='+data.user_training_check_div+']').prop('selected', true);
		} else {
			$('#CMB_user_training_check_div option:first').prop('selected', true);
		}
	} catch (e) {
		alert('setAllItemShipmentDetail: ' + e.message);
	}
}

/**
 * calculate remaining
 * 
 * @author : ANS831 - 2018/03/01 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calculateRemaining($tr_element) {
	try {
		var remain_amount 		= 	$($tr_element).find('.DSP_remain_amount').text().trim();
		var instructed_amount 	= 	$($tr_element).find('.TXT_instructed_amount').val().trim();
		remain_amount 			=   remain_amount.replace(/,/g,'');
		instructed_amount 		=   instructed_amount.replace(/,/g,'');
		remain_amount 			!= 	'' ? parseInt(remain_amount) 		: 0;
		instructed_amount 		!= 	'' ? parseInt(instructed_amount) 	: 0;
		var remaining 			= remain_amount - instructed_amount;
		$($tr_element).find('.DSP_remaining').text(addCommas(remain_amount - instructed_amount).replace('.00',''));
	} catch (e) {
		console.log('calculateRemaining: ' + e.message);
	}
}

/**
 * save shipment detail
 *
 * @author      :   ANS831 - 2018/03/05 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function saveShipmentDetail() {
	try{
	    var data = getData();
	    $.ajax({
	        type        :   'POST',
	        url         :   '/shipment/shipment-detail/save-shipment',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		if (res.error_cd === 'E016') {
	            			var msg_e016= _text['E016'].replace('{0}', '出荷指示数');
							msg_e016= msg_e016.replace('{1}', '残数');
							jMessage_str('E016', msg_e016, function(ok) {
								if (ok) {
		            				fillItemErrors(res.error_list);
		            			}
							}, msg_e016);
	            		} else if (res.error_cd === 'E101'){
	            			var msg_e101= _text['E101'].replace('{0}', res.inv_no);
							jMessage_str('E101', msg_e101, function(ok) {}, msg_e101);
						} else if (res.error_cd === 'E017'){
							jMessage_str('E017',msg_e016, function(ok) {
								if (ok) {
									var arr = [];
					            	for(var k in res.error_serial_list) {
									   arr.push(res.error_serial_list[k]['product_cd']);
									}
		            				raiseErrorE017(arr);
		            			}
							}, msg_e016);
	            		} else {
	            			jMessage(res.error_cd);
	            		}
	            		if(res.error_rcv == 1){
	            			$('#TXT_rcv_no').errorStyle(_text['E005']);
	            		} else {
							_removeErrorStyle($('#TXT_rcv_no'));
	            		}
	            		if(res.error_pi_no == 1){
							$('.TXT_pi_no').errorStyle(_text['E005']);
	            		} else {
							_removeErrorStyle($('.TXT_pi_no'));	
	            		}
	            	} else {
	            		var msg = (mode == 'I') ? 'I001' : 'I003';
	            		jMessage(msg, function(r){
		                	if(r){
		                		mode = 'U';
		                		$('.TXT_mode').text(mode);
		                		var data = {
		                			rcv_no 		: res.fwd_no_h,
		                			mode 		: mode,
		                		};
		                		$('#TXT_fwd_no').val(res.fwd_no_h);
		                		setItemShipmentDetail(mode);
	                			referShipment();
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
        alert('saveShipmentDetail: ' + e.message)
    }
}

/**
 * get data of input
 * 
 * @author : ANS831 - 2018/03/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getData() {
	try {
		var _data 				= [];
		var _fwd_detail_no 		= 1;
		$('.table-shipment-detail tbody tr').each(function(index) {
			var _data_serial 	= [];
			var _no 			= $(this).find('.rcv_detail_no').text().trim();
			var _product_cd 	= $(this).find('.DSP_code').text().trim();
			var _fwd_qty 		= $(this).find('.TXT_instructed_amount').val().trim().replace(/,/g,'');
			//get array from string
			var _list_serial 	= ($(this).find('.list_serial').text().trim()).split("; ");
			_list_serial.forEach(function(entry){
				var _data_serial_no = {"serial_no" : entry};
				_data_serial.push(_data_serial_no);
			});
			//Do not register records whose value is 0
			if(_fwd_qty > 0) {
				var _t_fwd_d = {
					'index_no'		: 	index,
					'fwd_detail_no' : 	_fwd_detail_no,
					'no'			: 	_no,
					'product_cd' 	:	_product_cd,
					'fwd_qty' 		:	_fwd_qty,
					'list_serial'   :   _data_serial
				}
				_data.push(_t_fwd_d);
				_fwd_detail_no++;		
			}
		});

		var STT_data = {
				'mode'							: mode, 
				//key
				'fwd_no'						: $('#TXT_fwd_no').val(),
				//Basic
				'forwarding_div'				: $('#CMB_forwarding_div ').val(),		
				'split_fwd_no'					: '', 
				'deliver_date'					: $('#TXT_deliver_date').val(),
				//Orders received
				'rcv_no'						: $('#TXT_rcv_no').val(),
				'pi_no'							: $('.TXT_pi_no').val(),
				//Other
				'forwarding_way_div'			: $('#CMB_forwarding_way_div').val(),
				'forwarding_way_remarks'		: $('#TXT_forwarding_way_remarks').val(),
				'forwarding_dest_div'			: $('#CMB_forwarding_dest_div').val(),
				'forwarder_div'					: $('#CMB_forwarder_div').val(),
				'forwarder_remarks'				: $('#TXT_forwarder_remarks').val(),
				'packing_method_div'			: $('#CMB_packing_method_div').val(),
				'packing_method_remarks'		: $('#TXT_packing_method_remarks').val(),
				'confirmation_div'				: $('#CHK_confirmation_div').prop('checked') == true? "1" : "0",
				'user_training_check_div'		: $('#CMB_user_training_check_div').val(),
				'shipping_complete_date'		: '',
				'inside_remarks'				: $('#TXT_inside_remarks ').val(),
				'out_warehouse_div'				: _constVal1['out_warehouse_div'],
				//<Line item> data type json
				't_fwd_d' 						: _data,
			};
		return STT_data;
	} catch(e) {
        alert('getData' + e.message)
    }
}

/**
 * delete shipment detail
 * 
 * @author : ANS831 - 2018/03/18 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function deleteShipmentDetail() {
	try {
		var _fwd_no = $('#TXT_fwd_no').val().trim();
		$.ajax({
	        type        :   'POST',
	        url         :   '/shipment/shipment-detail/delete-shipment',
	        dataType    :   'json',
	        data        :   {_fwd_no : _fwd_no},
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		if (res.error_cd === 'E101'){
	            			var msg_e101= _text['E101'].replace('{0}', res.inv_no);
							jMessage_str('E101', msg_e101, function() {}, msg_e101);
	            		} else {
	            			jMessage(res.error_cd);
	            		}
	            	} else {
	            		jMessage('I002', function(r){
		                	if(r){
		                		var data = {};
		                		$('#operator_info').html('');
		                		setAllItemShipmentDetail(data,false);
		                		setItemReferReceive(data,false);
		                		_removeErrorStyle($('.TXT_pi_no'));
		                		_removeErrorStyle($('#TXT_rcv_no'));
		                		_initRowTable('table-shipment-detail', 'table-row', 1);
		                		$('#TXT_fwd_no').val('');
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
	}  catch(e) {
        console.log('deleteShipmentDetail: ' + e.message)
    }
}

/**
 * validate shipment no
 * 
 * @author : ANS831 - 2018/03/09 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function validateShipment() {
	try {
		_clearErrors();
		var error 	= true;
		if ($('#TXT_fwd_no').val() == '') {
			$('#TXT_fwd_no').errorStyle(_MSG_E001);
			error 	= false;
		}
		return error;
	} catch (e) {
		console.log('validateShipment: ' + e.message);
	}
}

/**
 * approve shipment detail
 * 
 * @author : ANS831 - 2018/03/12 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function approveShipment() {
	try {
		var params = {
			fwd_no 		   : $('#TXT_fwd_no').val().trim(),
		}	
		$.ajax({
	        type        :   'POST',
	        url         :   '/shipment/shipment-detail/approve',
	        dataType    :   'json',
	        data        :   params,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		jMessage('I005', function(r){
		                	if(r){
		                		var data = {
		                			fwd_no 		: res.fwd_no,
		                			fwd_status 	: res.fwd_status,
		                			mode 		: mode,
		                		}
		                		referShipment(data);
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
	}  catch(e) {
        console.log('approveShipment: ' + e.message)
    }
}

/**
 * approve cancel shipment detail
 * 
 * @author : ANS831 - 2018/03/12 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function cancelApproveShipment() {
	try {
		var params = {
			fwd_no 		   : $('#TXT_fwd_no').val().trim(),
		}
		$.ajax({
	        type        :   'POST',
	        url         :   '/shipment/shipment-detail/approve-cancel',
	        dataType    :   'json',
	        data        :   params,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		if (res.error_cd === 'E101'){
	            			var msg_e101= _text['E101'].replace('{0}', res.inv_no);
							jMessage_str('E101', msg_e101, function() {
							}, msg_e101);
	            		} else {
	            			jMessage(res.error_cd);
	            		}
	            	} else {
	            		jMessage('I006', function(r){
		                	if(r){
		                		var data = {
		                			fwd_no 		: res.fwd_no,
		                			fwd_status 	: res.fwd_status,
		                			mode 	 	: mode,
		                		}
		                		referShipment(data);
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
        console.log('cancelApproveShipment: ' + e.message)
    }
}

/**
 * validate shipment detail
 *
 * @author      :   ANS831 - 2018/03/15 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function validateShipmentDetail() {
	try {
		_clearErrors();
		var detail 	= $('#table-shipment-detail tbody tr');
		var error 	= 1;
		detail.each(function() {
			var instructed  = $(this).find('.TXT_instructed_amount').val().trim().replace(/,/g,'');
			instructed = parseInt(instructed);
			var code  = $(this).find('.DSP_code').text();
			//check valid all item list
			if (instructed != '' && instructed > 0 && code != ''){
				error 	= 0;
			}
		});
		if( error != 0 ) {
			return false;
		} else {
			return true;
		}
	} catch (e) {
		alert('validateShipmentDetail: ' + e.message);
	}
}

/**
 * validate remaining
 *
 * @author      :   ANS831 - 2018/03/15 - create
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   
 */
function validateRemaining(is_e016) {
	try {
		var detail 	= $('#table-shipment-detail tbody tr');
		var error 	= 0;
		detail.each(function() {
			//今回指示数 > 残数
			if (is_e016) {
				if (!ValidateRemainingDetail(this)){
					error++;
				}
			} else {
				ValidateAfterE016RemainingDetail(this);
			}
		});
		if( error > 0 ) {
			return false;
		} else {
			return true;
		}
	} catch (e) {
		alert('validateRemaining: ' + e.message);
	}
}

/**
 * Validate After E016 Remaining Detail 
 * 
 * @author : ANS831 - 2018/03/15 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function ValidateAfterE016RemainingDetail($tr_element) {
	try {
		var remain_amount 		= 	$($tr_element).find('.DSP_remain_amount').text().replace(/,/g,'');
		var instructed_amount 	= 	$($tr_element).find('.TXT_instructed_amount').val().replace(/,/g,'');
		remain_amount 			!= 	'' ? parseInt(remain_amount) 		: 0;
		instructed_amount 		!= 	'' ? parseInt(instructed_amount) 	: 0;
		var remaining 			= 	remain_amount - instructed_amount;
		//指示数 > 残数
		if(remaining >= 0) {
			$($tr_element).find('.TXT_instructed_amount').val();
		} else {
			// show msg E016: 出荷指示数は残数 より大きい値を登録できません。
			var msg_e016	= _text['E016'].replace('{0}', '出荷指示数');
			msg_e016	= msg_e016.replace('{1}', '残数');
    		$($tr_element).find('.TXT_instructed_amount').errorStyle(msg_e016);
		}
	} catch (e) {
		console.log('ValidateAfterE016RemainingDetail: ' + e.message);
	}	
}

/**
 * validate remaining detail 
 * 
 * @author : ANS831 - 2018/03/15 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function ValidateRemainingDetail($tr_element) {
	try {
		var remain_amount 		= 	$($tr_element).find('.DSP_remain_amount').text().replace(/,/g,'');
		var instructed_amount 	= 	$($tr_element).find('.TXT_instructed_amount').val().replace(/,/g,'');
		remain_amount 			!= 	'' ? parseInt(remain_amount) 		: 0;
		instructed_amount 		!= 	'' ? parseInt(instructed_amount) 	: 0;
		var remaining 			= 	remain_amount - instructed_amount;
		//指示数 > 残数
		if(remaining >= 0) {
			return true;
		} else {
			return false;
		}
	} catch (e) {
		console.log('calculateRemaining: ' + e.message);
	}
}

/**
 * raise Error E004
 *
 * @author      :   ANS831 - 2018/03/15 - create
 * @param       :  	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function raiseErrorE004() {
 try {
  	$('#table-shipment-detail tbody tr').each(function() {
	   var instructed_amount = $(this).find('.TXT_instructed_amount').val().trim();
	   if(instructed_amount <= 0 ){
	    	$(this).find('.TXT_instructed_amount').errorStyle(_text['E004']);
   		}
  	});   
	$('#table-shipment-detail tbody tr .error-item:first').focus();
    } catch (e) {
        alert('raiseErrorE004: ' + e.message);
    }
}

/**
 * disable all item
 *
 * @author      :   ANS831 - 2018/03/20 - create
 * @param       :  	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function disableAllItem(flag) {
 try {
		if(flag && mode == 'U'){
			$(':input').attr("disabled",true);
			// $('#TXT_fwd_no').parent().find(':input').removeAttr("disabled");
			$('#TXT_deliver_date').parent().addClass('btn_deliver_date');
			$('.btn_deliver_date').find('.ui-datepicker-trigger').on('click', function(e) {
				$('#ui-datepicker-div').css('display', 'none');
			});
			parent.$('.btn_deliver_date').removeClass('btn_deliver_date');
			
			//$('#TXT_shipping_complete_date').parent().addClass('btn_shipping_complete_date');
			//$('.btn_shipping_complete_date').find('.ui-datepicker-trigger').on('click', function(e) {
			//	$('#ui-datepicker-div').css('display', 'none');
			//});
			//parent.$('.btn_shipping_complete_date').removeClass('btn_shipping_complete_date');
	  		$('#TXT_inside_remarks').removeAttr('disabled');
		} else if (mode == 'U'){
			$(':input').removeAttr("disabled",true);
			$('#TXT_deliver_date').parent().addClass('btn_deliver_date');
			$('.btn_deliver_date').find('.ui-datepicker-trigger').on('click', function(e) {
				$('#ui-datepicker-div').css('display', 'block');
			});
			parent.$('.btn_deliver_date').removeClass('btn_deliver_date');

			//$('#TXT_shipping_complete_date').parent().addClass('btn_shipping_complete_date');
			//$('.btn_shipping_complete_date').find('.ui-datepicker-trigger').on('click', function(e) {
			//	$('#ui-datepicker-div').css('display', 'block');
			//});
			//parent.$('.btn_shipping_complete_date').removeClass('btn_shipping_complete_date');
		}	
    } catch (e) {
        alert('disableAllItem: ' + e.message);
    }
}

/**
 * fill Item Errors
 *
 * @author      :   ANS831 - 2018/03/20 - create
 * @param       :  	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function fillItemErrors(error_list) {
	try {
		// check e005 for table detail pi
		var detail 	= $('#table-shipment-detail tbody tr');
		detail.each(function(index) {
			if ($(this).is(':visible')) {
				var qty 		=	$(this).find('.TXT_instructed_amount');
				$.each(error_list, function(i, item) {
				    if (index == item.index_qty) {
				    	var msg_e016	= _text['E016'].replace('{0}', '出荷指示数');
						msg_e016		= msg_e016.replace('{1}', '残数');
				    	qty.errorStyle(msg_e016);
				    }
				});
			}
		});
	} catch (e) {
		alert('fillItemErrors: ' + e.message);
	}
}
/**
 * print shipment search list
 * 
 * @author : ANS831 - 2018/02/06 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function postPrintList() {
	try {
		var data = {
			fwd_no : $('#TXT_fwd_no').val()
		}			
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/shipment-detail/export-excel',
	        dataType    :   'json',
	        data        :   {fwd_list : data },
			loading		:	true,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
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
         alert('postPrintList' + e.message);
    }
}
/**
 * Check the same serial
 * 
 * @author : ANS831 - 2018/04/23 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function checkTheSameSerial() {
	try {
		var error = 0;
		var arr   = [];	
		var arrayDuplicate = [];

		_clearErrors();

		$('.table-shipment-detail tbody tr').each(function(index) {
			var product_cd 		= $(this).find('.DSP_code').text().trim();
			var serial     		= $(this).find('.list_serial').text().trim();
			if (arr[product_cd] === undefined){
				if (serial != ''){
					arr[product_cd] = '' + serial;
				}
			} else {
				if (serial != ''){
					arr[product_cd] = arr[product_cd] + '; ' + serial;
				}
			}
		});
		for (var product_cd in arr) {
		    var serial_list = arr[product_cd].split("; ");
		    if (!getInputDuplicate(serial_list)){
		    	arrayDuplicate.push(product_cd);
		    }
		}
		if (arrayDuplicate.length !== 0) {
			raiseErrorE017(arrayDuplicate);
			error++;
		}

		return error > 0 ? false : true;
	} catch (e) {
         alert('CheckTheSameSerial' + e.message);
    }
}

/**
 * getInputDuplicate
 *
 * @author      :   ANS831 - 2018/04/23 - create
 * @param       :  	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function getInputDuplicate(serial_list){
	try {
		for (var i = 0; i < serial_list.length - 1; i++) {
			for (var j = i+1; j < serial_list.length; j++) {
			    if (serial_list[i] == serial_list[j]) {
			        return false;
			    }
			}
		}
	    return true;
	} catch(e) {
		console.log('getInputDuplicate: ' + e.toString());
	}
}
/**
 * raiseErrorE017
 *
 * @author      :   ANS831 - 2018/05/02 - create
 * @param       :  	null
 * @return      :   null
 * @access      :   public
 * @see         :   init
 */
function raiseErrorE017(arr){
	try {
		$('.table-shipment-detail tbody tr ').each(function(index,val){
			if ($.inArray($(this).find('.DSP_code').text().trim(), arr) !== -1) {
				$(this).find('.TXT_instructed_amount').errorStyle(_text['E017']);
			}
		});
	} catch(e) {
		console.log('raiseErrorE017: ' + e.toString());
	}
}
/**
 * setItem Shipment Detail
 * 
 * @author : ANS831 - 2018/05/18 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setItemShipmentDetail(mode)
{ 
	try{
		if (mode == 'I') {
			$('#TXT_fwd_no').removeClass("required");
			$('#TXT_fwd_no').attr('disabled','disabled');
			$('#TXT_fwd_no').parent().addClass('popup-fwd-search')
			$('.popup-fwd-search').find('.btn-search').attr('disabled', 'disabled');
		} else {
			$('#TXT_fwd_no').addClass("required");
			$('#TXT_fwd_no').removeAttr('disabled');
			$('#TXT_fwd_no').parent().addClass('popup-fwd-search')
			$('.popup-fwd-search').find('.btn-search').removeAttr('disabled', 'disabled');
		}
	} catch (e) {
         alert('setItemShipmentDetail' + e.message);
	}
}

