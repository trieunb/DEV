<table class="table table-hover table-bordered table-xxs table-text table-library table-list" id="table-library">
	<thead>
		<tr class="col-table-header sticky-row">
			<th rowspan="2" class="text-center" width="10%">@lang('label.lib_val_cd')</th>
			<th class="text-center" width="20%">@lang('label.lib_val_nm_j')</th>
			<th class="text-center" width="20%">@lang('label.lib_val_ab_j')</th>
			<th class="text-center" width="9%">@lang('label.lib_val_ctl1')</th>
			<th class="text-center" width="9%">@lang('label.lib_val_ctl2')</th>
			<th class="text-center" width="9%">@lang('label.lib_val_ctl3')</th>
			<th class="text-center" width="9%">@lang('label.lib_val_ctl4')</th>
			<th class="text-center" width="9%">@lang('label.lib_val_ctl5')</th>
			<th rowspan="2" class="text-center" width="3%">@lang('label.ini_target_div')</th>
			<th rowspan="2" class="text-center" width="3%">
				<button type="button" class="btn btn-primary btn-icon btn-add-row" id="btn-add-row" style="">
					<i class="icon-plus3"></i>
				</button>
			</th>
		</tr>
		<tr class="col-table-header sticky-row">
			<th class="text-center">@lang('label.lib_val_nm_e')</th>
			<th class="text-center">@lang('label.lib_val_ab_e')</th>
			<th class="text-center" width="9%">@lang('label.lib_val_ctl6')</th>
			<th class="text-center" width="9%">@lang('label.lib_val_ctl7')</th>
			<th class="text-center" width="9%">@lang('label.lib_val_ctl8')</th>
			<th class="text-center" width="9%">@lang('label.lib_val_ctl9')</th>
			<th class="text-center" width="9%" style="border-right: 2px solid #ddd;">@lang('label.lib_val_ctl10')</th>
		</tr>
	</thead>
	<tbody>
		@if(isset($libraryInput) && !empty($libraryInput))
			@foreach($libraryInput as $input)
			<tr class="">
				<td class="drag-handler text-left">
					<input type="text" class="form-control tab-top TXT_lib_val_cd" value="{{ $input['lib_val_cd'] }}" maxlength="10">
				</td>
				<td class="text-left">
					<input type="text" class="form-control tab-top TXT_lib_val_nm_j" value="{{ $input['lib_val_nm_j'] }}" maxlength="100">
					<div class="boder-line"></div>
					<input type="text" class="form-control tab-bottom TXT_lib_val_nm_e" value="{{ $input['lib_val_nm_e'] }}" maxlength="50">
				</td>
				<td class="text-left">
					<input type="text" class="form-control tab-top TXT_lib_val_ab_j" value="{{ $input['lib_val_ab_j'] }}" maxlength="100">
					<div class="boder-line"></div>
					<input type="text" class="form-control tab-bottom TXT_lib_val_ab_e" value="{{ $input['lib_val_ab_e'] }}" maxlength="50">
				</td>
				<td class="text-left">
					<input type="text" class="form-control tab-top1 TXT_lib_val_ctl1" value="{{ $input['lib_val_ctl1'] }}" maxlength="50">
					<div class="boder-line"></div>
					<input type="text" class="form-control tab-bottom1 TXT_lib_val_ctl6" value="{{ $input['lib_val_ctl6'] }}" maxlength="50">
				</td>
				<td class="text-left">
					<input type="text" class="form-control tab-top1 TXT_lib_val_ctl2" value="{{ $input['lib_val_ctl2'] }}" maxlength="50">
					<div class="boder-line"></div>
					<input type="text" class="form-control tab-bottom1 TXT_lib_val_ctl7" value="{{ $input['lib_val_ctl7'] }}" maxlength="50">
				</td>
				<td class="text-left">
					<input type="text" class="form-control tab-top1 TXT_lib_val_ctl3" value="{{ $input['lib_val_ctl3'] }}" maxlength="50">
					<div class="boder-line"></div>
					<input type="text" class="form-control tab-bottom1 TXT_lib_val_ctl8" value="{{ $input['lib_val_ctl8'] }}" maxlength="50">
				</td>
				<td class="text-left">
					<input type="text" class="form-control tab-top1 TXT_lib_val_ctl4" value="{{ $input['lib_val_ctl4'] }}" maxlength="50">
					<div class="boder-line"></div>
					<input type="text" class="form-control tab-bottom1 TXT_lib_val_ctl9" value="{{ $input['lib_val_ctl9'] }}" maxlength="50">
				</td>
				<td class="text-left">
					<input type="text" class="form-control tab-top1 TXT_lib_val_ctl5" value="{{ $input['lib_val_ctl5'] }}" maxlength="50">
					<div class="boder-line"></div>
					<input type="text" class="form-control tab-bottom1 TXT_lib_val_ctl10" value="{{ $input['lib_val_ctl10'] }}" maxlength="50">
				</td>
				<td class="text-center">
					<input class="styled  tab-bottom1 RDI_ini_target_div" @if ($input['ini_target_div']) checked="checked" @endif name="ini_target_div" type="radio">
				</td>
				<td class="text-left">
					<button type="button" class="form-control remove-row tab-bottom1">
						<span class="icon-cross2 text-danger"></span>
					</button>
				</td>
			</tr>
		@endforeach
		@endif
	</tbody>
</table>