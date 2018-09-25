@extends('layouts.popup')

@section('title')
	Invoice一覧
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-add-new','btn-issue', 'btn-export'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/popup/css/invoice_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/popup/js/invoice_search.js')!!}
@endsection

@section('content')

	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">Invoice一覧</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<hr style="margin-top: 10px; width: 98%">
			<div class="panel-body search-condition">
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">Invoice Date</label>
					<div class="col-md-6 date-from-to date-order">
						<input type="text" name="TXT_inv_date_no_from" class="form-control number-date TXT_inv_date_no_from" style="display: inline;" value="">
						<input value="" type="tel" name="TXT_inv_date_from" class="datepicker form-control date-from TXT_inv_date_from">

						<span class="">～</span>

						<input type="text" name="TXT_inv_date_no_to" class="form-control number-date TXT_inv_date_no_to" style="display: inline;" value="">
						<input value="" type="tel" name="TXT_inv_date_to" class="datepicker form-control date-to TXT_inv_date_to">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">Invoice No</label>
					<div class="col-md-2">
						@includeIf('popup.searchinvoice', 
								    array('class_cd'	=> "TXT_inv_no",
								          'disableb_ime'=> 'disable-ime',
								          'is_nm'		=> false))
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">受注No</label>
					<div class="col-md-2">
						@includeIf('popup.searchaccept',
								    array('class_cd'	=> "TXT_rcv_no",
								          'disableb_ime'=> 'disable-ime',
								          'is_nm'		=> false))
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">PINo</label>
					<div class="col-md-2">
						@includeIf('popup.searchpi', 
								    array('class_cd' 	=> 'TXT_pi_no',
								         'is_nm'		=> false))
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">取引先名</label>
					<div class="col-md-2">
						<input type="text" id="TXT_client_nm" class="TXT_client_nm form-control ime-active required" maxlength="120">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">国コード</label>
					<div class="col-md-2">
						@includeIf('popup.searchcountry', 
									array(	'class_cd' 	=> 'TXT_country_div',
										  	'class_nm' 	=> 'TXT_country_nm',
										  	'is_nm' 		=> true,
											'different_jp' 	=> true))
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">ステータス</label>
					<div class="col-md-2" style="width:185px">
						<select name="CMB_status" class="form-control inv_data_div CMB_status">
							<option></option>
							@if(isset($inv_data_div))
								@foreach($inv_data_div as $k=>$v)
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

		<div id="invoice-list">
			@includeIf('invoice::InvoiceSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
	</div>

@endsection