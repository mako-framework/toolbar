<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\http\Response;
use mako\toolbar\panels\traits\DumperTrait;
use mako\view\ViewFactory;

/**
 * Response panel.
 */
class ResponsePanel extends Panel implements PanelInterface
{
	use DumperTrait;

	/**
	 * Constructor.
	 */
	public function __construct(
		ViewFactory $view,
		protected Response $response
	) {
		parent::__construct($view);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTabLabel(): string
	{
		return 'Response';
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(): string
	{
		$view = $this->view->create('mako-toolbar::panels.response',
		[
			'response' => $this->response,
			'dump'     => $this->getDumper(),
			'data'     => [
				'Headers' => $this->response->getHeaders(),
				'Cookies' => $this->response->getCookies(),
			],
		]);

		return $view->render();
	}
}
