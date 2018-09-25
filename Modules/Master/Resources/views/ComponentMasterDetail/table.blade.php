<table class="table table-hover table-bordered table-xxs table-text table-purchase-price table-list" id="table-purchase-price">
	<thead>
		<tr class="col-table-header sticky-row">
			<th class="text-center" style="width: 50px">メイン</th>
			<th class="text-center" style="width: 100px">発注先コード</th>
			<th class="text-center">発注先名</th>
			<th class="text-center" style="width: 120px">標準単価（JPY）</th>
			<th class="text-center" style="width: 120px">標準単価（USD）</th>
			<th class="text-center" style="width: 120px">標準単価（EUR）</th>
			<th class="text-center" style="width: 120px">発注ロットサイズ</th>
			<th class="text-center" style="width: 120px">下限ロットサイズ</th>
			<th class="text-center" style="width: 120px">上限ロットサイズ</th>
			<th class="text-center" style="width: 40px">
				<button type="button" class="btn btn-primary btn-icon btn-add-row" id="BTN_Add_line" style="float: right;">
					<i class="icon-plus3"></i>
				</button>
			</th>
		</tr>
	</thead>
	<tbody>
		@if(isset($purchase_price_data) && !empty($purchase_price_data))
			@foreach($purchase_price_data as $row)
				<tr class="">
					<td class="drag-handler text-center">
						<label class="radio-inline" style="margin-top: -20px;">
							<input class="RDI_main styled" name="RDI_main" type="radio"
								@if ($row['ini_target_div'] == '1')
									checked 
								@endif
							>
						</label>
					</td>
					<td class="text-center">
						@includeIf('popup.searchsuppliers', array(
							'class_cd'    => 'TXT_purchaser_order_cd',
							'is_required' => true,
							'val'		  => $row['supplier_cd'],
							'is_nm'       => false
						))
					</td>
					<td class="text-left">
						<div class="tooltip-overflow max-width20 DSP_purchaser_order_nm" data-toggle="tooltip" data-placement="top" title="">{{ $row['client_nm'] }}</div>
					</td>
					<td class="text-left">
						<input type="text" class="form-control price TXT_standard_unit_price_JPY currency_JPY" value="{{ $row['purchase_unit_price_JPY'] }}" maxlength="11" real_len="10" decimal_len="2">
					</td>
					<td class="text-center">
						<input type="text" class="form-control price TXT_standard_unit_price_USD" value="{{ $row['purchase_unit_price_USD'] }}" maxlength="11" real_len="10" decimal_len="2">
					</td>
					<td class="text-center text-right">
						<input type="text" class="form-control price TXT_standard_unit_price_EUR" value="{{ $row['purchase_unit_price_EUR'] }}" maxlength="11" real_len="10" decimal_len="2">
					</td>
					<td class="text-right">
						<input type="text" class="form-control quantity TXT_order_lot_size" value="{{ $row['order_lot_qty'] }}" maxlength="6">
					</td>
					<td class="text-center">
						<input type="text" class="form-control quantity TXT_lower_limit_lot_size" value="{{ $row['lower_limit_lot_qty'] }}" maxlength="6">
					</td>
					<td class="text-center">
						<input type="text" class="form-control quantity TXT_maximum_lot_size" value="{{ $row['upper_limit_lot_qty'] }}" maxlength="6">
					</td>
					<td class="w-40px text-center">
						<button type="button" class="form-control remove-row BTN_Delete_line">
							<span class="icon-cross2 text-danger"></span>
						</button>
					</td>
				</tr>
			@endforeach
		@else
			<tr class="">
				<td class="drag-handler text-center">
					<label class="radio-inline" style="margin-top: -20px;">
						<input class="RDI_main styled" name="RDI_main" type="radio" checked="checked">
					</label>
				</td>
				<td class="text-center">
					@includeIf('popup.searchsuppliers', array(
						'class_cd'    => 'TXT_purchaser_order_cd',
						'is_required' => true,
						'is_nm'       => false
					))
				</td>
				<td class="text-left">
					<div class="tooltip-overflow max-width20 DSP_purchaser_order_nm" data-toggle="tooltip" data-placement="top" title=""></div>
				</td>
				<td class="text-left">
					<input type="text" class="form-control price TXT_standard_unit_price_JPY currency_JPY" value="" maxlength="11" real_len="10" decimal_len="2">
				</td>
				<td class="text-center">
					<input type="text" class="form-control price TXT_standard_unit_price_USD" value="" maxlength="11" real_len="10" decimal_len="2">
				</td>
				<td class="text-center text-right">
					<input type="text" class="form-control price TXT_standard_unit_price_EUR" value="" maxlength="11" real_len="10" decimal_len="2">
				</td>
				<td class="text-right">
					<input type="text" class="form-control quantity TXT_order_lot_size" value="" maxlength="6">
				</td>
				<td class="text-center">
					<input type="text" class="form-control quantity TXT_lower_limit_lot_size" value="" maxlength="6">
				</td>
				<td class="text-center">
					<input type="text" class="form-control quantity TXT_maximum_lot_size" value="" maxlength="6">
				</td>
				<td class="w-40px text-center">
					<button type="button" class="form-control remove-row BTN_Delete_line">
						<span class="icon-cross2 text-danger"></span>
					</button>
				</td>
			</tr>
		@endif
	</tbody>
</table>