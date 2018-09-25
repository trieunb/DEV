<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive table-custom sticky-table sticky-headers sticky-ltr-cells">
				<table class="table table-hover table-bordered table-xxs table-list table-suppliers-master" id="table-suppliers-master">
					<thead>
					<tr class="col-table-header text-center sticky-row">
						<!-- client_cd -->
						<th class="text-center sticky-cell th-first">@lang('label.client_cd')</th>
						<!-- client_nm -->
						<th class="text-center sticky-cell th-last">@lang('label.client_nm')</th>
						<!-- cust_div -->
						<th class="text-center" style="padding: 0 10px;">@lang('label.cust_div')</th>
						<!-- supplier_div -->
						<th class="text-center" style="padding: 0 10px;">@lang('label.supplier_div')</th>
						<!-- outsourcer_div -->
						<th class="text-center" style="padding: 0 10px;">@lang('label.outsourcer_div')</th>
						<!-- client_staff_nm -->
						<th class="text-center">@lang('label.client_staff_nm')</th>
						<!-- client_zip -->
						<th class="text-center" style="padding: 0 10px;">@lang('label.client_zip')</th>
						<!-- client_adr1 -->
						<th class="text-center">@lang('label.client_adr1')</th>
						<!-- client_adr2 -->
						<th class="text-center">@lang('label.client_adr2')</th>
						<!-- client_city_div -->
						<th class="text-center">@lang('label.client_city_div')</th>
						<!-- client_country_div -->
						<th class="text-center">@lang('label.client_country_div')</th>
						<!-- port_city_div -->
						<th class="text-center">@lang('label.port_city_div')</th>
						<!-- port_country_div -->
						<th class="text-center">@lang('label.port_country_div')</th>
						<!-- client_tel -->
						<th class="text-center" style="padding: 0 30px;">@lang('label.client_tel')</th>
						<!-- client_fax -->
						<th class="text-center" style="padding: 0 30px;">@lang('label.client_fax')</th>
						<!-- client_mail1 -->
						<th class="text-center">@lang('label.client_mail1')</th>
						<!-- client_url -->
						<th class="text-center">@lang('label.client_url')</th>
						<!-- parent_client_cd -->
						<th class="text-center">@lang('label.parent_client_cd')</th>
						<!-- client_nm2 -->
						<th class="text-center">@lang('label.client_nm2')</th>
						<!-- remarks -->
						<th class="text-center">@lang('label.suppliers_search_remarks')</th>
					</tr>
					</thead>
					<tbody>
						@if(isset($clientList) && !empty($clientList))
							@foreach($clientList as $client)
								<tr class ="tr-table">
									<!-- client_cd -->
									<td class="text-left sticky-cell th-first client_cd">{{$client['client_cd']}}</td>

									<!-- client_nm -->
									<td class="text-left sticky-cell th-last">
										<div class="tooltip-overflow max-width20 client_nm" data-toggle="tooltip" data-placement="top" title="{{$client['client_nm']}}">{{$client['client_nm']}}</div>
									</td>

									<!-- cust_div -->
									<td class="text-center">
										<input type="checkbox" {{$client['cust_div'] == '1' ? 'checked="checked"' : ''}} class="check-all cust_div" disabled="disabled">
									</td>

									<!-- supplier_div -->
									<td class="text-center">
										<input type="checkbox" {{$client['supplier_div'] == '1' ? 'checked="checked"' : ''}} class="check-all supplier_div" disabled="disabled">
									</td>

									<!-- outsourcer_div -->
									<td class="text-center">
										<input type="checkbox" {{$client['outsourcer_div'] == '1' ? 'checked="checked"' : ''}} class="check-all outsourcer_div" disabled="disabled">
									</td>

									<!-- client_staff_nm -->
									<td class="text-left">
										<div class="tooltip-overflow max-width20 client_staff_nm" data-toggle="tooltip" data-placement="top" title="{{$client['client_staff_nm']}}">{{$client['client_staff_nm']}}</div>
									</td>

									<!-- client_zip -->
									<td class="text-left client_zip">{{$client['client_zip']}}</td>

									<!-- client_adr1 -->
									<td class="text-right">
										<div class="tooltip-overflow max-width20 client_adr1" data-toggle="tooltip" data-placement="top" title="{{$client['client_adr1']}}">{{$client['client_adr1']}}</div>
									</td>

									<!-- client_adr2 -->
									<td class="text-right">
										<div class="tooltip-overflow max-width20 client_adr2" data-toggle="tooltip" data-placement="top" title="{{$client['client_adr2']}}">{{$client['client_adr2']}}</div>
									</td>

									<!-- client_city_div -->
									<td class="text-left">
										<div class="tooltip-overflow max-width20 client_city_div" data-toggle="tooltip" data-placement="top" title="{{$client['client_city_div']}}">{{$client['client_city_div']}}</div>
									</td>

									<!-- client_country_div -->
									<td class="text-left">
										<div class="tooltip-overflow max-width20 client_country_div" data-toggle="tooltip" data-placement="top" title="{{$client['client_country_div']}}">{{$client['client_country_div']}}</div>
									</td>

									<!-- port_city_div -->
									<td class="text-left">
										<div class="tooltip-overflow max-width20 port_city_div" data-toggle="tooltip" data-placement="top" title="{{$client['port_city_div']}}">{{$client['port_city_div']}}</div>
									</td>
									
									<!-- port_country_div -->
									<td class="text-left">
										<div class="tooltip-overflow max-width20 port_country_div" data-toggle="tooltip" data-placement="top" title="{{$client['port_country_div']}}">{{$client['port_country_div']}}</div>
									</td>

									<!-- client_tel -->
									<td class="text-left min-width175 client_tel">{{$client['client_tel']}}</td>

									<!-- client_fax -->
									<td class="text-left min-width175 client_fax">{{$client['client_fax']}}</td>

									<!-- client_mail1 -->
									<td class="text-left client_mail1">{{$client['client_mail1']}}</td>

									<!-- client_url -->
									<td class="text-left">
										<div class="tooltip-overflow max-width20 client_url" data-toggle="tooltip" data-placement="top" title="{{$client['client_url']}}">{{$client['client_url']}}</div>
									</td>

									<!-- parent_client_cd -->
									<td class="text-left parent_client_cd">{{$client['parent_client_cd']}}</td>

									<!-- client_nm2 -->
									<td class="text-left">
										<div class="tooltip-overflow max-width20 client_nm2" data-toggle="tooltip" data-placement="top" title="{{$client['client_nm2']}}">{{$client['client_nm2']}}<div>
									</td>
									
									<!-- remarks -->
									<td class="text-left">
										<div class="tooltip-overflow max-width20 remarks" data-toggle="tooltip" data-placement="top" title="{{$client['remarks']}}">{{$client['remarks']}}</div>
									</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="20" class="text-center dataTables_empty">&nbsp;</td>
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