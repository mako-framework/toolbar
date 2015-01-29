<?php

/**
 * @copyright  Frederic G. Østby
 * @license    http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\session\Session;
use mako\toolbar\panels\Panel;
use mako\toolbar\panels\PanelInterface;
use mako\view\ViewFactory;

/**
 * Session panel.
 *
 * @author  Frederic G. Østby
 */

class SessionPanel extends Panel implements PanelInterface
{
	/**
	 * Session.
	 *
	 * @var \mako\session\Session;
	 */

	protected $session;

	/**
	 * Constructor.
	 *
	 * @access  public
	 * @param   \mako\view\ViewFactory  $view      View factory instance
	 * @param   \mako\session\Session   $session   Session instance
	 */

	public function __construct(ViewFactory $view, Session $session)
	{
		parent::__construct($view);

		$this->session = $session;
	}

	/**
	 * Returns the tab label.
	 *
	 * @access  public
	 * @return  string
	 */

	public function getTabLabel()
	{
		return 'Session';
	}

	/**
	 * Returns the rendered panel.
	 *
	 * @access  public
	 * @return  string
	 */

	public function render()
	{
		$view = $this->view->create('mako-toolbar::panels.session',
		[
			'id'   => $this->session->getId(),
			'data' => $this->session->getData(),
		]);

		return $view->render();
	}
}