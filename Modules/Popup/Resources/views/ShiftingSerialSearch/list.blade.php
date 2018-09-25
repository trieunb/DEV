<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="col-md-12">
			<div class="nav-pagination form-group">
				<div class="pull-right">
					<button type="button" id="btn-shifting-serial-ok" class="btn btn-primary btn-icon w-60px">OK</button>
					<button type="button" id="btn-shifting-serial-cancel" class="btn btn-primary btn-icon">キャンセル</button>
				</div>
			</div>
			<div class="table-responsive table-custom form-group">
				<table class="table table-hover table-bordered table-xxs table-list table-shifting tablesorter" id="table-shifting">
					<thead>
					<tr class="col-table-header text-center">
						<th class="col-1 text-center check-box" width="1%">
							<input type="checkbox" id="check-all" name="">
						</th>
						<th class="text-center" width="13%">シリアル番号</th>
						<th class="text-center" width="13%">製造指示番号</th>
						<th class="text-center" width="73%">製造状況</th>
					</tr>
					</thead>
					<tbody>
					@if(isset($List) && !empty($List))
						@foreach($List as $val)
						<tr class="tr-table">
							<td class="col-1 text-center">
								<input type="checkbox" name="" class="check-all">
							</td>
							<td class="text-left DSP_serial_no">{{ $val['serial_no'] or '' }}</td>
							<td class="text-left DSP_manufacture_no">{{ $val['manufacture_no'] or '' }}</td>
							<td class="text-left DSP_production_status_div">{{ $val['production_status_div'] or '' }}</td>
						</tr>
						@endforeach
					@else
						<tr>
							<td colspan="4" class="text-center dataTables_empty">&nbsp;</td>
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