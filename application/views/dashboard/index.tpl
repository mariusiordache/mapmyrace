{include file='web/includes/barebones-header.tpl'}
{include file='web/includes/header.tpl'}

<script type="text/javascript">
    var COURSES = {$courses|@json_encode};
</script>

<script type="backbone/template" id="course_table_view">
    <td><input type="checkbox" <% if (typeof(disabled) != "undefined" && disabled == true) { %>disabled="disabled"<% } %> name="course_id[]" value="<%= id %>" /></td>
    <td>#<%= id %></td>
    <td><%= name %></td>
    <td><%= date_created %></td>
    <td><%= location %></td>
    <td><%= Math.round(length / 10) / 100 %> km</td>
    <td><%= duration_string %></td>
    <td><a href="/dashboard/map?course_ids=<%= id %>" class="view">View</a></td>
    <td><a href="javascript:" class="delete">Delete</a></td>
</script>

<script type="backbone/template" id="suggested_course_table_view">
    <td><input type="checkbox" name="course_id[]" value="<%= id %>" /></td>
    <td width="40"><img src="<%= user.profile_pic_url %>" /></td>
    <td><%= user.username %></td>
    <td><%= name %></td>
    <td><%= date_created %></td>
    <td><%= location %></td>
    <td><%= Math.round(length / 10) / 100 %> km</td>
    <td><%= duration_string %></td>
</script>

<script type="backbone/template" id="course_empty_view">
    <td colspan="8" style="text-align:center">Momentan nu ai uploadat nici un traseu</td>
</script>

<script type="backbone/template" id="suggested_course_empty_view">
    <td colspan="8" style="text-align:center">Nu exista nici o sugestie.</td>
</script>

<div class="container">
    <!-- Nav tabs -->
    <h2>Traseele tale</h2>
    
    <div class="alert alert-danger" id="myErrorMessage" role="alert" style="display: none;"></div>
    <!-- Tab panes -->

    <div class="row" id="courses">
        <div class="col-md-6">
            <p>Aici poti vedea toate traseele tale</p>
        </div>
        <div class="col-md-6">
            <div class='profile-center-image'>
                <img alt="" src="/assets/images/plus.svg">

                <form id="fileupload" action="upload.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="file" multiple="true">
                </form>

            </div>
        </div>
    </div>

    <table class="table table-striped table-hover" id="trasee">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>ID</th>
                <th>Nume</th>
                <th>Data</th>
                <th>Location</th>
                <th>Lungime</th>
                <th>Durata</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    
    <button id="compareBtn" class="btn dsbl btn-primary" disabled="disabled">Compara</button>
    <button id="createEventBtn" class="btn dsbl btn-danger" disabled="disabled">Create Event</button>
    
    
    <!-- Nav tabs -->
    <h2>Traseele prietenilor</h2>

    <p>Alege unul din trasele de mai sus, si aici vei putea vedea traseele ale prietenilor tai din aceeasi zona.</p>

    <table class="table table-striped table-hover" id="friendstrasee">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th colspan="2">User</th>
                <th>Nume</th>
                <th>Data</th>
                <th>Location</th>
                <th>Lungime</th>
                <th>Durata</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>


<!-- Modal checkout-->
<div id="createEventModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form class="modal-dialog" name="newev" action="/dashboard/create_event" method="POST">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Event Nou</h4>
                </div>
                <input type="hidden" name="course_ids" value="" />
                <div class="modal-body">
                    <div class="form-group">
                        <form class="form-horizontal">
                            <fieldset>  
                                <!-- Text input-->
                                <div class="form-group"> 
                                    <div class="col-md-12">
                                        <div class="col-md-10">
                                            <label>Nume Eveniment</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>
                                                <input type="checkbox" name="public" />
                                                Public
                                            </label>
                                        </div>

                                        <input id="dependencyHash" name="textinput" type="text" placeholder="Nume Eveniment" class="form-control input-md">
                                    </div>
                                </div> 
                            </fieldset>
                        </form> 
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Anuleaza</button>
                    <input type="submit" class="btn btn-primary" value="Adauga" />
                </div>
        </div>
    </form>
</div>


{literal}
    <!-- jquery file upload templates (different templating engine -->

    <script id="template-upload" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
        <tr class="template-upload fade">
        <td>
        <span class="preview"></span>
        </td>
        <td>
        <p class="name">{%=file.name%}</p>
        {% if (file.error) { %}
        <div><span class="label label-important">Error</span> {%=file.error%}</div>
        {% } %}
        </td>
        <td>
        <p class="size">{%=o.formatFileSize(file.size)%}</p>
        {% if (!o.files.error) { %}
        <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
        {% } %}
        </td>
        </tr>
        {% } %}
    </script>

    <!-- The template to display files available for download -->
    <script id="template-download" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
        <tr class="template-download fade">
        <td>
        <span class="preview">
        {% if (file.thumbnail_url) { %}
        <a href="{%=file.url%}" title="{%=file.name%}" class="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
        {% } %}
        </span>
        </td>
        <td>
        <p class="name">
        <a href="{%=file.url%}" title="{%=file.name%}" class="{%=file.thumbnail_url?'gallery':''%}" download="{%=file.name%}">{%=file.name%}</a>
        </p>
        {% if (file.error) { %}
        <div><span class="label label-important">Error</span> {%=file.error%}</div>
        {% } %}
        </td>
        <td>
        <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        </tr>
        {% } %}
    </script>

{/literal}

{include file='web/includes/footer.tpl'}