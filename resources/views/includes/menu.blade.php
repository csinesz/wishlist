<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Wishlist</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                @if(Auth::check() && auth()->user()->hasRole('admin'))
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('users.index')}}">
                            @lang('gui.users')
                        </a>
                    </li>
                @elseif(Auth::check() && auth()->user()->hasRole('user'))
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('wishlists.index')}}">
                            @lang('gui.wishlists')
                        </a>
                    </li>
                @endif
            </ul>
            <div class="d-flex">
                <ul class="navbar-nav">
                    @if(Auth::check())
                        <li class="nav-item">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <span class="menu-title">
                                    @lang('gui.logout')
                                </span>

                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('login')}}">@lang('gui.login')</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
