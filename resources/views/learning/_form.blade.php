<div class="form-learning">

    <div class="row">
        <div class="col-6">
            <form action="{{url(route('user.updateAutoPlayFlag'))}}" method="post" id="formUpdateAutoPlayFlag">
                @csrf
                <div class="form-check">
                    <input class="form-check-input" name="auto_play_flag" type="checkbox" value="1" {{Auth::user()->auto_play_flag ? 'checked' : ''}} id="showMyData"
                           onchange="System.showLoading();$('#formUpdateAutoPlayFlag').submit()">
                    <label class="form-check-label" for="showMyData">
                        Auto play audio
                    </label>
                </div>
            </form>
        </div>
        <div class="col-6 text-end">
            @if (isset($totalLessons))
                <span class="text-small">{{$totalLessons}} {{Str::plural('lesson', $totalLessons)}}</span>
            @endif
            @if (isset($totalItems))
                <span class="text-small">{{isset($totalLessons) ? ' - ':''}}{{$totalItems}} {{Str::plural('item', $totalItems)}}</span>
            @endif
        </div>
    </div>


    <div class="border border-dark p-4 rounded-3">
        <div class="form-group form-lesson">

            @foreach($items as $item)
                <input type="hidden" name="id" value="{{$item['id']}}">

                <div class="row root-row">
                    <div class="col-11">
                        <div class="row">

                            @if ((isset($category) && $category->language_type == config('constant.LANGUAGE_TYPE_JAPANESE')) || (isset($languageType) && $languageType == config('constant.LANGUAGE_TYPE_JAPANESE')))
                                <div class="col-2">
                                    <p class="plain-text">{!! !empty($item['text_note']) ? $item['text_note'] : '&nbsp;' !!}</p>
                                </div>
                            @endif
                            <div
                                class="{{(isset($category) && $category->language_type == config('constant.LANGUAGE_TYPE_JAPANESE')) || (isset($languageType) && $languageType == config('constant.LANGUAGE_TYPE_JAPANESE')) ? 'col-5' : 'col-6'}}">
                                @if ($item['field_to_learn'] == 'display_source')
                                    <input class="input-learning" type="text" placeholder="" name="source[]" value="" onfocusin="System.autoPlayAudio(this)">
                                    <p class="text-suggest">{{$item['text_source']}}</p>
                                @else
                                    <p class="plain-text">{{$item['text_source']}}</p>
                                @endif
                            </div>
                            <div
                                class="{{(isset($category) && $category->language_type == config('constant.LANGUAGE_TYPE_JAPANESE')) || (isset($languageType) && $languageType == config('constant.LANGUAGE_TYPE_JAPANESE')) ? 'col-5' : 'col-6'}}">
                                @if ($item['field_to_learn'] == 'display_destination')
                                    <input class="input-learning" type="text" placeholder="" name="destination[]" value="" onfocusin="System.autoPlayAudio(this)">
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

        </div>
    </div>
</div>
