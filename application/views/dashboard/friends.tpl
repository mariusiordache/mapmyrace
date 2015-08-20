{include file='web/includes/barebones-header.tpl'}
{include file='web/includes/header.tpl'}

<script type="text/javascript">
    var FRIENDSHIP = {$friendship|@json_encode};
</script>

<script type="backbone/template" id="user_empty_view">
    <td colspan="8" style="text-align:center">Momentan nu exista niciun utilizator in aceasta lista.</td>
</script>

<script type="backbone/template" id="user_table_view">
    <td><img src="<%= profile_pic_url %>" /></td>
    <td><%= username %></td>
    <td><%= name %></td>
    <td><%=timestamp %></td>
    <td><%= email %></td>
    <td><a href="javascript:" class="unfriend">Unfriend</a></td>
</script>

<script type="backbone/template" id="user_sent_table_view">
    <td><img src="<%= profile_pic_url %>" /></td>
    <td><%= username %></td>
    <td><%= name %></td>
    <td><%=timestamp %></td>
    <td><%= email %></td>
    <td><a href="javascript:" class="cancel">Cancel</a></td>
</script>

<script type="backbone/template" id="user_pending_table_view">
    <td><img src="<%= profile_pic_url %>" /></td>
    <td><%= username %></td>
    <td><%= name %></td>
    <td><%=timestamp %></td>
    <td><%= email %></td>
    <td><a href="javascript:" class="accept">Accept</a></td>
    <td><a href="javascript:" class="reject">Reject</a></td>
</script>

<div class="container">
    
    <h2>Prieteni</h2>
    
    <div class="row" style="margin-top: 20px; margin-bottom: 20px;">
        
        <div class="col-md-4">
            <input type="text" data-provide="typeahead"  name="nume" placeholder="Cauta Prieteni" id="bloodhound" value="" class="form-control typeahead" />
        </div>
        <div class="col-md-1">
            <img src="/assets/dashboardv2/search.svg" style="height:30px;" />
        </div>
    </div>
    
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#friends" aria-controls="friends" role="tab" data-toggle="tab">Prieteni <span class="counter">0</span></a></li>
        <li role="presentation"><a href="#pending-requests" aria-controls="pending-requests" role="tab" data-toggle="tab">Cereri primite <span class="counter">0</span></a></li>
        <li role="presentation"><a href="#sent-requests" aria-controls="sent-requests" role="tab" data-toggle="tab">Cereri trimise <span class="counter">0</span></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="friends">
            
            <h3>Prieteni</h3>
            <p>Aici este lista cu toti prietenii tai.</p>
            
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
            
            <h3>Cereri primite</h3>
            <p>Aici este lista cu toti utilizatorii care vor sa fie prietenii tai. Daca accepti un utilizator ca si prieten, acesta va putea vedea traseele tale.</p>
            
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


{include file='web/includes/footer.tpl'}