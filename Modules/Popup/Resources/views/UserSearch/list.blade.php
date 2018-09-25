<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive">
				<table class="table table-hover table-bordered table-xxs table-text table-popup tablesorter" id="table-popup-user">
					<thead>
						<tr class="col-table-header">
							<th class="text-center user_cd">@lang('label.user_cd')</th>
							<th class="text-center">@lang('label.user_nm_j')</th>
							<th class="text-center">@lang('label.user_ab_j')</th>
							<th class="text-center">@lang('label.user_nm_e')</th>
							<th class="text-center">@lang('label.user_ab_e')</th>
							<th class="text-center">@lang('label.auth_role_div')</th>
							<th class="text-center">@lang('label.incumbent_div')</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($userList) && !empty($userList))
							@foreach($userList as $user)
								<tr class="tr-table">
									<td class="text-left user_cd">{{$user['user_cd']}}</td>
									<td class="text-overfollow user_nm_j max-width30">
										<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$user['user_nm_j']}}">{{$user['user_nm_j']}}</div>
									</td>
									<td class="text-overfollow user_ab_j">{{$user['user_ab_j']}}</td>
									<td class="text-overfollow user_nm_e max-width30">
										<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$user['user_nm_e']}}">{{$user['user_nm_e']}}</div>
									</td>
									<td class="text-overfollow user_ab_e">{{$user['user_ab_e']}}</td>
									<td class="text-overfollow auth_role_div">{{$user['auth_role_div']}}</td>
									<td class="text-overfollow incumbent_div">{{$user['incumbent_div']}}</td>
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
		</div>
		<div class="nav-pagination">
			{!! $paginate !!}
		</div>
	</div>
</div>