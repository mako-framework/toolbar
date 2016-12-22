<p><span class="mako-title">Execution Time</span></p>

<b>Total execution time:</b> <span class="mako-label">{{round($execution_time['total'], 4)}} seconds</span>

<hr>

<table class="mako-table mako-table-2c">

	<tr>
		<th>What</th>
		<th>Time</th>
	</tr>

{% foreach($execution_time['details'] as $timer => $data) %}

	<tr title="{{round($data['pct'], 4)}}%">
		<td>{{$timer}}</td>
		<td style="position:relative;">
			<div style="position:absolute;left:0;top:0;bottom:0;background-color:rgba(3, 197, 160, .3);z-index:1;width:{{$data['pct']}}%"></div>
			<span style="position:absolute;z-index:2;">{{round($data['time'], 4)}} seconds</span>
		</td>
	</tr>

{% endforeach %}

</table>
