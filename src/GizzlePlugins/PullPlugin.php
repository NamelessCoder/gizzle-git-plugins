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
 * Class PullPlugin
 */
class PullPlugin extends AbstractGitPlugin implements PluginInterface {

	const COMMAND = 'pull';
	const COMMAND_REBASE = '--rebase';
	const OPTION_REBASE = 'rebase';

	/**
	 * Perform whichever task the Plugin should perform based
	 * on the payload's data.
	 *
	 * @param Payload $payload
	 * @return void
	 */
	public function process(Payload $payload) {
		$directory = $this->getDirectorySettingOrFail();
		$branch = $this->getSettingValue(self::OPTION_BRANCH, $payload->getRepository()->getMasterBranch());
		$url = $this->getSettingValue(self::OPTION_REPOSITORY, $payload->getRepository()->getUrl());
		$git = $this->resolveGitCommand();
		$command = array(
			'cd', escapeshellarg($directory), '&&',
			$git, self::COMMAND, escapeshellarg($url), escapeshellarg($branch)
		);
		if (TRUE === (boolean) $this->getSettingValue(self::OPTION_REBASE, FALSE)) {
			$command[] = self::COMMAND_REBASE;
		}
		$output = $this->executeGitCommand($command);
		$payload->getResponse()->addOutputFromPlugin($this, $output);
	}

}
