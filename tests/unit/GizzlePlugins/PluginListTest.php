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

use NamelessCoder\Gizzle\PluginListInterface;
use NamelessCoder\GizzleGitPlugins\GizzlePlugins\PluginList;

/**
 * Class PluginListTest
 */
class PluginListTest extends \PHPUnit_Framework_TestCase {

	public function testInitializeSetsSettings() {
		$pluginList = new PluginList();
		$pluginList->initialize(array('foo' => 'bar'));
		$result = $this->getObjectAttribute($pluginList, 'settings');
		$this->assertEquals(array('foo' => 'bar'), $result);
	}

	public function testGetPluginClassNamesReturnsValidClasses() {
		$pluginList = new PluginList();
		$classes = $pluginList->getPluginClassNames();
		foreach ($classes as $class) {
			$implementsInterface = is_a($class, 'NamelessCoder\\Gizzle\\PluginInterface', TRUE);
			$this->assertTrue(class_exists($class), 'Class "' . $class . '" does not exist');
			$this->assertTrue($implementsInterface, 'Class "' . $class . '" is not a valid Gizzle plugin');
		}
	}

}
