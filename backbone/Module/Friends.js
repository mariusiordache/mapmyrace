define([
    'marionette',
    '/backbone/Collection/user/FriendshipCollection.js',
    '/backbone/Model/user/Friendship.js',
    'bloodhound',
    'typeaheadjs'
], function (Marionette, FriendshipCollection, Friendship, Bloodhound) {

    var UserTableView =  Marionette.ItemView.extend({
        tagName: 'tr',
        deleteItem: function() {
            this.model.destroy({wait: true});
            this.remove();
        }
    });
    
    var FriendUserTableView = UserTableView.extend({
        template: _.template($('#user_table_view').html()),
        events: {
            'click a.unfriend': 'deleteItem'
        }
    });
    
    var ReqSentUserTableView = UserTableView.extend({
        template: _.template($('#user_sent_table_view').html()),
        events: {
            'click a.cancel': 'deleteItem'
        }
    });
    
    var PendingUserTableView = UserTableView.extend({
        template: _.template($('#user_pending_table_view').html()),
        events: {
            'click a.reject': 'deleteItem',
            'click a.accept': 'acceptFriendShip',
        },
        acceptFriendShip: function() {
            this.model.save(
                {
                    accepted: 1
                }, 
                {
                    success: function (model, response) {
                        App.pending_requests.collection.remove(model);
                        App.friends.collection.add(model);
                    },
                    error: function (model, response) {
                        alert("An error ocurred!");
                    }
                }, 
                {
                    wait: true
                }
            );
        }
    });
    
    var UserCollectionView = Marionette.CollectionView.extend({
        initialize: function() {
            this.collection.bind('change reset add remove', this.count, this);
        },
        count: function() {
            var tab_id = this.$el.parents('.tab-pane').attr('id');
            $('ul[role=tablist] a[href=#' + tab_id + '] .counter').text(this.collection.length)
        }
    })
    
    var App = new Marionette.Application();
    App.friends = new UserCollectionView({
        childView: FriendUserTableView,
        el: '#friends tbody',
        collection: new FriendshipCollection()
    });
    
    App.pending_requests = new UserCollectionView({
        childView: PendingUserTableView,
        el: '#pending-requests tbody',
        collection: new FriendshipCollection()
    });
    
    App.sent_requests = new UserCollectionView({
        childView: ReqSentUserTableView,
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
    }).on('typeahead:selected', function (ev, item) {
        item.timestamp = moment().format("YYYY-MM-DD HH:mm:ss");
        item.target_user_id = item.user_id;
        item.request_user_id = USER_DATA.id;
        App.sent_requests.collection.create(item, {wait: true});
        
        $('#bloodhound.typeahead').val('');
    });

    return App;
})