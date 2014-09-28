<?php
namespace NamelessCoder\GizzleGitPlugins\GizzlePlugins;

use NamelessCoder\Gizzle\AbstractPlugin;
use NamelessCoder\Gizzle\PluginInterface;
use NamelessCoder\GizzleGitPlugins\Resolver\GitCommandResolver;

/**
 * Class AbstractGitPlugin
 */
abstract class AbstractGitPlugin extends AbstractPlugin implements PluginInterface {

	/**
	 * @return string
	 */
	protected function resolveGitCommand() {
		$commandResolver = new GitCommandResolver();
		return $commandResolver->resolveGitCommand();
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
		$code = 0;
		exec($command, $output,$code);
		if (0 < $code) {
			throw new \RuntimeException(sprintf('Git pull failed! Code %d, Message was: "%s"', $code, implode(PHP_EOL, $output)));
		}
		return $output;
	}

}
