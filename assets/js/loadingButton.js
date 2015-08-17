requirejs(["jquery"], function () {
        $.fn.setState = function (state) {
            return this.each(function () {
                switch (state) {
                    case 'loading':
                        $(this).attr('disabled', 'disabled');
                        $(this).data('original-text', $(this).html());
                        $(this).html($(this).data('loading-text'));
                        break;
                    case 'loaded':
                        $(this).removeAttr('disabled');
                        $(this).html($(this).data('original-text'));
                        break;
                }
            })
        }
});