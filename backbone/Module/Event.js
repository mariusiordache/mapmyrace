define([
    'marionette',
    '/backbone/Collection/user/CourseCollection.js',
    '/backbone/Model/user/Course.js',
], function (Marionette, CourseCollection, Course) {

    var CourseItemView = Marionette.ItemView.extend({
        tagName: 'tr',
        template: _.template($('#course_table_view').html()),
        initialize: function (options) {
            this.model.set('position', options.childIndex + 1);
        },
        deleteItem: function () {
            App.mycourses.collection.add(this.model);
            App.courses.collection.remove(this.model);
        },
        events: {
            'click a.delete': 'deleteItem',
            'change input[type=checkbox]': 'toggleCourse'
        },
        toggleCourse: function (e) {
            var checked = $(e.currentTarget).is(':checked');
            if (checked) {
                App.selectedCourses.add(this.model);
            } else {
                App.selectedCourses.remove(this.model);
            }
        }
    });
    
    var MyCourseItemView = Marionette.ItemView.extend({
        tagName: 'tr',
        template: _.template($('#my_course_table_view').html()),
        events: {
            'click a.add': 'addItem'
        },
        addItem: function (e) {
            App.mycourses.collection.remove(this.model);
            App.courses.collection.add(this.model);
        }
    });

    
     $('#compareBtn').on('click', function() {
         window.open('dashboard/map?course_ids=' + App.getSelectedIds().join(','),'_blank');
     });
     


    var CourseCollectionView = Marionette.CollectionView.extend({
        childView: CourseItemView,
        el: '#eventCourses tbody',
        childViewOptions: function (model, index) {
            return {
                childIndex: index
            }
        }
    });

    var App = new Marionette.Application();

    App.getSelectedIds = function () {
        var ids = new Array();
        App.selectedCourses.each(function (model) {
            ids.push(model.get('id'));
        });
        return ids;
    };
    
    App.courses = new CourseCollectionView();
    App.mycourses = new CourseCollectionView({
        el: '#myCourses tbody',
        childView: MyCourseItemView,
    });

    App.addInitializer(function (options) {

        App.mycourses.collection = new CourseCollection(MYCOURSES);
        App.courses.collection = new CourseCollection(COURSES);
        
        App.courses.collection.bind('add', function(model){
            $.post('/dashboard/add_course_to_event/' + EVENT.id + '/' + model.get('id'));
        });
        
        App.courses.collection.bind('remove', function(model){
            $.post('/dashboard/delete_course_to_event/' + EVENT.id + '/' + model.get('id'));
        });
        
        App.courses.collection.comparator = function (model) {
            return model.get('duration');
        }
        
        App.mycourses.collection.comparator = function (model) {
            return model.get('duration');
        }

        App.courses.render();
        App.mycourses.render();
        
        this.selectedCourses = new CourseCollection();
        this.selectedCourses.bind("change reset add remove", function() {
            if (!App.selectedCourses.length) {
                $('.btn.dsbl').attr('disabled', true);
            } else {
                $('.btn.dsbl').attr('disabled', false);
            }
        });
        
    });

    return App;
})