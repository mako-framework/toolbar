<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\http\Request;
use mako\toolbar\panels\Panel;
use mako\toolbar\panels\PanelInterface;
use mako\toolbar\panels\traits\DumperTrait;
use mako\view\ViewFactory;

/**
 * Request panel.
 *
 * @author Frederic G. Østby
 */
class RequestPanel extends Panel implements PanelInterface
{
	use DumperTrait;

	/**
	 * Request.
	 *
	 * @var \mako\http\Request
	 */
	protected $request;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @param \mako\view\ViewFactory $view    View factory instance
	 * @param \mako\http\Request     $request Request
	 */
	public function __construct(ViewFactory $view, Request $request)
	{
		parent::__construct($view);

		$this->request = $request;
	}

	/**
	 * Returns the tab label.
	 *
	 * @access public
	 * @return string
	 */
	public function getTabLabel(): string
	{
		return 'Request';
	}

	/**
	 * Returns the rendered panel.
	 *
	 * @access public
	 * @return string
	 */
	public function render(): string
	{
		$view = $this->view->create('mako-toolbar::panels.request',
		[
			'request'      => $this->request,
			'dump'         => $this->getDumper(),
			'superglobals' =>
			[
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
