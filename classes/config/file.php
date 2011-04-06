<?php defined('SYSPATH') or die('No direct script access.');

class Config_File extends Kohana_Config_Reader {

	protected $_env_map = array(
		1 => 'production',
		2 => 'staging',
		3 => 'testing',
		4 => 'development',
	);

	public function load($group, array $config = NULL)
	{
		if ($files = Kohana::find_file($this->_directory, $group, NULL, TRUE))
		{
			// Initialize the config array
			$config = array();

			foreach ($files as $file)
			{
				// Merge each file to the configuration array
				$config = Arr::merge($config, Kohana::load($file));
			}

			$sub_dir = FALSE;
			if (isset($this->_env_map[Kohana::$environment])) {				
				$sub_dir = DIRECTORY_SEPARATOR.$this->_env_map[Kohana::$environment];
			} else if (is_string(Kohana::$environment)) {
				$sub_dir = DIRECTORY_SEPARATOR.Kohana::$environment;
			}
			
			if ($sub_dir && $files = Kohana::find_file($this->_directory.$sub_dir, $group, NULL, TRUE))
			{
				foreach ($files as $file)
				{
					// Merge each file to the configuration array
					$config = Arr::merge($config, Kohana::load($file));
				}
			}
		}

		return parent::load($group, $config);
	}

}
