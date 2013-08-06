<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohana_Plugins
{
    public static $actions = array();
	public static $current_action;
	public static $run_actions;

	protected static $_config = array();

	// Plugins
	public static $plugins_pool;
	public static $plugins_active;

	public static $plugins_dir;

	public static $errors;
	public static $messages;

	public static function factory($params = array())
	{
		return new Plugins($params);
	}

	public function __construct($params = array())
	{
		$config = Kohana::$config->load('plugins');

		if (array_key_exists("plugins_dir", $params))
		{
			$this->set_plugin_dir($params['plugins_dir']);
		}
		else
		{
			$this->set_plugin_dir($config->get("dir"));
		}

		// Do we have plugins?
		$this->find_plugins();

		// Include active plugins
		$this->include_plugins();
	}

	public function find_plugins()
	{
		$paths = Kohana::include_paths();

		foreach($paths as $path)
		{
			$dir = $path . self::$plugins_dir;
			// Instantiate a new directory iterator object
			$directory = new DirectoryIterator($dir);

			if ($directory->isDir())
			{
				foreach ($directory AS $plugin)
				{
					// Is directory?
					if ( $plugin->isDir() && !$plugin->isDot() )
					{
						// Include plugin config options
						$config = require_once $dir . $plugin->getFilename() . DIRECTORY_SEPARATOR . "config.php";

						// Store plugin in our pool
						self::$plugins_pool[ $plugin->getFilename() ]['plugin'] = $plugin->getFilename();
						self::$plugins_pool[ $plugin->getFilename() ]['path'] = $dir;

						// Plugin is enabled
						if ($config['plugin_status'] == "enabled")
						{
							// This is an active plugin, lets store it for inclusion
							self::$plugins_active[$plugin->getFilename()] = $config['plugin_name'];

							// Store any info in the config file with our plugin
							foreach ($config AS $k => $v)
							{
								self::$plugins_pool[$plugin->getFilename()]['plugin_info'][$k] = trim($v);
							}
						}
					}
				}
			}
		}
	}

	public function include_plugins()
	{
		if ( self::$plugins_active AND !empty(self::$plugins_active) )
		{
			foreach (self::$plugins_active AS $name => $value)
			{
				include_once self::$plugins_pool[$name]['path'] . $name . DIRECTORY_SEPARATOR . "init.php";

				self::do_action("install_" . $name);
			}
		}
	}

	public function plugin_info($name)
	{
		if ( isset(self::$plugins_pool[$name]) )
		{
			return self::$plugins_pool[$name]['plugin_info'];
		}
		else
		{
			return FALSE;
		}
	}

	public function print_plugins()
	{
		return self::$plugins_pool;
	}

	public function set_plugin_dir($path)
	{
		if ( !empty($path) )
		{
			self::$plugins_dir = trim($path);
		}
	}

	/**
	 * Add Action
	 *
	 * Add a new hook trigger action
	 *
	 * @param mixed $name
	 * @param mixed $function
	 * @param mixed $priority
	 */
	public function add_action($name, $function, $priority=10)
	{
		// If we have already registered this action return true
		if ( isset(self::$actions[$name][$priority][$function]) )
		{
			return true;
		}

		/**
		 * Allows us to iterate through multiple action hooks.
		 */
		if ( is_array($name) )
		{
			foreach ($name AS $name)
			{
				// Store the action hook in the $hooks array
				self::$actions[$name][$priority][$function] = array("function" => $function);
			}
		}
		else
		{
			// Store the action hook in the $hooks array
			self::$actions[$name][$priority][$function] = array("function" => $function);
		}

		return true;
	}

	/**
	 * Do Action
	 *
	 * Trigger an action for a particular action hook
	 *
	 * @param mixed $name
	 * @param mixed $arguments
	 * @return mixed
	 */
	public function do_action($name, $arguments = "")
	{
		// Oh, no you didn't. Are you trying to run an action hook that doesn't exist?
		if ( !isset(self::$actions[$name]) )
		{
			return $arguments;
		}

		// Set the current running hook to this
		self::$current_action = $name;

		// Key sort our action hooks
		ksort(self::$actions[$name]);

		foreach(self::$actions[$name] AS $priority => $names)
		{
			if ( is_array($names) )
			{
				foreach($names AS $name)
				{
					// This line runs our function and stores the result in a variable
					$returnargs = call_user_func_array($name['function'], array(&$arguments));

					if ($returnargs)
					{
						$arguments = $returnargs;
					}

					// Store our run hooks in the hooks history array
					self::$run_actions[$name][$priority];
				}
			}
		}

		// No hook is running any more
		self::$current_action = '';

		return $arguments;
	}

	/**
	 * Remove Action
	 *
	 * Remove an action hook. No more needs to be said.
	 *
	 * @param mixed $name
	 * @param mixed $function
	 * @param mixed $priority
	 */
	public function remove_action($name, $function, $priority=10)
	{
		// If the action hook doesn't, just return true
		if ( !isset(self::$actions[$name][$priority][$function]) )
		{
			return true;
		}

		// Remove the action hook from our hooks array
		unset(self::$actions[$name][$priority][$function]);
	}

	/**
	 * Current Action
	 *
	 * Get the currently running action hook
	 *
	 * @return Action
	 */
	public function current_action()
	{
		return self::$current_action;
	}

	/**
	 * Has Run
	 *
	 * Check if a particular hook has been run
	 *
	 * @param mixed $hook
	 * @param mixed $priority
	 */
	public function has_run($action, $priority = 10)
	{
		if ( isset(self::$actions[$action][$priority]) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Action Exists
	 *
	 * Does a particular action hook even exist?
	 *
	 * @param mixed $name
	 */
	public function action_exists($name)
	{
		if ( isset(self::$actions[$name]) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Will print our information about all plugins and actions
	 * neatly presented to the user.
	 *
	 */
	public static function debug_class()
	{
		echo 'Plugins Directory: '.self::$plugins_dir;

		if ( isset(self::$plugins_pool) )
		{
			echo "<h2>Found plugins</h2>";
			echo "<p>All plugins found in the plugins directory.</p>";
			echo "<pre>";
			print_r(self::$plugins_pool);
			echo "</pre>";
			echo "<br />";
			echo "<br />";
		}

		if ( isset(self::$plugins_active) )
		{
			echo "<h2>Activated plugins</h2>";
			echo "<p>Activated plugins that have already been included and are usable.</p>";
			echo "<pre>";
			print_r(self::$plugins_active);
			echo "</pre>";
			echo "<br />";
			echo "<br />";
		}

		if ( isset(self::$actions) )
		{
			echo "<h2>Register action hooks</h2>";
			echo "<p>Action hooks that have been registered by the application and can be called via plugin files.</p>";
			echo "<pre>";
			print_r(self::$actions);
			echo "</pre>";
			echo "<br />";
			echo "<br />";
		}

		if ( isset(self::$run_actions) )
		{
			echo "<h2>Previously run action hooks</h2>";
			echo "<p>Hooks that have been called previously.</p>";
			echo "<pre>";
			print_r(self::$run_actions);
			echo "</pre>";
			echo "<br />";
			echo "<br />";
		}
	}
}
