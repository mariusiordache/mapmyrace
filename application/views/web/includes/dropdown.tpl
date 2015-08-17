<div class="dropdown selectbox {$class}" id="{$id}" {if isset($style)}style="{$style}"{/if}>
	<a class="selectbox-trigger" data-toggle="dropdown">
		{$selected}
		<span class="selectbox-caret"></span>
	</a>
		<ul role="menu" class="selectbox-options dropdown-menu" id="{$id}-options" aria-labelledby="{$id}">
		{foreach from=$options key=k item=o}
			<li role="presentation" class="selectbox-option"><a tabindex="-1" role="menuitem" class="selectbox-option-link" href="#" data-value="{$k}">{$o}</a></li>						
		{/foreach}
	</ul>
</div>