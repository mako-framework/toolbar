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
	 * Constructor.
	 *
	 * @param \mako\view\ViewFactory        $view       View factory instance
	 * @param \mako\http\routing\URLBuilder $urlBuilder URL builder
	 */
	public function __construct(
		ViewFactory $view,
		protected URLBuilder $urlBuilder
	)
	{
		parent::__construct($view);
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
