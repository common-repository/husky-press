<?php

namespace HuskyPress\Admin;

defined('ABSPATH') || exit;

class AdminHooks
{
	/**
	 * AdminHooks Constructor.
	 */
	public function __construct()
	{
		$this->run([
			new AdminAjax(),
			new AdminAssets(),
			new AdminMenu(),
		]);
	}

	/**
	 * Run all the runners.
	 *
	 * @param array $runners Instances of runner classes.
	 */
	private function run($runners)
	{
		foreach ($runners as $runner) {
			if (method_exists($runner, 'hooks')) {
				$runner->hooks();
			}
		}
	}
}