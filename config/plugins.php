<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
    "dir" => "plugins" . DIRECTORY_SEPARATOR,
	"manager" => array(
		"loader" => "Config", //Which manager will you use to load plugins (Config and DB are bundled)

		"Config" => array(
			"dir" => 'plugins' //2 config files will be stored in APPPATH.'config/{dir}' no trailing slash
		),
		"DB" => array(
			"connection" => "default",
			"table" => "plugins"
		)
	)

);
