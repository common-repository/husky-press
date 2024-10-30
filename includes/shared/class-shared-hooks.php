<?php

namespace HuskyPress\Shared;

defined('ABSPATH') || exit;

class SharedHooks
{
	/**
	 * SharedHooks Constructor.
	 */
	public function __construct()
	{
		$this->run([
			new TrackErrors(),
			new SafeUpdate(),
			new Backups(),
			new Snapshot(),
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