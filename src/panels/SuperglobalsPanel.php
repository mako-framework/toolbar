<?php

/**
 * @copyright  Frederic G. Østby
 * @license    http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\toolbar\panels\Panel;
use mako\toolbar\panels\PanelInterface;

/**
 * Superglobals panel.
 *
 * @author  Frederic G. Østby
 */

class SuperglobalsPanel extends Panel implements PanelInterface
{
	/**
	 * Returns the tab label.
	 *
	 * @access  public
	 * @return  string
	 */

	public function getTabLabel()
	{
		return 'Superglobals';
	}

	/**
	 * Returns the rendered panel.
	 *
	 * @access  public
	 * @return  string
	 */

	public function render()
	{
		$view = $this->view->create('mako-toolbar::panels.superglobals',
		[
			'superglobals' =>
			[
				'_COOKIE'  => &$_COOKIE,
				'_ENV'     => &$_ENV,
				'_FILES'   => &$_FILES,
				'_GET'     => &$_GET,
				'_POST'    => &$_POST,
				'_SERVER'  => &$_SERVER,
				'_SESSION' => &$_SESSION,
			],
		]);

		return $view->render();
	}
}