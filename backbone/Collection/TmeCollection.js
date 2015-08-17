define(['backbone'], function (Backbone) {
    return Backbone.Collection.extend({
        fetchFiltered: function (filters, sort, offset, limit) {
            return this.fetch({data: $.merge(filters, $.param({
                    sort: sort ? sort : null,
                    offset: offset ? offset : null,
                    limit: limit ? limit : null
                }))
            });
        },
        getCount: function(filters) {
            var that = this;
            $.get('/ajax/count/' + this.tmecollection, filters, function(count){
                that.trigger('counted', count);
            });
            
            return this;
        },
        url: function() {
            return '/ajax/resource/' + this.tmecollection;
        }
    });
});