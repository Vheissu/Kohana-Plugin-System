<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Plugins extends Controller {

	protected $_tpl = null;

	public function before()
	{
		$this->_tpl = View::factory('plugins/template');
	}
	public function action_index($msg = array())
	{
		$list = array();

		foreach(Plugins::$plugins_pool as $plugin_name => $data) {
			$return = array('name' => $plugin_name);

			Plugins::instance()->load_plugin($plugin_name);
			$plugin = Plugins::$plugins_pool[$plugin_name];

			$return['info'] = $plugin['instance']->info;

			$manager = Plugins::$manager->get($plugin_name);
			if($manager == false)
			{
				//add the plugin to the list
				Plugins::$manager->add($plugin_name);
			}

			$return['active'] = ($manager == false) ? false : $manager['active'];
			$return['installed'] = ($manager == false) ? false : $manager['installed'];

			$list[] = $return;
		}

		$this->_tpl->content = View::factory('plugins/list', array('list' => $list, 'msg' => $msg));
	}

	public function action_install() {
		$plugin = $this->request->param('plugin');
		$msg = array('title' => 'Install');

		if(array_key_exists($plugin, Plugins::$plugins_pool))
		{
			$plug = Plugins::instance()->load_plugin($plugin);

			if(Plugins::$manager->is_installed($plugin) == true)
			{
				$msg['class'] = 'alert-warning';
				$msg['content'] = $plug->info['name'].' is already installed';
			}
			else if(Plugins::$manager->install($plugin))
			{
				$msg['class'] = 'alert-success';
				$msg['content'] = $plug->info['name'].' was successfully installed.';
			}
			else
			{
				$msg['class'] = 'alert-warning';
				$msg['content'] = 'There was an error installing '.$plug->info['name'];
			}
		}
		else
		{
			$msg['class'] = 'alert-danger';
			$msg['content'] = $plugin.' does not seem to exist.';
		}

		$this->action_index($msg);
	}

	public function action_activate() {
		$plugin = $this->request->param('plugin');
		$msg = array('title' => 'Activation');

		if(array_key_exists($plugin, Plugins::$plugins_pool))
		{
			$plug = Plugins::instance()->load_plugin($plugin);

			if(Plugins::$manager->is_active($plugin) == true)
			{
				$msg['class'] = 'alert-warning';
				$msg['content'] = $plug->info['name'].' is already active';
			}
			else if(Plugins::$manager->activate($plugin))
			{
				$msg['class'] = 'alert-success';
				$msg['content'] = $plug->info['name'].' was successfully activated.';
			}
			else
			{
				$msg['class'] = 'alert-warning';
				$msg['content'] = 'There was an error activating '.$plug->info['name'];
			}
		}
		else
		{
			$msg['class'] = 'alert-danger';
			$msg['content'] = $plugin.' does not seem to exist.';
		}

		$this->action_index($msg);
	}

	public function action_deactivate() {
		$plugin = $this->request->param('plugin');
		$msg = array('title' => 'Deactivation');

		if(array_key_exists($plugin, Plugins::$plugins_pool))
		{
			$plug = Plugins::instance()->load_plugin($plugin);

			if(Plugins::$manager->is_active($plugin) == false)
			{
				$msg['class'] = 'alert-warning';
				$msg['content'] = $plug->info['name'].' is not active';
			}
			else if(Plugins::$manager->deactivate($plugin))
			{
				$msg['class'] = 'alert-success';
				$msg['content'] = $plug->info['name'].' was successfully deactivated.';
			}
			else
			{
				$msg['class'] = 'alert-warning';
				$msg['content'] = 'There was an error deactivating '.$plug->info['name'];
			}
		}
		else
		{
			$msg['class'] = 'alert-danger';
			$msg['content'] = $plugin.' does not seem to exist.';
		}

		$this->action_index($msg);
	}

	public function after()
	{
		$this->response->body($this->_tpl);
	}

} // End Plugins
