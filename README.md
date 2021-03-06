Kohana Plugin System
====================

This started out as fork of a library I wrote for Codeigniter that could be used in Kohana 3.3.

At the moment this module exists of an event(hook) system (borrowed from Laravel 3 and renamed to Plug) and a plugin manager.



## Download

### Composer
If you want to download this module through composer you'll have to add **vheissu/kohana-plugin-system** to your packages:

```
{
  "require":
  {
    "vheissu/kohana-plugin-system": "1.*"
  }
}
```

If you want to store your plugins' state in config files you'll also have to add **happydemon/arr** to your package list.

Next run ```composer update```

### Default

 -  [Download](https://github.com/Vheissu/Kohana-Plugin-System/archive/master.zip) the zip containing this module
 -  Unpack it in your modules folder
 
The same applies here as with composer, if you want to make store your plugins' state in config files instead of the database
you'll have to download [happyDemon/arr](https://github.com/happyDemon/arr) as well.

## Installation

3. Open up your ```bootstrap.php``` and enable the module:  ``` 'Kohana-Plugin-System' => MODPATH.'Kohana-Plugin-System'```
4. copy the config file stored in ```MODPATH.Kohana-Plugin-System/config/plugins.php``` to your ```APPPATH.config``` folder and change anything you want **(do not change manager.loader to DB yet, it will mess up your installation)**

If you've downloaded happydemon/arr, don't forget to add it on your ```bootstrap.php``` as well.
 
Open up your command line, *cd* into your app's folder and run: ```php minion plugins``` (defaults to db) or ```php minion plugins --manager=db``` or ```php minion plugins --manager=config```, to start the installation process.

Now change the ```manager.loader``` value (if needed) in your config file.

Alrighty, you've set everything up perfectly, we've bundled an **examplary plugin manager controller** which you can visit at ```http://{your app's url}/plugins```. This controller comes with no security, if you want to use it in a production environment you should create a controller called ```Controller_Plugins``` that extends ```Kohana_Controller_Plugins``` and do an auth/acl check in the ```before``` method.

## Documentation

An examplary plugin is bundled with this module to get you started, along with a userguide.

## Authors

- [Dwayne Charrington](https://github.com/Vheissu/)
- [Maxim Kerstens](https://github.com/happyDemon/)
