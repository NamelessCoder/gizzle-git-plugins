<?php
namespace NamelessCoder\GizzleGitPlugins\Tests\Unit\GizzlePlugins;

use NamelessCoder\Gizzle\Payload;
use NamelessCoder\GizzleGitPlugins\GizzlePlugins\PullPlugin;

/**
 * Class PullPluginTest
 */
class PullPluginTest extends \PHPUnit_Framework_TestCase {

	public function testProcessCallsExpectedMethodSequence() {
		$expectedCommandArray = array('cd', "'.'", '&&', 'foobar', 'pull', "'foobar'", "'master'");
		$plugin = $this->getMock('NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\PullPlugin', array('resolveGitCommand', 'executeGitCommand'));
		$plugin->expects($this->once())->method('resolveGitCommand')->will($this->returnValue('foobar'));
		$plugin->expects($this->once())->method('executeGitCommand')->with($expectedCommandArray)->will($this->returnValue(array('foo')));
		$response = $this->getMock('NamelessCoder\\Gizzle\\Response', array('addOutputFromPlugin'));
		$response->expects($this->once())->method('addOutputFromPlugin')->with($plugin, array('foo'));
		$repository = $this->getMock('NamelessCoder\\Gizzle\\Repository', array('getMasterBranch', 'getUrl'));
		$repository->expects($this->any())->method('getMasterBranch')->will($this->returnValue('master'));
		$repository->expects($this->any())->method('getUrl')->will($this->returnValue('foobar'));
		$payload = $this->getMock('NamelessCoder\\Gizzle\\Payload', array('getRepository', 'getResponse'), array(), '', FALSE);
		$payload->expects($this->any())->method('getRepository')->will($this->returnValue($repository));
		$payload->expects($this->once())->method('getResponse')->will($this->returnValue($response));
		$url = 'foobar';
		$ref = 'master';
		$settings = array(PullPlugin::OPTION_REPOSITORY => $url, PullPlugin::OPTION_BRANCH => $ref, PullPlugin::OPTION_DIRECTORY => '.');
		$plugin->initialize($settings);
		$plugin->process($payload);
	}

	public function testProcessThrowsErrorWhenNoDirectoryIsConfigured() {
		$plugin = new PullPlugin();
		$repository = $this->getMock('NamelessCoder\\Gizzle\\Repository', array('getMasterBranch', 'getUrl'));
		$repository->expects($this->any())->method('getMasterBranch')->will($this->returnValue('master'));
		$repository->expects($this->any())->method('getUrl')->will($this->returnValue('foobar'));
		$payload = $this->getMock('NamelessCoder\\Gizzle\\Payload', array('getRepository'), array(), '', FALSE);
		$payload->expects($this->any())->method('getRepository')->will($this->returnValue($repository));
		$this->setExpectedException('RuntimeException');
		$plugin->process($payload);
	}

}
