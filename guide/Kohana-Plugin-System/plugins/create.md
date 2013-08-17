# Create a plugin

Plugins can be stored anywhere in your Kohana filesystem (in your application folder or modules' folder) and are generally stored in a subfolder called *'plugins'* (this can be changed in the config file).

Each plugin gets their own folder, a plugin folder only requires 1 file to be defined: **plugin.php**, this file contains the plugin definition, however it can also contain a config.php file that gets loaded into the plugin definition when it's instantiated or any vendor classes you might require for your plugin (don't forget to include those in your plugin's **[init](plugins/create#init)** function

## Defining your plugin

You define your plugin by creating a file called **plugin.php** in your plugin folder. In this file we'll define a class that extends ```Plugin```, the name of the class must match your plugin folder's name with an uppercase first letter (e.g. if your plugin's folder is called *kmarkdown* you would call your class Kmarkdown).

This definition would look like this:

		<?php defined('SYSPATH') OR die('No direct access allowed.');

		class Kmarkdown extends Plugin
		{

		}
## Meta information

When creating your plugin you'll want to set the meta information that describes your plugin, you do this by adding a public variable to your plugin's class called ```$info```:

		public $info = array(
			'name'        => '',
			'description' => '',
			'author'      => '',
			'author_URL'  => '',
			'version'     => ''
		);

## Init

Your plugin class requires to have a public init() method that's called when this plugin get initialised, you can include any classes you need in this function or setup anything you need during events.

For example, the kmarkdown plugin has a vendor class bundled, which we would need included for our event to run:

		public function init() {
			include_once 'vendor/markdown.php';
		}

If you don't have a need for one, keep it empty. This method does not need to return anything.

## Registering events

Registered events are always mapped to methods of your plugin class that are prefixed with *on_* (message.parse would be mapped to on_message_parse).

You register events by defining them in a protected variable called ```$_events```, this way we know which events to assign a callback.

There are 2 ways to do this:

	protected $_events = array(
		'parse.message', //the event parse.message will get the method on_parse_message assigned as callback
		'parse' => 'parse.message' //the event parse.message will get the method on_parse assigned as callback
	);

As you can see, if you define a key, that key will be used as the name for the event's callback method.