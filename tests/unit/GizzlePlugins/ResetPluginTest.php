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
use NamelessCoder\GizzleGitPlugins\GizzlePlugins\ResetPlugin;

/**
 * Class ResetPluginTest
 */
class ResetPluginTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider getSettingsAndExpectedCommands
	 * @param array $settings
	 * @param array $expectedCommand
	 */
	public function testProcess($settings, $expectedCommand) {
		$mock = $this->getMock('NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\ResetPlugin', array('resolveGitCommand', 'executeGitCommand'));
		$response = $this->getMock('NamelessCoder\\Gizzle\\Response', array('addOutputFromPlugin'));
		$response->expects($this->once())->method('addOutputFromPlugin')->with($mock, array());
		$payload = $this->getMock('NamelessCoder\\Gizzle\\Payload', array('getResponse'), array(), '', FALSE);
		$payload->expects($this->once())->method('getResponse')->will($this->returnValue($response));
		$mock->expects($this->once())->method('resolveGitCommand')->will($this->returnValue('git'));
		$mock->expects($this->once())->method('executeGitCommand')->with($expectedCommand)->will($this->returnValue(array()));
		$mock->initialize($settings);
		$mock->process($payload);
	}

	/**
	 * @return array
	 */
	public function getSettingsAndExpectedCommands() {
		return array(
			array(
				array(ResetPlugin::OPTION_DIRECTORY => __DIR__),
				array('cd', escapeshellarg(__DIR__), '&&', 'git', 'reset')
			),
			array(
				array(ResetPlugin::OPTION_DIRECTORY => __DIR__, ResetPlugin::OPTION_HARD => TRUE),
				array('cd', escapeshellarg(__DIR__), '&&', 'git', 'reset', ResetPlugin::COMMAND_HARD)
			),
			array(
				array(ResetPlugin::OPTION_DIRECTORY => __DIR__, ResetPlugin::OPTION_HEAD => 'HEAD^1'),
				array('cd', escapeshellarg(__DIR__), '&&', 'git', 'reset', 'HEAD^1')
			),
			array(
				array(ResetPlugin::OPTION_DIRECTORY => __DIR__, ResetPlugin::OPTION_HEAD => 'HEAD^1', ResetPlugin::OPTION_HARD => TRUE),
				array('cd', escapeshellarg(__DIR__), '&&', 'git', 'reset', 'HEAD^1', ResetPlugin::COMMAND_HARD)
			),
		);
	}

}
