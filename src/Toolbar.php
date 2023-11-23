<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar;

use Closure;
use mako\application\Application;
use mako\Mako;
use mako\toolbar\panels\PanelInterface;
use mako\utility\Humanizer;
use mako\view\ViewFactory;

use function ini_get;
use function memory_get_peak_usage;
use function microtime;
use function round;

/**
 * Mako debug toolbar.
 */
class Toolbar
{
	/**
	 * Panels.
	 */
	protected array $panels = [];

	/**
	 * Timers.
	 */
	protected array $timers = [];

	/**
	 * Constructor.
	 */
	public function __construct(
		protected ViewFactory $view,
		protected Humanizer $humanizer,
		protected Application $application
	) {
	}

	/**
	 * Add a panel to the toolbar.
	 */
	public function addPanel(PanelInterface $panel): void
	{
		$this->panels[] = $panel;
	}

	/**
	 * Adds a timer.
	 */
	public function addTimer(string $name, Closure|float $time): void
	{
		$this->timers[$name] = $time;
	}

	/**
	 * Calculates the execution time.
	 */
	protected function calculateExecutionTime(): array
	{
		$totalTime = microtime(true) - $this->application->getStartTime();

		$executionTime = ['total' => $totalTime, 'details' => []];

		$otherTime = 0;

		foreach ($this->timers as $timer) {
			$otherTime += $timer instanceof Closure ? $timer() : $timer;
		}

		$detailedTime = $totalTime - $otherTime;

		$executionTime['details']['PHP'] = ['time' => $detailedTime, 'pct' => ($detailedTime / $totalTime * 100)];

		foreach ($this->timers as $timer => $time) {
			if ($time instanceof Closure) {
				$time = $time();
			}

			$executionTime['details'][$timer] = ['time' => $time, 'pct' => ($time / $totalTime * 100)];
		}

		return $executionTime;
	}

	/**
	 * Renders the toolbar.
	 */
	public function render(): string
	{
		$executionTime = $this->calculateExecutionTime();

		$view = $this->view->create('mako-toolbar::toolbar', [
			'humanizer'      => $this->humanizer,
			'version'        => Mako::VERSION,
			'memory'         => memory_get_peak_usage(),
			'memory_limit'   => ini_get('memory_limit'),
			'time'           => round($executionTime['total'], 4),
			'execution_time' => $executionTime,
			'panels'         => $this->panels,
		]);

		return $view->render();
	}
}
