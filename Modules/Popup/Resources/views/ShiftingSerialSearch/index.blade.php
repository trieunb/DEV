@extends('layouts.popup')

@section('title')
	移動依頼シリアル番号選択ダイアログ
@endsection

@section('stylesheet')
	{!! public_url('modules/popup/css/shifting_serial_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/popup/js/shifting_serial_search.js')!!}
@endsection

@section('content')

	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">移動依頼シリアル番号選択</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>

				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold ">製品</label>
					<label class="col-md-1 control-label text-left DSP_product_cd"></label>
					<label class="col-md-3 control-label text-left DSP_item_nm_j"></label>
					<label class="col-md-1 control-label text-right text-bold ">出庫倉庫</label>
					<label class="col-md-1 control-label text-left DSP_out_warehouse_div"></label>
					<label class="col-md-1 control-label text-left DSP_out_warehouse_div_nm"></label>
					<label class="col-md-1 control-label text-right text-bold">移動数量</label>
					<label class="col-md-1 control-label text-left DSP_move_qty"></label>
				</div>

				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>

				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">表示数量</label>
					<div class="col-md-2">
						<input type="text" class="form-control TXT_count quantity" id="TXT_count" name="TXT_count" style="width: 160px; display: inline;" value="" maxlength="6">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">製造指示番号</label>
					<div class="col-md-6">
						<input type="text" class="form-control TXT_manufacture_no_from" id="TXT_manufacture_no_from" name="TXT_manufacture_no_from" style="width: 160px; display: inline;" value="" tabindex="5" maxlength="8">
						
						<span class="">～</span>
		
						<input type="text" class="form-control TXT_manufacture_no_to" id="TXT_manufacture_no_to" name="TXT_manufacture_no_to" style="width: 160px; display: inline;" value="" tabindex="6" maxlength="8">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">シリアル番号</label>
					<div class="col-md-6">
						<input type="text" class="form-control TXT_serial_no_from" id="TXT_serial_no_from" name="TXT_serial_no_from" style="width: 160px; display: inline;" value="" tabindex="5" maxlength="7">
						
						<span class="">～</span>
		
						<input type="text" class="form-control TXT_serial_no_to" id="TXT_serial_no_to" name="TXT_serial_no_to" style="width: 160px; display: inline;" value="" tabindex="6" maxlength="7">
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-3 text-right pull-right">
						<button type="button" id="BTN_search" class="btn btn-primary btn-icon BTN_search" name="BTN_search">
							<i class="icon-search4"></i>
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- List shifting -->
		<div id="shifting-serial-list">
			@includeIf('popup::ShiftingSerialSearch.list')
		</div>
	</div>

@endsection