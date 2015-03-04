<p><span class="mako-title">Session</span></p>

<p><span class="mako-subtitle">Session id:</span></p>

<p>{{$id}}</p>

<p><span class="mako-subtitle">Session data:</span></p>

<table class="mako-table mako-table-2c">
	<tr>
		<th>Key</th>
		<th>Value</th>
	</tr>

	{% foreach($data as $key => $value) %}

		<tr>
			<td>{{$key}}</td>
			<td>{{print_r($value, true)}}</td>
		</tr>

	{% endforeach %}

</table>