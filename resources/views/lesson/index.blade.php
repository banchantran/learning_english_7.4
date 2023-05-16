@extends('layout.default')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">{{$category->name}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Lessons</li>
        </ol>
    </nav>
@endsection
@section('content')
    <h3 class="category-title">{{$category->name}} <small>- {{count($lessons)}} {{Str::plural('lesson', count($lessons))}}</small></h3>
    <div class="row row-cols-auto list-lessons">
        @foreach($lessons as $lesson)
            <div class="col">
                <div class="item {{in_array($lesson->id, $completedLessons) ? 'done' : ''}}">
                    <div class="actions">
                        <span><a href="{{url(route('learning.show', ['lessonId' => $lesson->id]))}}"><img src="{{url('img/learn.png')}}" alt="Learn" title="Learn"></a></span>
                        @auth
                            @if (\Illuminate\Support\Facades\Auth::user()->id === $lesson->user_id)
                                <span><img src="{{url('img/edit.png')}}" alt="Edit" title="Edit"
                                           data-url="{{url(route('lesson.show', ['categoryId' => $category->id, 'lessonId' => $lesson->id]))}}"
                                           onclick="System.showAjaxEditModal('#editLesson', this)">
                                </span>
                                <span><img src="{{url('img/trash-icon.png')}}" alt="Delete" title="Delete"
                                           data-url="{{route('lesson.delete', ['categoryId' => $category->id, 'lessonId' => $lesson->id])}}"
                                           onclick="System.showModal('#deleteConfirm', this)">
                                </span>
                            @endif
                        @endauth
                    </div>
                    <img class="done" src="{{url('img/done.png')}}" alt="done">
                    <span class="title">{{$lesson->name}}</span>
                    <small class="total-items">{{$lesson->items->count()}}  {{Str::plural('item', $lesson->items->count())}}</small>
                </div>
            </div>
        @endforeach

        @auth
            <div class="col">
                <div class="add-more" onclick="System.showModal('#createLesson', this)"><span class="line1"></span><span class="line2"></span></div>
            </div>
        @endauth
    </div>
@endsection
