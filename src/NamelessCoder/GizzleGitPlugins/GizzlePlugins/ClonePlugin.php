<?php

/**
 * This file belongs to the namelesscoder/gizzle-git-plugins package
 *
 * Copyright (c) 2014, Claus Due
 *
 * Released under the MIT license, of which the full text
 * was distributed with this package in file LICENSE.txt
 */

namespace NamelessCoder\GizzleGitPlugins\GizzlePlugins;

use NamelessCoder\Gizzle\Payload;
use NamelessCoder\Gizzle\PluginInterface;

/**
 * Class ClonePlugin
 */
class ClonePlugin extends AbstractGitPlugin implements PluginInterface {

	const OPTION_DIRECTORY = 'directory';
	const OPTION_REPOSITORY = 'repository';
	const OPTION_BRANCH = 'branch';
	const OPTION_DEPTH = 'depth';

}
