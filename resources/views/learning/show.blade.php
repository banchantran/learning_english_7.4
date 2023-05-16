@extends('layout.default')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url(route('lesson.index', ['categoryId' => $lesson->category->id]))}}">{{$lesson->category->name}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$lesson->name}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="ajax-form">
        <div class="row mb-20">
            <div class="col-4 d-flex align-items-center">
                @if (!empty($previousLesson))
                    <a href="{{url(route('learning.show', ['lessonId' => $previousLesson->id]))}}" class="text-info">
                        <span class="direction">&laquo; Previous lesson</span>
                    </a>
                @endif
            </div>
            <div class="col-4 text-center">
                <h2 class="lesson-name">{{$lesson->name}}</h2>
            </div>
            <div class="col-4 text-end  d-flex align-items-center justify-content-end">
                @if (!empty($nextLesson))
                    <a href="{{url(route('learning.show', ['lessonId' => $nextLesson->id]))}}" class="text-info">
                        <span class="direction">Next lesson &raquo;</span>
                    </a>
                @endif
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
                            data-url="{{url(route('learning.reload', ['lessonId' => $lesson->id]))}}"
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

                        @auth
                            <button type="button" class="btn btn-outline-success"
                                    data-url="{{url(route('learning.mark_completed', ['lessonId' => $lesson->id]))}}"
                                    onclick="System.markCompleted(this)">
                                <p class="d-flex align-items-center">
                                    <img src="{{url('img/complete_icon.png')}}"
                                         alt="reload" width="15px"
                                         class="mark-complete mr-10 {{$wasCompleted ? '' : 'hidden'}}">
                                    <span>Mark completed</span>
                                </p>
                            </button>
                        @endauth
                    </div>
                </div>
                <div class="col-12">
                    <p class="text-result hidden mt-3">Correct: <span class="point-result">3/10</span> <span class="status-result">(Excellent)</span></p>
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
