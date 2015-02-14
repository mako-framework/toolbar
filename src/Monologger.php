<?php

/**
 * @copyright  Frederic G. Østby
 * @license    http://www.makoframework.com/license
 */

namespace mako\toolbar;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

/**
 * Monologger.
 *
 * @author  Frederic G. Østby
 */

class Monologger extends AbstractProcessingHandler
{
	/**
	 * Log entries.
	 *
	 * @var array
	 */

	protected $entries = [];

	/**
	 * {@inheritdoc}
	 */

	protected function write(array $record)
	{
		$this->entries[] = $record;
	}

	/**
	 * Returns the number of log entries.
	 *
	 * @access  public
	 * @return  int
	 */

	public function getEntryCount()
	{
		return count($this->entries);
	}

	/**
	 * Returns the log entries.
	 *
	 * @access  public
	 * @return  array
	 */

	public function getEntries()
	{
		return $this->entries;
	}
}