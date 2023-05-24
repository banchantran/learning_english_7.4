@if (isset($data))
    @foreach($data as $item)
        <div class="row root-row">
            <div class="col-10">
                <div class="row">
                    <div class="col-6">
                        <input type="text" class="input-text-source" value="{{$item[0]}}" placeholder="Text source" name="source[]">
                    </div>
                    <div class="col-6">
                        <input type="text" class="input-text-destination" value="{{$item[7]}}" placeholder="Text destination" name="destination[]">
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="row">
                    <div class="upload-audio col-6 hidden">
                        <div class="play-audio ">
                            <audio controls>
                                <source src="{{$item[2]}}" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                            <input type="text" name="audio_path[]" value="{{$item[2]}}">
                        </div>
                    </div>
                    <div class="col-3">
                        <img class="trash-icon w-100" src="{{url('img/play.png')}}" alt="trash" onclick="System.playAudio(this)">
                    </div>

                    <div class="col-3">
                        <img class="trash-icon w-100" src="{{url('img/trash-icon.png')}}" alt="trash" onclick="System.removeRow(this)">
                    </div>
                </div>

            </div>
        </div>
    @endforeach
@endif
