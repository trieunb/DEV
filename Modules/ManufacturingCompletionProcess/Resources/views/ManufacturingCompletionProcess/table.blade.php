<table class="table table-hover table-bordered table-xxs table-text table-manufacturing-completion-process table-list" id="table-manufacturing-completion-process">
	<thead>
		<tr class="col-table-header sticky-row">
			<th class="text-center w-40px">NO</th>
			<th class="text-center w-120px">完了数量</th>
			<th class="text-center w-120px">完了日</th>
			<th class="text-center">備考</th>
			<th class="w-40px text-center">
				<button type="button" class="btn btn-primary btn-icon btn-add-row" id="btn-add-row" tabindex="58">
					<i class="icon-plus3"></i>
				</button>
			</th>
		</tr>
	</thead>
	<tbody>
		@if (!empty($listData))
			@foreach ($listData as $row)
				<tr class="">
					<td class="drag-handler text-center DSP_no">
					</td>
					<td class="text-right">
						<input type="text" class="form-control text-right required quantity TXT_complete_qty" real_len="6" value="{{$row['complete_qty']}}" maxlength="8">
					</td>
					<td class="text-center date">
						<input type="tel" class="datepicker form-control required TXT_complete_date" value="{{$row['complete_date']}}" maxlength="10">
					</td>
					<td class="text-center">
						<input type="text" class="form-control TXT_remarks" value="{{$row['remarks']}}" maxlength="200">
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
				<td class="drag-handler text-center DSP_no">
					1
				</td>
				<td class="text-right">
					<input type="text" class="form-control text-right required quantity TXT_complete_qty" real_len="6" value="" maxlength="8">
				</td>
				<td class="text-center date">
					<input type="tel" class="datepicker form-control required TXT_complete_date" value="" maxlength="10">
				</td>
				<td class="text-center">
					<input type="text" class="form-control TXT_remarks" value="" maxlength="200">
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