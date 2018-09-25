@extends('layouts.main')

@section('title')
	入金票一覧
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-add-new','btn-export'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/deposit/css/deposit_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/deposit/js/deposit_search.js')!!}
@endsection

@section('content')

	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">入金票一覧</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>

				<div class="form-group">
					<label class="col-md-1 control-label text-right text-bold">入金日</label>
					<div class="col-md-6 date-from-to">
						<input type="text" name="TXT_deposit_date_no_from" class="TXT_deposit_date_no_from form-control number-date" style="display: inline;">
						<input name="TXT_deposit_date_from" type="tel" class="TXT_deposit_date_from datepicker form-control date-from">

						<span class="">～</span>

						<input type="text" name="TXT_deposit_date_no_to" class="TXT_deposit_date_no_to form-control number-date" style="display: inline;">
						<input type="tel" name="TXT_deposit_date_to" class="TXT_deposit_date_to datepicker form-control date-to">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 control-label text-right text-bold">入金No</label>
					<div class="col-md-2">
						<input type="text" name="TXT_deposit_no" class="form-control left-radius right-radius TXT_deposit_no" maxlength="10">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 control-label text-right text-bold">受注No</label>
					<div class="col-md-2">
						@includeIf('popup.searchaccept', array(
							'id'       => 'TXT_rcv_no',
							'class_cd' => 'TXT_rcv_no',
							'is_nm'    => false
						))
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 control-label text-right text-bold">取引先名</label>
					<div class="col-md-4">
						<input name="TXT_client_nm" type="text" class="TXT_client_nm form-control" maxlength="120">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 control-label text-right text-bold">国コード</label>
					<div class="col-md-2">
						@includeIf('popup.searchcountry', array(
												'class_cd'     	=> 'TXT_country_div', 
												'class_nm'     	=> 'DSP_country_nm',
												'is_nm'        	=> true,
									  			'different_jp' 	=> true))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">分割入金管理</label>
					<div class="">
						<div class="col-md-2" style="float:left">
							<select class="form-control target_div CMB_split_deposit_div required" id="CMB_split_deposit_div" name="CMB_split_deposit_div">
							<option></option>
							@if(isset($target_div))
								@foreach($target_div as $k=>$v)
									<option value="{{$v['lib_val_cd']}}" 
											data-ctl1="{{$v['lib_val_ctl1']}}" data-ctl2="{{$v['lib_val_ctl2']}}"
											data-ctl5="{{$v['lib_val_ctl5']}}" data-ctl6="{{$v['lib_val_ctl6']}}"
											data-nm-j="{{$v['lib_val_nm_j']}}" data-nm-e="{{$v['lib_val_nm_e']}}">
										{{$v['lib_val_nm_j']}} 
									</option>
								@endforeach
							@endif
							</select>
						</div>
						<div style="float:left">
							<span class="input-group-text employee_nm">※対象選択時、分割入金管理票に表示されます。</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /search field -->
		<!-- List deposit -->
		<div id="deposit-list">
			@includeIf('deposit::DepositSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
	</div>

@endsection