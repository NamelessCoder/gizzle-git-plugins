<?php
namespace NamelessCoder\GizzleGitPlugins\Resolver;

/**
 * Class GitCommandResolver
 */
class GitCommandResolver {

	/**
	 * @return string
	 */
	public function resolveGitCommand() {
		return trim(shell_exec('which git'));
	}

}
