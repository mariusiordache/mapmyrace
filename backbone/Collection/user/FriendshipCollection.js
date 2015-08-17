define(['/backbone/Collection/TmeCollection.js', '/backbone/Model/user/Friendship.js'], function(TmeCollection, Friendship){
    return TmeCollection.extend({
        model: Friendship,
        tmecollection: 'friendship'
    });
});