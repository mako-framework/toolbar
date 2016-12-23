<div class="mako-panel-header">
	<span class="mako-title">Included files</span>
</div>

<div class="mako-panel-content">

	<p><span class="mako-subtitle">Included files:</span></p>

	<p><small>* The file list might not be a 100% accurate as it only contains the files included at the time of panel rendering.</small></p>

	<table class="mako-table mako-table-2c">
		<tr>
			<th>#</th>
			<th>Name</th>
		</tr>

		{% foreach($files as $key => $file) %}

			<tr>
				<td>{{$key + 1}}</td>
				<td>{{$file}}</td>
			</tr>

		{% endforeach %}

	</table>

</div>
