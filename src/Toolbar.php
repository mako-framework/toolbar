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

use function array_sum;
use function ini_get;
use function memory_get_peak_usage;
use function microtime;
use function round;

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
	protected $humanizer;

	/**
	 * Application.
	 *
	 * @var \mako\application\Application
	 */
	protected $application;

	/**
	 * Panels.
	 *
	 * @var array
	 */
	protected $panels = [];

	/**
	 * Timers.
	 *
	 * @var array
	 */
	protected $timers = [];

	/**
	 * Constructor.
	 *
	 * @param \mako\view\ViewFactory        $view        View factory instance
	 * @param \mako\utility\Humanizer       $humanizer   Humanizer instance
	 * @param \mako\application\Application $application Application
	 */
	public function __construct(ViewFactory $view, Humanizer $humanizer, Application $application)
	{
		$this->view = $view;

		$this->humanizer = $humanizer;

		$this->application = $application;
	}

	/**
	 * Add a panel to the toolbar.
	 *
	 * @param \mako\toolbar\panels\PanelInterface $panel Panel
	 */
	public function addPanel(PanelInterface $panel): void
	{
		$this->panels[] = $panel;
	}

	/**
	 * Adds a timer.
	 *
	 * @param string         $name Timer name
	 * @param \Closure|float $time Timer value
	 */
	public function addTimer(string $name, $time): void
	{
		$this->timers[$name] = $time;
	}

	/**
	 * Calculates the execution time.
	 *
	 * @return array
	 */
	protected function calculateExecutionTime(): array
	{
		$totalTime = microtime(true) - $this->application->getStartTime();

		$executionTime = ['total' => $totalTime, 'details' => []];

		$otherTime = array_sum($this->timers);

		$detailedTime = $totalTime - $otherTime;

		$executionTime['details']['PHP'] = ['time' => $detailedTime, 'pct' => ($detailedTime / $totalTime * 100)];

		foreach($this->timers as $timer => $time)
		{
			if($time instanceof Closure)
			{
				$time = $time();
			}

			$executionTime['details'][$timer] = ['time' => $time, 'pct' => ($time / $totalTime * 100)];
		}

		return $executionTime;
	}

	/**
	 * Renders the toolbar.
	 *
	 * @return string
	 */
	public function render(): string
	{
		$executionTime = $this->calculateExecutionTime();

		$view = $this->view->create('mako-toolbar::toolbar',
		[
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
