{include file='web/includes/barebones-header.tpl'}
{include file='web/includes/header.tpl'}

<script type="text/javascript">
    var FRIENDSHIP = {$friendship|@json_encode};
</script>

<script type="backbone/template" id="user_table_view">
    <td><img src="<%= profile_pic_url %>" /></td>
    <td><%= username %></td>
    <td><%= name %></td>
    <td><%=timestamp %></td>
    <td><%= email %></td>
</script>



<div class="trasee-tabs container">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#friends" aria-controls="friends" role="tab" data-toggle="tab">Friends</a></li>
        <li role="presentation"><a href="#pending-requests" aria-controls="pending-requests" role="tab" data-toggle="tab">Pending Requests</a></li>
        <li role="presentation"><a href="#sent-requests" aria-controls="sent-requests" role="tab" data-toggle="tab">Requests Sent</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="friends">
            
            <h2>Search Friend</h2>
            <div class="row">
                <div class="col-md-4">
                    <input type="text" data-provide="typeahead"  name="nume" placeholder="Nume" id="bloodhound" value="" class="form-control typeahead" />
                </div>
                <div class="col-md-2">
                    <a class="btn btn-default" href="javascript:;">Add</a>
                </div>
            </div>
            
            <h2>Friends</h2>
            <p>Here is a list of all your friends</p>
            
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Friends Since</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane" id="pending-requests">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Friends Since</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane" id="sent-requests">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Friends Since</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                   
                </tbody>
            </table>
        </div>
    </div>
</div>