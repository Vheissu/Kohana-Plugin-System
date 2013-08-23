<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Import data from the legacy system
 *
 * The import is destructive, the existing database will be truncated.
 *
 * @author Ando Roots <ando@sqroot.eu>
 */
class Task_Plugins extends Minion_Task
{
	/**
	 * Truncate local database and import everything from the remote
	 *
	 * @param array $params CLI params
	 * @throws Minion_Exception
	 * @return null
	 */
	protected function _execute(array $params)
	{
		$manager = Minion_CLI::read('Which manager do you want to install?', array('Config', 'DB'));

		switch($manager) {
			case 'Config':
				$config_path = APPPATH.'config'.DIRECTORY_SEPARATOR.Kohana::$config->load('plugins.manager.Config.dir');
				if(!file_exists($config_path))
				{
					Minion_CLI::write('Creating plugin config folder');
					mkdir($config_path);
					$config_tpl = "<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(

);";
					Minion_CLI::write('Creating plugin status & info config files');
					file_put_contents($config_path.DIRECTORY_SEPARATOR.'active.php', $config_tpl);
					file_put_contents($config_path.DIRECTORY_SEPARATOR.'list.php', $config_tpl);
				}
				break;
			case 'DB':
				$db = Database::instance(Kohana::$config->load('plugins.manager.DB.connection'));
				Minion_CLI::write('Dumping SQL into database');
				$db->query(null, "CREATE TABLE IF NOT EXISTS `".Kohana::$config->load('plugins.manager.DB.table')."` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `name` varchar(65) NOT NULL,
					  `installed` tinyint(1) NOT NULL,
					  `active` tinyint(1) NOT NULL,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `name` (`name`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
				break;
		}

		Minion_CLI::write('Installation ' . Minion_CLI::color('completed', 'green') . 'for your '.$manager.' manager');
	}
}