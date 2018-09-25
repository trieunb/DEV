<table class="table table-hover table-bordered table-xxs table-text table-order table-list" id="table-order">
	<thead>
		<tr class="col-table-header sticky-row">
			<!-- DSP_no -->
			<th class="text-center" style="width: 40px">NO</th>
			<!-- TXT_parts_cd -->
			<th class="text-center">コード</th>
			<!-- DSP_item_nm -->
			<th class="text-center" style="min-width: 200px;">品名</th>
			<!-- DSP_specification -->
			<th class="text-center" style="min-width: 200px">規格</th>
			<!-- TXT_parts_order_qty -->
			<th class="text-center" style="width: 100px">数量</th>
			<!-- DSP_unit -->
			<th class="text-center" style="width: 80px">単位</th>
			<!-- TXT_parts_order_unit_price -->
			<th class="text-center" style="width: 100px">単価</th>
			<!-- TXT_parts_order_unit_amt -->
			<th class="text-center" style="width: 100px">金額</th>
			<!-- TXT_detail_remarks -->
			<th class="text-center" style="min-width: 200px">備考</th>
			<!-- BTN_Delete line -->
			<th class="text-center" style="width: 40px">
				<button type="button" class="btn btn-primary btn-icon btn-add-row" id="btn-add-row" style="float: right;">
					<i class="icon-plus3"></i>
				</button>
			</th>
		</tr>
	</thead>
	<tbody>
		
    @if(isset($parts_order_info_d))
		@php
			$i = 1;
		@endphp

		@foreach($parts_order_info_d as $row)
		<tr class="tr-table">
			<input type="hidden" class="tax_detail" value="{{ $row['parts_order_detail_tax'] or 0 }}">
			<!-- DSP_no -->
			<td class="text-center DSP_no drag-handler">{{ $i++ }}</td>

			<!-- TXT_parts_cd -->
			<td class="text-center" style="width: 120px">
				@includeIf('popup.searchcomponent', array(
											'class_cd' 		=> 'TXT_parts_cd',
								    		'val' 			=> $row['parts_cd'],
								          	'is_required'	=> true,
								          	'disable_ime' 	=> 'disable-ime',
								          	'is_nm'			=> false
										))
			</td>
			
			<!-- DSP_item_nm -->
			<td class="text-left" style="min-width: 200px">
				<div class="tooltip-overflow max-width20 DSP_item_nm" data-toggle="tooltip" data-placement="top" title="{{ $row['item_nm'] or '' }}">
					{{ $row['item_nm'] or '' }}
				</div>
			</td>
			
			<!-- DSP_specification -->
			<td class="text-left" style="min-width: 200px">
				<div class="tooltip-overflow max-width20 DSP_specification"  data-toggle="tooltip" data-placement="top" title="{{ $row['specification'] or '' }}">
					{{ $row['specification'] or '' }}
				</div>
			</td>

			<!-- TXT_parts_order_qty -->
			<td class="text-center">
				<input type="text" class="form-control quantity TXT_parts_order_qty" value="{{ $row['parts_order_qty'] or '' }}" maxlength="8">
			</td>

			<!-- DSP_unit -->
			<td class="text-center text-right DSP_unit">
				{{ $row['unit_qty_div_nm'] or '' }}
			</td>

			<!-- TXT_parts_order_unit_price -->
			<td class="text-center">
				<input type="text" class="form-control price TXT_parts_order_unit_price" value="{{ $row['parts_order_unit_price'] or '' }}">
			</td>

			<!-- TXT_parts_order_unit_amt -->
			<td class="text-center">
				<input type="text" class="form-control numeric price TXT_parts_order_unit_amt" value="{{ $row['parts_order_amt'] or '' }}">
			</td>

			<!-- TXT_detail_remarks -->
			<td class="text-center" data-toggle="tooltip" data-placement="top" title="{{ $row['detail_remarks'] or '' }}" style="min-width: 200px">
				<input type="text" class="form-control TXT_detail_remarks" value="{{ $row['detail_remarks'] or '' }}">
			</td>

			<!-- BTN_Delete line -->
			<td class="w-40px text-center">
				<button type="button" class="form-control remove-row">
					<span class="icon-cross2 text-danger"></span>
				</button>
			</td>
		</tr>
    	@endforeach					
	@endif
	</tbody>
</table>