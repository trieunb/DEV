<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter or ''!!}
				{!! $paginate or ''!!}
			</div>
			<div class="table-responsive table-custom ">
				<table class="table table-hover table-bordered table-xxs table-list table-accept" id="table-accept">
					<thead>
					<tr class="col-table-header text-center">
						<th class="col-1 text-center check-box" width="1.5%">
							<input type="checkbox" id="check-all" name="">
						</th>
						<!-- rcv_no -->
						<th class="text-center" width="7%">受注No</th>
						<!-- rcv_date -->
						<th class="text-center th-date">受注日</th>
						<!-- rcv_detail_no -->
						<th class="text-center" width="4%">行番号</th>
						<!-- cust_nm -->
						<th class="text-center" width="18%">取引先名</th>
						<!-- cust_country_div -->
						<th class="text-center" width="2%">国</th>
						<!-- product_cd -->
						<th class="text-center" width="5%">Code</th>
						<!-- description -->
						<th class="text-center" width="10%">Item Name</th>
						<!-- unit_price -->
						<th class="text-center" width="6%">Unit Price</th>
						<!-- qty -->
						<th class="text-center" width="3%">Q'ty</th>
						<!-- currency_div -->
						<th class="text-center" width="5%">Cur</th>
						<!-- detail_amt -->
						<th class="text-center" width="4%">Amount</th>
						<!-- rcv_status_div -->
						<th class="text-center" width="8%">ステータス</th>
						<!-- deposit_no -->
						<th class="text-center" width="8%">最終入金No</th>
					</tr>
					</thead>
					<tbody>
						@if(isset($rcvList) && !empty($rcvList))
							@foreach($rcvList as $rcv)
								<tr class="tr-table">
									<td class="col-1 text-center">
										<input type="checkbox" name="" class="check-all">
										<span class="hidden DSP_rcv_status_div">{{$rcv['rcv_status_div'] or ''}}</span>
									</td>
									<!-- rcv_no -->
									<td class="text-left DSP_rcv_no accept_cd">{{$rcv['rcv_no'] or ''}}</td>

									<!-- rcv_date -->
									<td class="text-center">{{$rcv['rcv_date'] or ''}}</td>

									<!-- rcv_detail_no -->
									<td class="text-right">{{$rcv['rcv_detail_no'] or ''}}</td>

									<!-- cust_nm -->
									<td class="text-left max-width30">
										<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$rcv['cust_nm'] or ''}}">{{$rcv['cust_nm'] or ''}}</div>
									</td>

									<!-- cust_country_div -->
									<td class="text-left max-width30">
										<div class="tooltip-overflow " data-container="body" data-toggle="tooltip" title="{{$rcv['cust_country_div'] or ''}}">{{$rcv['cust_country_div'] or ''}}</div>
									</td>

									<!-- product_cd -->
									<td class="text-left">{{$rcv['product_cd'] or ''}}</td>
									
									<!-- description -->
									<td class="text-left max-width30">
										<div class="tooltip-overflow " data-container="body" data-toggle="tooltip" title="{{$rcv['description'] or ''}}">{{$rcv['description'] or ''}}</div>
									</td>

									<!-- unit_price -->
									<td class="text-right">{{$rcv['unit_price'] or ''}}</td>

									<!-- qty -->
									<td class="text-right">{{$rcv['qty'] or ''}}</td>

									<!-- currency_div -->
									<td class="text-left">{{$rcv['currency_div'] or ''}}</td>

									<!-- detail_amt -->
									<td class="text-right">
										<span class="hidden">{{$rcv['detail_amt'] or ''}}</span>
										{{$rcv['detail_amt'] or ''}}
									</td>

									<!-- rcv_status_div -->
									<td class="text-left">
										<span class="DSP_rcv_status_div_nm">{{$rcv['rcv_status_div_nm'] or ''}}</span>
									</td>

									<!-- deposit_no -->
									<td class="text-left DSP_deposit_no">{{$rcv['deposit_no'] or ''}}</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="21" class="text-center dataTables_empty">&nbsp;</td>
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