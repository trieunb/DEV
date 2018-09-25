<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination" style="width: 100%; display: inline-block;">
				<div class="col-md-3 col-sm-3 wrrap-pagi-fillter">
				{!! $fillter !!}
				</div>
				@if(isset($piList) && !empty($piList))
				<div class="col-md-4 col-sm-7 group-date-table">
					<label class="col-md-4 col-sm-4 control-label text-right text-bold required">受注日</label>
					<div class="col-md-6 col-sm-6">
						<input value="{{ date('Y/m/d') }}" type="tel" name="TXT_rcv_date" class="datepicker form-control TXT_rcv_date required" placeholder="yyyy/mm/dd" maxlength="10">
					</div>
				</div>
				@endif
				<div class="col-md-5" style="float: right;">
				{!! $paginate !!}
				</div>
			</div>
			<div class="table-responsive table-custom ">
				<table class="table table-hover table-bordered table-xxs table-list tablesorter" id="pi-order" style="">
					<thead>
					<tr class="col-table-header text-center">
						<th class="col-1 text-center" width="1.5%">
							<input type="checkbox" id="check-all" name="">
						</th>
						<th class="text-center" width="5%">PINO</th>
						<th class="text-center th-date" width="3%">見積日</th>
						<th class="text-center" width="5%">取引先コード</th>
						<th class="text-center" width="20%">取引先名</th>
						<th class="text-center" width="2%">通貨</th>
						<th class="text-center" width="3%">合計金額</th>
					</tr>
					</thead>
					<tbody>
						@if(isset($piList) && !empty($piList))
							@foreach($piList as $pi)
							<tr>
								<td class="col-1 text-center">
									<input type="checkbox" name="" class="check-all">
								</td>
								<td class="text-left DSP_pi_no">{{ $pi['pi_no'] }}</td>
								<td class="text-center DSP_pi_date">{{ $pi['pi_date'] }}</td>
								<td class="text-left">{{ $pi['cust_cd'] }}</td>
								<td class="text-left">
									<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{ $pi['cust_nm'] }}">{{ $pi['cust_nm'] }}</div>
								</td>
								<td class="text-left">{{ $pi['currency_nm'] }}</td>
								<td class="text-right">
									{{ $pi['total_amt'] }}
								</td>
							</tr>
							@endforeach
						@else
						<tr>
							<td colspan="7" class="text-center dataTables_empty">&nbsp;</td>
						</tr>
						@endif
					</tbody>
				</table>
			</div>
			<div class="nav-pagination">
				{!! $paginate !!}
			</div>
		</div>
	</div>
</div>