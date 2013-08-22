<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohana_Plugins
{
	// Plugins
	public static $plugins_pool;
	public static $plugins_dir;

	//Manage your plugins' status
	public static $manager = null;

	/**
	 * Get the Plugins instance
	 *
	 * @param array $params Any parameters you want to pass on the the constructor
	 * @return Plugins
	 */
	public static function instance($params = array())
	{
		static $instance = null;

		if($instance == null)
		{
			$instance = new Plugins($params);
		}

		return $instance;
	}

	
	public function __construct($params = array())
	{
		$config = Kohana::$config->load('plugins');

		self::$plugins_dir = trim((array_key_exists("plugins_dir", $params)) ? $params['plugins_dir'] : $config->get("dir"));

		$manager = $config->get('manager');
		self::$manager = Plugin_Manager::factory($manager['loader'], $manager[$manager['loader']]);

		// Check for plugins and initialise all active ones
		$this->find_plugins()
			->include_plugins();
	}

	/**
	 * Find all plugins defined in your Kohana codebase
	 */
	public function find_plugins()
	{
		$paths = Kohana::include_paths();

		foreach($paths as $path)
		{
			$dir = $path . self::$plugins_dir;

			//if there's no plugin dir skip to the next path
			if(!file_exists($dir))
			{
				continue;
			}

			// Instantiate a new directory iterator object
			$directory = new DirectoryIterator($dir);

			if ($directory->isDir())
			{
				foreach ($directory AS $plugin)
				{
					// Is directory?
					if ( $plugin->isDir() && !$plugin->isDot() )
					{
						// if there's no plugin.php in this folder, ignore it
						if(!file_exists($plugin->getPath().DIRECTORY_SEPARATOR.'plugin.php'))
						{
							continue;
						}

						// Store plugin in our pool
						self::$plugins_pool[ $plugin->getFilename() ]['plugin'] = ucfirst($plugin->getFilename());
						self::$plugins_pool[ $plugin->getFilename() ]['path'] = $dir;
					}
				}
			}
		}

		return $this;
	}

	/**
	 * Load a plugin and return its instance.
	 *
	 * @param $plugin name of the plugin.
	 * @return Plugin
	 */
	public function load_plugin($plugin) {
		if(!isset(self::$plugins_pool[$plugin]['instance']))
		{
			$plugin_path = self::$plugins_pool[$plugin]['path'] . $plugin . DIRECTORY_SEPARATOR;

			// Include plugin config options
			$config_file = $plugin_path . "config.php";
			$config = (file_exists($config_file)) ? include_once $config_file : array();

			$inst = new self::$plugins_pool[$plugin]['plugin']($plugin, $config);

			if(!is_a($inst, 'Plugin'))
			{
				throw new Kohana_Exception('The plugin definition of ":plugin" is invalid', array(':plugin' => $plugin));
			}

			//load the plugin class
			self::$plugins_pool[$plugin]['instance'] = $inst;
		}

		return self::$plugins_pool[$plugin]['instance'];
	}

	/**
	 * Initialise all active plugins
	 */
	public function include_plugins()
	{
		$active = self::$manager->get_active();

		if(count($active) > 0)
		{
			foreach ($active AS $name)
			{
				$plugin = $this->load_plugin($name);
				$plugin->init();
			}
		}

		return $this;
	}

	/**
	 * Get meta info on a plugin
	 *
	 * @param $name
	 * @return array|false
	 */
	public function plugin_info($name)
	{
		return ( isset(self::$plugins_pool[$name]) ) ? self::$plugins_pool[$name]['instance']->info : FALSE;
	}

	/**
	 * Return the status of a plugin [name, installed, active]
	 *
	 * @param $name string Name of the plugin
	 * @return null|array
	 */
	public function plugin_status($name)
	{
		return ( isset(self::$plugins_pool[$name]) ) ? self::$manager->get($name) : NULL;
	}
}
