<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
<div class="form-group">
	<div class="col-md-1">
		<input type="text" class="form-control text-right" id="count-add-blank-row" value="10">
	</div>
	<div class="col-md-1">
		<button class="btn btn-primary btn-icon" id="btn-add-blank-row">空行追加</button>
	</div>
</div> 
<div class="table-responsive sticky-table sticky-headers sticky-ltr-cells" style="max-height: 300px !important;">
	<table class="table table-hover table-bordered table-xxs table-text table-shipment-second table-list" id="table-text">
		<thead>
			<tr class="col-table-header sticky-row">
				<th rowspan="2" class="text-center" width="3%">NO</th>
				<th rowspan="2" class="text-center" width="8%">カートンNo</th>
				<th rowspan="2" class="text-center" width="25%">製品</th>
				<th rowspan="2" class="text-center" width="7%">数量</th>
				<th rowspan="2" class="text-center" width="5%">重量単位</th>
				<th colspan="2" class="text-center">Net重量</th>
				<th colspan="2" class="text-center">Gross重量</th>
				<th rowspan="2" class="text-center" width="5%">容積単位</th>
				<th colspan="2" class="text-center">容積</th>
				<th rowspan="2" class="text-center" width="3%" style="text-align: center;">
					<button type="button" class="btn btn-primary btn-icon btn-add-row" id="btn-add-row-second">
						<i class="icon-plus3"></i>
					</button>
				</th>
			</tr>
			<tr class="col-table-header sticky-row">
				<th class="text-center" width="7%">単位</th>
				<th class="text-center" width="7%">計</th>
				<th class="text-center" width="7%">単位</th>
				<th class="text-center" width="7%">計</th>
				<th class="text-center" width="7%">単位</th>
				<th class="text-center" width="7%">計</th>
			</tr>
		</thead>
		<tbody>
			@for($i = 0; $i < 3; $i ++)
			<tr class="">
				<td class="drag-handler text-center">{{$i+1}}</td>
				<td class="text-center">
					<input type="text" class="form-control" value="50">
					<!-- <div class="button" style="float: right;">
						<button class="btn">セット</button>
						<button class="btn">クリア</button>
					</div> -->
				</td>
				<td class="text-center">
					<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="製品名和文 製品名和文 製品名和文 製品名和文 製品名和文 製品名和文">製品名和文 製品名和文 製品名和文 製品名和文 製品名和文 製品名和文</div>
				</td>
				<td class="text-center">
					<input type="text" class="form-control" value="9999999">
				</td>
				<td class="text-center">
					<select class="form-control unit_w_div">
					</select>
				</td>
				<td class="text-center">
					<input type="text" class="form-control" value="9999999">
				</td>
				<td class="text-center">
					9999999
				</td>
				<td class="text-center">
					<input type="text" class="form-control" value="9999999">
				</td>
				<td class="text-center">
					9999999
				</td>
				<td class="text-center">
					<select class="form-control unit_m_div">
					</select>
				</td>
				<td class="text-center">
					<input type="text" class="form-control" value="9999999">
				</td>
				<td class="text-center">
					9999999
				</td>
				<td class="w-40px text-center">
					<button type="button" class="form-control remove-row">
						<span class="icon-cross2 text-danger"></span>
					</button>
				</td>
			</tr>
			@endfor
		</tbody>
	</table>
</div>
<div class="row">
	<table class="table table-xxs table-text">
		<tbody>
			<tr>
				<td></td>
				<td></td>
				<td class="text-right"><strong>合計</strong></td>
				<td class="text-left">999,999</td>
				<td></td>
				<td></td>
				<td class="text-left">999,999</td>
				<td></td>
				<td class="text-left">999,999</td>
				<td></td>
				<td></td>
				<td class="text-left">999,999</td>
			</tr>
		</tbody>
	</table>
</div>
<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
<div class="row"><div class="col-md-4"><p>総カートン数  999</p></div></div>
<div class="form-group" style="margin-top: 5px;">
	<div class="col-md-4 table-responsive">
		<table class="table table-hover table-bordered table-xxs table-text">
			<thead>
				<tr class="col-table-header">
					<th class="text-center">カートンNo</th>
					<th class="text-center">NET重量</th>
					<th class="text-center">ＧROSS重量</th>
					<th class="text-center">容積</th>
				</tr>
			</thead>
			<tbody>
				<tr class="">
					<td class="text-right">99999</td>
					<td class="">99999</td>
					<td class="text-right">99999</td>
					<td class="">99999</td>
				</tr>
				<tr class="">
					<td class="text-right">99999</td>
					<td class="">99999</td>
					<td class="text-right">99999</td>
					<td class="">99999</td>
				</tr>
				<tr class="">
					<td class="text-right">99999</td>
					<td class="">99999</td>
					<td class="text-right">99999</td>
					<td class="">99999</td>
				</tr>
				
			</tbody>
			<tfoot>
				<tr class="">
					<td class="col-table-header text-right">総合計</td>
					<td class="">99999</td>
					<td class="text-right">99999</td>
					<td class="">99999</td>
				</tr>
			</tfoot>
		</table>
	</div>
	<div class="col-md-8 table-responsive" style="float: right;">
		<div class="form-group">
			<label class="col-md-2 col-sm-2 control-label text-right text-bold">貯蔵管理責任者</label>
			<div class="col-md-3">
				<select class="form-control storage_manager_div">
				</select>
			</div>
		</div>
	</div>
</div>