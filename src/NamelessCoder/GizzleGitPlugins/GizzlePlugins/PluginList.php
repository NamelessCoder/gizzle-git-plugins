<?php
namespace NamelessCoder\GizzleGitPlugins\GizzlePlugins;

use NamelessCoder\Gizzle\PluginListInterface;

/**
 * Class PluginList
 */
class PluginList implements PluginListInterface {

	/**
	 * @var array
	 */
	protected $settings = array();

	/**
	 * Initialize the plugin with an array of settings.
	 *
	 * @param array $settings
	 * @return void
	 */
	public function initialize(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * Get all class names of plugins delivered from implementer package.
	 *
	 * @return string[]
	 */
	public function getPluginClassNames() {
		$plugins = array();
		if (TRUE === $this->isEnabled('NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\ClonePlugin')) {
			$plugins[] = 'NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\ClonePlugin';
		}
		if (TRUE === $this->isEnabled('NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\CheckoutPlugin')) {
			$plugins[] = 'NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\CheckoutPlugin';
		}
		if (TRUE === $this->isEnabled('NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\PullPlugin')) {
			$plugins[] = 'NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\PullPlugin';
		}
		if (TRUE === $this->isEnabled('NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\CommitPlugin')) {
			$plugins[] = 'NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\CommitPlugin';
		}
		if (TRUE === $this->isEnabled('NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\PushPlugin')) {
			$plugins[] = 'NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\PushPlugin';
		}
		return $plugins;
	}

	/**
	 * @param string $class
	 * @return boolean
	 */
	protected function isEnabled($class) {
		$class = 'NamelessCoder\\GizzleTYPO3Plugins\\GizzlePlugins\\ExtensionRepositoryReleasePlugin';
		return (boolean) TRUE === isset($this->settings[$class]['enabled']) ? $this->settings[$class]['enabled'] : TRUE;
	}

}
