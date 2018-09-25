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
		{!! public_url('css/icons/icomoon/styles.css')!!}
		{!! public_url('css/icons/fontawesome/css/font-awesome.min.css')!!}
		{!! public_url('css/bootstrap.css')!!}
		{!! public_url('css/core.css')!!}
		{!! public_url('css/components.css')!!}
		{!! public_url('css/colors.css')!!}
		{!! public_url('css/jquery.alerts.css')!!}
		{!! public_url('css/colorbox.css')!!}
		{{-- {!! public_url('assets/css/bootstrap-datepicker3.css')!!} --}}
		{!! public_url('css/jquery-ui.css')!!}
		{!! public_url('css/tables/jquery.stickytable.css')!!}
		{!! public_url('css/common.css')!!}
		<!-- /global stylesheets -->
		
		<!-- Component CSS files -->		
		@yield('stylesheet')
		<!-- /Component CSS files -->

		<!-- Core JS files -->
		{!! public_url('js/core/libraries/jquery.min.js')!!}
		{!! public_url('js/core/libraries/jquery_ui/jquery-ui.min.js')!!}
		{!! public_url('js/core/libraries/bootstrap.min.js')!!}
		{!! public_url('js/plugins/loaders/blockui.min.js')!!}
		{!! public_url('js/plugins/ui/nicescroll.min.js')!!}
		{!! public_url('js/plugins/ui/drilldown.js')!!}
		{!! public_url('js/plugins/ui/moment/moment.min.js')!!}
		{!! public_url('js/common/jquery.utility.js')!!}
		{{-- public_url('assets/js/common/jquery.validate.js') --}}
		{!! public_url('js/core/app.js')!!}
		{!! public_url('js/common/jquery.alerts.js')!!}
		{!! public_url('js/common/jquery.colorbox.js')!!}
		{!! public_url('js/plugins/tables/fixheader/jquery.stickytable.js')!!}
		{!! public_url('js/plugins/tables/sorttable/jquery.tablesorter.min.js')!!}
		{{-- {!! public_url('assets/js/plugins/pickers/datepicker/bootstrap-datepicker.js')!!} --}}
		{{-- {!! public_url('assets/locales/bootstrap-datepicker.ja.min.js')!!} --}}
		{{-- jquery format number --}}
		{!! public_url('js/plugins/forms/inputs/autoNumeric-min.js')!!}

		{{-- common jquery for Souei --}}
		{!! public_url('message/msg.js')!!}
		{!! public_url('js/common/common.js')!!}
		{!! public_url('js/common/config.js')!!}
				<!-- /core JS files -->

		{!! public_url('js/common/jquery-ui.js')!!}
		{!! public_url('js/common/jquery.ympicker.js')!!}
		{!! public_url('js/common/Sortable.js')!!}
		
		<!-- Component JS files -->		
		@yield('components')
		<!-- /Component JS files -->
	</head>
	
	<body class="navbar-bottom">
		
		<div class="navbar popup-form-header bg-popup affix" id="popup-navbar-second">
			<div class="panel-heading popup-header">
				<div class="heading-elements popup-header-heading-elements">
					<button type="button" id="btn-close-popup" class="close"><i class="fa fa-times"></i></button>
            	</div>	
			</div>
		</div>
			
		<!-- Page container -->
		<div class="page-container no-padding-bottom">
			
			<!-- Main content -->
			<div class="page-content">			

				<!-- Content area -->
				<div class="content-wrapper popup-form">	

					@yield('content')

				</div>
			</div>
			<!-- Main content -->
			
		</div>
		<!-- /page container -->

		<!-- form's Jquery files -->
		@yield('page_javascript')
		<!-- /form's  Jquery files -->

		<!-- form's hidden field (table add row, hidden textbox) -->
		@yield('content_hidden')
		<!-- /form's  hidden field -->
	</body>
	
</html>


