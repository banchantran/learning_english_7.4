@extends('layout.default')
@section('content')

    <form id="crawl" action="{{url(route('crawl.index'))}}" method="post" enctype="multipart/form-data">
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
                    <label for="">Url</label><br>
                    <input type="text" class="input-text-source w-100" placeholder="Text source" name="url">
                </div>
            </div>

            <hr class="default">
            <div class="form-group form-lesson root-form">
                @if (isset($data))
                    @foreach($data as $item)
                        <div class="row root-row">
                            <div class="col-5">
                                <input type="text" class="input-text-source" value="{{$item[0]}}" placeholder="Text source" name="source[]">
                            </div>
                            <div class="col-5">
                                <input type="text" class="input-text-destination" value="{{$item[7]}}" placeholder="Text destination" name="destination[]">
                            </div>
                            <div class="col-2">
                                <div class="row">
                                    <div class="upload-audio col-6">
                                        <div class="play-audio hidden">
                                            <audio controls>
                                                <source src="{{$item[2]}}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        </div>
                                    </div>
                                    <div class="remove col-3 d-flex align-items-center">
                                        <img class="trash-icon w-100" src="{{url('img/play.png')}}" alt="trash" onclick="System.playAudio(this)">
                                    </div>

                                    <div class="remove col-3 d-flex align-items-center">
                                        <img class="trash-icon w-100" src="{{url('img/trash-icon.png')}}" alt="trash" onclick="System.removeRow(this)">
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-red" data-url="" onclick="System.showLoading()">
                Save
            </button>
        </div>
    </form>

@endsection
