<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\session\Session;
use mako\toolbar\panels\traits\DumperTrait;
use mako\view\ViewFactory;

/**
 * Session panel.
 */
class SessionPanel extends Panel implements PanelInterface
{
	use DumperTrait;

	/**
	 * Constructor.
	 */
	public function __construct(
		ViewFactory $view,
		protected Session $session
	) {
		parent::__construct($view);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTabLabel(): string
	{
		return 'Session';
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(): string
	{
		$view = $this->view->create('mako-toolbar::panels.session', [
			'id'   => $this->session->getId(),
			'data' => $this->session->getData(),
			'dump' => $this->getDumper(),
		]);

		return $view->render();
	}
}
