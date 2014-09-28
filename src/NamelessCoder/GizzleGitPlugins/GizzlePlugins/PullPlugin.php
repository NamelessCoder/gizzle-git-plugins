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
		if (FALSE === isset($this->settings[self::OPTION_REPOSITORY])) {
			$this->settings[self::OPTION_REPOSITORY] = $payload->getRepository()->getUrl();
		}
		if (FALSE === isset($this->settings[self::OPTION_BRANCH])) {
			$this->settings[self::OPTION_BRANCH] = $payload->getRepository()->getMasterBranch();
		}
		$matchesRepository = $payload->getRepository()->getUrl() === $this->settings[self::OPTION_REPOSITORY];
		$matchesBranch = $payload->getRef() === $this->settings[self::OPTION_BRANCH];
		return $matchesRepository && $matchesBranch;
	}

	/**
	 * Perform whichever task the Plugin should perform based
	 * on the payload's data.
	 *
	 * @param Payload $payload
	 * @return void
	 */
	public function process(Payload $payload) {
		if (FALSE === isset($this->settings[self::OPTION_DIRECTORY]) || TRUE === empty($this->settings[self::OPTION_DIRECTORY])) {
			throw new \RuntimeException('Git Pull Plugin requires at least a directory setting');
		}
		$git = $this->resolveGitCommand();
		$command = array(
			'cd', escapeshellarg($this->settings[self::OPTION_DIRECTORY]), '&&',
			$git, 'pull',
			escapeshellarg($payload->getRepository()->getUrl()), escapeshellarg($this->settings[self::OPTION_BRANCH])
		);
		$output = $this->executeGitCommand($command);
		$payload->getResponse()->addOutputFromPlugin($this, $output);
	}

}
