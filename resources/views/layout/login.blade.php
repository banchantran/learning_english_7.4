<!DOCTYPE html>
<html lang="ja">
<head>
    @include('elements.meta')
</head>
<body>
<div class="wrapper-container">
    @yield('content')
</div>
<div id="loading">
    <img src="{{url('img/loading.gif')}}" alt="loading">
</div>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="{{url('js/common.js?v=' . time())}}"></script>
@yield('script')
</body>
</html>
