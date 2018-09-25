@extends('layouts.main')

@section('title')
	@lang('title.working-time-detail')
@endsection

@section('button')
	{{Button::button_left(array('btn-back', 'btn-save','btn-delete'), $mode)}}
@endsection

@section('stylesheet')
	{!! public_url('modules/workingtimemanage/css/working_time_detail.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/workingtimemanage/js/working_time_detail.js')!!}
@endsection


@section('content')
	<script>
		var mode 		= "{{$mode}}";
		var from 		= "{{$from}}";
		var userLogin 	= "{{$userLogin}}";
	</script>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.working-time-detail')</h5>
				<div id="operator_info">
					{!! infoMemberCreUp('', '', '', '') !!}
				</div>
			</div>
			<div class="panel-body">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">作業日報番号</label>
					<div class="col-md-3">
						@include('popup.searchworkingtime', 
								array(
								'val'=>isset($workReportNo) ? $workReportNo : '', 
								'id'=>'TXT_work_report_no', 
								'is_nm' => false,
								'is_required' => ($mode == 'U') ? true : false, 
								'is_disabled' => ($mode == 'I') ? true : false,
								)
							)
					</div>
				</div>
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">作業実施日</label>
					<div class="col-md-2 date">
						<input type="tel" class="datepicker form-control required" id="TXT_work_date" value="{{ date('Y/m/d') }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">作業担当者</label>
					<div class="col-md-3">
						@include('popup.searchuser', array(
															'val'=>isset($userCd) ? $userCd : $userLogin, 
															'id'=>'TXT_work_user_cd', 
															'is_required' => true,
															'class_nm' => 'show-text-overfollow'
														)
								)
					</div>
				</div>
				<div id="working_time_detail_id">
					@includeIf('workingtimemanage::WorkingTimeDetail.table_workingtime')
				</div>
				<div class="table-responsive">
					<table class="table table-total" style="border-top: none;">
						<tbody>
							<tr>
								<td colspan="3" class="text-right text-bold">合計作業時間</td>
								<td class="text-left" style="width: 300px">
									<span id="DSP_hours_total">00</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
									<span>時間</span> &nbsp;&nbsp; 
									<span id="DSP_minutes_total">00</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
									<span>分</span>
								</td>
								<td class="" style="width: 40px"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">コメント</label>
					<div class="col-md-10">
						<textarea class="form-control disable-resize" id="TXA_remarks" maxlength="200"></textarea>
					</div>
				</div>
			</div>
	</div>

@endsection

@section('content_hidden')
	<!-- table row -->
	<table class="hide">
		<tbody id="table-row">
			<tr class="">
				<td class="drag-handler text-center DSP_work_report_detail_no"></td>
				<td class="text-center" style="width: 170px;">
					@includeIf('popup.searchmanufacturinginstruction', array('is_nm' => false))
				</td>
				<td class="text-left">
					<div class="tooltip-overflow max-width20 DSP_item_nm_j" data-toggle="tooltip" data-placement="top" title=""></div>
				</td>
				<td class="text-left">
					<div class="input-group-select input-group-left" style="">
						<select class="form-control select-group-wrk work_hour_div ">
							<option></option>
							@if(isset($work_hour_div))
								@foreach($work_hour_div as $k=>$v)
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
						<span>時間</span>
					</div>
					<div class="input-group-select input-group-right" style="">
					<select class="form-control select-group-wrk work_time_div ">
						<option></option>
						@if(isset($work_time_div))
							@foreach($work_time_div as $k=>$v)
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
					<span>分</span>
					</div>
				</td>
				<td class="text-left">
					<input type="text" class="form-control TXT_memo" value="" maxlength="200" >
				</td>
				<td class="w-40px text-center">
					<button type="button" class="form-control remove-row">
						<span class="icon-cross2 text-danger"></span>
					</button>
				</td>
			</tr>
		</tbody>
	</table>
@endsection