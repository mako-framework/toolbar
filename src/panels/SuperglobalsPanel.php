<?php

/**
 * @copyright  Frederic G. Østby
 * @license    http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

/**
 * Superglobals panel.
 *
 * @author  Frederic G. Østby
 */

class SuperglobalsPanel extends \mako\toolbar\panels\Panel implements \mako\toolbar\panels\PanelInterface
{
	/**
	 * Returns the tab label.
	 * 
	 * @access  public
	 * @return  string
	 */

	public function getTabLabel()
	{
		return 'superglobals';
	}

	/**
	 * Returns the rendered panel.
	 * 
	 * @access  public
	 * @return  string
	 */

	public function render()
	{
		$view = $this->view->create('toolbar::panels.superglobals',
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