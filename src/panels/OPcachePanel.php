<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\http\routing\URLBuilder;
use mako\toolbar\panels\traits\DumperTrait;
use mako\view\ViewFactory;

use function opcache_get_status;

/**
 * OPcache panel.
 */
class OPcachePanel extends Panel implements PanelInterface
{
	use DumperTrait;

	/**
	 * URL builder.
	 *
	 * @var \mako\http\routing\URLBuilder
	 */
	protected $urlBuilder;

	/**
	 * Constructor.
	 *
	 * @param \mako\view\ViewFactory        $view       View factory instance
	 * @param \mako\http\routing\URLBuilder $urlBuilder URL builder
	 */
	public function __construct(ViewFactory $view, URLBuilder $urlBuilder)
	{
		$this->view = $view;

		$this->urlBuilder = $urlBuilder;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTabLabel(): string
	{
		return 'OPcache';
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(): string
	{
		return $this->view->render('mako-toolbar::panels.opcache',
		[
			'dump'   => $this->getDumper(),
			'status' => opcache_get_status(),
			'url'    => $this->urlBuilder,
		]);
	}
}
