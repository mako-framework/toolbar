<p><span class="mako-title">Configuration</span></p>

<p>Only the currently loaded configuration is displayed.</p>

<table class="mako-table">
	<tr>
		<th>File</th>
		<th>Configuration</th>
	</tr>

	{% foreach($config as $file => $configuration) %}

		<tr>
			<td>{{$file}}</td>
			<td>{{print_r($configuration, true)}}</td>
		</tr>

	{% endforeach %}

</table>