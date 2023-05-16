<!-- Modal create lesson -->
<div class="modal fade in" id="createLesson" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Life lessons!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCreateLesson" action="{{url(route('lesson.store', ['categoryId' => isset($category) ? $category->id : 0]))}}" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert-danger hidden">
                        <ol class="list-errors"></ol>
                    </div>
                    <div class="ajax-form">
                        <input type="hidden" name="id" value="">
                        <div class="form-group">
                            <label for="nameLesson">Lesson name</label>
                            <input type="text" class="form-control mt05" name="name" id="nameLesson" aria-describedby="nameLesson" placeholder="...">
                        </div>
                        <hr>
                        <div class="form-group form-lesson root-form">
                            <div class="row root-row">
                                <div class="col-5">
                                    <input type="text" placeholder="Text source" name="source[]">
                                </div>
                                <div class="col-5">
                                    <input type="text" placeholder="Text destination" name="destination[]">
                                </div>
                                <div class="col-2">
                                    <div class="row">
                                        <div class="upload-audio col-6">
                                            <div onclick="System.uploadAudio(this)">
                                                <img src="{{url('img/audio.png')}}" alt="audio" class="icon-audio">
                                                <span class="file-name" data-text-default="Audio">Audio</span>
                                            </div>

                                            <div class="play-audio hidden">
                                                <audio controls>
                                                    <source src="" type="audio/mpeg">
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
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-2">
                            <div class="add-more by-img"><img width="40px" src="{{url('img/add-more.png')}}" alt="add more" onclick="System.addMoreRow(this)">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-red" data-url="" onclick="System.submitForm(this)">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade in" id="editLesson" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Life lessons!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditLesson" action="{{url(route('lesson.update', ['categoryId' => isset($category) ? $category->id : 0]))}}" enctype="multipart/form-data">

                <div class="modal-body">
                    <div class="alert-danger hidden">
                        <ol class="list-errors"></ol>
                    </div>

                    <div class="ajax-form">
                        @include('lesson._form')
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-2">
                            <div class="add-more by-img"><img width="40px" src="{{url('img/add-more.png')}}" alt="add more" onclick="System.addMoreRow(this)">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-red" data-url="" onclick="System.submitForm(this)">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade in" id="createCategory" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Such a wonderful category!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCreateCategory" action="{{url(route('category.store'))}}" method="post" onsubmit="return false;">
                <input type="hidden" name="id" value="">
                <div class="modal-body">
                    <div class="alert-danger hidden">
                        <ol class="list-errors"></ol>
                    </div>

                    <div class="form-group">
                        <label for="nameCategory">Category name</label>
                        <input type="text" class="form-control mt05" name="name" id="nameCategory"
                               aria-describedby="nameCategory"
                               placeholder="">
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" name="is_public" type="checkbox" value="1" checked="checked" id="defaultCheck1">
                        <label class="form-check-label" for="defaultCheck1">
                            Public for everyone
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-red" data-url="" onclick="System.submitForm(this)">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Modal -->
<div class="modal fade in" id="deleteConfirm" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Confirm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-red" data-url="" onclick="System.deleteConfirm(this)">OK
                </button>
            </div>
        </div>
    </div>
</div>
