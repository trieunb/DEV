@extends('layouts.main')

@section('title')
	@lang('title.component-list-search')
@endsection

@section('button')
	{{Button::button_left(array('btn-search', 'btn-add-new', 'btn-export', 'btn-upload'))}}
@endsection

@section('stylesheet')
	{!! public_url('modules/master/css/component_list_search.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/master/js/component_list_search.js')!!}
@endsection

@section('content')
	<div class="row form-horizontal">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">@lang('title.component-list-search')</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">親品目</label>
					<div class="col-md-1">
						@includeIf('popup.searchcomponentproduct', array(
																	'class_cd'	=>	'TXT_parent_item_cd',
																))
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-md-1-cus control-label text-right text-bold">子品目</label>
					<div class="col-md-1">
						@includeIf('popup.searchcomponentproduct', array(
																	'class_cd'	=>	'TXT_child_item_cd'
																))
					</div>
				</div>
			</div>
		</div>
		<div id="component-list">
			@includeIf('master::ComponentListSearch.list', ['paginate' => $paginate, 'fillter' => $fillter])
		</div>
		<input type="file" name="upload-excel" class="hidden" id="upload-excel">
	</div>
@endsection