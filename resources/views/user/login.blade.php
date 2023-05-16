@extends('layout.login')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-4 offset-4">
                <form class="form-login" method="post" action="{{url(route('user.postLogin'))}}">
                    @csrf

                    <h1 class="title mb-20 text-center">Login form</h1>
                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            <p>{{session('success')}}</p>
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            <p>{{session('error')}}</p>
                        </div>
                    @endif
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" aria-describedby="emailHelp" placeholder="Username" value="{{old('username')}}">
                        @error('username')
                        <p class="text-danger f14">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" value="{{old('password')}}">
                        @error('password')
                        <p class="text-danger f14">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-check mt-2">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1" name="remember">
                        <label class="form-check-label f14" for="exampleCheck1">Remember me</label>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-red w-100 mt-4">Login</button>
                    </div>
                    <div class="text-center f14 mt-3">
                        Not a member? <a href="{{url(route('user.getRegister'))}}" class="text-success">Signup now</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
