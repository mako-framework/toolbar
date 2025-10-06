<?php

return
[
	/*
	 * ---------------------------------------------------------
	 * Debug server client
	 * ---------------------------------------------------------
	 *
	 * Debug server client settings.
	 */
	'debug_server_client' => [
		'address'        => mako\env('MAKO_DEBUG_SERVER_ADDRESS', 'localhost'),
		'port'           => mako\env('MAKO_DEBUG_SERVER_PORT', 9000),
		'socket_timeout' => mako\env('MAKO_DEBUG_SERVER_CLIENT_SOCKET_TIMEONT', 0.5),
		'stream_timeout' => mako\env('MAKO_DEBUG_SERVER_CLIENT_STREAM_TIMEONT', 1),
	],

	/*
	 * ---------------------------------------------------------
	 * Config
	 * ---------------------------------------------------------
	 *
	 * Config panel settings.
	 */
	'config' => [
		/*
		 * Array of config keys that you want masked in the output. Set to false to disable masking.
		 */
		'mask' => [
			'application.secret',
			'crypto.configurations.*.key',
			'database.configurations.*.password',
			'redis.configurations.*.password',
		],
	],
];
