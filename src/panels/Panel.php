<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\toolbar\panels\PanelInterface;
use mako\view\ViewFactory;

/**
 * Abstract panel.
 *
 * @author Frederic G. Østby
 */
abstract class Panel implements PanelInterface
{
	/**
	 * View factory instance.
	 *
	 * @var \mako\view\ViewFactory
	 */
	protected $view;

	/**
	 * Constructor.
	 *
	 * @param \mako\view\ViewFactory $view View factory instance
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
		return md5(get_called_class());
	}
}
