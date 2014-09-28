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
		$settings = $this->getSettingsFor('foobar', 'master');
		$settings[PullPlugin::OPTION_DIRECTORY] = '.';
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

	/**
	 * @dataProvider getTriggerTestSettingsAndPayloads
	 * @param array $settings
	 * @param Payload $payload
	 * @param boolean $expectation
	 */
	public function testTrigger(array $settings, Payload $payload, $expectation) {
		$plugin = new PullPlugin();
		$plugin->initialize($settings);
		$result = $plugin->trigger($payload);
		$this->assertEquals($expectation, $result);
	}

	/**
	 * @return array
	 */
	public function getTriggerTestSettingsAndPayloads() {
		$emptySettings = array();
		$validSettings = $this->getSettingsFor('foobar', 'master');
		return array(
			// empty settings match any repository configuration
			array($emptySettings, $this->getPayloadFor($this->getSettingsFor('foobar', 'master'), 'master'), TRUE),
			array($emptySettings, $this->getPayloadFor($this->getSettingsFor('foobar', 'development'), 'development'), TRUE),
			array($emptySettings, $this->getPayloadFor($this->getSettingsFor('foobar2', 'development'), 'development'), TRUE),
			// matches branch and repository when set in settings
			array($validSettings, $this->getPayloadFor($validSettings, 'master'), TRUE),
			array($validSettings, $this->getPayloadFor($this->getSettingsFor('foobar', 'development'), 'master'), FALSE),
			array($validSettings, $this->getPayloadFor($this->getSettingsFor('foobar2', 'development'), 'development'), FALSE),
		);
	}

	/**
	 * @param string $url
	 * @param string $ref
	 * @return array
	 */
	protected function getSettingsFor($url, $ref) {
		return array(
			PullPlugin::OPTION_REPOSITORY => $url,
			PullPlugin::OPTION_BRANCH => $ref,
		);
	}

	/**
	 * @param array $settings
	 * @param string $masterbranch
	 * @return Payload
	 */
	protected function getPayloadFor($settings, $masterbranch) {
		list ($url, $ref) = array_values($settings);
		$repository = $this->getMock('NamelessCoder\\Gizzle\\Repository', array('getMasterBranch', 'getUrl'));
		$repository->expects($this->any())->method('getMasterBranch')->will($this->returnValue($masterbranch));
		$repository->expects($this->any())->method('getUrl')->will($this->returnValue($url));
		$payload = $this->getMock('NamelessCoder\\Gizzle\\Payload', array('getRepository', 'getRef'), array(), '', FALSE);
		$payload->expects($this->once())->method('getRef')->will($this->returnValue('refs/heads/' . $ref));
		$payload->expects($this->any())->method('getRepository')->will($this->returnValue($repository));
		return $payload;
	}

}
