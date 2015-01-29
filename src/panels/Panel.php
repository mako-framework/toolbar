<?php

/**
 * @copyright  Frederic G. Østby
 * @license    http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\toolbar\panels\PanelInterface;
use mako\view\ViewFactory;

/**
 * Abstract panel.
 *
 * @author  Frederic G. Østby
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
	 * @access  public
	 * @param   \mako\view\ViewFactory  $view     View factory instance
	 */

	public function __construct(ViewFactory $view)
	{
		$this->view = $view;
	}

	/**
	 * Returns a unique panel id.
	 *
	 * @access  public
	 * @return  string
	 */

	public function getId()
	{
		return md5(get_called_class());
	}
}