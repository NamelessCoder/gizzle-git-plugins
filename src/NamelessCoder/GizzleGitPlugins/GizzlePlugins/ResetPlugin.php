<?php

/**
 * This file belongs to the namelesscoder/gizzle-git-plugins package
 *
 * Copyright (c) 2014, Claus Due
 *
 * Released under the MIT license, of which the full text
 * was distributed with this package in file LICENSE.txt
 */

namespace NamelessCoder\GizzleGitPlugins\GizzlePlugins;

use NamelessCoder\Gizzle\Payload;
use NamelessCoder\Gizzle\PluginInterface;

/**
 * Class ResetPlugin
 */
class ResetPlugin extends AbstractGitPlugin implements PluginInterface {

	const COMMAND = 'reset';
	const COMMAND_HARD = '--hard';
	const OPTION_HARD = 'hard';
	const OPTION_HEAD = 'head';

	/**
	 * @param Payload $payload
	 */
	public function process(Payload $payload) {
		$directory = $this->getDirectorySettingOrFail();
		$target = $this->getSettingValue(self::OPTION_HEAD);
		$git = $this->resolveGitCommand();
		$command = array('cd', escapeshellarg($directory), '&&', $git, self::COMMAND);
		if (FALSE === empty($target)) {
			$command[] = $target;
		}
		if (TRUE === (boolean) $this->getSettingValue(self::OPTION_HARD)) {
			$command[] = self::COMMAND_HARD;
		}
		$output = $this->executeGitCommand($command);
		$payload->getResponse()->addOutputFromPlugin($this, $output);
	}


}
