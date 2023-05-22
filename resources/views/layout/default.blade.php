<!DOCTYPE html>
<html lang="ja">
<head>
    @include('elements.meta')
</head>
<body>
<div class="wrapper-container">

    <div id="header">
        @include('elements.header')
    </div>

    @if ($activeNav !== 'dashboard')
        <div class="container-fluid">
            <div id="breadcrumbWrap" class="alert-secondary alert">
                @yield('breadcrumb')
            </div>
        </div>
    @endif

    @include('elements.flash-messages')

    <div id="Contentwrap">
        @yield('content')
    </div>

    @include('elements.dialog')
</div>

@include('elements.footer')

@yield('script')

</body>
</html>
