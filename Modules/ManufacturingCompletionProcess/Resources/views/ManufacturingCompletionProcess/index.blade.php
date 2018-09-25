@extends('layouts.main')

@section('title')
	@lang('title.manufacturing-completion-process')
@endsection

@section('button')
	{{Button::button_left(array('btn-back', 'btn-save'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/manufacturingcompletionprocess/css/manufacturing_completion_process.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/manufacturingcompletionprocess/js/manufacturing_completion_process.js')!!}
@endsection

@section('content')
	<script>
		var from = "{{$from}}";
	</script>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.manufacturing-completion-process')</h5>
				<div id="operator_info">
				</div>
				<!-- <div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div> -->
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold required">製造指示番号</label>
					<div class="col-md-2">
						@includeIf('popup.searchmanufacturinginstruction', array(
							'id'          => 'TXT_manufacture_no',
							'val'         => $manufacture_no,
							'is_required' => true,
							'is_nm'       => false
						))
					</div>
					<label class="col-md-1 control-label text-right text-bold">製造指示日</label>
					<label class="col-md-1 control-label text-left" id="DSP_production_instruction_date"></label>
					<label class="col-md-1 control-label text-right text-bold">製造状況</label>
					<label class="col-md-1 control-label text-left" id="DSP_production_status"></label>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">社内発注番号</label>
					<label class="col-md-2 control-label text-left" id="DSP_in_order_no"></label>
					<label class="col-md-1 control-label text-right text-bold">発注日</label>
					<label class="col-md-1 control-label text-left" id="DSP_internal_ordering_date"></label>
					<label class="col-md-1 control-label text-right text-bold">希望納期</label>
					<label class="col-md-1 control-label text-left" id="DSP_hope_delivery_date"></label>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">製品</label>
					<label class="col-md-4 control-label text-left" id="DSP_product">
						<span id="DSP_product_cd"></span>
						<span id="DSP_product_nm"></span>
					</label>
					<label class="col-md-1 control-label text-right text-bold">指示数</label>
					<label class="col-md-1 control-label text-left" id="DSP_manufacture_qty"></label>
					<label class="col-md-1 control-label text-right text-bold">完了数</label>
					<label class="col-md-1 control-label text-left" id="DSP_complete_qty"></label>
					<label class="col-md-1 control-label text-right text-bold">残数</label>
					<label class="col-md-1 control-label text-left" id="DSP_remain_amount"></label>
				</div>
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div id="div-table" class="table-responsive sticky-table sticky-headers sticky-ltr-cells" style="max-height: 300px;">
					@includeIf('manufacturingcompletionprocess::ManufacturingCompletionProcess.table')
				</div>
			</div>
		</div>
@endsection
@section('content_hidden')
	<!-- table row -->
	<table class="hide">
		<tbody id="table-row">
			<tr class="">
				<td class="drag-handler text-center DSP_no">
					
				</td>
				<td class="text-right">
					<input type="text" class="form-control text-right required quantity TXT_complete_qty" real_len="6" value="" maxlength="8">
				</td>
				<td class="text-center date">
					<input type="tel" class="datepicker form-control required TXT_complete_date" value="" maxlength="10">
				</td>
				<td class="text-center">
					<input type="text" class="form-control TXT_remarks" value="" maxlength="200">
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