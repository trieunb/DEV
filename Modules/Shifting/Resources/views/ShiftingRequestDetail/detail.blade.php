@extends('layouts.main')

@section('title')
	移動依頼入力
@endsection

@section('button')
	{{Button::button_left(array('btn-back', 'btn-save','btn-delete', 'btn-approve', 'btn-issue'),$mode)}}
@endsection

@section('stylesheet')
	{!! public_url('modules/shifting/css/shifting_request_detail.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/shifting/js/shifting_request_detail.js')!!}
@endsection


@section('content')
	<script>
		var mode = "{{$mode}}";
		var from = "{{$from}}";
	</script>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">移動依頼入力</h5>
				<div id="operator_info">
					{!! infoMemberCreUp('', '', '', '') !!}
				</div>
			</div>
			<div class="panel-body">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold DSP_move_no {{ $mode != 'I' ? 'required' : '' }}">移動依頼票番号</label>
					<div class="col-md-2">
						@includeIf('popup.searchshifting',array(
							'id'          => 'TXT_move_no',
							'class_tab'   => 'TXT_move_no',
							'is_required' => true,
							'is_nm'       => false,
							'val'         => isset($move_no) ? $move_no : ''))
					</div>
					<label class="col-md-4 col-md-3-cus control-label text-right" style="float: right;">
						<span class="text-bold hide" id="STT">ステータス</span>
						<span class="DSP_status" id="DSP_status"></span>
						<span class="DSP_status_tm" id="DSP_status_tm"></span>
					</label>
				</div>

				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">移動希望日</label>
					<div class="col-md-4 date-from-to">
						<input value="" type="tel" name="TXT_move_preferred_date" class="datepicker form-control TXT_move_preferred_date">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">製造指示書番号</label>
					<div class="col-md-2">
						@includeIf('popup.searchmanufacturinginstruction', array(
												'class_cd' 		=> 'TXT_manufacture_no', 
												'is_required' 	=> false,
												'disabled_ime'	=> 'disabled-ime',
												'is_nm'    		=> false))
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">出庫倉庫</label>
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
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">入庫倉庫</label>
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
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">備考</label>
					<div class="col-md-2">
						<input type="text" id="" class="form-control TXT_remarks" value="" maxlength="200">
					</div>
				</div>

				<div class="table-responsive sticky-table sticky-headers sticky-ltr-cells" id="table-refer" style="max-height: 300px;">
					@includeIf('shifting::ShiftingRequestDetail.table')
				</div>
			</div>
	</div>

@endsection

@section('content_hidden')
	<!-- table row -->
	<table class="hide">
		<tbody id="table-row">
			<tr class="tr-table">
                <input type="hidden" class="DSP_stock_current_qty">
                <input type="hidden" class="DSP_stock_available_qty_hidden">
                <input type="hidden" class="TXT_move_qty_hidden">

	        	<!-- move_detail_no -->
	            <td class="drag-handler text-center DSP_no"></td>
				
				<!-- item_cd -->
	            <td class="text-center" style="width: 170px;">
	                @includeIf('popup.searchcomponentproduct', array(
	                											'class_cd' 		=> 'TXT_item_cd',
	                											'is_required' 	=> true,
	                											'disabled_ime'	=> 'disabled-ime',
	                											'is_nm' 		=> false,))
	            </td>
				
				<!-- item_nm -->
	            <td class="text-left">
	                <div class="tooltip-overflow max-width20 DSP_item_nm" 
	                	 style="max-width: 200px;" 
	                	 data-toggle="tooltip" 
	                	 data-placement="top" 
	                	 title=""></div>
	            </td>
				
				<!-- specification -->
	            <td class="text-left">
	                <div class="tooltip-overflow max-width20 DSP_specification" 
	                	 style="max-width: 200px;" 
	                	 data-toggle="tooltip" 
	                	 data-placement="top" 
	                	 title=""></div>
	            </td>
				
				<!-- move_qty -->
	            <td class="text-center">
	                @includeIf('popup.searchshiftingserial', array(
                                                        'is_nm'         => false,
                                                        'is_required'   => true,
                                                        'class_cd'      => 'TXT_move_qty'))
                        <input type="hidden" class="serial_list" value="">
	            </td>
				
				<!-- unit_qty_div_nm -->
	            <td class="text-center DSP_unit">
	                
	            </td>
				
				<!-- stock_available_qty -->
	            <td class="text-right text-right DSP_stock_available_qty">
	            </td>
				
				<!-- detail_remarks -->
	            <td class="text-right">
	            	<input type="text" class="form-control TXT_detail_remarks" maxlength="200">
	            </td>
				
				<!-- BTN_delete_line -->
	            <td class="w-40px text-center">
	                <button type="button" class="form-control remove-row BTN_delete_line">
	                    <span class="icon-cross2 text-danger"></span>
	                </button>
	            </td>
	        </tr>
		</tbody>
	</table>
@endsection