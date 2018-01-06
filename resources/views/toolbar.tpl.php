<script>
	var Mako =
	{
		togglePanel: function(id)
		{
			if(id !== undefined)
			{
				var element = document.getElementById(id);

				if(window.getComputedStyle(element).getPropertyValue('display') == 'none')
				{
					element.style.display = 'block';
				}
				else
				{
					element.style.display = 'none';
				}
			}

			var elements = document.getElementsByClassName('mako-panel');

			for(var i = 0; i < elements.length; i++)
			{
				if(id === undefined || element !== elements[i])
				{
					elements[i].style.display = 'none';
				}
			}

			return false;
		},
		toggleToolbar: function(state)
		{
			if(state === 0)
			{
				this.togglePanel();

				document.getElementById('mako-toolbar').style.display = 'none';
				document.getElementById('mako-toolbar-hidden').style.display = 'block';

				document.cookie = 'mako_toolbar_hidden=1';
			}
			else
			{
				document.getElementById('mako-toolbar').style.display = 'block';
				document.getElementById('mako-toolbar-hidden').style.display = 'none';

				document.cookie = 'mako_toolbar_hidden=0';
			}
		},
		getCookie: function(name)
		{
			var name = name + '=';

			var cookies = document.cookie.split(';');

			for(var i = 0; i < cookies.length; i++)
			{
				var cookie = cookies[i];

				while(cookie.charAt(0) == ' ')
				{
					cookie = cookie.substring(1);
				}

				if(cookie.indexOf(name) == 0)
				{
					return cookie.substring(name.length, cookie.length);
				}
			}
			return false;
		}
	};
</script>

<style>
	.mako-icon
	{
		margin-right: 5px;
		display: inline-block;
		height: 25px;
		width: 25px;
		vertical-align: middle;
		background-image: url('data:image/png;base64,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TUw6Y29tLmFkb2JlLnhtcAAAAAAAPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS4xLjIiPgogICA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPgogICAgICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgICAgICAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyI+CiAgICAgICAgIDx0aWZmOlJlc29sdXRpb25Vbml0PjE8L3RpZmY6UmVzb2x1dGlvblVuaXQ+CiAgICAgICAgIDx0aWZmOkNvbXByZXNzaW9uPjU8L3RpZmY6Q29tcHJlc3Npb24+CiAgICAgICAgIDx0aWZmOlhSZXNvbHV0aW9uPjcyPC90aWZmOlhSZXNvbHV0aW9uPgogICAgICAgICA8dGlmZjpPcmllbnRhdGlvbj4xPC90aWZmOk9yaWVudGF0aW9uPgogICAgICAgICA8dGlmZjpZUmVzb2x1dGlvbj43MjwvdGlmZjpZUmVzb2x1dGlvbj4KICAgICAgPC9yZGY6RGVzY3JpcHRpb24+CiAgICAgIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiCiAgICAgICAgICAgIHhtbG5zOmV4aWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20vZXhpZi8xLjAvIj4KICAgICAgICAgPGV4aWY6UGl4ZWxYRGltZW5zaW9uPjI1PC9leGlmOlBpeGVsWERpbWVuc2lvbj4KICAgICAgICAgPGV4aWY6Q29sb3JTcGFjZT4xPC9leGlmOkNvbG9yU3BhY2U+CiAgICAgICAgIDxleGlmOlBpeGVsWURpbWVuc2lvbj4yNTwvZXhpZjpQaXhlbFlEaW1lbnNpb24+CiAgICAgIDwvcmRmOkRlc2NyaXB0aW9uPgogICAgICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgICAgICAgICB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iPgogICAgICAgICA8ZGM6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');
	}
	#mako-toolbar-hidden
	{
		display: none;
		padding: 12px;
		opacity: 0.5;
	}
	#mako-debug
	{
		width: 100%;
		position: fixed;
		bottom: 0;
		right:0;
		z-index: 9999;
		color: #fff;
		font-family:"Helvetica Neue",Helvetica,Arial,sans-serif !important;
		font-size: 16px !important;
		text-shadow: none;
		font-weight: normal;
	}
	#mako-debug pre
	{
		border: none;
		margin: 0 !important;
		padding: 0 !important;
	}
	#mako-debug a
	{
		color: #ccc;
		cursor: pointer;
		text-decoration: none;
	}
	#mako-debug p
	{
		margin-top: 1em;
		margin-bottom: 1em;
	}
	#mako-debug .mako-strong
	{
		font-weight: bold;
	}
	#mako-debug .mako-small
	{
		font-size: 0.85em;
	}
	#mako-debug .mako-right
	{
		float: right;
	}
	#mako-debug .mako-right span
	{
		float:left;
		clear:none;
		padding: 5px;
		padding-left: 8px;
		padding-right: 8px;
		color: #aaa;
		background: #111;
		border-bottom: 1px solid #333;
		-moz-box-shadow: inset 0 3px 5px #111;
		-webkit-box-shadow: inset 0 3px 5px #111;
		box-shadow: inset 0 3px 5px #111;
	}
	#mako-debug .mako-right span:not(:last-child)
	{
		border-right: 1px solid #232323;
	}
	#mako-debug hr
	{
		border: 0;
		height: 1px;
		background: #CCC;
		margin: 0;
		margin-top: 10px;
		margin-bottom: 10px;
		padding: 0;
	}
	#mako-debug .mako-close
	{
		float: right;
		padding-top: 20px;
		padding-right: 12px;
	}
	#mako-debug .mako-close a
	{
		color: #FFF;
	}
	#mako-debug .mako-label
	{
		background: #efefef;
		color: #2DB28A;
		padding: 2px 4px;
	}
	#mako-debug .mako-log
	{
		color: #fff;
	}
	#mako-debug .mako-notice
	{
		background: #999999;
	}
	#mako-debug .mako-critical
	{
		background: #B94A48;
	}
	#mako-debug .mako-alert
	{
		background: #F89406;
	}
	#mako-debug .mako-emergency
	{
		background: #B94A48;
	}
	#mako-debug .mako-error
	{
		background: #B94A48;
	}
	#mako-debug .mako-warning
	{
		background: #F89406;
	}
	#mako-debug .mako-info
	{
		background: #3A87AD;
	}
	#mako-debug .mako-debug
	{
		background: #468847;
	}
	#mako-debug .mako-title
	{
		color: #eee;
		font-size: 2.0em;
		text-align: center;
	}
	#mako-debug .mako-subtitle
	{
		color: #555;
		font-size: 1.2em;
		text-align: center;
	}
	#mako-debug .mako-empty
	{
		color: #ccc;
		margin: 200px auto;
	}

	#mako-debug .mako-table
	{
		width: 100%;
		border: 1px solid #ddd;
		background: #fff;
		border-collapse: collapse;
	}
	#mako-debug table.mako-table-2c td:first-child
	{
		min-width: 10%;
	}
	#mako-debug table.mako-table-2c td:last-child
	{
		width: 90%;
	}
	#mako-debug table th
	{
		padding: 4px;
		border-bottom: 2px solid #ddd;
	}
	#mako-debug table td
	{
		padding: 4px;
		border-bottom: 1px solid #ddd;
		border-right: 1px solid #ddd;
		vertical-align: top;
	}
	#mako-debug table tr:nth-child(odd)
	{
		background: #f4f4f4;
	}
	#mako-debug table tr th
	{
		text-align: left;
		padding: 4px;
	}
	#mako-debug #mako-toolbar
	{
		padding: 12px;
		background: #232323;
		border-top: 1px solid #000;
		font-size: 0.8em;
	}
	#mako-debug .mako-panel
	{
		display: none;
		height: 500px;
		overflow: auto;
		background: #fff;
		border-top: 2px solid #555;
		color: #222;
		font-size: 0.9em;
	}
	#mako-debug .mako-panel-header
	{
		padding: 12px;
		background: #2DB28A;
		color: #eee;
	}
	#mako-debug .mako-panel-content
	{
		padding: 12px;
	}
	#mako-debug a.mako-button
	{
		color: #bbb;
		padding: 5px;
		border-right: 1px solid #111;
		-webkit-box-shadow: 1px 0px 0px #444;
		-moz-box-shadow: 1px 0px 0px #444;
		box-shadow: 1px 0px 0px #444;
	}
	#mako-debug a.mako-button:hover
	{
		color: #eee;
	}
	#mako-debug a.mako-button:last-child
	{
		border-right: none;
		-webkit-box-shadow: none;
		-moz-box-shadow: none;
		box-shadow: none;
	}
	.mako-log-debug
	{
		color: #216BAB;
	}
	.mako-log-info
	{
		color: #2FBC4A;
	}
	.mako-log-notice
	{
		color: #EE9800;
	}
	.mako-log-warning
	{
		color: #E34A00;
	}
	.mako-log-error, .mako-log-critical, .mako-log-alert, .mako-log-emergency
	{
		color: #C22E20;
	}
	.mako-log-critical, .mako-log-alert, .mako-log-emergency
	{
		font-style: italic;
	}
	.mako-log-alert, .mako-log-emergency
	{
		text-decoration: underline;
	}
	.mako-log-emergency
	{
		font-weight: bold;
	}
</style>

<div id="mako-debug">

	{% foreach($panels as $panel) %}

		<div class="mako-panel" id="{{$panel->getId()}}">
			<div class="mako-close"><a onclick="Mako.togglePanel('{{$panel->getId()}}')">&#x2716;</a></div>
			{{raw:$panel->render()}}
		</div>

	{% endforeach %}

	<div class="mako-panel" id="execution">
		<div class="mako-close"><a onclick="Mako.togglePanel('execution')">&#x2716;</a></div>
		{{view:'mako-toolbar::panels.execution'}}
	</div>

	<div id="mako-toolbar-hidden">
		<div class="mako-icon" title="Mako {{$version}} ({{PHP_VERSION}})" onclick="Mako.toggleToolbar(1);"></div>
	</div>

	<div id="mako-toolbar">

		<div class="mako-right">
			<span title="peak memory usage"><a class="mako-button" onclick="Mako.togglePanel('execution')">{{$humanizer->fileSize($memory)}}</a></span>
			<span title="total execution time"><a class="mako-button" onclick="Mako.togglePanel('execution')">{{$time}} seconds</a></span>
		</div>

		<div class="mako-icon" title="Mako {{$version}} ({{PHP_VERSION}})" onclick="Mako.toggleToolbar(0);"></div>

		{% foreach($panels as $panel) %}

			<a class="mako-button" onclick="Mako.togglePanel('{{$panel->getId()}}')">{{$panel->getTabLabel()}}</a>

		{% endforeach %}

	</div>

</div>

<script>
	if(Mako.getCookie('mako_toolbar_hidden') === '1')
	{
		Mako.toggleToolbar(0);
	}
</script>
