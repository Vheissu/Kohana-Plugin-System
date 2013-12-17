<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Import data structure to manage installed plugins.
 *
 * @author happyDemon <maxim.kerstens@gmail.com>
 */
class Task_Plugins extends Minion_Task
{

	protected $_options = array('manager' => 'db');

	public function build_validation(Validation $validation)
	{
		return parent::build_validation($validation)
			->rule('manager', 'in_array', array(':value', array('config', 'db')));
	}

	/**
	 * Truncate local database and import everything from the remote
	 *
	 * @param array $params CLI params
	 * @throws Minion_Exception
	 * @return null
	 */
	protected function _execute(array $params)
	{
		switch($params['manager']) {
			case 'config':
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
			case 'db':
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

		Minion_CLI::write('Installation ' . Minion_CLI::color('completed', 'green') . 'for your '.$params['manager'].' manager');
	}
}