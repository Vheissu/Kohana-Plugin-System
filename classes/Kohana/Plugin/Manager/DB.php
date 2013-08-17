<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohana_Plugin_Manager_DB extends Plugin_Manager
{
	protected $_connection = 'default';
	protected $_table = 'plugins';

	public function __construct($config) {
		$this->_connection = $config['connection'];
		$this->_table = $config['table'];
	}

	public function get_active()
	{
		$query = DB::select('name')
			->from($this->_table)
			->where('active', '=', 1)
			->execute($this->_connection);

		return array_keys($query->as_array('name'));
	}

	/**
	 * Check if the plugin has been listed.
	 *
	 * @param $plugin string Name of a plugin
	 * @return integer
	 */
	protected function _exists($plugin) {
		return DB::select(array(DB::expr('COUNT(`name`)'), 'plugin'))
			->from($this->_table)
			->where('name', '=', $plugin)
			->execute($this->_connection)
			->get('plugin', 0);
	}
	public function activate($plugin)
	{
		if($this->_exists($plugin) == 0) {
			//seems like the plugin you want activated hasn't been listed yet
			throw new Kohana_Exception('The plugin ":plugin" you\'re trying to activate hasn\'t been listed yet', array(':plugin' => $plugin));
		}
		else
		{
			$installed = DB::select('installed')
				->from($this->_table)
				->where('name', '=', $plugin)
				->execute($this->_connection)
				->get('installed');

			//if the module hasn't been installed don't activate it
			if($installed == 0)
			{
				return false;
			}

			DB::update($this->_table)
				->set(array('active' => 1))
				->where('name', '=', $plugin)
				->execute($this->_connection);

			return true;
		}
	}

	public function deactivate($plugin)
	{
		if($this->_exists($plugin) != 0)
		{
			DB::update($this->_table)
				->set(array('active' => 0))
				->where('name', '=', $plugin)
				->execute($this->_connection);
		}

		return true;
	}

	public function add($plugin, $installed=false)
	{
		if($this->_exists($plugin) == 0)
		{
			DB::insert($this->_table)
				->values(array('name' => $plugin, 'installed' => $installed, 'active' => 0))
				->execute($this->_connection);

			return true;
		}

		return false;
	}

	public function get($plugin)
	{
		return DB::select(array('name', 'installed', 'active'))
			->from($this->_table)
			->where('name', '=', $plugin)
			->execute($this->_connection)
			->as_array();
	}

	public function get_all()
	{
		return DB::select(array('name', 'installed', 'active'))
			->from($this->_table)
			->execute($this->_connection)
			->as_array();
	}

	public function install($plugin)
	{
		if($this->_exists($plugin))
		{
			DB::update($this->_table)
				->set(array('installed', '=', 1))
				->where('name', '=', $plugin)
				->execute($this->_connection);

			return true;
		}

		return false;
	}

	public function is_installed($plugin)
	{
		if($this->_exists($plugin))
		{
			return (bool) DB::select('installed')
				->from($this->_table)
				->where('name', '=', $plugin)
				->execute($this->_connection)
				->get('installed', 0);
		}

		return false;
	}
}
