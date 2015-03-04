{% if(empty($logs)) %}

	<div class="mako-empty mako-title">No database queries...</div>

{% else %}

	<p><span class="mako-title">Database queries</span></p>

		{% foreach($logs as $name => $queries) %}

			<p><span class="mako-subtitle">Queries executed on the [ {{$name}} ] connection:</span></p>

			{% if(empty($queries)) %}

				<table class="mako-table">
					<tr>
						<td>No database queries have been executed on the connection.</td>
					</tr>
				</table>

			{% else %}

				<table class="mako-table mako-table-2c">

					<tr>
						<th>Time</th>
						<th>Query</th>
					</tr>

					{% foreach($queries as $query) %}

						<tr>
							<td>{{round($query['time'], 5)}} seconds</td>
							<td>{{$query['query']}}</td>
						</tr>

					{% endforeach %}

				</table>

			{% endif %}

	{% endforeach %}

{% endif %}