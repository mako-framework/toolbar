<?php

return
[
	/**
	 * ---------------------------------------------------------
	 * Config
	 * ---------------------------------------------------------
	 *
	 * Config panel settings.
	 */
	'config' =>
	[
		/**
		* Array of config keys of where you want the value masked in the output. Set to false to disable masking.
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
