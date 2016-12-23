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
	 * Environment.
	 *
	 * @var string
	 */
	protected $environment;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @param \mako\view\ViewFactory $view        View factory instance
	 * @param \mako\config\Config    $config      Configuration instance
	 * @param string|null            $environment Environment
	 */
	public function __construct(ViewFactory $view, Config $config, string $environment = null)
	{
		parent::__construct($view);

		$this->config = $config;

		$this->environment = $environment;
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
			'config'      => $this->config->getLoadedConfiguration(),
			'environment' => $this->environment,
			'dump'        => $this->getDumper(),
		]);

		return $view->render();
	}
}
