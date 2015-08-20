{include file='web/includes/barebones-header.tpl'}
{include file='web/includes/header.tpl'}

<script type="text/javascript">
    var COURSES = {$courses|@json_encode};
</script>

<script type="backbone/template" id="course_table_view">
    <td><input type="checkbox" name="course_id[]" value="<%= id %>" /></td>
    <td><%= position %></td>
    <td width="40"><img src="<%= user.profile_pic_url %>" /></td>
    <td><%= user.username %></td>
    <td><%= date_created %></td>
    <td><%= Math.round(length / 10) / 100 %> km</td>
    <td><%= duration_string %></td>
    <td><a href="javascript:">Add</a></td>
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
            <a href="/events/map/{$event.id}" class="btn btn-danger" disabled="disabled">Vezi cursa</a>
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

    
    <button id="compareBtn" class="btn dsbl btn-primary" disabled="disabled">Vezi doar selectate</button>
    
</div>


{include file='web/includes/footer.tpl'}