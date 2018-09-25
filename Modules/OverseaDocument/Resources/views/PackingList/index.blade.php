@extends('layouts.main')

@section('title')
	@lang('title.packing-list')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-issue', 'btn-export'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/overseadocument/css/packing_list.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/overseadocument/js/packing_list.js')!!}
@endsection

@section('content')
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.packing-list')</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">Invoice Date</label>
					<div class="col-md-6 date-from-to date-estimate">
						<input type="text" class="form-control number-date TXT_pi_date_no_from" style="display: inline;" value="">
						<input value="" type="tel" class="datepicker form-control date-from TXT_inv_date_no_from">
						
						<span class="">～</span>
		
						<input type="text" class="form-control number-date TXT_pi_date_no_to" style="display: inline;" value="">
						<input value="" type="tel" class="datepicker form-control date-to TXT_inv_date_no_to">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">Invoice No</label>
					<div class="col-md-2">
						<input type="text" class="form-control TXT_rcv_no" value="" maxlength="11">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">取引先名</label>
					<div class="col-md-2">
						<input type="text" class="form-control TXT_cust_nm" value="" maxlength="120">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">国コード</label>
					<div class="col-md-2">
						@include('popup.searchcountry', 
								array('val'			 	=>'',
									  'class' 			=>'',
									  'different_jp' 	=>  true))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">発行済フラグ</label>
					<div class="col-md-2">
						<select class="form-control done_div CMB_status">
							<option></option>
							@if(isset($done_div))
								@foreach($done_div as $k=>$v)
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
		<div id="div-packing-list">
			@includeIf('overseadocument::PackingList.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
		
@endsection