<div class="mako-panel-header">
	<span class="mako-title">Included files</span>
</div>

<div class="mako-panel-content">

	<p>&nbsp;&nbsp;</p>

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
