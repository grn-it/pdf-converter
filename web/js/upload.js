function setMessage(message, type) {
    $('#message').html('<div class="alert alert-' + type + '" role="alert">' + message + '</div>');
}

function setProgress(percent) {
    $('.progress-bar').css('width', percent + '%');
    $('.progress-bar').text(percent + '%');
}

function setProgressConverting() {
    $('.progress-bar').removeClass('progress-bar-success');
    $('.progress-bar').addClass('progress-bar-info');
}

function disableButtons() {
    $('.select-file, .upload-button').attr('disabled', 'disabled');
}

function enableButtons() {
    $('.select-file, .upload-button').removeAttr('disabled');
}

$(document).ready(function() {
    $('.select-file').click(function() {
        $('#uploadform-file').click();
    });

    $('#uploadform-file').fileupload({
        dataType: 'json',
        add: function (e, data) {
            var filename = data['files'][0]['name'];

            $('.select-file').text(filename);

            enableButtons();

            $('.upload-button').off('click').on('click', function() {
                setMessage('<b>Загружаем</b> файл «' + filename + '» на сервер...', 'info');
                setProgress(0);
                $('.progress').show();
                disableButtons();
                data.submit();
            })
        },
        progress: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            setProgress(progress);
        },
        done: function (e, data) {
            var data = data['result'];
            var fileId = data['fileId'];

            if (data['success']) {
                $.when($.getJSON('convert/' + data['fileId']));

                setMessage('<b>Конвертируем</b> PDF-файл в JPG-изображения...', 'info');
                setProgressConverting();

                var sse = new EventSource('convert/progress');
                sse.addEventListener('progress',function(e){
                    var data = JSON.parse(e.data);

                    setProgress(data['percent']);

                    if (data['converted']) {
                        setMessage('<b>Готово!</b> Сейчас вы будете перенаправлены на страницу слайдера', 'success');

                        setTimeout(function() {
                            window.location = 'slider/' + fileId;
                        }, 2000);

                        e.target.close();
                    }
                },false);
            } else {
                setMessage('<b>Ошибка!</b> ' + data['message'], 'danger');
                $('.progress').hide();
                enableButtons();
            }
        }
    });
});