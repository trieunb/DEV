<table class="table table-hover table-bordered table-xxs table-text table-shifting-detail table-list" id="table-shifting-detail">
    <thead>
        <tr class="col-table-header sticky-row">
            <th class="text-center" style="width: 40px">NO</th>
            <th class="text-center">品目コード</th>
            <th class="text-center">品目名</th>
            <th class="text-center">規格</th>
            <th class="text-center" style="width: 100px">数量</th>
            <th class="text-center" style="width: 80px">単位</th>
            <th class="text-center" style="width: 100px">有効在庫数</th>
            <th class="text-center" style="width: 150px">備考</th>
            <th class="text-center" style="width: 40px">
                <button type="button" class="btn btn-primary btn-icon btn-add-row" id="btn-add-row" style="float: right;">
                    <i class="icon-plus3"></i>
                </button>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(isset($move_info_d))
			@foreach($move_info_d as $row)
		        <tr class="tr-table">
                    <input type="hidden" class="DSP_stock_current_qty" value="{{ $row['stock_current_qty'] or 0}}">
                    <input type="hidden" class="DSP_stock_available_qty_hidden" value="{{ $row['stock_available_qty'] or 0}}">
                    <input type="hidden" class="TXT_move_qty_hidden" value="{{ $row['move_qty'] or 0}}">
		        	<!-- move_detail_no -->
		            <td class="drag-handler text-center DSP_no">{{ $row['move_detail_no'] or ''}}</td>
					
					<!-- item_cd -->
		            <td class="text-center" style="width: 170px;">
		                @includeIf('popup.searchcomponentproduct', array(
		                											'class_cd' 		=> 'TXT_item_cd',
		                											'is_required' 	=> true,
		                											'disabled_ime'	=> 'disabled-ime',
		                											'val'			=> $row['item_cd'],
		                											'is_nm' 		=> false,))
		            </td>
					
					<!-- item_nm -->
		            <td class="text-left">
		                <div class="tooltip-overflow max-width20 DSP_item_nm" 
		                	 style="max-width: 200px;" 
		                	 data-toggle="tooltip" 
		                	 data-placement="top" 
		                	 title="{{ $row['item_nm'] or ''}}">{{ $row['item_nm'] or ''}}</div>
		            </td>
					
					<!-- specification -->
		            <td class="text-left">
		                <div class="tooltip-overflow max-width20 DSP_specification" 
		                	 style="max-width: 200px;" 
		                	 data-toggle="tooltip" 
		                	 data-placement="top" 
		                	 title="{{ $row['specification'] or ''}}">{{ $row['specification'] or ''}}</div>
		            </td>
					
					<!-- move_qty -->
		            <td class="text-center">
                        @includeIf('popup.searchshiftingserial', array(
                                                        'is_nm'         => false,
                                                        'is_required'   => true,
                                                        'class_cd'      => 'TXT_move_qty',
                                                        'val'           => $row['move_qty'] ,
                                                        'is_readonly'   => $row['serial_management_div'] === "1" ? true : false,
                                                        'is_disabled'   => $row['serial_management_div'] === "1" ? true : false))
                        <input type="hidden" class="serial_list" value="{{ $row['serial_list'] }}">
		            </td>
					
					<!-- unit_qty_div_nm -->
		            <td class="text-center DSP_unit">
		                {{ $row['unit_qty_div_nm'] or ''}}
		            </td>
					
					<!-- stock_available_qty -->
		            <td class="text-right text-right DSP_stock_available_qty">
		                {{ $row['stock_available_qty'] or ''}}
		            </td>
					
					<!-- detail_remarks -->
		            <td class="text-left">
		                <input type="text" class="form-control TXT_detail_remarks" value="{{ $row['detail_remarks'] or ''}}" maxlength="200">
		            </td>
					
					<!-- BTN_delete_line -->
		            <td class="w-40px text-center">
		                <button type="button" class="form-control remove-row BTN_delete_line">
		                    <span class="icon-cross2 text-danger"></span>
		                </button>
		            </td>
		        </tr>
        	@endforeach					
		@endif
    </tbody>
</table>