<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar {

    use mako\application\CurrentApplication;
    use Psr\Log\LoggerInterface;

	use function var_export;

	/**
	 * Logs the provided value before returning it.
	 *
	 * @template T
	 * @param  T $value
	 * @return T
	 */
	function debug(mixed $value, ?callable $encoder = null): mixed
	{
		CurrentApplication::get()
		?->getContainer()
		->get(LoggerInterface::class)
		->debug($encoder ? $encoder($value) : var_export($value, true));

		return $value;
	}
}
