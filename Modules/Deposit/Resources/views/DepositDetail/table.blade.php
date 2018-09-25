<table class="table table-hover table-bordered table-xxs table-list table-deposit" id="table-deposit">
	<thead>
		<tr class="col-table-header text-center">
			<th class="text-center w-40px" style="width: 40px">No</th>
			<th class="text-center">製品名</th>
			<th class="text-center" width="15%">数量</th>
			<th class="text-center" width="15%">単価</th>
			<th class="text-center" width="15%">金額</th>
		</tr>
	</thead>
	<tbody>
	@if(isset($rcv_d_data) && !empty($rcv_d_data))
		@foreach($rcv_d_data as $row)
		<tr>
			<td class="drag-handler text-center DSP_rcv_detail_no">{{ $row['rcv_detail_no'] }}</td>
			<td class="text-left DSP_description">
				<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{ $row['description'] }}">{{ $row['description'] }}</div>
			</td>
			<td class="text-right DSP_qty">{{ $row['qty'] }}</td>
			<td class="text-right DSP_unit_price">{{ $row['unit_price'] }}</td>
			<td class="text-right DSP_detail_amt">{{ $row['detail_atm'] }}</td>
		</tr>
		@endforeach
	@endif
	</tbody>
</table>