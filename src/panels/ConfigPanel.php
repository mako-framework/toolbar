<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\config\Config;
use mako\toolbar\panels\traits\DumperTrait;
use mako\utility\Arr;
use mako\utility\Str;
use mako\view\ViewFactory;

use function is_array;
use function is_string;
use function strpos;

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
	 * @param \mako\view\ViewFactory $view        View factory instance
	 * @param \mako\config\Config    $config      Configuration instance
	 * @param string|null            $environment Environment
	 */
	public function __construct(ViewFactory $view, Config $config, ?string $environment = null)
	{
		parent::__construct($view);

		$this->config = $config;

		$this->environment = $environment;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTabLabel(): string
	{
		return 'Configuration';
	}

	/**
	 * Expand key.
	 *
	 * @param  array  $config Config
	 * @param  string $key    Config key
	 * @return array
	 */
	protected function expandKey(array $config, string $key): array
	{
		if(strpos($key, '*') === false)
		{
			return [$key];
		}

		return Arr::expandKey($config, $key);
	}

	/**
	 * Masks config values.
	 *
	 * @param  array $config Configuration
	 * @return array
	 */
	protected function maskValues(array $config): array
	{
		$mask = $this->config->get('mako-toolbar::config.config.mask');

		if(!empty($mask) && is_array($mask))
		{
			foreach($mask as $key)
			{
				foreach($this->expandKey($config, $key) as $key)
				{
					if(($value = Arr::get($config, $key)) !== null)
					{
						Arr::set($config, $key, is_string($value) ? Str::mask($value) : '******');
					}
				}
			}
		}

		return $config;
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(): string
	{
		$view = $this->view->create('mako-toolbar::panels.config',
		[
			'config'      => $this->maskValues($this->config->getLoadedConfiguration()),
			'environment' => $this->environment,
			'dump'        => $this->getDumper(),
		]);

		return $view->render();
	}
}
