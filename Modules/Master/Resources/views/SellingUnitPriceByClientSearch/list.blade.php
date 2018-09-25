<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive table-custom ">
				<table class="table table-hover table-bordered table-xxs table-list table-master-ml60" id="table-sales-price">
					<thead>
					<tr class="col-table-header text-center">
						<th class="text-center" width="6%">製品コード</th>
						<th class="text-center" width="15%">製品名和文</th>
						<th class="text-center" width="7%">取引先コード</th>
						<th class="text-center" width="15%">取引先名</th>
						<th class="text-center" width="8%">開始日</th>
						<th class="text-center" width="4%">単価(JPY)</th>
						<th class="text-center" width="4%">単価(USD)</th>
						<th class="text-center" width="4%">単価(USD)</th>
						<th class="text-center" width="15%">備考</th>
					</tr>
					</thead>
					<tbody>
						@if(isset($salesPriceList) && !empty($salesPriceList))
							@foreach($salesPriceList as $price)
								<tr class="tr-table">
									<td class="text-left product_cd">{{$price['product_cd']}}</td>
									<td class="text-left">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$price['product_nm_j']}}">{{$price['product_nm_j']}}</div>
									</td>
									<td class="text-left client_cd">{{$price['client_cd']}}</td>
									<td class="text-left">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$price['client_nm']}}">{{$price['client_nm']}}</div>
									</td>
									<td class="text-center apply_st_date">{{$price['apply_st_date']}}</td>
									<td class="text-right">
										{{preg_replace('/\.00$/','',$price['unit_price_JPY'])}}
										<span class="hidden">{{preg_replace('/\.00$/','',$price['unit_price_JPY'])}}</span>
									</td>
									<td class="text-right">
										{{preg_replace('/\.00$/','',$price['unit_price_USD'])}}
										<span class="hidden">{{preg_replace('/\.00$/','',$price['unit_price_USD'])}}</span>
									</td>
									<td class="text-right">
										{{preg_replace('/\.00$/','',$price['unit_price_EUR'])}}
										<span class="hidden">{{preg_replace('/\.00$/','',$price['unit_price_EUR'])}}</span>
									</td>
									<td class="text-left">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$price['remarks']}}">{{$price['remarks']}}</div>
									</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="9" class="text-center dataTables_empty">&nbsp;</td>
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