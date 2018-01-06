<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\controllers;

use mako\http\routing\Controller;

/**
 * OPcache.
 *
 * @author Frederic G. Østby
 */
class OPcache extends Controller
{
	/**
	 * Clears the opcache.
	 */
	public function reset()
	{
		opcache_reset();
	}
}