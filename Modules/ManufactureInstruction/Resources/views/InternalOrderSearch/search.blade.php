@extends('layouts.main')

@section('title')
	社内発注書一覧
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-add-new','btn-export','btn-issue'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/manufactureinstruction/css/internal_order_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/manufactureinstruction/js/internal_order_search.js')!!}
@endsection

@section('content')

	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">社内発注書一覧</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">社内発注書番号</label>
					<div class="col-md-1">
						<input type="text" class="form-control TXT_in_order_no" name="TXT_in_order_no" value="" maxlength="10">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">発注日</label>
					<div class="col-md-6 date-from-to date-estimate">
						<input value="" type="text" class="form-control number-date TXT_order_date_no_from" name="TXT_order_date_no_from" style="display: inline;">
						<input value="" type="tel" class="datepicker form-control date-from TXT_order_date_from" name="TXT_order_date_from">
						<span class="">～</span>
		
						<input type="text" class="form-control number-date TXT_order_date_no_to" name="TXT_order_date_no_to" style="display: inline;" value="">
						<input value="" type="tel" class="datepicker form-control date-to TXT_order_date_to" name="TXT_order_date_to">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">発注者氏名</label>
					<div class="col-md-2">
						<input type="text" class="form-control TXT_orderer_nm" name="TXT_orderer_nm" value="" maxlength="50"> 
					</div>
					<label class="col-md-3 control-label text-left">(キーワード検索が可能です)</label>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">製品名</label>
					<div class="col-md-2">
						<input type="text" class="form-control TXT_product_nm" name="TXT_product_nm" value="" maxlength="120">
					</div>
					<label class="col-md-3 control-label text-left">(キーワード検索が可能です)</label>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">製造指示状況</label>
					<div class="col-md-2">
						<select class="form-control manufacture_status_div CMB_manufacture_status_div" name="CMB_manufacture_status_div" style="width: 100px;">
						<option></option>
						@if(isset($manufacture_status_div))
							@foreach($manufacture_status_div as $k=>$v)
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
					<!-- {{Button::button_search(false)}} -->
				</div>
			</div>
		</div>
		
		<!-- /search field -->
		<div id="div-internal-list">
			@includeIf('manufactureinstruction::InternalOrderSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
		
	</div>

@endsection


