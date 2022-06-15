<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use Closure;
use mako\toolbar\MonologHandler;
use mako\toolbar\panels\traits\DumperTrait;
use mako\view\ViewFactory;

use function sprintf;

/**
 * Monolog panel.
 *
 * @author Frederic G. Østby
 */
class MonologPanel extends Panel implements PanelInterface
{
	use DumperTrait;

	/**
	 * Monolog handler.
	 *
	 * @var \mako\toolbar\MonologHandler
	 */
	protected $monologHandler;

	/**
	 * Constructor.
	 *
	 * @param \mako\view\ViewFactory   $view       View factory instance
	 * @param \mako\toolbar\MonologHandler $monologHandler Monolog handler
	 */
	public function __construct(ViewFactory $view, MonologHandler $monologHandler)
	{
		parent::__construct($view);

		$this->monologHandler = $monologHandler;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTabLabel(): string
	{
		return sprintf('%u log entries', $this->monologHandler->getEntryCount());
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
			'entries'      => $this->monologHandler->getEntries(),
			'level_helper' => $this->getLevelHelper(),
			'dump'         => $this->getDumper(),
		]);

		return $view->render();
	}
}
