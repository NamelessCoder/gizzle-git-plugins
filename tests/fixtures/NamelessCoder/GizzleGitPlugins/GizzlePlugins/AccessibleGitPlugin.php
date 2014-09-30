<?php
namespace NamelessCoder\GizzleGitPlugins\Tests\Fixtures\GizzlePlugins;

use NamelessCoder\Gizzle\PluginInterface;
use NamelessCoder\GizzleGitPlugins\GizzlePlugins\AbstractGitPlugin;
use NamelessCoder\GizzleGitPlugins\Resolver\GitCommandResolver;

/**
 * Class AccessibleGitPlugin
 */
class AccessibleGitPlugin extends AbstractGitPlugin implements PluginInterface {

	public function getGitCommandResolver() {
		return parent::getGitCommandResolver();
	}

	public function resolveGitCommand() {
		return parent::resolveGitCommand();
	}

	public function executeGitCommand($command) {
		return parent::executeGitCommand($command);
	}

	public function executeCommand($command, array &$output) {
		return parent::executeCommand($command, $output);
	}

}
