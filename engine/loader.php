<?php
class Loader
{
	private static $_db_connection;
	
	public function __construct()
	{
		// Set the models path
		$this->_model_path	= BASEPATH.'app/models/';
		
		// Just for reporting that you've accessed the constructor
		echo '<pre>Initialize '.(__CLASS__).' class</pre>';
	}
	
	public function model($_model = '')
	{
		/**
		 * Checking the parameter in array format
		 * This allow you to load model class in controller like:
		 * 		$this->load->model(array('model_1','model_2'));
		 */
		if (is_array($_model) AND count($_model) != 0)
		{
			foreach ($_model as $model)
			{
				$this->model($model);
			}
		}
		else
		{
			/**
			 * Remind that you set the class file using lowercase and the class name using
			 * uppercase in first class name
			 */
			$model_file	= strtolower($_model);
			$filename	= $this->_model_path . $model_file .'.php';
			
			// Checking for existing class file
			if (file_exists($filename))
			{
				require_once($filename);
				
				/**
				 * What is this?
				 * We've instance the parent controller class before so we do not need to
				 * instance again. Just use the object controller which is instance.
				 * For what? Just see line 5 of this file
				 * This will allow you to use statement like:
				 * 		$this->[model_name:using lowercase]->[model_method]();
				 */
				$OBJ	=& get_object_controller();	// @see	../index.php
				
				// Set the model class name in ucwords format
				$model_class	= ucwords($model_file);
				
				// Hey.. this method for loading models, so you have to open the database connection
				$this->db_connect();
				
				// Instance the model class using parent controller object
				$OBJ->$model_file	= new $model_class();
				
				return $OBJ->$model_file;
				
			}
			die('<pre>Unable to find: '. $filename.'</pre>');
		}
		
	}
	
	public function db_connect()
	{
		// Set the config file path
		$config_file	= BASEPATH.'app/config/config.php';
		
		// Checking for existing config file
		if (file_exists($config_file))
		{
			/**
			 * You've found the config file so insert it by using include() function
			 * Or if you're still do not know the difference between require() and include()
			 * function, you can try to use require() function
			 */
			include($config_file);
			
			/**
			 * Start connect to database server and selecting database name by using
			 * the configuration datas in config file
			 */
			self::$_db_connection	= mysql_connect($config['db_host'], $config['db_user'], $config['db_pass']) or die(mysql_error());
			mysql_select_db($config['db_name'], self::$_db_connection);
		}
	}
	
	public function db_close()
	{
		mysql_close(self::$_db_connection);
	}
}
?>
