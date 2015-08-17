define([
    'simpleStorage'
], function (simpleStorage) {
    $.widget("ui.tmetutorial", {
        // default options
        options: {
            disableClickLabel: "Disable",
            dissmisClickLabel: "OK, I got it",
            callURL: '',
            disableBtn: true,
            dissmisBtn: true,
            pagetoken: null
        },
        step: 0,
        generated: false,
        // the constructor
        _create: function () {
            var self = this;
            var hasStep = self.element.attr('data-step');
            if (hasStep) {
                self.step = hasStep;
            } else {
                self.step = self.uuid;
            }

            if (this.isAvailable()) {
                
                this.element.append(this.triggerTemplate());
                this._on(this.element, {
                    "click .pulsateing_ring_wrapper": function (event) {
                        if (!hasStep) {
                            self.show();
                        }
                    },
                    "click .custom-tutorial-close": function () {
                        simpleStorage.set(self.getToken(), true);
                        
                        this.element.find('.pulsateing_ring_wrapper, .custom-tutorial-wrapper').remove();
                        
                        self.next();
                    },
                    "click .custom-tutorial-disable": function () {
                        simpleStorage.set(self.getPageToken(), true);
                        $('.pulsateing_ring_wrapper, .custom-tutorial-wrapper').remove();
                    }
                });
            }
            
            this.element.addClass('tme-custom-tutorial-activated');
            
            return this;
        },
        show: function () {
            if (!this.generated) {
                this.showBoxTemplate();
            }
        },
        next: function () {
            var that = this,
                tutorial = new Object(),
                i = 0;
        
            $('.tme-custom-tutorial-activated').each(function(index, el){
                var step = $(el).attr('data-step');
                
                if (step && $(el).data('ui-tmetutorial').isAvailable()) {
                    tutorial[step] = $(el);
                    i++;
                }
            });
            
            if (i > 0) {
                var keys = Object.keys(tutorial);
                keys.sort();
        
                var nextItem = tutorial[keys[0]];
                nextItem.data('ui-tmetutorial').show();
            }
            
        },
        prev: function () {
            var that = this,
                tutorial = new Object(),
                i = 0;
        
            $('.tme-custom-tutorial-activated').each(function(index, el){
                var step = $(el).attr('data-step');
                
                if (step && $(el).data('ui-tmetutorial').isAvailable()) {
                    tutorial[step] = $(el);
                    i--;
                }
            });
            
            if (i > 0) {
                var keys = Object.keys(tutorial);
                keys.sort();
        
                var nextItem = tutorial[keys[0]];
                nextItem.data('ui-tmetutorial').show();
            }
            
        },
        getTotalTooltips: function() {
            return $('.tme-custom-tutorial-activated').length;
        },
        getStep: function () {
            return this.step;
        },
        getPageToken: function () {
            return 'tmetutorial_' + (this.options.pagetoken ? this.options.pagetoken : window.location.href.toString().split(window.location.host)[1].substr(1));
        },
        getToken: function () {
            return this.getPageToken() + "_x" + this.getStep();
        },
        isAvailable: function () {
            return !(simpleStorage.get(this.getPageToken()) === true || simpleStorage.get(this.getToken()) === true);
        },
        showBoxTemplate: function () {
            $('.custom-tutorial-wrapper').remove();
            var disableBtnTemplate = '';
            var dissmisBtnTemplate = '';
            
            if (this.options.disableBtn) {
                var disableBtnTemplate = $('<span>').addClass('custom-tutorial-disable').html(this.options.disableClickLabel);
            }
            if (this.options.dissmisBtn) {
                var dissmisBtnTemplate = $('<span>').addClass('custom-tutorial-close').html(this.options.dissmisClickLabel);
            }

            var tutorial_content = this.element.attr('data-content');
            
            this.element
                    .append(
                        $('<div>')
                            .addClass('custom-tutorial-wrapper')
                            .append(
                                $('<div>')
                                    .addClass('custom-tutorial-header')
                                    .text('Step ' + this.getStep() + ' / ' + this.getTotalTooltips())
                            )
                            .append(
                                $('<span>').text(tutorial_content)
                            )
                            .append(
                                $('<div>')
                                    .addClass('custom-tutorial-footer').html(disableBtnTemplate).append(dissmisBtnTemplate)
                            )
                    );
            this.element.focus();
        },
        triggerTemplate: function () {
            return '<div class="pulsateing_ring_wrapper"><div class="pulsateing_ring_content">?</div><div class="pulsateing_ring"></div></div>';
        }

    });
});