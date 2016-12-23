<div class="mako-panel-header">
	<span class="mako-title">Execution</span>
</div>

<div class="mako-panel-content">

	<p><span class="mako-subtitle">Execution time:</span></p>

	<p><b>Total execution time:</b> <span class="mako-label">{{round($execution_time['total'], 4)}} seconds</span></p>

	<table class="mako-table mako-table-2c">

		<tr>
			<th>What</th>
			<th>Time</th>
		</tr>

	{% foreach($execution_time['details'] as $timer => $data) %}

		<tr title="{{round($data['pct'], 4)}}%">
			<td>{{$timer}}</td>
			<td style="position:relative;">
				<div style="position:absolute;left:0;top:0;bottom:0;background-color:#2DB28A;z-index:1;width:{{$data['pct']}}%"></div>
				<span style="position:absolute;z-index:2;">{{round($data['time'], 4)}} seconds</span>
			</td>
		</tr>

	{% endforeach %}

	</table>

	<p><span class="mako-subtitle">Memory usage:</span></p>

	<table class="mako-table mako-table-2c">
		<tr>
			<th>Limit</th>
			<th>Usage</th>
		</tr>
		<tr>
			<td>{{$memory_limit}}</td>
			<td>{{$humanizer->fileSize($memory)}}</td>
		</tr>
	</table>

</div>
