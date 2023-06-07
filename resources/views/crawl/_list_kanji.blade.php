@if (isset($data))
    @foreach($data as $item)
        <div class="row root-row">
            <div class="col-10">
                <div class="row">
                    <div class="col-5">
                        <input type="text" class="input-text-source" value="{{isset($item[1]) ? trim(str_replace('）', '', $item[1])) : ''}}" placeholder="Text source" name="source[]">
                    </div>
                    <div class="col-5">
                        <input type="text" class="input-text-destination" value="{{isset($item[2]) ? trim(str_replace('）', '', $item[2])) : ''}}" placeholder="Text destination" name="destination[]">
                    </div>
                    <div class="col-2">
                        <input type="text" class="input-text-note" value="{{trim($item[0])}}" placeholder="Text note" name="note[]">
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="row">
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
