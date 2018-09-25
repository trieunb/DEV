@extends('layouts.main')

@section('title')
	@lang('title.component-list-detail')
@endsection

@section('button')
	{{Button::button_left(array('btn-back', 'btn-save','btn-delete'), $mode)}}
@endsection

@section('stylesheet')
	{!! public_url('modules/master/css/component_list_detail.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/master/js/component_list_detail.js')!!}
@endsection


@section('content')
	<script>
		var mode 	=	"{{$mode}}";
		var from 	=	"{{$from}}";
		var is_new 	=	"{{$is_new}}";
	</script>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.component-list-detail')</h5>
				<div class="infor-created">
					@includeIf('layouts._operator_info')
				</div>
			</div>
			<div class="panel-body">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group group-item">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">親品目コード</label>
					<div class="col-md-1">
						@includeIf('popup.searchcomponentproduct', array(
																		'class_cd'    => 'TXT_parent_item_cd', 
																		'is_required' => true,
																		'disabled_ime'=> 'disabled-ime',
																		'is_nm'       => true,
																		'val'		  => isset($parent_item_cd) ? $parent_item_cd : ''
																	))
					</div>
				</div>
				<div class="form-group group-item">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">子品目コード</label>
					<div class="col-md-1">
						@includeIf('popup.searchcomponentproduct',  array(
																		'class_cd'    => 'TXT_child_item_cd', 
																		'is_required' => true,
																		'disabled_ime'=> 'disabled-ime',
																		'is_nm'       => true,
																		'val'		  => isset($child_item_cd) ? $child_item_cd : ''
																	))
					</div>
				</div>
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">必要部品数量</label>
					<div class="col-md-1">
						<input type="text" id="" class="form-control quantity numeric TXT_child_item_qty required" value="" maxlength="6">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">単位</label>
					<div class="col-md-1">
						<select class="form-control unit_q_div CMB_unit" data-ini-target=true id="unit_q_div" data-lib-cd="">
							<option></option>
							@if(isset($unit_q_div))
								@foreach($unit_q_div as $k=>$v)
									<option value="{{$v['lib_val_cd']}}" 
											data-ctl1="{{$v['lib_val_ctl1']}}" data-ctl2="{{$v['lib_val_ctl2']}}"
											data-ctl5="{{$v['lib_val_ctl5']}}" data-ctl6="{{$v['lib_val_ctl6']}}"
											data-nm-j="{{$v['lib_val_nm_j']}}" data-nm-e="{{$v['lib_val_nm_e']}}"
											data-ini_target_div="{{$v['ini_target_div']}}" @if($v['ini_target_div']=='1') selected @endif>
										{{$v['lib_val_nm_j']}} 
									</option>
								@endforeach
							@endif
						</select>
					</div>
					<label class="col-md-1 col-md-1-cus control-label text-left" id="lbl_unit_q_div_e"></label>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-bold text-right">適用期間</label>
					<div class="col-md-6 date-from-to">
						<input value="" type="tel" class="datepicker date-from form-control TXT_application_period_from">
						
						<span class="">～</span>

						<input value="" type="tel" class="datepicker date-to form-control TXT_application_period_to">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">備考</label>
					<div class="col-md-10">
						<textarea id="" class="form-control disable-resize TXT_remarks" maxlength="200"></textarea>
					</div>
				</div>
			</div>
	</div>
@endsection