<div class="mako-panel-header">
	<span class="mako-title">Request</span>
</div>

<div class="mako-panel-content">

	<b>Method:</b> <span class="mako-label">{{$request->method()}}</span> &nbsp;&nbsp;
	<b>Real Method:</b> <span class="mako-label">{{$request->realMethod()}}</span> &nbsp;&nbsp;
	<b>Route:</b> <span class="mako-label" title="Pattern: {{$request->getRoute()->getRegex()}}">{{$request->getRoute()->getRoute()}}</span> &nbsp;&nbsp;
	<b>Client IP:</b> <span class="mako-label">{{$request->ip()}}</span>

	<hr>

	{% foreach($superglobals as $name => $superglobal) %}

		{% if(!empty($superglobal)) %}

			<p><span class="mako-subtitle">{{$name}}:</span></p>

			<table class="mako-table mako-table-2c">

				<tr>
					<th>Key</th>
					<th>Value</th>
				</tr>

				{% foreach($superglobal as $key => $value) %}

					<tr>
						<td>{{$key}}</td>
						<td>{{$dump($value)}}</td>
					</tr>

				{% endforeach %}

			</table>

		{% endif %}

	{% endforeach %}

</div>
