{% if(empty($entries)) %}

	<div class="mako-empty mako-title">No log entries...</div>

{% else %}

	<p><span class="mako-title">Log entries</span></p>

	<table class="mako-table mako-table-2c">
		<tr>
			<th>Type</th>
			<th>Message</th>
		</tr>

		{% foreach($entries as $entry) %}

			<tr>
				<td class="mako-log-{{$level_helper($entry['level'])}}">{{strtoupper($level_helper($entry['level']))}}</td>
				<td>{{print_r($entry['message'], true)}}</td>
			</tr>

		{% endforeach %}

	</table>

{% endif %}