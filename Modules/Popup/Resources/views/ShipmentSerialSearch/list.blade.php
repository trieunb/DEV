<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="col-md-12">
			<div class="nav-pagination form-group">
				<div class="pull-right">
					<button type="button" id="btn-shipment-serial-ok" class="btn btn-primary btn-icon w-60px">OK</button>
					<button type="button" id="btn-shipment-serial-cencel" class="btn btn-primary btn-icon">キャンセル</button>
				</div>
			</div>
			<div class="table-responsive table-custom form-group">
				<table class="table table-hover table-bordered table-xxs table-list table-shipment tablesorter" id="table-shipment">
					<thead>
					<tr class="col-table-header text-center">
						<th class="col-1 text-center check-box" width="1%">
							<input type="checkbox" id="check-all" name="">
						</th>
						<th class="text-center" width="13%">シリアル番号</th>
						<th class="text-center" width="13%">製造指示番号</th>
						<th class="text-center" width="15%">製造状況</th>
						<th class="text-center" width="16%">出荷指示No</th>
						<th class="text-center" width="30%">取引先名</th>
						<th class="text-center" width="12%">引渡予定日</th>
					</tr>
					</thead>
					<tbody>
					@if(isset($List) && !empty($List))
						@foreach($List as $val)
						<tr>
							<td class="col-1 text-center">
								<input type="checkbox" name="" class="check-all">
							</td>
							<td class="text-left DSP_serial_no">{{ $val['serial_no'] or '' }}</td>
							<td class="text-left DSP_shipment_id">{{ $val['manufacture_no'] or '' }}</td>
							<td class="text-left">{{ $val['production_status_div'] or '' }}</td>
							<td class="text-left">{{ $val['fwd_no'] or '' }}</td>
							<td class="text-left max-width50">
								<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{ $val['cust_nm'] or '' }}">{{ $val['cust_nm'] or '' }}</div>
							</td>
							<td class="text-center">{{ $val['deliver_date'] or '' }}</td>
						</tr>
						@endforeach
					@else
						<tr>
							<td colspan="15" class="text-center dataTables_empty">&nbsp;</td>
						</tr>
					@endif
					</tbody>
				</table>
			</div>
			<div class="nav-pagination">
			</div>
		</div>
	</div>
</div>