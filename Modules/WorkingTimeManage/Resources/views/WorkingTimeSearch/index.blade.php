@extends('layouts.main')

@section('title')
	@lang('title.working-time-search')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-add-new','btn-export'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/workingtimemanage/css/working_time_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/workingtimemanage/js/working_time_search.js')!!}
@endsection

@section('content')

	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.working-time-search')</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">作業日報番号</label>
					<div class="col-md-3">
						<input type="text" id="TXT_work_report_no" class="form-control TXT_work_report_no" name="TXT_work_report_no" maxlength="12">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">作業担当者コード</label>
					<div class="col-md-3">
						<input type="text" id="TXT_work_user_cd" class="form-control TXT_work_user_cd" name="TXT_work_user_cd" maxlength="20">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">作業担当者名</label>
					<div class="col-md-3">
						<input type="text" id="TXT_user_nm_j" class="form-control TXT_user_nm_j" name="TXT_user_nm_j" maxlength="50">
					</div>
					<label class="col-md-2 col-md-2-cus control-label text-left">(キーワード検索)</label>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">作業実施日</label>
					<div class="col-md-6 date-from-to">
						<input type="text" class="form-control number-date TXT_working_date_no_from" style="display: inline;" id="TXT_working_date_no_from" name="TXT_working_date_no_from">
						<input type="tel" class="datepicker form-control TXT_working_date_from date-from" id="TXT_working_date_from" name="TXT_working_date_from">
						
						<span class="">～</span>
		
						<input type="text" class="form-control number-date TXT_working_date_no_to" style="display: inline;" id="TXT_working_date_no_to" name="TXT_working_date_no_to">
						<input type="tel" class="datepicker form-control TXT_working_date_to date-to" id="TXT_working_date_to" name="TXT_working_date_to">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">製造指示番号</label>
					<div class="col-md-3">
						<input type="text" id="TXT_manufacture_no" class="form-control TXT_manufacture_no" name="TXT_manufacture_no" maxlength="8">
					</div>
				</div>
			</div>
		</div>
		<div id="workingtime_list">
			@includeIf('workingtimemanage::WorkingTimeSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
@endsection