<p><span class="mako-title">Superglobals</span></p>

{% foreach($superglobals as $name => $superglobal) %}

	{% if(!empty($superglobal)) %}

		<p><span class="mako-subtitle">${{$name}}:</span></p>

		<table class="mako-table mako-table-2c">

			<tr>
				<th>Key</th>
				<th>Value</th>
			</tr>

			{% foreach($superglobal as $key => $value) %}

				<tr>
					<td>{{$key}}</td>
					<td>{{print_r($value, true)}}</td>
				</tr>

			{% endforeach %}

		</table>

	{% endif %}

{% endforeach %}