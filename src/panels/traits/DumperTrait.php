<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels\traits;

use Closure;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

/**
 * Dumper trait.
 *
 * @author Frederic G. Østby
 */
trait DumperTrait
{
	/**
	 * Styles the dumper.
	 *
	 * @access protected
	 * @param \Symfony\Component\VarDumper\Dumper\HtmlDumper $dumper HTML dumper
	 */
	protected function styleDumper(HtmlDumper $dumper)
	{
		$styles =
		[
			'default' => 'background-color:transparent; color:#FF8400; line-height:1.2em; font:14px Menlo, Monaco, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:99999; word-break: normal',
		];

		$dumper->setStyles($styles);
	}

	/**
	 * Returns a dumper closure.
	 *
	 * @access protected
	 * @return \Closure
	 */
	protected function getDumper(): Closure
	{
		$dumper = new HtmlDumper;

		$this->styleDumper($dumper);

		return function($variable) use ($dumper)
		{
			$dumper->dump((new VarCloner)->cloneVar($variable));
		};
	}
}
