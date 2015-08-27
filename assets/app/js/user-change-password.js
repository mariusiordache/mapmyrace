define(["/backbone/Model/TmeModel.js"], function (TmeModel) {
    var retypeCorrect = false;
    var correctOldPassword = false;
    var user = new TmeModel($.extend(USER_DATA, {tmecollection: 'user'}));

    user.on("change", function (obj) {
        $('[data-user]').each(function (index, el) {
            $(el).text(obj.get($(el).data('user')));
        });
    });
    $('input[name=old_password]').keyup(function (e) {
        $.post("/user/check_password", {username: USER_DATA.username, password: $(this).val()}, function (data) {
            $target = $(e.currentTarget).next();
            if (data) {
                $target.attr('src', '/assets/dashboardv2/checkmark_green.svg').removeClass('hide');
                correctOldPassword = true;
            } else {
                $target.attr('src', '/assets/dashboardv2/exclamation_point.svg').removeClass('hide');
                correctOldPassword = false;
            }
        }, "JSON");

    });

    $('input[name=new_password]').keyup(function (e) {
        $target = $(e.currentTarget).next();
        if ($(this).val() === "") {
            $target.addClass('hide');
        } else {
            $target.removeClass('hide');
        }
    });
    $('input[name=retype_password]').keyup(function (e) {
        $target = $(e.currentTarget).next();
        if ($(this).val() === $('input[name=new_password]').val()) {
            $target.attr('src', '/assets/dashboardv2/checkmark_green.svg').removeClass('hide');
            retypeCorrect = true;
        } else {
            $target.attr('src', '/assets/dashboardv2/exclamation_point.svg').removeClass('hide');
            retypeCorrect = false;
        }

    });
    $('.change-password-form input').keyup(function (e) {
        $("#changePassword").removeClass("in");
        if (e.keyCode === 13) {
            $('.changePasswordModal').trigger('click');
        }
    });
    $('.changePasswordModal').click(function (event) {
        $("#changePassword").removeClass("in");
        if (retypeCorrect && correctOldPassword) {
            var obj = new Object();
            obj['password'] = $('input[name=retype_password]').val();
            user.save(obj, {
                patch: true,
                error: function () {
                    
                },
                success: function () {
                    location.replace("/account/profile?success=true");
                    $("#changePassword").addClass("in");
                }
            });
        }
        event.preventDefault();
    });

});
