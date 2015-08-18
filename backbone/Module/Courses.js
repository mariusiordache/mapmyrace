define([
    'marionette',
    '/backbone/Collection/user/CourseCollection.js',
    '/backbone/Model/user/Course.js',
], function (Marionette, CourseCollection, Course) {

    var CourseItemView =  Marionette.ItemView.extend({
        tagName: 'tr',
        template: _.template($('#course_table_view').html()),
        deleteItem: function() {
            this.model.destroy({wait: true});
            this.remove();
        },
        events: {
            'click a.delete': 'deleteItem'
        }
    });
    
    var CourseCollectionView = Marionette.CollectionView.extend({
        childView: CourseItemView,
        el: '#trasee tbody',
        collection: new CourseCollection()
    });
    
    var App = new Marionette.Application();
    
    App.courses = new CourseCollectionView({
        
    });
    
    App.addInitializer(function (options) {
        
        for (i in COURSES) {
            App.courses.collection.add(new Course(COURSES[i]));
        }
        
        App.courses.render();
    });
    
    $('#fileupload').fileupload({
        url: '/dashboard/upload',
        autoUpload: true,
        multiple: true,
        maxFileSize: 50000000000,
        acceptFileTypes: /(\.|\/)(gpx)$/i,
        add: function (e, data) {
            var fileType = data.files[0].name.split('.').pop(), allowdtypes = 'gpx';
            if (allowdtypes.indexOf(fileType) < 0) {
                alert('Invalid file type, aborted');
                return false;
            }
            
            $('#loading-overlay').show();

            data.process().done(function () {
                data.submit();
            });

        },
        done: function (e, data) {
            var that = $(this).data('blueimp-fileupload') || $(this).data('fileupload');
            App.courses.collection.add(new Course(data.result.data));
        }
    });
   
    return App;
})