<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive table-custom ">
				<table class="table table-hover table-bordered table-xxs table-working-time table-list tablesorter" id="table-working-time">
					<thead>
					<tr class="col-table-header text-center">
						<th class="text-center" width="6%">作業日報番号</th>
						<th class="text-center" width="6%">製造指示番号</th>
						<th class="text-center th-date">作業実施日</th>
						<th class="text-center" width="6%">作業担当者コード</th>
						<th class="text-center" width="20%">作業担当者名</th>
						<th class="text-center" width="10%">作業時間</th>
					</tr>
					</thead>
					<tbody>
						@if(isset($workingtimeList) && !empty($workingtimeList))
							@foreach($workingtimeList as $workingtime)
								<tr class="tr-table">
									<td class="text-right work_report_no">{{$workingtime['work_report_no']}}</td>
									<td class="text-right">{{$workingtime['manufacture_no']}}</td>
									<td class="text-center">{{$workingtime['work_date']}}</td>
									<td class="text-right">{{$workingtime['work_user_cd']}}</td>
									<td class="text-left">
										<div class="tooltip-overflow max-width20" style="max-width: 20px;" data-toggle="tooltip" data-placement="top" title="{{$workingtime['user_nm_j']}}">{{$workingtime['user_nm_j']}}</div>
									</td>
									<td class="text-left">{{$workingtime['work_div']}}</td>
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