<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
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
	 * Timers.
	 *
	 * @var array
	 */
	protected $timers = [];

	/**
	 * Constructor.
	 *
	 * @access public
	 * @param \mako\view\ViewFactory  $view      View factory instance
	 * @param \mako\utility\Humanizer $humanizer Humanizer instance
	 */
	public function __construct(ViewFactory $view, Humanizer $humanizer)
	{
		$this->view = $view;

		$this->humanizer = $humanizer;
	}

	/**
	 * Add a panel to the toolbar.
	 *
	 * @access public
	 * @param \mako\toolbar\PanelInterface $panel Panel
	 */
	public function addPanel(PanelInterface $panel)
	{
		$this->panels[] = $panel;
	}

	/**
	 * [addTimer description]
	 * @param string $name [description]
	 * @param float  $time [description]
	 */
	public function addTimer(string $name, float $time)
	{
		$this->timers[$name] = $time;
	}

	/**
	 * Calculates the execution time.
	 *
	 * @access protected
	 * @return array
	 */
	protected function calculateExecutionTime(): array
	{
		$totalTime = microtime(true) - MAKO_START;

		$executionTime = ['total' => $totalTime, 'details' => []];

		$otherTime = array_sum($this->timers);

		$detailedTime = $totalTime - $otherTime;

		$executionTime['details']['PHP'] = ['time' => $detailedTime, 'pct' => ($detailedTime / $totalTime * 100)];

		foreach($this->timers as $timer => $time)
		{
			$executionTime['details'][$timer] = ['time' => $time, 'pct' => ($time / $totalTime * 100)];
		}

		return $executionTime;
	}

	/**
	 * Renders the toolbar.
	 *
	 * @access public
	 * @return string
	 */
	public function render()
	{
		$executionTime = $this->calculateExecutionTime();

		$view = $this->view->create('mako-toolbar::toolbar',
		[
			'version'        => Mako::VERSION,
			'memory'         => $this->humanizer->fileSize(memory_get_peak_usage()),
			'time'           => round($executionTime['total'], 4),
			'execution_time' => $executionTime,
			'timers'         => $this->timers,
			'panels'         => $this->panels,
		]);

		return $view->render();
	}
}
