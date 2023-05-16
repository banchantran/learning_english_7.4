@extends('layout.default')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Bookmark</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="ajax-form">
        <div class="row mb-20">
            <div class="col-4 d-flex align-items-center">
            </div>
            <div class="col-4 text-center">
                <h2 class="lesson-name">Bookmark <span class="text-small"> - {{count($bookmarkItemIds)}} {{Str::plural('item', count($bookmarkItemIds))}}</span></h2>
            </div>
            <div class="col-4 text-end  d-flex align-items-center justify-content-end">
            </div>
        </div>
        <hr class="default">

        <div class="group-actions mb20">
            <div class="row">
                <div class="col-6">
                    <select class="form-select display-type d-inline-block" name="show_type" aria-label="Default select" onchange="$('.btn-reload').click()">
                        <option value="random">Random</option>
                        <option value="text_source">Learning source</option>
                        <option value="text_destination">Learning destination</option>
                    </select>
                    <button type="button" class="btn btn-outline-success" onclick="System.showSuggestion(this)">
                        <p class="d-flex align-items-center">
                            <img src="{{url('img/open-eye.png')}}" alt="reload" width="18px" class="mr-10 hidden open-eye">
                            <img src="{{url('img/close-eye.png')}}" alt="reload" width="18px" class="mr-10 close-eye">
                            <span>Show suggestions</span>
                        </p>
                    </button>

                    <button type="button"
                            class="btn btn-outline-success btn-reload"
                            data-url="{{url(route('bookmark.reload'))}}"
                            onclick="System.reloadFormLearning(this)">
                        <p class="d-flex align-items-center">
                            <img src="{{url('img/reload.png')}}" alt="reload" width="15px" class="mr-10">
                            <span>Reload</span>
                        </p>
                    </button>
                </div>
                <div class="col-6">
                    <div class="float-end">
                        <button type="button" class="btn btn-outline-success" onclick="System.checkResult()">
                            <p class="d-flex align-items-center">
                                <img src="{{url('img/signal.png')}}" alt="reload" width="15px" class="mr-10">
                                <span>Check result</span>
                            </p>
                        </button>
                    </div>
                </div>
                <div class="col-12">
                    <p class="text-result hidden mt-3">Correct: <span class="point-result"></span> <span class="status-result"></span></p>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="border border-dark p-4 rounded-3">
            <div class="form-group form-lesson form-learning">
                @include('learning._form')
            </div>
        </div>
    </div>
@endsection
