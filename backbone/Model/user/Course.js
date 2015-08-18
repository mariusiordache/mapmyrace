define(['/backbone/Model/TmeModel.js'], function (TmeModel) {
    return TmeModel.extend({
        initialize: function () {
            var d = this.get('duration');
            var hours = Math.floor(d / 3600);
            var left = d - (hours * 3600);
            var minutes = Math.floor(left / 60);
            var seconds =  left - minutes * 60;
            
            this.set('duration_string', (hours ? hours + "h ": "") + minutes + "m " + seconds + "s")
        }

    });
});