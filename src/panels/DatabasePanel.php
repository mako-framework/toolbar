<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use SqlFormatter;

use mako\database\ConnectionManager;
use mako\toolbar\panels\Panel;
use mako\toolbar\panels\PanelInterface;
use mako\utility\Arr;
use mako\view\ViewFactory;

/**
 * Database panel.
 *
 * @author Frederic G. Østby
 */
class DatabasePanel extends Panel implements PanelInterface
{
	/**
	 * Conenction manager instance.
	 *
	 * @var array
	 */
	protected $database;

	/**
	 * Total queries.
	 *
	 * @var int
	 */
	protected $totalQueries;

	/**
	 * Total query time.
	 *
	 * @var float
	 */
	protected $totalQueryTime;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @param \mako\view\ViewFactory           $view     View factory instance
	 * @param \mako\database\ConnectionManager $database Connection manager instance
	 */
	public function __construct(ViewFactory $view, ConnectionManager $database)
	{
		parent::__construct($view);

		$this->database = $database;

		$this->collectQueryStats();
	}

	/**
	 * Collects query stats.
	 *
	 * @access protected
	 */
	protected function collectQueryStats()
	{
		$this->totalQueries = 0;

		$this->totalQueryTime = 0;

		foreach($this->database->getLogs() as $queryLog)
		{
			$this->totalQueries += count($queryLog);

			$this->totalQueryTime += array_sum(Arr::pluck($queryLog, 'time'));
		}
	}

	/**
	 * Returns the total query time.
	 *
	 * @access public
	 * @return float
	 */
	public function getTotalQueryTime(): float
	{
		return $this->totalQueryTime;
	}

	/**
	 * Returns the tab label.
	 *
	 * @access public
	 * @return string
	 */
	public function getTabLabel(): string
	{
		if($this->totalQueries === 0)
		{
			return sprintf('%u database queries', $this->totalQueries);
		}
		else
		{
			return sprintf('%u database queries ( %f seconds )', $this->totalQueries, round($this->totalQueryTime, 4));
		}
	}

	/**
	 * Returns logs with syntax highlighted queries.
	 *
	 * @access public
	 * @param  array $logs Query logs
	 * @return array
	 */
	protected function getFormattedLog(array $logs): array
	{
		// Configure the SQL formatter

		SqlFormatter::$pre_attributes = 'style="color: black; background-color: transparent;"';

		// Add syntax highlighting to SQL queries

		foreach($logs as &$log)
		{
			foreach($log as $key => $query)
			{
				$log[$key]['query'] = SqlFormatter::format($query['query']);
			}
		}

		return $logs;
	}

	/**
	 * Returns the rendered panel.
	 *
	 * @access public
	 * @return string
	 */
	public function render(): string
	{
		$view = $this->view->create('mako-toolbar::panels.database',
		[
			'logs' => $this->getFormattedLog($this->database->getLogs()),
		]);

		return $view->render();
	}
}
