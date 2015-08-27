{include file="web/includes/user-profile-header.tpl"}

<main id="content" role="main" >
    <div class="container-fluid">
        <div class='profile-center-image'>
            <img alt="" src="{$user.profile_pic_url|default}">

            <form id="fileupload" action="upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="profile">
            </form>

        </div>
        {if $success}
            <div class="email-not-confirmed"><p class="pull-left">Password was changed!</p>  <img class="pull-right close-message" src="/assets/dashboardv2/close_message.svg" /></div>
            {/if}
        <div class='name-status'>
            <h3 data-user="name">{$user.name}</h3>
            <p>
                <span class="roles">
                    {foreach from=$user.roles item=role}
                        {$role} 
                    {/foreach}
                </span>
                -
                <span class="role-status">
                    Freelancer
                </span>
            </p>
        </div>
        <div class="container-fluid profile-body">
            <div class="col-md-6">
                <div class="form-container">
                    <h3>Personal info</h3>
                    <form>
                        <p>
                            <span class="fake-input" data-user="name">{$user.name}</span>
                            <input class="form-control clean-input" name="name" value="{$user.name}" /><img class="edit-input" src="/assets/images/edit.svg">
                        </p>
                        <p>
                            <span class="fake-input" data-user="email">{$user.email}</span>
                            <input class="form-control clean-input" name="email" value="{$user.email}" /><img class="edit-input" src="/assets/images/edit.svg">
                        </p>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <h3>Account info</h3>
                <div class="change-password">
                    <a href="/account/changepassword" class="flat-primary-btn changePasswordModal">change password</a>
                </div>
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
</main>
