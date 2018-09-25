<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination" style="width: 100%;
    display: inline-block;">
				<div class="col-md-3 col-sm-3">
				{!! $fillter !!}
				</div>
				<div class="col-md-4 col-sm-7 group-date-table">
					<label class="col-md-4 col-sm-4 control-label text-right text-bold required">受入実施日</label>
					<div class="col-md-6 col-sm-6">
						<input value="2016/12/22" type="tel" class="datepicker form-control">
					</div>
				</div>
				<div class="col-md-5" style="float: right;">
				{!! $paginate !!}
				</div>
			</div>
			<div class="table-responsive table-custom ">
				<table class="table table-hover table-bordered table-xxs table-list table-stocking" id="table-stocking">
					<thead>
					<tr class="col-table-header text-center">
						<th class="col-1 text-center" width="1.5%">
							<input type="checkbox" id="check-all" name="">
						</th>
						<th class="text-center" width="6%">製造指示書番号</th>
						<th class="text-center" width="5%">指示書発行日</th>
						<th class="text-center" width="12%">製品名</th>
						<th class="text-center" width="2%">製造指示数</th>
						<th class="text-center" width="4%">検品数</th>
						<th class="text-center" width="2%">未入荷数</th>
						<th class="text-center" width="2%">外注加工金額</th>
						<th class="text-center" width="5%">受入処理</th>
						<th class="text-center" width="10%">備考</th>
					</tr>
					</thead>
					<tbody>
						@for ($i = 0; $i <= 3; $i++)
						<tr>
							<td class="col-1 text-center">
								<input type="checkbox" name="" class="check-all">
							</td>
							<td class="text-left">123456</td>
							<td class="text-center">2017/01/01</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="製品名 製品名 製品名 製品名 製品名 製品名 製品名 製品名 製品名">製品名 製品名 製品名 製品名 製品名 製品名 製品名 製品名 製品名</div>
							</td>
							<td class="text-right">
								999,999,999
								<span class="hidden">999,999,999</span>
							</td>
							<td class="text-right">
								<input type="text" class="form-control numeric" value="999,999,999">
							</td>
							<td class="text-right">
								999,999,999
								<span class="hidden">999,999,999</span>
							</td>
							<td class="text-right">
								999,999,999
								<span class="hidden">999,999,999</span>
							</td>
							<td class="text-left" data-toggle="tooltip" data-placement="top" title="Text">
								<select class="form-control">
									<option value="0"></option>
									<option value="1" selected>Text</option>
								</select>
							</td>
							<td class="text-center" data-toggle="tooltip" data-placement="top" title="Text">
								<input type="text" class="form-control" value="Text">
							</td>
						</tr>
						@endfor
						<tr>
							<td class="col-1 text-center">
								<input type="checkbox" name="" class="check-all">
							</td>
							<td class="text-left">987654</td>
							<td class="text-center">2017/01/22</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="XXXXXXXXXXX YYYYYYYYYY ZZZZZZZZZZZ">XXXXXXXXXXX YYYYYYYYYY ZZZZZZZZZZZ</div>
							</td>
							<td class="text-right">
								999,999,999
								<span class="hidden">999,999,111</span>
							</td>
							<td class="text-right">
								<input type="text" class="form-control numeric" value="999,999,111">
							</td>
							<td class="text-right">
								999,999,999
								<span class="hidden">999,999,111</span>
							</td>
							<td class="text-right">
								999,999,999
								<span class="hidden">999,999,111</span>
							</td>
							<td class="text-left" data-toggle="tooltip" data-placement="top" title="Text">
								<select class="form-control">
									<option value="0"></option>
									<option value="1">Text</option>
									<option value="2" selected>YY</option>
								</select>
							</td>
							<td class="text-center" data-toggle="tooltip" data-placement="top" title="Text">
								<input type="text" class="form-control" value="YYY">
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