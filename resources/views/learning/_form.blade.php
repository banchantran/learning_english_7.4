@foreach($items as $item)
    <input type="hidden" name="id" value="{{$item['id']}}">
    <div class="row root-row">
        <div class="col-11">
            <div class="row">
                <div class="col-6">
                    @if ($item['field_to_learn'] == 'text_source')
                        <input class="input-learning" type="text" placeholder="" name="source[]" value="">
                        <p class="text-suggest">{{$item['text_source']}}</p>
                    @else
                        <p class="plain-text">{{$item['text_source']}}</p>
                    @endif
                </div>
                <div class="col-6">
                    @if ($item['field_to_learn'] == 'text_destination')
                        <input class="input-learning" type="text" placeholder="" name="destination[]" value="">
                        <p class="text-suggest">{{$item['text_destination']}}</p>
                    @else
                        <p class="plain-text">{{$item['text_destination']}}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-1 d-flex">
            <div class="row">
                <div class="play-audio hidden">
                    <audio controls>
                        <source src="{{!empty($item['audio_path']) ? url($item['audio_path']) : ''}}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                </div>
                <div class="action col-6 d-flex align-items-center cursor-pointer">
                    <img class="play-icon {{!empty($item['audio_path']) ? '' : 'no-file'}} w-100"
                         src="{{url('img/play.png')}}" alt="audio"
                         onclick="System.playAudio(this)">
                </div>
                @auth
                    <div class="action col-6 d-flex align-items-center cursor-pointer">
                        <img class="bookmark-icon {{in_array($item['id'], $bookmarkItemIds) ? 'checked' : ''}} w-100"
                             src="{{url('img/bookmark.png')}}" alt="bookmark"
                             data-url="{{url(route('bookmark.store', ['itemId' => $item['id']]))}}"
                             onclick="System.setBookmark(this)">
                    </div>
                @endauth
                <p class="text-suggest">&nbsp;</p>
            </div>
        </div>
    </div>
@endforeach
