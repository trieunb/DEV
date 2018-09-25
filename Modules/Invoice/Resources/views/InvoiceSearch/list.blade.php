<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive table-custom ">
				<table class="table table-hover table-bordered table-xxs table-list table-invoice tablesorter" id="table-invoice">
					<thead>
					<tr class="col-table-header text-center">
						<th class="col-1 text-center check-box" width="1.5%">
							<input type="checkbox" id="check-all" name="">
						</th>
						<!-- inv_data_div -->
						<th class="text-center" width="7%">区分</th>
						<!-- inv_date -->
						<th class="text-center th-date">Invoice Date</th>
						<!-- inv_no -->
						<th class="text-center" width="8%">Invoice No</th>
						<!-- inv_detail_no -->
						<th class="text-center" width="4%">行番号</th>
						<!-- rcv_no -->
						<th class="text-center" width="8%">受注No</th>
						<!-- pi_no -->
						<th class="text-center" width="9%">PINo</th>
						<!-- cust_nm -->
						<th class="text-center" width="10%">取引先名</th>
						<!-- country_nm -->
						<th class="text-center" width="5%">国</th>
						<!-- product_cd -->
						<th class="text-center" width="5%">Code</th>
						<!-- description -->
						<th class="text-center" width="8%">Item Name</th>
						<!-- unit_price -->
						<th class="text-center" width="5%">Unit Price</th>
						<!-- qty -->
						<th class="text-center" width="4%">Q'ty</th>
						<!-- currency_div -->
						<th class="text-center" width="3%">Cur</th>
						<!-- detail_amt -->
						<th class="text-center" width="5%">Amount</th>
					</tr>
					</thead>
					<tbody>
						@if(isset($invoiceList) && !empty($invoiceList))
							@foreach($invoiceList as $invoice)
								<tr>
									<td class="col-1 text-center">
										<input type="checkbox" name="" class="check-all">
									</td>

									<!-- inv_data_div_nm -->
									<td class="text-left">{{$invoice['inv_data_div_nm'] or ''}}</td>

									<!-- inv_date -->
									<td class="text-center nowrap">{{$invoice['inv_date'] or ''}}</td>

									<!-- inv_no -->
									<td class="text-left inv_no nowrap">{{$invoice['inv_no'] or ''}}</td>

									<!-- inv_detail_no -->
									<td class="text-left">{{$invoice['inv_detail_no'] or ''}}</td>

									<!-- rcv_no -->
									<td class="text-left nowrap">{{$invoice['rcv_no'] or ''}}</td>

									<!-- pi_no -->
									<td class="text-left nowrap">{{$invoice['pi_no'] or ''}}</td>

									<!-- cust_nm -->
									<td class="text-left">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$invoice['cust_nm'] or ''}}">
											{{$invoice['cust_nm'] or ''}}
										</div>
									</td>

									<!-- country_nm -->
									<td class="text-left">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$invoice['country_nm'] or ''}}">
											{{$invoice['country_nm'] or ''}}
										</div>
									</td>

									<!-- product_cd -->
									<td class="text-left">{{$invoice['product_cd'] or ''}}</td>

									<!-- description -->
									<td class="text-left">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$invoice['description'] or ''}}">
											{{$invoice['description'] or ''}}
										</div>
									</td>

									<!-- unit_price -->
									<td class="text-right">{{$invoice['unit_price'] or ''}}</td>

									<!-- qty -->
									<td class="text-right">{{$invoice['qty'] or ''}}</td>

									<!-- currency_div -->
									<td class="text-left">{{$invoice['currency_div'] or ''}}</td>

									<!-- detail_amt -->
									<td class="text-right">{{$invoice['detail_amt'] or ''}}</td>
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
				{!! $paginate !!}
			</div>
		</div>
	</div>
</div>