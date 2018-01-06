<div class="mako-panel-header">
	<span class="mako-title">OPcache</span>
</div>

<div class="mako-panel-content">

	<p>
		<div style="float:right">
			<form id="mako.opcache.clear" action="{{$url->toRoute('mako.toolbar.opcache.reset')}}" method="POST">
				<label>
					<input id="mako.opcache.clear.reload" type="checkbox" value="1" name="reload" checked>
					Reload page?
				</label>

				<button type="submit" name="mako-toolbar-clear-cache">Clear cache</button>
			</form>
		</div>

		<b>Enabled:</b> <span class="mako-label">{{$status['opcache_enabled'] ? 'Yes' : 'No'}}</span>
	</p>

	{% if(!empty($status['scripts'])) %}

		<hr>

		<p><span class="mako-subtitle">Scripts:</span></p>

		<table class="mako-table">
			<tr>
				<th>Script</th>
				<th>Hits</th>
				<th>Last Used</th>
			</tr>
			{% foreach($status['scripts'] as $script) %}
				<tr>
					<td>{{$dump($script['full_path'])}}</td>
					<td>{{$script['hits']}}</td>
					<td>{{$script['last_used']}}</td>
				</tr>
			{% endforeach %}
		</table>

	{% endif %}

</div>

<script>
	(function()
	{
		var form = document.getElementById('mako.opcache.clear');

		form.onsubmit = function(event)
		{
			event.preventDefault();

			var ajax = new XMLHttpRequest();

			ajax.open('POST', form.getAttribute('action'), true);

			ajax.send();

			if(document.getElementById('mako.opcache.clear.reload').checked)
			{
				window.location.reload(true);
			}
		};
	})();
</script>
