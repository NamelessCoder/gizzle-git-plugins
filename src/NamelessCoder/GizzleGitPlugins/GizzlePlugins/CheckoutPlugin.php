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
 * Class CheckoutPlugin
 */
class CheckoutPlugin extends AbstractGitPlugin implements PluginInterface {

	const COMMAND = 'checkout';
	const COMMAND_FORCE = '--force';
	const COMMAND_TRACK = '--track';
	const COMMAND_NOTRACK = '--no-track';
	const COMMAND_DETACH = '--detach';
	const COMMAND_ORPHAN = '--orphan';
	const COMMAND_NEWBRANCH = '-b';
	const OPTION_NEWBRANCH = 'newbranch';
	const OPTION_STARTPOINT = 'startpoint';
	const OPTION_UPSTREAM = 'upstream';
	const OPTION_FORCE = 'force';
	const OPTION_TRACK = 'track';
	const OPTION_NOTRACK = 'notrack';
	const OPTION_DETACH = 'detach';
	const OPTION_ORPHAN = 'orphan';

	/**
	 * @param Payload $payload
	 */
	public function process(Payload $payload) {
		$directory = $this->getDirectorySettingOrFail();
		$directory = sprintf($directory, $payload->getRepository()->getName());
		$git = $this->resolveGitCommand();
		$branch = $this->getSettingValue(self::OPTION_BRANCH, $payload->getRepository()->getMasterBranch());
		$track = $this->getSettingValue(self::OPTION_TRACK, FALSE);
		$noTrack = $this->getSettingValue(self::OPTION_NOTRACK, FALSE);
		$start = $this->getSettingValue(self::OPTION_STARTPOINT);
		$force = $this->getSettingValue(self::OPTION_FORCE, FALSE);
		$detach = $this->getSettingValue(self::OPTION_DETACH, FALSE);
		$orphan = $this->getSettingValue(self::OPTION_ORPHAN, FALSE);
		$newBranch = $this->getSettingValue(self::OPTION_NEWBRANCH, FALSE);
		$command = array($git, self::COMMAND);
		if (TRUE === $noTrack) {
			$command[] = self::COMMAND_NOTRACK;
		} elseif (TRUE === $track) {
			$trackBranch = $this->getSettingValue(self::OPTION_UPSTREAM, $payload->getRepository()->getMasterBranch());
			$command[] = self::COMMAND_TRACK;
			$command[] = escapeshellarg($trackBranch);
		}
		if (TRUE === $force) {
			$command[] = self::COMMAND_FORCE;
		}
		if (TRUE === $orphan) {
			$command[] = self::COMMAND_ORPHAN;
		}
		if (TRUE === $detach) {
			$command[] = self::COMMAND_DETACH;
		}
		if (TRUE === $newBranch) {
			$command[] = self::COMMAND_NEWBRANCH;
		}
		$command[] = escapeshellarg($branch);
		if (NULL !== $start) {
			$command[] = escapeshellarg($start);
		}
		$output = array(
			'Executing Git checkout command: ' . implode(' ', $command)
		);
		$output = array_merge($output, $this->executeGitCommand($command));
		$payload->getResponse()->addOutputFromPlugin($this, $output);
	}

}
