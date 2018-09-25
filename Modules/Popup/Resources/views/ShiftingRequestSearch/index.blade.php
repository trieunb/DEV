@extends('layouts.popup')

@section('title')
	移動依頼票一覧
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-add-new','btn-export'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/popup/css/search_shifting.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/popup/js/search_shifting.js')!!}
@endsection

@section('content')

	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">移動依頼票一覧</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">品目コード</label>
					<div class="col-md-3">
						<input type="text" name="TXT_item_cd" id="item_cd" class="form-control TXT_item_cd" value="" maxlength="6">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">製造指示書番号</label>
					<div class="col-md-3">
						<div class="input-group">
							@include('popup.searchmanufacturinginstruction', array(
												'class_cd' 		=> 'TXT_manufacture_no',
												'is_required' 	=> false,
												'disabled_ime'	=> 'disabled-ime',
												'is_nm'    		=> false))
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">移動依頼No</label>
					<div class="col-md-3">
						<input type="text" id="move_no" name="TXT_move_no" class="form-control TXT_move_no" value="" maxlength="14">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">登録日</label>
					<div class="col-md-6 date-from-to">
						<input type="text" name="TXT_register_date_no_from" class="form-control number-date TXT_register_date_no_from" style="display: inline;" value="" maxlength="3">
						<input value="" type="tel" name="TXT_register_date_from" class="datepicker form-control date-from TXT_register_date_from" maxlength="10">
						
						<span class="">～</span>
		
						<input type="text" name="TXT_register_date_no_to" class="form-control number-date TXT_register_date_no_to" style="display: inline;" value="" maxlength="3">
						<input value="" type="tel" name="TXT_register_date_to" class="datepicker form-control date-to TXT_register_date_to"  maxlength="10">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">移動希望日</label>
					<div class="col-md-6 date-estimate">
						<input type="text" name="TXT_desire_date_move_no_from" class="form-control number-date TXT_desire_date_move_no_from" style="display: inline;" value="" maxlength="3">
						<input value="" type="tel" name="TXT_desire_date_move_from" class="datepicker form-control TXT_desire_date_move_from date-from" maxlength="10">
						
						<span class="">～</span>
		
						<input type="text" name="TXT_desire_date_move_no_to" class="form-control number-date TXT_desire_date_move_no_to" style="display: inline;" value="" maxlength="3">
						<input value="" type="tel" name="TXT_desire_date_move_to" class="datepicker form-control TXT_desire_date_move_to date-to" maxlength="10">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">出庫倉庫</label>
					<div class="col-md-2">
						@includeIf('popup.searchwarehouse', array(
												'class_cd' 		=> 'TXT_out_warehouse_div',
												'is_required' 	=> true,
												'disabled_ime'	=> 'disabled-ime',
												'is_nm'			=> true,
												'is_required'	=> true))
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">入庫倉庫</label>
					<div class="col-md-2">
						@includeIf('popup.searchwarehouse', array(
												'class_cd' 		=> 'TXT_in_warehouse_div',
												'is_required' 	=> true,
												'disabled_ime'	=> 'disabled-ime',
												'is_nm'			=> true,
												'is_required'	=> true))
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">ステータス</label>
					<div class="col-md-2" style="width:185px">
						<select name="CMB_move_status_div" class="form-control move_status_div CMB_move_status_div">
							<option></option>
							@if(isset($move_status_div))
								@foreach($move_status_div as $k=>$v)
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

				<div class="form-group">
					<div class="col-md-3 text-right pull-right">
						<button type="button" id="btn-search-popup" class="btn btn-primary btn-icon btn-search-popup" name="btn-search-popup" style="width: 82px;">
							<i class="icon-search4"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /search field -->

		<div id="div-shifting-list">
			@includeIf('popup::ShiftingRequestSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
@endsection