<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\config\Config;
use mako\toolbar\panels\Panel;
use mako\toolbar\panels\PanelInterface;
use mako\toolbar\panels\traits\DumperTrait;
use mako\view\ViewFactory;

/**
 * Config panel.
 *
 * @author Frederic G. Østby
 */
class ConfigPanel extends Panel implements PanelInterface
{
	use DumperTrait;

	/**
	 * Config.
	 *
	 * @var \mako\config\Config;
	 */
	protected $config;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @param \mako\view\ViewFactory $view   View factory instance
	 * @param \mako\config\Config    $config Configuration instance
	 */
	public function __construct(ViewFactory $view, Config $config)
	{
		parent::__construct($view);

		$this->config = $config;
	}

	/**
	 * Returns the tab label.
	 *
	 * @access  public
	 * @return string
	 */
	public function getTabLabel(): string
	{
		return 'Configuration';
	}

	/**
	 * Returns the rendered panel.
	 *
	 * @access  public
	 * @return string
	 */
	public function render(): string
	{
		$view = $this->view->create('mako-toolbar::panels.config',
		[
			'config' => $this->config->getLoadedConfiguration(),
			'dump'   => $this->getDumper(),
		]);

		return $view->render();
	}
}
