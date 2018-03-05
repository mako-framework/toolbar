<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use Closure;
use mako\toolbar\Monologger;
use mako\toolbar\panels\traits\DumperTrait;
use mako\view\ViewFactory;

/**
 * Monolog panel.
 *
 * @author Frederic G. Østby
 */
class MonologPanel extends Panel implements PanelInterface
{
	use DumperTrait;

	/**
	 * Monologger.
	 *
	 * @var \mako\toolbar\Monologger
	 */
	protected $monologger;

	/**
	 * Constructor.
	 *
	 * @param \mako\view\ViewFactory   $view       View factory instance
	 * @param \mako\toolbar\Monologger $monologger Monologger
	 */
	public function __construct(ViewFactory $view, Monologger $monologger)
	{
		parent::__construct($view);

		$this->monologger = $monologger;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTabLabel(): string
	{
		return sprintf('%u log entries', $this->monologger->getEntryCount());
	}

	/**
	 * Returns a helper funtion.
	 *
	 * @return \Closure
	 */
	protected function getLevelHelper(): Closure
	{
		return function($level)
		{
			switch($level)
			{
				case 100:
					return 'debug';
				case 200:
					return 'info';
				case 250:
					return 'notice';
				case 300:
					return 'warning';
				case 400:
					return 'error';
				case 500:
					return 'critical';
				case 550:
					return 'alert';
				case 600:
					return 'emergency';
			}
		};
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(): string
	{
		$view = $this->view->create('mako-toolbar::panels.monolog',
		[
			'entries'      => $this->monologger->getEntries(),
			'level_helper' => $this->getLevelHelper(),
			'dump'         => $this->getDumper(),
		]);

		return $view->render();
	}
}
