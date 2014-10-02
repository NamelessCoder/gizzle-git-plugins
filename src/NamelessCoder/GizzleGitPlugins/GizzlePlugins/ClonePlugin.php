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
 * Class ClonePlugin
 */
class ClonePlugin extends AbstractGitPlugin implements PluginInterface {

	const COMMAND = 'clone';
	const COMMAND_SINGLEBRANCH = '--single-branch';
	const COMMAND_DEPTH = '--depth';
	const OPTION_DEPTH = 'depth';
	const OPTION_SINGLE = 'single';

	/**
	 * @param Payload $payload
	 */
	public function process(Payload $payload) {
		$directory = $this->getDirectorySettingOrFail(FALSE);
		$directory = sprintf($directory, $payload->getRepository()->getName());
		$url = $this->getSettingValue(self::OPTION_REPOSITORY, $payload->getRepository()->getUrl());
		$depth = $this->getSettingValue(self::OPTION_DEPTH, 0);
		$git = $this->resolveGitCommand();
		$command = array($git, self::COMMAND, escapeshellarg($url), $directory);
		if (0 < $depth) {
			$command[] = self::COMMAND_DEPTH;
			$command[] = $depth;
		}
		if (TRUE === (boolean) $this->getSettingValue(self::OPTION_SINGLE, FALSE)) {
			$command[] = self::COMMAND_SINGLEBRANCH;
			$command[] = escapeshellarg($this->getSettingValue(self::OPTION_BRANCH, $payload->getRepository()->getMasterBranch()));
		}
		$output = $this->executeGitCommand($command);
		$payload->getResponse()->addOutputFromPlugin($this, $output);
	}

}
