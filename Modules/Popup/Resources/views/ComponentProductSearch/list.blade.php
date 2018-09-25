<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div id="table-listing">
			<div class="table-responsive table-custom sticky-table sticky-headers sticky-ltr-cells">
				<table class="table table-hover table-bordered table-xxs table-list table-striped table-component-product" id="table-component-product">
					<thead>
						<tr class="col-table-header text-center">
							<th class="text-center" width="5%">コード</th>
							<th class="text-center" width="7%">名称和文</th>
							<th class="text-center" width="7%">名称英文</th>
							<th class="text-center" width="7%">規格名</th>
							<th class="text-center" width="3%">単位</th>
							<th class="text-center" width="3%">在庫管理有無</th>
						</tr>
					</thead>
					<tbody class="results">
						@if(isset($List) && !empty($List))
						@foreach($List as $val)
						<tr class ="tr-table">
							<td class="text-left componentproduct_cd">{{$val['item_cd'] or ''}}</td>
							<td class="text-left componentproduct_nm">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$val['item_nm_j'] or ''}}">{{$val['item_nm_j'] or ''}}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$val['item_nm_e'] or ''}}">{{$val['item_nm_e'] or ''}}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$val['specification'] or ''}}">{{$val['specification'] or ''}}</div>
							</td>
							<td class="text-left">{{$val['unit_qty_div'] or ''}}</td>
							<td class="text-left">{{$val['stock_management_div'] or ''}}</td>
						</tr>
						@endforeach
						@else
						<tr>
							<td colspan="6" class="text-center dataTables_empty">&nbsp;</td>
						</tr>
						@endif
					</tbody>
				</table>
			</div>
			</div>
			<div class="nav-pagination">
				{!! $paginate !!}
			</div>
		</div>
	</div>
</div>