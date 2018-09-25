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
						<th class="text-center" width="8%">入出庫No</th>
						<th class="text-center" width="8%">入出庫区分</th>
						<th class="text-center th-date">入出庫日</th>
						<th class="text-center" width="8%">入力種別</th>
						<th class="text-center" width="8%">倉庫コード</th>
						<th class="text-center" width="15%">倉庫名</th>
						<th class="text-center" width="8%">部品コード</th>
						<th class="text-center" width="15%">部品名</th>
						<th class="text-center" width="5%">数量</th>
						<th class="text-center">摘要</th>
					</tr>
					</thead>
					<tbody>
						@for ($i = 0; $i <= 5; $i++)
						<tr>
							<td class="text-left stockmanage_id">99999</td>
							<td class="text-left stockmanager_nm">XXXXX</td>
							<td class="text-center">2017/10/10</td>
							<td class="text-left">XXXXX</td>
							<td class="text-left">XXXXX</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="倉庫名 倉庫名 倉庫名 倉庫名 倉庫名 倉庫名">倉庫名 倉庫名 倉庫名 倉庫名 倉庫名 倉庫名</div>
							</td>
							<td class="text-left">99999</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="部品名 部品名 部品名 部品名 部品名 部品名 部品名 部品名">部品名 部品名 部品名 部品名 部品名 部品名 部品名 部品名</div>
							</td>
							<td class="text-right">
								9,999,999
								<span class="hidden">9,999,999</span>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="摘要 摘要 摘要 摘要 摘要 摘要 摘要 摘要 摘要 摘要 摘要">摘要 摘要 摘要 摘要 摘要 摘要 摘要 摘要 摘要 摘要 摘要</div>
							</td>
						</tr>
						@endfor
						<tr>
							<td class="text-left stockmanage_id">911119</td>
							<td class="text-left stockmanager_nm">ZZZZZZZZZZ</td>
							<td class="text-center">2017/11/10</td>
							<td class="text-left">AAAAAAAAA</td>
							<td class="text-left">BBBBBBBBBB</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="XXXXXXXXXX ZZZZZZZZZZ YYYYYYYYYY">XXXXXXXXXX ZZZZZZZZZZ YYYYYYYYYY</div>
							</td>
							<td class="text-left">99199</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="XXXXXXXXXX ZZZZZZZZZZ YYYYYYYYYY">XXXXXXXXXX ZZZZZZZZZZ YYYYYYYYYY</div>
							</td>
							<td class="text-right">
								9,999,991
								<span class="hidden">9,999,991</span>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="XXXXXXXXXX ZZZZZZZZZZ YYYYYYYYYY">XXXXXXXXXX ZZZZZZZZZZ YYYYYYYYYY</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="nav-pagination">
				{!! $paginate !!}
			</div>
		</div>
	</div>
</div>