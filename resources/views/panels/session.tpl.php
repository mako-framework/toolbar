<div class="mako-panel-header">
	<span class="mako-title">Session</span>
</div>

<div class="mako-panel-content">

	<b>Session id:</b> <span class="mako-label">{{$id}}</span>

	<hr>

	<p><span class="mako-subtitle">Session data:</span></p>

	<table class="mako-table mako-table-2c">
		<tr>
			<th>Key</th>
			<th>Value</th>
		</tr>

		{% foreach($data as $key => $value) %}

			<tr>
				<td>{{$key}}</td>
				<td>{{$dump($value)}}</td>
			</tr>

		{% endforeach %}

	</table>

</div>
