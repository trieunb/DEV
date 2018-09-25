@extends('layouts.main')

@section('title')
	@lang('title.manufacturing-instruction-search')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-manufacturing-instruction', 'btn-export', 'btn-good-issue-source'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/manufactureinstruction/css/manufacturing_instruction_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/manufactureinstruction/js/manufacturing_instruction_search.js')!!}
@endsection


@section('content')
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">製造指示書一覧</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">製造指示番号</label>
					<div class="col-md-3">
						<input type="text" class="form-control TXT_manufacture_no" name="TXT_manufacture_no" id="TXT_manufacture_no" maxlength="8">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">社内発注書番号</label>
					<div class="col-md-3">
						<input type="text" class="form-control TXT_in_order_no" name="TXT_in_order_no" id="TXT_in_order_no" maxlength="10">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">製造指示日</label>
					<div class="col-md-6 date-from-to date-order">
						<input type="text" class="form-control number-date TXT_mannufacturing_instruction_date_no_from" name="TXT_mannufacturing_instruction_date_no_from" style="display: inline;">
						<input value="" type="tel" class="datepicker form-control date-from TXT_mannufacturing_instruction_date_from" name="TXT_mannufacturing_instruction_date_from">
						
						<span class="">～</span>
		
						<input type="text" class="form-control number-date TXT_mannufacturing_instruction_date_no_to" name="TXT_mannufacturing_instruction_date_no_to" style="display: inline;">
						<input type="tel" class="datepicker form-control date-to TXT_mannufacturing_instruction_date_to" name="TXT_mannufacturing_instruction_date_to">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">発注者名</label>
					<div class="col-md-3">
						<input type="text" class="form-control TXT_orderer_nm" name="TXT_orderer_nm" id="TXT_orderer_nm" maxlength="120"> 
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">処理</label>
					<div class="col-md-1">
						<select class="form-control manufacture_kind_div CMB_manufacture_kind_div" name="CMB_manufacture_kind_div">
						<option></option>
						@if(isset($manufacture_kind_div))
							@foreach($manufacture_kind_div as $k=>$v)
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
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">内製・外注</label>
					<div class="col-md-1">
						<select class="form-control outsourcing_div CMB_inhouse_outsourcing_manufacturing" name="CMB_inhouse_outsourcing_manufacturing">
						<option></option>
						@if(isset($outsourcing_div))
							@foreach($outsourcing_div as $k=>$v)
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
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">製造状況</label>
					<div class="col-md-1">
						<select class="form-control production_status_div CMB_production_status" name="CMB_production_status">
						<option></option>
						@if(isset($production_status_div))
							@foreach($production_status_div as $k=>$v)
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
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">製造完了日</label>
					<div class="col-md-6 date-from-to date-manufacturing-completion">
						<input type="text" class="form-control number-date TXT_mannufacturing_completion_date_no_from" name="TXT_mannufacturing_completion_date_no_from" style="display: inline;">
						<input type="tel" class="datepicker form-control date-from TXT_mannufacturing_completion_date_from" name="TXT_mannufacturing_completion_date_from">
						
						<span class="">～</span>
		
						<input type="text" class="form-control number-date TXT_mannufacturing_completion_date_no_to" name="TXT_mannufacturing_completion_date_no_to" style="display: inline;">
						<input type="tel" class="datepicker form-control date-to TXT_mannufacturing_completion_date_to" name="TXT_mannufacturing_completion_date_to">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">出庫元データ作成</label>
					<div class="col-md-1">
						<select class="form-control done_div CMB_create_shipement_source_data" name="CMB_create_shipement_source_data">
						<option></option>
						@if(isset($done_div))
							@foreach($done_div as $k=>$v)
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
				</div>
			</div>
		</div>
		<!-- /search field -->
		<div id="div-manufactor-search-list">
			@includeIf('manufactureinstruction::ManufacturingInstructionSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
	</div>
@endsection
