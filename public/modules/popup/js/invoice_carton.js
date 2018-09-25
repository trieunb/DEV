/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要		:	
 * 作成日		:	2018/03/06
 * 作成者		:	Trieunb
 *
 * 更新日		:
 * 更新者		:
 * 更新内容		:
 *
 * @package		:	shipment
 * @copyright	:	Copyright (c) ANS
 * @version		:	1.0.0
 * ****************************************************************************
 */

$(document).ready(function () {
	initEvents();
});

/**
 * init Events
 * @author  :   DuyTP - 2017/06/09 - create
 * @param
 * @return
 */
function initEvents() {
	try {
	
		//click btn ok
 		$(document).on('click', '#btn-carton-ok', function() {
 			var table_carton 	= parent.$('.popup-carton').parents('body').find('#table-carton tbody tr');
 			var carton_from 	= parseInt($('.carton-from').val());
 			var carton_to 		= parseInt($('.carton-to').val());
 			if (carton_from > carton_to) {
 				jMessage('E484');
 			} else {
 				var inv_detail_no 			= parent.$('.popup-carton').parent('tr').find('.DSP_inv_detail_no').text();
				var product_nm 				= parent.$('.popup-carton').parent('tr').find('.TXT_description').val();
				var product_cd 				= parent.$('.popup-carton').parent('tr').find('.TXT_product_cd').val();
				// var qty 					= parent.$('.popup-carton').parent('tr').find('.TXT_qty').val();
				var unit_net_weight_div 	= parent.$('.popup-carton').parent('tr').find('.CMB_unit_net_weight_div').val();
				var unit_net_weight 		= parent.$('.popup-carton').parent('tr').find('.TXT_unit_net_weight').val();
				var unit_gross_weight 		= parent.$('.popup-carton').parent('tr').find('.TXT_unit_gross_weight').val();
				var unit_measure_price 		= parent.$('.popup-carton').parent('tr').find('.CMB_unit_measure_price').val();
				var unit_measure_qty 		= parent.$('.popup-carton').parent('tr').find('.TXT_unit_measure_qty').val();
 				var data = {
 					inv_detail_no 			: inv_detail_no,
 					product_nm 				: product_nm,
 					product_cd 				: product_cd,
 					// qty 					: qty,
 					unit_net_weight_div 	: unit_net_weight_div,
 					unit_net_weight 		: unit_net_weight,
 					// total_net_weight 		: _roundNumeric(parseFloat(qty)*parseFloat(unit_net_weight)),
 					unit_gross_weight 		: unit_gross_weight,
 					// total_gross_weight 		: _roundNumeric(parseFloat(qty)*parseFloat(unit_gross_weight)),
 					unit_measure_price 		: unit_measure_price,
 					unit_measure_qty 		: unit_measure_qty,
 					// total_measure 			: _roundNumeric(parseFloat(qty)*parseFloat(unit_measure_qty)),
 				};
 				// console.log(data)
 				var row_index = 0;
 				table_carton.each(function() {
 					row_index = $(this).index();
 					if (row_index >= (carton_from-1) && row_index <= (carton_to-1)) {
 						$(this).find(':input').val('');
 						$(this).find('.DSP_fwd_detail_no_table_carton').text(data.inv_detail_no);
 						$(this).find('.DSP_product_nm_table_carton').text(data.product_nm);
 						$(this).find('.DSP_product_nm_table_carton').attr('title', data.product_nm);
 						$(this).find('.TXT_product_cd_table_carton').val(data.product_cd);
 						// $(this).find('.TXT_qty_table_carton').val(data.qty)
 						if (data.unit_net_weight_div != '') {
							$(this).find('.CMB_unit_net_weight_div_table_carton option[value='+data.unit_net_weight_div+']').prop('selected', true);
						} else {
							$(this).find('.CMB_unit_net_weight_div_table_carton option:first').prop('selected', true);
						}
 						$(this).find('.TXT_unit_net_weight_table_carton').val(data.unit_net_weight);
 						// $(this).find('.DSP_total_net_weight_table_carton').text(data.total_net_weight)
 						$(this).find('.TXT_unit_gross_weight_table_carton').val(data.unit_gross_weight);
 						// $(this).find('.DSP_total_gross_weight_table_carton').text(data.total_gross_weight)
 						if (data.unit_measure_price != '') {
							$(this).find('.CMB_unit_measure_table_carton option[value='+data.unit_measure_price+']').prop('selected', true);
						} else {
							$(this).find('.CMB_unit_measure_table_carton option:first').prop('selected', true);
						}
 						$(this).find('.TXT_unit_measure_table_carton').val(data.unit_measure_qty);
 						// $(this).find('.DSP_total_measure_table_carton').text(data.total_measure)
 					}
 				});
 				parent.$.colorbox.close();
 			}
 		});
 		//click btn cencel
 		$(document).on('click', '#btn-carton-cancel', function() {
 			parent.$.colorbox.close();
 		});
 		//change carton-from
 		$(document).on('change', '.carton-from', function() {
 			try {
 				$('.carton-to').val($.mbTrim($(this).val()));
 			} catch (e) {
	        console.log('change: carton-from' + e.message);
		    }
 		});
	} catch (e) {
		console.log('initEvents: ' + e.message);
	}
}

/**
 * get data for purchase request list search condition
 * 
 * @author : ANS806 - 2018/02/21 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function getDataSearch() {
	try {
		var data = {
				'product_cd'   				: $('.DSP_product_cd').text().trim(),
	            'count'   					: $('.TXT_count').val().trim(),
	            'manufacture_no_from'   	: $('.TXT_manufacture_no_from').val().trim(),
	            'manufacture_no_to'   		: $('.TXT_manufacture_no_to').val().trim(),
	            'serial_no_from'   			: $('.TXT_serial_no_from').val().trim(),
	            'serial_no_to'   			: $('.TXT_serial_no_to').val().trim(),
	            'out_warehouse_div'			: parent.$('.popup-shipmentserial').parents('body').find('.out_warehouse_div').text().trim(),
	        };
        return data;
	} catch (e) {
        console.log('getDataSearch' + e.message);
    }
}