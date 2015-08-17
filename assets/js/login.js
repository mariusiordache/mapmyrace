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