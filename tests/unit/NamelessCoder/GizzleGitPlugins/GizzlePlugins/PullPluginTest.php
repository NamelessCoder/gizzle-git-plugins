<?php
namespace NamelessCoder\GizzleGitPlugins\Tests\Unit\GizzlePlugins;
use NamelessCoder\Gizzle\Payload;
use NamelessCoder\GizzleGitPlugins\GizzlePlugins\PullPlugin;

/**
 * Class PullPluginTest
 */
class PullPluginTest extends \PHPUnit_Framework_TestCase {

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
