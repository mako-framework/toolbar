<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\http\Request;
use mako\toolbar\panels\traits\DumperTrait;
use mako\view\ViewFactory;

/**
 * Request panel.
 */
class RequestPanel extends Panel implements PanelInterface
{
	use DumperTrait;

	/**
	 * Constructor.
	 */
	public function __construct(
		ViewFactory $view,
		protected Request $request
	) {
		parent::__construct($view);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTabLabel(): string
	{
		return 'Request';
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(): string
	{
		$view = $this->view->create('mako-toolbar::panels.request', [
			'request'      => $this->request,
			'dump'         => $this->getDumper(),
			'superglobals' => [
				'GET parameters'      => &$_GET,
				'POST parameters'     => &$_POST,
				'Cookies'             => &$_COOKIE,
				'Uploaded files'      => &$_FILES,
				'Native session data' => &$_SESSION,
				'Environment'         => &$_ENV,
				'Server variables'    => &$_SERVER,
			],
		]);

		return $view->render();
	}
}
