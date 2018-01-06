<div class="mako-panel-header">
	<span class="mako-title">Included files</span>
</div>

<div class="mako-panel-content">

	<p><span class="mako-subtitle">Included files:</span></p>

	<p>
		<small>* The file list might not be 100% accurate as it only contains the files included at the time of panel rendering. Also keep in mind that some of the files are included by the toolbar and will not be loaded in a production environment.</small>
	</p>

	<table class="mako-table mako-table-2c">
		<tr>
			<th>#</th>
			<th>Name</th>
		</tr>

		{% foreach($files as $key => $file) %}

			<tr>
				<td>{{$key + 1}}</td>
				<td>{{$dump($file)}}</td>
			</tr>

		{% endforeach %}

	</table>

</div>
