<?php defined('SYSPATH') or die('No direct script access.');

class Kohana extends Kohana_Core {
	
	public static function init(array $settings = NULL)
	{
		parent::init($settings);
		
		// Reload the config (for kohana versions less than 3.1)
		Kohana::$config = Config::instance();
	}
}

