<script type="text/template" id="datepicker-template">
	<div class="datepicker">
		<div class="row-fluid columns">
			<div class="span3 years column">
				<ul>
				<% for(var i=startYear; i<=endYear; i++) { %>
					<li class="option <% if(selected.year != 0 && i == selected.year) { print('active') } %>"><a class="optionLink" data-value="<%= i %>" href="#"><%= i %></a></li>
				<% } %>
				</ul>
			</div>
			<div class="span6 months column">
				<ul>
				{section loop=12 name=months}
					<li class="option <% if(selected.month != 0 && {$smarty.section.months.iteration} == selected.month) { print('active') } %>"><a class="optionLink" data-value="{$smarty.section.months.iteration}" href="#">{"date.Month`$smarty.section.months.iteration`"|kms_lang}</a></li>
				{/section}
				</ul>
			</div>
			<div class="span3 days column">
				<ul>
				{section loop=31 name=days}
					<li class="option <% if(selected.day != 0 && {$smarty.section.days.iteration} == selected.day) { print('active') } %>"><a class="optionLink" data-value="{$smarty.section.days.iteration}" href="#">{$smarty.section.days.iteration}</a></li>
				{/section}
				</ul>
			</div>
		</div>
		<div class="datepickerToolbar">
			<button type="button" class="btn btn-warning btn-small ok">{"Done"|kms_lang}</button>
			<button type="button" class="btn btn-cancel btn-small cancel">{"Cancel"|kms_lang}</button>
		</div>
	</div>
</script>