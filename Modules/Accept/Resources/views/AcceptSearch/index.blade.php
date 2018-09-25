@extends('layouts.main')

@section('title')
	@lang('title.accept-search')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-add-new','btn-export', 'btn-approve'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/accept/css/accept_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/accept/js/accept_search.js')!!}
@endsection

@section('content')
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">受注一覧</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>

				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">受注日</label>
					<div class="col-md-6 date-from-to date-order">
						<input type="text" name="TXT_rcv_date_no_from" class="form-control number-date disable-ime TXT_rcv_date_no_from" style="display: inline;" value="">
						<input value="" type="tel" name="TXT_rcv_date_from" class="datepicker form-control date-from TXT_rcv_date_from">

						<span class="">～</span>

						<input type="text" name="TXT_rcv_date_no_to" class="form-control number-date disable-ime TXT_rcv_date_no_to" style="display: inline;" value="">
						<input value="" type="tel" name="TXT_rcv_date_to" class="datepicker form-control date-to TXT_rcv_date_to">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">受注No</label>
					<div class="col-md-1">
						<input type="text" name="TXT_rcv_no" class="form-control TXT_rcv_no" maxlength="10" style="min-width: 160px;">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">取引先名</label>
					<div class="col-md-3">
						<input type="text" name="TXT_cust_nm" class="form-control TXT_cust_nm" maxlength="120">
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
						<select name="CMB_rcv_status_div" class="form-control rcv_status_div CMB_rcv_status_div">
							<option></option>
							@if(isset($rcv_status_div))
								@foreach($rcv_status_div as $k=>$v)
									<option value="{{$v['lib_val_cd']}}" 
											data-ctl1="{{$v['lib_val_ctl1']}}" data-ctl2="{{$v['lib_val_ctl2']}}"
											data-ctl5="{{$v['lib_val_ctl5']}}" data-ctl6="{{$v['lib_val_ctl6']}}"
											data-nm-j="{{$v['lib_val_nm_j']}}" data-nm-e="{{$v['lib_val_nm_e']}}"
											>
										{{$v['lib_val_nm_j']}} 
									</option>
								@endforeach
							@endif
						</select>
					</div>
				</div>
			</div>
		</div>

		<div id="rcv-list">
			@includeIf('accept::AcceptSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
@endsection