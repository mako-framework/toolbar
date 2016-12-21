<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\view\ViewFactory;

/**
 * Panel interface.
 *
 * @author Frederic G. Østby
 */
interface PanelInterface
{
	public function getId(): string;
	public function getTabLabel(): string;
	public function render(): string;
}
