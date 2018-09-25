@extends('layouts.popup')

@section('title')
	@lang('title.suppliers-master-search')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-add-new','btn-export', 'btn-upload'))}}
@endsection

@section('stylesheet')
	{!! public_url('css/tables/jquery.stickytable.css')!!}
	{!! public_url('modules/popup/css/suppliers_master_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('js/plugins/tables/fixheader/jquery.stickytable.js')!!}
	{!! public_url('modules/popup/js/suppliers_master_search.js')!!}
@endsection

@section('content')
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.suppliers-master-search')</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">取引先コード</label>
					<div class="col-md-1">
						<input type="text" id="TXT_client_cd" name="TXT_client_cd" class="form-control TXT_client_cd" maxlength="6">
					</div>
					<!-- <div class="col-md-2">
						@include('popup.searchsuppliers', 
								  array('id'	=> "TXT_client_cd"))
					</div> -->
						<!-- <span class="text-right-input">Google Inc</span> -->
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">取引先名称</label>
					<div class="col-md-3">
						<input type="text" id="TXT_client_nm" class="form-control" tabindex="3" maxlength="120">
					</div>
					<label class="col-md-2 col-md-2-cus control-label text-left">(キーワード検索)</label>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">親取引先コード</label>
					<div class="col-md-2">
						@include('popup.searchsuppliers', 
								  array('id' => 'TXT_parent_client_cd'))
						<!-- <span class="text-right-input">Alphabet Inc</span> -->
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">取引先コード</label>
					<div class="col-md-6">
						<input type="text" id="TXT_client_cd_from" class="form-control" style="width: 160px; display: inline;" tabindex="5" maxlength="6">
						
						<span class="">～</span>
		
						<input type="text" id="TXT_client_cd_to" class="form-control" style="width: 160px; display: inline;" tabindex="6" maxlength="6">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">国コード</label>
					<div class="col-md-2">
						@include('popup.searchcountry',
								  array('id' => 'TXT_country_cd'))
						<!-- <span class="text-right-input">United states</span> -->
					</div>
					<div class="col-md-2 text-right pull-right">
						<button type="button" id="btn-search-popup" class="btn btn-primary btn-icon w-60px">
							<i class="icon-search4"></i>
						</button>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">取引先種別</label>
					<div class="col-md-3">
						<label class="checkbox-inline">
							<input type="checkbox" id="CHK_customer">得意先
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" id="CHK_suppliers">仕入先
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" id="CHK_outsourcer">外注先
						</label>
					</div>
				</div>
			</div>
		</div>
		<!-- /search field -->
		
		<!-- List client -->
		<div id="client_list">
			@includeIf('popup::SuppliersMasterSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
	<!-- </div> -->
@endsection