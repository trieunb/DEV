<table class="table table-hover table-bordered table-xxs table-text table-stock-manage table-list" id="table-stock-manage">
	<thead>
		<tr class="col-table-header sticky-row">
			<th class="text-center" style="width: 40px">NO</th>
			<th class="text-center">品目コード</th>
			<th class="text-center" width="25%">品目名</th>
			<th class="text-center" width="20%">規格名</th>
			<th class="text-center" style="width: 120px">シリアル番号</th>
			<th class="text-center" style="width: 120px">数量</th>
			<th class="text-center">摘要</th>
			<th class="text-center" style="width: 40px">
				<button type="button" class="btn btn-primary btn-icon btn-add-row" id="btn-add-row" style="float: right;">
					<i class="icon-plus3"></i>
				</button>
			</th>
		</tr>
	</thead>
	<tbody>
	@if(isset($in_out_d) && !empty($in_out_d))
		@foreach($in_out_d as $key => $val)
		<tr class="">
			<td class="drag-handler text-center DSP_in_out_detail_no">{{ $val['in_out_detail_no'] }}</td>
			<td class="text-center" style="width: 120px;">
				@includeIf('popup.searchcomponentproduct', array(
																'class_cd'		=>	'TXT_item_cd',
																'val'			=> 	$val['item_cd'],
																'is_nm'			=>	false,
																'is_required'	=>	true,
															))
			</td>
			<td class="text-left">
				<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{ $val['item_nm'] }}">{{ $val['item_nm'] }}</div>
			</td>
			<td class="text-left">
				<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{ $val['specification'] }}">{{ $val['specification'] }}</div>
			</td>
			<td class="text-left">
				<input type="text" name="TXT_serial_no" class="form-control TXT_serial_no text-right numeric-only" maxlength="7" value="{{ $val['serial_no'] }}" @if ($val['serial_management_div'] == '0' || $val['serial_management_div'] == '') disabled="disabled" @endif>
				<span class="hide DSP_serial_management_div">{{ $val['serial_management_div'] }}</span>
				<span class="hide DSP_stock_management_div">{{ $val['stock_management_div'] }}</span>
			</td>
			<td class="text-center">
				<input type="text" name="TXT_in_out_qty" class="form-control quantity TXT_in_out_qty required" maxlength="8" value="{{ $val['in_out_qty'] }}" @if ($val['serial_management_div'] == '1') disabled="disabled" @endif>
			</td>
			<td class="text-center">
				<input type="text" class="TXT_detail_remarks form-control" value="{{ $val['detail_remarks'] }}" maxlength="200">
			</td>
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