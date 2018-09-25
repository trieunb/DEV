@extends('layouts.main')

@section('title')
	仕入修正
@endsection

@section('button')
	{{Button::button_left(array('btn-back', 'btn-save','btn-delete'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/stocking/css/stocking_update.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/stocking/js/stocking_update.js')!!}
@endsection

@section('content')
	<script>
		var isCheckedSuppliersPopup = "true";
	</script>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">仕入修正</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">仕入番号</label>
					<label class="col-md-2 col-md-2-cus control-label text-left DSP_purchase_no">{{ $stockInfo['purchase_no'] or '' }}</label>

					<label class="col-md-2 control-label text-right text-bold">部品発注書番号</label>
					<label class="col-md-2 control-label text-left date DSP_parts_order_no">{{ $stockInfo['parts_order_no'] or '' }}</label>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">仕入明細番号</label>
					<label class="col-md-2 col-md-2-cus control-label text-left DSP_purchase_detail_no">{{ $stockInfo['purchase_detail_no'] or '' }}</label>
				</div>

				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold required">仕入日</label>
					<div class="col-md-2">
						<input type="tel" id="TXT_purchase_date" class="form-control datepicker disable-ime required" value="{{ $stockInfo['purchase_date'] or '' }}" placeholder="yyyy/mm/dd">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold required">仕入先コード</label>
					<div class="col-md-1">
						@include('popup.searchsuppliers', array(
												'val'			=> isset($stockInfo['supplier_cd']) ? $stockInfo['supplier_cd'] : '',
												'class_cd' 		=> 'TXT_supplier_cd',
												'is_required' 	=> true,
												'disabled_ime'	=> 'disabled-ime',
												'is_nm'    		=> true))
					</div>
				</div>
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">部品コード</label>
					<label class="col-md-6 control-label text-left DSP_parts_cd">{{ $stockInfo['parts_cd'] or '' }}</label>
					<!-- <div class="col-md-1">
						<input type="text" class="form-control TXT_parts_cd min-width80 required" name="TXT_parts_cd" value="{{ $stockInfo['parts_cd'] or '' }}" maxlength="6">
					</div> -->
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">部品名</label>
					<label class="col-md-6 control-label text-left DSP_part_nm">{{ $stockInfo['parts_nm'] or '' }}</label>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">規格</label>
					<label class="col-md-6 control-label text-left DSP_specification_stock">{{ $stockInfo['specification'] or '' }}</label>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold required">入庫数</label>
					<div class="col-md-1">
						<input type="text" class="form-control TXT_parts_receipt_qty text-right quantity numeric min-width150 required" name="TXT_parts_receipt_qty" value="{{ $stockInfo['parts_receipt_qty'] or '' }}" maxlength="8">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">単価</label>
					<label class="col-md-1 col-md-1-cus control-label text-right DSP_unit_price min-width163">{{ $stockInfo['unit_price'] or '' }}</label>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">金額</label>
					<div class="col-md-1">
						<input type="text" class="form-control TXT_detail_amt min-width150 money numeric" name="TXT_detail_amt" value="{{ $stockInfo['detail_amt'] or '' }}" maxlength="14">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">明細備考</label>
					<div class="col-md-6">
						<input type="text" class="form-control TXT_detail_remarks" name="TXT_detail_remarks" value="{{ $stockInfo['detail_remarks'] or '' }}" maxlength="200">
					</div>
				</div>
			</div>
			<input type="hidden" id="purchase_detail_amt_round_div"	value="{{ $purchase_detail_amt_round_div or 1 }}">
		</div>
@endsection