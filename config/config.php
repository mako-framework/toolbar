<?php

return
[
	/*
	 * ---------------------------------------------------------
	 * Config
	 * ---------------------------------------------------------
	 *
	 * Config panel settings.
	 */
	'config' =>
	[
		/*
		 * Array of config keys that you want masked in the output. Set to false to disable masking.
		 */
		'mask' =>
		[
			'application.secret',
			'crypto.configurations.*.key',
			'database.configurations.*.password',
			'redis.configurations.*.password',
		],
	],
];
