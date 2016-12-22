<p><span class="mako-title">Response</span></p>

<b>Status:</b> <span class="mako-label">{{$response->getStatus()}}</span> &nbsp;&nbsp;
<b>Content Type:</b> <span class="mako-label">{{$response->getType()}}</span> &nbsp;&nbsp;
<b>Character Set:</b> <span class="mako-label">{{$response->getCharset()}}</span> &nbsp;&nbsp;

<hr>

{% foreach($data as $name => $items) %}

	{% if(!empty($items)) %}

		<p><span class="mako-subtitle">{{$name}}:</span></p>

		<table class="mako-table mako-table-2c">

			<tr>
				<th>Key</th>
				<th>Value</th>
			</tr>

			{% foreach($items as $key => $value) %}

				<tr>
					<td>{{$key}}</td>
					<td>{{$dump($value)}}</td>
				</tr>

			{% endforeach %}

		</table>

	{% endif %}

{% endforeach %}
