<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\http\Response;
use mako\toolbar\panels\Panel;
use mako\toolbar\panels\PanelInterface;
use mako\toolbar\panels\traits\DumperTrait;
use mako\view\ViewFactory;

/**
 * Response panel.
 *
 * @author Frederic G. Østby
 */
class ResponsePanel extends Panel implements PanelInterface
{
	use DumperTrait;

	/**
	 * Response.
	 *
	 * @var \mako\http\Response
	 */
	protected $response;

	/**
	 * Constructor.
	 *
	 * @param \mako\view\ViewFactory $view     View factory instance
	 * @param \mako\http\Response    $response Response
	 */
	public function __construct(ViewFactory $view, Response $response)
	{
		parent::__construct($view);

		$this->response = $response;
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
			'data'     =>
			[
				'Headers' => $this->response->getHeaders(),
				'Cookies' => $this->response->getCookies(),
			],
		]);

		return $view->render();
	}
}
