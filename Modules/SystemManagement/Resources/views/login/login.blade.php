@extends('layouts.login')

@section('title')
	ログイン
@endsection

@section('page_javascript')
	{!! public_url('modules/test/js/login.js')!!}
@endsection

@section('content')

	<div class="content-wrapper">

		<!-- Form with validation -->
		<form autocomplete="off">
			{{-- {!! csrf_field() !!} --}}
			<div class="panel panel-body login-form">
				<div class="text-center">
					<div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
					<h5 class="content-group"><small>Login to your account credentials</small></h5>
				</div>

				<div class="form-group has-feedback has-feedback-left">
					<input type="text" class="form-control" name="name" id="email">
					<div class="form-control-feedback">
						<i class="icon-user text-muted" style="padding-bottom: 11px"></i>
					</div>
				</div>

				<div class="form-group has-feedback has-feedback-left">
					<input type="password" class="form-control" name="password" id="password"> 
					<div class="form-control-feedback">
						<i class="icon-lock2 text-muted" style="padding-bottom: 11px"></i>
					</div>
				</div>

				{{--<div class="form-group login-options">--}}
					{{--<div class="row">--}}
						{{--<div class="col-sm-6">--}}
							{{--<label class="checkbox-inline">--}}
								{{--<input type="checkbox" id="remember" name="remember"> Remember--}}
							{{--</label>--}}
						{{--</div>--}}

						{{--<div class="col-sm-6 text-right">--}}
							{{--<a href="{{ url('/password/reset') }}">Forgot Password?</a>--}}
						{{--</div>--}}
					{{--</div>--}}
				{{--</div>--}}

				<div class="form-group">
					<button type="button" id="btn_login" class="btn bg-blue btn-block">ログイン <i class="icon-arrow-right14 position-right"></i></button>
				</div>

			</div>
		</form>
		<!-- /form with validation -->

	</div>

@endsection

