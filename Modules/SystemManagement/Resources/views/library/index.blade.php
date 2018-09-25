@extends('layouts.main')

@section('title')
	@lang('title.library-search')
@endsection

@section('button')
	{{Button::button_left(array('btn-search'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/systemmanagement/css/library_master_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/systemmanagement/js/library_master_search.js')!!}
@endsection

@section('content')
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.library-search')</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold">@lang('label.lib_cd')</label>
					<div class="col-md-2">
						<input type="text" name="TXT_lib_cd" class="form-control TXT_lib_cd" value="" maxlength="30">
					</div>
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold">@lang('label.lib_nm')</label>
					<div class="col-md-2">
						<input type="text" name="TXT_lib_nm" class="form-control TXT_lib_nm" value="" maxlength="50">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold">@lang('label.lib_val_cd_search')</label>
					<div class="col-md-2">
						<input type="text" name="TXT_lib_val_cd" class="form-control TXT_lib_val_cd" value=""  maxlength="10">
					</div>
					<label class="col-md-2 col-md-1-cus control-label text-right text-bold">@lang('label.lib_val_ab')</label>
					<div class="col-md-2">
						<input type="text" name="TXT_lib_val_ab" class="form-control TXT_lib_val_ab" value=""  maxlength="100">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label text-right text-bold">@lang('label.change_perm_div')</label>
					<div class="col-md-2">
						<select name="CMB_change_perm_div" class="form-control possible_div CMB_change_perm_div">
							<option></option>
							@if(isset($possible_div))
								@foreach($possible_div as $k=>$v)
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
		<div id="library-list">
			@includeIf('systemmanagement::library.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
@endsection