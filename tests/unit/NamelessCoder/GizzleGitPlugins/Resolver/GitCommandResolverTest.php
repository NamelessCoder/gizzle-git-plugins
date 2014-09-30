<?php

/**
 * This file belongs to the namelesscoder/gizzle-git-plugins package
 *
 * Copyright (c) 2014, Claus Due
 *
 * Released under the MIT license, of which the full text
 * was distributed with this package in file LICENSE.txt
 */

namespace NamelessCoder\GizzleGitPlugins\Tests\Unit\Resolver;

use NamelessCoder\GizzleGitPlugins\Resolver\GitCommandResolver;

/**
 * Class GitCommandResolverTest
 */
class GitCommandResolverTest extends \PHPUnit_Framework_TestCase {

	public function testResolvesGitCommand() {
		$resolver = new GitCommandResolver();
		$expected = trim(shell_exec('which git'));
		$command = $resolver->resolveGitCommand();
		$this->assertEquals($expected, $command);
	}

}
