define(['/backbone/Collection/TmeCollection.js', '/backbone/Model/user/Course.js'], function(TmeCollection, Course){
    return TmeCollection.extend({
        model: Course,
        tmecollection: 'course'
    });
});