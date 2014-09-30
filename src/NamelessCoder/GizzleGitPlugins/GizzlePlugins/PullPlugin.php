<?php
namespace NamelessCoder\GizzleGitPlugins\GizzlePlugins;

use NamelessCoder\Gizzle\Payload;
use NamelessCoder\Gizzle\PluginInterface;

/**
 * Class PullPlugin
 */
class PullPlugin extends AbstractGitPlugin implements PluginInterface {

	/**
	 * Perform whichever task the Plugin should perform based
	 * on the payload's data.
	 *
	 * @param Payload $payload
	 * @return void
	 */
	public function process(Payload $payload) {
		$directory = $this->getSettingValue(self::OPTION_DIRECTORY);
		$branch = $this->getSettingValue(self::OPTION_BRANCH, $payload->getRepository()->getMasterBranch());
		$url = $payload->getRepository()->getUrl();
		if (TRUE === empty($directory)) {
			throw new \RuntimeException('Git Pull Plugin requires at least a directory setting');
		}
		$git = $this->resolveGitCommand();
		$command = array(
			'cd', escapeshellarg($directory), '&&',
			$git, 'pull', escapeshellarg($url), escapeshellarg($branch)
		);
		$output = $this->executeGitCommand($command);
		$payload->getResponse()->addOutputFromPlugin($this, $output);
	}

}
