<div id="result" class="panel panel-flat">
	<div class="panel-body">
		<div class="no-padding">
			<div class="nav-pagination" style="width: 100%; display: inline-block;">
				<div class="col-md-3 col-sm-3">
				{!! $fillter !!}
				</div>
				<div class="col-md-4 col-sm-7 group-date-table">
					<label class="col-md-4 col-sm-4 control-label text-right text-bold required">入庫日</label>
					<div class="col-md-6 col-sm-6">
						<input type="tel" id="input_warehouse_date" class="datepicker form-control required disabled-ime" name="input_warehouse_date" value="{{date('Y/m/d')}}" maxlength="10">
					</div>
				</div>
				<div class="col-md-5" style="float: right;">
				{!! $paginate !!}
				</div>
			</div>

			<div class="table-responsive table-custom ">
				<table class="table table-hover table-bordered table-xxs table-result table-stocking" id="table-stocking">
					<thead>
						<tr class="col-table-header text-center">
							<th class="col-1 text-center check-box" width="1%" rowspan="2">
								<input type="checkbox" id="check-all" name="">
							</th>
							<th class="text-center max-width90">部品コード</th>
							<th class="text-center" colspan="2">部品名</th>
							<th class="text-center" colspan="2">規格</th>
							<th class="text-center max-width90">発注数</th>
							<th class="text-center max-width90">未入庫数</th>
							<th class="text-center max-width90">入庫数</th>
							<th class="text-center max-width90">単価</th>
							<th class="text-center max-width150">金額</th>
						</tr>
						<tr class="col-table-header text-center">
							<th class="text-center max-width90">仕入先コード</th>
							<th class="text-center">仕入先名</th>
							<th class="text-center max-width90">発注日</th>
							<th class="text-center max-width130">発注番号</th>
							<th class="text-center max-width90">製造指示番号</th>
							<th class="text-center" colspan="4">備考</th>
							<th class="text-center max-width150">受入状況</th>
						</tr>
					</thead>
					<tbody>
					@if( isset($dataLists) && !empty($dataLists) )
						@php
							$index = 0;
						@endphp
						@foreach($dataLists as $dataList)
							@php
								$index++;
							@endphp
							<!-- line 1 -->
							<tr class="tr-table index-{{$index}} row1">
								<input type="hidden" class="parts_order_detail_no" value="{{ $dataList['parts_order_detail_no'] or '' }}">
								<input type="hidden" class="tax_rate" value="{{ $dataList['tax_rate'] or 0 }}">
								<td class="col-1 text-center" rowspan="2">
									<input type="checkbox" name="" class="check-all">
								</td>
								<td class="text-left max-width90 parts_cd">{{ $dataList['parts_cd'] or '' }}</td>

								<td class="text-left" colspan="2">
									<div class="tooltip-overflow max-width150 parts_nm" data-toggle="tooltip" data-placement="top" title="{{ $dataList['parts_nm'] or '' }}">{{ $dataList['parts_nm'] or '' }}</div>
								</td>

								<td class="text-left" colspan="2">
									<div class="tooltip-overflow max-width150 DSP_specification" data-toggle="tooltip" data-placement="top" title="{{ $dataList['specification'] or '' }}">{{ $dataList['specification'] or '' }}</div>
								</td>

								<td class="text-right max-width90 parts_order_qty">{{ $dataList['parts_order_qty'] or '' }}</td>

								<td class="text-right max-width90 parts_not_yet_receipt_qty">{{ $dataList['parts_not_yet_receipt_qty'] or '' }}</td>

								<td class="text-right max-width90">
									<input type="text" class="form-control parts_receipt_qty text-right quantity required" value="{{ $dataList['parts_receipt_qty'] == '' ? '0' : $dataList['parts_receipt_qty'] }}" maxlength="8">
								</td>

								<td class="text-right max-width90 unit_price">{{ $dataList['unit_price'] or '' }}</td>

								<td class="text-right max-width150">
									<input type="text" class="form-control text-right parts_purchase_actual_amount price" value="{{ $dataList['parts_purchase_actual_amount'] or '0' }}" maxlength="15">
								</td>
							</tr>

							<!-- line 2 -->
							<tr class="tr-table index-{{$index}} row2">
								<td class="text-left max-width90 supplier_cd">{{ $dataList['supplier_cd'] or '' }}</td>

								<td class="text-left">
									<div class="tooltip-overflow max-width150 supplier_nm" data-toggle="tooltip" data-placement="top" title="{{ $dataList['supplier_nm'] or '' }}">{{ $dataList['supplier_nm'] or '' }}</div>
								</td>

								<td class="text-center max-width90 order_date">{{ $dataList['order_date'] or '' }}</td>

								<td class="text-left max-width130 parts_order_no">{{ $dataList['parts_order_no'] or '' }}</td>

								<td class="text-left max-width90 manufacture_no">{{ $dataList['manufacture_no'] or '' }}</td>

								<td class="text-left" colspan="4">
									<input type="text" class="form-control remarks" value="{{ $dataList['remarks'] or '' }}" maxlength="200">
								</td>

								<td class="text-left max-width150 acceptance_status_div" data-status-div="{{ $dataList['acceptance_status_div'] or '' }}">{{ $dataList['acceptance_status_nm'] or '' }}</td>
							</tr>
						@endforeach
					@else
						<tr>
							<td colspan="11" class="text-center dataTables_empty">&nbsp;</td>
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