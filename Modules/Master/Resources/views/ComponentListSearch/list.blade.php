<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive table-custom ">
				<table class="table table-hover table-bordered table-xxs table-list table-component-list" id="table-component-list">
					<thead>
					<tr class="col-table-header text-center">
						<th class="text-center" width="5%">製品コード</th>
						<th class="text-center" width="15%">製品名和文</th>
						<th class="text-center" width="15%">製品規格名</th>
						<th class="text-center" width="5%">部品コード</th>
						<th class="text-center" width="15%">部品名和文</th>
						<th class="text-center" width="15%">部品規格名</th>
						<th class="text-center" width="5%">必要部品数</th>
						<th class="text-center" width="5%">開始日</th>
						<th class="text-center" width="5%">終了日</th>
					</tr>
					</thead>
					<tbody>
					@if(isset($List) && !empty($List))
						@foreach($List as $val)
						<tr>
							<td class="text-left parent_item_cd">{{$val['parent_item_cd'] or ''}}</td>
							<td class="text-left max-width20">
								<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$val['parent_item_nm_j'] or ''}}">{{$val['parent_item_nm_j'] or ''}}</div>
							</td>
							<td class="text-left max-width20">
								<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$val['parent_specification'] or ''}}">{{$val['parent_specification'] or ''}}</div>
							</td>
							<td class="text-left child_item_cd">{{$val['child_item_cd'] or ''}}</td>
							<td class="text-left max-width20">
								<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$val['child_item_nm_j'] or ''}}">{{$val['child_item_nm_j'] or ''}}</div>
							</td>
							<td class="text-left max-width20">
								<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$val['child_specification'] or ''}}">{{$val['child_specification'] or ''}}</div>
							</td>
							<td class="text-right">{{$val['child_item_qty'] or ''}}</td>
							<td class="text-center">{{$val['apply_st_date'] or ''}}</td>
							<td class="text-center">{{$val['apply_ed_date'] or ''}}</td>
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