<?php defined('SYSPATH') OR die('No direct access allowed.');

abstract class Kohana_Plugin_Manager
{
	/**
	 * Configure the plugin manager with a config specific to the driver
	 * @param $config
	 */
	abstract public function __construct($config);

	/**
	 * Get all active plugins.
	 *
	 * @return array
	 */
	abstract public function get_active();

	/**
	 * Get stored info about a plugin.
	 * returns an array with these keys: name, installed, active.
	 * returns false if no plugin was found.
	 *
	 * @param string $plugin Name of the plugin
	 * @return array|false
	 */
	abstract public function get($plugin);

	/**
	 * Get information on all listed plugins.
	 *
	 * @see Plugin_Manager::get()
	 */
	abstract public function get_all();

	/**
	 * Activate a plugin.
	 *
	 * Throws an exception if the plugin hasn't been listed.
	 * Returns false if the plugin hasn't been installed yet.
	 *
	 * @param string $plugin Name of the plugin
	 * @return bool
	 */
	abstract public function activate($plugin);

	/**
	 * Deactivate a plugin.
	 *
	 * @param string $plugin Name of the plugin
	 * @return bool
	 */
	abstract public function deactivate($plugin);

	/**
	 * List a plugin.
	 *
	 * returns false if there's already a plugin listed with that name.
	 *
	 * @param string $plugin Name of the plugin
	 * @param bool $installed Is the plugin already installed?
	 * @return bool
	 */
	abstract public function add($plugin, $installed=false);

	/**
	 * Mark a plugin as installed.
	 *
	 * returns false if already installed.
	 *
	 * @param string $plugin Name of the plugin
	 * @return bool
	 */
	abstract public function install($plugin);

	/**
	 * Check if a plugin was installed.
	 *
	 * @param string $plugin Name of the plugin
	 * @return bool
	 */
	abstract public function is_installed($plugin);

	/**
	 * Initiate a plugin manager.
	 *
	 * @param string $type The name of the driver
	 * @param array $config array Config options for the specific driver
	 * @return Config_Manager
	 */
	static public function factory($type, $config) {
		$class = 'Plugin_Manager_' . $type;
		return new $class($config);
	}
}
