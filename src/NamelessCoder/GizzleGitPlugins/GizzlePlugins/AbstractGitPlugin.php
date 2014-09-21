<?php
namespace NamelessCoder\GizzleGitPlugins\GizzlePlugins;

use NamelessCoder\GizzleGitPlugins\Resolver\GitCommandResolver;

/**
 * Class AbstractGitPlugin
 */
abstract class AbstractGitPlugin {

	/**
	 * @return string
	 */
	protected function resolveGitCommand() {
		$commandResolver = new GitCommandResolver();
		return $commandResolver->resolveGitCommand();
	}

}
