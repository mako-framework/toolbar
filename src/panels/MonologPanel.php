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
 */
class MonologPanel extends Panel implements PanelInterface
{
	use DumperTrait;

	/**
	 * Constructor.
	 *
	 * @param \mako\view\ViewFactory       $view           View factory instance
	 * @param \mako\toolbar\MonologHandler $monologHandler Monolog handler
	 */
	public function __construct(
		ViewFactory $view,
		protected MonologHandler $monologHandler
	)
	{
		parent::__construct($view);
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
		return static function (int $level): string
		{
			return match($level)
			{
				100     => 'debug',
				200     => 'info',
				250     => 'notice',
				300     => 'warning',
				400     => 'error',
				500     => 'critical',
				550     => 'alert',
				600     => 'emergency',
				default => 'unknown',
			};
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
