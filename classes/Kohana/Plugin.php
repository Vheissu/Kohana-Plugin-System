<?php defined('SYSPATH') OR die('No direct access allowed.');

abstract class Kohana_Plugin
{
	/**
	 * As a plugin author you're required to set this info yourself.
	 *
	 * @var array Plugin meta
	 */
	public $info = array(
		'name'        => '',
		'description' => '',
		'author'      => '',
		'author_URL'  => '',
		'version'     => ''
	);

	/**
	 * If a config.php file is bundled with the plugin,
	 * it'll be loaded in here.
	 *
	 * @var array
	 */
	protected $_config = array();

	/**
	 * @var null|string Name of the plugin
	 */
	protected $_name = null;

	public function __construct($name, $config) {
		$this->_name = $name;
		$this->_config = $config;
	}

	/**
	 * Only run once, when installing the plugin.
	 *
	 * Overwrite this if needed.
	 *
	 * @return bool
	 */
	protected function _install()
	{
		return true;
	}

	/**
	 * Install a plugin if possible.
	 *
	 * @return bool
	 * @throws Kohana_Exception
	 */
	final public function install() {
		if(Plugins::$manager->is_installed($this->_name))
		{
			throw new Kohana_Exception('The plugin ":plugin" is already installed.', array(':plugin' => $this->info['name']));
		}

		if($this->_install() == true)
		{
			return Plugins::$manager->install($this->_name);
		}

		return false;
	}

	/**
	 * Run when initialising the plugin when it's active.
	 *
	 * @return bool
	 */
	abstract protected function _init();

	/**
	 * Store the name of events you want registered with this plugin.
	 *
	 * Examples:
	 *  [
	 *      'message.parse',            // the event message.parse will listen the method called on_message_parse
	 *      'parse' => 'message.parse   // the event message.parse will listen the method called on_parse
	 *  ]
	 *
	 * @var array
	 */
	protected $_events = array();

	/**
	 * Initialise the plugin and register all events that were defined in $this->_events.
	 *
	 * @return Kohana_Plugin
	 * @throws Kohana_Exception
	 */
	final public function init() {
		if($this->_init() == true) {
			if(count($this->_events) > 0)
			{
				foreach($this->_events as $id => $event)
				{
					$class_event = (Valid::digit($id)) ? str_replace('.', '_', $event) : $id;

					Plug::listen($event, array($this, 'on_'.$class_event));
				}
			}

			return $this;
		}
		else
		{
			throw new Kohana_Exception('Failed to initialise the ":plugin" plugin', array(':plugin' => $this->info['name']));
		}
	}
}
