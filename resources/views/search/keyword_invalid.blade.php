@extends('layout.default')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            {{--            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">{{$category->name}}</a></li>--}}
            <li class="breadcrumb-item active" aria-current="page">Search</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container">

        <div class="row mt-4 mb-4">
            <div class="col-8 offset-2">
                <form method="get" action="{{url(route('search.result'))}}"
                      class="form-inline my-2 my-lg-0 d-flex group-search align-items-center justify-content-center">
                    <div class="form-group w-75">
                        <input type="text" name="keyword" class="form-control keyword-search" placeholder="Keyword..." value="{{!empty($keyword) ? $keyword : ''}}">
                    </div>
                    <button type="submit" class="btn btn-outline-success btn-search my-2 my-sm-0 ml10" onclick="System.showLoading()">
                        <img src="{{url('img/search-icon.svg')}}" alt="search">
                        Search
                    </button>
                </form>
            </div>
        </div>

        <div class="list-result container">
            <div class="row result-item">
                <div class="col-12 text-center text-muted">Hey! You have to enter something!</div>
            </div>
        </div>

    </div>
@endsection
