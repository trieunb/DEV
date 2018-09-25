<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title')</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	{!! Html::style('assets/css/icons/icomoon/styles.css')!!}
	{!! Html::style('assets/css/icons/fontawesome/css/font-awesome.min.css')!!}
	{!! Html::style('assets/css/bootstrap.css')!!}
	{!! Html::style('assets/css/core.css')!!}
	{!! Html::style('assets/css/components.css')!!}
	{!! Html::style('assets/css/colors.css')!!}
	{!! Html::style('assets/modules/auth/css/login.css')!!}
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	{!! Html::script('assets/js/core/libraries/jquery.min.js')!!}
	{!! Html::script('assets/js/core/libraries/jquery_ui/jquery-ui.min.js')!!}
	{!! Html::script('assets/js/core/libraries/bootstrap.min.js')!!}
	{!! Html::script('assets/js/plugins/loaders/blockui.min.js')!!}
	{!! Html::script('assets/js/plugins/ui/nicescroll.min.js')!!}
	{!! Html::script('assets/js/plugins/ui/drilldown.js')!!}
	{!! Html::script('assets/js/plugins/ui/moment/moment.min.js')!!}
	{!! public_url('assets/js/common/jquery.utility.js')!!}
{{--	{!! Html::script('assets/js/common/jquery.validate.js')!!}--}}
	{!! Html::script('assets/js/core/app.js')!!}
	{!! Html::script('assets/js/common/jquery.alerts.js')!!}
	{!! Html::script('assets/js/common/jquery.colorbox.js')!!}
	{!! Html::script('assets/js/plugins/pickers/datepicker/bootstrap-datepicker.min.js')!!}
	{!! Html::script('assets/locales/bootstrap-datepicker.ja.min.js')!!}
	{{-- jquery format number --}}
	{!! Html::script('assets/js/plugins/forms/inputs/autoNumeric-min.js')!!}
	
	{!! Html::script('assets/message/msg.js')!!}
	<!-- /core JS files -->
		
</head>

<body class="login-container login-cover">

	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">
			<!-- Main content -->
			@yield('content')
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

</body>

</html>
<!-- form's Jquery -->
@yield('page_javascript')
<!-- /form's  Jquery -->
