Kohana Plugin System
====================

This started out as fork of a library I wrote for Codeigniter that could be used in Kohana 3.3.

At the moment this module exists of an event(hook) system (borrowed from Laravel 3 and renamed to Plug) and a plugin manager.

## Installation
1. [Download](https://github.com/Vheissu/Kohana-Plugin-System/archive/master.zip) the zip containing this module
2. Unpack it in your modules folder
3. Open up your ```bootstrap.php``` and enable the module:  ``` 'Kohana-Plugin-System' => MODPATH.'Kohana-Plugin-System'```
4. copy the config file stored in ```MODPATH.Kohana-Plugin-System/config/plugins.php``` to your ```APPPATH.config``` folder and change anything you want **(do not change manager.loader to DB yet, it will mess up your installation)**

Before going further with the installation you'll have to know that if you want to make use of the 
config-based plugin manager you'll have to repeat the first 3 steps with 
[happyDemon/arr](https://github.com/happyDemon/arr), which is used for saving config files. 
This is not needed if you're going to be using the DB-based manager.

Open up your command line, *cd* into your app's folder and run: ```php minion plugins```, it will ask you which manager you want 
to run the installation process for.

Lastly change the manager.loader value (if needed) in your config file.

Alrighty, you've set everything up perfectly, we've bundled an examplary plugin manager which you can visit at ```http://{your app's url}/plugins```

## Documentation

An examplary plugin is bundled with this module to get you started, along with a userguide.

## Authors

- [Dwayne Charrington](https://github.com/Vheissu/)
- [Maxim Kerstens](https://github.com/happyDemon/)
