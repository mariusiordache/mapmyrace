{if $position="bottomRight"}
	<div style="position: fixed; bottom: 10px; right: 10px; z-index: 100;">
		<button type="submit" class="buttonL bBlue" data-loading-text="saving...">			
		<span class="icon-checkmark-3"></span><span>{$submitLabel|default:"Save"}</span>
		</button>
	</div>
{else}
	<button type="submit" class="buttonL bBlue" data-loading-text="saving...">			
		<span class="icon-checkmark-3"></span><span>{$submitLabel|default:"Save"}</span>
	</button>
{/if}