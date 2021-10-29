<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\controllers;

use mako\http\routing\Controller;

use function opcache_reset;

/**
 * OPcache.
 */
class OPcache extends Controller
{
	/**
	 * Clears the opcache.
	 */
	public function reset(): void
	{
		opcache_reset();
	}
}
