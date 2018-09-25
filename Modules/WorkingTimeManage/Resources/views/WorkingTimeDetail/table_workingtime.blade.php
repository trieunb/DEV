<div class="table-responsive sticky-table sticky-headers sticky-ltr-cells">
	<table class="table table-hover table-bordered table-xxs table-text table-working-time table-list" id="table-working-time">
		<thead>
			<tr class="col-table-header sticky-row">
				<th class="text-center" style="width: 40px">No</th>
				<th class="text-center">製造指示番号</th>
				<th class="text-center">製品名</th>
				<th class="text-center" style="width: 300px">作業時間</th>
				<th class="text-center" style="width: 400px">メモ</th>
				<th class="text-center" style="width: 40px">
					<button type="button" class="btn btn-primary btn-icon btn-add-row" id="btn-add-row" style="float: right;">
						<i class="icon-plus3"></i>
					</button>
				</th>
			</tr>
		</thead>
		<tbody>
			@if(isset($workingTimeList) && !empty($workingTimeList))
				@foreach($workingTimeList as $k=>$workingtime)
					<tr class="">
						<td class="drag-handler text-center DSP_work_report_detail_no">{{ $k+1 }}</td>
						<td class="text-center" style="width: 170px;">
							@includeIf('popup.searchmanufacturinginstruction', 
							array(
								'val'=>isset($workingtime['manufacture_no']) ? $workingtime['manufacture_no'] : ''
								)
							)
						</td>
						<td class="text-left">
							<div class="tooltip-overflow max-width20 DSP_item_nm_j" data-toggle="tooltip" data-placement="top" title="{{ $workingtime['item_nm_j'] or ''}}">{{ $workingtime['item_nm_j'] or ''}}</div>
						</td>
						<td class="text-left">
							<div class="input-group-select input-group-left" style="">
								<select class="form-control select-group-wrk work_hour_div " data-selected="{{ $workingtime['work_hour_div'] or ''}}">
									<option></option>
									@if(isset($work_hour_div))
										@foreach($work_hour_div as $k=>$v)
											<option value="{{$v['lib_val_cd']}}" 
													data-ctl1="{{$v['lib_val_ctl1']}}" data-ctl2="{{$v['lib_val_ctl2']}}"
													data-ctl5="{{$v['lib_val_ctl5']}}" data-ctl6="{{$v['lib_val_ctl6']}}"
													data-nm-j="{{$v['lib_val_nm_j']}}" data-nm-e="{{$v['lib_val_nm_e']}}"
													data-ini_target_div="{{$v['ini_target_div']}}">
												{{$v['lib_val_nm_j']}} 
											</option>
										@endforeach
									@endif
								</select>
								<span>時間</span>
							</div>
							<div class="input-group-select input-group-right" style="">
							<select class="form-control select-group-wrk work_time_div " data-selected="{{ $workingtime['work_time_div'] or ''}}">
								<option></option>
									@if(isset($work_time_div))
										@foreach($work_time_div as $k=>$v)
											<option value="{{$v['lib_val_cd']}}" 
													data-ctl1="{{$v['lib_val_ctl1']}}" data-ctl2="{{$v['lib_val_ctl2']}}"
													data-ctl5="{{$v['lib_val_ctl5']}}" data-ctl6="{{$v['lib_val_ctl6']}}"
													data-nm-j="{{$v['lib_val_nm_j']}}" data-nm-e="{{$v['lib_val_nm_e']}}"
													data-ini_target_div="{{$v['ini_target_div']}}">
												{{$v['lib_val_nm_j']}} 
											</option>
										@endforeach
									@endif
							</select>
							<span>分</span>
							</div>
						</td>
						<td class="text-left">
							<input type="text" class="form-control TXT_memo" value="{{ $workingtime['memo'] or ''}}" maxlength="200" >
						</td>
						<td class="w-40px text-center">
							<button type="button" class="form-control remove-row">
								<span class="icon-cross2 text-danger"></span>
							</button>
						</td>
					</tr>
				@endforeach
			@else
				<tr class="">
					<td class="drag-handler text-center DSP_work_report_detail_no">1</td>
					<td class="text-center" style="width: 170px;">
						@includeIf('popup.searchmanufacturinginstruction', array('is_nm' => false))
					</td>
					<td class="text-left">
						<div class="tooltip-overflow max-width20 DSP_item_nm_j" data-toggle="tooltip" data-placement="top" title=""></div>
					</td>
					<td class="text-left">
						<div class="input-group-select input-group-left" style="">
							<select class="form-control select-group-wrk work_hour_div " data-ini-target=true>
								<option></option>
									@if(isset($work_hour_div))
										@foreach($work_hour_div as $k=>$v)
											<option value="{{$v['lib_val_cd']}}" 
													data-ctl1="{{$v['lib_val_ctl1']}}" data-ctl2="{{$v['lib_val_ctl2']}}"
													data-ctl5="{{$v['lib_val_ctl5']}}" data-ctl6="{{$v['lib_val_ctl6']}}"
													data-nm-j="{{$v['lib_val_nm_j']}}" data-nm-e="{{$v['lib_val_nm_e']}}"
													data-ini_target_div="{{$v['ini_target_div']}}" @if($v['ini_target_div']=='1') selected @endif>
												{{$v['lib_val_nm_j']}} 
											</option>
										@endforeach
									@endif
							</select>
							<span>時間</span>
						</div>
						<div class="input-group-select input-group-right" style="">
						<select class="form-control select-group-wrk work_time_div " data-ini-target=true>
							<option></option>
									@if(isset($work_time_div))
										@foreach($work_time_div as $k=>$v)
											<option value="{{$v['lib_val_cd']}}" 
													data-ctl1="{{$v['lib_val_ctl1']}}" data-ctl2="{{$v['lib_val_ctl2']}}"
													data-ctl5="{{$v['lib_val_ctl5']}}" data-ctl6="{{$v['lib_val_ctl6']}}"
													data-nm-j="{{$v['lib_val_nm_j']}}" data-nm-e="{{$v['lib_val_nm_e']}}"
													data-ini_target_div="{{$v['ini_target_div']}}" @if($v['ini_target_div']=='1') selected @endif>
												{{$v['lib_val_nm_j']}} 
											</option>
										@endforeach
									@endif
						</select>
						<span>分</span>
						</div>
					</td>
					<td class="text-left">
						<input type="text" class="form-control TXT_memo" value="" maxlength="200" >
					</td>
					<td class="w-40px text-center">
						<button type="button" class="form-control remove-row">
							<span class="icon-cross2 text-danger"></span>
						</button>
					</td>
				</tr>
			@endif
		</tbody>
	</table>
</div>