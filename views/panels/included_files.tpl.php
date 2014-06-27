<p><span class="mako-title">INCLUDED FILES</span></p>

<table class="mako-table">
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