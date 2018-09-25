@extends('layouts.popup')

@section('title')
	部品発注書一覧
@endsection

@section('stylesheet')
	{!! public_url('modules/componentorder/css/order_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/popup/js/order_search.js')!!}
@endsection

@section('content')

	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">部品発注書一覧</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>

			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>

				<div class="form-group">
					<label class="col-md-1 control-label text-right text-bold min-width120">発注日</label>
					<div class="col-md-6 date-from-to date-order">
						<input type="text" name="TXT_parts_order_date_no_from" class="TXT_parts_order_date_no_from form-control number-date" style="display: inline;">
						<input type="tel" name="TXT_parts_order_date_from" class="TXT_parts_order_date_from datepicker form-control date-from">
						
						<span class="">～</span>
		
						<input type="text" name="TXT_parts_order_date_no_to" class="TXT_parts_order_date_no_to form-control number-date" style="display: inline;">
						<input type="tel" name="TXT_parts_order_date_to" class="TXT_parts_order_date_to datepicker form-control date-to">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 control-label text-right text-bold min-width120">注文番号</label>
					<div class="col-md-6">
						<input type="text" name="TXT_parts_order_no_from" class="TXT_parts_order_no_from form-control" maxlength="14" style="width: 160px; display: inline;">
						
						<span class="">～</span>
		
						<input type="text" name="TXT_parts_order_no_to" class="TXT_parts_order_no_to form-control" maxlength="14" style="width: 160px; display: inline;">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold min-width120">仕入先名</label>
					<div class="col-md-3">
						<input type="text" name="TXT_supplier_nm" class="TXT_supplier_nm form-control" maxlength="120">
					</div>
					<span class="col-md-3 text-left">（キーワード検索が可能です）</span>
				</div>

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold min-width120">部品名</label>
					<div class="col-md-3">
						<input type="text" name="TXT_part_nm" class="TXT_part_nm form-control" maxlength="120">
					</div>
					<span class="col-md-3 text-left">（キーワード検索が可能です）</span>
				</div>

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold min-width120">購入依頼書番号</label>
					<div class="col-md-1">					
						<input type="text" name="TXT_buy_no" class="TXT_buy_no form-control min-width165" maxlength="14">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold min-width120">社内発注書番号</label>
					<div class="col-md-1">
						<input type="text" name="TXT_internalorder_cd" class="TXT_internalorder_cd form-control min-width165" maxlength="10">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold min-width120">製造指示書番号</label>
					<div class="col-md-1">
						<input type="text" name="TXT_manufacture_no" class="TXT_manufacture_no form-control min-width165" maxlength="8">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-md-1 control-label text-right text-bold min-width120">ステータス</label>
					<div class="col-md-2" style="width:185px">
						<select name="CMB_status" class="form-control buy_status_div CMB_status">
						<option></option>
						@if(isset($buy_status_div))
							@foreach($buy_status_div as $k=>$v)
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
					<div class="col-md-2 text-right pull-right">
						<button type="button" id="btn-search-popup" class="btn btn-primary btn-icon w-60px">
							<i class="icon-search4"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /search field -->
		
		<div id="component-order-list">
			@includeIf('popup::OrderSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
@endsection