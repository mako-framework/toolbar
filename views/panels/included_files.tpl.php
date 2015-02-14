<p><span class="mako-title">Included files</span></p>

<p>Note that the debug toolbar includes several files that might not be included when the toolbar is disabled.</p>

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