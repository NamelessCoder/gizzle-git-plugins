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
use NamelessCoder\GizzleGitPlugins\GizzlePlugins\CheckoutPlugin;

/**
 * Class CheckoutPluginTest
 */
class CheckoutPluginTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider getSettingsAndExpectedCommands
	 * @param array $settings
	 * @param array $expectedCommand
	 */
	public function testProcess($settings, $expectedCommand) {
		$mock = $this->getMock('NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\CheckoutPlugin',
			array('resolveGitCommand', 'executeGitCommand'));
		$repository = $this->getMock('NamelessCoder\\Gizzle\\Repository', array('getMasterBranch'));
		$response = $this->getMock('NamelessCoder\\Gizzle\\Response', array('addOutputFromPlugin'));
		$response->expects($this->once())->method('addOutputFromPlugin')->with($mock);
		$repository->expects($this->any())->method('getMasterBranch')->will($this->returnValue('master'));
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
				array(CheckoutPlugin::OPTION_DIRECTORY => '.', CheckoutPlugin::OPTION_BRANCH => 'master'),
				array('git', CheckoutPlugin::COMMAND, escapeshellarg('master'))
			),
			array(
				array(CheckoutPlugin::OPTION_DIRECTORY => '.'),
				array('git', CheckoutPlugin::COMMAND, escapeshellarg('master'))
			),
			array(
				array(
					CheckoutPlugin::OPTION_DIRECTORY => '.',
					CheckoutPlugin::OPTION_BRANCH => 'master',
					CheckoutPlugin::OPTION_NEWBRANCH => TRUE
				),
				array(
					'git',
					CheckoutPlugin::COMMAND,
					CheckoutPlugin::COMMAND_NEWBRANCH,
					escapeshellarg('master')
				)
			),
			array(
				array(
					CheckoutPlugin::OPTION_DIRECTORY => '.',
					CheckoutPlugin::OPTION_BRANCH => 'master',
					CheckoutPlugin::OPTION_STARTPOINT => 'refname'
				),
				array(
					'git',
					CheckoutPlugin::COMMAND,
					escapeshellarg('master'),
					escapeshellarg('refname')
				)
			),
			array(
				array(
					CheckoutPlugin::OPTION_DIRECTORY => '.',
					CheckoutPlugin::OPTION_BRANCH => 'master',
					CheckoutPlugin::OPTION_DETACH => TRUE,
					CheckoutPlugin::OPTION_ORPHAN => TRUE
				),
				array(
					'git',
					CheckoutPlugin::COMMAND,
					CheckoutPlugin::COMMAND_ORPHAN,
					CheckoutPlugin::COMMAND_DETACH,
					escapeshellarg('master')
				)
			),
			array(
				array(
					CheckoutPlugin::OPTION_DIRECTORY => '.',
					CheckoutPlugin::OPTION_BRANCH => 'master',
					CheckoutPlugin::OPTION_TRACK => TRUE,
					CheckoutPlugin::OPTION_UPSTREAM => 'upstream-branch'
				),
				array(
					'git',
					CheckoutPlugin::COMMAND,
					CheckoutPlugin::COMMAND_TRACK,
					escapeshellarg('upstream-branch'),
					escapeshellarg('master')
				)
			),
			array(
				array(
					CheckoutPlugin::OPTION_DIRECTORY => '.',
					CheckoutPlugin::OPTION_BRANCH => 'master',
					CheckoutPlugin::OPTION_TRACK => TRUE
				),
				array(
					'git',
					CheckoutPlugin::COMMAND,
					CheckoutPlugin::COMMAND_TRACK,
					escapeshellarg('master'),
					escapeshellarg('master')
				)
			),
			array(
				array(
					CheckoutPlugin::OPTION_DIRECTORY => '.',
					CheckoutPlugin::OPTION_BRANCH => 'master',
					CheckoutPlugin::OPTION_NOTRACK => TRUE
				),
				array(
					'git',
					CheckoutPlugin::COMMAND,
					CheckoutPlugin::COMMAND_NOTRACK,
					escapeshellarg('master')
				)
			),
			// check: track and notrack mutually exclusive with priority to notrack
			array(
				array(
					CheckoutPlugin::OPTION_DIRECTORY => '.',
					CheckoutPlugin::OPTION_BRANCH => 'master',
					CheckoutPlugin::OPTION_TRACK => TRUE,
					CheckoutPlugin::OPTION_NOTRACK => TRUE,
				),
				array(
					'git',
					CheckoutPlugin::COMMAND,
					CheckoutPlugin::COMMAND_NOTRACK,
					escapeshellarg('master')
				)
			),
			array(
				array(
					CheckoutPlugin::OPTION_DIRECTORY => '.',
					CheckoutPlugin::OPTION_BRANCH => 'master',
					CheckoutPlugin::OPTION_TRACK => TRUE,
					CheckoutPlugin::OPTION_FORCE => TRUE
				),
				array(
					'git',
					CheckoutPlugin::COMMAND,
					CheckoutPlugin::COMMAND_TRACK,
					escapeshellarg('master'),
					CheckoutPlugin::COMMAND_FORCE,
					escapeshellarg('master')
				)
			),
		);
	}

}
