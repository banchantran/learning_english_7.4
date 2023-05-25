<input type="hidden" name="id" value="{{isset($lesson) ? $lesson->id : ''}}">
<div class="form-group">
    <label for="nameLesson">Lesson name</label>
    <input type="text" class="form-control mt05"
           name="name"
           value="{{isset($lesson) ? $lesson->name : ''}}"
           id="nameLesson"
           aria-describedby="nameLesson"
           placeholder="...">
</div>
<hr>
<div class="form-group form-lesson root-form">
    @if (!empty($items) && is_object($items))
        @foreach($items as $item)
            <div class="row root-row">
                <input type="hidden" name="item_id[]" value="{{$item->id}}">

                @if ((isset($category) && $category->language_type == config('constant.LANGUAGE_TYPE_JAPANESE')) || (isset($languageType) && $languageType == config('constant.LANGUAGE_TYPE_JAPANESE')))
                    <div class="col-2">
                        <input type="text" placeholder="Text note" name="note[]" value="{{$item->text_note}}">
                    </div>
                @endif
                <div class="{{(isset($category) && $category->language_type == config('constant.LANGUAGE_TYPE_JAPANESE')) || (isset($languageType) && $languageType == config('constant.LANGUAGE_TYPE_JAPANESE')) ? 'col-4' : 'col-5'}}">
                    <input type="text" placeholder="Text source" name="source[]" value="{{$item->text_source}}">
                </div>
                <div class="{{(isset($category) && $category->language_type == config('constant.LANGUAGE_TYPE_JAPANESE')) || (isset($languageType) && $languageType == config('constant.LANGUAGE_TYPE_JAPANESE')) ? 'col-4' : 'col-5'}}">
                    <input type="text" placeholder="Text destination" name="destination[]" value="{{$item->text_destination}}">
                </div>
                <div class="col-2">
                    <div class="row">
                        <div class="upload-audio col-6">
                            <div onclick="System.uploadAudio(this)">
                                <img src="{{url('img/audio.png')}}" alt="audio" class="icon-audio">
                                <span class="file-name" data-text-default="Audio">{{!empty($item->audio_name) ? $item->audio_name : 'Audio'}}</span>
                            </div>

                            <div class="play-audio hidden">
                                <audio controls>
                                    <source src="{{url($item->audio_path)}}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>

                            <input type="file" name="audio[]" accept=".mp3" class="hidden file-audio" onchange="System.setAudioName(this)">
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
    @else
        <div class="row root-row">
            <div class="col-4">
                <input type="text" placeholder="Text source" name="source[]">
            </div>
            <div class="col-4">
                <input type="text" placeholder="Text destination" name="destination[]">
            </div>
            <div class="col-2">
                <input type="text" placeholder="Text note" name="note[]">
            </div>
            <div class="col-2">
                <div class="row">
                    <div class="upload-audio col-9">
                        <div onclick="System.uploadAudio(this)">
                            <img src="{{url('img/audio.png')}}" alt="audio" class="icon-audio">
                            <span class="file-name">Audio</span>
                        </div>
                        <input type="file" name="audio[]" accept=".mp3" class="hidden" onchange="System.setAudioName(this)">
                    </div>
                    <div class="remove col-3 d-flex align-items-center">
                        <img class="trash-icon w-100" src="{{url('img/trash-icon.png')}}" alt="trash" onclick="System.removeRow(this)">
                    </div>
                </div>

            </div>
        </div>
    @endif
</div>
