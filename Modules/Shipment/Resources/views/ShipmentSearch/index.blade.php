@extends('layouts.main')

@section('title')
	@lang('title.shipment-search')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-add-new','btn-export', 'btn-approve-estimate','btn-issue'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/shipment/css/shipment_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/shipment/js/shipment_search.js')!!}
@endsection

@section('content')

	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.shipment-search')</h5>
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
						<input type="text" name="TXT_cre_date_no_from" class="form-control number-date TXT_cre_date_no_from" style="display: inline;" value="">
						<input value="" type="tel" name="TXT_cre_date_from" class="datepicker form-control date-from TXT_cre_date_from">
						<span class="">～</span>
						<input type="text" name="TXT_cre_date_no_to" class="form-control number-date TXT_cre_date_no_to" style="display: inline;" value="">
						<input value="" type="tel" name="TXT_cre_date_to" class="datepicker form-control date-to TXT_cre_date_to">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">出荷指示No</label>
					<div class="col-md-2">
						<input type="text" name="TXT_fwd_no" class="form-control TXT_fwd_no" value="" maxlength="12">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">取引先名</label>
					<div class="col-md-3">
						<input type="text" name="TXT_client_nm" class="form-control TXT_client_nm" value="" maxlength="120">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">国コード</label>
					<div class="col-md-2">
						@include('popup.searchcountry', array(
														'class_cd'		=>	'TXT_country_cd',
														'different_jp' 	=>  true
													))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right"><strong>ステータス</strong></label>
					<div class="col-md-2" style="width:185px">
						<select name="CMB_status" class="form-control fwd_status_div CMB_status">
							<option></option>
							@if(isset($fwd_status_div))
								@foreach($fwd_status_div as $k=>$v)
									<option value="{{$v['lib_val_cd']}}" 
											data-ctl1="{{$v['lib_val_ctl1']}}" data-ctl2="{{$v['lib_val_ctl2']}}"
											data-ctl5="{{$v['lib_val_ctl5']}}" data-ctl6="{{$v['lib_val_ctl6']}}"
											data-nm-j="{{$v['lib_val_nm_j']}}" data-nm-e="{{$v['lib_val_nm_e']}}"
											data-ini_target_div="{{$v['ini_target_div']}}">
										{{$v['lib_val_nm_j']}} 
									</option>
								@endforeach
							@endif
						</select>
					</div>
				</div>
			</div>
		</div>
		<!-- /search field -->
		<!-- List PI -->
		<div id="shipment-list">
			@includeIf('shipment::ShipmentSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
	</div>

@endsection