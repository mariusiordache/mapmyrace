define(['backbone'], function (Backbone) {
    return Backbone.Model.extend({
        url: function () {
            var col = this.collection !== undefined ? this.collection.tmecollection : this.get('tmecollection');
            
            if (!col) {
                console.log('You must set a tmecollection before saving this object');
                return false;
            }
            
            return "/ajax/resource/" + col + ( this.get("id") ? '/' + this.get("id") : '');
        },
        next: function (filters) {
            if (this.collection) {
                if (filters) {
                    this.collection.reset(this.collection.where(filters));
                }
                return this.collection.at(this.collection.indexOf(this) + 1);
            }
        },
        prev: function (filters) {
            if (this.collection) {
                if (filters) {
                    this.collection.reset(this.collection.where(filters));
                }
                return this.collection.at(this.collection.indexOf(this) - 1);
            }
        }
    });
});