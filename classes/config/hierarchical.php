<?php defined('SYSPATH') or die('No direct script access.');
/**
 * File-based hierarchical configuration reader. 
 *
 * @package    Kohana
 * @category   Configuration
 * @author     Kohana Team and Ryder Ross
 * @copyright  (c) 2009-2011 Kohana Team and Ryder Ross
 * @license    http://kohanaframework.org/license
 */
class Config_Hierarchical extends Config_Reader {

	protected $_env_map = array(
		1 => 'production',
		2 => 'staging',
		3 => 'testing',
		4 => 'development',
	);

	/**
	 * @var  string  Configuration group name
	 */
	protected $_configuration_group;

	/**
	 * @var string subdirectory to pull configs from
	 */
	protected $_subdirectory = NULL;

	/**
	 * @var  bool  Has the config group changed?
	 */
	protected $_configuration_modified = FALSE;


	public function __construct($directory = 'config', $env = NULL)
	{
		// Set the configuration directory name
		$this->_directory = trim($directory, '/');
		
		// Determine the other directory to check for configs
		if ($env != NULL)
		{
			$this->_subdirectory = 'env_'.$env;
		}
		else
		{
			if (is_string(Kohana::$environment))
			{
				$this->_subdirectory = 'env_'.Kohana::$environment;
			}
			else if (isset($this->_env_map[Kohana::$environment]))
			{
				$this->_subdirectory = 'env_'.$this->_env_map[Kohana::$environment];
			}
		}

		// Load the empty array
		parent::__construct();
	}
	
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
			
			if ($this->_subdirectory && $files = Kohana::find_file($this->_directory.DIRECTORY_SEPARATOR.$this->_subdirectory, $group, NULL, TRUE))
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

