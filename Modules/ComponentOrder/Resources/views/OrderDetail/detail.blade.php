@extends('layouts.main')

@section('title')
	部品発注書入力
@endsection

@section('button')
	{{Button::button_left(array('btn-back', 'btn-save','btn-delete', 'btn-approve', 'btn-cancel-approve', 'btn-issue'),$mode)}}
@endsection

@section('stylesheet')
	{!! public_url('modules/componentorder/css/order_detail.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/componentorder/js/order_detail.js')!!}
@endsection


@section('content')
	<script>
		var mode = "{{$mode}}"
		var from = "{{$from}}"
		var isCheckedSuppliersPopup = "true";
	</script>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">部品発注書入力</h5>
				<div id="operator_info">
					{!! infoMemberCreUp('', '', '', '') !!}
				</div>
			</div>

			<div class="panel-body">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold {{ $mode != 'I' ? 'required' : '' }}" id="lable_parts_order_no">注文番号</label>
					<div class="col-md-2">
						@includeIf('popup.searchorder', array(
											'id'          => 'TXT_parts_order_no',
											'class_tab'   => 'TXT_parts_order_no',
											'is_required' => true,
											'is_nm'       => false,
								          	'disable_ime' => 'disable-ime',
											'val'         => isset($parts_order_no) ? $parts_order_no : ''))
					</div>

					<label class="col-md-1 control-label text-right text-bold required mw-115px">発注日</label>
					<div class="col-md-1 date">
						<input type="tel" class="form-control datepicker required" id="TXT_parts_order_date" value="{{ date('Y/m/d') }}">
					</div>

					<label class="col-md-1 control-label text-right text-bold mw-135px">部品発注種別区分</label>
					<label class="col-md-1 control-label text-left DSP_parts_order_type_div">{{ $parts_order_type_div_nm or '' }}</label>

					<label class="col-md-3 col-md-3-cus control-label text-right" style="float: right;">
						<span class="text-bold hide" id="STT">ステータス</span>
						<span class="DSP_status" id="DSP_status"></span>
						<span class="DSP_status_tm" id="DSP_status_tm"></span>
					</label>
				</div>

				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">購入依頼番号</label>
					<label class="col-md-2 col-md-2-cus control-label text-left DSP_buy_no">{{ $DSP_buy_no or '' }}</label>

					<label class="col-md-1 control-label text-right text-bold mw-115px">社内発注書番号</label>
					<label class="col-md-1 control-label text-left date DSP_in_order_no">{{ $DSP_in_order_no or '' }}</label>
					
					<label class="col-md-1 control-label text-right text-bold mw-135px">製造指示書番号</label>
					<label class="col-md-1 control-label text-left DSP_manufacturing_instruction_number">{{ $DSP_manufacturing_instruction_number or '' }}</label>
				</div>

				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold required">仕入先コード</label>
					<div class="col-md-2">
						@includeIf('popup.searchsuppliers',
								    array('id'			=> "TXT_supplier_cd",
								    	  'val' 		=> '',
								          'is_required' => true,
								          'disable_ime' => 'disable-ime',
								          'is_nm'		=> true))
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">仕入先担当者名</label>
					<div class="col-md-2">
						<input type="text" id="TXT_supplier_staff_nm" class="form-control" value="" maxlength="120">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">件名</label>
					<div class="col-md-2">
						<input type="text" id="TXT_subject_nm" class="form-control" value="" maxlength="50">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">注文書有効期限</label>
					<div class="col-md-2 date">
						<input type="tel" id="TXT_expiration_date" class="form-control datepicker disable-ime" value="" placeholder="yyyy/mm/dd">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">希望納期</label>
					<div class="col-md-2">
						<input type="tel" id="TXT_hope_delivery_date" class="form-control datepicker disable-ime" value="" placeholder="yyyy/mm/dd">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">備考</label>
					<div class="col-md-8">
						<textarea id="TXT_remarks" class="form-control disable-resize" rows="2" maxlength="200"></textarea>
					</div>
				</div>

				<div class="table-responsive sticky-table sticky-headers sticky-ltr-cells" id="table-refer" style="max-height: 300px;">
					@includeIf('componentorder::OrderDetail.table')
				</div>

				<div class="table-responsive" style="max-height: 300px;">
					<table class="table table-total" style="border-top: none;">
						<input type="hidden" id="purchase_detail_amt_round_div" 	value="{{ $purchase_detail_amt_round_div or 1 }}">
						<input type="hidden" id="purchase_detail_tax_round_div" 	value="{{ $purchase_detail_tax_round_div or 1 }}">
						<input type="hidden" id="purchase_summary_tax_round_div" 	value="{{ $purchase_summary_tax_round_div or 1 }}">
						<input type="hidden" id="report_number_parts_order" 		value="{{ $report_number_parts_order or 1 }}">
						<input type="hidden" id="tax_rate" 							value="{{ $tax_rate or 0 }}">
						<input type="hidden" id="total_detail_tax" 					value="0">
						<tbody>
							<tr>
								<td colspan="7" class="text-right text-bold">小計</td>
								<td class="text-right" id="DSP_total_detail_amt" style="width: 100px"></td>
								<td class="" style="width: 150px"></td>
								<td class="" style="width: 40px"></td>
							</tr>
							<tr>
								<td colspan="7" class="text-right text-bold">消費税</td>
								<td class="text-right" id="DSP_tax_amt" style="width: 100px"></td>
								<td class="" style="width: 150px"></td>
								<td class="" style="width: 40px"></td>
							</tr>
							<tr>
								<td colspan="7" class="text-right text-bold">合計</td>
								<td class="text-right" id="DSP_total_amt" style="width: 100px"></td>
								<td class="" style="width: 150px"></td>
								<td class="" style="width: 40px"></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		<!-- /search field -->
	</div>
@endsection

@section('content_hidden')
	<!-- table row -->
	<table class="hide">
		<tbody id="table-row">
			<tr class="tr-table">
				<input type="hidden" class="tax_detail" value="0">
				<!-- DSP_no -->
				<td class="text-center DSP_no drag-handler">1</td>

				<!-- TXT_parts_cd -->
				<td class="text-center" style="width: 120px;">
					@includeIf('popup.searchcomponent', array(
													'class_cd' 		=> 'TXT_parts_cd',
										    	  	'val' 			=> '',
										          	'is_required' 	=> true,
										          	'disable_ime' 	=> 'disable-ime',
										          	'is_nm'			=> false
												))
				
				<!-- DSP_item_nm -->
				<td class="text-left" style="min-width: 200px;">
					<div class="tooltip-overflow max-width20 DSP_item_nm" data-toggle="tooltip" data-placement="top" title=""></div>
				</td>
				
				<!-- DSP_specification -->
				<td class="text-left" style="min-width: 200px">
					<div class="tooltip-overflow max-width20 DSP_specification" data-toggle="tooltip" data-placement="top" title=""></div>
				</td>

				<!-- TXT_parts_order_qty -->
				<td class="text-center">
					<input type="text" class="form-control quantity TXT_parts_order_qty required" maxlength="8">
				</td>

				<!-- DSP_unit -->
				<td class="text-center text-right DSP_unit">
					
				</td>

				<!-- TXT_parts_order_unit_price -->
				<td class="text-center">
					<input type="text" class="form-control price TXT_parts_order_unit_price">
				</td>

				<!-- TXT_parts_order_unit_amt -->
				<td class="text-center">
					<input type="text" class="form-control numeric price TXT_parts_order_unit_amt">
				</td>

				<!-- TXT_detail_remarks -->
				<td class="text-center" data-toggle="tooltip" data-placement="top" title="" style="min-width: 200px">
					<input type="text" class="form-control TXT_detail_remarks" maxlength="200">
				</td>

				<!-- BTN_Delete line -->
				<td class="w-40px text-center">
					<button type="button" class="form-control remove-row">
						<span class="icon-cross2 text-danger"></span>
					</button>
				</td>
			</tr>
		</tbody>
	</table>
	<!--/table row -->
@endsection