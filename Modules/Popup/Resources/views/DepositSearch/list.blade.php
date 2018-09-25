<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive table-custom sticky-table sticky-headers sticky-ltr-cells">
				<table class="table table-hover table-striped table-bordered table-xxs table-listtable-striped table-deposit tablesorter" id="table-deposit">
					<thead>
						<tr class="col-table-header sticky-row">
							<!-- deposit_date -->
							<th class="text-center sticky-cell th-first padding-30" width="1%">入金日</th>
							<!-- deposit_no -->
							<th class="text-center sticky-cell th-last padding-30" width="5%">入金No</th>
							<!-- deposit_div_nm -->
							<th class="text-center" width="3%">入金分類</th>
							<!-- client_cd -->
							<th class="text-center" width="3%">取引先コード</th>
							<!-- client_nm -->
							<th class="text-center" width="5%">取引先名</th>
							<!-- country_div_nm -->
							<th class="text-center" width="1%">国</th>
							<!-- rcv_no -->
							<th class="text-center padding-25" width="3%">受注No</th>
							<!-- inv_no -->
							<th class="text-center padding-25" width="3%">InvoiceNo</th>
							<!-- split_deposit_div_nm -->
							<th class="text-center" width="3%">分割入金管理</th>
							<!-- initial_deposit_date -->
							<th class="text-center" width="3%">当初入金予定日</th>
							<!-- deposit_bank_div_nm -->
							<th class="text-center" width="3%">入金銀行</th>
							<!-- deposit_way_div_nm -->
							<th class="text-center" width="3%">入金区分</th>
							<!-- currency_div_nm -->
							<th class="text-center" width="5%">通貨</th>
							<!-- remittance_amt -->
							<th class="text-center" width="2%">先方送付額</th>
							<!-- fee_foreign_amt -->
							<th class="text-center" width="4%">手数料（外貨）</th>
							<!-- fee_yen_amt -->
							<th class="text-center" width="5%">手数料（円貨）</th>
							<!-- arrival_foreign_amt -->
							<th class="text-center" width="6%">着金額（外貨）</th>
							<!-- deposit_yen_amt -->
							<th class="text-center" width="5%">円入金額</th>
							<!-- exchange_rate -->
							<th class="text-center" width="6%">レート</th>
							<!-- rate_confirm_div_nm -->
							<th class="text-center" width="6%">レート区分</th>
							<!-- notices -->
							<th class="text-center" width="3%">特記事項</th>
							<!-- inside_remarks -->
							<th class="text-center" width="5%">社内用備考</th>
							<!-- deposit_detail_no -->
							<th class="text-center" width="5%">行番号</th>
							<!-- pi_no -->
							<th class="text-center padding-40" width="3%">PiNo</th>
							<!-- description -->
							<th class="text-center" width="6%">製品名</th>
							<!-- qty -->
							<th class="text-center" width="5%">数量</th>
							<!-- unit_price -->
							<th class="text-center" width="5%">単価</th>
							<!-- detail_amt -->
							<th class="text-center" width="5%">金額</th>
						</tr>
					</thead>
					<tbody class="results">
						@if(isset($depositList) && !empty($depositList))
							@foreach($depositList as $deposit)
								<tr class="tr-table">
									<!-- deposit_date -->
									<td class="text-center sticky-cell th-first">{{$deposit['deposit_date'] or ''}}</td>
									<!-- deposit_no -->
									<td class="text-left sticky-cell th-last DSP_deposit_no">{{$deposit['deposit_no'] or ''}}</td>
									<!-- deposit_div_nm -->
									<td class="text-left">{{$deposit['deposit_div_nm'] or ''}}</td>
									<!-- client_cd -->
									<td class="text-left">{{$deposit['client_cd'] or ''}}</td>
									<!-- client_nm -->
									<td class="text-left max-width50">
										<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$deposit['client_nm'] or ''}}">{{$deposit['client_nm'] or ''}}</div>
									</td>
									<!-- country_div_nm -->
									<td class="text-left max-width50">
										<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$deposit['country_div_nm'] or ''}}">{{$deposit['country_div_nm'] or ''}}</div>
									</td>
									<!-- rcv_no -->
									<td class="text-left">{{$deposit['rcv_no'] or ''}}</td>
									<!-- inv_no -->
									<td class="text-left">{{$deposit['inv_no'] or ''}}</td>
									<!-- split_deposit_div_nm -->
									<td class="text-left max-width50">
										<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$deposit['split_deposit_div_nm'] or ''}}">{{$deposit['split_deposit_div_nm'] or ''}}</div>
									</td>
									<!-- initial_deposit_date -->
									<td class="text-center">{{$deposit['initial_deposit_date'] or ''}}</td>
									<!-- deposit_bank_div_nm -->
									<td class="text-left max-width50">
										<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$deposit['deposit_bank_div_nm'] or ''}}">{{$deposit['deposit_bank_div_nm'] or ''}}</div>
									</td>
									<!-- deposit_way_div_nm -->
									<td class="text-left max-width50">
										<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$deposit['deposit_way_div_nm'] or ''}}">{{$deposit['deposit_way_div_nm'] or ''}}</div>
									</td>
									<!-- currency_div_nm -->
									<td class="text-left">{{$deposit['currency_div_nm'] or ''}}</td>
									<!-- remittance_amt -->
									<td class="text-right">{{$deposit['remittance_amt'] or ''}}</td>
									<!-- fee_foreign_amt -->
									<td class="text-right">{{$deposit['fee_foreign_amt'] or ''}}</td>
									<!-- fee_yen_amt -->
									<td class="text-right">{{$deposit['fee_yen_amt'] or ''}}</td>
									<!-- arrival_foreign_amt -->
									<td class="text-right">{{$deposit['arrival_foreign_amt'] or ''}}</td>
									<!-- deposit_yen_amt -->
									<td class="text-right">{{$deposit['deposit_yen_amt'] or ''}}</td>
									<!-- exchange_rate -->
									<td class="text-right">{{$deposit['exchange_rate'] or ''}}</td>
									<!-- rate_confirm_div_nm -->
									<td class="text-left max-width50">
										<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$deposit['rate_confirm_div_nm'] or ''}}">{{$deposit['rate_confirm_div_nm'] or ''}}</div>
									</td>
									<!-- notices -->
									<td class="text-left max-width50">
										<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$deposit['notices'] or ''}}">{{$deposit['notices'] or ''}}</div>
									</td>
									<!-- inside_remarks -->
									<td class="text-left max-width50">
										<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$deposit['inside_remarks'] or ''}}">{{$deposit['inside_remarks'] or ''}}</div>
									</td>
									<!-- rcv_detail_no -->
									<td class="text-left">{{$deposit['rcv_detail_no'] or ''}}</td>
									<!-- pi_no -->
									<td class="text-left">{{$deposit['pi_no'] or ''}}</td>
									<!-- description -->
									<td class="text-left max-width50">
										<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$deposit['description'] or ''}}">{{$deposit['description'] or ''}}</div>
									</td>
									<!-- qty -->
									<td class="text-right">{{$deposit['qty'] or ''}}</td>
									<!-- unit_price -->
									<td class="text-right">{{$deposit['unit_price'] or ''}}</td>
									<!-- detail_amt -->
									<td class="text-right">{{$deposit['detail_amt'] or ''}}</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="28" class="text-center dataTables_empty">&nbsp;</td>
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