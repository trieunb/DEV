					<table class="table table-hover table-bordered table-xxs table-text table-list table-accept" id="table-accept">
						<thead>
							<tr class="col-table-header sticky-row">
								<th rowspan="2" class="text-center" width="3%">NO</th>
								<th rowspan="2" class="text-center" width="8%">区分</th>
								<th rowspan="2" class="text-center" width="5%">Code</th>
								<th class="text-center">Description</th>
								<th class="text-center">Q'ty</th>
								<th class="text-center">Unit of M</th>
								<th class="text-center">Unit Price</th>
								<th class="text-center">Amount</th>
								<th colspan="2" class="text-center">Unit Measurement</th>
								<th rowspan="2" class="text-center" width="2%">
									<button type="button" class="btn btn-primary btn-icon btn-add-row" id="btn-add-row" style="float: right;">
										<i class="icon-plus3"></i>
									</button>
								</th>
							</tr>
							<tr class="col-table-header sticky-row">
								<th class="text-center" width="27%">社外用備考</th>
								<th class="text-center" width="8%">Unit N/W</th>
								<th class="text-center" width="8%">Unit of W</th>
								<th class="text-center" width="8%">N/W</th>
								<th class="text-center" width="12%">Unit G/W</th>
								<th class="text-center" width="8%">G/W</th>
								<th class="text-center" width="8%">Measure</th>
							</tr>
						</thead>
						<tbody>
							@if(isset($rcv_d))
								@foreach($rcv_d as $key => $val)
								<tr class="">
									<!-- NO -->
									<td class="drag-handler text-center DSP_rcv_detail_no">{{ $val['rcv_detail_no'] or ''}}</td>

									<!-- 区分 -->
									<td class="text-center">
										<select class="form-control tab-top sales_detail_div CMB_sales_detail_div required_detail" data-selected="{{ $val['sales_detail_div'] or ''}}" data-ini-target=true>
											<option></option>
												@if(isset($sales_detail_div))
													@foreach($sales_detail_div as $k=>$v)
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

									<!-- Code -->
									<td class="text-center">
										@includeIf('popup.searchproduct', [
																'class_cd'    => 'TXT_product_cd',
																'class_tab'   => 'tab-top', 
																'is_nm'       => false,
								          						'disable_ime' => 'disable-ime',
																'val'		  => $val['product_cd']])
									</td>

									<!-- Description/社外用備考 -->
									<td class="text-center">
										<input type="text" class="form-control tab-top TXT_description required_detail" value="{{ $val['description'] or ''}}" maxlength="120">
										<div class="boder-line"></div>
										<input type="text" class="form-control tab-bottom TXT_outside_remarks" value="{{ $val['outside_remarks'] or ''}}" maxlength="200">
									</td>

									<!-- Q'ty/Unit N/W -->
									<td class="text-center">
										<input type="text" class="form-control quantity tab-top TXT_qty disable-ime" value="{{ $val['qty'] or ''}}" maxlength="7">
										<div class="boder-line"></div>
										<input type="text" class="form-control weight tab-bottom TXT_unit_net_weight disable-ime" value="{{ $val['unit_net_weight'] or ''}}" maxlength="7">
									</td>

									<!-- Unit of M/Unit of W -->
									<td class="text-center">
										<select class="form-control tab-top unit_q_div CMB_unit_of_m_div" data-selected="{{ $val['unit_qty_div'] or ''}}" data-ini-target=true>
											<option></option>
												@if(isset($unit_q_div))
													@foreach($unit_q_div as $k=>$v)
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
										<div class="boder-line"></div>
										<select class="form-control tab-bottom unit_w_div CMB_unit_net_weight_div @if($key == 0) unit_net_weight_div @endif" data-selected="{{ $val['unit_net_weight_div'] or ''}}" data-ini-target=true>
											<option></option>
												@if(isset($unit_w_div))
													@foreach($unit_w_div as $k=>$v)
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

									<!-- Unit Price/N/W -->
									<td class="text-center">
										<input type="text" class="form-control price tab-top TXT_unit_price disable-ime" value="{{ $val['unit_price'] or ''}}" maxlength="12">
										<span class="hidden DSP_unit_price_JPY">{{ $val['unit_price_JPY'] or ''}}</span>
										<span class="hidden DSP_unit_price_USD">{{ $val['unit_price_USD'] or ''}}</span>
										<span class="hidden DSP_unit_price_EUR">{{ $val['unit_price_EUR'] or ''}}</span>
										<div class="boder-line"></div>
										<input type="text" class="form-control weight tab-bottom TXT_net_weight" disabled="disabled" value="{{ $val['net_weight'] or ''}}" maxlength="20">
									</td>

									<!-- Amount/Unit G/W -->
									<td class="text-center">
										<input type="text" class="form-control money tab-top TXT_amount disable-ime" disabled="disabled" value="{{ $val['detail_amt'] or ''}}" maxlength="20">
										<div class="boder-line"></div>
										<input type="text" class="form-control weight tab-bottom TXT_unit_gross_weight disable-ime" value="{{ $val['unit_gross_weight'] or ''}}" maxlength="9">
									</td>

									<!-- Unit Measurement(G/W) -->
									<td class="text-center">
										<input type="text" class="form-control measure tab-top TXT_unit_measure_qty disable-ime" value="{{ $val['unit_measure'] or ''}}" maxlength="7">
										<div class="boder-line"></div>
										<input type="text" class="form-control weight tab-bottom TXT_gross_weight disable-ime" disabled="disabled" value="{{ $val['gross_weight'] or ''}}" maxlength="20">
									</td>

									<!-- Unit Measurement(Measure) -->
									<td class="text-center">
										<select class="form-control tab-top unit_m_div CMB_unit_measure_price @if($key == 0) unit_measure_price @endif" data-selected="{{ $val['unit_measure_div'] or ''}}" data-ini-target=true>
											<option></option>
												@if(isset($unit_m_div))
													@foreach($unit_m_div as $k=>$v)
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
										<div class="boder-line"></div>
										<input type="text" class="form-control measure tab-bottom TXT_measure disable-ime" disabled="disabled" value="{{ $val['measure'] or ''}}" maxlength="20">
									</td>

									<td class="text-center">
										<button type="button" class="form-control tab-bottom remove-row" id="remove-row">
											<span class="icon-cross2 text-danger"></span>
										</button>
									</td>
								</tr>
								@endforeach
							@endif
						</tbody>
					</table>