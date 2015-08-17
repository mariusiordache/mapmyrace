var login = {
    setup: function () {
        this.form = $('#login-form');
        this.form.on('submit', $.proxy(this.post, this));
        this.errors = this.form.find('.text-danger');
    },
    post: function (e) {
        this.errors.html('').removeClass('in');
        var data = this.form.serialize();

        this.errors.addClass('alert-warning').removeClass('alert-danger');
        this.errors.addClass('in').html('Please wait ... ');
        buttonLoading('button[type="submit"]');

        $.post(PAGE_DATA.base_url + '/user/post_login', data, $.proxy(this.onPost, this), 'json');
        e.preventDefault();
        e.stopPropagation();
        return false;
    },
    onPost: function (data) {
        if (data.success) {
            $('#thanks').show().addClass('in').html("Welcome back! We're redirecting you to your dashboard...");
            this.form.removeClass('in').addClass('out');
            if ('goback' in data && data.goback !== '') {
                location.href = data.goback;
            } else {
                location.href = PAGE_DATA.base_url + '/dashboard';
            }
        } else {

            buttonReset('button[type="submit"]');
            this.errors.addClass('alert-danger').removeClass('alert-warning');
            var error_html = '<ul>';
            for (var i in data.errors) {
                error_html += '<li>' + data.errors[i] + '</li>';
            }
            error_html += '</ul>';
            this.errors.html(error_html).addClass('in');
        }
    }
};

login.setup();

$(".login-form").validate();
$(".login-btn").click(function () {
    $.post("/user/post_login", $('.login-form').serialize(), function (data) {
        if (data.success === true) {
            window.location.replace(data.goback);
        } else {
            $('.login-form div.error').removeClass('error');
            $('.error-message').empty();
            $.each(data.errors, function (e, i) {
                var $target = $('.login-form').find('input[name="' + e + '"]');
                $target.addClass('error');
            });
        }
    });
});
$('.login-form input').keypress(function (e) {
    if (e.which === 13) {
        $(".login-btn").trigger('click');
    }
});
$('.register-form input').keypress(function (e) {
    if (e.which === 13) {
        $(".create-account").trigger('click');
    }
});
$(".create-account").click(function () {
    $.post("/user/post_register", $('.register-form input').serialize(), function (data) {
        if (data.success === true) {
            location.reload();
        } else {
            $('.register-form div.error').removeClass('error');
            $('.error-message').empty();
            $.each(data.errors, function (e, i) {
                var $target = $('.register-form input[name=' + e + ']');
                $target.addClass('error');
            });
        }
    }, "JSON");

});