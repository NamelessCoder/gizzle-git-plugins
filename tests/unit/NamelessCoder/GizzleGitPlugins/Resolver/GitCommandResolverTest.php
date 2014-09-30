<?php
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
