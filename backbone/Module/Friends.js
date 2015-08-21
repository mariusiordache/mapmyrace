define([
    'marionette',
    "socketio",
    '/backbone/Collection/user/FriendshipCollection.js',
    '/backbone/Model/user/Friendship.js',
    'bloodhound',
    'typeaheadjs'
], function (Marionette, io, FriendshipCollection, Friendship, Bloodhound) {

    var NoChildrenView = Marionette.ItemView.extend({
        tagName: 'tr',
        template: _.template($('#user_empty_view').html())
    });
    
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
        emptyView: NoChildrenView,
        initialize: function() {
            this.collection.bind('change reset add remove start', this.count, this);
        },
        count: function() {
            var tab_id = this.$el.parents('.tab-pane').attr('id');
            $('ul[role=tablist] a[href=#' + tab_id + '] .counter').text(this.collection.length)
        }
    })
    
    var App = new Marionette.Application();
    App.socket = io.connect(PAGE_DATA.socketio.domain + ":" + PAGE_DATA.socketio.port);
    
    App.socket.emit("register_channel", {channel: 'friendship'});

    App.socket.on('friendship', function (data) {
        switch (data.type) {
            case "delete":
                App.friends.collection.remove({id: data.id})
                App.pending_requests.collection.remove({id: data.id})
                App.sent_requests.collection.remove({id: data.id})
                break;
            case "save":
                // friendship was accepted
                if (data.data.accepted == "1") {
                    var model = App.sent_requests.collection.filter(function(fr) {
                        return fr.get("id") === parseInt(data.data.id) || fr.get("id") === data.data.id + ""
                    });
                    if (model) {
                        App.sent_requests.collection.remove(model);
                        App.friends.collection.add(model);
                        $('[aria-controls=friends]').trigger('click');
                    }
                }
                
                if (data.data.target_user_id == USER_DATA.id && data.data.friendship) {
                    // new friendship received
                    App.pending_requests.collection.add(data.data.friendship);
                    $('[aria-controls=pending-requests]').trigger('click');
                }
        }
    });
     
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
        
        this.friends.collection.reset(FRIENDSHIP.friends);
        this.pending_requests.collection.reset(FRIENDSHIP.pending);
        this.sent_requests.collection.reset(FRIENDSHIP.sent);

        this.friends.collection.comparator = function (model) {
            return model.get('id');
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
                return '<div><img width="30" src="' + user.profile_pic_url + '" /><strong>' + user.email + '</strong><img style="width:25px; float:right; cursor:pointer" src="/assets/images/addfriend.png"></div>'
            }
        }
    }).on('typeahead:selected', function (ev, item) {
        
        $('[aria-controls=sent-requests]').trigger('click')
        
        item.timestamp = moment().format("YYYY-MM-DD HH:mm:ss");
        item.target_user_id = item.user_id;
        item.request_user_id = USER_DATA.id;
        App.sent_requests.collection.create(item, {wait: true});
        
        setTimeout(function() {
            $('#bloodhound.typeahead').val('');
        }, 500);
    });

    return App;
})