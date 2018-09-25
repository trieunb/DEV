@extends('layouts.popup')

@section('title')
	購入依頼書一覧
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-add-new', 'btn-issue','btn-export'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/popup/css/purchase_request_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/popup/js/purchase_request_search.js')!!}
@endsection

@section('content')

	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">購入依頼書一覧</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold required">購入依頼日</label>
					<div class="col-md-6 date-from-to">
						<input type="text" name="TXT_buy_date_no_from" class="form-control number-date TXT_buy_date_no_from" style="display: inline;" value="">
						<input value="" type="tel" name="TXT_buy_date_from" class="datepicker form-control TXT_buy_date_from">
						
						<span class="">～</span>
		
						<input type="text" name="TXT_buy_date_no_to" class="form-control number-date TXT_buy_date_no_to" style="display: inline;" value="">
						<input value="" type="tel" name="TXT_buy_date_to" class="datepicker form-control TXT_buy_date_to">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">購入依頼番号</label>
					<div class="col-md-6 date-from-to">
						<input type="text" name="TXT_buy_no_from" class="form-control TXT_buy_no_from" style="width: 160px; display: inline;" value="" maxlength="14">
						
						<span class="">～</span>
		
						<input type="text" name="TXT_buy_no_to" class="form-control TXT_buy_no_to" style="width: 160px; display: inline;" value="" maxlength="14">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold">仕入先名</label>
					<div class="col-md-3">
						<input type="text" name="TXT_supplier_nm" id="" class="form-control TXT_supplier_nm" value="" maxlength="50">
					</div>
					<span class="col-md-3 text-left">（キーワード検索が可能です）</span>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold">部品名</label>
					<div class="col-md-3">
						<input type="text" name="TXT_parts_nm" id="" class="form-control TXT_parts_nm" value="" maxlength="120">
					</div>
					<span class="col-md-3 text-left">（キーワード検索が可能です）</span>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">ステータス</label>
					<div class="col-md-2" style="width:185px">
						<select name="CMB_buy_status_div" class="form-control buy_status_div CMB_buy_status_div">
							<option></option>
							@if(isset($buy_status_div))
								@foreach($buy_status_div as $k=>$v)
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
		<div id="purchase-request-list">
			@includeIf('popup::PurchaseRequestSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
@endsection