@extends('layouts.popup')

@section('title')
	カートン明細セット
@endsection

@section('stylesheet')	
	{!! public_url('modules/popup/css/invoice_carton.css')!!}
@endsection

@section('page_javascript')
	{!! public_url('modules/popup/js/invoice_carton.js')!!}
@endsection

@section('content')
	<!-- Main content -->
	<div class="row form-horizontal">
		<!-- Search field -->
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h5 class="panel-title text-bold">カートン明細セット</h5>
				<div class="heading-elements">
					<ul class="icons-list">
						<li><a data-action="collapse"></a></li>
					</ul>
				</div>
			</div>
			<div class="panel-body search-condition">
				<div class="form-group clearfix" style="border-top: 1px solid #ddd"></div>
				<div class="form-group">
					<label class="col-md-4 control-label text-right text-bold">No</label>
					<div class="col-md-6">
						<input type="text" class="form-control price text-right carton-from" style="width: 100px; display: inline;">
						
						<span class="">～</span>
		
						<input type="text" class="form-control price text-right carton-to" style="width: 100px; display: inline;">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-3">
					</div>
					<div class="col-md-1">
						<button type="button" id="btn-carton-ok" class="btn btn-primary btn-icon w-60px">OK</button>
					</div>
					<div class="col-md-1">
						<button type="button" id="btn-carton-cancel" class="btn btn-primary btn-icon">キャンセル </button>
					</div>
					<div class="col-md-8">
					</div>
				</div>
			</div>
		</div><!--/.panel-body -->
		</div><!--/.panel -->
	</div><!--/.row -->
	<!-- /main content -->
@endsection

