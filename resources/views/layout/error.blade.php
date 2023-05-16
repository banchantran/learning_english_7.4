<!DOCTYPE html>
<html lang="ja">
<head>
    @include('elements.meta')
</head>
<body>
<div class="wrapper-container">
    <a id="pagetop"></a>
    <div id="header">
        @include('elements.header')
    </div>

    @yield('content')
</div>
@include('elements.footer')
</body>
</html>
