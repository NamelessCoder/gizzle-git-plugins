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
use NamelessCoder\GizzleGitPlugins\GizzlePlugins\PullPlugin;

/**
 * Class PullPluginTest
 */
class PullPluginTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider getSettingsAndExpectedCommands
	 * @param array $settings
	 * @param array $expectedCommand
	 */
	public function testProcess($settings, $expectedCommand) {
		$mock = $this->getMock('NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\PullPlugin', array('resolveGitCommand', 'executeGitCommand'));
		$repository = $this->getMock('NamelessCoder\\Gizzle\\Repository', array('getMasterBranch', 'getUrl'));
		$response = $this->getMock('NamelessCoder\\Gizzle\\Response', array('addOutputFromPlugin'));
		$response->expects($this->once())->method('addOutputFromPlugin')->with($mock, array());
		$repository->expects($this->any())->method('getMasterBranch')->will($this->returnValue('master'));
		$repository->expects($this->any())->method('getUrl')->will($this->returnValue('foobar'));
		$payload = $this->getMock('NamelessCoder\\Gizzle\\Payload', array('getRepository', 'getResponse'), array(), '', FALSE);
		$payload->expects($this->any())->method('getRepository')->will($this->returnValue($repository));
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
				array(
					PullPlugin::OPTION_DIRECTORY => '.',
					PullPlugin::OPTION_REPOSITORY => 'foobar'
				),
				array('cd', "'.'", '&&', 'git', 'pull', escapeshellarg('foobar'), escapeshellarg('master'))
			),
			array(
				array(
					PullPlugin::OPTION_DIRECTORY => '.',
					PullPlugin::OPTION_REPOSITORY => 'foobar',
					PullPlugin::OPTION_REBASE => TRUE
				),
				array('cd', "'.'", '&&', 'git', 'pull', escapeshellarg('foobar'), escapeshellarg('master'), PullPlugin::COMMAND_REBASE)
			),
		);
	}

}
