define([
    'marionette',
    '/backbone/Collection/user/CourseCollection.js',
    '/backbone/Model/user/Course.js',
], function (Marionette, CourseCollection, Course) {

    var NoChildrenView = Marionette.ItemView.extend({
        tagName: 'tr',
        template: _.template($('#course_empty_view').html())
    });
    
    var NoSuggestionView = Marionette.ItemView.extend({
        tagName: 'tr',
        template: _.template($('#suggested_course_empty_view').html())
    });
    
    var CourseItemView = Marionette.ItemView.extend({
        tagName: 'tr',
        template: _.template($('#course_table_view').html()),
        initialize: function() {
            this.model.on('change', this.render, this);
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
            
            App.highlightPossibleCourses();
        },
        onRender: function() {
            if (this.model.get('disabled')) {
                this.$el.addClass('disabled');
            } else {
                this.$el.removeClass('disabled');
            }
        }
    });

    var SuggestionItemView = CourseItemView.extend({
        tagName: 'tr',
        template: _.template($('#suggested_course_table_view').html())
    });
    
    var CourseCollectionView = Marionette.CollectionView.extend({
        childView: CourseItemView,
        emptyView: NoChildrenView,
        el: '#trasee tbody',
        collection: new CourseCollection()
    });
    
    var SuggestedCollectionView = Marionette.CollectionView.extend({
        childView: SuggestionItemView,
        emptyView: NoSuggestionView,
        el: '#friendstrasee tbody'
    });

    var App = new Marionette.Application();

    App.courses = new CourseCollectionView({
    });
    
    App.suggestedCourses = new SuggestedCollectionView({
    });
    
    App.isFirstSuggestion = false;
    
    App.highlightPossibleCourses = function() {
        
        if (App.selectedCourses.length > 0) {
            
            var first = App.selectedCourses.first();
            
            if (!App.isFirstSuggestion) {
                $('#loading-overlay').show();
                $.get('/dashboard/suggest/' + first.get('id'), function(resp){
                    
                    App.suggestedCourses.collection = new CourseCollection(resp.friends);
                    App.suggestedCourses.render();
                    
                    var possibleCourses = new CourseCollection(resp.me);
                    App.courses.collection.each(function(course){
                        if (first.get('id') != course.get('id') && !possibleCourses.findWhere({id: course.get('id')})) {
                            course.set('disabled', true);
                        }
                    });
                    
                    $('#loading-overlay').hide();
                });

            }
            
            App.isFirstSuggestion = true;
        } else {
            App.courses.collection.each(function(course){
                course.set('disabled', false);
            });
            
            App.suggestedCourses.collection = new CourseCollection();
            App.suggestedCourses.render();
            
            App.isFirstSuggestion = false;
        }
    };

    App.getSelectedIds = function () {
        var ids = new Array();
        App.selectedCourses.each(function (model) {
            ids.push(model.get('id'));
        });
        return ids;
    };

    $('#compareBtn').on('click', function () {
        window.open('dashboard/map?course_ids=' + App.getSelectedIds().join(','), '_blank');
    });

    $('#createEventBtn').on('click', function () {
        $('input[name=course_ids]').val(App.getSelectedIds().join(','));
        $('#createEventModal').modal('show')
    });


    App.addInitializer(function (options) {

        this.selectedCourses = new CourseCollection();
        this.selectedCourses.bind("change reset add remove", function () {
            if (!App.selectedCourses.length) {
                $('.btn.dsbl').attr('disabled', true);
            } else {
                $('.btn.dsbl').attr('disabled', false);
            }
        });

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
                alert('Poti uploada doar fisiere .gpx');
                return false;
            }

            $('#loading-overlay').show();

            data.process().done(function () {
                data.submit();
            });

        },
        done: function (e, data) {
            var that = $(this).data('blueimp-fileupload') || $(this).data('fileupload');

            if (data.result.success == true) {
                App.courses.collection.add(new Course(data.result.data));
            } else {
                $('#myErrorMessage').html(data.result.errors.join("<br />")).show(200);
                setTimeout(function() {
                    $('#myErrorMessage').hide(200);
                }, 2000);
            }
        }
    });

    return App;
})