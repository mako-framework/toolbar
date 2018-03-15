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
		* Array of config keys of you want values to mask. Set to false to disable masking.
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
