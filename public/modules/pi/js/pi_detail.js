/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2017/08/20
 * 作成者		:	Trieunb - ANS806 - trieunb@ans-asia.com
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	PI
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */
 var current_date 	= new Date().toJSON().slice(0,10).replace(/-/g,'/');
 var error_key		=	'E005';
 $(document).ready(function () {
 	initCombobox();
	initEvents();
	var date = $('.TXT_pi_date').val();
	_getTaxRate(date)
	//refer data from screen search to detail
	
	if (mode != 'I' && $(".TXT_pi_no").val() != '') {
		$(".TXT_pi_no").trigger("change");
	} else {
		setItemPiHDel();
	}
	if (mode != 'I') {
		$(".TXT_pi_no").addClass("required");
	} else {
		disablePiNo();
		$(".TXT_pi_no").removeClass("required");
		$(".TXT_pi_no").val('');
	}
	if ((mode == 'I' || mode == 'U') && $(".TXT_pi_no").val() == '') {
		$('.infor-created .heading-elements').addClass('hidden');
	}
	$(".TXT_sign_cd").val(cre_user_cd);
	$(".DSP_sign_nm").text(cre_user_nm);
});

function initCombobox() {
	var name = $('.country_cd').val();
	/*_getComboboxData(name, 'port_country_div');
	_getComboboxData(name, 'port_city_div');
	_getComboboxData(name, 'shipment_div');
	_getComboboxData(name, 'currency_div');
	_getComboboxData(name, 'trade_terms_div');
	_getComboboxData(name, 'payment_conditions_div');
	_getComboboxData(name, 'unit_q_div');
	_getComboboxData(name, 'unit_w_div');
	_getComboboxData(name, 'unit_m_div');
	_getComboboxData(name, 'sales_detail_div');
	_getComboboxData(name, 'bank_div');*/
}
function changeNmCombobox(name) {
	_changeNmCombobox(name, 'port_country_div');
	_changeNmCombobox(name, 'port_city_div');
	_changeNmCombobox(name, 'shipment_div');
	_changeNmCombobox(name, 'currency_div');
	_changeNmCombobox(name, 'trade_terms_div');
	_changeNmCombobox(name, 'payment_conditions_div');
	_changeNmCombobox(name, 'unit_q_div');
	_changeNmCombobox(name, 'unit_w_div');
	_changeNmCombobox(name, 'unit_m_div');
	_changeNmCombobox(name, 'sales_detail_div');
	_changeNmCombobox(name, 'bank_div');

}
/**
 * init Events
 * @author  :   Trieunb - 2017/08/20 - create
 * @param
 * @return
 */
function initEvents() {
	try {
		//init 1 row table at mode add new (I)
		_initRowTable('table-pi', 'table-row', 1, setClassUnitCombobox);
		//drag and drop row table
		_dragLineTable('table-pi', true, setClassUnitCombobox);
		//init back
		$(document).on('click', '#btn-back', function () {
			sessionStorage.setItem('detail', true);
			/*if (from == 'PiSearch') {
				location.href = '/pi/pi-search';
			}*/
			if (from == 'OrderConfirmSearch') {
				location.href = '/order/order-confirm';
			}else{
				location.href = '/pi/pi-search';
			}
		});
		// button save
		$(document).on('click', '#btn-save', function() {
			try {
				if (mode != 'I') {
					var msg = 'C003';
				} else {
					var msg = 'C001';
				}
				if(validate()){
					var _row_detail = $('#table-pi tbody tr').length;
					if(_row_detail > 0) {
						if (!showMsgDetail()) {
							if (!validateDetail()) {
								jMessage('E004', function(r) {
		 							if (r) {
		 								showMsgDetail();
		 							}
		 						});
							}
	 					} else {
	 						if (validateErrorNumericDetail()) {
								jMessage(msg, function(r) {
									if (r) {
										savePi();
									}
								});
							}
	 					}
					} else {
						jMessage('E004');
					}
				}
			} catch (e) {
				alert('#btn-save ' + e.message);
			}
		});	
		// button delete
		$(document).on('click', '#btn-delete', function() {
			try {
				if(validatePiNo()){
					jMessage('C002', function(r) {
						if (r) {
							deletePi();
						}
					});	
				}		   
			} catch (e) {
				alert('#btn-delete ' + e.message);
			}
		});
		//btn print
 		$(document).on('click', '#btn-print', function(){
			if(validatePiNo()) {
				jMessage('C004',  function(r) {
					if (r) {
						piExport();
					}
				});
			}
 		});
		//btn approve
 		$(document).on('click', '#btn-approve', function(){
 			try {
 				if(validatePiNo()){
					jMessage('C005', function(r) {
						if (r) {
							approvePi();
						}
					});
				}		   
			} catch (e) {
				alert('#btn-approve ' + e.message);
			}
 		});
 		//btn cancel approve
 		$(document).on('click', '#btn-cancel-approve', function(){
 			try {
 				if(validatePiNo()){
 					jMessage('C006', function(r) {
						if (r) {
							cancelApprovePi();
						}
					});
				}		   
			} catch (e) {
				alert('#btn-cancel-approve ' + e.message);
			}
 		});
 		//btn btn-copy
 		$(document).on('click', '#btn-copy', function(){
 			try {
 				mode = 'I';
				$('.TXT_pi_no').val('');
				$('.TXT_pi_no').attr('disabled', true);
				$('.TXT_pi_no').removeClass('required');

				$('.TXT_pi_no').parent().addClass('popup-pi-search')
				$('.popup-pi-search').find('.btn-search').attr('disabled', true);
				parent.$('.popup-pi-search').removeClass('popup-pi-search');

				$('.TXT_pi_date').val(current_date);
				
				$('#btn-delete').addClass('hidden');
				$('#btn-print').addClass('hidden');
				$('#btn-approve').addClass('hidden');
				$('#btn-cancel-approve').addClass('hidden');
				$('#btn-upload').addClass('hidden');
				$('#btn-download-csv').addClass('hidden');
				$('#btn-copy').addClass('hidden');
				$('#table-pi tbody tr').find('.DSP_pi_detail_no').addClass('drag-handler');
				disableInputByMode('I');
				//change title PI(見積伝票)作成
				$('title').text('PI(見積伝票)作成');
				$('.panel-title').text('PI(見積伝票)作成');
			} catch (e) {
				alert('#btn-cancel-approve ' + e.message);
			}
 		});
		// show/hide address to
		$(document).on('click', '#show-address-to', function(){
			$(".address-to").toggleClass("hidden");
			if ($(this).text() == '住所非表示') {
				$(this).text('住所表示');
			} else {
				$(this).text('住所非表示');
			}
		});
		// show/hide address from
		$(document).on('click', '#show-address-from', function() {
			$(".address-from").toggleClass("hidden");
			if ($(this).text() == '住所非表示') {
				$(this).text('住所表示');
			} else {
				$(this).text('住所非表示');
			}
		});
		//add row
		$(document).on('click', '#btn-add-row', function () {
			try {
				_addNewRowTable('table-pi', 'table-row', 30, updateTablePiDetail);
			} catch (e) {
				alert('add new row' + e.message);
			}

		});
		// remove row table
		$(document).on('click','#remove-row',function(){
			var obj   = $(this);
			jMessage('C002', function(r) {
				if(r) {
					obj.closest('tr').remove();
					_updateTable('table-pi', true);
					calTotalDetailAmt();
		 			calTotalTaxAmt();
		 			calTotalAmt();
		 			calTotalNetWeight();
		 			calTotalGrossWeight();
		 			calTotalMeasure();
		 			calTotalQty();
		 			$('.table-pi tbody tr:last :input:first').focus();
		 			//
		 			$('.table-pi tbody tr select.CMB_unit_measure_price').removeClass('unit_measure_price');
		 			$('.table-pi tbody tr select.CMB_unit_net_weight_div').removeClass('unit_net_weight_div');
		 			$('.table-pi tbody tr select.CMB_unit_measure_price:first').addClass('unit_measure_price');
		 			$('.table-pi tbody tr select.CMB_unit_net_weight_div:first').addClass('unit_net_weight_div');
		 			$('.unit_measure_price').trigger('change');
		 			$('.unit_net_weight_div').trigger('change');
				}
			});
		});
		//change TXT_pi_no 
		$(document).on('change', '.TXT_pi_no ', function(e) {
			var data = {
				pi_no 		: 	$(this).val(),
				pi_status 	: 	mode,
				mode 		: 	mode
			};
			if (e.isTrigger) {
				referPiDetail(data, function() {
						var lib_val_ctl1 = $('.CMB_trade_terms_div > option:selected').attr('data-ctl5');
				 		var lib_val_ctl2 = $('.CMB_trade_terms_div > option:selected').attr('data-ctl6');
				 		changeTrade(lib_val_ctl1, lib_val_ctl2);
					});
			} else {
				referPiDetail(data, showMessageW001);
			}
		});
		//change TXT_pi_date  
		$(document).on('change', '.TXT_pi_date  ', function(e) {
			var date = $(this).val();
			_getTaxRate(date, calTotalTaxAmt);
		});
		//change TXT_cust_cd 
		$(document).on('change', '.TXT_cust_cd ', function() {
			referSuppliers(true);
		});
		//change TXT_cust_city_div 
		$(document).on('change', '.TXT_cust_city_div', function() {
			var city_div 	=	$(this).val();
			_referCity(city_div, $(this), $('.TXT_cust_country_div'), function() {
				var country_div =	$('.TXT_cust_country_div').val();
				changeNmCombobox(country_div);
				setItemCustCountryDiv(country_div);
				calTotalTaxAmt();
				calTotalAmt();
				_clearValidateMsg();
				$('.CMB_payment_conditions_div').trigger('change');
			}, true);
		});
		// change TXT_cust_country_div
 		$(document).on('change', '.TXT_cust_country_div', function() {
 			var country_div 	=	$(this).val();
 			_referCountry(country_div, $('.TXT_cust_city_div'), $(this), function() {
 				var country_div =	$('.TXT_cust_country_div').val();
 				changeNmCombobox(country_div);
 				setItemCustCountryDiv(country_div);
 				calTotalTaxAmt();
 				calTotalAmt();
 				_clearValidateMsg();
				$('.CMB_payment_conditions_div').trigger('change');

 			}, true);
 		});
		//change TXT_consignee_cd 
		$(document).on('change', '.TXT_consignee_cd ', function() {
			referSuppliers(false);
		});
		//change TXT_consignee_city_div 
		$(document).on('change', '.TXT_consignee_city_div ', function() {
			var city_div 	=	$(this).val();
			_referCity(city_div, $(this), $('.TXT_consignee_country_div'), function() {
				_clearValidateMsg();
			}, true);
		});
		//combobox trade terms
 		$(document).on('change', '.CMB_trade_terms_div', function(){
 			var lib_val_ctl1 = $('option:selected', this).attr('data-ctl5');
 			var lib_val_ctl2 = $('option:selected', this).attr('data-ctl6');
 			changeTrade(lib_val_ctl1, lib_val_ctl2);
 			// _addFreigtAndInsurance(lib_val_ctl1, lib_val_ctl2);
 			// calTotalAmt();
 		});
 		// change TXT_consignee_country_div
 		$(document).on('change', '.TXT_consignee_country_div', function() {
 			var country_div 	=	$(this).val();
 			_referCountry(country_div, $('.TXT_consignee_city_div'), $(this), function() {
 				_clearValidateMsg();
 			}, true);
 		});
 		// change TXT_dest_city_div
 		$(document).on('change', '.TXT_dest_city_div', function() {
 			var city_div 	=	$(this).val();
 			_referCity(city_div, $(this), $('.TXT_dest_country_div'), function() {
 				_clearValidateMsg();
 			}, true);
 		});
 		// change TXT_dest_country_div
 		$(document).on('change', '.TXT_dest_country_div', function() {
 			var country_div 	=	$(this).val();
 			_referCountry(country_div, $('.TXT_dest_city_div'), $(this), '', true);
 		});
 		// change CMB_currency_div
 		$(document).on('change', '.CMB_currency_div', function() {
 			var currency_div = $(this).val();
 			if (currency_div == 'JPY') {
 				$('#table-pi tbody tr').find('.price').addClass('currency_JPY');
 			} else {
 				$('#table-pi tbody tr').find('.price').removeClass('currency_JPY');
 			}
 		});
 		//handle area <数量合計明細>
 		// change TXT_product_cd
 		$(document).on('change', '.TXT_product_cd', function() {
 			var parent = $(this).parents('#table-pi tbody tr');
 			parent.addClass('refer-product-pos');
 			parent.addClass('cal-refer-pos');
 			var pi_date 	= $('.TXT_pi_date').val();
 			var client_cd  	= $('.TXT_cust_cd').val();
 			var data = {
 				'product_cd' 	: 	$(this).val(),
 				'pi_date'       : 	pi_date,
 				'client_cd'     : 	client_cd,
 				'country_cd' 	: 	$('.TXT_cust_country_div').val(),
 				'currency_div'  :   $('.CMB_currency_div').val()
 			}
 			referProduct(data, 'pos', $(this));
 		});
 		//change TXT_qty
 		$(document).on('change', '.TXT_qty', function() {
 			var parents = $(this).parents('#table-pi tbody tr');
 			parents.addClass('cal-refer-pos');
 			//cal amount
 			calAmount('pos');
 			//cal net weight
 			calNetWeight('pos');
 			//cal gross weight
 			calGrossWeight('pos');
 			//cal measure
 			calMeasure('pos');

 			calTotalDetailAmt();
 			calTotalTaxAmt();
 			calTotalAmt();
 			calTotalNetWeight();
 			calTotalGrossWeight();
 			calTotalMeasure();

 			calTotalQty();
 			//remover class parent
			parent.$('.cal-refer-pos').removeClass('cal-refer-pos');
			//validate for input numeric in table detail
			validateAmountDetail($(this));
			validateNetWeightDetail($(this));
			validateGrossWeightDetail($(this));
			validateMeasureDetail($(this));
			validateQtyDetail($(this));

 		});
 		//change TXT_unit_price
 		$(document).on('change', '.TXT_unit_price', function() {
 			var parents = $(this).parents('#table-pi tbody tr');
 			parents.addClass('cal-refer-pos');
 			calAmount('pos');
 			//remover class parent
			parent.$('.cal-refer-pos').removeClass('cal-refer-pos');

 			calTotalDetailAmt();
 			calTotalTaxAmt();
 			calTotalAmt();
 			//validate for input numeric in table detail
 			validateAmountDetail($(this));
			validateQtyDetail($(this));
 		});
 		//change TXT_unit_net_weight
 		$(document).on('change', '.TXT_unit_net_weight', function() {
 			var parents = $(this).parents('#table-pi tbody tr');
 			parents.addClass('cal-refer-pos');
 			calNetWeight('pos');
 			//remover class parent
			parent.$('.cal-refer-pos').removeClass('cal-refer-pos');

 			calTotalNetWeight();
 			//validate for input numeric in table detail
			validateNetWeightDetail($(this));
			validateQtyDetail($(this));
 		});
 		//change TXT_unit_gross_weight
 		$(document).on('change', '.TXT_unit_gross_weight', function() {
 			var parents = $(this).parents('#table-pi tbody tr');
 			parents.addClass('cal-refer-pos');
 			calGrossWeight('pos');
 			//remover class parent
			parent.$('.cal-refer-pos').removeClass('cal-refer-pos');

 			calTotalGrossWeight();
 			//validate for input numeric in table detail
			validateGrossWeightDetail($(this));
			validateQtyDetail($(this));
 		});
 		//change TXT_unit_measure_qty
 		$(document).on('change', '.TXT_unit_measure_qty', function() {
 			var parents = $(this).parents('#table-pi tbody tr');
 			parents.addClass('cal-refer-pos');
 			calMeasure('pos');
 			//remover class parent
			parent.$('.cal-refer-pos').removeClass('cal-refer-pos');

 			calTotalMeasure();
 			//validate for input numeric in table detail
			validateMeasureDetail($(this));
			validateQtyDetail($(this));
 		});
 		
 		//change CMB_unit_net_weight_div
 		$(document).on('change', '.unit_net_weight_div', function() {
 			var unit_nm 		= $(this).find('option:selected').text();
 			var unit_net_weight = $(this).find('option:selected').val();
 			$('.DSP_unit_total_gross_weight_nm').text(unit_nm);
 			$('.DSP_unit_total_gross_weight_div').text(unit_net_weight);
 			$('.DSP_unit_total_net_weight_nm').text(unit_nm);
 			$('.DSP_unit_total_net_weight_div').text(unit_net_weight);
 		});
 		//change CMB_unit_measure_price
 		$(document).on('change', '.unit_measure_price', function() {
 			var unit_measure_nm = $(this).find('option:selected').text();
 			var unit_measure = $(this).find('option:selected').val();
 			$('.DSP_unit_total_measure_nm').text(unit_measure_nm);
 			$('.DSP_unit_total_measure_div').text(unit_measure);
 		});
 		//change TXT_freigt_amt and TXT_insurance_amt
 		$(document).on('change', '.TXT_freigt_amt, .TXT_insurance_amt', function() {
 			calTotalAmt();
 		});
 		//change TXT_sign_cd
 		$(document).on('change', '.TXT_sign_cd', function() {
 			var user_cd 	=	$(this).val();
 			_referUser(user_cd, $(this), '', true);
 		});
 		//btn-approve-estimate
 		$(document).on('click', '#btn-approve-estimate', function(){
			jConfirm('伝票承認してもよろしいですか？', 1, function(r){
				if(r){
					jSuccess('伝票承認しました。');
				}
			});
 		});
 		$(document).on('focusout', '.TXT_unit_price', function() {
	   		var val = $(this).val().trim();
	   		if (val !== '') {
	   			$(this).removeClass('warning-item');
	   		}
	   	});
 		//Import data csv
	    $(document).on('click','#btn-upload',function (e) {
	        jConfirm('取込処理を開始してよろしいですか？', 1, function(r) {
	            if (r) {
	                var input = $('#pi-import');
	                openFile();
	                function openFile()
	                {
	                    input.trigger('click'); // opening dialog
	                    document.body.onfocus = function () { 
	                        setTimeout(checkImport, 100); 
	                        document.body.onfocus = null;
	                    };
	                }
	                function checkImport()
	                {
	                    if (input.val().length > 0) {
	                        var url = "/pi/import-csv";
	                        _ImportCSV(input, url, function(data) {
	                        	var str = '';
	                        	if(data != null && data != undefined) {
				                	for(var i = 0; i < data.length; i++){
				                		str += data[i]+"</br>";
				                	}
				                }
			                	$('#id_import_data').html(str);
			                	$('#md_import_data').modal('show');
			                	$('#close_modal').removeAttr('disabled');
				            });
	                    }
	                }
	            } 
	        });
	    });
	    //download data csv
	    $(document).on('click','#btn-download-csv',function (e) {
	        try {
	            $.ajax({
	                type        :   'GET',
	                url         :   '/pi/download-csv',
	                dataType    :   'json',
	                data        :   '',
	                success: function(res) {
	                    document.location.href = res.file;
	                }
	            });
	        } catch (e){
	            console.log('exportExcel: '+e.message);
	        }
	    });
	    //CMB_payment_conditions_div
	    $(document).on('change','.CMB_payment_conditions_div',function (e) {
	        try {
	           payment_e = $('.CMB_payment_conditions_div').find('option:selected').data('nm-e');
	           $('.TXT_payment_notes').val($.trim($('.CMB_payment_conditions_div option:selected').text()));
	        } catch (e){
	            console.log('CMB_payment_conditions_div: '+e.message);
	        }
	    });
	    $(document).on('click','.btn-clear-info', function(e){
	    	try {
	    		$('.TXT_consignee_cd').val('');
	    		$('.TXT_consignee_nm').val('');
	    		$('.address-from').find(':input').val('');
	    		$('.address-from').find('.DSP_consignee_city_nm').text('');
	    		$('.address-from').find('.DSP_consignee_country_nm').text('');
	    		$('.TXT_consignee_cd').focus();
	    	} catch(e) {
	    		console.log('btn-clear-info: '+e.message);
	    	}
	    })
	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}
/**
 * validate
 *
 * @author		:	Trieunb - 2017/10/10 - create
 * @params		:	null
 * @return		:	null
 */
function validate(){
	try {
		var element = $('body');
		var error = 0;
		_clearErrors();
		element.find('.required:not([readonly])').each(function() {
			if(($(this).is("input") || $(this).is("textarea")) &&  $.trim($(this).val()) == '' ) {
				$(this).errorStyle(_MSG_E001);
				error ++;
			}else if( $(this).is("select") &&  ($(this).val() == '' || $(this).val() == undefined) ) {
				$(this).errorStyle(_MSG_E001);
				error ++;
			}else if($(this).is("input[type=checkbox]") && !$(this).is(":checked")){
                $(this).errorStyle(_MSG_E001);
                error ++;
            }
		});

		element.find('input.email:enabled:not([readonly])').each(function(){
			if(!_validateEmail($(this).val())){
				$(this).errorStyle(_text['E015']);
				error++;
			}
		});
		element.find('input.fax:enabled:not([readonly])').each(function(){
		    if(!_validatePhoneFaxNumber($(this).val())){
		        $(this).errorStyle(_text['E015']);
		        error++;
		    }
		});
		if ($('.address-to').hasClass('hidden') && $('.address-to').find('.error-item').length > 0) {
			// $('#show-address-to').trigger('click');
			$('.address-to').removeClass('hidden');
		}
		if ($('.address-from').hasClass('hidden') && $('.address-from').find('.error-item').length > 0) {
			// $('#show-address-from').trigger('click');
			$('.address-from').removeClass('hidden');
		}

		$(document).find('.error-item:first').focus();

		if( error > 0 ) {
			return false;
		} else {
			return true;
		}
	} catch (e) {
		alert('validate: ' + e.message);
	}
}
/**
 * disabled input flow mode
 *
 * @author		:	Trieunb - 2017/08/28 - create
 * @params		:	null
 * @return		:	null
 */
function disableInputByMode(mode_status) {
	if (mode_status == 'A' || mode_status == 'O' || mode_status == 'L') {
		_disabldedAllInput();
		$('input[type=file]').attr('disabled', false);
		$('.remarks').attr('disabled', false);
		$('#show-address-to').attr('disabled', false);
		$('#show-address-from').attr('disabled', false);
	} else {
		$(":input").each(function (i) {
			if (!$(this).hasClass('TXT_amount') && 
				!$(this).hasClass('TXT_net_weight') && 
				!$(this).hasClass('TXT_gross_weight') && 
				!$(this).hasClass('TXT_measure')) {
				$(this).prop('disabled', false);
			}
		});
		_formatDatepicker();
	}
	if (mode == 'I') {
		disablePiNo();
	}
}
/**
 * refer pi infomation
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referPiDetail(data, callback) {
	//clear all error
	_clearErrors();
	//
	try	{
		$.ajax({
			type 		: 'GET',
			url 		: '/pi/refer-pi-detail',
			dataType	: 'json',
			data 		: data,
			success: function(res) {
				if (res.response) {
					$('.heading-btn-group').html(res.button);
					$('#div-table-pi').html(res.html_pi_d);
					setItemPiH(res.pi_h);
					var date = $('.TXT_pi_date').val();
					_getTaxRate(date)
					var name 	=	$('.TXT_cust_country_div').val();

					/*_getComboboxData(name, 'unit_q_div',setSelectCombobox);
					_getComboboxData(name, 'unit_w_div',setSelectCombobox);
					_getComboboxData(name, 'unit_m_div',setSelectCombobox);
					_getComboboxData(name, 'sales_detail_div', setSelectCombobox);*/

					changeNmCombobox(name);
					_setTabIndex();
					//set tabindex
					_setTabIndexTable('table-pi');
					//drap and drop row table
					_dragLineTable('table-pi', true, setClassUnitCombobox);
					var param = {
						'mode'		: mode,
						'from'		: 'PiDetail',
						'pi_no'		: data.pi_no,
					};
					_postParamToLink(from, 'PiDetail', '', param)
					_clearErrors();
					$('.infor-created .heading-elements').removeClass('hidden');
					setSelectCombobox();
					//change title PI-受注登録
					$('title').text('PI-受注登録');
					$('.panel-title').text('PI-受注登録');
				} else {
					setItemPiHDel(data.pi_no);
					if ($('.TXT_pi_no').val() !== '') {
						jMessage('W001');
					}
				}
				if (typeof callback == 'function') {
					callback();
				}
				//disable input by mode
				disableInputByMode(res.status);
				//set tabindex
				_setTabIndex();
				//set tabindex for table pi detail
				_setTabIndexTable('table-pi');


				//Kha-ANS342-20180517
				//set drag handler for table-pi
				if (res.status != 'R') {
					$('#table-pi tbody tr').find('.drag-handler').removeClass('drag-handler');
				} 
				else {
					$('#table-pi tbody tr').find('.DSP_pi_detail_no').addClass('drag-handler');
				}
			}
		});
	} catch (e) {
		alert('referPiDetail: ' + e.message);
	}
}
function setItemPiH(data) {
	try {
		if (mode == 'I') {
			// $('.TXT_pi_no').val('');
			$('.TXT_pi_no').attr('disabled', true);
			$('.TXT_pi_no').parent().addClass('popup-pi-search')
			$('.popup-pi-search').find('.btn-search').attr('disabled', true);
			parent.$('.popup-pi-search').removeClass('popup-pi-search');
			$(".TXT_pi_no").removeClass("required");
		} else {
			// $('.TXT_pi_no').val(data.pi_no);
			$('.TXT_pi_no').attr('disabled', false);
			$('.TXT_pi_no').parent().addClass('popup-pi-search')
			$('.popup-pi-search').find('.btn-search').attr('disabled', false);
			parent.$('.popup-pi-search').removeClass('popup-pi-search');
			$(".TXT_pi_no").addClass("required");
		}
		//<共通>set
		$('.TXT_pi_status').val(data.pi_status_div);
		$('.TXT_pi_no').val(data.pi_no);
		$('#DSP_cre_user_cd').text(data.cre_user_cd +' '+ data.cre_user_nm);
		$('#DSP_cre_datetime').text(data.cre_datetime);
		$('#DSP_upd_user_cd').text(data.upd_user_cd +' '+ data.upd_user_nm);
		$('#DSP_upd_datetime').text(data.upd_datetime);

		// $('.TXT_pi_no').val(data.pi_no);
		$('.TXT_pi_date').val(data.pi_date);
		$('.DSP_status').text(data.pi_status_nm);
		$('.DSP_pi_status_cre_datetime').text(data.pi_status_cre_datetime);
		//cust
		$('.TXT_cust_cd').val(data.cust_cd);
		$('.TXT_cust_nm').val(data.cust_nm);
		$('.TXT_cust_adr1').val(data.cust_adr1);
		$('.TXT_cust_adr2').val(data.cust_adr2);
		$('.TXT_cust_zip').val(data.cust_zip);
		$('.TXT_cust_city_div').val(data.cust_city_div);
		$('.DSP_cust_city_nm').text(data.cust_city_nm);
		$('.TXT_cust_country_div').val(data.cust_country_div);
		_addTaxRate(data.cust_country_div);
		$('.DSP_cust_country_nm').text(data.cust_country_nm);
		$('.TXT_cust_tel').val(data.cust_tel);
		$('.TXT_cust_fax').val(data.cust_fax);
		$('.TXT_cust_fax').val(data.cust_fax);
		//consignee
		$('.TXT_consignee_cd').val(data.consignee_cd);
		$('.TXT_consignee_nm').val(data.consignee_nm);
		$('.TXT_consignee_adr1').val(data.consignee_adr1);
		$('.TXT_consignee_adr2').val(data.consignee_adr2);
		$('.TXT_consignee_zip').val(data.consignee_zip);
		$('.TXT_consignee_city_div').val(data.consignee_city_div);
		$('.DSP_consignee_city_nm').text(data.consignee_city_nm);
		$('.TXT_consignee_country_div').val(data.consignee_country_div);
		$('.DSP_consignee_country_nm').text(data.consignee_country_nm);
		$('.TXT_consignee_tel').val(data.consignee_tel);
		$('.TXT_consignee_fax').val(data.consignee_fax);
		$('.TXT_consignee_fax').val(data.consignee_fax);

		$('.TXT_shipping_mark_1').val(data.mark1);
		$('.TXT_shipping_mark_2').val(data.mark2);
		$('.TXT_shipping_mark_3').val(data.mark3);
		$('.TXT_shipping_mark_4').val(data.mark4);
		$('.TXT_packing').val(data.packing);

		if (data.shipment_div != '') {
			$('.CMB_shipment_div option[value='+data.shipment_div+']').prop('selected', true);
		} else {
			$('.CMB_shipment_div option:first').prop('selected', true);
		}
		if (data.currency_div != '') {
			$('.CMB_currency_div option[value='+data.currency_div+']').prop('selected', true);
		} else {
			$('.CMB_currency_div option:first').prop('selected', true);
		}
		if (data.port_city_div != '') {
			$('.CMB_port_city_div option[value='+data.port_city_div+']').prop('selected', true);
		} else {
			$('.CMB_port_city_div option:first').prop('selected', true);
		}
		if (data.port_country_div != '') {
			$('.CMB_port_country_div option[value='+data.port_country_div+']').prop('selected', true);
		} else {
			$('.CMB_port_country_div option:first').prop('selected', true);
		}
		if (data.trade_terms_div != '') {
			$('.CMB_trade_terms_div option[value='+data.trade_terms_div+']').prop('selected', true);
		} else {
			$('.CMB_trade_terms_div option:first').prop('selected', true);
		}
		
		$('.TXT_dest_city_div').val(data.dest_city_div);
		$('.DSP_dest_city_nm').text(data.dest_city_nm);
		$('.TXT_dest_country_div').val(data.dest_country_div);
		$('.DSP_dest_country_nm').text(data.dest_country_nm);

		//<数量合計明細>
		$('.DSP_total_qty').text(data.total_qty.replace(/\.00$/,''));

		$('.DSP_total_gross_weight').text(data.total_gross_weight.replace(/\.00$/,''));
		$('.DSP_unit_total_gross_weight_nm').text(data.unit_total_gross_weight_nm);
		$('.DSP_unit_total_gross_weight_div').text(data.unit_total_gross_weight_div);

		$('.DSP_total_net_weight').text(data.total_net_weight.replace(/\.00$/,''));
		$('.DSP_unit_total_net_weight_nm').text(data.unit_total_gross_weight_nm);
		$('.DSP_unit_total_net_weight_div').text(data.unit_total_net_weight_div);

		$('.DSP_total_measure').text(data.total_measure.replace(/\.00$/,''));
		$('.DSP_unit_total_measure_nm').text(data.unit_total_measure_nm);
		$('.DSP_unit_total_measure_div').text(data.unit_total_measure_div);

		//<金額合計>
		$('.DSP_total_detail_amt').text(data.total_detail_amt.replace(/\.00$/,''));
		$('.TXT_freigt_amt').val(data.freigt_amt.replace(/\.00$/,''));
		$('.TXT_insurance_amt').val(data.insurance_amt.replace(/\.00$/,''));
		$('.TXT_freigt_amt').val(data.freigt_amt.replace(/\.00$/,''));
		$('.DSP_tax_amt').text(data.total_detail_tax.replace(/\.00$/,''));
		$('.DSP_total_amt').text(data.total_amt.replace(/\.00$/,''));

		if (data.payment_conditions_div != '') {
			$('.CMB_payment_conditions_div option[value='+data.payment_conditions_div+']').prop('selected', true);
		} else {
			$('.CMB_payment_conditions_div option:first').prop('selected', true);
		}
		
		$('.TXT_payment_notes').val(data.payment_notes);

		$('.TXT_time_of_shipment').val(data.time_of_shipment);
		if (data.bank_div != '') {
			$('.CMB_bank option[value='+data.bank_div+']').prop('selected', true);
		} else {
			$('.CMB_bank option:first').prop('selected', true);
		}
		
		$('.TXT_country_of_origin').val(data.country_of_origin);
		$('.TXT_manufacture').val(data.manufacture);
		$('.TXT_varidity').val(data.pi_validity);
		$('.TXT_other_conditions1').val(data.other_conditions1);
		$('.TXT_other_conditions2').val(data.other_conditions2);
		$('.TXT_other_conditions3').val(data.other_conditions3);
		$('.TXT_other_conditions4').val(data.other_conditions4);
		$('.TXT_other_conditions5').val(data.other_conditions5);
		$('.TXT_other_conditions6').val(data.other_conditions6);
		$('.TXT_other_conditions7').val(data.other_conditions7);
		$('.TXT_other_conditions8').val(data.other_conditions8);
		$('.TXT_other_conditions9').val(data.other_conditions9);
		$('.TXT_other_conditions10').val(data.other_conditions10);
		$('.TXT_sign_cd').val(data.sign_user_cd);
		$('.DSP_sign_nm').text(data.sign_user_nm);
		$('.TXA_inside_remarks').val(data.inside_remarks);
		//
		var currency_div = $('.currency_div').find('option:selected').val();
		if (currency_div == 'JPY') {
			$('#table-pi tbody tr').find('.price').addClass('currency_JPY');
		} else {
			$('#table-pi tbody tr').find('.price').removeClass('currency_JPY');
		}
	} catch (e) {
		alert('setItemPiH: ' + e.message);
	}
}
/**
 * refer cust m client
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referSuppliers(flag) {
	try	{
		var cust_cd 	=	'';
		if (flag) {
			cust_cd 	=	$('.TXT_cust_cd').val();
		} else {
			cust_cd 	=	$('.TXT_consignee_cd').val();
		}
		var data = {
				cust_cd 	: 	cust_cd,
				cust_div 	:  	'1'
			};
		$.ajax({
			type 		: 'GET',
			url 		: '/pi/refer-suppliers',
			dataType	: 'json',
			data 		: data,
			success: function(res) {
				var data = '';
				if (res.response) {
					data 	=	res.data;
				}
				setItemReferSuppliers(data, flag)
			}
		});
	} catch (e) {
		alert('referSuppliers: ' + e.message);
	}
}
/**
 * refer product pi detail
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referProduct(data, pos, obj) {
	try	{
		$.ajax({
			type 		: 'GET',
			url 		: '/pi/refer-product',
			dataType	: 'json',
			data 		: data,
			timeout 	: 10000,
			success: function(res) {
				var data = '';
				$('.TXT_unit_price').removeClass('warning-item');
				if (res.response) {
					data 	=	res.data;
					_clearErrors();
					if (data.unit_price == null) {
						jMessage('W002', function(r) {
							if (r) {
								 _removeErrorStyle(obj);
								var msg = _text['W002']
								obj.closest('tr').find('.TXT_unit_price').addClass('warning-item');
								obj.closest('tr').find('.TXT_unit_price').focus();
							}
						});
					}
				}
				setItemReferProduct(data, pos)
			}
		});
	} catch (e) {
		alert('referProduct: ' + e.message);
	}
}
/**
 * set item refer product
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setItemReferProduct(data, pos) {
	try {
		if (data != '') {
			//<上段>
			parent.$('.refer-product-'+pos).find('.TXT_product_cd').val(data.product_cd);
			parent.$('.refer-product-'+pos).find('.TXT_description').val(data.description);
			parent.$('.refer-product-'+pos).find('.CMB_unit_of_m_div option:first').prop('selected', true);
			parent.$('.refer-product-'+pos).find('.CMB_unit_measure_price option:first').prop('selected', true);
			parent.$('.refer-product-'+pos).find('.CMB_unit_net_weight_div option:first').prop('selected', true);
			if (!jQuery.isEmptyObject(data)) {
				if (data.unit_qty_div != '') {
					parent.$('.refer-product-'+pos).find('.CMB_unit_of_m_div option[value='+data.unit_qty_div+']').prop('selected', true);
				}
				if (data.unit_measure_price != '') {
					parent.$('.refer-product-'+pos).find('.CMB_unit_measure_price option[value='+data.unit_measure_price+']').prop('selected', true);
				}
				if (data.unit_net_weight_div != '') {
					parent.$('.refer-product-'+pos).find('.CMB_unit_net_weight_div option[value='+data.unit_net_weight_div+']').prop('selected', true);
				}
			}
			parent.$('.refer-product-'+pos).find('.TXT_unit_price').val(data.unit_price);
			parent.$('.refer-product-'+pos).find('.DSP_unit_price_JPY').text(data.unit_price_JPY);
			parent.$('.refer-product-'+pos).find('.DSP_unit_price_USD').text(data.unit_price_USD);
			parent.$('.refer-product-'+pos).find('.DSP_unit_price_EUR').text(data.unit_price_EUR);
			parent.$('.refer-product-'+pos).find('.TXT_unit_measure_qty').val(data.measure);
			//<上段>
			parent.$('.refer-product-'+pos).find('.TXT_unit_net_weight').val(data.unit_net_weight);
			parent.$('.refer-product-'+pos).find('.TXT_unit_gross_weight').val(data.unit_gross_weight);
		} else {
			parent.$('.refer-product-'+pos).find('.TXT_description').val('');
		}
			//cal amount
			calAmount(pos);
			//cal net weight
			calNetWeight(pos);
			//cal gross weight
			calGrossWeight(pos);
			//cal measure
			calMeasure(pos);
			//remover class parent
			parent.$('.cal-refer-'+pos).removeClass('cal-refer-'+pos);

			
			calTotalDetailAmt();
			calTotalTaxAmt();
			calTotalAmt();

			calTotalGrossWeight();
			calTotalNetWeight();
			calTotalMeasure();
			calTotalQty();
			if (jQuery.isEmptyObject(data)) {
				// parent.$('.refer-product-'+pos).find('.TXT_qty').val('');
				// parent.$('.refer-product-'+pos).find('.TXT_outside_remarks').val('');
				// parent.$('.refer-product-'+pos).find('.CMB_sales_detail_div option:first').prop('selected', true);
				calTotalQty();
			}
			//remove class parent refer-product
			parent.$('.refer-product-'+pos).removeClass('refer-product-'+pos);
	} catch (e) {
		alert('setItemReferProduct: ' + e.message);
	}
}
/**
 * set item refer suppliers Cust and Consignee
 * 
 * @author : ANS806 - 2017/12/07 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function setItemReferSuppliers(data, flag) {
	try	{
		
		if (flag) {
			if (data != '') {
				// <得意先>
				$('.TXT_cust_nm').val(data.client_nm);
				$('.TXT_cust_adr1').val(data.client_adr1);
				$('.TXT_cust_adr2').val(data.client_adr2);
				$('.TXT_cust_zip').val(data.client_zip);
				$('.TXT_cust_city_div').val(data.client_city_div);
				$('.DSP_cust_city_nm').text(!jQuery.isEmptyObject(data) ? data.client_city_nm : '');
				$('.TXT_cust_country_div').val(data.client_country_div);
				$('.DSP_cust_country_nm').text(!jQuery.isEmptyObject(data) ? data.client_country_nm : '');
				$('.TXT_cust_tel').val(data.client_tel);
				$('.TXT_cust_fax').val(data.client_fax);
				// <Consignee>
				$('.TXT_consignee_cd').val('');
				$('.TXT_consignee_nm').val(data.consignee_nm);
				$('.TXT_consignee_adr1').val(data.consignee_adr1);
				$('.TXT_consignee_adr2').val(data.consignee_adr2);
				$('.TXT_consignee_zip').val(data.consignee_zip);
				$('.TXT_consignee_city_div').val(data.consignee_city_div);
				$('.DSP_consignee_city_nm').text(!jQuery.isEmptyObject(data) ? data.consignee_city_nm : '');
				$('.TXT_consignee_country_div').val(data.consignee_country_div);
				$('.DSP_consignee_country_nm').text(!jQuery.isEmptyObject(data) ? data.consignee_country_nm : '');
				$('.TXT_consignee_tel').val(data.consignee_tel);
				$('.TXT_consignee_fax').val(data.consignee_fax);
				// <他>
				$('.TXT_shipping_mark_1').val(data.mark1);
				$('.TXT_shipping_mark_2').val(data.mark2);
				$('.TXT_shipping_mark_3').val(data.mark3);
				$('.TXT_shipping_mark_4').val(data.mark4);

				$('.TXT_dest_city_div').val(data.consignee_city_div);
				$('.DSP_dest_city_nm').text(!jQuery.isEmptyObject(data) ? data.consignee_city_nm : '');
				$('.TXT_dest_country_div').val(data.consignee_country_div);
				$('.DSP_dest_country_nm').text(!jQuery.isEmptyObject(data) ? data.consignee_country_nm : '');
				/*
				$('.TXT_dest_city_div').val(data.delivery_city_div);
				$('.DSP_dest_city_nm').text(!jQuery.isEmptyObject(data) ? data.deliverye_city_nm : '');
				$('.TXT_dest_country_div').val(data.delivery_country_div);
				$('.DSP_dest_country_nm').text(!jQuery.isEmptyObject(data) ? data.deliverye_country_nm : '');*/

	 			changeNmCombobox(data.client_country_div);
	 			calTotalTaxAmt();
	 			calTotalAmt();
	 			setItemCustCountryDiv(data.client_country_div);

	 			$('.CMB_currency_div option:first').prop('selected', true);
				$('.CMB_payment_conditions_div option:first').prop('selected', true);
				if (!jQuery.isEmptyObject(data)) {
					if (data.sales_currency_div != '') {
						$('.CMB_currency_div option[value='+data.sales_currency_div+']').prop('selected', true);
						$('.CMB_payment_conditions_div').trigger('change');
					}
					if (data.payment_conditions_div != '') {
						$('.CMB_payment_conditions_div option[value='+data.payment_conditions_div+']').prop('selected', true);
						$('.CMB_payment_conditions_div').trigger('change');
					}
				} else {
					// $('.TXT_cust_cd').val('');
					$('.TXT_consignee_cd').val('');
					$('.CMB_payment_conditions_div').trigger('change');
				}
				if (data.bank_div != '') {
					$('.CMB_bank option[value='+data.bank_div+']').prop('selected', true);
				} else {
					$('.CMB_bank option:first').prop('selected', true);
				}
			} else {
				$('.TXT_cust_nm').val('');
			}
			
		} else {
			if (data != '') {
				// <Consignee>
				$('.TXT_consignee_nm').val(data.client_nm);
				$('.TXT_consignee_adr1').val(data.client_adr1);
				$('.TXT_consignee_adr2').val(data.client_adr2);
				$('.TXT_consignee_zip').val(data.client_zip);
				$('.TXT_consignee_city_div').val(data.client_city_div);
				$('.DSP_consignee_city_nm').text(!jQuery.isEmptyObject(data) ? data.client_city_nm : '');
				$('.TXT_consignee_country_div').val(data.client_country_div);
				$('.DSP_consignee_country_nm').text(!jQuery.isEmptyObject(data) ? data.client_country_nm : '');
				$('.TXT_consignee_tel').val(data.client_tel);
				$('.TXT_consignee_fax').val(data.client_fax);
				if (jQuery.isEmptyObject(data)) {
					$('.TXT_consignee_cd').val('');
				}
			} else {
				$('.TXT_consignee_nm').val('');
			}
		}
		_clearValidateMsg();
	} catch (e) {
		alert('setItemReferSuppliers: ' + e.message);
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
	try {
		var _data = [];
		$('#table-pi tbody tr').each(function() {
			var _pi_detail_no 		=	($(this).find('.DSP_pi_detail_no').text() == "") ? 0 : $(this).find('.DSP_pi_detail_no').text();
			var _qty 				=	($(this).find('.TXT_qty').val() == "") ? 0 : $(this).find('.TXT_qty').val().replace(/,/g, '');
			var _unit_price 		=	($(this).find('.TXT_unit_price ').val() == "") ? 0 : $(this).find('.TXT_unit_price').val().replace(/,/g, '');
			var _amount 			=	($(this).find('.TXT_amount').val() == "") ? 0 : $(this).find('.TXT_amount').val().replace(/,/g, '');
			var _unit_measure_qty	=	($(this).find('.TXT_unit_measure_qty').val() == "") ? 0 : $(this).find('.TXT_unit_measure_qty').val().replace(/,/g, '');
			var _unit_net_weight 	=	($(this).find('.TXT_unit_net_weight').val() == "") ? 0 : $(this).find('.TXT_unit_net_weight').val().replace(/,/g, '');
			var _net_weight 		=	($(this).find('.TXT_net_weight').val() == "") ? 0 : $(this).find('.TXT_net_weight').val().replace(/,/g, '');
			var _unit_gross_weight 	=	($(this).find('.TXT_unit_gross_weight').val() == "") ? 0 : $(this).find('.TXT_unit_gross_weight').val().replace(/,/g, '');
			var _gross_weight 		=	($(this).find('.TXT_gross_weight').val() == "") ? 0 : $(this).find('.TXT_gross_weight').val().replace(/,/g, '');
			var _measure 			=	($(this).find('.TXT_measure').val() == "") ? 0 : $(this).find('.TXT_measure').val().replace(/,/g, '');
	 		var _tax_rate 			= 0;
				_tax_rate 			= parseFloat($('.tax_rate').text().replace(/,/g, ''));
				_tax_rate   		= !isNaN(_tax_rate) ? _tax_rate : 0;
			var _detail_tax 		= 0;
			if (_amount != '') {
					_detail_tax 	= _roundNumeric(parseFloat(_tax_rate) *  parseFloat(_amount), 2, 2);
				}
			//get data table pi detail
			var _t_pi_d = {
					'pi_detail_no' 			: parseInt(_pi_detail_no),
					'sales_detail_div' 		: $(this).find('.CMB_sales_detail_div').val(),
					'product_cd' 			: $(this).find('.TXT_product_cd').val(),
					'description' 			: $(this).find('.TXT_description').val(),
					'qty' 					: parseInt(_qty),
					'unit_of_m_div' 		: $(this).find('.CMB_unit_of_m_div ').val(),
					'unit_price' 			: parseFloat(_unit_price),
					'amount' 				: parseFloat(_amount),
					'detail_tax' 			: parseFloat(_detail_tax),
					'unit_measure_qty' 		: parseFloat(_unit_measure_qty),
					'unit_measure_price'	: $(this).find('.CMB_unit_measure_price').val(),
					'outside_remarks'		: $(this).find('.TXT_outside_remarks').val(),
					'unit_net_weight'		: parseFloat(_unit_net_weight),
					'unit_net_weight_div'	: $(this).find('.CMB_unit_net_weight_div').val(),
					'net_weight'			: parseFloat(_net_weight),
					'unit_gross_weight'		: parseFloat(_unit_gross_weight),
					'gross_weight'			: parseFloat(_gross_weight),
					'measure'				: parseFloat(_measure)
				};
			_data.push(_t_pi_d);
		});
		var _total_qty 				=	($('.DSP_total_qty').text() == "") ? 0 : $('.DSP_total_qty').text().replace(/,/g, '');
		var _total_gross_weight 	=	($('.DSP_total_gross_weight').text() == "") ? 0 : $('.DSP_total_gross_weight').text().replace(/,/g, '');
		var _total_net_weight 		=	($('.DSP_total_net_weight').text()  == "") ? 0 : $('.DSP_total_net_weight').text().replace(/,/g, '');
		var _total_measure 			=	($('.DSP_total_measure').text()  == "") ? 0 : $('.DSP_total_measure').text().replace(/,/g, '');
		
		var _total_detail_amt 		=	($('.DSP_total_detail_amt').text()  == "") ? 0 : $('.DSP_total_detail_amt').text().replace(/,/g, '');
		var _freigt_amt 			=	$('.TXT_freigt_amt').hasClass('hidden') ? 0 : $('.TXT_freigt_amt').val().replace(/,/g, '');
		var _insurance_amt 			=	$('.TXT_insurance_amt').hasClass('hidden') ? 0 : $('.TXT_insurance_amt').val().replace(/,/g, '');
		var _tax_amt 				=	($('.DSP_tax_amt').text()  == "") ? 0 : $('.DSP_tax_amt').text().replace(/,/g, '');
		// var _tax_amt 				=	calTotalTaxAmt();
		var _total_amt 				=	($('.DSP_total_amt').text()  == "" )? 0 : $('.DSP_total_amt').text().replace(/,/g, '')
		var STT_data = {
				'mode'					: mode, 
				'pi_no'					: $('.TXT_pi_no').val(),
				'pi_status'				: $('.TXT_pi_status').val(),
				'pi_date'				: $('.TXT_pi_date ').val(),
				//取引先
				'cust_cd'				: $('.TXT_cust_cd ').val(),
				'cust_nm'				: $('.TXT_cust_nm ').val(),
				'cust_adr1'				: $('.TXT_cust_adr1 ').val(),
				'cust_adr2'				: $('.TXT_cust_adr2 ').val(),
				'cust_zip'				: $('.TXT_cust_zip ').val(),
				'cust_city_div'			: $('.TXT_cust_city_div ').val(),
				'cust_country_div'		: $('.TXT_cust_country_div ').val(),
				'cust_tel'				: $('.TXT_cust_tel ').val(),
				'cust_fax'				: $('.TXT_cust_fax ').val(),
				//Consignee
				'consignee_cd'			: $('.TXT_consignee_cd ').val(),
				'consignee_nm'			: $('.TXT_consignee_nm ').val(),
				'consignee_adr1'		: $('.TXT_consignee_adr1 ').val(),
				'consignee_adr2'		: $('.TXT_consignee_adr2 ').val(),
				'consignee_zip'			: $('.TXT_consignee_zip ').val(),
				'consignee_city_div'	: $('.TXT_consignee_city_div ').val(),
				'consignee_country_div'	: $('.TXT_consignee_country_div ').val(),
				'consignee_tel'			: $('.TXT_consignee_tel ').val(),
				'consignee_fax'			: $('.TXT_consignee_fax ').val(),
				// <他>
				'shipping_mark_1'		: $('.TXT_shipping_mark_1').val(),
				'shipping_mark_2'		: $('.TXT_shipping_mark_2').val(),
				'shipping_mark_3'		: $('.TXT_shipping_mark_3').val(),
				'shipping_mark_4'		: $('.TXT_shipping_mark_4').val(),
				'packing'				: $('.TXT_packing').val(),
				'shipment_div'			: $('.CMB_shipment_div').val(),
				'currency_div'			: $('.CMB_currency_div').val(),
				'port_city_div'			: $('.CMB_port_city_div').val(),
				'port_country_div'		: $('.CMB_port_country_div').val(),
				'trade_terms_div'		: $('.CMB_trade_terms_div').val(),
				'dest_city_div'			: $('.TXT_dest_city_div').val(),
				'TXT_dest_country_div'	: $('.TXT_dest_country_div').val(),
				'payment_conditions_div': $('.CMB_payment_conditions_div').val(),
				'payment_notes'			: $('.TXT_payment_notes').val(),
				//<明細> data type json
				't_pi_d' 					: _data,
				//<数量合計明細>
				'total_qty'						: parseInt(_total_qty),
				'unti_total_qty_div'			: $('#table-pi tbody tr:first').find('.CMB_unit_of_m_div option:selected').val(),
				'total_gross_weight'			: parseFloat(_total_gross_weight),
				'unit_total_gross_weight_div'	: $('.DSP_unit_total_gross_weight_div').text(),
				'total_net_weight'				: parseFloat(_total_net_weight),
				'unit_total_net_weight_div'		: $('.DSP_unit_total_net_weight_div').text(),
				'total_measure'					: parseFloat(_total_measure),
				'unit_total_measure_div'		: $('.DSP_unit_total_measure_div').text(),
				//<金額合計>
				'total_detail_amt'		: parseFloat(_total_detail_amt),
				'freigt_amt'			: (_freigt_amt == '') ? 0 : parseFloat(_freigt_amt),
				'insurance_amt'			: (_insurance_amt == '') ? 0 : parseFloat(_insurance_amt),
				'tax_amt'				: parseFloat(_tax_amt),
				'total_amt'				: parseFloat(_total_amt),
				//<フッタ>
				'time_of_shipment'		: $('.TXT_time_of_shipment').val(),
				'bank'					: $('.CMB_bank').val(),
				'country_of_origin '	: $('.TXT_country_of_origin').val(),
				'manufacture '			: $('.TXT_manufacture ').val(),
				'varidity '				: $('.TXT_varidity ').val(),
				'other_conditions1'		: $('.TXT_other_conditions1').val(),
				'other_conditions2'		: $('.TXT_other_conditions2').val(),
				'other_conditions3'		: $('.TXT_other_conditions3').val(),
				'other_conditions4'		: $('.TXT_other_conditions4').val(),
				'other_conditions5'		: $('.TXT_other_conditions5').val(),
				'other_conditions6'		: $('.TXT_other_conditions6').val(),
				'other_conditions7'		: $('.TXT_other_conditions7').val(),
				'other_conditions8'		: $('.TXT_other_conditions8').val(),
				'other_conditions9'		: $('.TXT_other_conditions9').val(),
				'other_conditions10'	: $('.TXT_other_conditions10').val(),
				'sign_cd'				: $('.TXT_sign_cd').val(),
				'inside_remarks'		: $('.TXA_inside_remarks').val()
			};
		return STT_data;
	} catch(e) {
        console.log('getData' + e.message)
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
function savePi() {
	try{
	    var data = getData();
	    $.ajax({
	        type        :   'POST',
	        url         :   '/pi/pi-detail/save',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd, function(ok) {
	            			if (ok) {
	            				fillItemErrorsE005(res.errors_item, res.error_list);
	            			}
	            		});
	            	} else {
	            		var msg = (mode == 'I') ? 'I001' : 'I003';
	            		jMessage(msg, function(r){
		                	if(r){
		                		mode = 'U';
		                		var data = {
		                			pi_no 		: res.pi_no,
		                			pi_status 	: res.pi_status,
		                			mode 		: mode,
		                		}
		                		referPiDetail(data);
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
        console.log('postSave' + e.message)
    }
}
/**
 * delete pi detail
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function deletePi() {
	try {
		var _pi_no = $('.TXT_pi_no').val();
		$.ajax({
	        type        :   'POST',
	        url         :   '/pi/pi-detail/delete',
	        dataType    :   'json',
	        data        :   {pi_no : _pi_no},
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		jMessage('I002', function(r){
		                	if(r){
		                		setItemPiHDel();
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
        console.log('deletePi' + e.message)
    }
}
/**
 * approve pi detail
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function approvePi() {
	try {
		var data = {
			pi_no 				: 	$('.TXT_pi_no').val(),
			pi_inside_remarks	: 	$('.TXA_inside_remarks').val()
		}
		$.ajax({
	        type        :   'POST',
	        url         :   '/pi/pi-detail/approve',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		jMessage('I005', function(r){
		                	if(r){
		                		var data = {
		                			pi_no 		: res.pi_no,
		                			pi_status 	: res.pi_status,
		                			mode 		: mode,
		                		}
		                		referPiDetail(data);
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
        console.log('approvePi' + e.message)
    }
}
/**
 * print pi detail
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function piExport() {
	try {
		var data = {
			pi_no 			: 	$('.TXT_pi_no').val(),
			pi_status_div 	: 	$('.TXT_pi_status').val()
		}
		$.ajax({
	        type        :   'POST',
	        url         :   '/export/pi-export',
	        dataType    :   'json',
	        data        :   {pi_list : data},
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
	}  catch(e) {
        console.log('piExport' + e.message)
    }
}
/**
 * approve cancel pi detail
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function cancelApprovePi() {
	try {
		var data = {
			pi_no 				: 	$('.TXT_pi_no').val(),
			pi_inside_remarks	: 	$('.TXA_inside_remarks').val()
		}
		$.ajax({
	        type        :   'POST',
	        url         :   '/pi/pi-detail/approve-cancel',
	        dataType    :   'json',
	        data        :   data,
	        success: function(res) {
	            if (res.response) {
	            	if (res.error_cd != '') {
	            		jMessage(res.error_cd);
	            	} else {
	            		jMessage('I006', function(r){
		                	if(r){
		                		var data = {
		                			pi_no 		: res.pi_no,
		                			pi_status 	: res.pi_status,
		                			mode 	 	: mode,
		                		}
		                		referPiDetail(data);
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
        console.log('approvePi' + e.message)
    }
}
/**
 * total qty
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalQty() {
	try {
		var _total_qty = 0;
		$('#table-pi tbody tr').each(function() {
			var qty = $(this).find('.TXT_qty').val();
			qty = qty.replace(/,/g, '');
			if (qty != '') {
				_total_qty = _roundNumeric(parseFloat(_total_qty) +  parseFloat(qty), 2, 2);
			}
		});
		$('.DSP_total_qty').text(_convertMoneyToIntAndContra(_total_qty));
	} catch(e) {
        console.log('calTotalQty' + e.message)
    }
}
/**
 * total gross weight
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalGrossWeight() {
	try {
		var _total_gross_weight = 0;
		$('#table-pi tbody tr').each(function() {
			var gross_weight = $(this).find('.TXT_gross_weight').val();
			gross_weight = gross_weight.replace(/,/g, '');
			if (gross_weight != '') {
				_total_gross_weight = _roundNumeric(parseFloat(_total_gross_weight) +  parseFloat(gross_weight), 2, 2);
			}
		});
		$('.DSP_total_gross_weight').text(_convertMoneyToIntAndContra(_total_gross_weight));
		var unit_total_gross_weight_nm = $('#table-pi tbody tr:first').find('.unit_net_weight_div option:selected').text();
		var unit_total_gross_weight_div = $('#table-pi tbody tr:first').find('.unit_net_weight_div option:selected').val();
		$('.DSP_unit_total_gross_weight_nm').text(unit_total_gross_weight_nm)
		$('.DSP_unit_total_gross_weight_div').text(unit_total_gross_weight_div)
	} catch(e) {
        console.log('calTotalGrossWeight' + e.message)
    }
}
/**
 * total net weight
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalNetWeight() {
	try {
		var _total_net_weight = 0;
		$('#table-pi tbody tr').each(function() {
			var net_weight = $(this).find('.TXT_net_weight').val();
			net_weight = net_weight.replace(/,/g, '');
			if (net_weight != '') {
				_total_net_weight = _roundNumeric(parseFloat(_total_net_weight) +  parseFloat(net_weight), 2, 2);
			}
		});
		$('.DSP_total_net_weight').text(_convertMoneyToIntAndContra(_total_net_weight));
		var unit_total_net_weight_nm = $('#table-pi tbody tr:first').find('.unit_net_weight_div option:selected').text();
		var unit_total_net_weight_div = $('#table-pi tbody tr:first').find('.unit_net_weight_div option:selected').val();
		$('.DSP_unit_total_net_weight_nm').text(unit_total_net_weight_nm)
		$('.DSP_unit_total_net_weight_div').text(unit_total_net_weight_div)
	} catch(e) {
        console.log('calTotalNetWeight' + e.message)
    }
}
/**
 * total measure
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalMeasure() {
	try {
		var _total_measure = 0;
		$('#table-pi tbody tr').each(function() {
			var measure = $(this).find('.TXT_measure').val();
			measure = measure.replace(/,/g, '');
			if (measure != '') {
				_total_measure = _roundNumeric(parseFloat(_total_measure) +  parseFloat(measure), 2, 2);
			}
		});
		$('.DSP_total_measure').text(_convertMoneyToIntAndContra(_total_measure));
		var unit_total_measure_nm = $('#table-pi tbody tr:first').find('.unit_measure_price option:selected').text();
		var unit_total_measure_div = $('#table-pi tbody tr:first').find('.unit_measure_price option:selected').val();
		$('.DSP_unit_total_measure_nm').text(unit_total_measure_nm)
		$('.DSP_unit_total_measure_div').text(unit_total_measure_div)
	} catch(e) {
        console.log('calTotalMeasure' + e.message)
    }
}
/**
 * total detail amt
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalDetailAmt() {
	try {
		var _total_amount = 0;
		$('#table-pi tbody tr').each(function() {
			var amount = $(this).find('.TXT_amount').val();
			amount = amount.replace(/,/g, '');
			if (amount != '') {
				_total_amount = _roundNumeric(parseFloat(_total_amount) +  parseFloat(amount), 2, 2);
			}
		});
		$('.DSP_total_detail_amt').text(_convertMoneyToIntAndContra(_total_amount));
	} catch(e) {
        console.log('calTotalAmt' + e.message)
    }
}
/**
 * total tax amt
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
 function calTotalTaxAmt() {
 	try {
 		var country_div 	=	$('.TXT_cust_country_div').val();
 		_addTaxRate(country_div);
 		if (country_div == 'JP') {
 			$('.DSP_tax_amt').text($('.tax_rate').text());
 			var _total_tax_amt 		= 0;
	 		var _tax_rate 			= 0;
				_tax_rate 			= parseFloat($('.DSP_tax_amt').text().replace(/,/g, ''));
				_tax_rate   		= !isNaN(_tax_rate) ? _tax_rate : 0;

	 		var _total_detail_amt 	= 0;
				_total_detail_amt 	= parseFloat($('.DSP_total_detail_amt').text().replace(/,/g, ''));
				_total_detail_amt   = !isNaN(_total_detail_amt) ? _total_detail_amt : 1;
			var _total_tax_rate 	= 0;

			$('#table-pi tbody tr').each(function() {
				var amount = $(this).find('.TXT_amount').val();
				amount = amount.replace(/,/g, '');
				if (amount != '') {
					_total_tax_rate 	= _roundNumeric(parseFloat(_tax_rate) *  parseFloat(amount), 2, 2);
					_total_tax_amt 		= _roundNumeric(_total_tax_rate + _total_tax_amt, 2, 2);
				}
			});
			// _total_tax_amt 			= _roundNumeric(_total_detail_amt * _tax_rate, 2, 2);
			_total_tax_amt 			= _roundNumeric(_total_tax_amt, _constVal1['sales_tax_round_div']);

			$('.DSP_tax_amt').text(_convertMoneyToIntAndContra(_total_tax_amt));
 		} else {
 			$('.DSP_tax_amt').addClass('hidden');
 			$('.DSP_tax_amt').text('');
 		}
 		
 	} catch(e) {
        console.log('calTotalTaxAmt' + e.message)
    }
 }
/**
 * total amt
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalAmt() {
	try {
		var _total_amt 			= 0;
		var _total_detail_amt 	= 0;
			_total_detail_amt 	= parseFloat($('.DSP_total_detail_amt').text().replace(/,/g, ''));
			_total_detail_amt   = !isNaN(_total_detail_amt) ? _total_detail_amt : 0;
		var _freigt_amt 	= 0;
		if (!$('.TXT_freigt_amt').hasClass('hidden')) {
			_freigt_amt 	= parseFloat($('.TXT_freigt_amt').val().replace(/,/g, ''));
			_freigt_amt   	= !isNaN(_freigt_amt) ? _freigt_amt : 0;
		}
		var _insurance_amt 	= 0;
		if (!$('.TXT_insurance_amt').hasClass('hidden')) {
			_insurance_amt 	= parseFloat($('.TXT_insurance_amt').val().replace(/,/g, ''));
			_insurance_amt   = !isNaN(_insurance_amt) ? _insurance_amt : 0;
		}
		var _tax_amt 	= 0;
		if (!$('.DSP_tax_amt').hasClass('hidden')) {
			_tax_amt 	= parseFloat($('.DSP_tax_amt').text().replace(/,/g, ''));
			_tax_amt   	= !isNaN(_tax_amt) ? _tax_amt : 0;
		}
		_total_amt 				= _roundNumeric(_total_detail_amt + _freigt_amt + _insurance_amt + _tax_amt, 2, 2);
		$('.DSP_total_amt').text(_convertMoneyToIntAndContra(_total_amt));
	} catch(e) {
        console.log('calTotalAmt' + e.message)
    }
}
/**
 * cal Amount
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calAmount(pos) {
	try {
		var qty 			= 	parent.$('.cal-refer-'+pos).find('.TXT_qty').val().replace(/,/g, '');
		var unit_price 		=	parent.$('.cal-refer-'+pos).find('.TXT_unit_price').val().replace(/,/g, '');
		var amount 			=	_roundNumeric(parseFloat(qty) * parseFloat(unit_price), 2, 2);
		if (isNaN(amount)) {
			amount = 0;
		}
		parent.$('.cal-refer-'+pos).find('.TXT_amount').val(_convertMoneyToIntAndContra(amount));
	} catch(e) {
        console.log('calAmount' + e.message)
    }
}
/**
 * cal net weight
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calNetWeight(pos) {
	try {
		var qty 				= 	parent.$('.cal-refer-'+pos).find('.TXT_qty').val().replace(/,/g, '');
		var unit_net_weight 	=	parent.$('.cal-refer-'+pos).find('.TXT_unit_net_weight').val().replace(/,/g, '');
		var net_weight 			=	_roundNumeric(parseFloat(qty) * parseFloat(unit_net_weight), 2, 2);
		if (isNaN(net_weight)) {
			net_weight = 0;
		}
		parent.$('.cal-refer-'+pos).find('.TXT_net_weight').val(_convertMoneyToIntAndContra(net_weight));
	} catch(e) {
        console.log('calNetWeight' + e.message)
    }
}
/**
 * cal gross weight
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calGrossWeight(pos) {
	try {
		var qty 				= 	parent.$('.cal-refer-'+pos).find('.TXT_qty').val().replace(/,/g, '');
		var unit_gross_weight 	=	parent.$('.cal-refer-'+pos).find('.TXT_unit_gross_weight').val().replace(/,/g, '');
		var gross_weight 			=	_roundNumeric(parseFloat(qty) * parseFloat(unit_gross_weight), 2, 2);
		if (isNaN(gross_weight)) {
			gross_weight = 0;
		}
		parent.$('.cal-refer-'+pos).find('.TXT_gross_weight').val(_convertMoneyToIntAndContra(gross_weight));
	} catch(e) {
        console.log('calGrossWeight' + e.message)
    }
}
/**
 * cal measure
 * 
 * @author : ANS806 - 2017/12/05 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calMeasure(pos) {
	try {
		var qty 				= 	parent.$('.cal-refer-'+pos).find('.TXT_qty').val().replace(/,/g, '');
		var unit_measure_qty 	=	parent.$('.cal-refer-'+pos).find('.TXT_unit_measure_qty').val().replace(/,/g, '');
		var measure 			=	_roundNumeric(parseFloat(qty) * parseFloat(unit_measure_qty), 2, 2);
		if (isNaN(measure)) {
			measure = 0;
		}
		parent.$('.cal-refer-'+pos).find('.TXT_measure').val(_convertMoneyToIntAndContra(measure));
	} catch(e) {
        console.log('calMeasure' + e.message)
    }
}
/**
 * set select commbobox
 *
 * @author      :   ANS806 - 2017/07/05
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   saveFunction
 */
function setSelectCombobox() {
	try {
		$('#table-pi tbody tr').each(function() {
			var _sales_detail_div 		=	$(this).find('.CMB_sales_detail_div').attr('data-selected');
			$(this).find('.CMB_sales_detail_div option[value='+_sales_detail_div+']').prop('selected', true);
			var _unit_of_m_div 			=	$(this).find('.CMB_unit_of_m_div').attr('data-selected');
			if (_unit_of_m_div != '') {
				$(this).find('.CMB_unit_of_m_div option[value='+_unit_of_m_div+']').prop('selected', true);
			} else {
				$(this).find('.CMB_unit_of_m_div option:first').prop('selected', true);
			}
			var _unit_net_weight_div 	=	$(this).find('.CMB_unit_net_weight_div').attr('data-selected');
			if (_unit_net_weight_div != '') {
				$(this).find('.CMB_unit_net_weight_div option[value='+_unit_net_weight_div+']').prop('selected', true);
			} else {
				$(this).find('.CMB_unit_net_weight_div option:first').prop('selected', true);
			}
			
			var _unit_measure_price 	=	$(this).find('.CMB_unit_measure_price').attr('data-selected');
			if (_unit_measure_price != '') {
				$(this).find('.CMB_unit_measure_price option[value='+_unit_measure_price+']').prop('selected', true);
			} else {
				$(this).find('.CMB_unit_measure_price option:first').prop('selected', true);
			}
			
		});
	} catch (e)  {
        alert('setSelectCombobox:  ' + e.message);
    }
}
/**
 * set Item When change Cust Country Div
 *
 * @author      :   ANS806 - 2017/07/05
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   saveFunction
 */
function setItemCustCountryDiv(country_div) {
	try {
		if (country_div == 'JP' || country_div =='jp' || country_div =='jP' || country_div =='Jp') {
			$('.TXT_packing').val(_constVal1['pi_packing'])
			$('.TXT_time_of_shipment').val(_constVal1['pi_time_of_shipment'])
			$('.TXT_country_of_origin').val(_constVal1['pi_country_of_origin'])
			$('.TXT_manufacture').val(_constVal1['pi_manufacture'])
			$('.TXT_varidity').val(_constVal1['pi_varidity'])
			$('.TXT_other_conditions1').val(_constVal1['pi_other_conditions1'])
			$('.TXT_other_conditions2').val(_constVal1['pi_other_conditions2'])
			$('.TXT_other_conditions3').val(_constVal1['pi_other_conditions3'])
			$('.TXT_other_conditions4').val(_constVal1['pi_other_conditions4'])
			$('.TXT_other_conditions5').val(_constVal1['pi_other_conditions5'])
			$('.TXT_other_conditions6').val(_constVal1['pi_other_conditions6'])
			$('.TXT_other_conditions7').val(_constVal1['pi_other_conditions7'])
			$('.TXT_other_conditions8').val(_constVal1['pi_other_conditions8'])
			$('.TXT_other_conditions9').val(_constVal1['pi_other_conditions9'])
			$('.TXT_other_conditions10').val(_constVal1['pi_other_conditions10'])
		} else {
			$('.TXT_packing').val(_constVal2['pi_packing'])
			$('.TXT_time_of_shipment').val(_constVal2['pi_time_of_shipment'])
			$('.TXT_country_of_origin').val(_constVal2['pi_country_of_origin'])
			$('.TXT_manufacture').val(_constVal2['pi_manufacture'])
			$('.TXT_varidity').val(_constVal2['pi_varidity'])
			$('.TXT_other_conditions1').val(_constVal2['pi_other_conditions1'])
			$('.TXT_other_conditions2').val(_constVal2['pi_other_conditions2'])
			$('.TXT_other_conditions3').val(_constVal2['pi_other_conditions3'])
			$('.TXT_other_conditions4').val(_constVal2['pi_other_conditions4'])
			$('.TXT_other_conditions5').val(_constVal2['pi_other_conditions5'])
			$('.TXT_other_conditions6').val(_constVal2['pi_other_conditions6'])
			$('.TXT_other_conditions7').val(_constVal2['pi_other_conditions7'])
			$('.TXT_other_conditions8').val(_constVal2['pi_other_conditions8'])
			$('.TXT_other_conditions9').val(_constVal2['pi_other_conditions9'])
			$('.TXT_other_conditions10').val(_constVal2['pi_other_conditions10'])
		}
	} catch (e)  {
        alert('setItemCustCountryDiv:  ' + e.message);
    }
}
/**
 * set class unit combobx
 *
 * @author      :   ANS806 - 2017/07/05
 * @param       :
 * @return      :   null
 * @access      :   public
 * @see         :   saveFunction
 */
function setClassUnitCombobox() {
	try {
		//remove class
		$('#table-pi tbody tr').find('.CMB_unit_net_weight_div').removeClass('unit_net_weight_div');
		$('#table-pi tbody tr').find('.CMB_unit_measure_price').removeClass('unit_measure_price');
		//add class
		$('#table-pi tbody tr:first').find('.CMB_unit_net_weight_div').addClass('unit_net_weight_div');
		$('#table-pi tbody tr:first').find('.CMB_unit_measure_price').addClass('unit_measure_price');
		calTotalGrossWeight();
		calTotalNetWeight();
		calTotalMeasure();
	} catch (e)  {
        alert('setClassUnitCombobox:  ' + e.message);
    }
}
/**
 * validate detail
 *
 * @author		:	Trieunb - 2017/12/20 - create
 * @params		:	null
 * @return		:	null
 */
function validateDetail() {
	try {
		var detail 	= $('#table-pi tbody tr');
		var error 	= 0;
		$('.TXT_product_cd').removeClass('warning-item');
		var flag 	= false;
		detail.each(function() {
			error 	= 0;
			if ($(this).is(':visible')) {
				$(this).find('.required_detail:enabled:not([readonly])').each(function() {
					if(($(this).is("input") || $(this).is("textarea")) &&  $.trim($(this).val()) !== '') {
						error ++;
					} else if( $(this).is("select") &&  ($(this).val() !== '') ) {
						error ++;
					} else if($(this).is("input[type=checkbox]") && $(this).is(":checked")){
	                    error ++;
	                }

				})
			   	if( error == 3 ) {
					flag 	= true;
				}
			}

		});
		return flag;
	} catch (e) {
		alert('validateDetail: ' + e.message);
	}
}
function validateErrorNumericDetail() {
	try {
		_clearErrors();
		var detail 	= $('#table-pi tbody tr');
		var error 	= 0;
		if (detail.find('.error-numeric').length > 0) {
			error ++;
		}
		if( error > 0 ) {
			return false;
		} else {
			return true;
		}
	} catch (e) {
		alert('validateErrorNumericDetail: ' + e.message);
	}
}
function showMsgDetail() {
	try {
		var detail 	= $('#table-pi tbody tr');
		var error 	= 0;
		detail.find('.required_detail:enabled:not([readonly])').each(function() {
			if ($(this).is(':visible')) {
				if(($(this).is("input") || $(this).is("textarea")) &&  $.trim($(this).val()) == '' ) {
					$(this).errorStyle(_MSG_E001);
					error ++;
				}else if( $(this).is("select") &&  ($(this).val() == '' || $(this).val() == undefined) ) {
					$(this).errorStyle(_MSG_E001);
					error ++;
				}else if($(this).is("input[type=checkbox]") && !$(this).is(":checked")){
                    $(this).errorStyle(_MSG_E001);
                    error ++;
                }
			}
		});
		if( error > 0 ) {
			return false;
		} else {
			return true;
		}
	} catch (e) {
		alert('showMsgDetail: ' + e.message);
	}
}
function validatePiNo() {
	try {
		_clearErrors();
		var error 	= true;
		if ($('.pi_cd').val() == '') {
			$('.pi_cd').errorStyle(_MSG_E001);
			error 	= false;
		}
		return error;
	} catch (e) {
		alert('validatePiNo: ' + e.message);
	}
}
function showMessageW001() {
	try {
		// jMessage('W001');
		var lib_val_ctl1 = $('.CMB_trade_terms_div > option:selected').attr('data-ctl5');
 		var lib_val_ctl2 = $('.CMB_trade_terms_div > option:selected').attr('data-ctl6');
 		changeTrade(lib_val_ctl1, lib_val_ctl2);
	} catch (e) {
		alert('showMessageW001: ' + e.message);
	}
}
function validateAmountDetail(element) {
	try {
		var parent 			= element.parents('#table-pi tbody tr');
		var amount 			= parent.find('.TXT_amount').val().replace(/,/g, '');
			amount 			= parseFloat(amount);

		var flag_amount 	=	false;
		if (amount < -9999999999999.99 || amount > 9999999999999.99) {
			parent.find('.TXT_amount').addClass('error-numeric');
			flag_amount		=	true;
		} else {
			parent.find('.TXT_amount').removeClass('error-numeric');
		}

		if (flag_amount) {
			parent.find('.TXT_unit_price').addClass('error-numeric');
		} else {
			parent.find('.TXT_unit_price').removeClass('error-numeric');
		}
	} catch (e) {
		alert('validateAmountDetail: ' + e.message);
	}
}
function validateNetWeightDetail(element) {
	try {
		var parent 			= element.parents('#table-pi tbody tr');
		var net_weight 		= parent.find('.TXT_net_weight').val().replace(/,/g, '');
			net_weight 		= parseFloat(net_weight);

		var flag_net_weight 	=	false;
		if (net_weight < -9999999999999.99 || net_weight > 9999999999999.99) {
			parent.find('.TXT_net_weight').addClass('error-numeric');
			flag_net_weight		=	true;
		} else {
			parent.find('.TXT_net_weight').removeClass('error-numeric');
		}

		if (flag_net_weight) {
			parent.find('.TXT_unit_net_weight').addClass('error-numeric');
		} else {
			parent.find('.TXT_unit_net_weight').removeClass('error-numeric');
		}
	} catch (e) {
		alert('validateNetWeightDetail: ' + e.message);
	}
}
function validateGrossWeightDetail(element) {
	try {
		var parent 			= element.parents('#table-pi tbody tr');
		var gross_weight 	= parent.find('.TXT_gross_weight').val().replace(/,/g, '');
			gross_weight 	= parseFloat(gross_weight);

		var flag_gross_weight 	=	false;
		if (gross_weight < -9999999999999.99 || gross_weight > 9999999999999.99) {
			parent.find('.TXT_gross_weight').addClass('error-numeric');
			flag_gross_weight	=	true;
		} else {
			parent.find('.TXT_gross_weight').removeClass('error-numeric');
		}

		if (flag_gross_weight) {
			parent.find('.TXT_unit_gross_weight').addClass('error-numeric');
		} else {
			parent.find('.TXT_unit_gross_weight').removeClass('error-numeric');
		}
	} catch (e) {
		alert('validateGrossWeightDetail: ' + e.message);
	}
}
function validateMeasureDetail(element) {
	try {
		var parent 			= element.parents('#table-pi tbody tr');
		var measure 		= parent.find('.TXT_measure').val().replace(/,/g, '');
			measure 		= parseFloat(measure);

		var flag_measure 	=	false;
		if (measure < -9999999999999.99 || measure > 9999999999999.99) {
			parent.find('.TXT_measure').addClass('error-numeric');
			flag_measure	=	true;
		} else {
			parent.find('.TXT_measure').removeClass('error-numeric');
		}

		if (flag_measure) {
			parent.find('.TXT_unit_measure_qty').addClass('error-numeric');
		} else {
			parent.find('.TXT_unit_measure_qty').removeClass('error-numeric');
		}
	} catch (e) {
		alert('validateMeasureDetail: ' + e.message);
	}
}
function validateQtyDetail(element) {
	try {
		var parent 			= element.parents('#table-pi tbody tr');
		parent.find('.TXT_qty').removeClass('error-numeric');
		if (parent.find('.error-numeric').length > 0) {
			parent.find('.TXT_qty').addClass('error-numeric');
		}
		parent.find('.error-numeric:first').focus();
	} catch (e) {
		alert('validateQtyDetail: ' + e.message);
	}
}
function addClassCurrency() {
	try {
		var currency_div 	=	$('.CMB_currency_div option').val();
		if (currency_div == 'JP') {
			$('#table-pi tbody tr').find('.TXT_unit_price').addClass('currency_JPY')
		} else {
			$('#table-pi tbody tr').find('.TXT_unit_price').removeClass('currency_JPY')
		}
	} catch (e) {
		alert('addClassCurrency: ' + e.message);
	}
}
function changeTrade(lib_val_ctl1, lib_val_ctl2) {
	try {
		_addFreigtAndInsurance(lib_val_ctl1, lib_val_ctl2);
		calTotalAmt();
	} catch (e) {
		alert('changeTrade: ' + e.message);
	}
}
function disablePiNo() {
	try {
		$('.TXT_pi_no').attr('disabled', true);
		$('.TXT_pi_no').parent().addClass('popup-pi-search')
		$('.popup-pi-search').find('.btn-search').attr('disabled', true);
		parent.$('.popup-pi-search').removeClass('popup-pi-search');
	} catch (e) {
		alert('disablePiNo: ' + e.message);
	}
}
function updateTablePiDetail() {
	_updateTable('table-pi', true);
	$('#table-pi tbody tr:first').find('.CMB_unit_net_weight_div').addClass('unit_net_weight_div')
	$('#table-pi tbody tr:first').find('.CMB_unit_measure_price').addClass('unit_measure_price')
}
function setItemPiHDel(pi_no) {
	try {
		$(':input').val('');
		$('.TXT_pi_no').val(pi_no);
		$('.TXT_pi_date ').val(current_date);
		$('.TXT_packing ').val(_constVal1['pi_packing']);
		$('.DSP_cust_city_nm').text('');
		$('.DSP_cust_country_nm').text('');
		$('.DSP_consignee_city_nm').text('');
		$('.DSP_consignee_country_nm').text('');
		$('.DSP_dest_city_nm').text('');
		$('.DSP_dest_country_nm').text('');
		$('.DSP_total_detail_amt').text('');
		$('.DSP_total_amt').text('');
		$('.DSP_total_qty').text('');
		$('.DSP_total_gross_weight').text('');
		$('.DSP_unit_total_gross_weight_div').text('');
		$('.DSP_total_net_weight').text('');
		$('.DSP_unit_total_net_weight_div').text('');
		$('.DSP_total_measure').text('');
		$('.DSP_unit_total_measure_div').text('');
		$('.DSP_tax_amt').text('');
		$('.DSP_sign_nm').text('');

		$('.TXT_sign_cd').val(cre_user_cd);
		$('.DSP_sign_nm').text(cre_user_nm);
		$('#DSP_cre_user_cd').text('');
		$('#DSP_cre_datetime').text('');
		$('#DSP_upd_user_cd').text('');
		$('#DSP_upd_datetime').text('');

		$('.DSP_status').text('');
		$('.DSP_pi_status_cre_datetime').text('');

		$('.DSP_tax_amt').addClass('hidden');
		$('.title-jp').addClass('hidden');

		//init 1 row table at mode add new (I)
		_initRowTable('table-pi', 'table-row', 1, setClassUnitCombobox);
		
		$('.infor-created .heading-elements').addClass('hidden');
		//set default value of select
    	$('select').each(function() {
    		if($(this).attr('data-ini-target') == 'true'){
	    		var objParent = $(this);
	    		objParent.find('option').each(function(){
	    			if($(this).attr('data-ini_target_div') == 1){
						objParent.val($(this).attr('value'));
						objParent.trigger('change');
					}
	    		});
	    	}
		});
	} catch (e) {
		alert('setItemPiHDel: ' + e.message);
	}
}
function fillItemErrorsE005(errors_item, error_list) {
	try {
		if (errors_item.pi_no == error_key) {
			$('.TXT_pi_no').errorStyle(_text['E005']);
		}
		if (errors_item.cust_cd == error_key) {
			// $('.TXT_cust_cd').addClass('error_e005');
			$('.TXT_cust_cd').errorStyle(_text['E005']);
		}
		if (errors_item.cust_city_div == error_key) {
			// $('.TXT_cust_city_div').addClass('error_e005');
			$('.TXT_cust_city_div').errorStyle(_text['E005']);
		}
		if (errors_item.cust_country_div == error_key) {
			// $('.TXT_cust_country_div').addClass('error_e005');
			$('.TXT_cust_country_div').errorStyle(_text['E005']);
		}
		if (errors_item.consignee_cd == error_key) {
			// $('.TXT_consignee_cd').addClass('error_e005');
			$('.TXT_consignee_cd').errorStyle(_text['E005']);
		}
		if (errors_item.consignee_city_div == error_key) {
			// $('.TXT_consignee_city_div').addClass('error_e005');
			$('.TXT_consignee_city_div').errorStyle(_text['E005']);
		}
		if (errors_item.consignee_country_div == error_key) {
			// $('.TXT_consignee_country_div').addClass('error_e005');
			$('.TXT_consignee_country_div').errorStyle(_text['E005']);
		}
		if (errors_item.dest_city_div == error_key) {
			// $('.TXT_dest_city_div').addClass('error_e005');
			$('.TXT_dest_city_div').errorStyle(_text['E005']);
		}
		if (errors_item.dest_country_div == error_key) {
			// $('.TXT_dest_country_div').addClass('error_e005');
			$('.TXT_dest_country_div').errorStyle(_text['E005']);
		}
		if (errors_item.sign_cd == error_key) {
			// $('.TXT_sign_cd ').addClass('error_e005');
			$('.TXT_sign_cd').errorStyle(_text['E005']);
		}
		// check e005 for table detail pi
		var detail 	= $('#table-pi tbody tr');
		detail.each(function() {
			if ($(this).is(':visible')) {
				var product 		=	$(this).find('.TXT_product_cd');
				var product_code 	= 	product.val().trim();
				$.each(error_list, function(i, item) {
				    if (item.product_cd === product_code) {
				    	// product.addClass('error_e005');
				    	product.errorStyle(_text['E005']);
				    }
				});
			}
		});
		if ($('.address-to').hasClass('hidden') && $('.address-to').find('.error-item').length > 0) {
			// $('#show-address-to').trigger('click');
			$('.address-to').removeClass('hidden');
		}
		if ($('.address-from').hasClass('hidden') && $('.address-from').find('.error-item').length > 0) {
			// $('#show-address-from').trigger('click');
			$('.address-from').removeClass('hidden');
		}
		$(document).find('.error-item:first').focus();
	} catch (e) {
		alert('fillItemErrorsE005: ' + e.message);
	}
}