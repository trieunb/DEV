<table class="table table-hover table-bordered table-xxs table-text table-shipment table-list table-provisional-shipment-detail" id="table-provisional-shipment-detail">
	<thead>
		<tr class="col-table-header sticky-row">
			<th class="text-center" width="5%">NO</th>
			<th class="text-center" width="10%">CODE</th>
			<th class="text-center" width="35%">製品名</th>
			<th class="text-center" width="10%">受注数</th>
			<th class="text-center" width="10%">未出荷数</th>
			<th class="text-center" width="15%">指示数</th>
			<th class="text-center" width="10%">残数</th>
			<th class="text-center" width="5%"></th>
		</tr>
	</thead>
	<tbody>
		@if(isset($received_info_table))
			@foreach($received_info_table as $row)
		<tr class="">
			<td class="drag-handler text-center DSP_no" width="5%">
				@if(isset($row['fwd_detail_no']))
					{{ $row['fwd_detail_no'] or ''}}
				@else
					{{ $row['rcv_detail_no'] or ''}}
				@endif
			</td>
			<td class="text-right DSP_code" width="10%">
				{{ $row['product_cd'] or '' }}
			</td>
			<td class="text-left DSP_product_nm" width="35%">
				<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{ $row['description'] }}">{{ $row['description'] or '' }}</div>
			</td>
			<td class="text-right DSP_rcv_amount" width="10%">
				{{ $row['qty'] or '' }}
			</td>
			<td class="text-right DSP_remain_amount " width="10%">
				{{ $row['remaining_qty'] or '' }}
			</td>
			<td class="text-center" width="15%">
				<input type="text"  class="form-control required TXT_instructed_amount quantity" value="{{ $row['fwd_qty'] or '' }}">
			</td>
			<td class="text-center DSP_remaining numeric" width="10%">{{ $row['remaining'] or 0 }}</td>
			<td class="text-center BTN_clear" width="5%">
				<button type="button" class="form-control remove-row BTN_clear">
						<span class="icon-cross2 text-danger"></span>
				</button>
			</td>
			<td class="rcv_detail_no hide">{{ $row['rcv_detail_no'] or ''}}</td>
		</tr>
			@endforeach
		@else
			<tr>
				<td colspan="20" class="text-center dataTables_empty">&nbsp;</td>
			</tr>						
		@endif
	</tbody>
</table>