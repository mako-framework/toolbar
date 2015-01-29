<?php

/**
 * @copyright  Frederic G. Østby
 * @license    http://www.makoframework.com/license
 */

namespace mako\toolbar;

use mako\Mako;
use mako\utility\Humanizer;
use mako\toolbar\panels\PanelInterface;
use mako\view\ViewFactory;

/**
 * Mako debug toolbar.
 *
 * @author  Frederic G. Østby
 */

class Toolbar
{
	/**
	 * View factory instance.
	 *
	 * @var \mako\view\ViewFactory
	 */

	protected $view;

	/**
	 * Humanizer instance.
	 *
	 * @var \mako\utility\Humanizer
	 */

	/**
	 * Panels.
	 *
	 * @var array
	 */

	protected $panels = [];

	/**
	 * Constructor.
	 *
	 * @access  public
	 * @param   \mako\view\ViewFactory   $view       View factory instance
	 * @param   \mako\utility\Humanizer  $humanizer  Humanizer instance
	 */

	public function __construct(ViewFactory $view, Humanizer $humanizer)
	{
		$this->view = $view;

		$this->humanizer = $humanizer;
	}

	/**
	 * Add a panel to the toolbar.
	 *
	 * @access  public
	 * @param   \mako\toolbar\PanelInterface  $panel  Panel
	 */

	public function addPanel(PanelInterface $panel)
	{
		$this->panels[] = $panel;
	}

	/**
	 * Renders the toolbar.
	 *
	 * @access  public
	 * @return  string
	 */

	public function render()
	{
		$view = $this->view->create('mako-toolbar::toolbar',
		[
			'version' => Mako::VERSION,
			'memory'  => $this->humanizer->fileSize(memory_get_peak_usage()),
			'time'    => round(microtime(true) - MAKO_START, 4),
			'panels'  => $this->panels,
		]);

		return $view->render();
	}
}