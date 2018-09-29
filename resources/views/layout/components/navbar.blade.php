<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('idx') }}">
            <img src="/images/logo_bw.png" width="30" height="30" class="d-inline-block align-top" alt="">
            city_matters
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ Request::url() == route('idx') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('idx') }}">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item {{ Request::url() == route('opendata') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('opendata') }}">Open Data</a>
                </li>
                <li class="nav-item {{ Request::url() == route('diy') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('diy')  }}">DIY Hardware</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        About
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">What is Particular Matter?</a>
                        <a class="dropdown-item" href="#">Team</a>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
            @guest
                <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('user.settings') }}">
                            {{ __('Settings') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
            </ul>
        </div>
    </div>
</nav>