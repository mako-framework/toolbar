<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\view\ViewFactory;

use function md5;

/**
 * Abstract panel.
 */
abstract class Panel implements PanelInterface
{
	/**
	 * View factory instance.
	 */
	protected ViewFactory $view;

	/**
	 * Constructor.
	 */
	public function __construct(ViewFactory $view)
	{
		$this->view = $view;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getId(): string
	{
		return md5(static::class);
	}
}
