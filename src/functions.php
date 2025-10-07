<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar {

    use mako\application\CurrentApplication;
    use Psr\Log\LoggerInterface;

	use function is_string;
	use function var_export;

	/**
	 * Logs the provided value before returning it.
	 *
	 * @template T
	 * @param  T $value
	 * @return T
	 */
	function log(mixed $value, mixed $level = 'debug', ?callable $encoder = null): mixed
	{
		CurrentApplication::get()
		?->getContainer()
		->get(LoggerInterface::class)
		->log($level, ($encoder ? $encoder($value) : (is_string($value) ? $value : var_export($value, true))));

		return $value;
	}
}
