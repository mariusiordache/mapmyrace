define([
    'marionette',
    '/backbone/Collection/user/FriendshipCollection.js',
    '/backbone/Model/user/Friendship.js',
    'bloodhound',
    'typeaheadjs'
], function (Marionette, FriendshipCollection, Friendship, Bloodhound) {

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
    // constructs the suggestion engine
    var friendsDataSource = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('email'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: '/user/search?q=%QUERY',
            wildcard: '%QUERY'
        }
    });

    friendsDataSource.initialize();

    $('#bloodhound.typeahead').typeahead({
        hint: true,
        highlight: true,
        minLength: 1,
        limit: 5
    },
    {
        displayKey: 'email',
        source: friendsDataSource.ttAdapter(),
        templates: {
            empty: [
                '<div class="empty-message">',
                'unable to find any user that match the current query',
                '</div>'
            ].join('\n'),
            suggestion: function(user){
                console.log(user)
                return '<div><img width="30" src="' + user.profile_pic_url + '" /><strong>' + user.email + '</strong></div>'
            }
        }
    });

    return App;
})