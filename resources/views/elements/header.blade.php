<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{url(route('home'))}}"><img src="{{url('img/speak_english.png')}}" alt="logo" width="100px" class="logo-top"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item {{$activeNav === 'dashboard' ? 'active' : ''}} mr-10">
                    <a class="nav-link" href="{{url(route('dashboard.index'))}}">Dashboard</a>
                </li>
                <li class="nav-item {{$activeNav === 'category' ? 'active' : ''}} mr-10">
                    <a class="nav-link" href="{{url(route('home'))}}">Category</a>
                </li>
                @auth
                    <li class="nav-item {{$activeNav === 'bookmark' ? 'active' : ''}} mr-10">
                        <a class="nav-link" href="{{url(route('bookmark.learn'))}}">Bookmark</a>
                    </li>
                    <li class="nav-item {{$activeNav === 'practice' ? 'active' : ''}} mr-10 dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                            Practice
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{url(route('practice.learn', ['language' => 'english']))}}">English</a>
                            <a class="dropdown-item" href="{{url(route('practice.learn', ['language' => 'japanese']))}}">Japanese</a>
                            <a class="dropdown-item" href="{{url(route('practice.learn', ['language' => 'chinese']))}}">Chinese</a>
                        </div>
                    </li>
                @endauth
            </ul>
            @if (!$hideSearchBar)
                <form class="form-inline my-2 my-lg-0 group-search" method="get" action="{{url(route('search.result'))}}">
                    <input class="form-control mr-sm-2" name="keyword" type="search" placeholder="Keyword..." aria-label="Search">
                    <button class="btn btn-outline-success btn-search my-2 my-sm-0 d-flex align-items-center" type="submit">
                        <img src="{{url('img/search-icon.svg')}}" alt="search">
                        Search
                    </button>
                </form>
            @endif

            @auth
                <ul class="nav-bar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link text-pink dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{\Illuminate\Support\Facades\Auth::user()->username}}
                        </a>
                        <div class="dropdown-menu dropdown-menu-custom" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{url(route('user.logout'))}}">Logout</a>
                        </div>
                    </li>
                </ul>
            @else
                <a href="{{url(route('user.getLogin'))}}" class="link-dark ml20 text-decoration-underline">Login</a>
                <span class="ml10">or</span>
                <a href="{{url(route('user.getRegister'))}}" class="link-dark ml10 text-decoration-underline">Register</a>
            @endauth
        </div>
    </div>
</nav>
