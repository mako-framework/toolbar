<div class="mako-panel-header">
	<span class="mako-title">Configuration</span>
</div>

<div class="mako-panel-content">

	<p>
		<b>Environment:</b> <span class="mako-label">{{$environment, default: 'Default'}}</span> &nbsp;&nbsp;
	</p>

	<hr>

	<p><span class="mako-subtitle">Loaded configuration:</span></p>

	<table class="mako-table mako-table-2c">
		<tr>
			<th>File</th>
			<th>Configuration</th>
		</tr>

		{% foreach($config as $file => $configuration) %}

			<tr>
				<td>{{$file}}</td>
				<td>{{$dump($configuration)}}</td>
			</tr>

		{% endforeach %}

	</table>

</div>
