let System = {};

System.showLoading = function () {
    $('#loading').show();
}

System.hideLoading = function () {
    $('#loading').hide();
}

System.removeRow = function (e) {
    if ($(e).closest('.root-form').find('.root-row').length === 1) {
        return;
    }

    $(e).closest('.root-row').remove();
}

System.uploadAudio = function (e) {
    let inputFile = $(e).closest('.upload-audio').find('input[type="file"]');

    inputFile.click();
}

System.setAudioName = function (e) {
    let inputFile = $(e),
        audioFile = $(e).closest('.root-row').find('audio'),
        fileName = inputFile.val().match(/[^\\/]*$/)[0];

    let audioUrl = URL.createObjectURL(inputFile[0].files[0]);

    audioFile.attr('src', audioUrl);
    inputFile.closest('.upload-audio').find('span').html(fileName);
}

System.playAudio = function (e) {
    let fileAudio = $(e).closest('.root-row').find('audio');

    // stop other audio
    $('audio').each(function () {
        this.pause(); // Stop playing
        this.currentTime = 0; // Reset time
    });

    if (fileAudio.attr('src') === '') return;

    fileAudio[0].play();
}

System.addMoreRow = function (e) {
    let form = $(e).closest('form'),
        rootForm = form.find('.root-form'),
        rootRow = form.find('.root-row:first-child').clone();

    rootRow.find('input[type="hidden"]').val('');
    rootRow.find('input[type="text"]').val('');
    rootRow.find('input[type="file"]').val('');
    rootRow.find('.file-name').html('Audio');
    rootRow.find('audio').attr('src', '');

    rootForm.append(rootRow);
}

System.showEditModal = function (modalId, e) {

    let modal = $(modalId),
        url = $(e).attr('data-url');

    System.showLoading();
    System.resetModal(modalId);

    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function (obj) {
            System.hideLoading();

            if (obj.success === true) {
                $.each(obj.data, function (field, value) {
                    modal.find('[name="' + field + '"]').each(function(i, item) {
                        if ($(item).is(':checkbox')) {
                            $(item).prop('checked', value);
                        } else {
                            $(item).val(value);
                        }
                    });
                });

                modal.modal();
            }
        },
        error: function (obj) {
            System.hideLoading();

            alert('Oops! hihi ^^');
        }
    });
}

System.showAjaxEditModal = function (modalId, e) {

    let modal = $(modalId),
        url = $(e).attr('data-url');

    System.showLoading();
    System.resetModal(modalId);

    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function (obj) {
            System.hideLoading();

            modal.find('.ajax-form').html('');
            if (obj.success === true) {
                modal.find('.ajax-form').html(obj.data);
                modal.modal();
            } else {
                window.location.reload();
            }
        },
        error: function (obj) {
            System.hideLoading();

            alert('Oops! hihi ^^');
        }
    });
}

System.showModal = function (modalId, e) {
    let modal = $(modalId);

    if ($(e).attr('data-url')) {
        modal.find('.btn-primary').attr('data-url', $(e).attr('data-url'));
    }

    System.resetModal(modalId);

    // show modal
    modal.modal();
}

System.resetModal = function (modalId) {
    let modal = $(modalId);

    modal.find('.alert-danger').hide();
    modal.find('.list-errors').empty();
    modal.find('input[type=text], select').val('');
    modal.find('audio').attr('src', '');
    modal.find('[data-text-default]').each(function (i, item) {
        $(item).html($(item).attr('data-text-default'));
    });
}

System.submitForm = function (e) {
    let form = $(e).closest('form'),
        url = form.attr('action'),
        formData = new FormData(form[0]);

    System.showLoading();

    $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        data: formData,
        contentType: false,
        processData: false,
        success: function (obj) {
            if (obj.success === true) {
                window.location.reload();
            }
        },
        error: function (obj) {
            let response = JSON.parse(obj.responseText);
            System.showErrors(form, response.errors);
            System.hideLoading();
        }
    });
}

System.showErrors = function (form, errors) {
    let listErrors = '';

    $.each(errors, function (i, item) {
        listErrors += '<li><span class="error">' + item[0] + '</span></li>';
    });

    form.find('.list-errors').html(listErrors);
    form.find('.alert-danger').show();
}

System.deleteConfirm = function (e) {
    let url = $(e).attr('data-url');

    System.showLoading();

    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function (obj) {
            if (obj.success === true) {
                window.location.reload();
            }
        }
    })
}

System.showSuggestion = function (e) {
    $('.form-learning').find('.text-suggest').each(function (i, item) {
        if ($(item).css('visibility') === 'hidden') {
            $(item).css({visibility: "visible"});

            $(e).find('.close-eye').hide();
            $(e).find('.open-eye').show();
        } else {
            $(item).css({visibility: "hidden"});

            $(e).find('.close-eye').show();
            $(e).find('.open-eye').hide();
        }
    });
}

System.checkResult = function () {
    let formLearning = $('.form-learning'),
        pointResult = $('.point-result'),
        statusResult = $('.status-result'),
        textResult = $('.text-result');

    let totalItems = formLearning.find('.root-row').length,
        totalCorrectAnswer = 0;

    formLearning.find('input.input-learning').each(function (i, item) {
        let correctAnswer = $(item).next('.text-suggest').html();

        if ($(item).val() === correctAnswer) {
            totalCorrectAnswer++;
            $(item).removeClass('highlight');
        } else {
            $(item).addClass('highlight');
        }
    });

    $rate = totalCorrectAnswer / totalItems;
    switch (true) {
        case $rate === 1    :
            statusResult.html('- Excellent ❀❀❀');
            $('#startConfetti').click();
            break;
        case $rate >= 0.9   :
            statusResult.html('- Very good');
            break;
        case $rate >= 0.8   :
            statusResult.html('- Good');
            break;
        case $rate >= 0.6   :
            statusResult.html('- Try again （＾ω＾）');
            break;
        case $rate < 0.6    :
            statusResult.html('- Bad ┌( ಠ_ಠ )┘');
            break;
    }

    setTimeout(function () {
        $('#stopConfetti').click();
    }, 5000);

    pointResult.html(totalCorrectAnswer + '/' + totalItems);
    textResult.show();
}

System.setBookmark = function (e) {
    let url = $(e).attr('data-url');

    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function (obj) {
            if (obj.success === false) {
                window.location.reload();
                return;
            }

            $(e).toggleClass('checked');
        },
        error: function () {
            alert('Oops! hihi ^^');
        }
    })
}

System.markCompleted = function (e) {
    let url = $(e).attr('data-url');

    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function (obj) {
            if (obj.success === false) {
                window.location.reload();
                return;
            }

            if (obj.data.was_completed) {
                $(e).find('img.mark-complete').show();
            } else {
                $(e).find('img.mark-complete').hide();
            }
        },
        error: function () {
            alert('Oops! hihi ^^');
        }
    })
}

System.reloadFormLearning = function (e) {
    let url = $(e).attr('data-url'),
        formLearning = $('.form-learning'),
        displayType = $('select.display-type').val();

    System.showLoading();

    $('.text-result').hide();

    $.ajax({
        url: url,
        type: 'get',
        data: {'displayType': displayType},
        dataType: 'json',
        success: function (obj) {
            if (obj.success) {
                formLearning.html(obj.data);
            }

            System.hideLoading();
        },
        error: function () {
            System.hideLoading();
            alert('Oops! hihi ^^');
        }
    })
}
