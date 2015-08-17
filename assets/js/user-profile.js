define(["/backbone/Model/TmeModel.js"], function (TmeModel) {

    var user = new TmeModel($.extend(USER_DATA, {tmecollection: 'user'}));

    user.on("change", function (obj) {
        $('[data-user]').each(function (index, el) {
            $(el).text(obj.get($(el).data('user')));
        });
    });

    $('.form-container').on('click', ".edit-input", function () {
        $('.fake-input').show();
        $(this).siblings('.fake-input').hide();
        $(this).siblings('.clean-input').focus();
    });
    $('.form-container').on('click', ".saveInput", function () {
        $('.fake-input').show();
        var obj = new Object();
        obj[$(this).attr('name')] = $(this).val();
        if ($(this).val() !== "") {
            $(this).removeClass('error');
            user.save(obj, {
                patch: true,
                error: function () {
                    alert('sss');
                }
            }
            );
            $(this).blur();
            $(this).siblings('.fake-input').show();
        } else {
            $(this).addClass('error');
        }
        $(this).addClass('edit-input').removeClass('saveInput').attr('src', '/assets/images/edit.svg');

    });
    $('.changePasswordModal').click(function () {
        $('#changePasswodModal').modal('show');
    });
    $('.save-password').click(function () {
        if ($('input[name="new_password"]').val() === $('input[name="retype_password"]').val() &&
                $('input[name="new_password"]').val().length > 0 && $('input[name="retype_password"]').val().length > 0
                ) {

        } else {

        }

    });
    $('.form-container .clean-input').keyup(function (e) {
        if ($(this).val() !== $(this).siblings('.fake-input').text()) {
            $(this).siblings('.edit-input').attr('src', '/assets/images/checkmark_green.svg').removeClass('edit-input').addClass('saveInput');
        } else {
            $(this).siblings('.edit-input').attr('src', '/assets/images/edit.svg').addClass('edit-input').removeClass('saveInput');
        }
        if (e.keyCode === 13) {
            var obj = new Object();
            obj[$(this).attr('name')] = $(this).val();
            if ($(this).val() !== "") {
                $(this).removeClass('error');
                user.save(obj, {
                    patch: true,
                    error: function () {
                        alert('sss');
                    }
                }
                );
                $(this).blur();
                $(this).siblings('.fake-input').show();
            } else {
                $(this).addClass('error');
            }
            $(this).siblings('img').addClass('edit-input').removeClass('saveInput').attr('src', '/assets/images/edit.svg');
        }
    });

    $(".close-message").click(function(){
        $(this).parent().remove();
    })
    $('#fileupload').fileupload({
        url: '/account/upload_profile_pic',
        autoUpload: true,
        multiple: false,
        maxFileSize: 50000000000,
        acceptFileTypes: /(\.|\/)(jpeg,jpg,png)$/i,
        add: function (e, data) {
            var fileType = data.files[0].name.split('.').pop(), allowdtypes = 'jpeg,jpg,png';
            if (allowdtypes.indexOf(fileType) < 0) {
                alert('Invalid file type, aborted');
                return false;
            }

            data.process().done(function () {
                data.submit();
            });

        },
        done: function (e, data) {
            var that = $(this).data('blueimp-fileupload') || $(this).data('fileupload');

            if (data.result.url !== undefined) {
                $('.profile-center-image img').attr('src', data.result.url);
            } else {
                alert(data.result.error);
            }
        }
    });
});