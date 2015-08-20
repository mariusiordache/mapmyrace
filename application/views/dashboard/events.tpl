{include file='web/includes/barebones-header.tpl'}
{include file='web/includes/header.tpl'}


<div class="container">
    
    <h2>Lista Evenimente</h2>
    <p style="margin-bottom:20px;">Aici poti vedea evenimtele tale, ale prietenilor si cele publice. Daca doresti sa faci un eveniment nou, trebuie sa incepi de la lista cu trasee.</p>
    
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        {foreach from=$events key=token item=eventgroup}
        <li role="presentation"  class="{if $token == 'mine'}active{/if}" >
            <a href="#{$token}" aria-controls="{$token}" role="tab" data-toggle="tab">{$eventgroup.label}</a>
        </li>
        {/foreach}
    </ul>
    
    <!-- Tab panes -->
    <div class="tab-content">
        {foreach from=$events key=token item=eventgroup}
            <div role="tabpanel" class="tab-pane {if $token == 'mine'}active{/if}" id="{$token}">

                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Organizator</th>
                            <th>Nume</th>
                            <th>Trasee</th>
                            <th>Best Time</th>
                            <th>Lungime</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    {foreach from=$eventgroup.data item=event}
                        <tr>
                            <td>#{$event.id}</td>
                            <td>{$event.date_created|date_format:"%B %e, %Y"}</td>
                            <td><img src="{$event.user.profile_pic_url}" /> {$event.user.username}</td>
                            <td>{$event.name}</td>
                            <td>{if !empty($event.num_courses)}{$event.num_courses}{/if}</td>
                            <td>{if !empty($event.best_time)}{$event.best_time|formatInterval}{/if}</td>
                            <td>{if !empty($event.avg_length)}{$event.avg_length}{/if} km</td>
                            <td><a href="/events/view/{$event.id}">Vezi</a></td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>
        {/foreach}

    </div>


</div>



{include file='web/includes/footer.tpl'}