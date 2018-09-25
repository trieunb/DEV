<table class="table table-hover table-bordered table-xxs table-text table-purchase table-list" id="table-purchase">
	<thead>
		<tr class="col-table-header sticky-row">
			<th class="text-center" style="width: 40px">NO</th>
			<th class="text-center" width="10%">コード</th>
			<th class="text-center">品名</th>
			<th class="text-center">規格</th>
			<th class="text-center" style="width: 100px">数量</th>
			<th class="text-center" style="width: 80px">単位</th>
			<th class="text-center" style="width: 100px">単価</th>
			<th class="text-center" style="width: 150px">金額</th>
			<th class="text-center" style="width: 150px">備考</th>
			<th class="text-center" style="width: 40px">
				<button type="button" class="btn btn-primary btn-icon btn-add-row" id="btn-add-row" style="float: right;">
					<i class="icon-plus3"></i>
				</button>
			</th>
		</tr>
	</thead>
	<tbody>
	@if(isset($buy_d) && !empty($buy_d))
		@foreach($buy_d as $key => $val)
		<tr>
			<td class="drag-handler text-center DSP_buy_detail_no">{{ $val['buy_detail_no'] or '' }}</td>
			<td class="text-center" style="width: 170px;">
				@includeIf('popup.searchcomponent', array(
														'class_cd' 		=> 'TXT_parts_cd',
														'is_required'	=>	true,
														'val'		  	=> $val['parts_cd']
													))
			</td>
			<td class="text-left DSP_item_nm_j">
				<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{ $val['item_nm_j'] or '' }}">{{ $val['item_nm_j'] or '' }}</div>
			</td>
			<td class="text-left DSP_specification">
				<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{ $val['specification'] or '' }}">{{ $val['specification'] or '' }}</div>
			</td>
			<td class="text-center">
				<input type="tel" class="form-control money numeric required TXT_buy_qty" value="{{ $val['buy_qty'] or '' }}" maxlength="6">
			</td>
			<td class="text-center text-right DSP_unit">
				{{ $val['lib_val_nm_j'] or '' }}
			</td>
			<td class="text-center">
				<input type="text" class="form-control price TXT_buy_unit_price" value="{{ $val['buy_unit_price'] or '' }}" maxlength="12">
			</td>
			<td class="text-center">
				<input type="text" class="form-control money numeric TXT_buy_detail_amt" disabled="true" value="{{ $val['buy_detail_amt'] or '' }}" maxlength="12">
				<span class="hidden tax_rate"></span>
				<input type="text" class="hidden form-control money TXT_buy_detail_tax" value="{{ $val['buy_detail_tax'] or '' }}" maxlength="12">
			</td>
			<td class="text-center">
				<input type="text" class="form-control TXT_detail_remarks" value="{{ $val['detail_remarks'] or '' }}" maxlength="200">
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