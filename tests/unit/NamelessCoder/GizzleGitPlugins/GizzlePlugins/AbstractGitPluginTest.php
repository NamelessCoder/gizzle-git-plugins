<?php

/**
 * This file belongs to the namelesscoder/gizzle-git-plugins package
 *
 * Copyright (c) 2014, Claus Due
 *
 * Released under the MIT license, of which the full text
 * was distributed with this package in file LICENSE.txt
 */

namespace NamelessCoder\GizzleGitPlugins\Tests\Unit\GizzlePlugins;

use NamelessCoder\Gizzle\Payload;
use NamelessCoder\GizzleGitPlugins\GizzlePlugins\AbstractGitPlugin;
use NamelessCoder\GizzleGitPlugins\Tests\Fixtures\GizzlePlugins\AccessibleGitPlugin;

class AbstractGitPluginTest extends \PHPUnit_Framework_TestCase {

	public function testGetAndDirectorySettingOrFailThrowsExceptionIfMissingSetting() {
		$plugin = new AccessibleGitPlugin();
		$this->setExpectedException('InvalidArgumentException');
		$plugin->getDirectorySettingOrFail();
	}

	public function testGetAndDirectorySettingOrFailThrowsExceptionIfDirectoryMissing() {
		$plugin = new AccessibleGitPlugin();
		$plugin->initialize(array(AccessibleGitPlugin::OPTION_DIRECTORY => 'issetbutdoesnotexist'));
		$this->setExpectedException('InvalidArgumentException');
		$plugin->getDirectorySettingOrFail();
	}

	public function testExecuteCommandExecutesCommandAndSetsOutput() {
		$plugin = new AccessibleGitPlugin();
		$command = 'pwd';
		$output = array();
		$expectedCode = 0;
		$result = $plugin->executeCommand($command, $output);
		$this->assertEquals($expectedCode, $result);
		$this->assertNotEmpty($output);
	}

	public function testGetGitCommandResolverReturnsGitCommandResolver() {
		$plugin = new AccessibleGitPlugin();
		$resolver = $plugin->getGitCommandResolver();
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

	/**
	 * @param mixed $command
	 * @param integer $returnCode
	 * @param string $expectedException
	 * @dataProvider getGitCommandReturnCodesAndExpectedException
	 */
	public function testExecuteGitCommand($command, $returnCode, $expectedException) {
		$expectedCommand = TRUE === is_array($command) ? implode(' ', $command) : $command;
		$plugin = $this->getMockForAbstractClass('NamelessCoder\\GizzleGitPlugins\\Tests\\Fixtures\\GizzlePlugins\\AccessibleGitPlugin',
			array(), '', FALSE, FALSE, FALSE, array('executeCommand'));
		$plugin->expects($this->once())->method('executeCommand')->with($expectedCommand)->will($this->returnValue($returnCode));
		if (0 < $returnCode) {
			$this->setExpectedException($expectedException);
		}
		$plugin->executeGitCommand($command);
	}

	/**
	 * @return array
	 */
	public function getGitCommandReturnCodesAndExpectedException() {
		return array(
			array('pull', 0, NULL),
			array(array('pull', 'origin', 'master'), 0, NULL),
			array('pull', 1, 'RuntimeException'),
			array('pull', 128, 'RuntimeException'),
		);
	}

	/**
	 * @dataProvider getTriggerTestSettingsAndPayloads
	 * @param array $settings
	 * @param Payload $payload
	 * @param boolean $expectation
	 */
	public function testTrigger(array $settings, Payload $payload, $expectation) {
		$plugin = new AccessibleGitPlugin();
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
			// enabled setting FALSE ignores any Payload
			array(array(AbstractGitPlugin::OPTION_ENABLED => FALSE), $this->getPayloadFor($this->getSettingsFor('any', 'development'), 'development'), FALSE),
			array(array(AbstractGitPlugin::OPTION_ENABLED => FALSE), $this->getPayloadFor($this->getSettingsFor('any', 'master'), 'development'), FALSE),
			array(array(AbstractGitPlugin::OPTION_ENABLED => FALSE), $this->getPayloadFor($this->getSettingsFor('any', 'development'), 'master'), FALSE),
			array(array(AbstractGitPlugin::OPTION_ENABLED => FALSE), $this->getPayloadFor($this->getSettingsFor('any', 'master'), 'master'), FALSE),
			array(array(AbstractGitPlugin::OPTION_ENABLED => FALSE), $this->getPayloadFor($this->getSettingsFor('other', 'master'), 'master'), FALSE),
			array(array(AbstractGitPlugin::OPTION_ENABLED => FALSE), $this->getPayloadFor($this->getSettingsFor('other', 'development'), 'master'), FALSE),
			array(array(AbstractGitPlugin::OPTION_ENABLED => FALSE), $this->getPayloadFor($this->getSettingsFor('other', 'master'), 'development'), FALSE),
			array(array(AbstractGitPlugin::OPTION_ENABLED => FALSE), $this->getPayloadFor($this->getSettingsFor('other', 'development'), 'development'), FALSE),
		);
	}

	/**
	 * @param string $url
	 * @param string $ref
	 * @return array
	 */
	protected function getSettingsFor($url, $ref) {
		return array(
			AbstractGitPlugin::OPTION_REPOSITORY => $url,
			AbstractGitPlugin::OPTION_BRANCH => $ref,
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

	protected function callInaccessibleMethod($object, $method) {
		$arguments = array_slice(func_get_args(), 2);
		array_unshift($arguments, $object);
		$reflection = new \ReflectionMethod($object, $method);
		$reflection->setAccessible(TRUE);
		return call_user_func_array(array($reflection, 'invoke'), $arguments);

	}

}
