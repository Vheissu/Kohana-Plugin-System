# Config file

Allowing for configurability is something you might want your plugin to offer.

You can do this by adding a config.php file in your plugin's folder in the same format as you would a normal Kohana config file.

The array of your config file will be loaded into your plugin's class under the protected variable ```$_config```, making it available to all the events stored in your plugin class.