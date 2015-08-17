{include file="web/includes/barebones-header.tpl"}
<div class="container">

    <a href="{$crud_ignition_url}">Back to CrudIgnition home</a><br />
    
    <h3>Triggers, Procedures, Functions result</h3>
    
    <table class="table table-borderless">
        <thead>
            <tr>
                <th>&nbsp</th>
                <th>File</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$mysql_triggers item=data key=type}
                <tr>
                    <th colspan="3">{$type}</th>
                </tr>
                {$i=1}
                {foreach from=$data item=result key=file}
                    <tr>
                        <td>{$i}</td>
                        <td>{$file}</td>
                        <td>{if !empty($result)}{$result|@json_encode}{else}OK{/if}</td>
                    </tr>
                    {$i=$i+1}
                {/foreach}
            {/foreach}
        </tbody>
    </table>
</div>