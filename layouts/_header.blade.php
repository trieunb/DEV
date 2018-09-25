<div class="navbar navbar-default navbar-fixed-top header-highlight">
	<div class="navbar-header">
		<a class="navbar-brand" href="/">{{Html::image('assets/images/logo_light.png') }}</a>

		<ul class="nav navbar-nav visible-xs-block">
			<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
			<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
		</ul>
	</div>

	<div class="navbar-collapse collapse" id="navbar-mobile" style="height: 55px!important;">
		<ul class="nav navbar-nav">
			<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>			
		</ul>

		@yield('button')

		<ul class="nav navbar-nav navbar-right">
			<li class="dropdown dropdown-user">
				<a class="dropdown-toggle" data-toggle="dropdown">
					{{Html::image('assets/images/placeholder.jpg') }}
					<span>{{ Auth::check() ? Auth::user()->name : null}}</span>
					<i class="caret"></i>
				</a>

				<ul class="dropdown-menu dropdown-menu-right">
					<li><a href="#"><i class="icon-user-plus"></i> My profile</a></li>
					<li><a href="#"><i class="icon-coins"></i> My balance</a></li>
					<li><a href="#"><span class="badge bg-teal-400 pull-right">58</span> <i class="icon-comment-discussion"></i> Messages</a></li>
					<li class="divider"></li>
					<li><a href="/master/changepassword"><i class="icon-user-lock"></i> Change password</a></li>
					<li>
						{{--<a href="{{ url('/logout') }}" id="logout-link"--}}
						   {{--onclick="event.preventDefault();--}}
								 {{--document.getElementById('logout-form').submit();">--}}
							{{--<i class="icon-switch2"></i>Logout--}}
						{{--</a>--}}
						{{--<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">--}}
							{{--{{ csrf_field() }}--}}
						{{--</form>--}}
                        <a id="logout-link">
                            <i class="icon-switch2"></i>Logout
                        </a>
					</li>
				</ul>
			</li>
		</ul>
	</div>
</div>