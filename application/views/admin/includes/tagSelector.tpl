<div class="tagSelector row-fluid">
	<div class="span7">
		<input id="{$id|default:"tagAssocSelector"}" type="text" class="tagSelectorInput" data-source="/admin/tags/ajax_list" data-add-url="/admin/tags/ajax_add" placeholder="{$placeholder|default:"start typing..."}" {if isset($data_holder)}data-holder="{$data_holder}"{/if} {if isset($data_field)}data-field="{$data_field}"{/if} />								
	</div>
	<div class="searchDrop span5">
		<select class="tagSelectorDropdown" name="tagType" id="drop-{$id|default:"tagAssocSelector"}" data-placeholder="{if isset($shortLabel)}add...{else}Add as new ...{/if}">
			<option value="">{if isset($shortLabel)}add...{else}Add as new ...{/if}</option>
			{foreach from=$tagTypes key=tagTypeIndex item=tagType}
				<option value="{$tagType.id}">{$tagType.label}</option>
			{/foreach}
		</select>
	</div>
	<div class="span5 tagSelectorLoading" style="display: none;">
		<span>Loading...</span>
	</div>
	<!--
	{*
	<div class="btn-group" style="position: absolute; top: 0; right: 0; max-height: 100%; z-index: 99; border-radius: 2px;">
		<a class="buttonS bDefault" data-toggle="dropdown" style="width: {if isset($shortLabel)}50{else}100{/if}px;">{if isset($shortLabel)}add...{else}Add as new ...{/if}<span class="caret"></span></a>
		<ul role="menu" class="dropdown-menu tagSelectorMenu">
			<li><input type="text" placeholder="type to filter" /></li>
			{foreach from=$tagTypes key=tagTypeIndex item=tagType}
				<li><a href="javascript:void(0);" data-value="{$tagType.id}">{$tagType.label}</a></li>
			{/foreach}
		</ul>
	</div>				
	*}
	-->
</div>