
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
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
	{!! public_url('css/jquery-ui.css')!!}
	{!! public_url('css/tables/jquery.stickytable.css')!!}
	{!! public_url('css/common.css')!!}
	{!! Html::style('css/errors.css')!!}

</head>

<body style="">
	<div class="container">
		@yield('content')
	</div>
	
</body>
</html>