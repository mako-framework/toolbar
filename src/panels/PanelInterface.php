<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

/**
 * Panel interface.
 *
 * @author Frederic G. Østby
 */
interface PanelInterface
{
	/**
	 * Returns a unique panel id.
	 *
	 * @return string
	 */
	public function getId(): string;

	/**
	 * Returns the tab label.
	 *
	 * @return string
	 */
	public function getTabLabel(): string;

	/**
	 * Returns the rendered panel.
	 *
	 * @return string
	 */
	public function render(): string;
}
