/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/04/11
 * 作成者		:	ANS806 - Trieunb
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
/*==================================================== CALCULATOR FOR TABLE INVOICE DETAIL ====================================================*/
/**
 * init Events Calculator in carton
 * @author  :   Trieunb - 2018/04/11 - create
 * @param
 * @return
 */
function eventCalTotalCarton() {
	try {
		// calculator 
		$(document).on('change', '.TXT_qty_table_carton ', function() {
			// var qty = $(this).val();
			var parents = $(this).parents('#table-carton tbody tr');
 			parents.addClass('cal-carton');
 			//cal net weight
 			calNetWeightCarton();
 			//cal gross weight
 			calGrossWeightCarton();
 			//cal measure
 			calMeasureCarton();
 			//remover class parent
			parent.$('.cal-carton').removeClass('cal-carton');
			calTotalQtyCarton();
			calTotalNetWeightCarton();
			calTotalGrossWeightCarton();
			calTotalMeasureCarton();
			calNumberCarton();
		});
		// calculator 
		$(document).on('change', '.TXT_carton_number ', function() {
			calNumberCarton();
		});
		// remove row table carton
		$(document).on('click','#remove-row',function(){
			var obj   = $(this);
			jMessage('C002', function(r) {
				if(r) {
					obj.closest('tr').remove();
					_updateTable('table-carton', true);
		 			$('.table-carton tbody tr:last :input:first').focus();
		 			calTotalQtyCarton();
					calTotalNetWeightCarton();
					calTotalGrossWeightCarton();
					calTotalMeasureCarton();
					calNumberCarton();
				}
			});
		});
	} catch (e) {
		alert('event Calculator Total Carton' + e.message);
	}
}
/**
 * total qty
 * 
 * @author : ANS806 - 2018/04/10 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalQty() {
	try {
		var _total_qty = 0;
		$('#table-invoice tbody tr').each(function() {
			var qty = $(this).find('.TXT_qty').val();
			qty = qty.replace(/,/g, '');
			if (qty != '') {
				_total_qty = _roundNumeric(parseFloat(_total_qty) +  parseFloat(qty), 2, 2);
			}
		});
		$('.DSP_total_qty').text(_convertMoneyToIntAndContra(_total_qty));
	} catch(e) {
        console.log('calTotalQty: ' + e.message)
    }
}
/**
 * total gross weight
 * 
 * @author : ANS806 - 2018/04/10 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalGrossWeight() {
	try {
		var _total_gross_weight = 0;
		$('#table-invoice tbody tr').each(function() {
			var gross_weight = $(this).find('.TXT_gross_weight').val();
			gross_weight = gross_weight.replace(/,/g, '');
			if (gross_weight != '') {
				_total_gross_weight = _roundNumeric(parseFloat(_total_gross_weight) +  parseFloat(gross_weight), 2, 2);
			}
		});
		$('.DSP_total_gross_weight').text(_convertMoneyToIntAndContra(_total_gross_weight));
		
	} catch(e) {
        console.log('calTotalGrossWeight: ' + e.message)
    }
}
/**
 * total net weight
 * 
 * @author : ANS806 - 2018/04/10 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalNetWeight() {
	try {
		var _total_net_weight = 0;
		$('#table-invoice tbody tr').each(function() {
			var net_weight = $(this).find('.TXT_net_weight').val();
			net_weight = net_weight.replace(/,/g, '');
			if (net_weight != '') {
				_total_net_weight = _roundNumeric(parseFloat(_total_net_weight) +  parseFloat(net_weight), 2, 2);
			}
		});
		$('.DSP_total_net_weight').text(_convertMoneyToIntAndContra(_total_net_weight));
	} catch(e) {
        console.log('calTotalNetWeight:' + e.message)
    }
}
/**
 * total measure
 * 
 * @author : ANS806 - 2018/04/10 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalMeasure() {
	try {
		var _total_measure = 0;
		$('#table-invoice tbody tr').each(function() {
			var measure = $(this).find('.TXT_measure').val();
			measure = measure.replace(/,/g, '');
			if (measure != '') {
				_total_measure = _roundNumeric(parseFloat(_total_measure) +  parseFloat(measure), 2, 2);
			}
		});
		$('.DSP_total_measure').text(_convertMoneyToIntAndContra(_total_measure));
	} catch(e) {
        console.log('calTotalMeasure: ' + e.message)
    }
}
/**
 * total detail amt
 * 
 * @author : ANS806 - 2018/04/10 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalDetailAmt() {
	try {
		var _total_amount = 0;
		$('#table-invoice tbody tr').each(function() {
			var amount = $(this).find('.TXT_amount').val();
			amount = amount.replace(/,/g, '');
			if (amount != '') {
				_total_amount = _roundNumeric(parseFloat(_total_amount) +  parseFloat(amount), 2, 2);
			}
		});
		$('.DSP_total_detail_amt').text(_convertMoneyToIntAndContra(_total_amount));
	} catch(e) {
        console.log('calTotalAmt: ' + e.message)
    }
}
/**
 * total tax amt
 * 
 * @author : ANS806 - 2018/04/10 - create
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

			$('#table-invoice tbody tr').each(function() {
				var amount = $(this).find('.TXT_amount').val();
				amount = amount.replace(/,/g, '');
				if (amount != '') {
					_total_tax_rate = _roundNumeric(parseFloat(_tax_rate) *  parseFloat(amount), 2, 2);
					_total_tax_amt  = _roundNumeric(_total_tax_rate + _total_tax_amt, 2, 2);
				}
			});
			
			_total_tax_amt = _roundNumeric(_total_tax_amt, _constVal1['sales_tax_round_div']);

			$('.DSP_tax_amt').text(_convertMoneyToIntAndContra(_total_tax_amt));
 		} else {
 			$('.DSP_tax_amt').addClass('hidden');
 			$('.DSP_tax_amt').text('');
 		}
 		
 	} catch(e) {
        console.log('calTotalTaxAmt: ' + e.message)
    }
}
/**
 * cal Total Amt
 * 
 * @author : ANS806 - 2018/04/10 - create
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

		var _freigt_amt 		= 0;
		if (!$('.TXT_freight_amt').hasClass('hidden')) {
			_freigt_amt = parseFloat($('.TXT_freight_amt').val().replace(/,/g, ''));
			_freigt_amt = !isNaN(_freigt_amt) ? _freigt_amt : 0;
		}

		var _insurance_amt 	= 0;
		if (!$('.TXT_insurance_amt').hasClass('hidden')) {
			_insurance_amt = parseFloat($('.TXT_insurance_amt').val().replace(/,/g, ''));
			_insurance_amt = !isNaN(_insurance_amt) ? _insurance_amt : 0;
		}

		var _tax_amt 	= 0;
		if (!$('.DSP_tax_amt').hasClass('hidden')) {
			_tax_amt = parseFloat($('.DSP_tax_amt').text().replace(/,/g, ''));
			_tax_amt = !isNaN(_tax_amt) ? _tax_amt : 0;
		}

		_total_amt = _roundNumeric(_total_detail_amt + _freigt_amt + _insurance_amt + _tax_amt, 2, 2);
		$('.DSP_total_amt').text(_convertMoneyToIntAndContra(_total_amt));
	} catch(e) {
        console.log('calTotalAmt: ' + e.message)
    }
}
/*==================================================== CALCULATOR FOR TABLE CARTON INVOICE DETAIL ====================================================*/
/**
 * cal total qty carton
 * 
 * @author : ANS806 - 2018/04/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalQtyCarton() {
	try {
		var _total_qty = 0;
		$('#table-carton tbody tr').each(function() {
			var qty = $(this).find('.TXT_qty_table_carton').val();
			qty = qty.replace(/,/g, '');
			if (qty != '') {
				_total_qty = _roundNumeric(parseFloat(_total_qty) +  parseFloat(qty), 2, 2);
			}
		});
		$('.DSP_carton_total_qty').text(_convertMoneyToIntAndContra(_total_qty));
	} catch(e) {
        console.log('calTotalQtyCarton: ' + e.message)
    }
}
/**
 * total net weight carton
 * 
 * @author : ANS806 - 2018/04/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalNetWeightCarton() {
	try {
		var _total_net_weight = 0;
		$('#table-carton tbody tr').each(function() {
			var net_weight = $(this).find('.DSP_total_net_weight_table_carton').text();
			net_weight = net_weight.replace(/,/g, '');
			if (net_weight != '') {
				_total_net_weight = _roundNumeric(parseFloat(_total_net_weight) +  parseFloat(net_weight), 2, 2);
			}
		});
		$('.DSP_carton_total_net_weight').text(_convertMoneyToIntAndContra(_total_net_weight));
	} catch(e) {
        console.log('calTotalNetWeightCarton:' + e.message)
    }
}
/**
 * cal total gross weight carton
 * 
 * @author : ANS806 - 2018/04/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalGrossWeightCarton() {
	try {
		var _total_gross_weight = 0;
		$('#table-carton tbody tr').each(function() {
			var gross_weight = $(this).find('.DSP_total_gross_weight_table_carton').text();
			gross_weight = gross_weight.replace(/,/g, '');
			if (gross_weight != '') {
				_total_gross_weight = _roundNumeric(parseFloat(_total_gross_weight) +  parseFloat(gross_weight), 2, 2);
			}
		});
		$('.DSP_carton_total_gross_weight').text(_convertMoneyToIntAndContra(_total_gross_weight));
	
	} catch(e) {
        console.log('calTotalGrossWeightCarton: ' + e.message)
    }
}
/**
 * cal total measure carton
 * 
 * @author : ANS806 - 2018/04/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalMeasureCarton() {
	try {
		var _total_measure = 0;
		$('#table-carton tbody tr').each(function() {
			var measure = $(this).find('.DSP_total_measure_table_carton').text();
			measure = measure.replace(/,/g, '');
			if (measure != '') {
				_total_measure = _roundNumeric(parseFloat(_total_measure) +  parseFloat(measure), 2, 2);
			}
		});
		$('.DSP_carton_total_measure').text(_convertMoneyToIntAndContra(_total_measure));
	} catch(e) {
        console.log('calTotalMeasureCarton: ' + e.message)
    }
}
/**
 * cal net weight carton
 * 
 * @author : ANS806 - 2018/04/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calNetWeightCarton() {
	try {
		var qty 				= 	parent.$('.cal-carton').find('.TXT_qty_table_carton').val().replace(/,/g, '');
		var unit_net_weight 	=	parent.$('.cal-carton').find('.TXT_unit_net_weight_table_carton').val().replace(/,/g, '');
		var net_weight 			=	_roundNumeric(parseFloat(qty) * parseFloat(unit_net_weight), 2, 2);
		if (isNaN(net_weight)) {
			net_weight = 0;
		}
		parent.$('.cal-carton').find('.DSP_total_net_weight_table_carton').text(_convertMoneyToIntAndContra(net_weight));
	} catch(e) {
        console.log('calNetWeightCarton' + e.message)
    }
}
/**
 * cal gross weight carton
 * 
 * @author : ANS806 - 2018/04/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calGrossWeightCarton() {
	try {
		var qty 				= 	parent.$('.cal-carton').find('.TXT_qty_table_carton').val().replace(/,/g, '');
		var unit_gross_weight 	=	parent.$('.cal-carton').find('.TXT_unit_gross_weight_table_carton').val().replace(/,/g, '');
		var gross_weight 		=	_roundNumeric(parseFloat(qty) * parseFloat(unit_gross_weight), 2, 2);
		if (isNaN(gross_weight)) {
			gross_weight = 0;
		}
		parent.$('.cal-carton').find('.DSP_total_gross_weight_table_carton').text(_convertMoneyToIntAndContra(gross_weight));
	} catch(e) {
        console.log('calGrossWeightCarton' + e.message)
    }
}
/**
 * cal measure carton
 * 
 * @author : ANS806 - 2018/04/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calMeasureCarton() {
	try {
		var qty 				= 	parent.$('.cal-carton').find('.TXT_qty_table_carton').val().replace(/,/g, '');
		var unit_measure_qty 	=	parent.$('.cal-carton').find('.TXT_unit_measure_table_carton').val().replace(/,/g, '');
		var measure 			=	_roundNumeric(parseFloat(qty) * parseFloat(unit_measure_qty), 2, 2);
		if (isNaN(measure)) {
			measure = 0;
		}
		parent.$('.cal-carton').find('.DSP_total_measure_table_carton').text(_convertMoneyToIntAndContra(measure));
	} catch(e) {
        console.log('calMeasureCarton' + e.message)
    }
}
/**
 * cal number carton
 * 
 * @author : ANS806 - 2018/04/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calNumberCarton() {
	try {
		var newData 	=	[];
		var num_carton	=	'';
		$('#table-carton tbody tr').each(function() {
			var num_carton = $(this).find('.TXT_carton_number').val();
			if (num_carton != '' && num_carton != '0') {
				newData.push(num_carton);
			}
			
		});
		var unique_array = []
		if (newData.length > 0) {
			for(var j = 0; j < newData.length; j++) {
		        if(unique_array.indexOf(newData[j]) == -1) {
		            unique_array.push(newData[j]);
		        }
		    }
		    num_carton	=	unique_array.length;
		}
		$('.DSP_total_carton_num').text(num_carton);
		tableCartonTotal(unique_array);
		return unique_array;
	} catch(e) {
        console.log('calNumberCarton' + e.message)
    }
}
/**
 * table carton number
 * 
 * @author : ANS806 - 2018/04/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function tableCartonTotal(data) {
	try {
		$('#table-carton-total tbody').empty();
		var html		=	'';
			data 		=	data.sort(function(a, b){return a-b});
		var data_total 	=	[];
		var data_carton = 	{};
		for (var i = 0; i < data.length; i++) {
			var carton_num 		= data[i];
			var net_weight 		= 0;
			var gross_weight 	= 0;
			var measure 		= 0;
			$('#table-carton tbody tr').each(function() {
				if (carton_num == $(this).find('.TXT_carton_number').val()) {
					var net_weight_carton = $(this).find('.DSP_total_net_weight_table_carton').text().replace(/,/g, '');
					if (net_weight_carton == '') {
						net_weight_carton = 0;
					}
					var gross_weight_carton = $(this).find('.DSP_total_gross_weight_table_carton').text().replace(/,/g, '');
					if (gross_weight_carton == '') {
						gross_weight_carton = 0;
					}
					var measure_carton = $(this).find('.DSP_total_measure_table_carton').text().replace(/,/g, '');
					if (measure_carton == '') {
						measure_carton = 0;
					}
					net_weight 		= parseFloat(net_weight) + parseFloat(net_weight_carton);
					gross_weight 	= parseFloat(gross_weight) + parseFloat(gross_weight_carton);
					measure 		= parseFloat(measure) + parseFloat(measure_carton);
				}
				data_carton 	=	{
					carton_num 		: carton_num,
					net_weight 		: _roundNumeric(net_weight, 2, 2),
					gross_weight 	: _roundNumeric(gross_weight, 2, 2),
					measure 		: _roundNumeric(measure, 2, 2),
				};
				
			});
			data_total.push(data_carton);
		}
		for (var i = 0; i < data_total.length; i++) {
			html += '<tr class="">'
			html +=		'<td class="text-right DSP_carton_number">'+data_total[i]['carton_num']+'</td>'
			html +=		'<td class="text-right DSP_net_weight">'+_convertMoneyToIntAndContra(data_total[i]['net_weight'])+'</td>'
			html +=		'<td class="text-right DSP_gross_weight">'+_convertMoneyToIntAndContra(data_total[i]['gross_weight'])+'</td>'
			html +=		'<td class="text-right DSP_measure">'+_convertMoneyToIntAndContra(data_total[i]['measure'])+'</td>'
			html +=	'</tr>';
		}
		$('#table-carton-total tbody').append(html);
		calTotalCarton();
	} catch(e) {
        console.log('tableCartonTotal' + e.message)
    }
}
/**
 * cal total in table carton
 * 
 * @author : ANS806 - 2018/04/11 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function calTotalCarton() {
	try {
		var _total_net_weight 	= 0;
		var _total_gross_weight = 0;
		var _total_measure		= 0;
		$('#table-carton-total tbody tr').each(function() {
			var _total_net_weight_carton 	= $(this).find('.DSP_net_weight').text();
			_total_net_weight_carton 		= _total_net_weight_carton.replace(/,/g, '');
			if (_total_net_weight_carton != '') {
				_total_net_weight = _roundNumeric(parseFloat(_total_net_weight) +  parseFloat(_total_net_weight_carton), 2, 2);
			}
			var _total_gross_weight_carton 	= $(this).find('.DSP_gross_weight').text();
			_total_gross_weight_carton 		= _total_gross_weight_carton.replace(/,/g, '');
			if (_total_gross_weight_carton != '') {
				_total_gross_weight = _roundNumeric(parseFloat(_total_gross_weight) +  parseFloat(_total_gross_weight_carton), 2, 2);
			}
			var _total_measure_carton 	= $(this).find('.DSP_measure').text();
			_total_measure_carton 		= _total_measure_carton.replace(/,/g, '');
			if (_total_measure_carton != '') {
				_total_measure = _roundNumeric(parseFloat(_total_measure) +  parseFloat(_total_measure_carton), 2, 2);
			}
		});
		$('.DSP_total_net_weight_carton').text(_convertMoneyToIntAndContra(_total_net_weight));
		$('.DSP_total_gross_weight_carton').text(_convertMoneyToIntAndContra(_total_gross_weight));
		$('.DSP_total_measure_carton').text(_convertMoneyToIntAndContra(_total_measure));
	} catch(e) {
        console.log('calTotalCarton' + e.message)
    }
}