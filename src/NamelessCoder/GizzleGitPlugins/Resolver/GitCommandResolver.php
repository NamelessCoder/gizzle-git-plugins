<?php

/**
 * This file belongs to the namelesscoder/gizzle-git-plugins package
 *
 * Copyright (c) 2014, Claus Due
 *
 * Released under the MIT license, of which the full text
 * was distributed with this package in file LICENSE.txt
 */

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
