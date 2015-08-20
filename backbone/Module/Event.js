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
            this.model.destroy({wait: true});
            this.remove();
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

    App.addInitializer(function (options) {

        App.courses.collection = new CourseCollection(COURSES);
        App.courses.collection.comparator = function (model) {
            return model.get('duration');
        }

        App.courses.render();
        
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