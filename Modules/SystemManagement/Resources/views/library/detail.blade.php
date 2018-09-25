@extends('layouts.main')

@section('title')
	@lang('title.library-detail')
@endsection

@section('button')
	{{Button::button_left(array('btn-back', 'btn-save'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/systemmanagement/css/library_master_detail.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/systemmanagement/js/library_master_detail.js')!!}
@endsection

@section('content')
	<script>
		var mode = "{{ $mode }}";
		var from = "{{ $from }}";
	</script>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.library-detail')</h5>
				<div class="operator_info">
					@includeIf('layouts._operator_info')
				</div>
			</div>
			<div class="panel-body">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">@lang('label.lib_cd')</label>
					<label class="col-md-3 col-md-3-cus control-label text-left DSP_lib_cd">{{ $lib_cd or ''}}</label>
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">@lang('label.lib_nm')</label>
					<label class="col-md-2 col-md-2-cus control-label text-left DSP_lib_nm"></label>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">@lang('label.change_perm_div')</label>
					<label class="col-md-3 col-md-3-cus control-label text-left DSP_change_perm_div"></label>
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">@lang('label.lib_val_cd_digit')</label>
					<label class="col-md-2 col-md-2-cus control-label text-left DSP_lib_val_cd_digit"></label>
				</div>
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="table-responsive sticky-table sticky-headers sticky-ltr-cells" style="max-height: 300px;" id="library-input">
					@includeIf('systemmanagement::library.table_detail')
				</div>
			</div>
	</div>
@endsection
@section('content_hidden')
	<!-- table row -->
	<table class="hide">
		<tbody id="table-row">
			<tr class="">
				<td class="drag-handler text-left">
					<input type="text" class="form-control tab-top TXT_lib_val_cd" value="" maxlength="10">
				</td>
				<td class="text-left">
					<input type="text" class="form-control tab-top TXT_lib_val_nm_j" value="" maxlength="100">
					<div class="boder-line"></div>
					<input type="text" class="form-control tab-bottom TXT_lib_val_nm_e" value="" maxlength="50">
				</td>
				<td class="text-left">
					<input type="text" class="form-control tab-top TXT_lib_val_ab_j" value="" maxlength="100">
					<div class="boder-line"></div>
					<input type="text" class="form-control tab-bottom TXT_lib_val_ab_e" value="" maxlength="50">
				</td>
				<td class="text-left">
					<input type="text" class="form-control tab-top1 TXT_lib_val_ctl1" value="" maxlength="50">
					<div class="boder-line"></div>
					<input type="text" class="form-control tab-bottom1 TXT_lib_val_ctl6" value="" maxlength="50">
				</td>
				<td class="text-left">
					<input type="text" class="form-control tab-top1 TXT_lib_val_ctl2" value="" maxlength="50">
					<div class="boder-line"></div>
					<input type="text" class="form-control tab-bottom1 TXT_lib_val_ctl7" value="" maxlength="50">
				</td>
				<td class="text-left">
					<input type="text" class="form-control tab-top1 TXT_lib_val_ctl3" value="" maxlength="50">
					<div class="boder-line"></div>
					<input type="text" class="form-control tab-bottom1 TXT_lib_val_ctl8" value="" maxlength="50">
				</td>
				<td class="text-left">
					<input type="text" class="form-control tab-top1 TXT_lib_val_ctl4" value="" maxlength="50">
					<div class="boder-line"></div>
					<input type="text" class="form-control tab-bottom1 TXT_lib_val_ctl9" value="" maxlength="50">
				</td>
				<td class="text-left">
					<input type="text" class="form-control tab-top1 TXT_lib_val_ctl5" value="" maxlength="50">
					<div class="boder-line"></div>
					<input type="text" class="form-control tab-bottom1 TXT_lib_val_ctl10" value="" maxlength="50">
				</td>
				<td class="text-center">
					<input class="styled  tab-bottom1 RDI_ini_target_div" name="ini_target_div" type="radio">
				</td>
				<td class="text-left">
					<button type="button" class="form-control remove-row tab-bottom1">
						<span class="icon-cross2 text-danger"></span>
					</button>
				</td>
			</tr>
		</tbody>
	</table>
	<!--/table row -->
@endsection