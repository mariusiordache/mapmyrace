{include file='web/includes/barebones-header.tpl'}
{include file='web/includes/header.tpl'}

<script type="text/javascript">
    var COURSES = {$courses|@json_encode};
</script>

<script type="backbone/template" id="course_table_view">
    <td>#<%= id %></td>
    <td><%= name %></td>
    <td><%= date_created %></td>
    <td><%= Math.round(length / 10) / 100 %> km</td>
    <td><%= duration_string %></td>
    <td><a href="/dashboard/map?course_ids=<%= id %>" class="view">View</a></td>
    <td><a href="javascript:" class="delete">Delete</a></td>
</script>

<div class="trasee-tabs container">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#trasee" aria-controls="home" role="tab" data-toggle="tab">Trasee</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="trasee">

            <div class="row" id="courses">
                <div class='profile-center-image'>
                    <img alt="" src="/assets/images/plus.svg">

                    <form id="fileupload" action="upload.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="file" multiple="true">
                    </form>

                </div>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nume</th>
                        <th>Data</th>
                        <th>Lungime</th>
                        <th>Durata</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
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