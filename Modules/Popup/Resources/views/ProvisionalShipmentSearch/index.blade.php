@extends('layouts.popup')

@section('title')
	@lang('title.provisional-shipment-search')
@endsection

@section('stylesheet')
	{!! public_url('modules/popup/css/provisional_shipment_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/popup/js/provisional_shipment_search.js')!!}
@endsection

@section('content')

	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.provisional-shipment-search')</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">作成日</label>
					<div class="col-md-6 date-from-to">
						<input type="text" class="form-control number-date TXT_cre_date_no_from" id="TXT_cre_date_no_from" name="TXT_cre_date_no_from" tabindex="1"  style="display: inline;" value="">
						<input value="" type="tel" class="datepicker form-control date-from TXT_cre_date_from" id="TXT_cre_date_from" name="TXT_cre_date_from" tabindex="2">
						<span class="">～</span>
						<input type="text" class="form-control number-date TXT_cre_date_no_to" style="display: inline;" value="" id="TXT_cre_date_no_to" name="TXT_cre_date_no_to" tabindex="3">
						<input value="" type="tel" class="datepicker form-control date-to TXT_cre_date_to" id="TXT_cre_date_to" name="TXT_cre_date_to" tabindex="4">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">仮出荷指示No</label>
					<div class="col-md-2">
						<input type="text" class="form-control TXT_fwd_no" id="TXT_fwd_no" name="TXT_fwd_no" value="" tabindex="5" maxlength="12">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold " >取引先名</label>
					<div class="col-md-3">
						<input type="text" class="form-control TXT_client_nm" value="" id="TXT_client_nm" name="TXT_client_nm" tabindex="6" maxlength="120">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">国コード</label>
					<div class="col-md-2" tabindex="7">
						@includeIf('popup.searchcountry', array(
												'class_cd'     	=> 'TXT_country_div', 
												'class_nm'     	=> 'DSP_country_nm',
												'is_nm'        	=> true,
												'different_jp' 	=> true))
					</div>
					<div class="col-md-2 text-right pull-right">
						<button type="button" id="btn-search-popup" class="btn btn-primary btn-icon w-60px">
							<i class="icon-search4"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /search field -->
		<!-- List shipment -->
		<div id="div-shipment-list">
			@includeIf('shipment::ProvisionalShipmentSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
	</div>

@endsection