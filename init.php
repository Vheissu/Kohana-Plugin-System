<?php defined('SYSPATH') or die('No direct script access.');

// Load our plugins module, get the party started
Plugins::instance();

Route::set('plugins.index', 'plugins')
	->defaults(array(
	'controller' => 'Plugins',
	'action'     => 'index',
));
Route::set('plugins.install', 'plugins/<plugin>/install')
	->defaults(array(
	'controller' => 'Plugins',
	'action'     => 'install',
));
Route::set('plugins.activate', 'plugins/<plugin>/activate')
	->defaults(array(
	'controller' => 'Plugins',
	'action'     => 'activate',
));
Route::set('plugins.deactivate', 'plugins/<plugin>/deactivate')
	->defaults(array(
	'controller' => 'Plugins',
	'action'     => 'deactivate',
));