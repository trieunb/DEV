@extends('layouts.popup')

@section('title')
	@lang('title.stock-manage-search')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-add-new', 'btn-export'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/popup/css/inputoutput_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/popup/js/inputoutput_search.js')!!}
@endsection

@section('content')
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.stock-manage-search')</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">入出庫日</label>
					<div class="col-md-6 date-from-to input-output-date-to-from">
						<input type="text" name="TXT_in_out_date_from_no" class="form-control number-date TXT_in_out_date_from_no" style="display: inline;" value="">
						<input value="" type="tel" name="TXT_in_out_date_from" class="datepicker form-control date-from TXT_in_out_date_from">
						
						<span class="">～</span>
		
						<input type="text" name="TXT_in_out_date_to_no" class="form-control number-date TXT_in_out_date_to_no" style="display: inline;" value="">
						<input value="" type="tel" name="TXT_in_out_date_to" class="datepicker form-control date-to TXT_in_out_date_to">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">入出庫No</label>
					<div class="col-md-2">
						<input type="text" name="TXT_in_out_no" class="form-control TXT_in_out_no" name="" value="" maxlength="14">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">品目コード</label>
					<div class="col-md-2">
						@includeIf('popup.searchcomponentproduct', array(
																'class_cd' 		=> 'TXT_item_cd',
																'is_nm'			=>	true
															))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">倉庫</label>
					<div class="col-md-2">
						@includeIf('popup.searchwarehouse', array(
																'class_cd' 		=> 'TXT_warehouse_div',
																'is_nm'			=>	true
															))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">入出庫区分</label>
					<div class="col-md-2">
						<select name="CMB_in_out_div" class="form-control in_out_div CMB_in_out_div">
							<option></option>
							@if(isset($in_out_div))
								@foreach($in_out_div as $k=>$v)
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
					<label class="col-md-2 control-label text-right text-bold">入力種別</label>
					<div class="col-md-2">
						<select name="CMB_in_out_data_div" class="form-control in_out_data_div CMB_in_out_data_div">
							<option></option>
							@if(isset($in_out_data_div))
								@foreach($in_out_data_div as $k=>$v)
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
		<div id="input-output-list">
		@includeIf('popup::InputOutputSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
	</div>
@endsection


