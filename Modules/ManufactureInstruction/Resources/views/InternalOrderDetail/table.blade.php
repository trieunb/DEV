<div class="table-responsive sticky-table sticky-headers sticky-ltr-cells">
						<table class="table table-hover table-bordered table-xxs table-list table-internal-order" id="table-internal-order">
							<thead>
								<tr class="col-table-header">
									<th class="text-center" width="3%"></th>
									<th class="text-center" width="5%">製品コード</th>
									<th class="text-center" width="8%">処理</th>
									<th class="text-center" width="12%" style="padding: 0px 200px;">製品名</th>
									<th class="text-center" width="8%">数量</th>
									<th class="text-center" width="5%">希望納期</th>
									<th class="text-center">備考</th>
									<th class="text-center" width="8%">指示数量</th>
									<th class="w-40px text-center">
										<button type="button" class="btn btn-primary btn-icon btn-add-row" id="btn-add-row" tabindex="58">
											<i class="icon-plus3"></i>
										</button>
									</th>
								</tr>
							</thead>
							<tbody>
							@if(isset($data_table))
								@foreach($data_table as $data_list)
								<tr class="">
									<td class="text-center DSP_in_order_detail_no" style="display: none;">{{ $data_list['in_order_detail_no'] }}</td>
									<td class=" @if(isset($isManufactured) && $isManufactured == false) drag-handler @endif text-center DSP_disp_order">{{ $data_list['disp_order'] }}</td>
									<!-- Item - required -->
									<td class="text-center" style="width: 120px;">

										@includeIf('popup.searchproduct', array(
											'class_cd'    => 'TXT_product_cd',
											'val'		  => $data_list['product_cd'],
											'is_required' => true,
											'is_nm'       => false
										))

									</td>
									<td class="text-center">
										<select class="form-control manufacture_kind_div CMB_manufacture_kind_div" data-selected="{{ $data_list['manufacture_kind_div'] or ''}}" data-ini-target=true>
										<option></option>
										@if(isset($manufacture_kind_div))
											@foreach($manufacture_kind_div as $k=>$v)
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
									</td>
									<td class="text-left">
										<div class="tooltip-overflow max-width20 product_nm DSP_product_nm" style="max-width: 20px;" data-toggle="tooltip" data-placement="top" title="{{ $data_list['product_nm_j'] }}">{{ $data_list['product_nm_j'] }}</div>
									</td>
									<!-- Item - required -->
									<td class="text-center">
										<input type="text" class="form-control money TXT_in_order_qty required numeric" real_len="8" value="{{ $data_list['in_order_qty'] }}">
									</td>
									<td class="text-center date">
										<input type="tel" class="form-control datepicker TXT_hope_delivery_date hasDatepicker" value="{{ $data_list['hope_delivery_date'] }}" placeholder="yyyy/mm/dd">
									</td>
									<td class="text-center">
										<input type="text" class="form-control TXT_detail_remarks" value="{{ $data_list['detail_remarks'] }}" maxlength="200">
									</td>
									<td class="text-right TXT_sum_manufacture_qty">
										{{ $data_list['manufacture_qty'] or '0'}}
									</td>
									<td class="TXT_manufacture_status_div" style="display: none">{{ $data_list['manufacture_status_div'] or ''}}</td>
									<td class="w-40px text-center">
										<button type="button" class="form-control remove-row" tabindex="69">
											<span class="icon-cross2 text-danger"></span>
										</button>
									</td>
								</tr>
								@endforeach
							@endif
							</tbody>
						</table>
					</div>