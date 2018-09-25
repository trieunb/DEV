@extends('layouts.popup')

@section('title')
	@lang('title.pi-search')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-add-new', 'btn-print', 'btn-approve'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/popup/css/pi_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/popup/js/pi_search.js')!!}
@endsection

@section('content')

	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.pi-search')</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">見積日</label>
					<div class="col-md-6 date-from-to date-estimate">
						<input type="text" name="TXT_pi_date_no_from" class="form-control number-date TXT_pi_date_no_from" style="display: inline;">
						<input value="" type="tel" name="TXT_pi_date_from" class="datepicker form-control date-from TXT_pi_date_from">
						
						<span class="">～</span>
		
						<input type="text" name="TXT_pi_date_no_to" class="form-control number-date TXT_pi_date_no_to" style="display: inline;">
						<input value="" type="tel" name="TXT_pi_date_to" class="datepicker form-control date-to TXT_pi_date_to">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">受注日</label>
					<div class="col-md-6 date-from-to date-order">
						<input type="text" name="TXT_rcv_date_no_from" class="form-control number-date TXT_rcv_date_no_from" style="display: inline;">
						<input value="" type="tel" name="TXT_rcv_date_from" class="datepicker form-control date-from TXT_rcv_date_from">
						
						<span class="">～</span>
		
						<input type="text" name="TXT_rcv_date_no_to" class="form-control number-date TXT_rcv_date_no_to" style="display: inline;">
						<input value="" type="tel" name="TXT_rcv_date_to" class="datepicker form-control date-to TXT_rcv_date_to">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">PI No</label>
					<div class="col-md-2" style="height: 25px;">
						<input type="text" name="TXT_pi_no" class="form-control disable-ime TXT_pi_no" value="" maxlength="12">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">受注 No</label>
					<div class="col-md-2" style="height: 25px;">
						@includeIf('popup.searchaccept', array(
														'class_cd' 		=> 'TXT_rcv_no',
														'disabled_ime'	=>	'disabled-ime'))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">取引先名</label>
					<div class="col-md-2" style="height: 25px;">
						<input type="text" name="TXT_cust_nm" class="form-control TXT_cust_nm" value="" maxlength="120">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">国コード</label>
					<div class="col-md-2">
						@include('popup.searchcountry', array(
														'class_cd'		=>	'TXT_country_cd',
														'different_jp' 	=>  true
													))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">ステータス</label>
					<div class="col-md-2" style="width:185px">
						<select name="CMB_pi_status_div" class="form-control pi_status_div CMB_pi_status_div">
							<option></option>
							@if(isset($pi_status_div))
								@foreach($pi_status_div as $k=>$v)
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
					<div class="col-md-2 text-right pull-right">
						<button type="button" id="btn-search-popup" class="btn btn-primary btn-icon w-60px">
							<i class="icon-search4"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /search field -->
		<!-- List PI -->
		<div id="pi-list">
			@includeIf('popup::PiSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
	</div>

@endsection


