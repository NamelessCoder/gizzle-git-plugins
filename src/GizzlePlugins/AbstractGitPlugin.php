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

use NamelessCoder\Gizzle\AbstractPlugin;
use NamelessCoder\Gizzle\Payload;
use NamelessCoder\Gizzle\PluginInterface;
use NamelessCoder\GizzleGitPlugins\Resolver\GitCommandResolver;

/**
 * Class AbstractGitPlugin
 */
abstract class AbstractGitPlugin extends AbstractPlugin implements PluginInterface {

	const OPTION_ENABLED = 'enabled';
	const OPTION_DIRECTORY = 'directory';
	const OPTION_REPOSITORY = 'remote';
	const OPTION_BRANCH = 'branch';

	/**
	 * Analyse $payload and return TRUE if this plugin should
	 * be triggered in processing the payload.
	 *
	 * @param Payload $payload
	 * @return boolean
	 */
	public function trigger(Payload $payload) {
		$url = $payload->getRepository()->getUrl();
		$branch = $payload->getRepository()->getMasterBranch();
		$isEnabled = (boolean) $this->getSettingValue(self::OPTION_ENABLED, TRUE);
		$matchesRepository = $url === $this->getSettingValue(self::OPTION_REPOSITORY, $url);
		$matchesBranch = $payload->getRef() === 'refs/heads/' . $this->getSettingValue(self::OPTION_BRANCH, $branch);
		return ($matchesRepository && $matchesBranch && $isEnabled);
	}

	/**
	 * @return string
	 * @throws \InvalidArgumentException
	 */
	protected function getDirectorySettingOrFail($requireExistingDirectory = TRUE) {
		$directory = $this->getSettingValue(self::OPTION_DIRECTORY);
		if (TRUE === empty($directory)) {
			throw new \InvalidArgumentException('Plugin requires at least a directory setting');
		}
		if (TRUE === $requireExistingDirectory && FALSE === is_dir($directory)) {
			throw new \InvalidArgumentException('Directory does not exist: ' . $directory);
		}
		return $directory;
	}

	/**
	 * @return GitCommandResolver
	 */
	protected function getGitCommandResolver() {
		return new GitCommandResolver();
	}

	/**
	 * @return string
	 */
	protected function resolveGitCommand() {
		return $this->getGitCommandResolver()->resolveGitCommand();
	}

	/**
	 * @param mixed $command
	 * @return array
	 */
	protected function executeGitCommand($command) {
		if (TRUE === is_array($command)) {
			$command = implode(' ', $command);
		}
		$output = array();
		$code = $this->executeCommand($command, $output);
		if (0 < $code) {
			throw new \RuntimeException(
				sprintf('Git command "%s" failed! Code %d, Message was: "%s"', $command, $code, implode(PHP_EOL, $output))
			);
		}
		return $output;
	}

	/**
	 * @param string $command
	 * @param array $output
	 * @return integer
	 */
	protected function executeCommand($command, array &$output) {
		$code = 0;
		exec($command, $output,$code);
		return $code;
	}

}
