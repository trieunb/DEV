@extends('layouts.main')

@section('title')
	@lang('title.suppliers-master-maintenance')
@endsection

@section('button')
	{{Button::button_left(array('btn-back', 'btn-save','btn-delete','btn-copy'),$mode)}}
@endsection

@section('stylesheet')
	{!! public_url('modules/master/css/suppliers_master_maintenance.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/master/js/suppliers_master_maintenance.js')!!}
@endsection

@section('content')
	<script>
		var mode   = "{{$mode}}";
		var from   = "{{$from}}";
		var is_new = "{{$is_new}}";
	</script>
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.suppliers-master-maintenance')</h5>
				<div id="operator_info">
					{!! infoMemberCreUp('', '', '', '') !!}
				</div>
			</div>
			<div class="panel-body search-field">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>

				<!-- 取引先 -->
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold supplier-label {{ $mode != 'I' ? 'required' : '' }}">取引先</label>
					<div class="col-md-1">
						@includeIf('popup.searchsuppliers',
								    array('id'			=> "TXT_client_cd",
								    	  'val' 		=> $suppliersMasterMaintenance,
								          'is_required' => true,
								          'disable_ime' => 'disable-ime',
								          'is_nm'		=> false))
					</div>
				</div>
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>

				<!-- 取引先名称 -->
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">取引先名称</label>
					<div class="col-md-6">
						<input type="text" id="TXT_client_nm" class="form-control ime-active required" maxlength="120">
					</div>
				</div>

				<!-- 取引先種別 -->
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">取引先種別</label>
					<div class="col-md-3">
						<label class="checkbox-inline">
							<input type="checkbox" class="required-checkbox" id="CHK_customer">得意先
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" class="required-checkbox" id="CHK_suppliers">仕入先
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" class="required-checkbox" id="CHK_outsourcer">外注先
						</label>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-flat">
			<div class="panel-body">
				<div class="tabbable" id="cmTabs">
					<ul class="nav nav-xs nav-tabs nav-tabs-component">
						<li class="active">
							<a href="#tab_00" data-toggle="tab" aria-expanded="true">基本情報</a>
						</li>
						<li class="">
							<a href="#tab_01" data-toggle="tab" aria-expanded="false">販売用情報</a>
						</li>
						<li class="">
							<a href="#tab_02" data-toggle="tab" aria-expanded="false">入金情報</a>
						</li>
						<li class="">
							<a href="#tab_03" data-toggle="tab" aria-expanded="false">出金情報</a>
						</li>
						<!-- <li class=""><a href="#tab_04" data-toggle="tab" aria-expanded="false">取引先口座</a></li> -->
						<li class="">
							<a href="#tab_05" data-toggle="tab" aria-expanded="false">当方口座</a>
						</li>
						<li class="">
							<a href="#tab_06" data-toggle="tab" aria-expanded="false">請求先住所</a>
						</li>
						<li class="">
							<a href="#tab_07" data-toggle="tab" aria-expanded="false">納品先住所</a>
						</li>
						<li class="">
							<a href="#tab_08" data-toggle="tab" aria-expanded="false">荷受人住所</a>
						</li>
					</ul>
					<div class="tab-content">
						@includeIf('master::SuppliersMasterMaintenance.tabtable.tab_00')
						@includeIf('master::SuppliersMasterMaintenance.tabtable.tab_01')
						@includeIf('master::SuppliersMasterMaintenance.tabtable.tab_02')
						@includeIf('master::SuppliersMasterMaintenance.tabtable.tab_03')
						<!-- @includeIf('master::SuppliersMasterMaintenance.tabtable.tab_04') -->
						@includeIf('master::SuppliersMasterMaintenance.tabtable.tab_05')
						@includeIf('master::SuppliersMasterMaintenance.tabtable.tab_06')
						@includeIf('master::SuppliersMasterMaintenance.tabtable.tab_07')
						@includeIf('master::SuppliersMasterMaintenance.tabtable.tab_08')
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection