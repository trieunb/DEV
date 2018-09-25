<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div id="table-listing">
				<div class="table-responsive table-custom ">
					<table class="table table-hover table-bordered table-xxs table-list table-authority" id="table-authority">
						<thead>
							<tr class="col-table-header text-center">
								<th class="text-center">権限ロール名</th>
								<th class="text-center">画面コード</th>
								<th class="text-center">画面名</th>
								<th class="text-center">機能名</th>
								<th class="col-1 text-left check-box">
									<label class="radio-inline">
										<input class="radio-checkbox" name="CHK_check_all" id="check-all-available" type="radio">
										利用可
									</label>
								</th>
								<th class="col-1 text-left check-box">
									<!-- <input type="checkbox" id="check-all" name=""> -->
									<label class="radio-inline">
										<input class="radio-checkbox" name="CHK_check_all" id="check-all-not-available" type="radio">
										利用不可
									</label>
								</th>
								<th class="col-1 text-left check-box">
									<label class="radio-inline">
										<input class="radio-checkbox" name="CHK_check_all"  id="check-all-not-set" type="radio">
										未設定
									</label>
								</th>
							</tr>
						</thead>
						<tbody>
							@if(isset($authorityList) && !empty($authorityList))
								@foreach($authorityList as $key=>$row)
									<tr data-auth_role_div="{{$row['auth_role_div']}}" data-prg_cd="{{$row['prg_cd']}}" data-fnc_cd="{{$row['fnc_cd']}}" data-row_index="{{$key+1}}" class="tr-row">
										<td class="text-left">{{$row['auth_role_div_nm']}}</td>
										<td class="text-left">{{$row['prg_cd']}}</td>
										<td class="text-left max-width30">
											<div class="tooltip-overflow " data-toggle="tooltip" data-placement="top" title="{{$row['prg_nm']}}">{{$row['prg_nm']}}</div>
										</td>
										<td class="text-left">{{$row['fnc_nm']}}</td>
										<td class="col-1 text-left">
											<label class="radio-inline">
												<input name="CHK_fnc_use_div_{{$key+1}}" class="check-all-available" type="radio" data-fnc_use_div="1" @if ($row['fnc_use_div'] == '1') checked="" @endif >
												利用可
											</label>
										</td>
										<td class="col-1 text-left">
											<label class="radio-inline">
												<input name="CHK_fnc_use_div_{{$key+1}}" class="check-all-not-available" type="radio" data-fnc_use_div="0" @if ($row['fnc_use_div'] == '0') checked="" @endif >
												利用不可
											</label>
										</td>
										<td class="col-1 text-left">
											<label class="radio-inline">
												<input name="CHK_fnc_use_div_{{$key+1}}" class="check-all-not-set" type="radio" data-fnc_use_div="2" @if ($row['fnc_use_div'] == '2') checked="" @endif >
												未設定
											</label>
										</td>
									</tr>
								@endforeach
							@else
								<tr class="tr-empty">
									<td colspan="7" class="text-center dataTables_empty">&nbsp;</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>