Kohana Plugin System
====================

A fork of a library I wrote for Codeigniter, largely untouched or changed, just updated to be a module that can be used in Kohana 3.3.

Kohana Plugin System allows you to create Kohana web applications that use hooks and allow your users to extend. It is an events system on one hand and a plugin library on the other. Currently plugins have config files, but in an ideal app you would probably use a database like Wordpress does.  Sample plugin "kmarkdown" in the plugins folder.

Simply add the folder to your Kohana modules directory, enable it in your bootstrap and that's it. Take a look inside of the module, the "plugins" directory has all of the plugins, but you can move this. Just make sure you update the path in the config file to reflect the new plugins location.

Take a look at the example plugin to see how it works. But much like the modules system in Kohana, each plugin has an init.php file which allows you to bootstrap your plugin.
