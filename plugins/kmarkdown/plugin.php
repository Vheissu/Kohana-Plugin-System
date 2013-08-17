<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kmarkdown extends Plugin
{
	/**
	 * @var array Plugin meta
	 */
	public $info = array(
		'name'        => 'Kohana Markdown',
		'description' => 'A simple plugin for compiling Markdown',
		'author'      => 'Dwayne Charrington',
		'author_URL'  => 'http://ilikekillnerds.com'
	);

	/**
	 * Run when initialising the plugin when it's active.
	 */
	public function init() {
		include_once 'vendor/markdown.php';
	}

	protected $_events = array('parse.message');

	/**
	 * when the event parse.message is called, run this method.
	 *
	 * @param $message string Message to parse
	 * @return string Compiled markdown
	 */
	public function on_parse_message($message) {
		$markdown_value = Markdown($message);
		return $markdown_value;
	}

}
