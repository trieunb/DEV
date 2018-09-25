@extends('layouts.main')

@section('title')
	@lang('title.selling-unit-price-by-client-search')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-add-new', 'btn-export'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/master/css/selling_unit_price_by_client_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/master/js/selling_unit_price_by_client_search.js')!!}
@endsection

@section('content')

	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.selling-unit-price-by-client-search')</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">製品名</label>
					<div class="col-md-5">
						<input type="text" name="TXT_product_nm" id="TXT_product_nm" class="form-control TXT_product_nm">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">取引先コード</label>
					<div class="col-md-1">
						@includeIf('popup.searchsuppliers', array('class_cd'=>'TXT_client_cd'))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-2-cus control-label text-right text-bold">国コード</label>
					<div class="col-md-1">
						@include('popup.searchcountry', array('class_cd'=>'TXT_country_cd'))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">種別</label>
					<div class="col-md-2">
						<select class="form-control sales_unit_price_kind_div TXT_type" name="TXT_type" id="TXT_type">
							<option></option>
							@if(isset($sales_unit_price_kind_div))
								@foreach($sales_unit_price_kind_div as $k=>$v)
									<option value="{{$v['lib_val_cd']}}" 
											data-ctl1="{{$v['lib_val_ctl1']}}" data-ctl2="{{$v['lib_val_ctl2']}}"
											data-ctl5="{{$v['lib_val_ctl5']}}" data-ctl6="{{$v['lib_val_ctl6']}}"
											data-nm-j="{{$v['lib_val_nm_j']}}" data-nm-e="{{$v['lib_val_nm_e']}}"
											data-ini_target_div="{{$v['ini_target_div']}}">
										{{$v['lib_val_nm_j']}} 
									</option>
								@endforeach
							@endif
						</select>
					</div>
				</div>
			</div>
		</div>
		<div id="sales_price_list">
			@includeIf('master::SellingUnitPriceByClientSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
@endsection