@extends('layout.login')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-4 offset-4">
                <form class="form-login" method="post" action="{{url(route('user.getRegister'))}}">
                    @csrf
                    <h1 class="title mb-20 text-center">Signup form</h1>
                    <div class="form-group">
                        <input type="text" name="full_name" class="form-control" aria-describedby="fullname" placeholder="Full name" value="{{old('full_name')}}">
                        @error('full_name')
                        <p class="text-danger f14">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <input type="text" name="email" class="form-control" aria-describedby="emailHelp" placeholder="Email" value="{{old('email')}}">
                        @error('email')
                        <p class="text-danger f14">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <input type="text" name="username" class="form-control" aria-describedby="Username" placeholder="Username" value="{{old('username')}}">
                        @error('username')
                        <p class="text-danger f14">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        @error('password')
                        <p class="text-danger f14">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <input type="password" name="verify_password" class="form-control" placeholder="Verify Password">
                        @error('verify_password')
                        <p class="text-danger f14">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-red w-100 mt-4" onclick="System.showLoading()">Signup</button>
                    </div>
                    <div class="text-center f14 mt-3">
                        Already a member? <a href="{{url(route('user.getLogin'))}}" class="text-success">Login now</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
