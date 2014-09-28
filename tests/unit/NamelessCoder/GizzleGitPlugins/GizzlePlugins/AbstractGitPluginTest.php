<?php
namespace NamelessCoder\GizzleGitPlugins\Tests\Unit\GizzlePlugins;

use NamelessCoder\GizzleGitPlugins\Tests\Fixtures\GizzlePlugins\AccessibleGitPlugin;

class AbstractGitPluginTest extends \PHPUnit_Framework_TestCase {

	public function testGetGitCommandResolverReturnsGitCommandResolver() {
		$plugin = $this->getMockForAbstractClass('NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\AbstractGitPlugin');
		$resolver = $this->callInaccessibleMethod($plugin, 'getGitCommandResolver');
		$this->assertInstanceOf('NamelessCoder\\GizzleGitPlugins\\Resolver\\GitCommandResolver', $resolver);
	}

	public function testResolveGitCommandDelegatesToGitCommandResolver() {
		$resolver = $this->getMock('NamelessCoder\\GizzleGitPlugins\\Resolver\\GitCommandResolver', array('resolveGitCommand'));
		$resolver->expects($this->once())->method('resolveGitCommand')->will($this->returnValue('foobar'));
		$plugin = $this->getMockForAbstractClass('NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\AbstractGitPlugin',
			array(), '', FALSE, FALSE, TRUE, array('getGitCommandResolver'));
		$plugin->expects($this->once())->method('getGitCommandResolver')->will($this->returnValue($resolver));
		$result = $this->callInaccessibleMethod($plugin, 'resolveGitCommand');
		$this->assertEquals('foobar', $result);
	}

	protected function callInaccessibleMethod($object, $method) {
		$reflection = new \ReflectionMethod($object, $method);
		$reflection->setAccessible(TRUE);
		return $reflection->invoke($object);
	}

}
