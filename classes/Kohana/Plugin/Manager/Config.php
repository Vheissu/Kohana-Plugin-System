<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohana_Plugin_Manager_Config extends Plugin_Manager
{
	protected $_config_path = null;

	protected $_inst = null;

	public function __construct($config) {
		//initiate the config instance we'll be using
		$this->_inst = new Config;

		$this->_config_path = 'config'.DIRECTORY_SEPARATOR.$config['dir'];

		//configure the config reader
		$this->_inst->attach(new Kohana_Config_File_Reader($this->_config_path));

		// We'll be storing the config in APPPATH, make sure the folder exists there
		if(!file_exists(APPPATH.$this->_config_path))
		{
			mkdir(APPPATH.$this->_config_path);
		}
	}

	public function get_active()
	{
		return $this->_inst->load('active')->as_array();
	}

	public function activate($plugin)
	{
		$listed = $this->_inst->load('list')->get($plugin, false);

		if($listed == false)
		{
			//seems like the plugin you want activated hasn't been listed yet
			throw new Kohana_Exception('The plugin ":plugin" you\'re trying to activate hasn\'t been listed yet', array(':plugin' => $plugin));
		}
		else {
			//if the plugin hasn't been installed return false
			if($listed['installed'] == false)
			{
				return false;
			}

			//get a list of active plugins
			$activated_plugins = $this->_inst->load('active');

			//see if it's already in there
			if(!in_array($plugin, $activated_plugins))
			{
				//add the plugin
				$activated_plugins[] = $plugin;

				//save the config file
				$activated_plugins->export(APPPATH.$this->_config_path);

				return true;
			}
		}
	}

	public function deactivate($plugin)
	{
		$listed = $this->_inst->load('list')->get($plugin, false);

		if($listed != false)
		{
			$activated_plugins = $this->_inst->load('active');

			$key = array_search($plugin, $activated_plugins->as_array());

			//if the plugin is active
			if($key !== false)
			{
				//remove it
				unset($activated_plugins[$key]);

				//save the config file
				$activated_plugins->export(APPPATH.$this->_config_path);
			}
		}

		return true;
	}

	public function add($plugin, $installed=false)
	{
		$list = $this->_inst->load('list');

		//check if the plugin's already been listed
		if($list->get($plugin, false) == false)
		{
			//add the plugin
			$list->set($plugin, array('installed' => $installed));

			//save the config file
			$list->export(APPPATH.$this->_config_path);

			return true;
		}

		//the plugin has already been listed
		return false;
	}

	/**
	 * Standardise the output of a plugin's description.
	 *
	 * @param $plugin Name
	 * @param array $list The plugin's LIST config value (array('installed' => boolean))
	 * @param array $activated_plugins
	 * @return array
	 */
	protected function _plugin_description($plugin, $list=null, $activated_plugins=null)
	{
		if($list == null)
		{
			$list = $this->_inst->load('list')->get($plugin, false);
		}

		if($activated_plugins == null)
		{
			$activated_plugins = $this->_inst->load('active')->as_array();
		}

		return array(
			'name' => $plugin,
			'installed' => $list['installed'],
			'active' => in_array($plugin, $activated_plugins)
		);
	}

	public function get($plugin)
	{
		$listed = $this->_inst->load('list')->get($plugin, false);

		if($listed != false)
		{
			return $this->_plugin_description($plugin, $listed);
		}

		return false;
	}

	public function get_all()
	{
		$plugin_list = $this->_inst->load('list')->as_array();
		$activated = $this->_inst->load('active')->as_array();
		$list = array();

		foreach($plugin_list as $name => $description) {
			$list[] = $this->_plugin_description($name, $description, $activated);
		}

		return $list;
	}

	public function install($plugin)
	{
		$list = $this->_inst->load('list');
		$listed = $list->get($plugin, false);

		if($listed != false)
		{
			$list->set($plugin, array('installed' => true))
				->export(APPPATH.$this->_config_path);

			return true;
		}

		return false;
	}

	public function is_installed($plugin)
	{
		$listed = $this->_inst->load('list')->get($plugin, false);

		if($listed != false)
		{
			return $listed['installed'];
		}

		return false;
	}
}
