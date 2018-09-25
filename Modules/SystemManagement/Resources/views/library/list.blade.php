<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive table-custom ">
				<table class="table table-hover table-bordered table-xxs table-list table-library" id="table-library">
					<thead>
					<tr class="col-table-header text-center">
						<th class="text-center" width="15%">@lang('label.lib_cd')</th>
						<th class="text-center" width="15%">@lang('label.lib_nm')</th>
						<th class="text-center" width="5%">@lang('label.change_perm_div')</th>
						<th class="text-center" width="5%">@lang('label.lib_val_cd_digit')</th>
						<th class="text-center" width="5%">@lang('label.lib_val_cd_search')</th>
						<th class="text-center" width="15%">@lang('label.lib_nm_j')</th>
						<th class="text-center" width="15%">@lang('label.lib_nm_e')</th>
						<th class="text-center" width="5%">@lang('label.disp_order')</th>
					</tr>
					</thead>
					<tbody>
						@if(isset($libraryList) && !empty($libraryList))
							@foreach($libraryList as $library)
							<tr>
								<td class="text-left lib_cd">{{$library['lib_cd'] or ''}}</td>
								<td class="text-left lib_nm">{{$library['lib_nm'] or ''}}</td>
								<td class="text-left change_perm_flg">{{$library['change_perm_div'] or ''}}</td>
								<td class="text-right lib_val_cd_digit">{{$library['lib_val_cd_digit'] or ''}}</td>
								<td class="text-right lib_val_cd">{{$library['lib_val_cd'] or ''}}</td>
								<td class="text-left lib_val_nm_j">{{$library['lib_val_nm_j'] or ''}}</td>
								<td class="text-left lib_val_nm_e">{{$library['lib_val_nm_e'] or ''}}</td>
								<td class="text-right">{{$library['disp_order'] or ''}}</td>
							</tr>
							@endforeach
						@else
						<tr>
							<td colspan="8" class="text-center dataTables_empty">&nbsp;</td>
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