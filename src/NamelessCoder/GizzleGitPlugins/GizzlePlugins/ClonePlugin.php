<?php
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
