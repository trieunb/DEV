<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination">
				{!! $fillter !!}
				{!! $paginate !!}
			</div>
			<div class="table-responsive table-custom sticky-table sticky-headers sticky-ltr-cells">
				<table class="table table-hover table-bordered table-xxs table-list table-striped table-packing" id="table-packing">
					<thead>
						<tr class="col-table-header text-center sticky-row">
							<th class="col-1 text-center sticky-cell th-first" width="1%">
								<input type="checkbox" id="check-all" name="">
							</th>
							<th class="text-center sticky-cell" width="2%" style="padding: 0 15px;">Invoice No</th>
							<th class="text-center sticky-cell" width="2%">Invoice Date</th>
							<th class="text-center sticky-cell th-last" width="4%" style="padding-left: 30px;padding-right: 30px;">取引先名</th>
							<th class="text-center" width="2%" style="padding: 0 30px;">住所1</th>
							<th class="text-center" width="2%" style="padding: 0 30px;">住所2</th>
							<th class="text-center" width="3%" style="padding: 0 30px;">都市名</th>
							<th class="text-center" width="1%" style="padding: 0 30px;">〒</th>
							<th class="text-center" width="2%" style="padding: 0 30px;">国名</th>
							<th class="text-center" width="3%" style="padding: 0 60px;">Tel</th>
							<th class="text-center" width="3%" style="padding: 0 60px;">Fax</th>
							<th class="text-center" width="4%">Consignee</th>
							<th class="text-center" width="4%">C住所1</th>
							<th class="text-center" width="4%">C住所2</th>
							<th class="text-center" width="5%">C都市名</th>
							<th class="text-center" width="3%" >C〒</th>
							<th class="text-center" width="5%">C国名</th>
							<th class="text-center" width="4%" style="padding: 0 60px;">CTel</th>
							<th class="text-center" width="4%" style="padding: 0 60px;">CFax</th>
							<th class="text-center" width="3%">Date of shipment</th>
							<th class="text-center" width="3%">Shipped per</th>

							<th class="text-center" width="3%" style="padding: 0 30px;">船積地都市名</th>
							<th class="text-center" width="3%" style="padding: 0 30px;">船積地国名</th>
							<th class="text-center" width="3%" style="padding: 0 30px;">仕向地都市名</th>
							<th class="text-center" width="3%" style="padding: 0 30px;">仕向地国名</th>
							<th class="text-center" width="4%">Shipping Mark1</th>
							<th class="text-center" width="4%">Shipping Mark2</th>
							<th class="text-center" width="4%">Shipping Mark3</th>
							<th class="text-center" width="4%">Shipping Mark4</th>
							<th class="text-center" width="3%" style="padding: 0 30px;">署名者</th>
							<th class="text-center" width="3%">総カートン数</th>
							<th class="text-center" width="3%">カートンNo</th>
							<th class="text-center" width="3%" style="padding: 0 25px;">カートン行No</th>
							<th class="text-center" width="2%">製品コード</th>
							<th class="text-center" width="2%">製品名</th>
							<th class="text-center" width="3%">社外用備考</th>
							<th class="text-center" width="2%">数量</th>
							<th class="text-center" width="2%">数量単位</th>
							<th class="text-center" width="2%">Net重量</th>
							<th class="text-center" width="2%">Gross重量</th>
							<th class="text-center" width="2%">重量単位</th>
							<th class="text-center" width="2%">容積</th>
							<th class="text-center" width="3%">容積単位</th>
						</tr>
					</thead>
					<tbody class="results">		
					@if( isset($mPackingList) && !empty($mPackingList) )
						@foreach($mPackingList as $datalist)
						<tr>
							<td class="col-1 text-center sticky-cell th-first">
								<input type="checkbox" name="" class="check-all">
							</td>
							<td class="text-left sticky-cell inv_no">
								{{$datalist['inv_no']}}	
							</td>
							<td class="text-center sticky-cell">{{$datalist['inv_date']}}</td>
							<td class="text-left sticky-cell th-last">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['client_nm']}}">{{$datalist['client_nm']}}</div>
							</td>
							<td class="text-left"> 
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['client_adr1']}}">{{$datalist['client_adr1']}}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['client_adr2']}}">{{$datalist['client_adr2']}}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['client_city_div']}}">{{$datalist['client_city_div']}}</div>
							</td>
							<td class="text-right">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['client_zip']}}">{{$datalist['client_zip']}}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['client_country_div']}}">{{$datalist['client_country_div']}}</div>
							</td>
							<td class="text-left">{{$datalist['client_tel']}}</td>
							<td class="text-left">{{$datalist['client_fax']}}</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['consignee_nm']}}">{{$datalist['consignee_nm']}}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['consignee_adr1']}}">{{$datalist['consignee_adr1']}}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['consignee_adr2']}}">{{$datalist['consignee_adr2']}}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['consignee_city_div']}}">{{$datalist['consignee_city_div']}}</div>
							</td>
							<td class="text-left">{{$datalist['consignee_zip']}}</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['consignee_country_div']}}">{{$datalist['consignee_country_div']}}</div>
							</td>
							<td class="text-left">{{$datalist['consignee_tel']}}</td>
							<td class="text-left">{{$datalist['consignee_fax']}}</td>
							<td class="text-center">{{$datalist['shipment_date']}}</td>
							<td class="text-left">{{$datalist['shipment_nm']}}</td>

							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['port_city_div']}}">{{$datalist['port_city_div']}}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['port_country_div']}}">{{$datalist['port_country_div']}}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['dest_city_div']}}">{{$datalist['dest_city_div']}}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['dest_country_div']}}">{{$datalist['dest_country_div']}}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['mark1']}}">{{$datalist['mark1']}}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['mark2']}}">{{$datalist['mark2']}}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['mark3']}}">{{$datalist['mark3']}}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['mark4']}}">{{$datalist['mark4']}}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['user_nm_e']}}">{{$datalist['user_nm_e']}}</div>
							</td>
							<td class="text-right">
								{{$datalist['total_carton_number']}}
								<span class="hidden">{{$datalist['total_carton_number']}}</span>
							</td>
							<td class="text-right">{{$datalist['carton_number']}}</td>
							<td class="text-right">{{$datalist['inv_carton_no']}}</td>
							<td class="text-left">{{$datalist['product_cd']}}</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['description']}}">{{$datalist['description']}}</div>
							</td>
							<td class="text-left">
								<div class="tooltip-overflow max-width20" data-toggle="tooltip" data-placement="top" title="{{$datalist['outside_remark']}}">{{$datalist['outside_remark']}}</div>
							</td>
							<td class="text-right">
								{{$datalist['qty']}}
								<span class="hidden">{{$datalist['qty']}}</span>
							</td>
							<td class="text-left">{{$datalist['unit_q_div']}}</td>
							<td class="text-right">
								{{$datalist['net_weight']}}
								<span class="hidden">{{$datalist['net_weight']}}</span>
							</td>
							<td class="text-right">
								{{$datalist['gross_weight']}}
								<span class="hidden">{{$datalist['gross_weight']}}</span>
							</td>
							<td class="text-left">{{$datalist['unit_net_weight_div']}}</td>
							<td class="text-right">
								{{$datalist['measure']}}
								<span class="hidden">{{$datalist['measure']}}</span>
							</td>
							<td class="text-left">{{$datalist['unit_measure_div']}}</td>
						</tr>
						@endforeach
					@else
						<tr>
							<td colspan="43" class="text-center dataTables_empty">&nbsp;</td>
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