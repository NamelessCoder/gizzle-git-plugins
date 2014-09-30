<?php
namespace NamelessCoder\GizzleGitPlugins\GizzlePlugins;

use NamelessCoder\Gizzle\AbstractPlugin;
use NamelessCoder\Gizzle\Payload;
use NamelessCoder\Gizzle\PluginInterface;
use NamelessCoder\GizzleGitPlugins\Resolver\GitCommandResolver;

/**
 * Class AbstractGitPlugin
 */
abstract class AbstractGitPlugin extends AbstractPlugin implements PluginInterface {

	const OPTION_DIRECTORY = 'directory';
	const OPTION_REPOSITORY = 'remote';
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
	 * @return GitCommandResolver
	 */
	protected function getGitCommandResolver() {
		return new GitCommandResolver();
	}

	/**
	 * @return string
	 */
	protected function resolveGitCommand() {
		return $this->getGitCommandResolver()->resolveGitCommand();
	}

	/**
	 * @param mixed $command
	 * @return array
	 */
	protected function executeGitCommand($command) {
		if (TRUE === is_array($command)) {
			$command = implode(' ', $command);
		}
		$output = array();
		$code = $this->executeCommand($command, $output);
		if (0 < $code) {
			throw new \RuntimeException(sprintf('Command failed! Code %d, Message was: "%s"', $code, implode(PHP_EOL, $output)));
		}
		return $output;
	}

	/**
	 * @param string $command
	 * @param array $output
	 * @return integer
	 */
	protected function executeCommand($command, array &$output) {
		$code = 0;
		exec($command, $output,$code);
		return $code;
	}

}
