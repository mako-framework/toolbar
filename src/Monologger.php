<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar;

use Monolog\Handler\AbstractProcessingHandler;

use function count;

/**
 * Monologger.
 *
 * @author Frederic G. Østby
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
	 * {@inheritDoc}
	 */
	protected function write(array $record): void
	{
		$this->entries[] = $record;
	}

	/**
	 * Returns the number of log entries.
	 *
	 * @return int
	 */
	public function getEntryCount(): int
	{
		return count($this->entries);
	}

	/**
	 * Returns the log entries.
	 *
	 * @return array
	 */
	public function getEntries(): array
	{
		return $this->entries;
	}
}
