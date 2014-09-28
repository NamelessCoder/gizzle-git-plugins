<?php
namespace NamelessCoder\GizzleGitPlugins\GizzlePlugins;

use NamelessCoder\Gizzle\Payload;
use NamelessCoder\Gizzle\PluginInterface;

/**
 * Class PullPlugin
 */
class PullPlugin extends AbstractGitPlugin implements PluginInterface {

	const OPTION_DIRECTORY = 'directory';
	const OPTION_REPOSITORY = 'repository';
	const OPTION_BRANCH = 'branch';

	/**
	 * Analyse $payload and return TRUE if this plugin should
	 * be triggered in processing the payload.
	 *
	 * @param Payload $payload
	 * @return boolean
	 */
	public function trigger(Payload $payload) {
		$url = $payload->getRepository()->getUrl();
		$branch = $payload->getRepository()->getMasterBranch();
		$matchesRepository = $url === $this->getSettingValue(self::OPTION_REPOSITORY, $url);
		$matchesBranch = $payload->getRef() === 'refs/heads/' . $this->getSettingValue(self::OPTION_BRANCH, $branch);
		return ($matchesRepository && $matchesBranch);
	}

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
