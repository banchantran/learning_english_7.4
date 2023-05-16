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
        <div class="row mb-20">
            <div class="col-12">
                <div class="records">
                    @include('elements.paging', ['paginator' => $data])
                </div>
            </div>
        </div>
        <div class="list-result container">
            @if (count($data) > 0)
                @foreach($data as $item)
                    <div class="row result-item">
                        <div class="col-5 highlight-result">
                            <div class="learning-text">
                                <p class="text-source">{{$item->text_source}}</p>
                                <p class="text-destination">{{$item->text_destination}}</p>
                            </div>
                        </div>
                        <div class="col-4 highlight-result">
                            <div class="category-lesson">
                                <p class="link-category"><a href="{{url(route('lesson.index', ['categoryId' => $item->category_id]))}}" target="_blank">{{$item->category_name}}</a></p>
                                <p class="link-lesson"><a class="text-red-i" href="{{url(route('learning.show', ['lessonId' => $item->lesson_id]))}}">{{$item->lesson_name}}</a></p>
                            </div>
                        </div>
                        <div class="col-2">
                            <p>{{$item->username}}</p>
                        </div>
                        <div class="col-1 text-end">
                            <img class="play-icon mr-10 {{!empty($item->audio_path) ? '' : 'no-file'}}" width="20px"
                                 src="{{url('img/play.png')}}" alt="audio"
                                 onclick="System.playAudio(this)">

                            @auth
                                <img class="bookmark-icon {{in_array($item->id, $bookmarkItemIds) ? 'checked' : ''}}" width="20px"
                                     src="{{url('img/bookmark.png')}}" alt="bookmark"
                                     data-url="{{url(route('bookmark.store', ['itemId' => $item->id]))}}"
                                     onclick="System.setBookmark(this)">
                            @endauth
                        </div>
                    </div>
                @endforeach
            @else
                <div class="row result-item">
                    <div class="col-12 text-center text-muted">Nothing to display!</div>
                </div>
            @endif
        </div>

        <div class="row mt-5 mb-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $data->appends(['keyword' => $keyword])->links() }}
            </div>
        </div>
    </div>
@endsection
@section('script')
    @if (count($data) > 0)
        <script type="application/javascript">
            $(document).ready(function () {
                let keyword = $('.keyword-search').val();

                if ($.trim(keyword) !== '') {
                    $(".highlight-result").mark(keyword);
                }
            })
        </script>
    @endif
@endsection
