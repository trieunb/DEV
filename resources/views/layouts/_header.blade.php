<div class="navbar navbar-default navbar-fixed-top header-highlight">
	<div class="navbar-header">
		<a class="navbar-brand">{{Html::image('images/img-logo.png') }}</a>

		<ul class="nav navbar-nav visible-xs-block">
			<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
			<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
		</ul>
	</div>

	<div class="navbar-collapse collapse" id="navbar-mobile">
		<ul class="nav navbar-nav">
			<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>			
		</ul>
        <div class="heading-btn-group">
			@yield('button')
		</div>
		<ul class="nav navbar-nav navbar-right">
			<li class="dropdown dropdown-user">
				<a class="dropdown-toggle" data-toggle="dropdown">
					{{Html::image('images/logo.png') }}
					<span>{{-- {{ Auth::check() ? Auth::user()->name : null}} --}}</span>
					<i class="caret"></i>
				</a>

				<ul class="dropdown-menu dropdown-menu-right">
					<!-- <li><a href="#"><i class="icon-user-plus"></i> My profile</a></li>
					<li><a href="#"><i class="icon-coins"></i> My balance</a></li>
					<li><a href="#"><span class="badge bg-primary pull-right">58</span> <i class="icon-comment-discussion"></i> Messages</a></li>
					<li class="divider"></li> -->
					<li><a id="btn-change-password"><i class="icon-user-lock"></i>パスワード変更</a></li>
					<li>
						{{--<a href="{{ url('/logout') }}" id="logout-link"--}}
						   {{--onclick="event.preventDefault();--}}
								 {{--document.getElementById('logout-form').submit();">--}}
							{{--<i class="icon-switch2"></i>Logout--}}
						{{--</a>--}}
						{{--<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">--}}
							{{--{{ csrf_field() }}--}}
						{{--</form>--}}
                        <a id="" href="/login/logout">
                            <i class="icon-switch2"></i>ログアウト
                        </a>
					</li>
				</ul>
			</li>
		</ul>
	</div>
</div>