<?php

namespace HuskyPress\Skins;

use Plugin_Upgrader_Skin;

defined('ABSPATH') || exit;

class RollbackSkin extends Plugin_Upgrader_Skin
{
	/**
	 * @return void
	 */
	public function header()
	{
		return;
	}

	/**
	 * @return void
	 */
	public function footer()
	{
		return;
	}

	/**
	 * Action to perform before an update.
	 *
	 * @return void
	 */
	public function before()
	{
		return;
	}

	/**
	 * Action to perform following a single plugin update.
	 *
	 * @return void
	 */
	public function after()
	{
		return;
	}

	/**
	 * @return void
	 */
	public function feedback($string, ...$args)
	{
		return;
	}
}