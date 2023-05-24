@extends('layout.default')
@section('content')

    <form id="crawl" action="{{url(route('crawl.store'))}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <div class="alert-danger hidden">
                <ol class="list-errors"></ol>
            </div>
            <div class="ajax-form">
                <input type="hidden" name="id" value="">
                <div class="form-group">
                    <label for="nameLesson">Category</label>
                    <select name="category_id" class="form-select">
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-3">
                    <label for="">Lesson name</label><br>
                    <input type="text" class="input-text-source w-100" placeholder="Lesson name" name="lesson">
                </div>
                <div class="form-group mt-3">
                    <label for="">Url</label><br>
                    <input type="text" id="url_crawl" class="input-text-source w-100" value="{{isset($url) ? $url : ''}}" placeholder="Text source" name="url">
                </div>
            </div>

            <hr class="default">
            <div class="form-group form-lesson root-form root-form-crawl">

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" data-url="{{url(route('crawl.run'))}}" onclick="System.crawl(this)">Crawl</button>
            <button type="submit" class="btn btn-red" data-url="" onclick="System.showLoading()">
                Save
            </button>
        </div>
    </form>

@endsection
