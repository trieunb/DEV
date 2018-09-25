@extends('layouts.main')

@section('title')
	@lang('title.manufacturing-instruction-report')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-issue-instruction', 'btn-export'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/manufactureinstruction/css/manufacturing_instruction_report.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/manufactureinstruction/js/manufacturing_instruction_report.js')!!}
@endsection


@section('content')

	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">製造指示書発行</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">社内発注書発行日</label>
					<div class="col-md-5 date-from-to date-estimate">
						<input type="text" class="form-control number-date TXT_internal_purchase_order_no_from" name="TXT_internal_purchase_order_no_from" style="display: inline;" value="">

						<input value="" type="tel" class="datepicker form-control date-from TXT_internal_purchase_order_date_from" name="TXT_internal_purchase_order_date_from">
						
						<span class="">～</span>
		
						<input type="text" class="form-control number-date TXT_internal_purchase_order_no_to" style="display: inline;" value="" name="TXT_internal_purchase_order_no_to">
						<input value="" type="tel" class="datepicker form-control date-to TXT_internal_purchase_order_date_to" name="TXT_internal_purchase_order_date_to">
					</div>
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">希望納期</label>
					<div class="col-md-2 date">
						<input type="tel" class="form-control datepicker TXT_hope_delivery_date" value=""  name="TXT_hope_delivery_date">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">社内発注書番号</label>
					<input type="text" class="hidden country_suppliers_cd">
					<div class="col-md-2">
						@includeIf('popup.searchinternalorder', array(
											'class_cd' 		=> 'TXT_in_order_no',
											'disabled_ime'	=> 'disabled-ime'))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">製品コード</label>
					<input type="text" class="hidden country_suppliers_cd">
					<div class="col-md-3">
						@includeIf('popup.searchproduct', array('class_cd'=> 'TXT_product_cd','is_nm' => true))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">製品名</label>
					<div class="col-md-2">
						<input type="text" class="form-control TXT_product_nm" value="" maxlength="120" name="TXT_product_nm"> 
					</div>
					<label class="col-md-3 control-label text-left">（キーワード検索）</label>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">指示書種別</label>
					<div class="col-md-2">
						<select class="form-control manufacture_kind_div CMB_manufacture_kind_div" style="width: 100px;">
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
			</div>
		</div>
		<!-- /search field -->

		<div id="div-manufactor-report-list">
			@includeIf('manufactureinstruction::ManufacturingInstructionReport.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
		
	</div>

@endsection


