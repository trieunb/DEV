<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive table-custom ">
				<table class="table table-hover table-bordered table-xxs table-list table-stock-manager tablesorter" id="table-stock-manager">
					<thead>
					<tr class="col-table-header">
						<th class="text-center" width="8%">倉庫コード</th>
						<th class="text-center" width="18%">倉庫名</th>
						<th class="text-center" width="8%">品目コード</th>
						<th class="text-center">品目名</th>
						<th class="text-center" width="15%">規格</th>
						<th class="text-center" width="10%">現在庫数</th>
						<th class="text-center" width="10%">有効在庫数</th>
					</tr>
					</thead>
					<tbody>
						@if(isset($stockList) && !empty($stockList))
							@foreach($stockList as $stock)
								<tr>
									<td class="text-left DSP_warehouse_cd">{{$stock['warehouse_cd']}}</td>
									<td class="text-left">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$stock['warehouse_nm']}}">{{$stock['warehouse_nm']}}</div>
									</td>
									<td class="text-left">{{$stock['item_cd']}}</td>
									<td class="text-left">
										<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$stock['item_nm_j']}}">{{$stock['item_nm_j']}}</div>
									</td>
									<td class="text-left max-width20">
										<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$stock['specification']}}">{{$stock['specification']}}</div>
									</td>
									<td class="text-right">
										{{$stock['stock_current_qty']}}
										<span class="hidden">{{$stock['stock_current_qty']}}</span>
									</td>
									<td class="text-right">
										{{$stock['stock_available_qty']}}
										<span class="hidden">{{$stock['stock_available_qty']}}</span>
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