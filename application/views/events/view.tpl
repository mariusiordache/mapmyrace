{include file='web/includes/barebones-header.tpl'}
{include file='web/includes/header.tpl'}

<script type="text/javascript">
    var EVENT = {$event|@json_encode};
    var COURSES = {$courses|@json_encode};
    var MYCOURSES = {$mycourses|@json_encode};
</script>

<script type="backbone/template" id="course_table_view">
    <td><input type="checkbox" name="course_id[]" value="<%= id %>" /></td>
    <td><%= position %></td>
    <td width="40"><img src="<%= user.profile_pic_url %>" /></td>
    <td><%= user.username %></td>
    <td><%= date_created %></td>
    <td><%= Math.round(length / 10) / 100 %> km</td>
    <td><%= duration_string %></td>
    <td><% if (user_id == {$user_id}) { %><a href="javascript:" class="delete">Sterge</a><% } %></td>
</script>

<script type="backbone/template" id="my_course_table_view">
    <td><%= name %></td>
    <td><%= date_created %></td>
    <td><%= Math.round(length / 10) / 100 %> km</td>
    <td><%= duration_string %></td>
    <td><a href="javascript:" class="add">Adauga</a></td>
</script>

<div class="container">

    <h2>{$event.name}</h2>
    
    <div class="row">
        <div class="col-lg-4">
            <div class="row">
                <div class="col-lg-2">
                <img src="{$event.user.profile_pic_url}" />
                </div>
                <div class="col-lg-10">
                    Organizator:  {$event.user.username}<br />
                    Data Eveniment: {$event.date_created|date_format:"%B %e, %Y"}
                </div>
            </div>
        </div>
                
        <div class="col-lg-8">
            <a href="/events/map/{$event.id}" class="btn btn-danger">Vezi cursa</a>
        </div>
    </div>
        
    <div style="clear:both"></div>

    
    
    <p style="margin-top:30px;">Traseele inscrise la acest eveniment</p>
    
    <table id="eventCourses" class="table table-striped table-hover">
        <thead>
            <tr>
                <th width="10">&nbsp;</th>
                <th width="10">#Rank</th>
                <th colspan="2">User</th>
                <th>Data Traseu</th>
                <th>Lungime</th>
                <th>Duration</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>

    
    <button id="compareBtn" class="eventCourses btn dsbl btn-primary" disabled="disabled">Vezi doar selectate</button>
    
    
    <hr />
    <h3>Traseele tale</h3>
    <p>Alege de mai jos unul dintre traseele pe care vrei sa il inscrii in acest eveniment</p>
    
    <table id="myCourses" class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>Data Traseu</th>
                <th>Lungime</th>
                <th>Duration</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</div>


{include file='web/includes/footer.tpl'}