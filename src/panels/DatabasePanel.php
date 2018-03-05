<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\database\ConnectionManager;
use mako\view\ViewFactory;
use SqlFormatter;

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
	 * Constructor.
	 *
	 * @param \mako\view\ViewFactory           $view     View factory instance
	 * @param \mako\database\ConnectionManager $database Connection manager instance
	 */
	public function __construct(ViewFactory $view, ConnectionManager $database)
	{
		parent::__construct($view);

		$this->database = $database;
	}

	/**
	 * Returns the total query count.
	 *
	 * @return int
	 */
	public function getTotalQueryCount(): int
	{
		return count($this->database->getLogs(false));
	}

	/**
	 * Returns the total query time.
	 *
	 * @return float
	 */
	public function getTotalQueryTime(): float
	{
		return array_sum(array_column($this->database->getLogs(false), 'time'));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTabLabel(): string
	{
		if(($totalQueries = $this->getTotalQueryCount()) === 0)
		{
			return sprintf('%u database queries', $totalQueries);
		}
		else
		{
			return sprintf('%u database queries ( %f seconds )', $totalQueries, round($this->getTotalQueryTime(), 4));
		}
	}

	/**
	 * Returns logs with syntax highlighted queries.
	 *
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
	 * {@inheritDoc}
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
