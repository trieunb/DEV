@extends('layouts.popup')

@section('title')
	@lang('title.shipment-serial-search')
@endsection

@section('stylesheet')
	{!! public_url('modules/popup/css/shipment_serial_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/popup/js/shipment_serial_search.js')!!}
@endsection

@section('content')

	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.shipment-serial-search')</h5>
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
					<label class="col-md-1 control-label text-right text-bold ">受注数量</label>
					<label class="col-md-1 control-label text-left DSP_qty"></label>
					<label class="col-md-1 control-label text-right text-bold">残数量</label>
					<label class="col-md-1 control-label text-left DSP_remaining_qty"></label>
					<label class="col-md-1 control-label text-right text-bold">今回指示数</label>
					<label class="col-md-1 control-label text-left intruction_number DSP_fwd_qty"></label>
				</div>
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">表示数量</label>
					<div class="col-md-2">
						<input type="text" class="form-control text-right TXT_count price" maxlength="6" id="TXT_count" name="TXT_count" style="width: 160px; display: inline;" value="">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">製造指示番号</label>
					<div class="col-md-6">
						<input type="text" class="form-control TXT_manufacture_no_from" id="TXT_manufacture_no_from" name="TXT_manufacture_no_from" style="width: 160px; display: inline;" value="" tabindex="5"  maxlength="8">
						
						<span class="">～</span>
		
						<input type="text" class="form-control TXT_manufacture_no_to" id="TXT_manufacture_no_to" name="TXT_manufacture_no_to" style="width: 160px; display: inline;" value="" tabindex="6"  maxlength="8">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">シリアル番号</label>
					<div class="col-md-6">
						<input type="text" class="form-control TXT_serial_no_from" id="TXT_serial_no_from" name="TXT_serial_no_from" style="width: 160px; display: inline;" value="" tabindex="5"  maxlength="7">
						
						<span class="">～</span>
		
						<input type="text" class="form-control TXT_serial_no_to" id="TXT_serial_no_to" name="TXT_serial_no_to" style="width: 160px; display: inline;" value="" tabindex="6"  maxlength="7">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 text-right pull-right">
						<button type="button" id="BTN_search" class="btn btn-primary btn-icon w-60px BTN_search" name="BTN_search">
							<i class="icon-search4"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /search field -->
		<!-- List PI -->
		<div id="shipment-serial-list">
			@includeIf('popup::ShipmentSerialSearch.list')
		</div>
	</div>

@endsection