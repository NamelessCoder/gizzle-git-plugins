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

use NamelessCoder\GizzleGitPlugins\GizzlePlugins\ClonePlugin;

/**
 * Class ClonePluginTest
 */
class ClonePluginTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider getSettingsAndExpectedCommands
	 * @param array $settings
	 * @param array $expectedCommand
	 */
	public function testProcess($settings, $expectedCommand) {
		$mock = $this->getMock('NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\ClonePlugin', array('resolveGitCommand', 'executeGitCommand'));
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
				array(ClonePlugin::OPTION_DIRECTORY => '.', ClonePlugin::OPTION_BRANCH => 'master', ClonePlugin::OPTION_REPOSITORY => 'foobar'),
				array('git', 'clone', "'foobar'", '.')
			),
			array(
				array(ClonePlugin::OPTION_DIRECTORY => '.', ClonePlugin::OPTION_BRANCH => 'master', ClonePlugin::OPTION_REPOSITORY => 'foobar', ClonePlugin::OPTION_SINGLE => TRUE),
				array('git', 'clone', "'foobar'", '.', ClonePlugin::COMMAND_SINGLEBRANCH, "'master'")
			),
			array(
				array(ClonePlugin::OPTION_DIRECTORY => '.', ClonePlugin::OPTION_BRANCH => 'master', ClonePlugin::OPTION_REPOSITORY => 'foobar', ClonePlugin::OPTION_DEPTH => 11),
				array('git', 'clone', "'foobar'", '.', ClonePlugin::COMMAND_DEPTH, 11)
			),
		);
	}

}
