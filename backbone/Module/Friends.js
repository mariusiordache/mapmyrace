define([
    'marionette',
    '/backbone/Collection/user/FriendshipCollection.js',
    '/backbone/Model/user/Friendship.js'
], function (Marionette, FriendshipCollection, Friendship) {

    var UserTableView = Marionette.ItemView.extend({
        tagName: 'tr',
        template: _.template($('#user_table_view').html())
    });

    var FriendshipCollectionView = Marionette.CollectionView.extend({
        childView: UserTableView
    });

    var App = new Marionette.Application();
    
    App.friends = new FriendshipCollectionView({
        el: '#friends tbody',
        collection: new FriendshipCollection()
    });
    
    App.pending_requests = new FriendshipCollectionView({
        el: '#pending-requests tbody',
        collection: new FriendshipCollection()
    });
    
    App.sent_requests = new FriendshipCollectionView({
        el: '#sent-requests tbody',
        collection: new FriendshipCollection()
    });

     App.addInitializer(function (options) {
         for (i in FRIENDSHIP.friends) {
             App.friends.collection.add(new Friendship(FRIENDSHIP.friends[i]));
         }
         
         for (i in FRIENDSHIP.pending) {
             App.pending_requests.collection.add(new Friendship(FRIENDSHIP.pending[i]));
         }
         
         for (i in FRIENDSHIP.sent) {
             App.sent_requests.collection.add(new Friendship(FRIENDSHIP.sent[i]));
         }
         
         App.friends.render();
         App.pending_requests.render();
         App.sent_requests.render();
     });

console.log(App)
    return App;
});
