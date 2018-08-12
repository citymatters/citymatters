<nav class="adminbar navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">
            Admin Menu:
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ Request::url() == route('home') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('home') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ __('Users') }}</a>
                </li>
                <li class="nav-item {{ Request::url() == route('admin.sensors') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.sensors') }}">{{ __('Sensors') }}</a>
                </li>
                <li class="nav-item {{ Request::url() == route('admin.organizations') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.organizations') }}">{{ __('Organizations') }}</a>
                </li>
                <li class="nav-item {{ Request::url() == route('admin.invites') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.invites') }}">{{ __('Invites') }}</a>
                </li>
            </ul>
        </div>
    </div>
</nav>