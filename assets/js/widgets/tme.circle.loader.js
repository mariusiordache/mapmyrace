$.widget("ui.circleloader", {
    // default options
    options: {
        total: 0,
        progress: null,
        percent: 0
    },
    // the constructor
    _create: function () {
        this.circle = this.element.find('circle');
        this.totalDashPct = this.circle.attr('stroke-dasharray')
        
        if (this.options.progress !== null ) {
            this.setProgress(this.options.progress);
        }
    },
    setTotal: function(total) {
        this.options.total = total;
        this.options.percent = this.options.progress  / this.options.total;
        this._update();
    },
    incProgress: function() {
        this.setProgress(this.options.progress+1);
    },
    setProgress: function(progress) {
        this.options.progress = progress;
        this.options.percent = this.options.progress  / this.options.total;
        this._update();
    },
    getProgress: function() {
        return this.options.progress;
    },
    setPercent: function(percent) {
        this.options.percent = percent / 100;
        this._update();
    },
    getPercent: function() {
        return this.options.percent;
    },
    isCompleted: function() {
        return this.getPercent() == 1;
    },
    setTotalAndProgress: function(total, progress) {
        this.options.total = total;
        this.setProgress(progress);
    },
    _getDashPct: function() {
        return this.totalDashPct - this.totalDashPct * this.options.percent;
    },
    _update: function () {
        this.circle.css('stroke-dashoffset', this._getDashPct() + 'px');
        this.element.trigger('tme.circle.updated', this.options);
    }
});