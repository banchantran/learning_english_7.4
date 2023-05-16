@if (session()->has('success'))
    <div class="container-fluid">
        <div class="alert alert-success">
            <p>{{session('success')}}</p>
        </div>
    </div>
@endif

@if (session()->has('error'))
    <div class="container-fluid">
        <div class="alert alert-danger">
            <p>{{session('error')}}</p>
        </div>
    </div>
@endif

