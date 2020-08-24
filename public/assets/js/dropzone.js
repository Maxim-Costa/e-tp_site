function readFile(input) {
    if (input.files && input.files[0]) {
        if (input.files[0].type.split('/')[0] === "image") {
            if (input.files[0].size <= 2097152) {

                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#icon_upload_avatar').removeClass('fa-upload').addClass('fa-times');
                    $('#button_upload_avatar').removeClass('btn-outline-info').addClass('btn-danger').css({"z-index": 10});
                    $('#dropzone').css("background-image", "url('" + e.target.result + "')");
                };

                reader.readAsDataURL(input.files[0]);

            } else {
                alert('le fichier et trop lourd, un peu comme ton humour :)')
            }
        } else {
            alert("le fichier n'est pas une image")
        }

    }
}

function reset(e) {
    e.wrap('<form>').closest('form').get(0).reset();
    e.unwrap();
}

$(".dropzone").change(function () {
    readFile(this);
});

$('.dropzone-wrapper')
    .on('dragover', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('dragover');
    })
    .on('dragleave', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
    });

$('.remove-preview').on('click', function () {
    let dropzone = $('.form-group').find('.dropzone');
    $('#dropzone').css("background-image", "url('')");
    $('#icon_upload_avatar').removeClass('fa-times').addClass('fa-upload');
    $('#button_upload_avatar').css({"z-index": 0}).removeClass('btn-danger').addClass('btn-outline-info');
    reset(dropzone);
});